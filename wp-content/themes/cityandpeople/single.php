<?php get_header('v2'); ?>
<!-- Page Content -->
<div class='container'>
    <div class='row'>
        <!-- Post Content Column -->
        <?php
		if (get_post_type() == 'city_object') {
			include get_theme_file_path('partials/content-city.php');
		} else {
			include get_theme_file_path('partials/content-single.php');
		}
		?>
        <?php get_sidebar(); ?>
    </div>
</div>
</div>
</div>

<?php get_footer(); ?>