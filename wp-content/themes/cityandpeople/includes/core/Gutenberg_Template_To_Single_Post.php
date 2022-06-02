<?php
class Gutenberg_Template_To_Single_Post
{
    public function __construct()
    {
    }
    public function gutenberg_template_to_single_post()
    {
        $post_type_object = get_post_type_object('post');
        $post_type_object->template = array(
            array('core/heading', array(
                'placeholder' => 'Add subtitle...',
            )),
            array('core/paragraph', array(
                'placeholder' => 'Add description...',
            )),
            array('core/spacer', array(
                'height' => '20px',
                'class' => 'wp-block-spacer',
            )),
            array('core/columns', array(), array(
                array('core/column', array(), array(
                    array('core/image', array()),
                )),
                array('core/column', array(), array(
                    array('core/paragraph', array(
                        'placeholder' => 'Add a inner paragraph',
                    )),
                )),
                array('core/column', array(), array(
                    array('core/paragraph', array(
                        'placeholder' => 'Add a inner paragraph',
                    )),
                )),
            )),
        );
    }
}
