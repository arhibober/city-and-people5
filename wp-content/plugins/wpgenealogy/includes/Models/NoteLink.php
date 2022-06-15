<?php
namespace WPGenealogy\Models;

use WPGenealogy\Model;
use WPGenealogy\Models\Note;

/**
 * Class NoteLink
 *
 * @package WPGenealogy\Models\NoteLink
 */
class NoteLink extends Model {

	protected $table = 'tp_note_links';
	
	protected $appends = [
		'note'
	];

	protected $fillable = [
		'created_by',
		'persfamID',
		'gedcom',
		'noteID',
		'eventID',
		'ordernum',
		'secret',
	];
	
	protected $dates = [
		'created_at',
		'updated_at',
	];

	/**
	 * wpgenealogy function to 
	 *
	 * @since		3.0.0
	 */
	public function getNoteAttribute() {
		return Note::where('noteID', $this->noteID)->first();
	}

}