<?php

/**
 * Plugin Name: A-Z Filter WP List Table
 * Plugin URI: 
 * Description: A toolkit used to filter WP List Table via A to Z filter sorting.
 * Version: 1.0.0
 * Author: khokansardar
 * Author URI: https://itzmekhokan.wordpress.com/
 * Text Domain: list-table-a2z
 * Domain Path: /languages/
 * Requires at least: 4.4
 * Requires PHP: 7.2
 * Tested up to: 5.2
 *
 * @package List_Table_A2Z
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

// Define LTA2Z_PLUGIN_FILE.
if ( ! defined( 'LTA2Z_PLUGIN_FILE' ) ) {
    define( 'LTA2Z_PLUGIN_FILE', __FILE__ );
}

// Include the main List_Table_A2Z class.
if ( ! class_exists( 'List_Table_A2Z' ) ) {
    include_once dirname( __FILE__ ) . '/includes/class-list-table-a2z.php';
}

/**
 * Main instance of List_Table_A2Z.
 *
 * Return the main instance of List_Table_A2Z to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return List_Table_A2Z
 */
function LTA2Z() {
    return List_Table_A2Z::instance();
}

// Set Global instance.
$GLOBALS['list_table_a2z'] = LTA2Z();
