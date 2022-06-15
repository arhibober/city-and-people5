<?php 
namespace WPGenealogy_Public\Traits;

trait User {
	/**
	 * Function to get user.
	 *
	 * @since    3.0.0
	 */
	public function users_get_alt() {
		/**
		 * get all user
		 *
		 */
		$data = $this->post_fixer($_POST);
		$this->get_alt(\WPGenealogy\Models\User::class, $data);
	}
	/**
	 * Function to get user.
	 *
	 * @since    3.0.0
	 */
	public function user_get_alt() {
		/**
		 * get all user
		 *
		 */
		$data = $this->post_fixer($_POST);

		$this->single(\WPGenealogy\Models\User::class, $data);
	}
	/**
	 * Function to get user.
	 *
	 * @since    3.0.0
	 */
	public function users_get() {
		/**
		 * Get all useres.
		 *
		 */
		$users = \WPGenealogy\Models\User::all();
		$usersA = [];
		foreach ($users as $key => $u) {
			$usersA[$key] = new \WP_User($u->ID);
			$get_user_meta = get_user_meta($u->ID);
			unset($get_user_meta['session_tokens']);
			$metas = array();
			foreach ($get_user_meta as $kexy => $value) {
				$metas[$kexy] = get_user_meta($u->ID, $kexy, true);
				if($kexy == 'last_login') {
					$metas[$kexy] = human_time_diff(get_user_meta($u->ID, $kexy, true));
				}
				if($kexy == 'gedcom_mult') {
				}
			}
			if(!$metas['gedcom_mult']) {
				$metas['gedcom_mult'] = array();
			}
			$usersA[$key]->meta = $metas;
		}
		wp_send_json($usersA, 200 );
	}
	/**
	 * Function to get user.
	 *
	 * @since    3.0.0
	 */
	public function user_add() {
		$nonce = $_POST['security'];
		if ( ! wp_verify_nonce( $nonce, 'wpgenealogy-ajax-nonce' ) ){
			die ( 'Busted!');
		}
		if(!current_user_can('manage_options')){
			wp_send_json(array(
				'status' => 'OK',
				'messages' => array(
					array(
						'type' => 'alert-info',
						'text' => 'You don\'t have permission to create a user'
					)
				)
			), 200);
		}


		$username = isset($_POST['username']) ? sanitize_text_field($_POST['username']) : '';
		$password = isset($_POST['password']) ? $_POST['password'] : '';
		$email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
		$no_email = isset($_POST['no_email']) ? sanitize_text_field($_POST['no_email']) : '';

		$allow_add_data = isset($_POST['caps']['allow_add_data']) && $_POST['caps']['allow_add_data'] == 'true' ? true : false;
		$allow_add_media = isset($_POST['caps']['allow_add_media']) && $_POST['caps']['allow_add_media'] == 'true' ? true : false;
		$allow_edit_data = isset($_POST['caps']['allow_edit_data']) && $_POST['caps']['allow_edit_data'] == 'true' ? true : false;
		$allow_edit_media = isset($_POST['caps']['allow_edit_media']) && $_POST['caps']['allow_edit_media'] == 'true' ? true : false;
		$allow_add_suggestion = isset($_POST['caps']['allow_add_suggestion']) && $_POST['caps']['allow_add_suggestion'] == 'true' ? true : false;
		$allow_delete_data = isset($_POST['caps']['allow_delete_data']) && $_POST['caps']['allow_delete_data'] == 'true' ? true : false;
		$allow_delete_media = isset($_POST['caps']['allow_delete_media']) && $_POST['caps']['allow_delete_media'] == 'true' ? true : false;
		$allow_living = isset($_POST['caps']['allow_living']) && $_POST['caps']['allow_living'] == 'true' ? true : false;
		$allow_private = isset($_POST['caps']['allow_private']) && $_POST['caps']['allow_private'] == 'true' ? true : false;
		$allow_ged = isset($_POST['caps']['allow_ged']) && $_POST['caps']['allow_ged'] == 'true' ? true : false;
		$allow_pdf = isset($_POST['caps']['allow_pdf']) && $_POST['caps']['allow_pdf'] == 'true' ? true : false;
		$allow_lds = isset($_POST['caps']['allow_lds']) && $_POST['caps']['allow_lds'] == 'true' ? true : false;
		$allow_profile = isset($_POST['caps']['allow_profile']) && $_POST['caps']['allow_profile'] == 'true' ? true : false;



		/**
		 * .
		 *
		 * @since    3.0.0
		 */
		if ( ! $username  ) {
			wp_send_json(array(
				'status' => 'OK',
				'messages' => array(
					array(
						'type' => 'alert-info',
						'text' => 'Username required'
					)
				)
			), 200);
		}

		/**
		 * .
		 *
		 * @since    3.0.0
		 */
		if ( ! $email  ) {
			wp_send_json(array(
				'status' => 'OK',
				'messages' => array(
					array(
						'type' => 'alert-info',
						'text' => 'Email required'
					)
				)
			), 200);
		}

		/**
		 * .
		 *
		 * @since    3.0.0
		 */
		if ( ! $password  ) {
			wp_send_json(array(
				'status' => 'OK',
				'messages' => array(
					array(
						'type' => 'alert-info',
						'text' => 'Password required'
					)
				)
			), 200);
		}

		/**
		 * .
		 *
		 * @since    3.0.0
		 */
		if ( username_exists( $username )  ) {
			wp_send_json(array(
				'status' => 'OK',
				'messages' => array(
					array(
						'type' => 'alert-info',
						'text' => 'User exist'
					)
				)
			), 200);
		}

		/**
		 * .
		 *
		 * @since    3.0.0
		 */
		if ( email_exists( $email )  ) {
			wp_send_json(array(
				'status' => 'OK',
				'messages' => array(
					array(
						'type' => 'alert-info',
						'text' => 'Email exist'
					)
				)
			), 200);
		}

		/**
		 * get user_id by user name.
		 * 
		 * @since    3.0.0
		 */
		$user_id = username_exists( $username );

		/**
		 * create user if user not exist.
		 * 
		 * @since    3.0.0
		 */
		if ( ! $user_id && false == email_exists( $email ) ) {

			$user_id = wp_create_user( $username, $password, $email );

			foreach ($_POST as $key => $value) {
				if($key=='email'){
					add_user_meta($user_id, $key, sanitize_email($value));
				} else {
					add_user_meta($user_id, $key, sanitize_text_field($value));
				}
			}

			$user = new \WP_User($user_id);

			if( $role ) {
				$user->set_role($role);
			}

			if($allow_add_data) {
				$user->add_cap('allow_add_data');
			} else {
				$user->remove_cap('allow_add_data');
			}
			if($allow_add_media) {
				$user->add_cap('allow_add_media');
			} else {
				$user->remove_cap('allow_add_media');
			}
			if($allow_edit_data) {
				$user->add_cap('allow_edit_data');
			} else {
				$user->remove_cap('allow_edit_data');
			}
			if($allow_edit_media) {
				$user->add_cap('allow_edit_media');
			} else {
				$user->remove_cap('allow_edit_media');
			}
			if($allow_add_suggestion) {
				$user->add_cap('allow_add_suggestion');
			} else {
				$user->remove_cap('allow_add_suggestion');
			}
			if($allow_delete_data) {
				$user->add_cap('allow_delete_data');
			} else {
				$user->remove_cap('allow_delete_data');
			}
			if($allow_delete_media) {
				$user->add_cap('allow_delete_media');
			} else {
				$user->remove_cap('allow_delete_media');
			}
			if($allow_living) {
				$user->add_cap('allow_living');
			} else {
				$user->remove_cap('allow_living');
			}
			if($allow_private) {
				$user->add_cap('allow_private');
			} else {
				$user->remove_cap('allow_private');
			}
			if($allow_ged) {
				$user->add_cap('allow_ged');
			} else {
				$user->remove_cap('allow_ged');
			}
			if($allow_pdf) {
				$user->add_cap('allow_pdf');
			} else {
				$user->remove_cap('allow_pdf');
			}
			if($allow_lds) {
				$user->add_cap('allow_lds');
			} else {
				$user->remove_cap('allow_lds');
			}
			if($allow_profile) {
				$user->add_cap('allow_profile');
			} else {
				$user->remove_cap('allow_profile');
			}

			wp_send_json(array(
				'status' => 'OK',
				'messages' => array(
					array(
						'type' => 'alert-info',
						'text' => 'User created'
					)
				)
			), 200);



			

		} else {
			wp_send_json(array(
				'status' => 'OK',
				'messages' => array(
					array(
						'type' => 'alert-info',
						'text' => 'User already exist.'
					)
				)
			), 200);
		} 

		die();
	}


