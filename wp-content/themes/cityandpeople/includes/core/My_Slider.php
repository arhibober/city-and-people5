<?php
class My_Slider
{
    public function __construct()
    {
    }
    public function my_register_blocks()
    {
        if (function_exists('acf_register_block_type')) {
            wp_register_style('slick', 'http://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css', array(), '1.8.1');
            wp_enqueue_style('slick');
            wp_register_style('slick-theme', 'http://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css', array(), '1.8.1');
            wp_enqueue_style('slick-theme');
            wp_register_script('slick', 'http://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array('jquery'), '1.8.1', true);
            wp_enqueue_script('slick');
            wp_register_style('block-slider', get_template_directory_uri() . '/blocks/slider/slider.css', array(), '1.0.0');
            wp_enqueue_style('block-slider');
            wp_register_script('block-slider', get_template_directory_uri() . '/blocks/slider/slider.js', array(), '1.0.0', true);
            wp_enqueue_script('block-slider');
            acf_register_block_type([
                'name' => 'slider',
                'title' => __('Slider'),
                'description' => __('A custom slider block.'),
                'render_template' => dirname(dirname(__file__)) . '/blocks/slider/slider.php',
                'category' => 'formatting',
                'icon' => 'images-alt2',
                'align' => 'full',
                'enqueue_assets' => function () {
                },
            ]);
        }
    }
}