<?php

// Extend Classes.
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

if ( ! class_exists( 'WP_A2Z_Base_List_Table' ) ) {
	include_once LTA2Z_ABSPATH . 'includes/admin/data-table/class-a2z-admin-base-list-table.php';
}

class WP_A2Z_List_Table extends WP_A2Z_Base_List_Table {

	function __construct() {
		parent::__construct();

		$this->prepare_items();
	}

	public function display_a2z_filter_list() {
		$post_type = $this->screen->post_type;
		?>
		<table class="wp-list-table <?php echo implode( ' ', $this->get_table_classes() ); ?>">
			<thead>
				<tr>
					<?php
					foreach ( range( 'A', 'Z' ) as $letter ) {
						$args 		= $this->get_az_default_query_args();
						$query_args = wp_parse_args(
							$args,
							array(
								'start_with' => $letter,
							)
						);

						echo '<td>';
						echo $this->get_az_start_with_link( $query_args, $letter, esc_attr( 'az-filter' ) );
						echo '</td>';
					}
					?>
				</tr>
			</thead>
		</table>
		<?php
	}

	/**
	 * Displays the table.
	 *
	 * @since 3.1.0
	 */
	public function display() {
		$singular = $this->_args['singular'];

		$this->display_tablenav( 'top' );

		$this->screen->render_screen_reader_content( 'heading_list' );

		// Modified
		$this->display_a2z_filter_list();
		?>
		<table class="wp-list-table <?php echo implode( ' ', $this->get_table_classes() ); ?>">
			<thead>
			<tr>
				<?php $this->print_column_headers(); ?>
			</tr>
			</thead>

			<tbody id="the-list"
				<?php
				if ( $singular ) {
					echo " data-wp-lists='list:$singular'";
				}
				?>
				>
				<?php $this->display_rows_or_placeholder(); ?>
			</tbody>

			<tfoot>
			<tr>
				<?php $this->print_column_headers( false ); ?>
			</tr>
			</tfoot>

		</table>
		<?php
		$this->display_tablenav( 'bottom' );
	}
}
