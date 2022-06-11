<?php
class Widgets
{
    public function __construct()
    {
    }
    public function cityandpeople_widgets()
    {
        register_sidebar([
            'name' => __('Cityandpeople first Sidebar', 'cityandpeople_sidebar'),
            'id' => 'cityandpeople_sidebar',
            'description' => __('Cityandpeople first Sidebar for something.'),
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget' => '</aside>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ]);

        register_sidebar([
            'name' => 'Cityandpeople second Sidebar',
            'id' => 'cityandpeople_sidebar_2',
            'description' => __('Cityandpeople second Sidebar for something.'),
            'before_widget' => '<div id="%1$s" class="backgroundlist %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3>',
            'after_title' => '</h3>',
        ]);
    }
}