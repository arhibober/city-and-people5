<?php 
namespace WPGenealogy_Public\Traits;

trait EventType {
	/**
	 * Function to add event.
	 *
	 * @since    3.0.0
	 */
	public function event_types_get(){

		/**
		 * 
		 * get event types.
		 */	
		$this->get(\WPGenealogy\Models\EventType::class);
	}

	/**
	 * Function to add event.
	 *
	 * @since    3.0.0
	 */
	public function event_types_get_alt(){

		/**
		 * 
		 * get event types.
		 */	
		$data = $this->post_fixer($_POST);
		
		$this->get_alt(\WPGenealogy\Models\EventType::class, $data);
	}

	/**
	 * Function to add event.
	 *
	 * @since    3.0.0
	 */
	public function event_type_get_alt(){

		/**
		 * 
		 * get event types.
		 */	
		$data = $this->post_fixer($_POST);

		$this->single(\WPGenealogy\Models\EventType::class, $data);
	}


	/**
	 * Function to add event.
	 *
	 * @since    3.0.0
	 */
	public function event_type_update(){

		/**
		 * 
		 * get event types. update($model, $data, $keyValue, $get=false)
		 */	
		$data = $this->post_fixer($_POST);

		$this->update(\WPGenealogy\Models\EventType::class, $data, ['id' =>  $data['id']]);
	}


	/**
	 * Function to add event.
	 *
	 * @since    3.0.0
	 */
	public function event_type_add(){


		$data = $this->post_fixer($_POST);

		$exist = $this->exist(\WPGenealogy\Models\EventType::class, array(
			'tag'=> $data['tag'],
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
		 * get event types. update($model, $data, $keyValue, $get=false)
		 */	
		$this->add(\WPGenealogy\Models\EventType::class, $data);
	}


	/**
	 * Function to add event.
	 *
	 * @since    3.0.0
	 */
	public function event_type_delete(){
		$data = $this->post_fixer($_POST);

		/**
		 * citation delete.
		 * 
		 */	
		$this->delete(\WPGenealogy\Models\EventType::class, $data['selected']);
	}


}