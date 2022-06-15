<?php
namespace WPGenealogy\Models;

use WPGenealogy\Model;

/**
 * Class EventType
 *
 * @package WPGenealogy\Models\EventType
 */
class EventType extends Model {
	
	protected $table = 'tp_event_types';
	
	protected $fillable = [
		'created_by',
		'eventtypeID',
		'tag',
		'description',
		'display',
		'keep',
		'collapse',
		'ordernum',
		'ldsevent',
		'type',
	];

	protected $dates = [
		'created_at',
		'updated_at',
	];

	public function getEventtypeIDAttribute() {
		return $this->id;
	}

}