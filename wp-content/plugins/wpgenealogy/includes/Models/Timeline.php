<?php
namespace WPGenealogy\Models;

use WPGenealogy\Model;

/**
 * Class Timeline
 *
 * @package WPGenealogy\Models\Timeline
 */
class Timeline extends Model {
  protected $table = 'tp_timelines';
  protected $fillable = [
    'evday',
    'evmonth',
    'evyear',
    'endday',
    'endmonth',
    'endyear',
    'evtitle',
    'evdetail',
    'created_by',
  ];
  protected $dates = [
    'created_at',
    'updated_at',
  ];
}