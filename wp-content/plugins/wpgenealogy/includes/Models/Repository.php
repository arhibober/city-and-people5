<?php
namespace WPGenealogy\Models;

use WPGenealogy\Model;

/**
 * Class Repository
 *
 * @package WPGenealogy\Models\Repository
 */
class Repository extends Model {
	protected $table = 'tp_repositories';
	protected $fillable = [
		'created_by',
		
		'repoID',
		'reponame',
		'gedcom',
		'addressID',
		'changedate',
		'changedby',
	];
	protected $dates = [
		'created_at',
		'updated_at',
	];
}