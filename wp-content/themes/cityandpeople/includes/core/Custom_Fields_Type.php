<?php
class Custom_Fields_Type
{
    public function __construct()
    {
    }

    public function true_custom_fields()
    {
        /*add_post_type_support( 'high-school', 'custom-fields' );
        add_post_type_support( 'human', 'custom-fields' );
        add_post_type_support( 'theatre', 'custom-fields' );
        add_post_type_support( 'museum', 'custom-fields' );*/
        add_post_type_support( 'city-object', 'custom-fields' );
    }

    public static function is_post_type($type)
    {
        /*global $wp_query;
        if ($type == get_post_type( $wp_query->post->ID )) {
            return true;
        }*/
        return false;
    }
}
