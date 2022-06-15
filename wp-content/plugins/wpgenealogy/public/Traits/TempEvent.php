<?php 
namespace WPGenealogy_Public\Traits;

trait TempEvent {

	/**
	 * Function to get trees.
	 *
	 * @since    3.0.0
	 */
	public function save_tentative() {

		$data = $this->post_fixer($_POST);

		if(isset($data['people'])) {
			\WPGenealogy\Models\TempEvent::create(array(
				'created_by' => wp_get_current_user()->ID,
				'type' => 'I',
				'gedcom' => $data['people']['gedcom'],
				'personID' => $data['people']['personID'],
				'eventID' => $data['eventID'],
				'eventdate' => $data['eventdate'],
				'eventplace' => $data['eventplace'],
				//'info',
				'note' => $data['note'],
				'user' => wp_get_current_user()->user_login
				//'postdate',
			));
		}
		if(isset($data['family'])) {
			\WPGenealogy\Models\TempEvent::create(array(
				'created_by' => wp_get_current_user()->ID,
				'type' => 'F',
				'gedcom' => $data['family']['gedcom'],
				'familyID' => $data['family']['familyID'],
				'eventID' => $data['eventID'],
				'eventdate' => $data['eventdate'],
				'eventplace' => $data['eventplace'],
				//'info',
				'note' => $data['note'],
				'user' => wp_get_current_user()->user_login
				//'postdate',
			));

		}
		if(isset($_POST['children'])) {
			\WPGenealogy\Models\TempEvent::create(array(
				'created_by' => wp_get_current_user()->ID,
				'type' => 'C',
				'gedcom' => $data['children']['people_obj']['gedcom'],
				'personID' => $data['children']['people_obj']['personID'],
				'eventID' => $data['eventID'],
				'eventdate' => $data['eventdate'],
				'eventplace' => $data['eventplace'],
				//'info',
				'note' => $data['note'],
				'user' => wp_get_current_user()->user_login
				//'postdate',
			));
		}
	}


	public function temp_events_get(){
		$this->get(\WPGenealogy\Models\TempEvent::class);
	}


	public function temp_events_get_alt(){
		$data = $this->post_fixer($_POST);
		
		$this->get_alt(\WPGenealogy\Models\TempEvent::class, $data);
	}

	public function temp_event_get_alt(){
		$data = $this->post_fixer($_POST);

		$this->single(\WPGenealogy\Models\TempEvent::class, $data);
	}


	public function temp_event_delete(){
		$data = $this->post_fixer($_POST);

		$this->delete(\WPGenealogy\Models\TempEvent::class, $data['selected']);
	}


}