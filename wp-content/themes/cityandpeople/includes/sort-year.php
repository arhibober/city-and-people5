<?php

function pre_custom_post_order_sort($query)
{
    if (is_archive() && $query->is_main_query()) {
        $query->set('orderby', 'meta_value');
        $query->set('meta_key', 'year');
        $query->set('order', 'DESC');
    }
}
add_action('pre_get_posts', 'pre_custom_post_order_sort');