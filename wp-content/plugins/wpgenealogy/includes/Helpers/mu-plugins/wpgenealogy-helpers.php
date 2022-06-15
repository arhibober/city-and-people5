<?php
/**
 *
 * Plugin Name:       WPGenealogy Helper
 * Description:       This plugin helps `wpgenealogy` plugins to run into a stand alone page
 */

add_filter('option_active_plugins', function ($plugins) {
	$wpgenealogy_settings =  get_option('wpgenealogy_settings');

	if($wpgenealogy_settings){
		
		$wpgenealogy_page_id = $wpgenealogy_settings['configuration']['general']['pages']['wpgenealogy_page_id'];
		
		$wpgenealogy_page_name =  get_post($wpgenealogy_page_id) ? get_post($wpgenealogy_page_id)->post_title : '';
		
		$REQUEST_URI = explode('/',$_SERVER['REQUEST_URI']);
		
		$REQUEST_URI_COUNT = array_filter($REQUEST_URI);

		$frontpage_id = get_option( 'page_on_front' );

		$REQUEST_URI = end($REQUEST_URI_COUNT);

		if(($wpgenealogy_page_id && $wpgenealogy_page_name)) {
			if ( 
				strpos($_SERVER['REQUEST_URI'], strval($wpgenealogy_page_id)) !== false 
				|| 
				strtolower($wpgenealogy_page_name) == strtolower($REQUEST_URI)
				||
				(count($REQUEST_URI_COUNT) == 1 && $frontpage_id == $wpgenealogy_page_id)
			) {
				if(in_array('wpgenealogy/wpgenealogy.php', $plugins)) {
					return $plugins[0] = 'wpgenealogy/wpgenealogy.php';

				}
			}
		}
	}
	
	return $plugins;
}, 100);
