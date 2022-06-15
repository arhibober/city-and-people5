<?php 
namespace WPGenealogy_Public\Traits;

use WPGenealogy\Eloquent\Facades\DB;

trait Others {

	/**
	 * function to get surname.
	 *
	 * @since    3.0.0
	 */
	public function get_surname(){

		$data = $this->post_fixer($_POST);

		$perPage 	= isset($data['per_page']) ? sanitize_text_field($data['per_page']) : 10000;
		$columns 	= isset($data['columns']) ? sanitize_text_field($data['columns']) : ['*'];
		$pageName 	= isset($data['page_name']) ? sanitize_text_field($data['page_name']) : 'page';
		$page 		= isset($data['current_page']) ? sanitize_text_field($data['current_page']) : 1;
		$sort 		= isset($data['sort']) ? sanitize_text_field($data['sort']) : 'total';
		$order 		= isset($data['order']) ? sanitize_text_field($data['order']) : 'DESC';

		$query = isset($data['query']) && $data['query'] ? $data['query'] : '';

		$q = \WPGenealogy\Models\People::query();
        //$q->where('created_by', wp_get_current_user()->ID);

		$q->groupBy('lastname');
		$q->select('lastname',  DB::raw('count(*) as total'));
		$q->where('lastname', '<>', '');
		$q->whereNotNull('lastname');
		if(isset($query['gedcom']) && $query['gedcom']){
			$q->where('gedcom', $query['gedcom']);
		}
		if(isset($query['starting']) && $query['starting']){
			$q->where('lastname', 'like', $query['starting'].'%');
		}
		if(isset($data['top']) && $data['top']) {
			$perPage = sanitize_text_field($data['top']);
		}
		$result = $q->orderBy($sort, $order)->paginate($perPage, $columns, $pageName, $page);
		wp_send_json($result, 200 );
	}

