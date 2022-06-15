<?php
namespace WPGenealogy\Models;

use WPGenealogy\Model;

/**
 * Class Children
 *
 * @package WPGenealogy\Models\Children
 */
class Children extends Model {

	protected $table = 'tp_childrens';

	protected $fillable = [
		'created_by',
		'gedcom',
		'familyID',
		'personID',
		'frel',
		'mrel',
		'sealdate',
		'sealdatetr',
		'sealplace',
		'haskids',
		'ordernum',
		'parentorder',
	];
	
	protected $dates = [
		'created_at',
		'updated_at',
	];
	
	/**
	 * get people object of children.
	 */
	public function people(){
		return $this->belongsTo( \WPGenealogy\Models\People::class, 'personID', 'personID' )->where('gedcom', $this->gedcom);
	}

	/**
	 * get people object of children.
	 */
	public function families(){
		return $this->belongsTo( \WPGenealogy\Models\Family::class, 'familyID', 'familyID' )->where('gedcom', $this->gedcom);
	}
/*
	public function getFrelAttribute() {
		return $this->frel;
	}

	public function getMrelAttribute() {
		return $this->mrel;
	}*/
}