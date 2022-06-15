<?php
namespace WPGenealogy\Models;

use WPGenealogy\Model;

/**
 * Class Report
 *
 * @package WPGenealogy\Models\Report
 */
class Report extends Model {
	protected $table = 'tp_reports';
	protected $fillable = [
		'created_by',
		'gedcom',
		'reportname',
		'reportdesc',
		'ranking',
		'display',
		'criteria',
		'orderby',
		'sqlselect',
		'active',

	];
	protected $dates = [
		'created_at',
		'updated_at',
	];
}