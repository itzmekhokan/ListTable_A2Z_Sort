<?php

/**
 * List_Table_A2Z setup
 *
 * @package List_Table_A2Z
 * @since   1.0.0
 */

defined('ABSPATH') || exit;

/**
 * Main List_Table_A2Z Class.
 *
 * @class List_Table_A2Z
 */
final class List_Table_A2Z {

    /**
     * List_Table_A2Z version.
     *
     * @var string
     */
    public $version = '1.0.0';
    
    /**
     * Instance of admin class.
     *
     * @var List_Table_A2Z_Admin
     */
    public $admin = null;

    /**
     * The single instance of the class.
     *
     * @var   List_Table_A2Z
     * @since 1.0.0
     */
    protected static $instance = null;

    /**
     * Main List_Table_A2Z Instance.
     *
     * Ensures only one instance of List_Table_A2Z is loaded or can be loaded.
     *
     * @since  1.0.0
     * @static
     * @see    List_Table_A2Z()
     * @return List_Table_A2Z - Main instance.
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * List_Table_A2Z Constructor.
     */
    public function __construct() {
        $this->define_constants();
        add_action( 'plugins_loaded', array( $this, 'load_classes' ), 9 );
        add_action( 'init', array( $this, 'init' ) );
        add_action( 'activated_plugin', array( $this, 'activated_plugin' ) );
        add_action( 'deactivated_plugin', array( $this, 'deactivated_plugin' ) );
        do_action( 'list_table_a2z_loaded' );
    }

    /**
     * Define List_Table_A2Z Constants.
     */
    private function define_constants() {
        if ( ! defined( 'LTA2Z_ABSPATH' ) )
            define( 'LTA2Z_ABSPATH', dirname( LTA2Z_PLUGIN_FILE ) . '/' );
        if ( ! defined( 'LTA2Z_BASENAME' ) )
            define( 'LTA2Z_BASENAME', plugin_basename( LTA2Z_PLUGIN_FILE ) );
        if (! defined( 'LTA2Z_VERSION' ) )
            define( 'LTA2Z_VERSION', $this->version );
    }

    /**
     * Include required core files used in admin and on the frontend.
     */
    public function includes() {
        /**
         * Core classes and functions.
         */
        include_once LTA2Z_ABSPATH . 'includes/lta2z-functions.php';
        if ( is_admin() ) {
            $this->admin = include_once LTA2Z_ABSPATH . 'includes/class-lta2z-admin.php';
        }
    }

    /**
     * Initialises.
     */
    public function init() {
        // Set up localisation.
        $this->load_plugin_textdomain();
    }

    /**
     * Load Localisation files.
     */
    public function load_plugin_textdomain() {
        $locale = is_admin() && function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale();
        $locale = apply_filters( 'plugin_locale', $locale, 'list-table-a2z' );

        unload_textdomain( 'list-table-a2z' );
        load_textdomain( 'list-table-a2z', WP_LANG_DIR . '/list-table-a2z/list-table-a2z-' . $locale . '.mo' );
        load_plugin_textdomain( 'list-table-a2z', false, plugin_basename( dirname( LTA2Z_PLUGIN_FILE ) ) . '/languages' );
    }

    /**
     * Instantiate classes when List_Table_A2Z is activated
     */
    public function load_classes() {
        // all systems ready - GO!
        $this->includes();
    }
    
    /**
     * Get the plugin url.
     *
     * @return string
     */
    public function plugin_url() {
        return untrailingslashit( plugins_url( '/', LTA2Z_PLUGIN_FILE ) );
    }

    /**
     * Get the plugin path.
     *
     * @return string
     */
    public function plugin_path() {
        return untrailingslashit( plugin_dir_path( LTA2Z_PLUGIN_FILE ) );
    }
    
    /**
     * Ran when any plugin is activated.
     *
    */
    public function activated_plugin( $filename ) {
        $default_settings = apply_filters(
            'lta2z_on_activate_default_settings', 
            array(
                'enabled' => 1
            )
        );

        if ( $default_settings ) {
            update_option( 'list_table_a2z_options', $default_settings );
        }
    }
    
    /**
     * Ran when any plugin is deactivated.
     *
    */
    public function deactivated_plugin( $filename ) {
        
    }
}
