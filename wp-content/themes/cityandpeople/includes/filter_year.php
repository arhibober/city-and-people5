<?php

function filter_year($query)
{
    if (isset($_GET["o_year"]) && is_archive()) {
        $query->set('post_type', ['high-school']);
        $current_meta = $query->get('meta_query') ? $query->get('meta_query') : [];
        $current_meta[] = array(
            'key' => 'o_year',
            'value' => $_GET['o_year'],
            'compare' => '=',
        );
        $query->set('meta_query', $current_meta);
    }
}
add_action('pre_get_posts', 'filter_year');