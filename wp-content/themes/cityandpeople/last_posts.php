<?php
echo '<h4>' . __('See also') . '</h4>';
$myposts = get_posts();
foreach ($myposts as $post) {
    setup_postdata($post); ?>
<div class='mpost clearfix'>
    <?php
        if (has_post_thumbnail()) {
        ?>
    <div class='entry-image'>
        <a href='<?php the_permalink(); ?>'>
            <?php the_post_thumbnail('thumbnail'); ?>
        </a>
    </div>
    <?php
        }
        ?>
    <div class='entry-c'>
        <div class='entry-title'>
            <h4>
                <a href='<?php the_permalink(); ?>'>
                    <?php the_title(); ?>
                </a>
            </h4>
        </div>
        <ul class='entry-meta clearfix'>
            <li><i class='icon-calendar3'></i> <?php echo get_the_date(); ?></li>
            <li><i class='icon-comments'></i> <?php comments_number('0'); ?></li>
        </ul>
        <div class='entry-content'>
            <?php the_excerpt(); ?>
        </div>
    </div>
</div>
<?php
    wp_reset_postdata();
    the_post();
}