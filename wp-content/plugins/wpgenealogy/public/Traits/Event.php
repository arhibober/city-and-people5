<?php

namespace WPGenealogy_Public\Traits;

trait Event {

	/**
	 * Function to to add event.
	 *
	 * @since    3.0.0
	 */
	public function events_get() {

		/**
		 * 
		 * get all events.
		 */	
		$this->get(\WPGenealogy\Models\Event::class);
	}

	/**
	 * Function to to add event.
	 *
	 * @since    3.0.0
	 */
	public function event_add() {
		$data = $this->post_fixer($_POST);
		
		/**
		 * Define a array to store response data.
		 * 
		 */
		$result = array();
		
		/**
		 * Check if address 
		 *
		 */		
		if (isset($data['address']) && $data['address']) {
		
			/**
			 *	Create address if address data. 
			 *
			 */
			$address = $this->add(\WPGenealogy\Models\Address::class , $data['address'], true);
			
			/**
			 *	Set addressID if event 
			 *
			 */
			$data['addressID'] = $address['address']->id;
		}

		/**
		 * Check if eventplace 
		 *
		 */		
		if (isset($data['eventdate']) && $data['eventdate']) {
			$data['eventdatetr'] = $this->convertDate($this->removeSpace($data['eventdate']));
		}



		/**
		 * Check if eventplace 
		 *
		 */		
		if (isset($data['eventplace']) && $data['eventplace']) {

			/**
			 * Set place  
			 *
			 */
			$data['place'] = $data['eventplace'];

			/**
			 * Create place  
			 *
			 */
			$place = $this->add(\WPGenealogy\Models\Place::class , $data, true);
		}

		/**
		 * Create event 
		 *
		 */		
		$event = $this->add(\WPGenealogy\Models\Event::class , $data, true);
		
		/**
		 * Put event to response data. 
		 *
		 */		
		if(isset($event) && $event){
			array_push($result, $event);
		}
		
		/**
		 * Put address to response data. 
		 *
		 */		
		if(isset($address) && $address){
			array_push($result, $address);
		}
		
		/**
		 * Put place to response data. 
		 *
		 */		
		if(isset($place) && $place){
			array_push($result, $place);
		}
		
		/**
		 * respons data. 
		 *
		 */		
		$this->send_json($result);

	}

	/**
	 * Function to to add event.
	 *
	 * @since    3.0.0
	 */
	public function event_update() {
		$data = $this->post_fixer($_POST);

		unset($data['eventtype']);
		
		/**
		 * Define a array to store response data.
		 *
		 */			
		$result = array();

		/**
		 * Check if address
		 *
		 */			
		if (isset($data['address']) && $data['address'] && $data['address']['id']) {

			/**
			 *  Create address if address data.
			 *
			 */			
			$address = $this->update(\WPGenealogy\Models\Address::class , $data['address'], array (
				'id' => $data['address']['id']
			), true);

		}

		/**
		 * Check if address
		 *
		 */			
		if (isset($data['address']) && $data['address'] && !$data['address']['id']) {

			/**
			 *  Create address if address data.
			 *
			 */			
			$address = $this->add(\WPGenealogy\Models\Address::class , $data['address'], true);

			/**
			 *  Set addressID if event
			 *
			 */			
			$data['addressID'] = $address['address']->id;
		}

		/**
		 * Check if eventplace
		 *
		 */			
		if (isset($data['eventplace']) && $data['eventplace']) {

			/**
			 *  Set place 
			 *
			 */			
			$data['place'] = $data['eventplace'];

			$exist = $this->exist(\WPGenealogy\Models\Place::class , array (
				'place' => $data['place']
			));

			if(!isset($exist['place']) ) {
				$place = $this->add(\WPGenealogy\Models\Place::class , $data, true);
			}
		}


		$event = $this->update(\WPGenealogy\Models\Event::class , $data, array (
			'id' => $data['id']
		), true);

		/**
		 * Put event to response data.
		 *
		 */			
		if(isset($event) && $event){
			array_push($result, $event);
		}

		/**
		 * Put address to response data.
		 *
		 */			
		if(isset($address) && $address){
			array_push($result, $address);
		}

		/**
		 * Put place to response data.
		 *
		 */			
		if(isset($place) && $place){
			array_push($result, $place);
		}

		/**
		 * respons data.
		 *
		 */
		$this->send_json($result);

	}

	/**
	 * Function to to delete people.
	 *
	 * @since    3.0.0
	 */
	public function event_delete() {
		$data = $this->post_fixer($_POST);

		/**
		 * delete event.
		 *
		 */
		$this->delete(\WPGenealogy\Models\Event::class, $data['selected']);
	}


	/**
	 * Function to to add address through event.
	 *
	 * @since    3.0.0
	 */
	public function event_add_or_update() {
		$data = $this->post_fixer($_POST);


		$result = array();

		if ($data['address'] && !$data['address']['id']) {

			/**
			 * Add address if address id not exist.
			 *
			 */
			$address = $this->add(\WPGenealogy\Models\Address::class, $data['address'], true);

			$data['addressID'] = $address['address']->id;
		}

		if ($data['address'] && $data['address']['id']) {

			/**
			 * Update address if address id exist.
			 *
			 */
			$address = $this->update(\WPGenealogy\Models\Address::class, $data['address'], array (
				'id' => $data['address']['id']
			), true);
		}

		if (!isset($data['id']) && !$data['id']) {

			/**
			 * Add event if event id not exist.
			 *
			 */
			$event = $this->add(\WPGenealogy\Models\Event::class, $data, true);
		}

		if (isset($data['id']) && $data['id']) {

			/**
			 * Update event if event id exist.
			 *
			 */
			$event = $this->update(\WPGenealogy\Models\Event::class, $data, array (
				'id' => $data['id']
			), true);
		}

		/**
		 * Put place to response data.
		 *
		 */
		if(isset($event) && $event){
			array_push($result, $event);
		}

		/**
		 * Put address to response data.
		 *
		 */
		if(isset($address) && $address){
			array_push($result, $address);
		}

		/**
		 * respons data.
		 *
		 */
		$this->send_json($result);

	}

}

