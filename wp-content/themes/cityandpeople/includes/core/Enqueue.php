<?php
class enqueue
{
    public function __constructor()
    {
    }
    public function cityandpeople_enqueue()
    {
        $url = get_theme_file_uri();

        $ver = CITYANDPEOPLE_DEV_MODE ? time() : false;

        wp_register_style('cityandpeople_google_fonts', 'https://fonts.googleapis.com/css?family=Open+Sans&display=swap', [], $ver); // Google fonts
        wp_register_style('cityandpeople_bootstrap', $url . '/assets/vendor/bootstrap/css/bootstrap.min.css', [], $ver); // bootstrap.min.css
        wp_register_style('cityandpeople_modern_business', $url . '/assets/css/modern-business.css', [], $ver); // modern-business.css
        wp_register_style('theme_style', $url . '/theme_style.css', [], $ver);
        wp_register_script('cityandpeople_bootstrap', $url . '/assets/vendor/bootstrap/js/bootstrap.bundle.min.js', 'jquery', $ver, true);
        wp_register_script('cityandpeople_ajax', $url . '/js/ajax.js', 'jquery', $ver, true);
        wp_enqueue_style('cityandpeople_bootstrap');
        wp_enqueue_style('cityandpeople_modern_business');
        wp_enqueue_style('cityandpeople_google_fonts');
        wp_enqueue_style('theme_style');
        wp_enqueue_script('jquery');
        wp_enqueue_script('cityandpeople_bootstrap');
        wp_enqueue_script('cityandpeople_ajax');
        wp_enqueue_style('style', get_stylesheet_uri());
		load_theme_textdomain('city-and-people', $url . '/languages');
    }
}