<?php
namespace WPGenealogy\Models;

use WPGenealogy\Model;

/**
 * Class Chart
 *
 * @package WPGenealogy\Models\Chart
 */
class Chart extends Model {

	protected $table = 'tp_charts';

	protected $appends = [
		'settings_mod'
	];

	protected $fillable = [
		'created_by',
		'branch',
		'gedcom',
		'settings',
	];

	protected $dates = [
		'created_at',
		'updated_at',
	];

	public function getSettingsModAttribute() {
		return unserialize($this->settings);
	}

}