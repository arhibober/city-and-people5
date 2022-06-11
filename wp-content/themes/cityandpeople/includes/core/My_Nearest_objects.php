<?php
class My_nearest_objects
{
	public function __construct()
	{
	}
	public function my_nearest_function()
	{
		$args = array(
			'post_type' => 'city_object',
			'post__not_in' => array($_POST['current_id'])
		);
		$wide = strstr(substr(strstr(get_the_content(null, null, $_POST['current_id']), 'map_center'), 12, strlen(strstr(get_the_content(null, null, $_POST['current_id']), 'map_center')) - 12), ',', true);
		$long = strstr(substr(strstr(strstr(get_the_content(null, null, $_POST['current_id']), 'map_center'), ','), 1, strlen(strstr(strstr(get_the_content(null, null, $_POST['current_id']), 'map_center'), ',')) - 1), '"', true);
		$my_query = new WP_Query($args);
		$content_only = array();
		if ($my_query->have_posts()) {
			while ($my_query->have_posts()) {
				$my_query->the_post();
				if (strstr($my_query->post->post_content, 'map_center'))
					$content_only[$my_query->post->ID] = $my_query->post->post_content;
			}
			uasort(
				$content_only,
				function ($content1, $content2) use ($wide, $long) {
					$wide_near1 = strstr(substr(strstr($content1, 'map_center'), 12, strlen(strstr($content1, 'map_center')) - 12), ',', true);
					$long_near1 = strstr(substr(strstr(strstr($content1, 'map_center'), ','), 1, strlen(strstr(strstr($content1, 'map_center'), ',')) - 1), '"', true);
					$is_near1 = 12742000 * asin(sqrt(pow(sin(($wide_near1 - $wide) * pi() / 360), 2) + cos($wide_near1 * pi() / 180) * cos($wide * pi() / 180) * pow(sin(($long_near1 - $long) * pi() / 360), 2)));
					$wide_near2 = strstr(substr(strstr($content2, 'map_center'), 12, strlen(strstr($content2, 'map_center')) - 12), ',', true);
					$long_near2 = strstr(substr(strstr(strstr($content2, 'map_center'), ','), 1, strlen(strstr(strstr($content2, 'map_center'), ',')) - 1), '"', true);
					$is_near2 = 12742000 * asin(sqrt(pow(sin(($wide_near2 - $wide) * pi() / 360), 2) + cos($wide_near2 * pi() / 180) * cos($wide * pi() / 180) * pow(sin(($long_near2 - $long) * pi() / 360), 2)));
					if ($is_near1 == $is_near2)
						return 0;
					if ($is_near1 > $is_near2)
						return 1;
					if ($is_near1 < $is_near2)
						return -1;
				}
			);
			foreach ($content_only as $id => $content) {
				$wide_near = strstr(substr(strstr($content, 'map_center'), 12, strlen(strstr($content, 'map_center')) - 12), ',', true);
				$long_near = strstr(substr(strstr(strstr($content, 'map_center'), ','), 1, strlen(strstr(strstr($content, 'map_center'), ',')) - 1), '"', true);
				$distance_near = 12742 * asin(sqrt(pow(sin(($wide_near - $wide) * pi() / 360), 2) + cos($wide_near * pi() / 180) * cos($wide * pi() / 180) * pow(sin(($long_near - $long) * pi() / 360), 2)));
				if ($distance_near <= $_POST['diapason']) {
					$title = get_the_title($id);
					$link = get_permalink($id);
					echo "<a href = '" . $link . "'>" . $title . "</a> - " . round($distance_near, 1) . "&nbsp;";
					_e('km');
					echo '<br/>';
				}
			}
		}
		wp_reset_postdata();
		wp_die();
		return;
	}
}