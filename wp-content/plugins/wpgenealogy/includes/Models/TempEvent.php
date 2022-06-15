<?php
namespace WPGenealogy\Models;

use WPGenealogy\Model;

/**
 * Class Event
 *
 * @package WPGenealogy\Models\Event
 */
class TempEvent extends Model {
	protected $table = 'tp_temp_events';
	protected $appends = ['people', 'family', 'children'];
	protected $fillable = [
		'created_by',
		'type',
		'gedcom',
		'personID',
		'familyID',
		'eventID',
		'eventdate',
		'eventplace',
		'info',
		'note',
		'user',
		'postdate',
	];

	protected $dates = [
		'created_at',
		'updated_at',
	];

	/**
	 * wpgenealogy function to delete people bulk.
	 *
	 * @since    3.0.0
	 */
    public function getPeopleAttribute() {
        if($this->type == 'I') {
        	$people = \WPGenealogy\Models\People::where('gedcom', $this->gedcom)->where('personID', $this->personID)->first();
        	return $people;
        }
        
        if($this->type == 'C') {
        	
        }
    }

	/**
	 * wpgenealogy function to delete people bulk.
	 *
	 * @since    3.0.0
	 */
    public function getFamilyAttribute() {
        if($this->type == 'F') {
        	$family = \WPGenealogy\Models\Family::where('gedcom', $this->gedcom)->where('familyID', $this->familyID)->first();
        	if($family && $family->husband) {
        		$family->husband_obj = \WPGenealogy\Models\People::where('gedcom', $this->gedcom)->where('personID', $family->husband)->first();
        	}
        	if($family && $family->wife) {
        		$family->wife_obj = \WPGenealogy\Models\People::where('gedcom', $this->gedcom)->where('personID', $family->wife)->first();
        	}
        	return $family;
        }
    }

	/**
	 * wpgenealogy function to delete people bulk.
	 *
	 * @since    3.0.0
	 */
    public function getChildrenAttribute() {
        if($this->type == 'C') {
        	
        }
    }


}