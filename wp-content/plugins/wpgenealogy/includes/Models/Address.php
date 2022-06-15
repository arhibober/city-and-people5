<?php
namespace WPGenealogy\Models;

use WPGenealogy\Model;

/**
 * Class Address
 *
 * @package WPGenealogy\Models\Address
 */
class Address extends Model {

	protected $table = 'tp_addresses';

	protected $fillable = [
		'created_by',
		'addressID',
		'address1',
		'address2',
		'city',
		'state',
		'zip',
		'country',
		'www',
		'email',
		'phone',
		'gedcom'
	];

	protected $dates = [
		'created_at',
		'updated_at',
	];

}