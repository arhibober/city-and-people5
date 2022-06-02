<?php

function filter_rating($query)
{
    if (isset($_GET["rating"]) && is_archive()) {
        $args = array(
            'numberposts' => 2,
            'post_type' => 'high-school',
            'meta_key' => 'rating',
            'meta_value' => $_GET['rating'],
        );
        $query = new WP_Query($args);
    }
}