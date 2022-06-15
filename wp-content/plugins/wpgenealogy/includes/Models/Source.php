<?php
namespace WPGenealogy\Models;

use WPGenealogy\Model;

/**
 * Class Source
 *
 * @package WPGenealogy\Models\Source
 */
class Source extends Model {
	protected $table = 'tp_sources';
	protected $fillable = [
		'created_by',
		'gedcom', 
		'sourceID', 
		'callnum', 
		'type', 
		'title', 
		'author', 
		'publisher', 
		'other', 
		'shorttitle', 
		'comments', 
		'actualtext', 
		'repoID', 
		'changedate', 
		'changedby', 
	];
	protected $dates = [
		'created_at',
		'updated_at',
	];
}