	/**
	 * Function to update user.
	 *
	 * @since    3.0.0
	 */
	public function user_update() {


		$nonce = $_POST['security'];
		if ( ! wp_verify_nonce( $nonce, 'wpgenealogy-ajax-nonce' ) ){
			die ( 'Busted!');
		}
		if(!current_user_can('manage_options')){
			wp_send_json(array(
				'status' => 'OK',
				'messages' => array(
					array(
						'type' => 'alert-info',
						'text' => 'You don\'t have permission to update a user',
					)
				)
			), 200);
		}


		$allow_add_data = isset($_POST['caps']['allow_add_data']) && $_POST['caps']['allow_add_data'] == 'true' ? true : false;
		$allow_add_media = isset($_POST['caps']['allow_add_media']) && $_POST['caps']['allow_add_media'] == 'true' ? true : false;
		$allow_edit_data = isset($_POST['caps']['allow_edit_data']) && $_POST['caps']['allow_edit_data'] == 'true' ? true : false;
		$allow_edit_media = isset($_POST['caps']['allow_edit_media']) && $_POST['caps']['allow_edit_media'] == 'true' ? true : false;
		$allow_add_suggestion = isset($_POST['caps']['allow_add_suggestion']) && $_POST['caps']['allow_add_suggestion'] == 'true' ? true : false;
		$allow_delete_data = isset($_POST['caps']['allow_delete_data']) && $_POST['caps']['allow_delete_data'] == 'true' ? true : false;
		$allow_delete_media = isset($_POST['caps']['allow_delete_media']) && $_POST['caps']['allow_delete_media'] == 'true' ? true : false;
		$allow_living = isset($_POST['caps']['allow_living']) && $_POST['caps']['allow_living'] == 'true' ? true : false;
		$allow_private = isset($_POST['caps']['allow_private']) && $_POST['caps']['allow_private'] == 'true' ? true : false;
		$allow_ged = isset($_POST['caps']['allow_ged']) && $_POST['caps']['allow_ged'] == 'true' ? true : false;
		$allow_pdf = isset($_POST['caps']['allow_pdf']) && $_POST['caps']['allow_pdf'] == 'true' ? true : false;
		$allow_lds = isset($_POST['caps']['allow_lds']) && $_POST['caps']['allow_lds'] == 'true' ? true : false;
		$allow_profile = isset($_POST['caps']['allow_profile']) && $_POST['caps']['allow_profile'] == 'true' ? true : false;



		$user_id = username_exists( $username ) ? username_exists( $username ) : sanitize_text_field($_POST['data']['ID']);

		foreach ($_POST['data']['meta']  as $key => $value) {
			update_user_meta($user_id, $key, sanitize_text_field($value));
		}

		$user = new \WP_User($user_id);

		if( $role ) {
			$user->set_role($role);
		}

		if($allow_add_data) {
			$user->add_cap('allow_add_data');
		} else {
			$user->remove_cap('allow_add_data');
		}
		if($allow_add_media) {
			$user->add_cap('allow_add_media');
		} else {
			$user->remove_cap('allow_add_media');
		}
		if($allow_edit_data) {
			$user->add_cap('allow_edit_data');
		} else {
			$user->remove_cap('allow_edit_data');
		}
		if($allow_edit_media) {
			$user->add_cap('allow_edit_media');
		} else {
			$user->remove_cap('allow_edit_media');
		}
		if($allow_add_suggestion) {
			$user->add_cap('allow_add_suggestion');
		} else {
			$user->remove_cap('allow_add_suggestion');
		}
		if($allow_delete_data) {
			$user->add_cap('allow_delete_data');
		} else {
			$user->remove_cap('allow_delete_data');
		}
		if($allow_delete_media) {
			$user->add_cap('allow_delete_media');
		} else {
			$user->remove_cap('allow_delete_media');
		}
		if($allow_living) {
			$user->add_cap('allow_living');
		} else {
			$user->remove_cap('allow_living');
		}
		if($allow_private) {
			$user->add_cap('allow_private');
		} else {
			$user->remove_cap('allow_private');
		}
		if($allow_ged) {
			$user->add_cap('allow_ged');
		} else {
			$user->remove_cap('allow_ged');
		}
		if($allow_pdf) {
			$user->add_cap('allow_pdf');
		} else {
			$user->remove_cap('allow_pdf');
		}

		if($allow_lds) {
			$user->add_cap('allow_lds');
		} else {
			$user->remove_cap('allow_lds');
		}
		if($allow_profile) {
			$user->add_cap('allow_profile');
		} else {
			$user->remove_cap('allow_profile');
		}

		wp_send_json(array(
			'status' => 'OK',
			'messages' => array(
				array(
					'type' => 'alert-info',
					'text' => 'User has been updated.'
				)
			)
		), 200);

		die();
	}

	/**
	 * Function to delete user.
	 *
	 * @since    3.0.0
	 */
	public function user_delete() {
		$id = sanitize_text_field($_POST['ID']);
		$nonce = $_POST['security'];
		if ( ! wp_verify_nonce( $nonce, 'wpgenealogy-ajax-nonce' ) ){
			die ( 'Busted!');
		}
		if(!current_user_can('manage_options')){
			wp_send_json(array(
				'status' => 'OK',
				'messages' => array(
					array(
						'type' => 'alert-info',
						'text' => 'You don\'t have permission to delete a user',
					)
				)
			), 200);
		}
		wp_delete_user( $id );
		wp_send_json(array(
			'status' => 'OK',
			'messages' => array(
				array(
					'type' => 'alert-info',
					'text' => 'User has been deleted'
				)
			)
		), 200);
		die();
	}
	
}