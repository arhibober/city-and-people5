<?php get_header('v2'); ?>
<!-- Search Query -->
<div class='container'>
    <div class='row'>
        <div class='col-md-12'>
            <h1><?php _e('Search Results for:', 'bootkit'); ?> <?php the_search_query(); ?></h1>
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
                <div class='card-header'><?php _e('What are you searhing for today?', 'bootkit'); ?></div>
                <div class='card-body'>
                    <?php get_search_form(); ?>
                </div>
                <?php if (have_posts()) {
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
				?>
            </div>
        </div>
        <?php get_sidebar(); ?>
    </div>
</div>
<?php get_footer(); ?>