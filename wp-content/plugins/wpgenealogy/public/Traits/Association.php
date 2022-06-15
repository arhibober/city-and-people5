<?php 
namespace WPGenealogy_Public\Traits;

trait Association {
	
	/**
	 * Function to get trees.
	 *
	 * @since    3.0.0
	 */
	public function associations_get() {

		/**
		 * get associations.
		 * 
		 */	
		$this->get(\WPGenealogy\Models\Association::class);
	}

	/**
	 * Function to get trees.
	 *
	 * @since    3.0.0
	 */
	public function associations_get_alt() {

		/**
		 * get associations.
		 * 
		 */	
		$data = $this->post_fixer($_POST);
		
		$this->get_alt(\WPGenealogy\Models\Association::class, $data);
	}

	/**
	 * Function to add event.
	 *
	 * @since    3.0.0
	 */
	public function association_add(){

		/**
		 * association add.
		 * 
		 */	
		$data = $this->post_fixer($_POST);
		
		$this->add(\WPGenealogy\Models\Association::class, $data);
	}

	/**
	 * Function to add event.
	 *
	 * @since    3.0.0
	 */
	public function association_update(){

		/**
		 * association update.
		 * 
		 */	
		$data = $this->post_fixer($_POST);
		
		$this->update(\WPGenealogy\Models\Association::class, $data, 'id', $data['id']);
	}

	/**
	 * Function to add event.
	 *
	 * @since    3.0.0
	 */
	public function association_delete(){

		/**
		 * association delete.
		 * 
		 */	
		$data = $this->post_fixer($_POST);
		
		$this->delete(\WPGenealogy\Models\Association::class, $data['selected']);
	}

}