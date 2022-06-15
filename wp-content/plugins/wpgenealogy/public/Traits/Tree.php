<?php 
namespace WPGenealogy_Public\Traits;

trait Tree {


	/**
	 * Function to get trees.
	 *
	 * @since    3.0.0
	 */
	public function trees_get() {
		
		/**
		 * get all trees.
		 *
		 */
		$this->get(\WPGenealogy\Models\Tree::class);
	}
	/**
	 * Function to get address.
	 *
	 * @since    3.0.0
	 */
	public function trees_get_alt() {

		/**
		 * get all trees
		 *
		 */
		$data = $this->post_fixer($_POST);

		$this->get_alt(\WPGenealogy\Models\Tree::class, $data);
	}

	/**
	 * Function to get address.
	 *
	 * @since    3.0.0
	 */
	public function tree_get_alt() {

		/**
		 * get all trees
		 *
		 */
		$data = $this->post_fixer($_POST);

		$this->single(\WPGenealogy\Models\Tree::class, $data);
	}




	/**
	 * Function to add tree.
	 *
	 * @since    3.0.0
	 */
	public function tree_add() {

		/**
		 * Check if tree exist.
		 *
		 */
		$data = $this->post_fixer($_POST);

		$exist = $this->exist(\WPGenealogy\Models\Tree::class, array(
			'gedcom' => $data['gedcom']
		));

		if($exist) {
			return $this->send_json($exist);
		}

		$result = $this->add(\WPGenealogy\Models\Tree::class, $data, true);



		if($result->tree->id) {
			$data = array(
			    'branch' => 'Default',
			    'gedcom' => $result->tree->gedcom,
			    'description' => 'description'

			);
			$this->add(\WPGenealogy\Models\Branch::class, $data, true);
		}

        $data = array (
            'tree' => $result->tree,
            'messages' => array(
                array(
                    'type' => 'alert-success',
                    'text' => ucfirst('tree') . ' has been added successfully.'
                )
            )
        );

        /**
         * Return response to frontend.
         *
         */
        return $this->send_json($result);

	}

	/**
	 * Function to update tree.
	 *
	 * @since    3.0.0
	 */
	public function tree_update() {

		/**
		 * Update tree
		 *
		 */
		$data = $this->post_fixer($_POST);

		$this->update(\WPGenealogy\Models\Tree::class, $data, array('gedcom' =>  $data['gedcom']));
	}

	/**
	 * Function to delete tree.
	 *
	 * @since    3.0.0
	 */
	public function tree_delete() {
		
		$data = $this->post_fixer($_POST);

		/**
		 * Delete tree
		 *
		 */
		$this->delete(\WPGenealogy\Models\Tree::class, $data['selected']);
	}
}