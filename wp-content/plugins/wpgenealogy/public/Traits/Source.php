<?php 
namespace WPGenealogy_Public\Traits;

trait Source {

	/**
	 * Function to get trees.
	 *
	 * @since    3.0.0
	 */
	public function sources_get() {

		/**
		 * get all source
		 *
		 */
		$this->get(\WPGenealogy\Models\Source::class);
	}
	/**
	 * Function to get trees.
	 *
	 * @since    3.0.0
	 */
	public function sources_get_alt() {

		/**
		 * get all source
		 *
		 */
		$data = $this->post_fixer($_POST);
		$this->get_alt(\WPGenealogy\Models\Source::class, $data);
	}




	/**
	 * Function to get trees.
	 *
	 * @since    3.0.0
	 */
	public function source_add() {

		/**
		 * add source
		 *
		 */
		$data = $this->post_fixer($_POST);
		$this->add(\WPGenealogy\Models\Source::class, $data);
	}
	
	/**
	 * Function to get trees.
	 *
	 * @since    3.0.0
	 */
	public function source_delete() {

		/**
		 * Delete source
		 *
		 */
		$data = $this->post_fixer($_POST);
		$this->delete(\WPGenealogy\Models\Source::class, $data);
	}
}