	/**
	 * function to search people with advanced fileds
	 *
	 * @since    3.0.0
	 */
	public function people_search_advanced(){
		$data = $this->post_fixer($_POST);

		$perPage 	= isset($data['per_page']) ? sanitize_text_field($data['per_page']) : 10;
		$columns 	= isset($data['columns']) ? sanitize_text_field($data['columns']) : ['*'];
		$pageName 	= isset($data['page_name']) ? sanitize_text_field($data['page_name']) : 'page';
		$page 		= isset($data['current_page']) ? sanitize_text_field($data['current_page']) : 1;
		$sort 		= isset($data['sort']) ? sanitize_text_field($data['sort']) : 'created_at';
		$order 		= isset($data['order']) ? sanitize_text_field($data['order']) : 'DESC';

		$query = isset($data['query']) && $data['query'] ? $data['query'] : '';

		$q = \WPGenealogy\Models\People::query();

       // $q->where('created_by', wp_get_current_user()->ID);

		$q->whereNull('private');

		$q->whereNull('living');

		if(isset($query['gedcom']) && $query['gedcom']){
			$q->where('gedcom', $query['gedcom']);
		}

		if( isset($query['firstname']) &&  $query['firstname'] && $query['firstname']['value'] ){
			if($query['firstname']['operator'] == 'contains' || $query['firstname']['operator'] == 'exists') {
				$q->where('firstname', 'like', '%'.$query['firstname']['value'].'%');
			} elseif ($query['firstname']['operator'] == 'equals') {
				$q->where('firstname', '=', $query['firstname']['value']);
			} elseif ($query['firstname']['operator'] == 'startswith') {
				$q->where('firstname', 'like', $query['firstname']['value'].'%');
			} elseif ($query['firstname']['operator'] == 'endswith') {
				$q->where('firstname', 'like', '%'.$query['firstname']['value']);
			}  elseif ($query['firstname']['operator'] == 'dnexist') {
				$q->where('firstname', 'not like', '%'.$query['firstname']['value'].'%');
			} elseif ($query['firstname']['operator'] == 'soundexof') { } else {}
		}
		
		if( isset($query['lastname']) &&  $query['lastname'] && $query['lastname']['value'] ){
			if($query['lastname']['operator'] == 'contains' || $query['lastname']['operator'] == 'exists') {
				$q->where('lastname', 'like', '%'.$query['lastname']['value'].'%');
			} elseif ($query['lastname']['operator'] == 'equals') {
				$q->where('lastname', '=', $query['lastname']['value']);
			} elseif ($query['lastname']['operator'] == 'startswith') {
				$q->where('lastname', 'like', $query['lastname']['value'].'%');
			} elseif ($query['lastname']['operator'] == 'endswith') {
				$q->where('lastname', 'like', '%'.$query['lastname']['value']);
			} elseif ($query['lastname']['operator'] == 'dnexist') {
				$q->where('lastname', 'not like', '%'.$query['lastname']['value'].'%');
			} elseif ($query['lastname']['operator'] == 'soundexof') { 
			} elseif ($query['lastname']['operator'] == 'metaphoneof') { 
			} else { }
		}

		if(  isset($query['personID']) && $query['personID'] && $query['personID']['value'] ){
			if($query['personID']['operator'] == 'contains') {
				$q->where('personID', 'like', '%'.$query['personID']['value'].'%');
			} elseif ($query['personID']['operator'] == 'equals') {
				$q->where('personID', '=', $query['personID']['value']);
			} elseif ($query['personID']['operator'] == 'startswith') {
				$q->where('personID', 'like', $query['personID']['value'].'%');
			} elseif ($query['personID']['operator'] == 'endswith') {
				$q->where('personID', 'like', '%'.$query['personID']['value']);
			} else { }
		}

		if( isset($query['sex']) && $query['sex'] && $query['sex']['value'] ){
			if($query['sex']['operator'] == 'equals') {
				$q->where('sex', '=', $query['sex']['value']);
			} else {}
		}

		if( isset($query['birthplace']) && $query['birthplace'] && $query['birthplace']['value'] ){
			if($query['birthplace']['operator'] == 'contains' || $query['birthplace']['operator'] == 'exists') {
				$q->where('birthplace', 'like', '%'.$query['birthplace']['value'].'%');
			} elseif ($query['birthplace']['operator'] == 'equals') {
				$q->where('birthplace', '=', $query['birthplace']['value']);
			} elseif ($query['birthplace']['operator'] == 'startswith') {
				$q->where('birthplace', 'like', $query['birthplace']['value'].'%');
			} elseif ($query['birthplace']['operator'] == 'endswith') {
				$q->where('birthplace', 'like', '%'.$query['birthplace']['value']);
			} elseif ($query['birthplace']['operator'] == 'dnexist') {
				$q->where('birthplace', 'not like', '%'.$query['birthplace']['value'].'%');
			} else { }
		}

		if( isset($query['birthdate']) && $query['birthdate'] && $query['birthdate']['value'] ){
			if($query['birthdate']['operator'] == 'equals' ) {
				$q->whereBetween('birthdatetr', [$query['birthdate']['value'].'-01-01', $query['birthdate']['value'].'-01-01']);
			} elseif ($query['birthdate']['operator'] == 'pm2') {
				$q->whereBetween('birthdatetr', [((int)$query['birthdate']['value'] - 2).'-01-01', ((int)$query['birthdate']['value'] + 2).'-01-01']);
			} elseif ($query['birthdate']['operator'] == 'pm5') {
				$q->whereBetween('birthdatetr', [((int)$query['birthdate']['value'] - 5).'-01-01', ((int)$query['birthdate']['value'] + 5).'-01-01']);
			} elseif ($query['birthdate']['operator'] == 'pm10') {
				$q->whereBetween('birthdatetr', [((int)$query['birthdate']['value'] - 10).'-01-01', ((int)$query['birthdate']['value'] + 10).'-01-01']);
			} elseif ($query['birthdate']['operator'] == 'lt') {
				$q->where('birthdatetr', '<', $query['birthdate']['value'].'-01-01')->where('birthdatetr', '>', '0000-00-00');
			} elseif ($query['birthdate']['operator'] == 'gt') {
				$q->where('birthdatetr', '>', $query['birthdate']['value'].'-01-01');
			} elseif ($query['birthdate']['operator'] == 'lte') {
				$q->where('birthdatetr', '<=', $query['birthdate']['value'].'-01-01')
					->where('birthdatetr', '>', '0000-00-00');
			} elseif ($query['birthdate']['operator'] == 'gte') {
				$q->where('birthdatetr', '>=', $query['birthdate']['value'].'-01-01');
			} elseif ($query['birthdate']['operator'] == 'exists') {
				$q->where('birthdatetr', 'like', '%'.$query['birthdate']['value'].'%');
			} elseif ($query['birthdate']['operator'] == 'dnexist') {
				$q->where('birthdatetr', 'not like', '%'.$query['birthdate']['value'].'%');
			} else { }
		}

		if( isset($query['altbirthplace']) && $query['altbirthplace'] && $query['altbirthplace']['value'] ){
			if($query['altbirthplace']['operator'] == 'contains' || $query['altbirthplace']['operator'] == 'exists') {
				$q->where('altbirthplace', 'like', '%'.$query['altbirthplace']['value'].'%');
			} elseif ($query['altbirthplace']['operator'] == 'equals') {
				$q->where('altbirthplace', '=', $query['altbirthplace']['value']);
			} elseif ($query['altbirthplace']['operator'] == 'startswith') {
				$q->where('altbirthplace', 'like', $query['altbirthplace']['value'].'%');
			} elseif ($query['altbirthplace']['operator'] == 'endswith') {
				$q->where('altbirthplace', 'like', '%'.$query['altbirthplace']['value']);
			} elseif ($query['altbirthplace']['operator'] == 'dnexist') {
				$q->where('altbirthplace', 'not like', '%'.$query['altbirthplace']['value'].'%');
			} else { }
		}

		if( isset($query['altbirthdate']) && $query['altbirthdate'] && $query['altbirthdate']['value'] ){
			if($query['altbirthdate']['operator'] == 'equals' ) {
				$q->whereBetween('altbirthdatetr', [$query['altbirthdate']['value'].'-01-01', $query['altbirthdate']['value'].'-01-01']);
			} elseif ($query['altbirthdate']['operator'] == 'pm2') {
				$q->whereBetween('altbirthdatetr', [((int)$query['altbirthdate']['value'] - 2).'-01-01', ((int)$query['altbirthdate']['value'] + 2).'-01-01']);
			} elseif ($query['altbirthdate']['operator'] == 'pm5') {
				$q->whereBetween('altbirthdatetr', [((int)$query['altbirthdate']['value'] - 5).'-01-01', ((int)$query['altbirthdate']['value'] + 5).'-01-01']);
			} elseif ($query['altbirthdate']['operator'] == 'pm10') {
				$q->whereBetween('altbirthdatetr', [((int)$query['altbirthdate']['value'] - 10).'-01-01', ((int)$query['altbirthdate']['value'] + 10).'-01-01']);
			} elseif ($query['altbirthdate']['operator'] == 'lt') {
				$q->where('altbirthdatetr', '<', $query['altbirthdate']['value'].'-01-01')->where('altbirthdatetr', '>', '0000-00-00');
			} elseif ($query['altbirthdate']['operator'] == 'gt') {
				$q->where('altbirthdatetr', '>', $query['altbirthdate']['value'].'-01-01');
			} elseif ($query['altbirthdate']['operator'] == 'lte') {
				$q->where('altbirthdatetr', '<=', $query['altbirthdate']['value'].'-01-01')
					->where('altbirthdatetr', '>', '0000-00-00');
			} elseif ($query['altbirthdate']['operator'] == 'gte') {
				$q->where('altbirthdatetr', '>=', $query['altbirthdate']['value'].'-01-01');
			} elseif ($query['altbirthdate']['operator'] == 'exists') {
				$q->where('altbirthdatetr', 'like', '%'.$query['altbirthdate']['value'].'%');
			} elseif ($query['altbirthdate']['operator'] == 'dnexist') {
				$q->where('altbirthdatetr', 'not like', '%'.$query['altbirthdate']['value'].'%');
			} else { }
		}

		if( isset($query['deathplace']) && $query['deathplace'] && $query['deathplace']['value'] ){
			if($query['deathplace']['operator'] == 'contains' || $query['deathplace']['operator'] == 'exists') {
				$q->where('deathplace', 'like', '%'.$query['deathplace']['value'].'%');
			} elseif ($query['deathplace']['operator'] == 'equals') {
				$q->where('deathplace', '=', $query['deathplace']['value']);
			} elseif ($query['deathplace']['operator'] == 'startswith') {
				$q->where('deathplace', 'like', $query['deathplace']['value'].'%');
			} elseif ($query['deathplace']['operator'] == 'endswith') {
				$q->where('deathplace', 'like', '%'.$query['deathplace']['value']);
			} elseif ($query['deathplace']['operator'] == 'dnexist') {
				$q->where('deathplace', 'not like', '%'.$query['deathplace']['value'].'%');
			} else { }
		}

		if( isset($query['deathdate']) && $query['deathdate'] && $query['deathdate']['value'] ){
			if($query['deathdate']['operator'] == 'equals' ) {
				$q->whereBetween('deathdatetr', [$query['deathdate']['value'].'-01-01', $query['deathdate']['value'].'-01-01']);
			} elseif ($query['deathdate']['operator'] == 'pm2') {
				$q->whereBetween('deathdatetr', [((int)$query['deathdate']['value'] - 2).'-01-01', ((int)$query['deathdate']['value'] + 2).'-01-01']);
			} elseif ($query['deathdate']['operator'] == 'pm5') {
				$q->whereBetween('deathdatetr', [((int)$query['deathdate']['value'] - 5).'-01-01', ((int)$query['deathdate']['value'] + 5).'-01-01']);
			} elseif ($query['deathdate']['operator'] == 'pm10') {
				$q->whereBetween('deathdatetr', [((int)$query['deathdate']['value'] - 10).'-01-01', ((int)$query['deathdate']['value'] + 10).'-01-01']);
			} elseif ($query['deathdate']['operator'] == 'lt') {
				$q->where('deathdatetr', '<', $query['deathdate']['value'].'-01-01')->where('deathdatetr', '>', '0000-00-00');
			} elseif ($query['deathdate']['operator'] == 'gt') {
				$q->where('deathdatetr', '>', $query['deathdate']['value'].'-01-01');
			} elseif ($query['deathdate']['operator'] == 'lte') {
				$q->where('deathdatetr', '<=', $query['deathdate']['value'].'-01-01')
					->where('deathdatetr', '>', '0000-00-00');
			} elseif ($query['deathdate']['operator'] == 'gte') {
				$q->where('deathdatetr', '>=', $query['deathdate']['value'].'-01-01');
			} elseif ($query['deathdate']['operator'] == 'exists') {
				$q->where('deathdatetr', 'like', '%'.$query['deathdate']['value'].'%');
			} elseif ($query['deathdate']['operator'] == 'dnexist') {
				$q->where('deathdatetr', 'not like', '%'.$query['deathdate']['value'].'%');
			} else { }
		}

		if( isset($query['burialplace']) && $query['burialplace'] && $query['burialplace']['value'] ){
			if($query['burialplace']['operator'] == 'contains' || $query['burialplace']['operator'] == 'exists') {
				$q->where('burialplace', 'like', '%'.$query['burialplace']['value'].'%');
			} elseif ($query['burialplace']['operator'] == 'equals') {
				$q->where('burialplace', '=', $query['burialplace']['value']);
			} elseif ($query['burialplace']['operator'] == 'startswith') {
				$q->where('burialplace', 'like', $query['burialplace']['value'].'%');
			} elseif ($query['burialplace']['operator'] == 'endswith') {
				$q->where('burialplace', 'like', '%'.$query['burialplace']['value']);
			} elseif ($query['burialplace']['operator'] == 'dnexist') {
				$q->where('burialplace', 'not like', '%'.$query['burialplace']['value'].'%');
			} else { }
		}

		if( isset($query['burialdate']) && $query['burialdate'] && $query['burialdate']['value'] ){
			if($query['burialdate']['operator'] == 'equals' ) {
				$q->whereBetween('burialdatetr', [$query['burialdate']['value'].'-01-01', $query['burialdate']['value'].'-01-01']);
			} elseif ($query['burialdate']['operator'] == 'pm2') {
				$q->whereBetween('burialdatetr', [((int)$query['burialdate']['value'] - 2).'-01-01', ((int)$query['burialdate']['value'] + 2).'-01-01']);
			} elseif ($query['burialdate']['operator'] == 'pm5') {
				$q->whereBetween('burialdatetr', [((int)$query['burialdate']['value'] - 5).'-01-01', ((int)$query['burialdate']['value'] + 5).'-01-01']);
			} elseif ($query['burialdate']['operator'] == 'pm10') {
				$q->whereBetween('burialdatetr', [((int)$query['burialdate']['value'] - 10).'-01-01', ((int)$query['burialdate']['value'] + 10).'-01-01']);
			} elseif ($query['burialdate']['operator'] == 'lt') {
				$q->where('burialdatetr', '<', $query['burialdate']['value'].'-01-01')->where('burialdatetr', '>', '0000-00-00');
			} elseif ($query['burialdate']['operator'] == 'gt') {
				$q->where('burialdatetr', '>', $query['burialdate']['value'].'-01-01');
			} elseif ($query['burialdate']['operator'] == 'lte') {
				$q->where('burialdatetr', '<=', $query['burialdate']['value'].'-01-01')
					->where('burialdatetr', '>', '0000-00-00');
			} elseif ($query['burialdate']['operator'] == 'gte') {
				$q->where('burialdatetr', '>=', $query['burialdate']['value'].'-01-01');
			} elseif ($query['burialdate']['operator'] == 'exists') {
				$q->where('burialdatetr', 'like', '%'.$query['burialdate']['value'].'%');
			} elseif ($query['burialdate']['operator'] == 'dnexist') {
				$q->where('burialdatetr', 'not like', '%'.$query['burialdate']['value'].'%');
			} else { }
		}

		if( isset($query['spouse']) && $query['spouse'] && isset($query['sex']) && $query['sex'] && $query['sex']['value']){
			if($query['sex']['value']=='M'){
				if( $query['spouse']['lastname'] && $query['spouse']['lastname']['value'] ){
					if($query['spouse']['lastname']['operator'] == 'contains' || $query['spouse']['lastname']['operator'] == 'exists') {
						$q->whereHas('spouseW', function($q) use ($query) {
							$q->where('lastname', 'like', '%'.$query['spouse']['lastname']['value'].'%');
						});
					} elseif ($query['spouse']['lastname']['operator'] == 'equals') {
						$q->whereHas('spouseW', function($q) use ($query) {
							$q->where('lastname', '=', $query['spouse']['lastname']['value']);
						});
					} elseif ($query['spouse']['lastname']['operator'] == 'startswith') {
						$q->whereHas('spouseW', function($q) use ($query) {
							$q->where('lastname', 'like', $query['spouse']['lastname']['value'].'%');
						});
					} elseif ($query['spouse']['lastname']['operator'] == 'endswith') {
						$q->whereHas('spouseW', function($q) use ($query) {
							$q->where('lastname', 'like', '%'.$query['spouse']['lastname']['value']);
						});
					} elseif ($query['spouse']['lastname']['operator'] == 'dnexist') {
						$q->whereHas('spouseW', function($q) use ($query) {
							$q->where('lastname', 'not like', '%'.$query['spouse']['lastname']['value'].'%');
						});
					} elseif ($query['spouse']['lastname']['operator'] == 'soundexof') { 
					} elseif ($query['spouse']['lastname']['operator'] == 'metaphoneof') { 
					} else { }
				}
			}
			if($query['sex']['value']=='F'){
				if( $query['spouse']['lastname'] && $query['spouse']['lastname']['value'] ){
					if($query['spouse']['lastname']['operator'] == 'contains' || $query['spouse']['lastname']['operator'] == 'exists') {
						$q->whereHas('spouseH', function($q) use ($query) {
							$q->where('lastname', 'like', '%'.$query['spouse']['lastname']['value'].'%');
						});
					} elseif ($query['spouse']['lastname']['operator'] == 'equals') {
						$q->whereHas('spouseH', function($q) use ($query) {
							$q->where('lastname', '=', $query['spouse']['lastname']['value']);
						});
					} elseif ($query['spouse']['lastname']['operator'] == 'startswith') {
						$q->whereHas('spouseH', function($q) use ($query) {
							$q->where('lastname', 'like', $query['spouse']['lastname']['value'].'%');
						});
					} elseif ($query['spouse']['lastname']['operator'] == 'endswith') {
						$q->whereHas('spouseH', function($q) use ($query) {
							$q->where('lastname', 'like', '%'.$query['spouse']['lastname']['value']);
						});
					} elseif ($query['spouse']['lastname']['operator'] == 'dnexist') {
						$q->whereHas('spouseH', function($q) use ($query) {
							$q->where('lastname', 'not like', '%'.$query['spouse']['lastname']['value'].'%');
						});
					} elseif ($query['spouse']['lastname']['operator'] == 'soundexof') { 
					} elseif ($query['spouse']['lastname']['operator'] == 'metaphoneof') { 
					} else { }
				}
			}
		}
		if( isset($query['spouse_last_name']) && $query['spouse_last_name'] && $query['spouse_last_name']['value'] ){
		}
		if( isset($query['nickname']) && $query['nickname'] && $query['nickname']['value'] ){
			if($query['nickname']['operator'] == 'contains' || $query['nickname']['operator'] == 'exists') {
				$q->where('nickname', 'like', '%'.$query['nickname']['value'].'%');
			} elseif ($query['nickname']['operator'] == 'equals') {
				$q->where('nickname', '=', $query['nickname']['value']);
			} elseif ($query['nickname']['operator'] == 'startswith') {
				$q->where('nickname', 'like', $query['nickname']['value'].'%');
			} elseif ($query['nickname']['operator'] == 'endswith') {
				$q->where('nickname', 'like', '%'.$query['nickname']['value']);
			} elseif ($query['nickname']['operator'] == 'dnexist') {
				$q->where('nickname', 'not like', '%'.$query['nickname']['value'].'%');
			} elseif ($query['nickname']['operator'] == 'soundexof') { 
			} elseif ($query['nickname']['operator'] == 'metaphoneof') { 
			} else { }
		}
		if( isset($query['title']) && $query['title'] && $query['title']['value'] ){
			if($query['title']['operator'] == 'contains' || $query['title']['operator'] == 'exists') {
				$q->where('title', 'like', '%'.$query['title']['value'].'%');
			} elseif ($query['title']['operator'] == 'equals') {
				$q->where('title', '=', $query['title']['value']);
			} elseif ($query['title']['operator'] == 'startswith') {
				$q->where('title', 'like', $query['title']['value'].'%');
			} elseif ($query['title']['operator'] == 'endswith') {
				$q->where('title', 'like', '%'.$query['title']['value']);
			} elseif ($query['title']['operator'] == 'dnexist') {
				$q->where('title', 'not like', '%'.$query['title']['value'].'%');
			} elseif ($query['title']['operator'] == 'soundexof') { 
			} elseif ($query['title']['operator'] == 'metaphoneof') { 
			} else { }
		}
		if( isset($query['prefix']) && $query['prefix'] && $query['prefix']['value'] ){
			if($query['prefix']['operator'] == 'contains' || $query['prefix']['operator'] == 'exists') {
				$q->where('prefix', 'like', '%'.$query['prefix']['value'].'%');
			} elseif ($query['prefix']['operator'] == 'equals') {
				$q->where('prefix', '=', $query['prefix']['value']);
			} elseif ($query['prefix']['operator'] == 'startswith') {
				$q->where('prefix', 'like', $query['prefix']['value'].'%');
			} elseif ($query['prefix']['operator'] == 'endswith') {
				$q->where('prefix', 'like', '%'.$query['prefix']['value']);
			} elseif ($query['prefix']['operator'] == 'dnexist') {
				$q->where('prefix', 'not like', '%'.$query['prefix']['value'].'%');
			} elseif ($query['prefix']['operator'] == 'soundexof') { 
			} elseif ($query['prefix']['operator'] == 'metaphoneof') { 
			} else { }
		}
		if( isset($query['suffix']) && $query['suffix'] && $query['suffix']['value'] ){
			if($query['suffix']['operator'] == 'contains' || $query['suffix']['operator'] == 'exists') {
				$q->where('suffix', 'like', '%'.$query['suffix']['value'].'%');
			} elseif ($query['suffix']['operator'] == 'equals') {
				$q->where('suffix', '=', $query['suffix']['value']);
			} elseif ($query['suffix']['operator'] == 'startswith') {
				$q->where('suffix', 'like', $query['suffix']['value'].'%');
			} elseif ($query['suffix']['operator'] == 'endswith') {
				$q->where('suffix', 'like', '%'.$query['suffix']['value']);
			} elseif ($query['suffix']['operator'] == 'dnexist') {
				$q->where('suffix', 'not like', '%'.$query['suffix']['value'].'%');
			} elseif ($query['suffix']['operator'] == 'soundexof') { 
			} elseif ($query['suffix']['operator'] == 'metaphoneof') { 
			} else { }
		}
		$result = $q->orderBy($sort, $order)->paginate($perPage, $columns, $pageName, $page);
		/*
		$result->getCollection()->transform(function ($people) {
			$people->spouses = [];
			foreach ($people->spouseW as $key => $spouse) {
				if($spouse->id != $people->id){
					$people->spouses[] = $spouse;
				}
			}
			foreach ($people->spouseH as $key => $spouse) {
				if($spouse->id != $people->id){
					$people->spouses[] = $spouse;
				}
			}
			return $people;
		});
		*/
		wp_send_json($result, 200 );
	}

