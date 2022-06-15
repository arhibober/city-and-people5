<?php 
namespace WPGenealogy_Public\Traits;

trait People {

	/**
	 * Function to get people.
	 *
	 * @since    3.0.0
	 */
	public function peoples_get(){	

		/**
		 * get all people
		 *
		 */
		$this->get(\WPGenealogy\Models\People::class);
	}

	/**
	 * Function to get people.
	 *
	 * @since    3.0.0
	 */
	public function peoples_get_alt(){	
		/**
		 * get all people
		 *
		 */
		$data = $this->post_fixer($_POST);
		
		$this->get_alt(\WPGenealogy\Models\People::class, $data);
	}

	/**
	 * Function to get people.
	 *
	 * @since    3.0.0
	 */
	public function people_get_alt(){	
		/**
		 * get all people
		 *
		 */
		$data = $this->post_fixer($_POST);
		
		$people = $this->single(\WPGenealogy\Models\People::class, $data, true);

		$people->parents_families = $people->parents_families();
		$people->spouses_families = $people->spouses_families();
		$people->events = $people->events;
		$people->note_links = $people->note_links;

		wp_send_json($people, 200 );
	}

	/**
	 * Function to get people.
	 *
	 * @since    3.0.0
	 */
	public function people_get_full(){	
		/**
		 * get all people
		 *
		 */
		$data = $this->post_fixer($_POST);
		
		$people = \WPGenealogy\Models\People::find($data['id']);
		
		if(!$people){
			return $this->url_does_not_exist();
		}

		$families = \WPGenealogy\Models\Family::where('husband', $people->personID)->orWhere('wife', $people->personID)->where('gedcom', $people->gedcom)->get();
		if($families) {
			foreach ($families as $key => $family) {
				if($family->husband){
					$family->husbObj = \WPGenealogy\Models\People::where('personID', $family->husband)->where('gedcom', $family->gedcom)->first();
				}
				if($family->wife){
					$family->wifeObj = \WPGenealogy\Models\People::where('personID', $family->wife)->where('gedcom', $family->gedcom)->first();
				}
				$family->childrens = [];
				$childrens = \WPGenealogy\Models\Children::where('familyID', $family->familyID)->where('gedcom', $family->gedcom)->get();
				if($childrens) {
					foreach ($childrens as $key => $children) {
						$children->person = \WPGenealogy\Models\People::where('personID', $children->personID)->where('gedcom', $children->gedcom)->first();
					}
				}
				$family->childrens = $childrens;
			}
		}
		$people->families = $families;
		$parents = \WPGenealogy\Models\Children::where('personID', $people->personID)->where('gedcom', $people->gedcom)->get();
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
		$people->parents = $parents;
		
		wp_send_json($people, 200 );
	}

	/**
	 * Function to add people.
	 *
	 * @since    3.0.0
	 */
	public function people_add() {
		/**
		 * check if place is exist or not.
		 *
		 */
		$data = $this->post_fixer($_POST);
		
		$exist = $this->exist(\WPGenealogy\Models\People::class, array(
			'gedcom'=> $data['gedcom'],
			'personID'=> $data['personID'],
		));

		if($exist){
			return $this->send_json($exist);
		}

		/**
		 * Assign places array.
		 *
		 */
		$places = array();

		/**
		 * push all place if exist.
		 *
		 * * * * * * * * * * * * * 
		 *
		 * birth place
		 *
		 */
		if(isset($data['birthplace']) && $data['birthplace']) {
			array_push($places, $data['birthplace']);
		}

		/**
		 * alter birth place
		 *
		 */
		if(isset($data['altbirthplace']) && $data['altbirthplace']) {
			array_push($places, $data['altbirthplace']);
		}

		/**
		 * death place
		 *
		 */
		if(isset($data['deathplace']) && $data['deathplace']) {
			array_push($places, $data['deathplace']);
		}

		/**
		 * burial place
		 *
		 */
		if(isset($data['burialplace']) && $data['burialplace']) {
			array_push($places, $data['burialplace']);
		}

		/**
		 * bapt place
		 *
		 */
		if(isset($data['baptplace']) && $data['baptplace']) {
			array_push($places, $data['baptplace']);
		}

		/**
		 * conf place
		 *
		 */
		if(isset($data['confplace']) && $data['confplace']) {
			array_push($places, $data['confplace']);
		}

		/**
		 * init place
		 *
		 */
		if(isset($data['initplace']) && $data['initplace']) {
			array_push($places, $data['initplace']);
		}

		/**
		 * endl place
		 *
		 */
		if(isset($data['endlplace']) && $data['endlplace']) {
			array_push($places, $data['endlplace']);
		}

		/**
		 * add all place if not exist.
		 *
		 */
		foreach ($places as $key => $place) {

			/**
			 * check if place is exist or not.
			 *
			 */
			$exist = $this->exist(\WPGenealogy\Models\Place::class, array('place'=> $place));


			if( ! isset($exist['place']) ) {

				/**
				 * create place if not exist.
				 *
				 */
				$place = $this->add(\WPGenealogy\Models\Place::class, array(
					'gedcom' => $data['gedcom'],
					'place' => $place
				), true);
				
				$places[$key] = $place;
			}
			else {
				unset($places[$key]);
			}
		}

		/**
		 * create people.
		 *
		 */
		$people = $this->add(\WPGenealogy\Models\People::class, $data, true);

		/**
		 * define data to response.
		 *
		 */
		$data = array();

		/**
		 * put people to data.
		 *
		 */
		array_push($data, $people);

		/**
		 * put place to data.

		 
		 *
		 */
		array_push($data, $places);

		/**
		 * send response.
		 *
		 */
		return $this->send_json($people);

	}

	/**
	 * Function to update people.
	 *
	 * @since    3.0.0
	 */
	public function people_update() {

		/**
		 * update people.
		 *
		 */
		$data = $this->post_fixer($_POST);
		
		$this->update(\WPGenealogy\Models\People::class, $data, array('id'=> $data['id']), false);
	}

	/**
	 * Function to delete people.
	 *
	 * @since    3.0.0
	 */
	public function people_delete() {

		/**
		 * delete people.
		 *
		 */
		$data = $this->post_fixer($_POST);

		
		$this->delete(\WPGenealogy\Models\People::class, $data['selected']);

		

	}

	/**
	 * Function to delete people.
	 *
	 * @since    3.0.0
	 */
	public function people_unlink_as_child() {
		/**
		 * delete people.
		 *
		 */
		$data = $this->post_fixer($_POST);
		
		$children = \WPGenealogy\Models\Children::where('familyID', $data['familyID'])->where('personID', $data['personID'])->first();
		$this->delete(\WPGenealogy\Models\Children::class, array($children->id));
		die();
	}

		/**
	 * Function to delete people.
	 *
	 * @since    3.0.0
	 */
	public function people_unlink_as_spouse() {
		/**
		 * delete people.
		 *
		 */
		$data = $this->post_fixer($_POST);
		
		$family = \WPGenealogy\Models\Family::where('id', $data['familyID'])->first();
		if($family->husband == $data['personID']) {
			$family->husband = '';
		}
		if($family->wife == $data['personID']) {
			$family->wife = '';
		}
		$family->save();
		die();
	}
}