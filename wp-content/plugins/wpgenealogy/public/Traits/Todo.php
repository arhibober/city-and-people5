<?php 
namespace WPGenealogy_Public\Traits;

trait Todo {
	
	/**
	 * Function to get trees.
	 *
	 * @since    3.0.0
	 */
	public function todos_get() {

		/**
		 * get todos.
		 * 
		 */	
		$this->get(\WPGenealogy\Models\Todo::class);
	}
	/**
	 * Function to get trees.
	 *
	 * @since    3.0.0
	 */
	public function todos_get_alt() {
		$data = $this->post_fixer($_POST);
		
		$this->get_alt(\WPGenealogy\Models\Todo::class, $data);
	}
	/**
	 * Function to add event.
	 *
	 * @since    3.0.0
	 */
	public function todo_add(){
		/**
		 * todo add.
		 * 
		 */	

		$_POST['date'] = date('Y-m-d');
		$_POST['from'] = wp_get_current_user()->ID;

		$data = $this->post_fixer($_POST);

		$this->todo_email($data);

		
		$this->add(\WPGenealogy\Models\Todo::class, $data);
	}



	/**
	 * send email
	 */
	public function todo_email($data = array()){

		$subject = __('Notification','wpgenealogy');
		$message = __('Hi, One task has been updated on your website!', 'wpgenealogy' );

		$admin = get_option('admin_email');
		$for = $this->todo_get_email($data['for']);
		$from = $this->todo_get_email($data['from']);

		if(isset($data['notify'])){
			$tos = array($admin,$for, $from);
		}

		$headers = array('Content-Type: text/html; charset=UTF-8');
		require_once(WPGENEALOGY_PATH. 'includes/Template/email.php');
		foreach($tos as $to){
			wp_mail( $to, $subject, $html, $headers );
		}
	}


	/**
	 * Users email
	 */
	public  function todo_get_email($id) {
		if(is_int($id) && ($id != '0')) {
			$to = get_userdata($id);
			return $to->user_email;
		}
	}









	public function todo_add_comment(){

		$data = [];
		$data['date'] = date('Y-m-d');
		$data['task'] = $_POST['id'];
		$data['body'] = $_POST['body'];
		$data['from'] = wp_get_current_user()->ID;
		$data['created_by'] = wp_get_current_user()->ID;

		$data = $this->post_fixer($data);

		$this->add(\WPGenealogy\Models\TodoComment::class, $data);
	}


	/**
	 * Function to add event.
	 *
	 * @since    3.0.0
	 */
	public function todo_update(){


		unset($_POST['asigned']);
		unset($_POST['submitter']);

		/**
		 * todo update.
		 * 
		 */	
		$data = $this->post_fixer($_POST);
		
		$this->update(\WPGenealogy\Models\Todo::class, $data, 'id', $data['id']);
	}

	/**
	 * Function to add event.
	 *
	 * @since    3.0.0
	 */
	public function todo_delete(){

		/**
		 * todo delete.
		 * 
		 */	
		$data = $this->post_fixer($_POST);
		
		$this->delete(\WPGenealogy\Models\Todo::class, $data['selected']);
	}

}