	/**
	 * function to search family  with advanced fileds
	 *
	 * @since    3.0.0
	 */
	public function family_search_advanced(){
		$data = $this->post_fixer($_POST);

		$perPage 	= isset($data['per_page']) ? sanitize_text_field($data['per_page']) : 10;
		$columns 	= isset($data['columns']) ? sanitize_text_field($data['columns']) : ['*'];
		$pageName 	= isset($data['page_name']) ? sanitize_text_field($data['page_name']) : 'page';
		$page 		= isset($data['current_page']) ? sanitize_text_field($data['current_page']) : 1;
		$sort 		= isset($data['sort']) ? sanitize_text_field($data['sort']) : 'created_at';
		$order 		= isset($data['order']) ? sanitize_text_field($data['order']) : 'DESC';
		$query = isset($data['query']) && $data['query'] ? $data['query'] : '';
		$q = \WPGenealogy\Models\Family::query();

        //$q->where('created_by', wp_get_current_user()->ID);

		$q->whereNull('private');
		if(isset($query['gedcom']) && $query['gedcom']){
			$q->where('gedcom', $query['gedcom']);
		}
		if( isset($query['husband'] ) && $query['husband'] ){
			if( $query['husband']['firstname'] && $query['husband']['firstname']['value'] ){
				if($query['husband']['firstname']['operator'] == 'contains' || $query['husband']['firstname']['operator'] == 'exists') {
					$q->whereHas('get_husband', function($q) use ($query) {
						$q->where('firstname', 'like', '%'.$query['husband']['firstname']['value'].'%');
					});
				} elseif ($query['husband']['firstname']['operator'] == 'equals') {
					$q->whereHas('get_husband', function($q) use ($query) {
						$q->where('firstname', '=', $query['husband']['firstname']['value']);
					});
				} elseif ($query['husband']['firstname']['operator'] == 'startswith') {
					$q->whereHas('get_husband', function($q) use ($query) {
						$q->where('firstname', 'like', $query['husband']['firstname']['value'].'%');
					});
				} elseif ($query['husband']['firstname']['operator'] == 'endswith') {
					$q->whereHas('get_husband', function($q) use ($query) {
						$q->where('firstname', 'like', '%'.$query['husband']['firstname']['value']);
					});
				}  elseif ($query['husband']['firstname']['operator'] == 'dnexist') {
					$q->whereHas('get_husband', function($q) use ($query) {
						$q->where('firstname', 'not like', '%'.$query['husband']['firstname']['value'].'%');
					});
				} elseif ($query['husband']['firstname']['operator'] == 'soundexof') { } else {}
			}

			if( $query['husband']['lastname'] && $query['husband']['lastname']['value'] ){

				if($query['husband']['lastname']['operator'] == 'contains' || $query['husband']['lastname']['operator'] == 'exists') {
					$q->whereHas('get_husband', function($q) use ($query) {
						$q->where('lastname', 'like', '%'.$query['husband']['lastname']['value'].'%');
					});
				} elseif ($query['husband']['lastname']['operator'] == 'equals') {
					$q->whereHas('get_husband', function($q) use ($query) {
						$q->where('lastname', '=', $query['husband']['lastname']['value']);
					});
				} elseif ($query['husband']['lastname']['operator'] == 'startswith') {
					$q->whereHas('get_husband', function($q) use ($query) {
						$q->where('lastname', 'like', $query['husband']['lastname']['value'].'%');
					});
				} elseif ($query['husband']['lastname']['operator'] == 'endswith') {
					$q->whereHas('get_husband', function($q) use ($query) {
						$q->where('lastname', 'like', '%'.$query['husband']['lastname']['value']);
					});
				} elseif ($query['husband']['lastname']['operator'] == 'dnexist') {
					$q->whereHas('get_husband', function($q) use ($query) {
						$q->where('lastname', 'not like', '%'.$query['husband']['lastname']['value'].'%');
					});
				} elseif ($query['husband']['lastname']['operator'] == 'soundexof') { 
				} elseif ($query['husband']['lastname']['operator'] == 'metaphoneof') { 
				} else { }
			}
		}

		if( isset($query['wife'] ) && $query['wife'] ){
			if( $query['wife']['firstname'] && $query['wife']['firstname']['value'] ){
				if($query['wife']['firstname']['operator'] == 'contains' || $query['wife']['firstname']['operator'] == 'exists') {
					$q->whereHas('get_wife', function($q) use ($query) {
						$q->where('firstname', 'like', '%'.$query['wife']['firstname']['value'].'%');
					});
				} elseif ($query['wife']['firstname']['operator'] == 'equals') {
					$q->whereHas('get_wife', function($q) use ($query) {
						$q->where('firstname', '=', $query['wife']['firstname']['value']);
					});
				} elseif ($query['wife']['firstname']['operator'] == 'startswith') {
					$q->whereHas('get_wife', function($q) use ($query) {
						$q->where('firstname', 'like', $query['wife']['firstname']['value'].'%');
					});
				} elseif ($query['wife']['firstname']['operator'] == 'endswith') {
					$q->whereHas('get_wife', function($q) use ($query) {
						$q->where('firstname', 'like', '%'.$query['wife']['firstname']['value']);
					});
				}  elseif ($query['wife']['firstname']['operator'] == 'dnexist') {
					$q->whereHas('get_wife', function($q) use ($query) {
						$q->where('firstname', 'not like', '%'.$query['wife']['firstname']['value'].'%');
					});
				} elseif ($query['wife']['firstname']['operator'] == 'soundexof') { } else {}
			}
			if( $query['wife']['lastname'] && $query['wife']['lastname']['value'] ){
				if($query['wife']['lastname']['operator'] == 'contains' || $query['wife']['lastname']['operator'] == 'exists') {
					$q->whereHas('get_wife', function($q) use ($query) {
						$q->where('lastname', 'like', '%'.$query['wife']['lastname']['value'].'%');
					});
				} elseif ($query['wife']['lastname']['operator'] == 'equals') {
					$q->whereHas('get_wife', function($q) use ($query) {
						$q->where('lastname', '=', $query['wife']['lastname']['value']);
					});
				} elseif ($query['wife']['lastname']['operator'] == 'startswith') {
					$q->whereHas('get_wife', function($q) use ($query) {
						$q->where('lastname', 'like', $query['wife']['lastname']['value'].'%');
					});
				} elseif ($query['wife']['lastname']['operator'] == 'endswith') {
					$q->whereHas('get_wife', function($q) use ($query) {
						$q->where('lastname', 'like', '%'.$query['wife']['lastname']['value']);
					});
				} elseif ($query['wife']['lastname']['operator'] == 'dnexist') {
					$q->whereHas('get_wife', function($q) use ($query) {
						$q->where('lastname', 'not like', '%'.$query['wife']['lastname']['value'].'%');
					});
				} elseif ($query['wife']['lastname']['operator'] == 'soundexof') { 
				} elseif ($query['wife']['lastname']['operator'] == 'metaphoneof') { 
				} else { }
			}
		}
		if( isset($query['familyID']) && $query['familyID'] && $query['familyID']['value'] ){
			if($query['familyID']['operator'] == 'contains') {
				$q->where('familyID', 'like', '%'.$query['familyID']['value'].'%');
			} elseif ($query['familyID']['operator'] == 'equals') {
				$q->where('familyID', '=', $query['familyID']['value']);
			} elseif ($query['familyID']['operator'] == 'startswith') {
				$q->where('familyID', 'like', $query['familyID']['value'].'%');
			} elseif ($query['familyID']['operator'] == 'endswith') {
				$q->where('familyID', 'like', '%'.$query['familyID']['value']);
			} else { }
		}
		if( isset($query['marrplace']) && $query['marrplace'] && $query['marrplace']['value'] ){
			if($query['marrplace']['operator'] == 'contains' || $query['marrplace']['operator'] == 'exists') {
				$q->where('marrplace', 'like', '%'.$query['marrplace']['value'].'%');
			} elseif ($query['marrplace']['operator'] == 'equals') {
				$q->where('marrplace', '=', $query['marrplace']['value']);
			} elseif ($query['marrplace']['operator'] == 'startswith') {
				$q->where('marrplace', 'like', $query['marrplace']['value'].'%');
			} elseif ($query['marrplace']['operator'] == 'endswith') {
				$q->where('marrplace', 'like', '%'.$query['marrplace']['value']);
			} elseif ($query['marrplace']['operator'] == 'dnexist') {
				$q->where('marrplace', 'not like', '%'.$query['marrplace']['value'].'%');
			} else { }
		}
		if( isset($query['marrdate'] ) && $query['marrdate'] && $query['marrdate']['value'] ){
			if($query['marrdate']['operator'] == 'equals' ) {
				$q->whereBetween('marrdatetr', [$query['marrdate']['value'].'-01-01', $query['marrdate']['value'].'-01-01']);
			} elseif ($query['marrdate']['operator'] == 'pm2') {
				$q->whereBetween('marrdatetr', [((int)$query['marrdate']['value'] - 2).'-01-01', ((int)$query['marrdate']['value'] + 2).'-01-01']);
			} elseif ($query['marrdate']['operator'] == 'pm5') {
				$q->whereBetween('marrdatetr', [((int)$query['marrdate']['value'] - 5).'-01-01', ((int)$query['marrdate']['value'] + 5).'-01-01']);
			} elseif ($query['marrdate']['operator'] == 'pm10') {
				$q->whereBetween('marrdatetr', [((int)$query['marrdate']['value'] - 10).'-01-01', ((int)$query['marrdate']['value'] + 10).'-01-01']);
			} elseif ($query['marrdate']['operator'] == 'lt') {
				$q->where('marrdatetr', '<', $query['marrdate']['value'].'-01-01')->where('marrdatetr', '>', '0000-00-00');
			} elseif ($query['marrdate']['operator'] == 'gt') {
				$q->where('marrdatetr', '>', $query['marrdate']['value'].'-01-01');
			} elseif ($query['marrdate']['operator'] == 'lte') {
				$q->where('marrdatetr', '<=', $query['marrdate']['value'].'-01-01')
					->where('marrdatetr', '>', '0000-00-00');
			} elseif ($query['marrdate']['operator'] == 'gte') {
				$q->where('marrdatetr', '>=', $query['marrdate']['value'].'-01-01');
			} elseif ($query['marrdate']['operator'] == 'exists') {
				$q->where('marrdatetr', 'like', '%'.$query['marrdate']['value'].'%');
			} elseif ($query['marrdate']['operator'] == 'dnexist') {
				$q->where('marrdatetr', 'not like', '%'.$query['marrdate']['value'].'%');
			} else { }
		}
		if( isset($query['divplace']) && $query['divplace'] && $query['divplace']['value'] ){
			if($query['divplace']['operator'] == 'contains' || $query['divplace']['operator'] == 'exists') {
				$q->where('divplace', 'like', '%'.$query['divplace']['value'].'%');
			} elseif ($query['divplace']['operator'] == 'equals') {
				$q->where('divplace', '=', $query['divplace']['value']);
			} elseif ($query['divplace']['operator'] == 'startswith') {
				$q->where('divplace', 'like', $query['divplace']['value'].'%');
			} elseif ($query['divplace']['operator'] == 'endswith') {
				$q->where('divplace', 'like', '%'.$query['divplace']['value']);
			} elseif ($query['divplace']['operator'] == 'dnexist') {
				$q->where('divplace', 'not like', '%'.$query['divplace']['value'].'%');
			} else { }
		}
		if( isset($query['divdate']) && $query['divdate'] && $query['divdate']['value'] ){
			if($query['divdate']['operator'] == 'equals' ) {
				$q->whereBetween('divdatetr', [$query['divdate']['value'].'-01-01', $query['divdate']['value'].'-01-01']);
			} elseif ($query['divdate']['operator'] == 'pm2') {
				$q->whereBetween('divdatetr', [((int)$query['divdate']['value'] - 2).'-01-01', ((int)$query['divdate']['value'] + 2).'-01-01']);
			} elseif ($query['divdate']['operator'] == 'pm5') {
				$q->whereBetween('divdatetr', [((int)$query['divdate']['value'] - 5).'-01-01', ((int)$query['divdate']['value'] + 5).'-01-01']);
			} elseif ($query['divdate']['operator'] == 'pm10') {
				$q->whereBetween('divdatetr', [((int)$query['divdate']['value'] - 10).'-01-01', ((int)$query['divdate']['value'] + 10).'-01-01']);
			} elseif ($query['divdate']['operator'] == 'lt') {
				$q->where('divdatetr', '<', $query['divdate']['value'].'-01-01')->where('divdatetr', '>', '0000-00-00');
			} elseif ($query['divdate']['operator'] == 'gt') {
				$q->where('divdatetr', '>', $query['divdate']['value'].'-01-01');
			} elseif ($query['divdate']['operator'] == 'lte') {
				$q->where('divdatetr', '<=', $query['divdate']['value'].'-01-01')
					->where('divdatetr', '>', '0000-00-00');
			} elseif ($query['divdate']['operator'] == 'gte') {
				$q->where('divdatetr', '>=', $query['divdate']['value'].'-01-01');
			} elseif ($query['divdate']['operator'] == 'exists') {
				$q->where('divdatetr', 'like', '%'.$query['divdate']['value'].'%');
			} elseif ($query['divdate']['operator'] == 'dnexist') {
				$q->where('divdatetr', 'not like', '%'.$query['divdate']['value'].'%');
			} else { }
		}
		if( isset($query['marrtype']) && $query['marrtype'] && $query['marrtype']['value'] ){
			if($query['marrtype']['operator'] == 'contains' || $query['marrtype']['operator'] == 'exists') {
				$q->where('marrtype', 'like', '%'.$query['marrtype']['value'].'%');
			} elseif ($query['marrtype']['operator'] == 'equals') {
				$q->where('marrtype', '=', $query['marrtype']['value']);
			} elseif ($query['marrtype']['operator'] == 'startswith') {
				$q->where('marrtype', 'like', $query['marrtype']['value'].'%');
			} elseif ($query['marrtype']['operator'] == 'endswith') {
				$q->where('marrtype', 'like', '%'.$query['marrtype']['value']);
			} elseif ($query['marrtype']['operator'] == 'dnexist') {
				$q->where('marrtype', 'not like', '%'.$query['marrtype']['value'].'%');
			} else { }
		}
		$q->with('get_husband');
		$q->with('get_wife');
		$result = $q->orderBy($sort, $order)->paginate($perPage, $columns, $pageName, $page);
		wp_send_json($result, 200 );
	}

