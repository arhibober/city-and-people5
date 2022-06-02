<?php
class Voices
{
    public function __construct()
    {
    }

    public function example__like(WP_REST_Request $request)
    {
        // Custom field slug
        $field_name = 'voices';
        // Get the current like number for the post
        $current_likes = get_field($field_name, $request['id']);
        // Add 1 to the existing number
        $updated_likes = $current_likes + 1;
        // Update the field with a new value on this post
        $likes = update_field($field_name, $updated_likes, $request['id']);
        return $likes;
    }

    public function blog_js()
    {
        wp_localize_script('jquery', 'bloginfo', array(
            'template_url' => get_bloginfo('template_url'),
            'site_url' => get_bloginfo('url'),
            'post_id' => get_queried_object(),
        ));
        global $wpdb;
        $sqlstr = "SELECT * FROM likes WHERE post_id=" . get_the_ID() . " AND IP=" . $_SERVER['REMOTE_ADDR'];
        $is_like = $wpdb->get_results($sqlstr, ARRAY_A);
        if (count($is_like) > 0) {
            $relation_result = $wpdb->get_results("DELETE FROM likes WHERE post_id=" . get_the_ID() . " AND IP=" . $_SERVER["REMOTE_ADDR"]);
            wp_localize_script('jquery', 'is_like', "true");
        } else {
            $relation_result = $wpdb->get_results("INSERT INTO likes VALUES (" . get_the_ID() . "," . $_SERVER["REMOTE_ADDR"] . ")");
            wp_localize_script('jquery', 'is_like', "false");
        }
    }
}