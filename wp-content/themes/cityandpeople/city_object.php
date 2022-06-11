<?php
/*
Template Name: Міський об'єкт
Template Post Type: city_object
*/
get_header('v2'); ?>
<!-- Page Content -->
<div class='container'>
    <div class='row'>
        <!-- Post Content Column -->
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
                by
                <a href='<?php echo $author_URL; ?>'><?php the_author(); ?></a>
            </p>

            <hr />

            <!-- Date/Time -->
            <p><?php the_time(get_option('date_format'));
					echo " ";
					the_time(get_option('time_format')); ?></p>

            <hr />

            <!-- Preview Image -->
            <?php
				if (has_post_thumbnail()) {
					the_post_thumbnail('full', ['class' => 'card-img-top']);
				}
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

            <!-- Tag cloud -->
            <?php the_tags('', ', '); ?>

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

				// тут можно указать post_tag (подборка постов по схожим меткам) или даже массив array('category', 'post_tag') - подборка и по меткам и по категориям
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

				// если посты, удовлетворяющие нашим условиям, найдены
				if ($my_query->have_posts()) :

					// выводим заголовок блока похожих постов
					echo '<h3>';
					_e('Similar objects');
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
							'value' => substr(get_post_meta($post->ID, "дата")[0], 0, 4),
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
					_e('Similar in date objects');
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
                        <a href='<?php echo $author_URL; ?>'><?php the_author(); ?></a>
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
                    <a href='<?php echo $image['url']; ?>'
                        alt='<?php echo get_post_meta($image['ID'], '_wp_attachment_image_alt')[0]; ?>'
                        title='<?php echo get_the_excerpt($image['ID']) ?>'>
                        <?php echo wp_get_attachment_image($image['ID'], $size) ?>
                    </a><br />
                    <div><?php echo get_the_content(NULL, NULL, $image['ID']) ?></div>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php endif;
				if (get_post_meta($post->ID, 'мапа', true) !== '') {
					echo '<h3>';
					_e('Map');
					echo '</h3><br/>';
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
				if (get_post_meta($post->ID, 'виноски', true) !== '') {
					echo '<h3>';
					_e('Footnotes');
					echo "</h3><br/>
					<a href='" . get_field('виноски')['url'] . "'>" . get_field('виноски')['title'] . "</a>";
				}
				if (get_post_meta($post->ID, 'дивись_також', true) !== '') {
					echo '<h3>';
					_e('See also');
					echo "</h3><br/>
				<a href='" . get_field('дивись_також')['url'] . "'>" . get_field('дивись_також')['title'] . "</a>";
				}		?>


            <!-- Post Single - Author End -->

            <?php

				if (comments_open() || get_comments_number()) {
					comments_template();
				}
			}
			?>
        </div>
        <?php get_sidebar(); ?>
        <!-- /.row -->
    </div>
</div>
<!-- /.container -->



<?php get_footer();