<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.blackandwhitedigital.net/
 * @since             1.0.0
 * @package           WPGenealogy
 *
 * @wordpress-plugin
 * Plugin Name: WPGenealogy
 * Plugin URI:        https://www.wpgenealogy.net/
 * Description:       All you need to build your family history website in WordPress
 * Version:           0.1.3
 * Author:            Black and White Digital OU
 * Author URI:        https://www.blackandwhitedigital.net/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpgenealogy
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}

if ( function_exists( 'wpg_fs' ) ) {
    wpg_fs()->set_basename( false, __FILE__ );
} else {
    // DO NOT REMOVE THIS IF, IT IS ESSENTIAL FOR THE `function_exists` CALL ABOVE TO PROPERLY WORK.
    
    if ( !function_exists( 'wpg_fs' ) ) {
        // Create a helper function for easy SDK access.
        function wpg_fs()
        {
            global  $wpg_fs ;
            
            if ( !isset( $wpg_fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $wpg_fs = fs_dynamic_init( array(
                    'id'             => '8394',
                    'slug'           => 'wpgenealogy',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_cc993564fe2d708fb28ccfdaa7910',
                    'is_premium'     => false,
                    'premium_suffix' => 'Premium',
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'trial'          => array(
                    'days'               => 14,
                    'is_require_payment' => true,
                ),
                    'menu'           => array(
                    'slug'       => 'wpgenealogy',
                    'first-path' => 'admin.php?page=wpgenealogy',
                    'support'    => true,
                ),
                    'is_live'        => true,
                ) );
            }
            
            return $wpg_fs;
        }
        
        // Init Freemius.
        wpg_fs();
        // Signal that SDK was initiated.
        do_action( 'wpg_fs_loaded' );
    }
    
    // ... Your plugin's main file logic ...
    /**
     * The code that runs during agendapress activation.
     * This action is documented in vendor/autoload.php
     */
    include dirname( __FILE__ ) . '/vendor/autoload.php';
    /**
     * Currently plugin version.
     * Start at version 1.0.0 and use SemVer - https://semver.org
     * Rename this for your plugin and update it as you release new versions.
     */
    define( 'WPGENEALOGY_VERSION', '0.1.2' );
    define( 'WPGENEALOGY_PATH', plugin_dir_path( __FILE__ ) );
    define( 'WPGENEALOGY_URL', plugin_dir_url( __FILE__ ) );
    /**
     * The code that runs during plugin activation.
     * This action is documented in includes/class-wpgenealogy-activator.php
     */
    function wpgenealogy_activate()
    {
        \WPGenealogy\WPGenealogy_Activator::activate();
    }
    
    /**
     * The code that runs during plugin deactivation.
     * This action is documented in includes/class-wpgenealogy-deactivator.php
     */
    function wpgenealogy_deactivate()
    {
        \WPGenealogy\WPGenealogy_Deactivator::deactivate();
    }
    
    register_activation_hook( __FILE__, 'wpgenealogy_activate' );
    register_deactivation_hook( __FILE__, 'wpgenealogy_deactivate' );
    /**
     * Begins execution of the plugin.
     *
     * Since everything within the plugin is registered via hooks,
     * then kicking off the plugin from this point in the file does
     * not affect the page life cycle.
     *
     * @since    1.0.0
     */
    function wpgenealogy_run()
    {
        $plugin = new \WPGenealogy\WPGenealogy();
        $plugin->run();
    }
    
    wpgenealogy_run();
    /**
     * Change template directory temporarily for treepess
     * dashboard.
     *
     * @since    3.0.0
     */
    add_filter(
        'template_directory',
        function ( $template_dir, $template, $theme_root ) {
        if ( wpgenealogy_check_page() ) {
            return plugin_dir_path( __FILE__ ) . '/includes/Template';
        }
        return $template_dir;
    },
        10,
        3
    );
    /**
     * Change template temporarily for treepess
     * dashboard.
     *
     * @since    3.0.0
     */
    add_filter( 'template', function ( $current_theme ) {
        if ( wpgenealogy_check_page() ) {
            return 'wpgenealogy';
        }
        return $current_theme;
    } );
    /**
     * Change stylesheet temporarily for treepess
     * dashboard.
     *
     * @since    3.0.0
     */
    add_filter( 'stylesheet', function ( $current_theme ) {
        if ( wpgenealogy_check_page() ) {
            return 'wpgenealogy';
        }
        return $current_theme;
    } );
    /**
     * Change stylesheet temporarily for treepess
     * dashboard.
     *
     * @since    3.0.0
     */
    function wpgenealogy_check_page()
    {
        $wpgenealogy_settings = get_option( 'wpgenealogy_settings' );
        
        if ( $wpgenealogy_settings ) {
            $wpgenealogy_page_id = $wpgenealogy_settings['configuration']['general']['pages']['wpgenealogy_page_id'];
            $wpgenealogy_page_name = ( get_post( $wpgenealogy_page_id ) ? get_post( $wpgenealogy_page_id )->post_title : '' );
            $REQUEST_URI = explode( '/', $_SERVER['REQUEST_URI'] );
            $REQUEST_URI_COUNT = array_filter( $REQUEST_URI );
            $frontpage_id = get_option( 'page_on_front' );
            $REQUEST_URI = end( $REQUEST_URI_COUNT );
            if ( $wpgenealogy_page_id && $wpgenealogy_page_name ) {
                if ( strpos( $_SERVER['REQUEST_URI'], strval( $wpgenealogy_page_id ) ) !== false || strtolower( $wpgenealogy_page_name ) == strtolower( $REQUEST_URI ) || count( $REQUEST_URI_COUNT ) == 1 && $frontpage_id == $wpgenealogy_page_id ) {
                    return true;
                }
            }
        }
    
    }

}

/*global $wpdb;
$table = $wpdb->prefix.'tp_reports';
$sql = "ALTER TABLE $table CHANGE `criteria` `criteria` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL";
$wpdb->query($sql);
$sql = "ALTER TABLE $table CHANGE `display` `display` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL";
$wpdb->query($sql);*/