<?php 
namespace WPGenealogy_Public\Traits;

trait Branch {

	/**
	 * Function to get branches.
	 *
	 * @since    3.0.0
	 */
	public function branches_get() {

		/**
		 * Get all branch.
		 *
		 */
		$this->get(\WPGenealogy\Models\Branch::class);
	}
	/**
	 * Function to get address.
	 *
	 * @since    3.0.0
	 */
	public function branches_get_alt() {

		/**
		 * get all people
		 *
		 */
		$data = $this->post_fixer($_POST);
		
		$this->get_alt(\WPGenealogy\Models\Branch::class, $_POST);
	}
	/**
	 * Function to get address.
	 *
	 * @since    3.0.0
	 */
	public function branch_get_alt() {

		/**
		 * get all people
		 *
		 */
		$data = $this->post_fixer($_POST);
		
		$this->single(\WPGenealogy\Models\Branch::class, $data);
	}


	/**
	 * Function to add branch.
	 *
	 * @since    3.0.0
	 */
	public function branch_add() {	

		/**
		 * Check if branch exist.
		 *
		 */
		$data = $this->post_fixer($_POST);
		
		$exist = $this->exist(\WPGenealogy\Models\Branch::class, array(
			'branch' =>  $data['branch'],
			'gedcom' =>  $data['gedcom']
		));


		if(isset($exist['branch']) && $exist['branch']) {
			return $this->send_json(array($exist));
		}



		/**
		 * Add branch.
		 *
		 */
		$this->add(\WPGenealogy\Models\Branch::class, $data);
	}

	/**
	 * Function to update branch.
	 *
	 * @since    3.0.0
	 */
	public function branch_update() {

		/**
		 * Update branch.
		 *
		 */
		$data = $this->post_fixer($_POST);
		
		$this->update(\WPGenealogy\Models\Branch::class, $data, array( 
			'id' => $data['id']
		));
	}

	/**
	 * Function to delete branch.
	 *
	 * @since    3.0.0
	 */
	public function branch_delete() {

		/**
		 * Update delete.
		 *
		 */
		$data = $this->post_fixer($_POST);
		
		$this->delete(\WPGenealogy\Models\Branch::class, $data['selected']);
	}

}
