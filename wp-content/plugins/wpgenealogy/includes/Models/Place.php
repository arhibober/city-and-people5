<?php
namespace WPGenealogy\Models;

use WPGenealogy\Model;

/**
 * Class Place
 *
 * @package WPGenealogy\Models\Place
 */
class Place extends Model {
	protected $table = 'tp_places';
	protected $fillable = [
			'created_by',
			'gedcom',
			'place',
			'longitude',
			'latitude',
			'zoom',
			'placelevel',
			'temple',
			'geoignore',
			'notes',
	];
	protected $dates = [
		'created_at',
		'updated_at',
	];

	public function events(){
    	return $this->hasMany('WPGenealogy\Models\Event', 'eventplace', 'place');
	}
}