<div class='col-md-8'>
    <?php
	while (have_posts()) {
		the_post();
		global $post;
		$author_ID = $post->post_author;
		$author_URL = get_author_posts_url($author_ID);
	?>

    <!-- Title -->

    <h1 class='mt-4 mb-3'><?php the_title() ?></h1>

    <!-- Post category: -->
    <h2 class='mt-4'><?php the_category(' ') ?></h2>

    <!-- Author -->
    <p class='lead'>
        <?php _e('by'); ?>
        <a href='<?php echo $author_URL; ?>'><?php the_author(); ?></a>
    </p>

    <hr />

    <!-- Date/Time -->
    <p><?php the_time(get_option('date_format'));
			echo " ";
			the_time(get_option('time_format')); ?></p>

    <hr />

    <!-- Preview Image -->
    <!-- <img class="img-fluid rounded" src="http://placehold.it/900x300" alt=""> -->
    <?php
		if (has_post_thumbnail())
			the_post_thumbnail('full', ['class' => 'card-img-top']);
		?>

    <hr />

    <!-- Post Content -->
    <?php
		the_content();
		$defaults = array(
			'before' => '<div class="row justify-content-center align-items-center">' . __('Pages:'),
			'after' => '</div>',
		);
		wp_link_pages($defaults);
		edit_post_link();
		?>
    <hr />

    <!-- Pagination -->
    <ul class='pagination justify-content-center mb-4'>
        <li class='page-item'>
            <?php previous_post_link(); ?>
        </li>
        <li class='page-item'>
            <?php next_post_link(); ?>
        </li>
    </ul>
    <?php
		$date_symbol = substr(get_option('date_format'), 1, 2);
		echo '<b>';
		_e('Key dates');
		echo '</b>: ' . get_field('дата');
		// необязательно, но в некоторых случаях без этого не обойтись
		global $post;

		// тут можно указать post_tag (подборка постов по схожим меткам) или даже массив array( 'category', 'post_tag' ) - подборка и по меткам и по категориям
		$related_tax = 'city_object_taxonomy';

		// получаем ID всех элементов (категорий, меток или таксономий), к которым принадлежит текущий пост
		$cats_tags_or_taxes = wp_get_object_terms($post->ID, $related_tax, array('fields' => 'ids'));

		// массив параметров для WP_Query
		$args = array(
			//'posts_per_page' => 4, // сколько похожих постов нужно вывести,
			'tax_query' => array(
				array(
					'taxonomy' => $related_tax,
					'field' => 'id',
					'include_children' => false, // нужно ли включать посты дочерних рубрик
					'terms' => $cats_tags_or_taxes,
					'operator' => 'IN', // если пост принадлежит хотя бы одной рубрике текущего поста, он будет отображаться в похожих записях, укажите значение AND и тогда похожие посты будут только те, которые принадлежат каждой рубрике текущего поста
					'post__not_in' => array($post->ID)
				)
			),
			'post__not_in' => array($post->ID)
		);
		$my_query = new WP_Query($args);
		$taxonomies = '';
		$taxonomies = get_the_terms(get_the_ID(), 'city_object_taxonomy');
		if ($taxonomies != '') {
			echo '<h3>';
			_e('Object Categories');
			echo '</h3>';
			$i = 0;

			// так как функция вернула массив, то логично будет прокрутить его через foreach()
			foreach ($taxonomies as $taxonomy) {
				echo '<a href="' . get_term_link($taxonomy) . '">' . $taxonomy->name . '</a>';
				if ($i != count($taxonomies) - 1)
					echo ', ';
				$i++;
			}
		}
		if (has_tag()) {
			echo '<h3>';
			_e('Tags');
			echo '</h3>';
			the_tags('', ', ');
		}

		// если посты, удовлетворяющие нашим условиям, найдены
		if ($my_query->have_posts()) :

			// выводим заголовок блока похожих постов
			echo '<h3>';
			_e('Similar Objects');
			echo '</h3>';

			// запускаем цикл
			while ($my_query->have_posts()) : $my_query->the_post();
				// в данном случае посты выводятся просто в виде ссылок
				echo '<a href="' . get_permalink($my_query->post->ID) . '">' . $my_query->post->post_title . '</a><br/>';
			endwhile;
		endif;

		// не забудьте про эту функцию, её отсутствие может повлиять на другие циклы на странице
		wp_reset_postdata();
		$args = array(
			'post_type'  => 'city_object',
			'meta_query' => array(
				array(
					'key'     => 'дата',
					'value' => substr(get_post_meta($post->ID, 'дата')[0], 0, 4),
					'compare' => 'LIKE'
				)
			),
			'post__not_in' => array($post->ID)
		);
		$my_query = new WP_Query($args);

		// если посты, удовлетворяющие нашим условиям, найдены
		if ($my_query->have_posts()) :

			// выводим заголовок блока похожих постов
			echo '<h3>';
			_e('Similar in date Objects');
			echo '</h3>';

			// запускаем цикл
			while ($my_query->have_posts()) : $my_query->the_post();
				// в данном случае посты выводятся просто в виде ссылок
				echo '<a href="' . get_permalink($my_query->post->ID) . '">' . $my_query->post->post_title . '</a><br/>';
			endwhile;
		endif;

		// не забудьте про эту функцию, её отсутствие может повлиять на другие циклы на странице
		wp_reset_postdata();
		?>

    <!-- Post Author Info -->

    <div class='card'>
        <div class='card-header'>
            <strong>
                <?php _e('Posted by'); ?>
                <a href="<?php echo $author_URL; ?>"><?php the_author(); ?></a>
            </strong>
        </div>
        <div class='card-body'>
            <div class='author-image'>
                <?php echo get_avatar($author_ID, 90, '', false, ['class' => 'img-circle']); ?>
            </div>
            <?php echo nl2br(get_the_author_meta('description')); ?>
        </div>
    </div>
    <?php
		$images = get_field('галерея_обєкту');
		$size = 'small'; // (thumbnail, medium, large, full или произвольный размер)
		if ($images) : ?>
    <ul class='object-gallery'>
        <?php foreach ($images as $image) : ?>
        <li>
            <a href='<?php echo $image['url']; ?>" alt="<?php echo get_post_meta($image['ID'], '_wp_attachment_image_alt')[0]; ?>" title="<?php echo get_the_excerpt($image['ID']) ?>">
								<?php echo wp_get_attachment_image($image['ID'], $size) ?>
							</a><br/>
								<div><?php echo get_the_content(NULL, NULL, $image['ID']) ?></div>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif;
		if (get_post_meta($post->ID, 'виноски', true) !== '') {
			echo '<h3>';
			_e('Footnotes');
			echo "</h3><br/>
				<a href='" . get_field('виноски')["url"] . "'>" . get_field('виноски')['title'] . '</a>';
		}
		if (get_post_meta($post->ID, 'дивись_також', true) !== '') {
			echo '<h3>';
			_e('See also');
			echo "</h3><br/>
					<a href='" . get_field('дивись_також')['url'] . "'>" . get_field('дивись_також')['title'] . '</a>';
		}
		if (strstr(get_the_content(), 'map_center')) {
			$wide = strstr(substr(strstr(get_the_content(), 'map_center'), 12, strlen(strstr(get_the_content(), "map_center")) - 12), ",", true);
			$long = strstr(substr(strstr(strstr(get_the_content(), 'map_center'), ','), 1, strlen(strstr(strstr(get_the_content(), "map_center"), ",")) - 1), '"', true);
			delete_post_meta($post->ID, 'широта');
			delete_post_meta($post->ID, 'довгота');
			add_post_meta($post->ID, 'широта', $wide);
			add_post_meta($post->ID, 'довгота', $long);
			echo '<h3>';
			_e('The Nearest Objects');
			echo '</h3>					
				<p>';
			_e('Choose a diapason');
			echo "</p>
				<form action='" . site_url() . "/wp-admin/admin-ajax.php' method='POST' id='diapason_form'>
				<input type='range' name='diapason' id='diapason' min='0' max='30'>
				<span id='range_value'>15</span>&nbsp;";
			_e('km');
			echo "<input type='hidden' name='current_id' value='" . $post->ID . "'/>
				<input type='hidden' name='action' id='action' value='my_nearest'>
				<div id='nearest'>";
			$args = array(
				'post_type' => 'city_object',
				'post__not_in' => array($post->ID)
			);
			$wide = strstr(substr(strstr(get_the_content(null, null, $post->ID), 'map_center'), 12, strlen(strstr(get_the_content(null, null, $post->ID), 'map_center')) - 12), ',', true);
			$long = strstr(substr(strstr(strstr(get_the_content(null, null, $post->ID), 'map_center'), ','), 1, strlen(strstr(strstr(get_the_content(null, null, $post->ID), 'map_center'), ',')) - 1), '"', true);
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
						if ($is_near1 == $is_near2) {
							return 0;
						}
						if ($is_near1 > $is_near2) {
							return 1;
						}
						if ($is_near1 < $is_near2) {
							return -1;
						}
					}
				);
				foreach ($content_only as $id => $content) {
					$wide_near = strstr(substr(strstr($content, 'map_center'), 12, strlen(strstr($content, 'map_center')) - 12), ',', true);
					$long_near = strstr(substr(strstr(strstr($content, 'map_center'), ','), 1, strlen(strstr(strstr($content, 'map_center'), ',')) - 1), '"', true);
					$distance_near = 12742 * asin(sqrt(pow(sin(($wide_near - $wide) * pi() / 360), 2) + cos($wide_near * pi() / 180) * cos($wide * pi() / 180) * pow(sin(($long_near - $long) * pi() / 360), 2)));
					if ($distance_near <= 15) {
						$title = get_the_title($id);
						$link = get_permalink($id);
						echo "<a href = '" . $link . "'>" . $title . '</a> - ' . round($distance_near, 1) . '&nbsp;';
						_e('km');
						echo '<br/>';
					}
				}
			}
			echo '</div>
				</form>';
			wp_reset_postdata();
		}
		$terms = get_the_terms($post->ID, 'city_object_taxonomy');
		$args = array();
		$args['post_type'] = 'city_object';
		$args['tax_query'][0]['taxonomy'] = 'city_object_taxonomy';
		$args['post__not_in'] = array($post->ID);
		foreach ($terms as $term) {
			if ($term->parent == get_term_by('slug', 'zviazuiuchi-taksonomii', 'city_object_taxonomy')->term_id) {
				$args['tax_query'][0]['terms'][] = $term->term_id;
			}
		}
		$my_query = new WP_Query($args);
		if ($my_query->have_posts()) {
			echo '<h3>';
			_e('Connected Posts');
			echo '</h3>';
			while ($my_query->have_posts()) {
				$my_query->the_post();
				echo "<a href = '" . get_permalink($my_query->post->ID) . "'>" . $my_query->post->post_title . '</a>';
			}
			wp_reset_postdata();
		}
		$children_document = false;
		$term_document = get_term_by('slug', 'dokument', 'city_object_taxonomy');
		$term_children = get_term_children($term_document->term_id, 'city_object_taxonomy');
		foreach ($term_children as $term_child) {
			if (is_object_in_term($post->ID, 'city_object_taxonomy', $term_child)) {
				$children_document = true;
				break;
			}
		}
		$children_people = false;
		$term_people = get_term_by('slug', 'liudyna', 'city_object_taxonomy');
		$term_children = get_term_children($term_people->term_id, 'city_object_taxonomy');
		foreach ($term_children as $term_child) {
			if (is_object_in_term($post->ID, 'city_object_taxonomy', $term_child)) {
				$children_people = true;
				break;
			}
		}
		if ((!is_object_in_term($post->ID, 'city_object_taxonomy', 'dokument')) && (!$children_document) && (!is_object_in_term($post->ID, 'city_object_taxonomy', 'liudyna')) && (!$children_people)) {
			if (get_post_meta($post->ID, 'мапа', true) !== '') {
				echo '<h3>';
				_e('Map');
				echo '</h3>';
				$iframe = get_field('мапа');

				// Use preg_match to find iframe src.
				preg_match('/src="(.+?)"/', $iframe, $matches);
				$src = $matches[1];

				// Add extra parameters to src and replace HTML.
				$params = array(
					'controls'  => 0,
					'hd'        => 1,
					'autohide'  => 1
				);
				$new_src = add_query_arg($params, $src);
				$iframe = str_replace($src, $new_src, $iframe);

				// Add extra attributes to iframe HTML.
				$attributes = 'frameborder="0"';
				$iframe = str_replace('></iframe>', ' ' . $attributes . '></iframe>', $iframe);

				// Display customized HTML.
				echo $iframe;
			}
			if (get_post_meta($post->ID, 'широта', true) !== '') {
				echo '<h3>';
				_e('Latitude');
				echo '</h3>' . get_field('широта');
			}
			if (get_post_meta($post->ID, 'довгота', true) !== '') {
				echo '<h3>';
				_e('Longitude');
				echo '</h3>' . get_field('довгота');
			}
		}
		if ((is_object_in_term($post->ID, 'city_object_taxonomy', 'liudyna')) || $children_people) {
			if (get_post_meta($post->ID, 'дата_народження', true) !== '') {
				echo '<h3>';
				_e('Birthday');
				echo '</h3>' . get_field('дата_народження');
			}
			if (get_post_meta($post->ID, 'місце_народження', true) !== '') {
				$birth_id = get_field('місце_народження', false, false);
				$birth_type = get_the_terms($birth_id, 'city_object_taxonomy');
				echo '<h3>';
				_e('Place of Birth');
				echo '</h3><a href=' . get_the_permalink($birth_id) . "'>" . $birth_type[0]->name . " " . get_the_title($birth_id) . "</a>";
			}
			if (get_post_meta($post->ID, 'дата_смерті', true) !== '') {
				echo '<h3>';
				_e('Date of Die');
				echo '</h3>' . get_field('дата_смерті');
			}
			if (get_post_meta($post->ID, 'місце_смерті', true) !== '') {

				$birth_id = get_field('місце_смерті', false, false);
				$birth_type = get_the_terms($birth_id, 'city_object_taxonomy');
				echo '<h3>';
				_e('Place of Die');
				echo '</h3><a href=' . get_the_permalink($birth_id) . "'>" . $birth_type[0]->name . " " . get_the_title($birth_id) . "</a>";
			}
		}
		$children_house = false;
		$term_house = get_term_by('slug', 'budynok', 'city_object_taxonomy');
		$term_children = get_term_children($term_house->term_id, 'city_object_taxonomy');
		foreach ($term_children as $term_child) {
			if (is_object_in_term($post->ID, 'city_object_taxonomy', $term_child)) {
				$children_house = true;
				break;
			}
		}
		if ((is_object_in_term($post->ID, 'city_object_taxonomy', 'budynok')) || $children_house) {
			if (get_post_meta($post->ID, 'адреса', true) !== '') {
				echo '<h3>';
				_e('Address');
				echo '</h3>' . get_field('адреса');
			}
			if (get_post_meta($post->ID, 'висота', true) !== '') {
				echo '<h3>';
				_e('Height');
				echo '</h3>' . get_field('висота');
			}
		}
		$children_street = false;
		$term_street = get_term_by('slug', 'vulytsia', 'city_object_taxonomy');
		$term_children = get_term_children($term_street->term_id, 'city_object_taxonomy');
		foreach ($term_children as $term_child) {
			if (is_object_in_term($post->ID, 'city_object_taxonomy', $term_child)) {
				$children_street = true;
				break;
			}
		}
		if ((is_object_in_term($post->ID, 'city_object_taxonomy', 'vulytsia')) || $children_street) {
			if (get_post_meta($post->ID, 'длина_вулиці', true) !== '') {
				echo '<h3>';
				_e('Street long');
				echo '</h3>' . get_field('длина_вулиці');
			}
		}
		$children_hight_school = false;
		$term_hight_school = get_term_by('slug', 'vnz', 'city_object_taxonomy');
		$term_children = get_term_children($term_hight_school->term_id, 'city_object_taxonomy');
		foreach ($term_children as $term_child) {
			if (is_object_in_term($post->ID, 'city_object_taxonomy', $term_child)) {
				$children_hight_school = true;
				break;
			}
		}
		if ((is_object_in_term($post->ID, 'city_object_taxonomy', 'vnz')) || $term_hight_school) {
			if (get_post_meta($post->ID, 'список_факультетів', true) !== '') {
				echo '<h3>';
				_e('Facultaty List');
				echo '</h3>' . get_field('список_факультетів');
			}
			if (get_post_meta($post->ID, 'рейтинг', true) !== '') {
				echo '<h3>';
				_e('Rating');
				echo '</h3>' . get_field('рейтинг');
			}
			if (get_post_meta($post->ID, 'список_ректорів', true) !== '') {
				echo '<h3>';
				_e("Rectors' List");
				echo '</h3>' . get_field('список_ректорів');
			}
		}
			?>

            <!-- Post Single - Author End -->

            <?php

			if (comments_open() || get_comments_number()) {
				comments_template();
			}
		}
			?>
</div>