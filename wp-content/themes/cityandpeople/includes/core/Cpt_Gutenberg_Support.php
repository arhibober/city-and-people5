<?php
class Cpt_Gutenberg_Support
{
    public function __construct()
    {
    }
    /*Register WordPress Gutenberg CPT */
    public function custom_post_types()
    {
        register_post_type('city_object',
            array(
                'labels' => array(
                    'name' => __('City Object'),
                    'singular_name' => __('City Object'),
                ),
                'has_archive' => true,
                'public' => true,
                'rewrite' => array('slug' => 'city_object'),
                'show_in_rest' => true,
                'supports' => array('editor'),
            )
        );
    }
}