	/**
	 * function to get people by id.
	 *
	 * @since    3.0.0
	 */
	public function get_people_by_id(){

		$data = $this->post_fixer($_POST);

		$result = \WPGenealogy\Models\People::find($data['id']);
		wp_send_json($result, 200 );
	}

	/**
	 * function to get family by id.
	 *
	 * @since    3.0.0
	 */
	public function get_family_by_id(){
		$data = $this->post_fixer($_POST);

		$result = \WPGenealogy\Models\Family::with('husbandObj')->with('wifeObj')->find($data['id']);
		wp_send_json($result, 200 );
	}

	/**
	 * function to get children by id.
	 *
	 * @since    3.0.0
	 */
	public function get_children_by_id(){
		$data = $this->post_fixer($_POST);

		$result = \WPGenealogy\Models\Children::with('peopleObj')->find($data['id']);
		wp_send_json($result, 200 );
	}

	/**
	 * function to get temp event save and delete.
	 *
	 * @since    3.0.0
	 */
	public function temp_event_save_delete(){
		$data = $this->post_fixer($_POST);

		$eventArray = array(
			'BIRT' => array('birthdate','birthplace'),
			'CHR' => array('altbirthdate','altbirthplace'),
			'DEAT' => array('deathdate','deathplace'),
			'BURI' => array('burialdate','burialplace'),
			'BAPL' => array('baptdate','baptplace'),
			'CONL' => array('confdate','confplace'),
			'INIT' => array('initdate','initplace'),
			'ENDL' => array('endldate','endlplace'),
		);

		if($data['people']){
			$people = \WPGenealogy\Models\People::find($data['people']['id']);

			if($_POST['eventID']=='BIRT') {
				if($datadatadatadata['eventdate']) {
					$people->birthdate = $datadatadatadata['eventdate'];
				}
				if($datadatadatadata['eventplace']){
					$people->birthplace = $datadatadatadata['eventplace'];
				}
			}
			if($datadatadatadata['eventID']=='CHR') {
				if($datadatadatadata['eventdate']) {
					$people->altbirthdate = $datadatadatadata['eventdate'];
				}
				if($datadatadata['eventplace']){
					$people->altbirthplace = $datadatadata['eventplace'];
				}
			}
			if($datadatadata['eventID']=='DEAT') {
				if($datadatadata['eventdate']) {
					$people->deathdate = $datadatadata['eventdate'];
				}
				if($datadatadata['eventplace']){
					$people->deathplace = $datadatadata['eventplace'];
				}
			}
			if($datadata['eventID']=='BURI') {
				if($datadata['eventdate']) {
					$people->burialdate = $datadata['eventdate'];
				}
				if($datadata['eventplace']){
					$people->burialplace = $datadata['eventplace'];
				}
			}
			if($datadata['eventID']=='BAPL') {
				if($datadata['eventdate']) {
					$people->baptdate = $datadata['eventdate'];
				}
				if($datadata['eventplace']){
					$people->baptplace = $datadata['eventplace'];
				}
			}
			if($data['eventID']=='CONL') {
				if($data['eventdate']) {
					$people->confdate = $data['eventdate'];
				}
				if($data['eventplace']){
					$people->confplace = $data['eventplace'];
				}
			}
			if($data['eventID']=='INIT') {
				if($data['eventdate']) {
					$people->initdate = $data['eventdate'];
				}
				if($data['eventplace']){
					$people->initplace = $data['eventplace'];
				}
			}
			if($data['eventID']=='ENDL') {
				if($data['eventdate']) {
					$people->endldate = $data['eventdate'];
				}
				if($data['eventplace']){
					$people->endlplace = $data['eventplace'];
				}
			}

			$people->save();
			$this->delete(\WPGenealogy\Models\TempEvent::class, array($data['id']));
		}

		$eventArray = array(
			'MARR' => array('marrdate','marrplace'),
			'SLGS' => array('sealdate','sealplace'),
			'DIV' => array('divdate','divplace'),
		);

		if($data['family']){
			$family = \WPGenealogy\Models\Family::find($datadata['family']['id']);
			
			if($data['eventID']=='MARR') {
				if($data['eventdate']) {
					$family->marrdate = $data['eventdate'];
				}
				if($data['eventplace']){
					$family->marrplace = $data['eventplace'];
				}
			}
			if($data['eventID']=='SLGS') {
				if($data['eventdate']) {
					$family->sealdate = $data['eventdate'];
				}
				if($data['eventplace']){
					$family->sealplace = $data['eventplace'];
				}
			}
			if($data['eventID']=='DIV') {
				if($data['eventdate']) {
					$family->divdate = $data['eventdate'];
				}
				if($data['eventplace']){
					$family->divplace = $data['eventplace'];
				}
			}
			$family->save();
			$this->delete(\WPGenealogy\Models\TempEvent::class, array($data['id']));
		}

		if($data['children']){
		}
	}

