<?php 
namespace WPGenealogy_Public\Traits;
use WPGenealogy\Eloquent\Facades\DB;

trait Place {
	
	/**
	 * Function to get trees.
	 *
	 * @since    3.0.0
	 */
	public function places_get() {
		
		/**
		 * get all place
		 *
		 */
		$this->get(\WPGenealogy\Models\Place::class);
	}
	/**
	 * Function to get trees.
	 *
	 * @since    3.0.0
	 */
	public function places_get_alt() {
		
		/**
		 * get all place
		 *
		 */
		$data = $this->post_fixer($_POST);
		
		$this->get_alt(\WPGenealogy\Models\Place::class, $data);
	}

	/**
	 * Function to get trees.
	 *
	 * @since    3.0.0
	 */
	public function place_get_alt() {

		
		/**
		 * get all place
		 *
		 */
		$data = $this->post_fixer($_POST);
		
		$this->single(\WPGenealogy\Models\Place::class, $data);
	}

	/**
	 * Function to get trees.
	 *
	 * @since    3.0.0
	 */
	public function place_update() {

		
		/**
		 * get all place
		 *
		 */
		$data = $this->post_fixer($_POST);
		
		$this->update(\WPGenealogy\Models\Place::class, $data, ['id' => $data['id']]);
	}
	/**
	 * Function to get trees.
	 *
	 * @since    3.0.0
	 */
	public function place_add() {

		
		/**
		 * get all place
		 *
		 */
		$data = $this->post_fixer($_POST);
		
		$this->add(\WPGenealogy\Models\Place::class, $data);
	}

	/**
	 * Function to delete people.
	 *
	 * @since    3.0.0
	 */
	public function place_delete() {

		/**
		 * delete note_link.
		 *
		 */
		$data = $this->post_fixer($_POST);
		
		$this->delete(\WPGenealogy\Models\Place::class, $data['selected']);
	}
	/**
	 * Function to delete people.
	 *
	 * @since    3.0.0
	 */
	public function locality_level_one() {

		$data = $this->post_fixer($_POST);

		$query = isset($data['query']) && $data['query'] ? $data['query'] : '';

		$first_char = \WPGenealogy\Models\Place::query();

		if(isset($query['gedcom']) && $query['gedcom']){
			$first_char->where('gedcom', $query['gedcom']);
		}

		$first_char->select( DB::raw("ucase(left(trim(substring_index(place,',',-1)),1)) as first_char"), DB::raw("count(ucase(left(trim(substring_index(place,',',-1)),1))) as count") );

		$first_char->groupBy('first_char')->orderBy('first_char');
		
		$first_chars = $first_char->get();
		
		$short_place = \WPGenealogy\Models\Place::query();

		if(isset($query['gedcom']) && $query['gedcom']){
			$short_place->where('gedcom', $query['gedcom']);
		}

		$short_place->select(
			DB::raw("trim(substring_index(place,',',-1)) as short_place"),
			DB::raw("count(place) as count")
		);

		$short_place->groupBy('short_place')->orderByDesc('count');
		
		if($query['limit']){
			$short_places = $short_place->limit(30)->get();
		} else {
			$short_places = $short_place->get();
		}

		wp_send_json( array(
			'first_chars' => $first_chars,
			'short_places' => $short_places,
		), 200);
	}

	public function places_get_alt_locality_level_one_char() {
		$data = $this->post_fixer($_POST);
		
		$query = isset($data['query']) && $data['query'] ? $data['query'] : '';
		$starting = $query['starting'];
		$q = \WPGenealogy\Models\Place::query();
		$q->select(
			DB::raw("trim(substring_index(place,',',-1)) as short_place"),
			DB::raw("count(place) as total")
		);
		$q->groupBy('short_place')->orderByDesc('total');
		$places = $q->get();
		foreach ($places as $key => $place) {
			if($place->short_place) {
				$locality_c  = substr($place->short_place,0, 1);
				if( $locality_c != $starting ) {
					$places->forget($key);
				}
			}
		}
		wp_send_json( $places, 200);
	}

	public function locality_level_two() {
		$data = $this->post_fixer($_POST);
		
		$query = isset($data['query']) && $data['query'] ? $data['query'] : '';
		$locality = $query['locality'];
		$q = \WPGenealogy\Models\Place::query();
		$q->where('place', 'like', '%' .$locality . '%');
		$q->select('place', DB::raw('count(*) as total'))->groupBy('place')->orderByDesc('total');
		$places = $q->get();
		foreach ($places as $key => $place) {
			if($place->place) {
				$locality_c  = trim(end(explode(',', $place->place)));
				if( $locality_c != $locality ) {
					$places->forget($key);
				}
			}
		}
		wp_send_json( $places, 200);
	}

	public function event_by_place() {
		$data = $this->post_fixer($_POST);
		
		$query = isset($data['query']) && $data['query'] ? $data['query'] : '';
		$eventsArray = [];
		$events = \WPGenealogy\Models\Event::where('eventplace', $query['place'])->get();
		foreach ($events as $key => $event) {

			if($event->persfamID) {
				if( substr($event->persfamID,0,1) =='I'){
					$event->person = \WPGenealogy\Models\People::where('gedcom', $event->gedcom)->where('personID', $event->persfamID)->first();
				}
				if(substr($event->persfamID,0,1)=='F'){
					$event->family = \WPGenealogy\Models\Family::where('gedcom', $event->gedcom)->where('familyID', $event->persfamID)->first();
				}
			}

			if($event->event_type) {
				$eventsArray[$event->event_type->tag][] = $event;
			} else {
				$eventsArray[$event->eventtypeID][] = $event;
			}
		}
		wp_send_json( $eventsArray, 200);
	}
}