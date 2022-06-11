<?php
class Custom_Taxonomies
{
	public function __construct()
	{
	}
	public function cityandpeople_register_taxonomies_init()
	{
		register_taxonomy('city_object_taxonomy', array('city_object'), array(
			'hierarchical'  => true,
			'labels'        => array(
				'name'              => __('City Object Taxonomies', 'taxonomy general name'),
				'singular_name'     => __('City Object Taxonomy', 'taxonomy singular name'),
				'search_items'      => __('Search City Object Taxonomy'),
				'all_items'         => __('All City Object Taxonomies'),
				'parent_item'       => __('Parent City Object Taxonomy'),
				'parent_item_colon' => __('Parent City Object Taxonomy:'),
				'edit_item'         => __('Edit City Object Taxonomy'),
				'update_item'       => __('Update City Object Taxonomy'),
				'add_new_item'      => __('Add New City Object Taxonomy'),
				'new_item_name'     => __('New City Object Taxonomy'),
				'menu_name'         => __('City Object Taxonomy'),
			),
			'show_admin_column' => true,
			'show_in_rest' => true, // Needed for tax to appear in Gutenberg editor.
			'hierarchical' => true,
			'show_ui'       => true,
			'query_var'     => true,
		));
	}
}