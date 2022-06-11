<?php

// Create id attribute allowing for custom "anchor" value.
$id = 'slider-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'slider';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $className .= ' align' . $block['align'];
}
if ($is_preview) {
    $className .= ' is-admin';
}

?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <?php if (have_rows('slides')) : ?>
    <div class="slides">
        <?php while (have_rows('slides')) : the_row();
                $image = get_sub_field('image');
            ?>
        <div>
            <?php echo wp_get_attachment_image($image['id'], 'full'); ?>
        </div>
        <?php endwhile; ?>
    </div>
    <?php else : ?>
    <p><?php _e("Please add some slides."); ?></p>
    <?php endif; ?>
</div>