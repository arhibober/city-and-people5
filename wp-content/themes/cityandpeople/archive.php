<?php get_header( 'v2' );?>
<div class='container'>
    <div class='row'>
        <div class='col-md-12'>
            <!-- Title -->
            <h1 class='mt-4 mb-3'>
                <?php printf( __( 'Year: %s', 'striped' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'striped' ) ) . '</span>' );?>
            </h1>
            <span><?php the_archive_description();?></span>
        </div>
    </div>
</div>
<!-- Page Content -->
<div class='container'>

    <!-- Marketing Icons Section -->
    <div class='row'>
        <!-- Blog Entries Column -->
        <div class='col-md-8'>



            <?php if( have_posts() ) {
    while( have_posts() ) {
        the_post();
        get_template_part( 'partials/posts/content', 'excerpt' );
    }
} else {
    get_template_part( 'partials/posts/content', 'none' );
}
?>

            <!-- Pagination -->
            <ul class='pagination justify-content-center mb-4'>
                <li class='page-item'>
                    <?php previous_posts_link( __( '&larr; Older' ) );?>
                </li>
                <li class='page-item'>
                    <?php next_posts_link( __( 'Newer &rarr;' ) );?>
                </li>
            </ul>


        </div>
        <?php get_sidebar();?>
        <!-- /.row -->
    </div>
    <!-- /.container -->
</div>

<?php get_footer();?>