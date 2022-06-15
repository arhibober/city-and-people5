<?php
namespace WPGenealogy\Models;

use WPGenealogy\Model;

/**
 * Class ProfileImage
 *
 * @package WPGenealogy\Models\ProfileImage
 */
class ProfileImage extends Model {
  protected $table = 'tp_profile_images';
  protected $fillable = [
        'gedcom',
        'personID',
        'url',
        'file',
  ];
  protected $dates = [
    'created_at',
    'updated_at',
  ];
}