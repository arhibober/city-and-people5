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

        <!-- Blog Entries Column -->
        <div class='col-md-8'>
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
            ?>

            <!-- Pagination -->
            <ul class='pagination justify-content-center mb-4'>
                <li class='page-item'>
                    <?php previous_posts_link(__('&larr; Older')); ?>
                </li>
                <li class='page-item'>
                    <?php next_posts_link(__('Newer &rarr;')); ?>
                </li>
            </ul>
        </div>
        <?php get_sidebar(); ?>
    </div>
</div>
<?php get_footer(); ?>