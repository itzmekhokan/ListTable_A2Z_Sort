<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'List_Table_A2Z_Admin' ) ) :

	/**
	 * List_Table_A2Z_Admin.
	 *
	 * Backend Event Handler.
	 *
	 * @class    List_Table_A2Z_Admin
	 * @package  List_Table_A2Z/Classes
	 * @category Class
	 * @author   khokansardar
	 */
	class List_Table_A2Z_Admin {

		/**
		 * WP Core List Table classes by screen.
		 */
		public $core_classes_by_screen = null;

		/**
		 * All settings enabled screen.
		 */
		public $enabled_screen = null;

		/**
		 * Constructor for the admin class. Hooks in methods.
		 */
		public function __construct() {
			$this->includes();
			$this->set_az_settings_enabled_screen();
			$this->core_classes_by_screen = $this->get_wp_list_table_classes_by_screen();

			add_action( 'current_screen', array( $this, 'lta2z_current_screen' ) );
			//add_filter( 'plugin_action_links_' . LTA2Z_BASENAME, array( $this, 'lta2z_plugin_action_links' ) );

			add_action( 'pre_get_posts', array( $this, 'lta2z_filter_pre_get_posts' ) );
			add_action( 'pre_get_users', array( $this, 'lta2z_filter_pre_get_users' ) );
			add_filter( 'posts_where', array( $this, 'lta2z_posts_where' ), 99, 2 );

		}

		/**
		 * Includes files.
		 *
		 */
		public function includes() {
			include_once LTA2Z_ABSPATH . 'includes/admin/settings/class-lta2z-settings.php';
		}

		/**
		 * Set enabled screen from active settings.
		 *
		 * @return void null.
		 */
		public function set_az_settings_enabled_screen() {
			$this->enabled_screen = array();
			
			if ( lta2z_get_options( 'enabled_userlist' ) ) {
				$this->enabled_screen[] = 'users';
			}
			$enabled_post_types = (array) lta2z_get_options( 'enabled_post_types' );

			foreach ( $enabled_post_types as $type ) {
				$this->enabled_screen[] = 'edit-' . $type;
			}

			return $this->enabled_screen;
		}

		/**
		 * Set post query via filter key start_with.
		 *
		 * @param object $query Post Query object.
		 */
		public function lta2z_filter_pre_get_posts( $query ) {
			global $post_type;

			$has_start_with = isset( $_GET['start_with'] ) ? sanitize_text_field( wp_unslash( $_GET['start_with'] ) ) : '';

			if ( ! $has_start_with ) {
				return $query;
			}

			if ( $query->is_main_query() ) {
				$query->set( 'starts_with', $has_start_with );
			}
		}

		/**
		 * Set users query via filter key start_with.
		 *
		 * @param object $query User Query object.
		 */
		public function lta2z_filter_pre_get_users( $query ) {
			global $pagenow;
			
			if ( 'users.php' !== $pagenow || ! is_admin() ) return $query;

			$has_start_with = isset( $_GET['start_with'] ) ? sanitize_text_field( wp_unslash( $_GET['start_with'] ) ) : '';

			if ( ! $has_start_with ) {
				return $query;
			}

			$query->set( 'search', esc_attr( $has_start_with ) . '*' );
			$query->set( 'search_columns', array( 'user_login', 'user_nicename' ) );
		}

		/**
		 * Modify $whare clause SQL via filter key start_with.
		 *
		 * @param string $where Where Clause SQL.
		 * @param object $query Post Query object.
		 */
		public function lta2z_posts_where( $where, $query ) {
			global $wpdb;

			$starts_with = esc_sql( $query->get( 'starts_with' ) );

			if ( $starts_with ) {
				$where .= " AND $wpdb->posts.post_title LIKE '$starts_with%'";
			}

			return $where;
		}

		/**
		 * Load A-Z list classes based on screen.
		 */
		public function lta2z_current_screen() {
			$current_screen = get_current_screen();

			if ( $this->enabled_screen && in_array( $current_screen->id, $this->enabled_screen, true ) ) {

				add_filter(
					"views_{$current_screen->id}",
					function( $views ) {
						global $wp_list_table;
						include_once LTA2Z_ABSPATH . 'includes/admin/data-table/class-a2z-admin-list-table.php';
						$a2z_list_table = new WP_A2Z_List_Table();
						$wp_list_table  = $a2z_list_table; // phpcs:ignore WordPress.WP.GlobalVariablesOverride

						return $views;
					}
				);
			}
		}

		/**
		 * Configure core list table based on screen id.
		 */
		public function get_wp_list_table_classes_by_screen() {
			$core_classes = array();
			if ( $this->enabled_screen ) {
				foreach ( $this->enabled_screen as $screen ) {
					if ( 'users' === $screen ) { // Added User List supports
						$core_classes['users'] = array(
							'class_name' => 'WP_Users_List_Table',
							'required'    => 'users',
						);
					} elseif ( 'edit-product' === $screen ) { // Added WC Product List supports
						$core_classes['edit-product'] = array(
							'class_name' => 'WC_Admin_List_Table_Products',
							'required'    => 'edit-product',
						);
					} else {
						$core_classes[$screen] = array(
							'class_name' => 'WP_Posts_List_Table',
							'required'    => 'posts',
						);
					}
				}
			}

			return $core_classes;
		}

	}

endif; // class_exists

return new List_Table_A2Z_Admin();
