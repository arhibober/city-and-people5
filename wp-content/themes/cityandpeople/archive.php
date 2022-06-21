<?php get_header('v2'); ?>
<div class='container container-archive'>
    <div class='row'>
        <div class='col-md-12'>
            <!-- Title -->
            <h1 class='mt-4 mb-3'>
                <?php
				if (strstr($_SERVER["REQUEST_URI"], "/author/")) {
					printf(__('Author: %s', 'striped'), '<span>' . get_the_author() . '</span>');
				} else {
					if (strstr($_SERVER["REQUEST_URI"], "/city_object_taxonomy/")) {
						$taxonomies = get_the_terms(get_the_ID(), 'city_object_taxonomy');
						printf(__('Taxonomy: %s', 'striped'), '<span>' . $taxonomies[0]->name . '</span>');
					} else {
						printf(__('Category: %s', 'striped'), '<span>' . get_cat_name(get_the_ID()) . '</span>');
					}
				}
				?>
            </h1>
            <span><?php the_archive_description(); ?></span>
        </div>
    </div>
</div>
<!-- Page Content -->
<div class='container'>

    <!-- Marketing Icons Section -->
    <div class='row'>
        <div class='col-md-8'>
            <!-- Blog Entries Column -->
            <div id="object_archive" class='row'>

                <?php
				if (strstr($_SERVER["REQUEST_URI"], "/author/")) {
					$query = new WP_Query(array(
						'author_name' => get_the_author(),
						'post_type' => array('post', 'city_object')
					));
					if ($query->have_posts()) {
						while ($query->have_posts()) {
							$query->the_post();
							get_template_part('partials/posts/content', 'excerpt');
						}
					} else {
						get_template_part('partials/posts/content', 'none');
					}
					echo paginate_links([
						'base'    => get_site_url() . '/author/' . get_the_author() . '/?page=%#%',
						'current' => max(1, get_query_var('page')),
						'before_page_number' => '&nbsp;',
						'total'   => $query->max_num_pages,
					]);
				} else {
					global $wp_query;
					if (have_posts()) {
						while (have_posts()) {
							the_post();
							get_template_part('partials/posts/content', 'excerpt');
						}
					} else {
						get_template_part('partials/posts/content', 'none');
					}
					echo paginate_links([
						'before_page_number' => '&nbsp;'
					]);
				}
				?>
            </div>
        </div>
        <?php get_sidebar(); ?>
    </div>
</div>

<?php get_footer(); ?>