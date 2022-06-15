<?php
namespace WPGenealogy\Models;

use WPGenealogy\Model;

/**
 * Class Association
 *
 * @package WPGenealogy\Models\Association
 */
class Association extends Model {

	protected $table = 'tp_associations';

	protected $fillable = [
		'created_by',
		'assocID',
		'gedcom',
		'persfamID',
		'passocID',
		'reltype',
		'relationship',
	];

	protected $dates = [
		'created_at',
		'updated_at',
	];

	public function getAssocIDAttribute() {
		return $this->id;
	}

}