<?php
namespace WPGenealogy\Models;

use WPGenealogy\Model;

/**
 * Class Tree
 *
 * @package WPGenealogy\Models\Tree
 */
class Tree extends Model {

	protected $table = 'tp_trees';

	protected $appends = [
		'number_of_peoples',
		'number_of_families',
		'number_of_sources',
		'number_of_branchs',
	];

	protected $fillable = [
		'gedcom',
		'created_by',
		'treename',
		'description',
		'owner',
		'email',
		'address',
		'city',
		'state',
		'country',
		'zip',
		'phone',
		'secret',
		'disallowgedcreate',
		'disallowpdf',
		'lastimportdate',
		'importfilename',
	];

	protected $dates = [
		'created_at',
		'updated_at',
	];

	/**
	 * Get the peoples.
	 */
	public function peoples(){
		return $this->hasMany(\WPGenealogy\Models\People::class, 'gedcom', 'gedcom');
	}

	/**
	 * Get the families.
	 */
	public function families(){
		return $this->hasMany(\WPGenealogy\Models\Family::class, 'gedcom', 'gedcom');
	}

	/**
	 * Get the sources.
	 */
	public function sources(){
		return $this->hasMany(\WPGenealogy\Models\Source::class, 'gedcom', 'gedcom');
	}

	public function getNumberOfPeoplesAttribute() {
		return \WPGenealogy\Models\People::where('gedcom', $this->gedcom)->count();
	}

	public function getNumberOfFamiliesAttribute() {
		return \WPGenealogy\Models\Family::where('gedcom', $this->gedcom)->count();
	}

	public function getNumberOfSourcesAttribute() {
		return \WPGenealogy\Models\Source::where('gedcom', $this->gedcom)->count();
	}
	public function getNumberOfBranchsAttribute() {
		return \WPGenealogy\Models\Branch::where('gedcom', $this->gedcom)->count();
	}
}

