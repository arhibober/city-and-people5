<?php
namespace WPGenealogy\Models;

use WPGenealogy\Model;
use WPGenealogy\Models\Source;

/**
 * Class Citation
 *
 * @package WPGenealogy\Models\Citation
 */
class Citation extends Model {

	protected $table = 'tp_citations';

	protected $appends = [
		'source_obj'
	];

	protected $fillable = [
		'created_by',
		'gedcom',
		'persfamID',
		'eventID',
		'sourceID',
		'ordernum',
		'description',
		'citedate',
		'citedatetr',
		'citetext',
		'page',
		'quay',
		'note',
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
	public function getSourceObjAttribute() {
		return Source::where('sourceID', $this->sourceID)->first();
	}

}