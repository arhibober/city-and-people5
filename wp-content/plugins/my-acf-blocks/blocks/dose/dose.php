<?php

// Create id attribute allowing for custom "anchor" value.
$id = 'blockquote-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'blockquote';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $className .= ' align' . $block['align'];
}

// Load values and assign defaults.
?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
<?php
    if (has_post_thumbnail())
		the_post_thumbnail("full", ["class" => "card-img-top"]);
	the_title ();
?>
</div>