<?php

namespace WPGenealogy;

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://bitbucket.org/blackandwhitedigital
 * @since      1.0.0
 *
 * @package    WPGenealogy
 * @subpackage WPGenealogy/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    WPGenealogy
 * @subpackage WPGenealogy/includes
 * @author     Black and White Digital <paul@blackandwhitedigital.com>
 */
class WPGenealogy {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      WPGenealogy_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WPGENEALOGY_VERSION' ) ) {
			$this->version = WPGENEALOGY_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wpgenealogy';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - WPGenealogy_Loader. Orchestrates the hooks of the plugin.
	 * - WPGenealogy_i18n. Defines internationalization functionality.
	 * - WPGenealogy_Admin. Defines all hooks for the admin area.
	 * - WPGenealogy_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		$this->loader = new \WPGenealogy\WPGenealogy_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the WPGenealogy_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new \WPGenealogy\WPGenealogy_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new \WPGenealogy_Admin\WPGenealogy_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'wp_ajax_switch_now', $plugin_admin, 'switch_now' );
		$this->loader->add_action( 'wp_ajax_nopriv_switch_now', $plugin_admin, 'switch_now');
		
		$this->loader->add_action('admin_menu', $plugin_admin, 'admin_menu');
		
		$this->loader->add_action( 'wp_login', $plugin_admin, 'user_last_login', 10, 2 );

		$this->loader->add_action ('wp_loaded', $plugin_admin, 'my_custom_redirect');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new \WPGenealogy_Public\WPGenealogy_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action('wp_ajax_import_ged', $plugin_public, 'import_ged');
		$this->loader->add_action('wp_ajax_nopriv_import_ged', $plugin_public, 'import_ged');

		$this->loader->add_action('wp_ajax_generate', $plugin_public, 'generate');
		$this->loader->add_action('wp_ajax_nopriv_generate', $plugin_public, 'generate');

		$this->loader->add_action('wp_ajax_check', $plugin_public, 'check');
		$this->loader->add_action('wp_ajax_nopriv_check', $plugin_public, 'check');

		$this->loader->add_action('wp_ajax_lock', $plugin_public, 'lock');
		$this->loader->add_action('wp_ajax_nopriv_lock', $plugin_public, 'lock');


		$models = array(
			array('sources','source'),
			array('todos','todo'),
			array('charts','chart'),
			array('citations','citation'),
			array('notes','note'),
			array('note_links','note_link'),
			array('event_types','event_type'),
			array('peoples','people'),
			array('families','family'),
			array('childrens','children'),
			array('trees','tree'),
			array('branches','branch'),
			array('events','event'),
			array('places','place'),
			array('addresses','address'),
			array('users','user'),
			array('associations','association'),
			array('temp_events','temp_event'),
			array('timelines','timeline'),
			array('reports','report'),
		);

		$actions = array(
			'search',
			'get',
			'get_alt', 
			'add',
			'update',
			'delete',
			'delete_bulk'
		);

		foreach ($models as $key => $model) {
			foreach ($actions as $key => $action) {
				if( $action == 'search' || $action == 'get' || $action == 'get_alt'){
					$model_action = $model[0].'_'.$action;
				} else {
					$model_action = $model[1].'_'.$action;
				}
				$this->loader->add_action('wp_ajax_'.$model_action, $plugin_public, $model_action);
				$this->loader->add_action('wp_ajax_nopriv_'.$model_action, $plugin_public, $model_action);
			}
			foreach ($actions as $key => $action) {
				if( $action == 'get' || $action == 'get_alt'){
					$model_action = $model[1].'_'.$action;
					$this->loader->add_action('wp_ajax_'.$model_action, $plugin_public, $model_action);
					$this->loader->add_action('wp_ajax_nopriv_'.$model_action, $plugin_public, $model_action);
				}
			}
		}

		$this->loader->add_action('wp_ajax_family_name', $plugin_public, 'family_name');
		$this->loader->add_action('wp_ajax_nopriv_family_name', $plugin_public, 'family_name');

		$this->loader->add_action('wp_ajax_peoples_name', $plugin_public, 'peoples_name');
		$this->loader->add_action('wp_ajax_nopriv_peoples_name', $plugin_public, 'peoples_name');

		$this->loader->add_action('wp_ajax_event_add_or_update', $plugin_public, 'event_add_or_update');
		$this->loader->add_action('wp_ajax_nopriv_event_add_or_update', $plugin_public, 'event_add_or_update');

		$this->loader->add_action('wp_ajax_settings_get', $plugin_public, 'settings_get');
		$this->loader->add_action('wp_ajax_nopriv_settings_get', $plugin_public, 'settings_get');

		$this->loader->add_action('wp_ajax_setting_update', $plugin_public, 'setting_update');
		$this->loader->add_action('wp_ajax_nopriv_setting_update', $plugin_public, 'setting_update');

		$this->loader->add_action('wp_ajax_children_oreder', $plugin_public, 'children_oreder');
		$this->loader->add_action('wp_ajax_nopriv_children_oreder', $plugin_public, 'children_oreder');

		$this->loader->add_action('wp_ajax_get_surname', $plugin_public, 'get_surname');
		$this->loader->add_action('wp_ajax_nopriv_get_surname', $plugin_public, 'get_surname');

		$this->loader->add_action('wp_ajax_people_search_advanced', $plugin_public, 'people_search_advanced');
		$this->loader->add_action('wp_ajax_nopriv_people_search_advanced', $plugin_public, 'people_search_advanced');

		$this->loader->add_action('wp_ajax_family_search_advanced', $plugin_public, 'family_search_advanced');
		$this->loader->add_action('wp_ajax_nopriv_family_search_advanced', $plugin_public, 'family_search_advanced');

		$this->loader->add_action('wp_ajax_save_tentative', $plugin_public, 'save_tentative');
		$this->loader->add_action('wp_ajax_nopriv_save_tentative', $plugin_public, 'save_tentative');

		$this->loader->add_action('wp_ajax_people_get_full', $plugin_public, 'people_get_full');
		$this->loader->add_action('wp_ajax_nopriv_people_get_full', $plugin_public, 'people_get_full');

		$this->loader->add_action('wp_ajax_family_get_full', $plugin_public, 'family_get_full');
		$this->loader->add_action('wp_ajax_nopriv_family_get_full', $plugin_public, 'family_get_full');

		$this->loader->add_action('wp_ajax_counter', $plugin_public, 'counter');
		$this->loader->add_action('wp_ajax_nopriv_counter', $plugin_public, 'counter');

		$this->loader->add_action('wp_ajax_people_unlink_as_child', $plugin_public, 'people_unlink_as_child');
		$this->loader->add_action('wp_ajax_nopriv_people_unlink_as_child', $plugin_public, 'people_unlink_as_child');

		$this->loader->add_action('wp_ajax_people_unlink_as_spouse', $plugin_public, 'people_unlink_as_spouse');
		$this->loader->add_action('wp_ajax_nopriv_people_unlink_as_spouse', $plugin_public, 'people_unlink_as_spouse');

		$this->loader->add_action('wp_ajax_upload_profile_image', $plugin_public, 'upload_profile_image');
		$this->loader->add_action('wp_ajax_nopriv_upload_profile_image', $plugin_public, 'upload_profile_image');
		$actions = array( 
			'get_people_by_id',
			'get_family_by_id',
			'get_children_by_id',
			'get_people_by_gedcom_personID',
			'temp_event_save_delete'
		);
		foreach ($actions as $key => $action) {
			# code...
			$this->loader->add_action('wp_ajax_'.$action, $plugin_public, $action);
			$this->loader->add_action('wp_ajax_nopriv_'.$action, $plugin_public, $action);
		}
		$actions = array( 
			'generate_pdf_individual_report',
			'generate_pdf_family_report',
			'generate_pdf_pedigree',
			'generate_pdf_descend',
			'suggeste_change',
			'locality_level_one',
			'locality_level_two',
			'places_get_alt_locality_level_one_char',
			'event_by_place',
			'get_statistics',
			'get_anniversaries',
			'get_calendar',
			'report_get_alt_calc',
			'whats_new',
			'export_gedcom',
			'get_relationship',
			'todo_add_comment',
			'wpg_create_page_name',
			'save_hub_content',
			'wpg_create_tree',
			'wpg_create_branch',
			'save_hub_content_fnish',
			'get_wpg_button_link',
			'remove_profile_imagef'
		);
		foreach ($actions as $key => $action) {
			$this->loader->add_action('wp_ajax_'.$action, $plugin_public, $action);
			$this->loader->add_action('wp_ajax_nopriv_'.$action, $plugin_public, $action);
		}
		$this->loader->add_filter( 'rest_pre_serve_request', $plugin_public, 'maybe_tp_feed', 10, 4 );
		$this->loader->add_action( 'rest_api_init', $plugin_public, 'rest_api_init_callback');
		add_shortcode('wpgenealogy', array($plugin_public, 'wpgenealogy_shortcode'));
		add_shortcode('wpgenealogy-tree', array($plugin_public, 'wpgenealogy_tree_func'));
		add_shortcode('wpgenealogy-chart', array($plugin_public, 'wpgenealogy_chart_func'));
		add_shortcode('wpgenealogy-test', array($plugin_public, 'wpgenealogy_test_func'));
		add_shortcode('wpgenealogy-public-wedget', array($plugin_public, 'wpgenealogy_public_wedget'));
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    WPGenealogy_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_user() {
		return $this->user;
	}
}
