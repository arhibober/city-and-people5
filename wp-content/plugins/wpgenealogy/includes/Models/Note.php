<?php
namespace WPGenealogy\Models;

use WPGenealogy\Model;

/**
 * Class Note
 *
 * @package WPGenealogy\Models\Note
 */
class Note extends Model {
	
	protected $table = 'tp_notes';

	protected $appends = [
		'notes2'
	];

	protected $fillable = [
		'created_by',
		
		'noteID',
		'gedcom',
		'note',
	];

	protected $dates = [
		'created_at',
		'updated_at',
	];

	public function getNoteIDAttribute($value){
		return $this->attributes['noteID'] = 'N'.$this->id;
	}

	public function getNotes2Attribute($value){
		return nl2br($this->note);
	}
	
}