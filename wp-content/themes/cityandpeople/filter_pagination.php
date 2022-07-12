<?php
/*
 * Template Name: Шаблон для пагінаційних сторінок відфільтрованих міських об'єктів
*/
get_header('v2'); ?>

<!-- Page Content -->
<div class='container'>

    <!-- Marketing Icons Section -->
    <div class='row'>
        <!-- Blog Entries Column -->
        <div class='col-md-8'>
            <?php
			while (have_posts()) {
				the_post();
				global $post;
				$author_ID = $post->post_author;
				$author_URL = get_author_posts_url($author_ID);
			?>

            <!-- Title -->

            <h1 class='mt-4 mb-3'>Архів міських об’єктів</h1>

            <!-- Post category: -->
            <h2 class='mt-4'><?php the_category(' ') ?></h2>

            <!-- Author -->
            <p class='lead'>
                by
                <a href='<?php echo $author_URL; ?>'><?php the_author(); ?></a>
            </p>

            <hr>

            <!-- Date/Time -->
            <p><?php the_time(get_option('date_format'));
					echo ' ';
					the_time(get_option('time_format')); ?></p>

            <hr>

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
            <?PHP
			}
			?>
            <div id='object_archive' class='row'>
                <?php
				$args = [];
				$address = get_site_url() . '/filter_pagination?';
				if (isset($_GET['new_date']) && $_GET['new_date'] || isset($_GET['old_date']) && $_GET['old_date']) {
					$args['meta_query'] = array('relation' => 'AND'); // AND means that all conditions of meta_query should be true
				}

				// if both minimum price and maximum price are specified we will use BETWEEN comparison
				if (isset($_GET['new_date']) && $_GET['new_date'] && isset($_GET['old_date']) && $_GET['old_date']) {
					$args['meta_query'][] = array(
						'key' => 'дата',
						'value' => array($_GET['old_date'], $_GET['new_date']),
						'type' => 'date',
						'compare' => 'between'
					);
					$address .= 'old_date=' . $_GET['old_date'] . '&new_date' . $_GET['new_date'];
				} else {
					// if only min price is set
					if (isset($_GET['old_date']) && $_GET['old_date']) {
						$args['meta_query'][] = array(
							'key' => 'дата',
							'value' => $_GET['old_date'],
							'type' => 'date',
							'compare' => '>'
						);
						$address .= 'old_date=' . $_GET['old_date'];
					}

					// if only max price is set
					if (isset($_GET['new_date']) && $_GET['new_date']) {
						$args['meta_query'][] = array(
							'key' => 'дата',
							'value' => $_GET['new_date'],
							'type' => 'date',
							'compare' => '<'
						);
						$address .= 'new_date=' . $_GET['new_date'];
					}
				}
				$args['post_type'] = "city_object";
				$taxonomies = "";
				if (isset($_GET['taxonomies'])) {
					if (count($_GET['taxonomies']) > 0) {
						$args['tax_query'][0]['taxonomy'] = 'city_object_taxonomy';
						foreach ($_GET['taxonomies'] as $taxonomy) {
							$address .= '&taxonomies[]=' . $taxonomy;
							$args['tax_query'][0]['terms'][] = $taxonomy;
						}
					}
				}
				$args['posts_per_page'] = get_option('posts_per_page');
				$args['offset'] = (int)(get_option('posts_per_page')) * ($_GET['page1'] - 1);
				$query = new WP_Query($args);
				if ($query->have_posts()) {
					while ($query->have_posts()) {
						$query->the_post();
						get_template_part('partials/posts/content', 'excerpt');
					}
				} else {
					get_template_part('partials/posts/content', 'none');
				}
				echo paginate_links([
					'base'    => $address . '&page1=%#%',
					'current' => $_GET['page1'],
					'before_page_number' => '&nbsp;',
					'total' => $query->max_num_pages,
				]);
				?>
            </div>
            <form action='<?php echo site_url() ?>/wp-admin/admin-ajax.php' method='POST' id='filter'>
                <input type='date' name='old_date' placeholder='<?php _e('The oldest date'); ?>' <?php
																									if (isset($_GET['old_date']))
																										echo 'value="' . $_GET['old_date'];
																									?> />
                <input type='date' name='new_date' placeholder='<?php _e('The newsest date'); ?>' <?php
																									if (isset($_GET['new_date']))
																										echo 'value="' . $_GET['new_date'];
																									?> />
                <?php
				$queried_object = get_queried_object();
				$taxonomies = get_terms([
					'taxonomy'     => 'city_object_taxonomy',
					'type'         => 'city_object',
					'orderby'      => 'name',
					'order'        => 'ASC',
					'hide_empty'   => 0,
					'hierarchical' => 1,
					'show_count' => 1,
					'pad_counts' => 0,
					'child_of' => $queried_object->term_id
					// полный список параметров смотрите в описании функции http://wp-kama.ru/function/get_terms
				]);
				$tax_hierarchies = array();
				Hierarchical::sort_terms_hierarchicaly($taxonomies, $tax_hierarchies);
				echo '<h4>';
				_e('Object categories:');
				echo '</h4>
				<ul>';
				Hierarchical::child_list($tax_hierarchies, $_GET['taxonomies']);
				?>
                <button><?php _e('Apply filter'); ?></button>
                <input type='hidden' name='action' value='myfilter' />
            </form>
        </div>
        <?php get_sidebar(); ?>
    </div>
</div>

<?php get_footer();
?>