	/**
	 * function to upload profile image.
	 *
	 * @since    3.0.0
	 */
	public function upload_profile_image(){
		$data = $this->post_fixer($_POST);

		if( ! isset( $_FILES ) || empty( $_FILES ) || ! isset( $_FILES['file'] ) ){
			return;
		}
		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
		}
		$upload_overrides = array( 'test_form' => false );
		$file = $_FILES['file'];
		if ($file['name']) {
			$uploadedfile = array(
				'name'     => $file['name'],
				'type'     => $file['type'],
				'tmp_name' => $file['tmp_name'],
				'error'    => $file['error'],
				'size'     => $file['size']
			);
			$file = wp_handle_upload( $uploadedfile, $upload_overrides );
			if ( $file && !isset( $file['error'] ) ) {
				\WPGenealogy\Models\ProfileImage::create([
					'gedcom' => $data['gedcom'],
					'personID' => $data['personID'],
					'file' => $file['file'],
					'url' => $file['url'],
				]);
			}
		}
		$people = \WPGenealogy\Models\People::where('gedcom', $data['gedcom'])->where('personID', $data['personID'])->first();
		
		wp_send_json($people->image, 200);

	}


	/**
	 * function to upload profile image.
	 *
	 * @since    3.0.0
	 */
	public function remove_profile_imagef(){
		$data = $this->post_fixer($_POST);
		$ProfileImage = \WPGenealogy\Models\ProfileImage::where('gedcom', $data['gedcom'])->where('personID', $data['personID'])->where('file', $data['file'])->where('url', $data['url'])->first();
		
		if($ProfileImage) {
			$ProfileImage->delete();
		}

		$people = \WPGenealogy\Models\People::where('gedcom', $data['gedcom'])->where('personID', $data['personID'])->first();
		wp_send_json($people->image, 200);
	}



	/**
	 * function to convert date.
	 *
	 * @since    3.0.0
	 */
	public function convertDate( $olddate ) {
		//additional month names (ie, different languages) may be added with same values in case multiple languages are used in the same database
		$months = array( 
			"JAN"=>1, 
			"FEB"=>2, 
			"MAR"=>3, 
			"APR"=>4, 
			"MAY"=>5, 
			"JUN"=>6, 
			"JUL"=>7, 
			"AUG"=>8, 
			"SEP"=>9, 
			"OCT"=>10, 
			"NOV"=>11, 
			"DEC"=>12 
		);

		$hebrewmonths = array(
			"TIS"=>1, 
			"CHE"=>2, 
			"HES"=>2, 
			"KIS"=>3, 
			"TEV"=>4, 
			"TEB"=>4, 
			"SHV"=>5, 
			"SHE"=>5, 
			"ADA"=>6, 
			"VEA"=>7, 
			"NIS"=>8, 
			"IYA"=>9, 
			"SIV"=>10, 
			"TAM"=>11, 
			"AB"=>12, 
			"AV"=>12, 
			"ELU"=>13 
		);

		//alternatives for "BEF" and "AFT" should be entered in these lists separated by commas
		$befarray = array( "BEF" );
		$aftarray = array( "AFT" );
		$lastday = array( 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
		$preferred_separator = "/";  //this character separates the components in a numeric date, as in "MM/DD/YYYY"
		$numeric_date_order = 0;  //0 = MM/DD/YYYY; 1 = DD/MM/YYYY
		$befstringused = false;
		
		if( $olddate ) {
			$olddate = strtoupper(trim( $olddate ));
			$dateparts = array();
			$dateparts = explode(" ",$olddate);
			$newyear = $newmonth = $newday = 0;

			$found = array_search( "TO", $dateparts );
			if( !$found ) $found = array_search( "AND", $dateparts );
			$ptr = $found ? $found - 1 : count($dateparts) - 1;

			$newparts = array();
			$newparts = explode($preferred_separator, $dateparts[$ptr] );
			//if number of parts is 3, insert them into array at $ptr, move $ptr up
			if( count( $newparts ) == 3 ) {
				$dateparts[$ptr++] = $newparts[0];
				$dateparts[$ptr++] = $newparts[1];
				$dateparts[$ptr] = $newparts[2];
				$reversedate = $numeric_date_order;
			}
			else
				$reversedate = 0;

			$slashpos = strpos($dateparts[$ptr],"/");
			if($slashpos) {
				$wholeyear1 = strtok($dateparts[$ptr],"/");
				$wholeyear2 = strtok("/");
				$len = -1 * strlen($wholeyear2);
				$len1 = strlen($wholeyear1);
				$century = substr($wholeyear1,0,$len1+$len);
				$year1 = substr($wholeyear1,$len1+$len);
				$year2 = $wholeyear2;
				if($year1 > $year2) $century++;
				$tempyear = $century . $year2;
			}
			else {
				$len = -1 * strlen($dateparts[$ptr]);
				if($len < -4) $len = -4;
				$tempyear = trim(substr($dateparts[$ptr],$len));
				$dash = strpos($tempyear,"-");
				if($dash !== false)
					$tempyear = substr($tempyear,$dash+1);
			}
			if( is_numeric( $tempyear ) ) {
				$newyear = $tempyear;
				$ptr--;
				
				$tempmonth = isset($dateparts[$ptr]) ? trim(substr(strtoupper($dateparts[$ptr]),0,3)) : "";
				//if it's in $months, or it's numeric and we're doing dd-mm-yyyy, proceed. If it's numeric and we're doing mm-dd-yyyy, then flip day and month
				$foundit = 0;
				if( !empty($months[$tempmonth]) ) {
					$newmonth = $months[$tempmonth];
					$foundit = 1;
				}
				elseif( !empty($hebrewmonths[$tempmonth]) ) {
					$newmonth = $hebrewmonths[$tempmonth];
					$foundit = 2;
				}
				elseif( is_numeric( $tempmonth ) && strlen($tempmonth) <= 2 ) {
					$newmonth = intval( $tempmonth );
					$foundit = 1;
				}
				if( $foundit ) {
					$ptr--;
					
					$tempday = isset($dateparts[$ptr]) ? $dateparts[$ptr] : "";
				}

				if($foundit == 1) {
					//if we're doing mm/dd/yyyy, we need to switch month and day here
					//it could be numeric, or it could be in $months, if we've switched.
					if( $reversedate ) {
						$temppart = $newmonth;
						$newmonth = $tempday;
						$tempday = $temppart;
					}
					if( is_numeric( $tempday ) && strlen($tempday) <= 2 ) {
						$newday = sprintf( "%02d", $tempday );
						$ptr--;
						$str = isset($dateparts[$ptr]) ? substr(strtoupper($dateparts[$ptr]),0,3) : "";
						if( in_array( $str, $aftarray ) ) {
							$newday++;
							if( $newday > $lastday[$newmonth-1] ) {
								$newday = 0;
								if( $newmonth == 12 ) $newyear++;
								$newmonth = $newmonth < 12 ? $newmonth + 1 : 1;
							}
						}
						else if( in_array( $str, $befarray ) ) {
							$newday--;
							$befstringused = true;
						}
					}
					else {
						$tempday2 = substr(strtoupper($tempday),0,3);
						$newday = 0;
						if( in_array( $tempday2, $aftarray ) ) {
							if( $newmonth == 12 ) $newyear++;
							$newmonth = $newmonth < 12 ? $newmonth + 1 : 1;
						}
						elseif( in_array( $tempday2, $befarray ) )
							$befstringused = true;
					}
				}
				elseif($foundit == 2 ) {
					//Hebrew
					if(!$tempday) $tempday = 1;
					$gregoriandate = JDtoGregorian( JewishToJD( $newmonth, $tempday, $newyear ) );
					$newdate = explode("/", $gregoriandate);
					$newyear = $newdate[2];
					$newmonth = $newdate[0];
					$newday= $newdate[1];
				}
				else {
					$newmonth = 0;
					$newday = 0;
					if( in_array( $tempmonth, $aftarray ) )
						$newyear++;
					elseif( in_array( $tempmonth, $befarray ) )
						$befstringused = true;
				}
				if($befstringused && $newday == 0 && $foundit != 2) {
					$newmonth--;
					if($newmonth <= 0) {
						$newmonth = 12;
						$newyear--;
					}
					$newday = cal_days_in_month(CAL_GREGORIAN, $newmonth, $newyear);
					//$newday = date('t', mktime(0, 0, 0, $newmonth, $newyear));
				}
			}
			$newdate = sprintf("%04d-%02d-%02d", $newyear,$newmonth,$newday);
		}
		else
			$newdate = "0000-00-00";
		return( $newdate );
	}

	/**
	 * function to suggeste change.
	 *
	 * @since    3.0.0
	 */
	public function suggeste_change(){
		$data = $this->post_fixer($_POST);

		$data = $data['data'];
		$id = $data['id'];
		$people = \WPGenealogy\Models\People::find($id);
		$tree_email = $people->tree->email;
		if(!$data['email']) {
			$response = array (
				'messages' => array(
					array(
						'type' => 'alert-info',
						'text' => 'Email is required'
					)
				)
			);
			wp_send_json($response, 200);
		}
		if(!$tree_email){
			$response = array (
				'messages' => array(
					array(
						'type' => 'alert-info',
						'text' => 'Tree owner email was not found. Message sent to site adminstrator'
					)
				)
			);
			$admin_email = get_option( 'admin_email', true );
			$to = $admin_email;
			$subject = 'Suggeste change : tree owner email missing!';
			$body = 'Name: '.$data['name'];
			$body .= 'Email: '.$data['email'];
			$body .= 'Description: '.$data['description'];
			$headers = array('Content-Type: text/html; charset=UTF-8');
			wp_mail( $to, $subject, $body, $headers );
			wp_send_json($response, 200);
		}
		$to = $tree_email;
		$subject = 'Suggeste change : tree owner email missing!';
		$body = 'Name: '.$data['name'];
		$body .= 'Email: '.$data['email'];
		$body .= 'Description: '.$data['description'];
		$headers = array('Content-Type: text/html; charset=UTF-8');
		wp_mail( $to, $subject, $body, $headers );
		$response = array (
			'messages' => array(
				array(
					'type' => 'alert-info',
					'text' => 'Email sent.'
				)
			)
		);
		wp_send_json($response, 200);
	}

	/**
	 * function to get statistics.
	 *
	 * @since    3.0.0
	 */
	public function get_statistics(){
		
		$data = $this->post_fixer($_POST);

		$gedcom = isset($data['query']['gedcom']) ? $data['query']['gedcom'] : '';

        //$model->where('created_by', wp_get_current_user()->ID);


		$total_individuals = \WPGenealogy\Models\People::where('created_by', wp_get_current_user()->ID)->get()->count();
		$total_males = \WPGenealogy\Models\People::where('created_by', wp_get_current_user()->ID)->where('sex', 'M')->get()->count();
		$total_females = \WPGenealogy\Models\People::where('created_by', wp_get_current_user()->ID)->where('sex', 'F')->get()->count();
		$total_unknown_gender = \WPGenealogy\Models\People::where('created_by', wp_get_current_user()->ID)->where('sex', '')->orWhere('sex', 'U')->get()->count();
		$total_living = \WPGenealogy\Models\People::where('created_by', wp_get_current_user()->ID)->whereNotNull('living')->get()->count();
		$total_families = \WPGenealogy\Models\Family::where('created_by', wp_get_current_user()->ID)->get()->count();
		$total_unique_surnames = \WPGenealogy\Models\People::where('created_by', wp_get_current_user()->ID)->whereNotNull('lastname')->groupBy('lastname')->count();

		if($gedcom){
			$total_individuals = \WPGenealogy\Models\People::where('created_by', wp_get_current_user()->ID)->where('gedcom', $gedcom)->get()->count();
			$total_males = \WPGenealogy\Models\People::where('created_by', wp_get_current_user()->ID)->where('gedcom', $gedcom)->where('sex', 'M')->get()->count();
			$total_females = \WPGenealogy\Models\People::where('created_by', wp_get_current_user()->ID)->where('gedcom', $gedcom)->where('sex', 'F')->get()->count();
			$total_unknown_gender = \WPGenealogy\Models\People::where('created_by', wp_get_current_user()->ID)->where('gedcom', $gedcom)->where('sex', '')->orWhere('sex', 'U')->get()->count();
			$total_living = \WPGenealogy\Models\People::where('created_by', wp_get_current_user()->ID)->where('gedcom', $gedcom)->whereNotNull('living')->get()->count();
			$total_families = \WPGenealogy\Models\Family::where('created_by', wp_get_current_user()->ID)->where('gedcom', $gedcom)->get()->count();
			$total_unique_surnames = \WPGenealogy\Models\People::where('created_by', wp_get_current_user()->ID)->where('gedcom', $gedcom)->whereNotNull('lastname')->groupBy('lastname')->count();
		}


		$total_photos = '';
		$total_documents = '';
		$total_headstones = '';
		$total_histories = '';
		$total_recordings = '';
		$total_videos = '';

		$total_sources = \WPGenealogy\Models\Source::where('created_by', wp_get_current_user()->ID)->get()->count();
		$peoples = \WPGenealogy\Models\People::where('created_by', wp_get_current_user()->ID)->get();
		if($gedcom){
			$total_sources = \WPGenealogy\Models\Source::where('created_by', wp_get_current_user()->ID)->where('gedcom', $gedcom)->get()->count();
			$peoples = \WPGenealogy\Models\People::where('created_by', wp_get_current_user()->ID)->where('gedcom', $gedcom)->get();
		}
		$lifespan = [];
		foreach ($peoples as $key => $people) {
			if($people->age) {
				$lifespan[$people->id] = $people->age;
			}
		}
		uasort($lifespan, array($this, 'cmp'));
		$average_lifespan = ceil(array_sum($lifespan)/count($lifespan));

		$earliest_birth = \WPGenealogy\Models\People::where('created_by', wp_get_current_user()->ID)->where('birthdatetr', '!=', '0000-00-00')->orderBy('birthdatetr')->first();
		if($gedcom){
			$earliest_birth = \WPGenealogy\Models\People::where('created_by', wp_get_current_user()->ID)->where('gedcom', $gedcom)->where('birthdatetr', '!=', '0000-00-00')->orderBy('birthdatetr')->first();
		}

		$longest_lived = array_slice($lifespan,0, 10, true);
		$longest_lived_peoples = array();
		foreach ($longest_lived as $key => $value) {

			$people = \WPGenealogy\Models\People::find($key);

			array_push($longest_lived_peoples, $people );
		}
		$data = array(
			'loaded' => true,
			'total_individuals' =>  $total_individuals,
			'total_males' =>  $total_males,
			'total_females' =>  $total_females,
			'total_unknown_gender' =>  $total_unknown_gender,
			'total_living' =>  $total_living,
			'total_families' =>  $total_families,
			'total_unique_surnames' =>  $total_unique_surnames,
			'total_photos' => $total_photos,
			'total_documents' => $total_documents,
			'total_headstones' => $total_headstones,
			'total_histories' => $total_histories,
			'total_recordings' => $total_recordings,
			'total_videos' => $total_videos,
			'total_sources' =>  $total_sources,
			'average_lifespan' => $average_lifespan,
			'earliest_birth' => $earliest_birth,
			'longest_lived_peoples_id' => $longest_lived,
			'longest_lived_peoples' => $longest_lived_peoples,
		);
		wp_send_json($data, 200);
	}

	/**
	 * function to get cmp.
	 *
	 * @since    3.0.0
	 */
	public function cmp($a, $b) {
		if ($a == $b) {
			return 0;
		}
		return ($a > $b) ? -1 : 1;
	}

	/**
	 * function to get anniversaries.
	 *
	 * @since    3.0.0
	 */
	public function get_anniversaries(){
		$data = $this->post_fixer($_POST);

		$q = \WPGenealogy\Models\Event::query();
		$query = $data['query'] ? $data['query']: [];
		$event = $query['event'] ? $query['event']: '';
		$day = $query['day'] ? $query['day'] : '';
		$month = $query['month'] ? $query['month'] : '';
		$year = $query['year'] ? $query['year'] : '';
		$keyword = $query['keyword'] ? $query['keyword'] : '';
		if(!$day) {
			wp_send_json([], 200);
		}

        $q->where('created_by', wp_get_current_user()->ID);

		if(isset($query['gedcom']) && $query['gedcom']){
			$q->where('gedcom', $query['gedcom']);
		}
		if($event) {
			$q->where('eventtypeID', $event);
		}
		if($day) {
			$q->whereDay('eventdatetr', $day);
		}
		if($month) {
			$q->whereMonth('eventdatetr', $month);
		}
		if($year) {
			$q->whereYear('eventdatetr', $year);
		}
		if($keyword) {
			$q->where('eventplace', 'like', '%'.$keyword.'%');
		}
		$events = $q->get();
		$eventsArray = [];
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
		wp_send_json($eventsArray, 200);
	}

	/**
	 * function to get calendar.
	 *
	 * @since    3.0.0
	 */
	public function get_calendar(){
		$data = $this->post_fixer($_POST);

		$q = \WPGenealogy\Models\Event::query();
		$query = $data['query'] ? $data['query']: [];
		$month = $query['month'] ? $query['month'] : '';
		$year = $query['year'] ? $query['year'] : '';
		if($month) {
			$q->whereMonth('eventdatetr', $month);
		}

        $q->where('created_by', wp_get_current_user()->ID);

		$events = $q->get();
		$eventsArray = [];
		foreach ($events as $key => $event) {
			if($event->persfamID) {
				if( substr($event->persfamID,0,1) =='I'){
					$event->person = \WPGenealogy\Models\People::where('gedcom', $event->gedcom)->where('personID', $event->persfamID)->first();
				}
				if(substr($event->persfamID,0,1)=='F'){
					$event->family = \WPGenealogy\Models\Family::where('gedcom', $event->gedcom)->where('familyID', $event->persfamID)->first();
				}
				$event->years = (int) date('Y', strtotime($event->eventdatetr));
			}
			array_push( $eventsArray, array (
				'key' => $event->id, 
				'customData' => $event, 
				'dates' => array( 
					'months' => (int) date('m', strtotime($event->eventdatetr)), 
					'days' => (int) date('d', strtotime($event->eventdatetr)), 
					'years' => $year, 
				)
			) );
		}
		wp_send_json($eventsArray, 200);
	}

	/**
	 * wpgenealogy function to get data for counter.
	 *
	 * @since    3.0.0
	 */
	public function counter( ){
		$people = \WPGenealogy\Models\People::where('created_by', wp_get_current_user()->ID)->get()->count(); 




		$family = \WPGenealogy\Models\Family::where('created_by', wp_get_current_user()->ID)->get()->count(); 
		$place = \WPGenealogy\Models\Place::where('created_by', wp_get_current_user()->ID)->get()->count(); 
		$note = \WPGenealogy\Models\Note::where('created_by', wp_get_current_user()->ID)->get()->count(); 
		$event_type = \WPGenealogy\Models\EventType::where('created_by', wp_get_current_user()->ID)->get()->count(); 
		$source = \WPGenealogy\Models\Source::where('created_by', wp_get_current_user()->ID)->get()->count(); 
		$tree = \WPGenealogy\Models\Tree::where('created_by', wp_get_current_user()->ID)->get()->count(); 
		$branch = \WPGenealogy\Models\Branch::where('created_by', wp_get_current_user()->ID)->get()->count(); 
		$data = array(
			'people' => $people,
			'family' => $family,
			'place' => $place,
			'note' => $note,
			'event_type' => $event_type,
			'source' => $source,
			'tree' => $tree,
			'branch' => $branch,
		);
		wp_send_json($data, 200 );
	}

	/**
	 * wpgenealogy function to get data for counter.
	 *
	 * @since    3.0.0
	 */
	public function whats_new( ) {
		$people = \WPGenealogy\Models\People::where('created_by', wp_get_current_user()->ID)->whereDate('created_at', '>', \Carbon\Carbon::now()->subDays(30))->limit(10)->get(); 
		$family = \WPGenealogy\Models\Family::where('created_by', wp_get_current_user()->ID)->whereDate('created_at', '>', \Carbon\Carbon::now()->subDays(30))->limit(10)->get(); 
		$data = array(
			'people' => $people,
			'family' => $family,
		);
		wp_send_json($data, 200 );
	}


	/**
	 * wpgenealogy function to get data for counter.
	 *
	 * @since    3.0.0
	 */

	public function tp_feed_event( $request ) {
		$timeline = \WPGenealogy\Models\Timeline::where('created_by', wp_get_current_user()->ID)->get();
		$html = '';
		foreach ($timeline as $key => $event) {
			$start = trim($event->evyear).'-'.trim($event->evmonth).'-'.trim($event->evday);
			$end = trim($event->endyear).'-'.trim($event->endmonth).'-'.trim($event->endday);
			$start = strtotime($start);
			$end = strtotime($end);
			$start = date('M j Y', $start);
			$end = date('M j Y', $end);
			$html .= '<event start="'.$start.' GMT" end="'.$end.' GMT" isDuration="true" icon="img/red-circle.png" title="'.$event->evtitle.'">'.$event->evdetail.' ('.$start.' - '.$end.')</event>';
		}
		return '<?xml version="1.0" encoding="UTF-8"?><data>'.$html.'</data>';
	}

	/**
	 * wpgenealogy function to get data for counter.
	 *
	 * @since    3.0.0
	 */
	public function tp_feed_member( $request ) {
		$people_id = $request['id'];
		$people = \WPGenealogy\Models\People::find($people_id);
		$families = \WPGenealogy\Models\Family::where('husband', $people->personID)->orWhere('wife', $people->personID)->get();
		$html = '';
		foreach ($families as $key => $family) {
			if($family->wife==$people->personID) {
				$spouse = \WPGenealogy\Models\People::where('personID', $family->husband)->first();
				$html .= '<event start="'.$family->marrdate.' GMT" end="'.$family->marrdate.' GMT" title="Married '.$spouse->name.'">'.$family->marrdate.', '.$family->marrplace.'</event>';
			}
			if($family->husband==$people->personID) {
				$spouse = \WPGenealogy\Models\People::where('personID', $family->wife)->first();
				$html .= '<event start="'.$family->marrdate.' GMT" end="'.$family->marrdate.' GMT" title="Married '.$spouse->name.'">'.$family->marrdate.', '.$family->marrplace.'</event>';
			}
			$family->childrens = [];
			$childrens = \WPGenealogy\Models\Children::where('familyID', $family->familyID)->where('gedcom', $family->gedcom)->get();
			if($childrens) {
				foreach ($childrens as $key => $children) {
					$person = \WPGenealogy\Models\People::where('personID', $children->personID)->where('gedcom', $children->gedcom)->first();
					$html .= '<event start="'.$person->birthdate.' GMT" end="'.$person->birthdate.' GMT" title="Child: '.$person->name.'">b. '.$person->birthdate.'</event>';
				}
			}
		}
		return '<?xml version="1.0" encoding="UTF-8"?><data>
	<event start="'.$people->birthdate.' GMT" end="'.$people->deathdate.' GMT" title="'.$people->name.' (1881 - 1928)">b. '.$people->birthdate.' - d. '.$people->deathdate.'</event>
	<event start="'.$people->birthdate.' GMT" end="'.$people->birthdate.' GMT" icon="'.plugin_dir_url(  dirname(dirname(plugin_basename( __FILE__ )))  ).'public/css/public/src/assets/images/green-circle.png" title="Born ('.$people->name.')">'.$people->birthdate.', '.$people->birthplace.'</event>
	<event start="'.$people->deathdate.' GMT" end="'.$people->deathdate.' GMT" icon="'.plugin_dir_url(  dirname(dirname(plugin_basename( __FILE__ )))  ).'public/css/public/src/assets/images/green-circle.png" title="Died ('.$people->name.')">'.$people->deathdate.', '.$people->deathplace.'</event>
	<event start="'.$people->burialdate.' GMT" end="'.$people->burialdate.' GMT" icon="'.plugin_dir_url(  dirname(dirname(plugin_basename( __FILE__ )))  ).'public/css/public/src/assets/images/green-circle.png" title="Buried ('.$people->name.')">'.$people->burialdate.', '.$people->burialplace.'</event>
	'.$html.'
	</data>
	';
	}

	/**
	 * wpgenealogy function to get data for counter.
	 *
	 * @since    3.0.0
	 */
	public function rest_api_init_callback(){
		register_rest_route( 'tp/v1', 'feed/member/(?P<id>\d+)', [
			'methods' => 'GET',
			'callback' => array($this, 'tp_feed_member'),
			'permission_callback' => '__return_true',
		]);
		register_rest_route( 'tp/v1', 'feed/event/(?P<id>\d+)', [
			'methods' => 'GET',
			'callback' => array($this, 'tp_feed_event'),
			'permission_callback' => '__return_true',
		]);
	}
	/**
	 * wpgenealogy function to get data for counter.
	 *
	 * @since    3.0.0
	 */
	public function maybe_tp_feed( $served, $result, $request, $server ) {
		if(strpos($request->get_route(), '/tp/v1/feed/member') !== false || strpos($request->get_route(), '/tp/v1/feed/event') !== false ){
			$server->send_header( 'Content-Type', 'text/xml' );
			echo $result->get_data();
			exit;
		}
		return $served;
	}

	/**
	 * wpgenealogy function to get data for counter.
	 *
	 * @since    3.0.0
	 */
	public function save_hub_content(){
		$data = $this->post_fixer($_POST);
		$wpg_page_heading = isset($_POST['wpg_page_heading']) && $_POST['wpg_page_heading'] ? $_POST['wpg_page_heading'] : '';
		$wpg_page_text = isset($_POST['wpg_page_text']) && $_POST['wpg_page_text'] ? $_POST['wpg_page_text'] : '';
		$wpg_button_text = isset($_POST['wpg_button_text']) && $_POST['wpg_button_text'] ? $_POST['wpg_button_text'] : '';
		$wpg_button_link = isset($_POST['wpg_button_link']) && $_POST['wpg_button_link'] ? $_POST['wpg_button_link'] : '';
		$wpg_image_link = isset($_POST['wpg_image_link']) && $_POST['wpg_image_link'] ? $_POST['wpg_image_link'] : '';

		$base = $this->setting();
		$input =  get_option('wpgenealogy_settings');

		$input['configuration']['general']['home_content']['heading'] = $wpg_page_heading;
		$input['configuration']['general']['home_content']['paragraph'] = $wpg_page_text;
		$input['configuration']['general']['home_content']['button']['text'] = $wpg_button_text;
		$input['configuration']['general']['home_content']['button']['url'] = $wpg_button_link;
		$input['configuration']['general']['home_content']['image_url'] = $wpg_image_link;

		$setting = $this->merge_setting($base, $input);
		
		update_option('wpgenealogy_settings', $setting);

		wp_send_json(array(
			'message' => 'Saved',
		));

	}

	/**
	 * wpgenealogy function to get data for counter.
	 *
	 * @since    3.0.0
	 */
	public function wpg_create_page_name(){

		$data = $this->post_fixer($_POST);

		$page_name = ( isset($data['page_name']) && $data['page_name']) ? $data['page_name'] : 'WP Genealogy Page';

		global $wpdb;
		$query = "SELECT ID, post_title, guid FROM ".$wpdb->posts." WHERE post_title =  '$page_name' AND post_status = 'publish'";
		$wpgenealogy_pages = $wpdb->get_results ($query);

		if(current($wpgenealogy_pages)) {
			wp_send_json(array(
				'page' => get_post(current($wpgenealogy_pages)->ID),
				'message' => 'Already have. Try different name',
			));
		}

		$content = '[wpgenealogy]';
		
		$my_post = array(
			'post_title'   => $page_name,
			'post_type'    => 'page',
			'post_content' => $content,
			'post_status'  => 'publish',

		);

		$page_id = wp_insert_post( $my_post, FALSE );

		$base = $this->setting();
		$input =  get_option('wpgenealogy_settings');
		$input['configuration']['general']['pages']['wpgenealogy_page_id'] = $page_id;
		$setting = $this->merge_setting($base, $input);
		
		update_option('wpgenealogy_settings', $setting);
		update_option('wpgenealogy_page_id', $page_id);

		wp_send_json(array(
			'page' => get_post($page_id),
			'page_link' => get_permalink( $page_id ),
			'message' => 'Page Created'
		));

	}



	public function wpg_create_tree(){
		$data = $this->post_fixer($_POST);


		$data = $this->post_fixer($_POST);
		$data['gedcom'] = str_replace(' ', '', $data['gedcom']);
		$data['treename'] = str_replace(' ', '', $data['gedcom']);

		$exist = $this->exist(\WPGenealogy\Models\Tree::class, array(
			'gedcom' => $data['gedcom']
		));

		if($exist) {
			return $this->send_json($exist);
		}

		$result = $this->add(\WPGenealogy\Models\Tree::class, $data, true);



		if($result['tree']->id) {
			$data = array(
			    'branch' => 'Default',
			    'gedcom' => $result['tree']->gedcom,
			    'description' => 'description'

			);
			$this->add(\WPGenealogy\Models\Branch::class, $data, true);
		}

        $data = array (
            'tree' => $result['tree'],
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
        return $this->send_json($data);
	}

	public function wpg_create_branch(){
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

	public function save_hub_content_fnish(){
		update_option('setup_wizard', 'done');
	}

	public function get_wpg_button_link(){

		if(get_option('wpgenealogy_page_id')) {

			$dat = '<li style="display: block;">'.get_the_permalink(get_option('wpgenealogy_page_id')).'#/surname</li>';
			$dat .= '<li style="display: block;">'.get_the_permalink(get_option('wpgenealogy_page_id')).'#/surname/all</li>';
			$dat .= '<li style="display: block;">'.get_the_permalink(get_option('wpgenealogy_page_id')).'#/people/search/advanced</li>';
			$dat .= '<li style="display: block;">'.get_the_permalink(get_option('wpgenealogy_page_id')).'#/family/search/advanced</li>';

			wp_send_json($dat, 200);
		}

	}




}