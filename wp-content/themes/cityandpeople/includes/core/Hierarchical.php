<?php
class Hierarchical
{
	public static function sort_terms_hierarchicaly(&$cats, &$into, $parentId = 0)
	{
		foreach ($cats as $i => $cat) {
			if ($cat->parent == $parentId) {
				$into[$cat->term_id] = $cat;
				unset($cats[$i]);
			}
		}

		foreach ($into as $top_cat) {
			$top_cat->children = array();
			self::sort_terms_hierarchicaly($cats, $top_cat->children, $top_cat->term_id);
		}
	}

	public static function child_list($taxonomies)
	{
		if (count($taxonomies) > 0)
			echo "<ul>";
		foreach ($taxonomies as $taxonomy) {
			echo "<li>
				<input type='checkbox' name='taxonomies[]' value='" . $taxonomy->term_id . "'/>" . $taxonomy->name;
			self::child_list($taxonomy->children);
			echo "</li>";
		}
		if (count($taxonomies) > 0)
			echo "</ul>";
	}
}