<?php 
namespace WPGenealogy_Public\Traits;

trait Children {

	/**
	 * Function to add event.
	 *
	 * @since    3.0.0
	 */
	public function childrens_get(){

		/**
		 * Get all childrens.
		 *
		 */
		$this->get(\WPGenealogy\Models\Children::class);
	}

	/**
	 * Function to add children.
	 *
	 * @since    3.0.0
	 */
	public function children_add(){
		$data = $this->post_fixer($_POST);
		
		/**
		 * Check if children exist.
		 *
		 */
		$exist = $this->exist(\WPGenealogy\Models\Children::class, array(
			'personID' =>  $data['personID'],
			'familyID' =>  $data['familyID']
		));
		
		if(isset($exist['children']) && $exist['children']) {
			return $this->send_json(array($exist));
		}
		
		/**
		 * Add children.
		 *
		 */
		$this->add(\WPGenealogy\Models\Children::class, $data);

	}

	/**
	 * Function to delete people.
	 *
	 * @since    3.0.0
	 */
	public function children_update() {
		$data = $this->post_fixer($_POST);

		/**
		 * define a array to put data.
		 *
		 */
		$result = array();

		/**
		 * init place
		 *
		 */
		if(isset($data['sealplace']) && $data['sealplace']) {
			
			/**
			 * check if place exist.
			 *
			 */
			$exist = $this->exist(\WPGenealogy\Models\Place::class, array('place'=> $data['sealplace']));

			if( ! isset($exist['place']) ) {

				/**
				 * create place if not exist.
				 *
				 */
				$place = $this->add(\WPGenealogy\Models\Place::class, array(
					'gedcom' => $data['gedcom'],
					'place' => $data['sealplace']
				), true);
			}
		}

		/**
		 * Update children.
		 *
		 */
		$children = $this->update(\WPGenealogy\Models\Children::class, $data, array('id' => $data['id']), true);
		
		/**
		 * Put children to data.
		 *
		 */
		array_push($result, $children);
		
		/**
		 * Put place to data.
		 *
		 */
		if(isset($place) && $place){
			array_push($result, $place);
		}

		/**
		 * Send respond data.
		 *
		 */
		return $this->send_json($result);
	}

	/**
	 * Function to delete people.
	 *
	 * @since    3.0.0
	 */
	public function children_delete() {
		$data = $this->post_fixer($_POST);

		/**
		 * Delete children.
		 *
		 */
		$this->delete(\WPGenealogy\Models\Children::class, $data['selected']);
	}


	/**
	 * Function to add children.
	 *
	 * @since    3.0.0
	 */
	public function children_oreder(){
		
		/**
		 * Check if children exist.
		 *
		 */
		$data = $this->post_fixer($_POST);
		if($data) {
			foreach ($data as $key => $value) {
				# code...
				$children = \WPGenealogy\Models\Children::find($value);
				$children->update(['ordernum' => $key]);
			}
		}


	}



}