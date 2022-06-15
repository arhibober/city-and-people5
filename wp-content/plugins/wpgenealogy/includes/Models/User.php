<?php
namespace WPGenealogy\Models;

use WPGenealogy\Model;

/**
 * Class Branch
 *
 * @package WPGenealogy\Models\Branch
 */
class User extends Model {
	protected $primaryKey = 'ID';
	protected $timestamp = false;
	protected $appends = ['meta', 'roles', 'caps'];

	public function getTable() {
		return $this->getConnection()->db->prefix . 'users';
	}

	public function meta() {
		return $this->hasMany('WPGenealogy\Models\UserMeta', 'user_id');
	}

	public function getMetaAttribute() {
		$meta =  get_user_meta($this->ID);

		unset($meta['session_tokens']);

		foreach ($meta as $key => $value) {
			$meta[$key] = get_user_meta($this->ID, $key, true);
			if($key == 'last_login') {
				$meta[$key] = human_time_diff(get_user_meta($this->ID, $key, true));
			}
		}
		return $meta;
	}
	public function getRolesAttribute() {
		$userdata = get_userdata($this->ID);
		$roles = $userdata->roles;
		return $roles;
	}

	public function getCapsAttribute() {
		$userdata = get_userdata($this->ID);
		$caps = $userdata->caps;
		return $caps;
	}
}

