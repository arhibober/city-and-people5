<?php
 
function kc_show_carousel($content)
{
	$args = [
        'post_type' => get_option('kc_post_type') ? get_option('kc_post_type') : 'post',
        'tag_in' => get_option('kc_tag'),
        'showposts' => get_option('kc_count'),
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
    ];
	if (get_option('kc_category_name') != false)
	{
		$args ["tax_query"][0]["field"] = "name";
		$args ["tax_query"][0]["taxonomy"] = "city_object_taxonomy";
		$args ["tax_query"][0]["terms"] = get_option('kc_category_name');
	}
    $query = new WP_Query($args);
    $html = '';
    if ($query->have_posts()) {
        $html = '<section id="demos">
    <div class="row">
        <div class="large-12 columns">
            <div class="owl-carousel owl-theme">';
        while ($query->have_posts()) {
            $query->the_post();
            $html .= '<div class="item" style="background:url(' . get_the_post_thumbnail_url($query->post->ID, 'thumbnail') . ') #80808052 center;background-size:cover;"><h5>';
            $html .= '<a href="' . get_permalink($query->post->ID) . '">' . $query->post->post_title . '</a>';
            $html .= '</h5></div>';
        }
        $html .= ' </div>
    </div>
	</div>
	</section>';
    }
	wp_reset_postdata();
    return $content . $html;
}
