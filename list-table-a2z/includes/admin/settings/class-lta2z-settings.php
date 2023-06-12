<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'List_Table_A2Z_Settings' ) ) :

	/**
	 * List_Table_A2Z_Settings.
	 *
	 * Backend Event Handler.
	 *
	 * @class    List_Table_A2Z_Settings
	 * @package  List_Table_A2Z/Classes
	 * @category Class
	 * @author   khokansardar
	 */
	class List_Table_A2Z_Settings {

		/**
		 * All settings options.
		 * 
		 * @var array
		 */
		public $options;

		/**
		 * Constructor for the settings class. Hooks in methods.
		 */
		public function __construct() {

			$this->setup_hooks();
			$this->options = (array) get_option( 'list_table_a2z_options' );
		}

		/**
		 * Function to setup hooks for Rewrite Rules.
		 *
		 * @return void
		 */
		public function setup_hooks() {
			// Actions.
			add_action( 'admin_menu', array( $this, 'add_lta2z_settings_menu' ) );
			add_action( 'admin_init', array( $this, 'add_lta2z_register_settings' ) );
		}

		/**
		 * List Table A2Z Settings Menu.
		 *
		 * @return void
		 */
		public function add_lta2z_settings_menu() {

			add_submenu_page(
				'options-general.php',
				'List Table A2Z Settings',
				'List Table A2Z Settings',
				'manage_options',
				'lta2z-settings',
				array( $this, 'lta2z_settings_html' )
			);
		}

		/**
		 * A2Z Admin Settings.
		 *
		 * @return void
		 */
		public function lta2z_settings_html() {

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( 'You do not have sufficient permissions to access this page.' );
			}

			?>
			<div class="wrap">
				<h2>List Table A2Z Settings</h2>
				<form method="post" action="options.php">
					<?php
						settings_fields( 'lta2z_settings_group' );

						do_settings_sections( 'list-table-a2z-settings' );

						submit_button();
					?>
				</form>
			</div>
			<?php
		}

		/**
		 * Function is used to register the settings.
		 *
		 * @return void
		 */
		public function add_lta2z_register_settings() {

			register_setting( 'lta2z_settings_group', 'list_table_a2z_options' );

			add_settings_section(
				'lta2z_section_global',
				'Gobal Settings', // Title.
				'',
				'list-table-a2z-settings'
			);

			add_settings_field(
				'lta2z_is_enabled',
				'Enabled List Table A2Z sort',
				array( $this, 'lta2z_is_enabled_callback' ),
				'list-table-a2z-settings',
				'lta2z_section_global'
			);

			add_settings_field(
				'enabled_post_types',
				'Enable for post types',
				array( $this, 'lta2z_enabled_post_types_callback' ),
				'list-table-a2z-settings',
				'lta2z_section_global'
			);

			add_settings_field(
				'enabled_userlist',
				'Enable for User List Table',
				array( $this, 'enabled_userlist_callback' ),
				'list-table-a2z-settings',
				'lta2z_section_global'
			);
		}

		/**
		 * Enabled A2Z settings.
		 *
		 * @return void
		 */
		public function lta2z_is_enabled_callback() {
			$checked = ( isset( $this->options['enabled'] ) ) ? checked( $this->options['enabled'], 1, false ) : '';
			echo '<input type="checkbox" id="enabled" name="list_table_a2z_options[enabled]" value="1" ' . $checked . ' />';
		}

		/**
		 * Enabled for post types settings.
		 *
		 * @return void
		 */
		public function lta2z_enabled_post_types_callback() {
			$checked = ( isset( $this->options['enabled_post_types'] ) && is_array( $this->options['enabled_post_types'] ) ) ? (array) $this->options['enabled_post_types'] : array();
			$args    = array(
				'public'              => true,
				'exclude_from_search' => false,
			);
			$post_types = get_post_types( $args, 'objects' );

			$exclude_some_default_types = array(
				'attachment',
				'revision',
				'nav_menu_item',
				'wp_template',
				'wp_template_part',
			);
			?>
			<?php foreach ( $post_types as $post_type_obj ):
				if ( in_array( $post_type_obj->name, $exclude_some_default_types, true ) ) {
					continue;
				}
				$labels = get_post_type_labels( $post_type_obj );
				?>
				<label for="<?php echo esc_attr( $post_type_obj->name ); ?>">
					<input type="checkbox" class="<?php echo esc_attr( $post_type_obj->name ); ?>" name="list_table_a2z_options[enabled_post_types][]" value="<?php echo esc_attr( $post_type_obj->name ); ?>" <?php echo checked( in_array( $post_type_obj->name, $checked ), 1 ); ?> />
					<?php echo esc_html( $labels->name ); ?>
				</label>
			<?php endforeach; ?>
			<?php
		}

		/**
		 * Enable A2Z settings for user list.
		 *
		 * @return void
		 */
		public function enabled_userlist_callback() {
			$checked = ( isset( $this->options['enabled_userlist'] ) ) ? checked( $this->options['enabled_userlist'], 1, false ) : '';
			echo '<input type="checkbox" id="enabled_userlist" name="list_table_a2z_options[enabled_userlist]" value="1" ' . $checked . ' />';
		}
	}

endif; // class_exists

return new List_Table_A2Z_Settings();
