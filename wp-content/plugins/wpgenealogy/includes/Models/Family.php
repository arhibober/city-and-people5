<?php
namespace WPGenealogy\Models;

use WPGenealogy\Model;
use WPGenealogy\Models\People;
use WPGenealogy\Models\Citation;
use WPGenealogy\Models\Children;

/**
 * Class Family
 *
 * @package WPGenealogy\Models\Family
 */
class Family extends Model {
	
	protected $table = 'tp_families';
	
	protected $appends = [
		'events',
		'citations',
		'note_links',
		'husband_obj',
		'wife_obj',
	];

	protected $fillable = [
		'created_by',
		'gedcom',
		'familyID',
		'husband',
		'wife',
		'marrdate',
		'marrdatetr',
		'marrplace',
		'marrtype',
		'divdate',
		'divdatetr',
		'divplace',
		'status',
		'sealdate',
		'sealdatetr',
		'sealplace',
		'husborder',
		'wifeorder',
		'changedate',
		'living',
		'private',
		'branch',
		'changedby',
		'edituser',
		'edittime'
	];

	public function getEventsAttribute() {
		return Event::where('persfamID', $this->familyID)->where('gedcom', $this->gedcom)->get();
	}
	
	public function getLivingAttribute(){
		if(($this->getHusbandObjAttribute() && $this->getHusbandObjAttribute()->living) || ( $this->getWifeObjAttribute() && $this->getWifeObjAttribute()->living)) {
			 return 1;
		}
	   
	}


	public function getCitationsAttribute() {
		return Citation::where('persfamID', $this->familyID)->where('gedcom', $this->gedcom)->get();
	}

	public function getNoteLinksAttribute() {
		return NoteLink::where('persfamID', $this->familyID)->where('gedcom', $this->gedcom)->get();
	}

	public function getHusbandObjAttribute() {
		return People::where('personID', $this->husband)->where('gedcom', $this->gedcom)->first();
	}

	public function getWifeObjAttribute() {
		return People::where('personID', $this->wife)->where('gedcom', $this->gedcom)->first();
	}

	public function get_husband() {
		return $this->belongsTo(People::class, 'husband', 'personID');
	}

	public function get_wife() {
		return $this->belongsTo(People::class, 'wife', 'personID');
	}

	/**
	 * wpgenealogy dates.
	 *
	 * @since    3.0.0
	 */
	protected $dates = [
		'created_at',
		'updated_at',
	];
}