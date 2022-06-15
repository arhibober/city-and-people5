<?php

namespace WPGenealogy;

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://bitbucket.org/blackandwhitedigital
 * @since      1.0.0
 *
 * @package    WPGenealogy
 * @subpackage WPGenealogy/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    WPGenealogy
 * @subpackage WPGenealogy/includes
 * @author     Black and White Digital <paul@blackandwhitedigital.com>
 */
class WPGenealogy_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wpgenealogy',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
