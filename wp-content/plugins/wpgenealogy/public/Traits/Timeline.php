<?php 
namespace WPGenealogy_Public\Traits;

trait Timeline {
	
	/**
	 * Function to get trees.
	 *
	 * @since    3.0.0
	 */
	public function timelines_get() {
		
		/**
		 * get all timeline
		 *
		 */
		$this->get(\WPGenealogy\Models\Timeline::class);
	}
	/**
	 * Function to get trees.
	 *
	 * @since    3.0.0
	 */
	public function timelines_get_alt() {
		
		/**
		 * get all timeline
		 *
		 */
		$data = $this->post_fixer($_POST);
		$this->get_alt(\WPGenealogy\Models\Timeline::class, $data);
	}

	/**
	 * Function to get trees.
	 *
	 * @since    3.0.0
	 */
	public function timeline_get_alt() {

		
		/**
		 * get all timeline
		 *
		 */
		$data = $this->post_fixer($_POST);

		$this->single(\WPGenealogy\Models\Timeline::class, $data);
	}

	/**
	 * Function to get trees.
	 *
	 * @since    3.0.0
	 */
	public function timeline_update() {

		
		/**
		 * get all timeline
		 *
		 */
		$data = $this->post_fixer($_POST);
		$this->update(\WPGenealogy\Models\Timeline::class, $data, ['id' => $data['id']]);
	}
	/**
	 * Function to get trees.
	 *
	 * @since    3.0.0
	 */
	public function timeline_add() {

		
		/**
		 * get all timeline
		 *
		 */
		$data = $this->post_fixer($_POST);
		$this->add(\WPGenealogy\Models\Timeline::class, $data);
	}




	/**
	 * Function to delete people.
	 *
	 * @since    3.0.0
	 */
	public function timeline_delete() {

		/**
		 * delete note_link.
		 *
		 */
		$data = $this->post_fixer($_POST);
		$this->delete(\WPGenealogy\Models\Timeline::class, $data['selected']);
	}

}