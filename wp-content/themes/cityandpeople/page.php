<?php get_header( 'v2' );?>
<!-- Page Content -->
<div class='container'>
    <div class='row'>
        <!-- Post Content Column -->
        <div class='col-lg-12'>
            <?php
while( have_posts() ) {
    the_post();
    global $post;
    $author_ID = $post->post_author;
    $author_URL = get_author_posts_url( $author_ID );

    ?>



            <!-- Title -->

            <h1 class='mt-4 mb-3'><?php the_title(); ?></h1>

            <!-- Post category: -->
            <h2 class='mt-4'><?php the_category(" "); ?></h2>

            <!-- Author -->
            <p class='lead'>
                by
                <a href='<?php echo $author_URL; ?>'><?php the_author();?></a>
            </p>

            <hr/>

            <!-- Date/Time -->
            <p><?php the_time(get_option('date_format'));
    echo ' ';
    the_time(get_option('time_format'));?></p>

            <hr/>

            <!-- Preview Image -->
            <!-- <img class="img-fluid rounded" src="http://placehold.it/900x300" alt=""> -->
            <?php
if (has_post_thumbnail()) {
        the_post_thumbnail( 'full', ['class' => 'card-img-top'] );
    }
    ?>

            <hr/>

            <!-- Post Content -->
            <?php
                the_content();
                $defaults = array(
                    'before' => '<div class="row justify-content-center align-items-center">' . __('Pages:'),
                    'after' => '</div>',
                );

                wp_link_pages( $defaults );

                edit_post_link();
            ?>

            <hr/>

            <!-- Tag cloud -->
            <?php the_tags( '', ', ' );?>

            <hr/>

            <!-- Pagination -->
            <ul class='pagination justify-content-center mb-4'>
                <li class='page-item'>
                    <?php previous_post_link(); ?>
                </li>
                <li class='page-item'>
                    <?php next_post_link(); ?>
                </li>
            </ul>



            <!-- Post Author Info -->

            <div class='card'>
                <div class='card-header'>
                    <strong>
                        Posted by
                        <a href='<?php echo $author_URL; ?>'><?php the_author();?></a>
                    </strong>
                </div>
                <div class='card-body'>
                    <div class='author-image'>
                        <?php echo get_avatar( $author_ID, 90, '', false, ['class' => 'img-circle'] ); ?>
                    </div>
                    <?php echo nl2br( get_the_author_meta( 'description' ) ); ?>
                </div>
            </div>

            <!-- Post Single - Author End -->

            <?php

    if ( comments_open() || get_comments_number() ) {
        comments_template();
    }

}
?>
        </div>

        <!-- /.row -->
    </div>
</div>
<!-- /.container -->



<?php get_footer();