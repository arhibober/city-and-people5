<?php
namespace WPGenealogy\Models;

use WPGenealogy\Model;

/**
 * Class Branch
 *
 * @package WPGenealogy\Models\Branch
 */
class Branch extends Model {

	protected $table = 'tp_branches';
	protected $appends = [
		'person'
	];
	protected $fillable = [
		'created_by',
		'branch',
		'gedcom',
		'description',
		'personID',
		'agens',
		'dgens',
		'dagens',
		'inclspouses',
		'action',
	];

	protected $dates = [
		'created_at',
		'updated_at',
	];

	/**
	 * get tree.
	 */
	public function tree(){
		return $this->belongsTo( \WPGenealogy\Models\Tree::class, 'gedcom', 'gedcom' );
	}

	/**
	 * get person.
	 */
	public function person(){
		return $this->belongsTo( \WPGenealogy\Models\People::class, 'personID', 'personID' );
	}
	public function getPersonAttribute() {
		return \WPGenealogy\Models\People::where('gedcom', $this->gedcom)->where('personID', $this->personID)->first();
	}
}