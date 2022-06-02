<?php
/**
 * Plugin Name: My ACF blocks
 * Description: Creating the Guttenberg blocks
 * Version: 1.0
 * Author: Arhibober.
 */

function mab_register_acf_block_types()
{
    acf_register_block_type([
        'name' => 'people_dose',
        'title' => __('People dose'),
        'description' => __("You can put in human's portrait and initiales here."),
        'render_template' => dirname(__file__) . '/blocks/dose/dose.php',
        'enqueue_style' => plugin_dir_url(__FILE__) . '/blocks/dose/dose.css',
    ]);
}

if (function_exists('acf_register_block_type')) {
    add_action('acf/init', 'mab_register_acf_block_types');
}