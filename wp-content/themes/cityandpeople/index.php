<?php get_header('v2'); ?>
<!-- Page Content -->
<div class='container'>
    <!-- Marketing Icons Section -->
    <div class='row'>
        <!-- Blog Entries Column -->
        <div class='col-md-8'>
            <?php if (have_posts()) {
                while (have_posts()) {
                    the_post();
                    get_template_part('partials/posts/content', 'excerpt');
                }
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
        <!-- /.row -->
    </div>
</div>
<!-- /.container -->

<?php get_sidebar('second') /* sidebar-second.php */ ?>

<?php get_footer();