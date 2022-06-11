<?php
class Custom_Post_Types
{
    public function __construct()
    {
    }

    public function cityandpeople_register_city_object_init()
    {
        $labels = array(
            'name' => __('City object'),
            'singular_name' => __('City object'), // show in admin panel add->Movie
            'add_new' => __('Add city object'),
            'add_new_item' => __('Add new city object'), // <title>
            'edit_item' => __('Edit city object'),
            'new_item' => __('New city object'),
            'all_items' => __('All city objects'),
            'view_item' => __('View city object'),
            'search_items' => __('Search city objects'),
            'not_found' => __('City objects not found.'),
            'not_found_in_trash' => __('City objects not found in trash.'),
            'menu_name' => __('City objects'),
        );
        $args = array(
            'labels' => $labels,
            'public' => true, //for all users - true
            'show_ui' => true, // show in admin panel
            'has_archive' => true,
            'menu_icon' => get_stylesheet_directory_uri() . '/img/function_icon.png', // иконка в меню
            'menu_position' => 20,
            'supports' => array('title', 'editor', 'comments', 'author', 'thumbnail'),
            'taxonomies' => array('post_tag', 'city_object_taxonomy'),
            'show_in_rest' => true,
        );
        register_post_type('city_object', $args);
    }
}