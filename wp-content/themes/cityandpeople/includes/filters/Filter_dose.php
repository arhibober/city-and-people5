<?php
class Filter_dose
{
	public function __construct()
	{
	}
	public function my_filter_function ()
	{
		$url = 'http://' . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ];
		$current_post_id = url_to_postid( $url );
		echo " cpid: ".$current_post_id;
		$post_editor_context = new WP_Block_Editor_Context (array ("post" => get_post($current_post_id)));
		echo " pec: ";
		print_r ($post_editor_context);
		$allowed_blocks = get_allowed_block_types ($post_editor_context);
		if ((get_post_type ($current_post_id) != "city_object") || (!is_object_in_term ($current_post_id, "city_object_taxonomy", "liudyna")))
		 $blocks_to_remove = [
			'my-acf-blocks/people_dose',
		];
		return array_diff ($allowed_blocks, $blocks_to_remove);
	}
}