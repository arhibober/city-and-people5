<?php
namespace WPGenealogy\Models;

use WPGenealogy\Model;

/**
 * Class Todo
 *
 * @package WPGenealogy\Models\Todo
 */
class Todo extends Model {

	protected $table = 'tp_todos';
	protected $appends = ['submitter', 'asigned', 'overdue', 'comments'];
	protected $fillable = [
		'created_by',
		'date',
		'title',
		'desc',
		'from',
		'for',
		'until',
		'status',
		'priority',
		'notify',
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
	/**
	 * wpgenealogy function to delete people bulk.
	 *
	 * @since    3.0.0
	 */
    public function getAsignedAttribute() {
        return \WPGenealogy\Models\User::where('ID', $this->for)->first();
    }
    /**
	 * wpgenealogy function to delete people bulk.
	 *
	 * @since    3.0.0
	 */
    public function getCommentsAttribute() {
        return \WPGenealogy\Models\TodoComment::where('task', $this->id)->get();
    }
	/**
	 * wpgenealogy function to delete people bulk.
	 *
	 * @since    3.0.0
	 */
    public function getOverdueAttribute() {
        if($this->until < date('Y-m-d')) {
        	return true;
        }
        return false;
    }
}	