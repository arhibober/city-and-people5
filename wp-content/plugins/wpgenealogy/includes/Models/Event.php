<?php
namespace WPGenealogy\Models;

use WPGenealogy\Model;
use WPGenealogy\Models\EventType;

/**
 * Class Event
 *
 * @package WPGenealogy\Models\Event
 */
class Event extends Model {
	
	protected $table = 'tp_events';
	
	protected $appends = [
		'event_type'
	];
	
	protected $fillable = [
		'created_by',
		'eventID', 
		'gedcom', 
		'persfamID', 
		'eventtypeID', 
		'eventdate', 
		'eventdatetr', 
		'eventplace', 
		'age', 
		'agency', 
		'cause', 
		'addressID', 
		'parenttag', 
		'info'
	];

	protected $dates = [
		'created_at',
		'updated_at',
	];

	public function getEventTypeAttribute() {
		return EventType::where('id', $this->eventtypeID)->oRwhere('tag', $this->eventtypeID)->oRwhere('eventtypeID', $this->eventtypeID)->first();
	}

}