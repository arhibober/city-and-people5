<?php 
namespace WPGenealogy_Public\Traits;

trait Family {

	/**
	 * Function to get people.
	 *
	 * @since    3.0.0
	 */
	public function families_get(){	
		
		/**
		 * get all families.
		 *
		 */
		$this->get(\WPGenealogy\Models\Family::class);
	}

	/**
	 * Function to get people.
	 *
	 * @since    3.0.0
	 */
	public function families_get_alt(){	
		/**
		 * get all people
		 *
		 */
		$data = $this->post_fixer($_POST);

		$this->get_alt(\WPGenealogy\Models\Family::class, $data);
	}

	/**
	 * Function to get people.
	 *
	 * @since    3.0.0
	 */
	public function family_get_alt(){	
		/**
		 * get all people
		 *
		 */
		$data = $this->post_fixer($_POST);

		$family = $this->single(\WPGenealogy\Models\Family::class, $data, true);
		wp_send_json($family, 200 );
	}
	/**
	 * Function to get people.
	 *
	 * @since    3.0.0
	 */
	public function family_get_full(){	
		$data = $this->post_fixer($_POST);

		$family = \WPGenealogy\Models\Family::find($data['id']);

		if(!$family){
			return $this->url_does_not_exist();
		}

		if($family->husband){
			$family->husbObj = \WPGenealogy\Models\People::where('personID', $family->husband)->where('gedcom', $family->gedcom)->first();
			$parents = \WPGenealogy\Models\Children::where('personID', $family->husbObj->personID)->where('gedcom', $family->husbObj->gedcom)->get();
			if($parents) {
				foreach ($parents as $key => $parent) {
					$parent->family = \WPGenealogy\Models\Family::where('familyID', $parent->familyID)->where('gedcom', $parent->gedcom)->first();
					if($parent->family->husband){
						$parent->family->husbObj = \WPGenealogy\Models\People::where('personID', $parent->family->husband)->where('gedcom', $parent->family->gedcom)->first();
					}
					if($parent->family->wife){
						$parent->family->wifeObj = \WPGenealogy\Models\People::where('personID', $parent->family->wife)->where('gedcom', $parent->family->gedcom)->first();
					}
				}
			}
			$family->husbObj->parents = $parents;
		}
		if($family->wife){
			$family->wifeObj = \WPGenealogy\Models\People::where('personID', $family->wife)->where('gedcom', $family->gedcom)->first();
			$parents = \WPGenealogy\Models\Children::where('personID', $family->wifeObj->personID)->where('gedcom', $family->wifeObj->gedcom)->get();
			if($parents) {
				foreach ($parents as $key => $parent) {
					$parent->family = \WPGenealogy\Models\Family::where('familyID', $parent->familyID)->where('gedcom', $parent->gedcom)->first();
					if($parent->family->husband){
						$parent->family->husbObj = \WPGenealogy\Models\People::where('personID', $parent->family->husband)->where('gedcom', $parent->family->gedcom)->first();
					}
					if($parent->family->wife){
						$parent->family->wifeObj = \WPGenealogy\Models\People::where('personID', $parent->family->wife)->where('gedcom', $parent->family->gedcom)->first();
					}
				}
			}
			$family->wifeObj->parents = $parents;
		}
		$childrens = \WPGenealogy\Models\Children::where('familyID', $family->familyID)->where('gedcom', $family->gedcom)->get();
		if($childrens) {
			foreach ($childrens as $key => $children) {
				$children->person = \WPGenealogy\Models\People::where('personID', $children->personID)->where('gedcom', $children->gedcom)->first();
			}
		}
		$family->childrens = $childrens;
		wp_send_json($family, 200 );
	}


	/**
	 * Function to get people.
	 *
	 * @since    3.0.0
	 */
	public function family_add(){
		$data = $this->post_fixer($_POST);

		/**
		 * Define array to store response data.
		 *
		 */
		$result = array();

		/**
		 * check if place is exist or not.
		 *
		 */
		
		$exist = $this->exist(\WPGenealogy\Models\Family::class, array(
			'gedcom'=> $data['gedcom'],
			'husband'=> $data['husband'],
			'wife'=> $data['wife']
		));
		
		/**
		 * 
		 * Create a family from posted data.
		 */	
		if( $exist ) {
			return $this->send_json($exist);
		}



		/**
		 * 
		 * Create a family from posted data.
		 */		
		$family = $this->add(\WPGenealogy\Models\Family::class, $data, true);
		

		
		/**
		 * 
		 * Check if childID exist
		 */		
		if(isset($data['childID']) && $data['childID'] && $family && $family['family']){

			$data['personID'] = $data['childID'];

			/**
			 * 
			 * get familyID from new family.
			 */	
			$post['familyID'] = $family['family']->familyID;
		
			/**
			 * 
			 * Create a new child for this family.
			 */			
			$children = $this->add(\WPGenealogy\Models\Children::class, $data, true);
		
			/**
			 * 
			 * Push children data to response array.
			 */			
			array_push($result, $children);
		}
		
		/**
		 * 
		 * response data
		 */				
		$this->send_json($family);
	}

	/**
	 * Function to update people.
	 *
	 * @since    3.0.0
	 */
	public function family_update() {
		$data = $this->post_fixer($_POST);

		/**
		 * 
		 * update family.
		 */	
		$this->update(\WPGenealogy\Models\Family::class, $data, array('id'=> $data['id']), false);
	}

	/**
	 * Function to delete people.
	 *
	 * @since    3.0.0
	 */
	public function family_delete() {
		$data = $this->post_fixer($_POST);

		/**
		 * 
		 * delete family.
		 */	
		$this->delete(\WPGenealogy\Models\Family::class, $data['selected']);
	}
}