<?php
namespace WPGenealogy\Models;

use WPGenealogy\Model;

/**
 * Class Todo
 *
 * @package WPGenealogy\Models\Todo
 */
class TodoComment extends Model {

	protected $table = 'tp_todo_comments';
	protected $appends = ['submitter'];
	protected $fillable = [
		'date',
		'task',
		'body',
		'from',
		'created_by'
	];

	protected $dates = [
		'created_at',
		'updated_at',
	];
	/**
	 * wpgenealogy function to delete people bulk.
	 *
	 * @since    3.0.0
	 */
    public function getSubmitterAttribute() {
        return \WPGenealogy\Models\User::where('ID', $this->from)->first();
    }
}