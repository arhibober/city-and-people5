<?php
class Setup
{
    public function __construct()
    {
    }
    public function cityandpeople_setup_theme()
    {
        add_theme_support('post-thumbnails');
        register_nav_menu('primary', __('Primary Menu', 'sityandpeople'));
    }
}