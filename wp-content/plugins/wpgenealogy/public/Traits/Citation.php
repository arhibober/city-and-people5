<?php 
namespace WPGenealogy_Public\Traits;

trait Citation {
	
	/**
	 * Function to get trees.
	 *
	 * @since    3.0.0
	 */
	public function citations_get() {

		/**
		 * get citations.
		 * 
		 */	
		$this->get(\WPGenealogy\Models\Citation::class);
	}
	/**
	 * Function to get trees.
	 *
	 * @since    3.0.0
	 */
	public function citations_get_alt() {
		$data = $this->post_fixer($_POST);
		
		$this->get_alt(\WPGenealogy\Models\Citation::class, $data);
	}
	/**
	 * Function to add event.
	 *
	 * @since    3.0.0
	 */
	public function citation_add(){

		/**
		 * citation add.
		 * 
		 */	
		$data = $this->post_fixer($_POST);
		
		$this->add(\WPGenealogy\Models\Citation::class, $data);
	}

	/**
	 * Function to add event.
	 *
	 * @since    3.0.0
	 */
	public function citation_update(){

		/**
		 * citation update.
		 * 
		 */	
		$data = $this->post_fixer($_POST);
		
		$this->update(\WPGenealogy\Models\Citation::class, $data, 'id', $data['id']);
	}

	/**
	 * Function to add event.
	 *
	 * @since    3.0.0
	 */
	public function citation_delete(){

		/**
		 * citation delete.
		 * 
		 */	
		$data = $this->post_fixer($_POST);
		
		$this->delete(\WPGenealogy\Models\Citation::class, $data['selected']);
	}

}