<?php
/*
 * Template Name: Archive year
 * Template post type: page, high-school
 */

get_header("v2");?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <!-- Title -->
            <h1 class="mt-4 mb-3">
                <?php the_title();?>
            </h1>
            <span><?php the_archive_description();?></span>
        </div>
    </div>
</div>
<!-- Page Content -->
<div class="container">

    <!-- <h1 class="my-4">Welcome to Modern Business</h1> -->

    <!-- Marketing Icons Section -->
    <div class="row">
        <!-- Blog Entries Column -->
        <div class="col-md-8">
            <?php
$args = array(
    'post_type' => 'high-school',
    'meta_key' => 'rating',
    'meta_value' => '4-5',
    'orderby' => 'meta_value',
    'order' => 'ASC',
);

$the_query = new WP_Query($args);
global $wpdb;

if ($the_query->have_posts()) {
    while ($the_query->have_posts()) {
        $meta_values = $wpdb->get_results("SELECT DISTINCT meta_value FROM $wpdb->postmeta WHERE meta_key LIKE 'year'", OBJECT);
        print_r($meta_values);
        print_r($the_query->get_post_meta($post->ID, 'year', true));
        $the_query->the_post();

        get_template_part('partials/posts/content', 'excerpt');
    }
} else {
    get_template_part('partials/posts/content', 'none');
}
wp_reset_postdata();
?>


            <!-- Pagination -->
            <ul class="pagination justify-content-center mb-4">
                <li class="page-item">
                    <?php previous_posts_link("&larr; Older");?>
                    <!-- <a class="page-link" href="#">&larr; Older</a> -->
                </li>
                <li class="page-item">
                    <?php next_posts_link("Newer &rarr;");?>
                    <!-- <a class="page-link" href="#">Newer &rarr;</a> -->
                </li>
            </ul>


        </div>
        <?php get_sidebar();?>
        <!-- /.row -->
    </div>
    <!-- /.container -->
</div>

<div class="filter-custom-field">
    <?php
global $wpdb;
$meta_values = $wpdb->get_results("SELECT DISTINCT meta_value FROM $wpdb->postmeta WHERE meta_key LIKE 'year'", OBJECT);
foreach ($meta_values as $meta_value): ?>
    <a href="?year=1805">
        <?php echo esc_html($meta_value->meta_value); ?>
    </a>
    <?php endforeach;?>
</div>

<?php get_footer();?>