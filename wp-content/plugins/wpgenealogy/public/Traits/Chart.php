<?php 
namespace WPGenealogy_Public\Traits;

trait Chart {

	/**
	 * Function to get charts.
	 *
	 * @since    3.0.0
	 */
	public function charts_get() {

		/**
		 * Get all chart.
		 *
		 */
		$this->get(\WPGenealogy\Models\Chart::class);
	}
	/**
	 * Function to get address.
	 *
	 * @since    3.0.0
	 */
	public function charts_get_alt() {

		/**
		 * get all people
		 *
		 */
		$data = $this->post_fixer($_POST);
		
		$this->get_alt(\WPGenealogy\Models\Chart::class, $data);
	}

	/**
	 * Function to get address.
	 *
	 * @since    3.0.0
	 */
	public function chart_get_alt() {

		/**
		 * get all people
		 *
		 */
		$data = $this->post_fixer($_POST);
		
		$chart = $this->single(\WPGenealogy\Models\Chart::class, $data, true);

		$chart->settings = unserialize($chart->settings);
		wp_send_json($chart, 200 );
	}


	/**
	 * Function to add chart.
	 *
	 * @since    3.0.0
	 */
	public function chart_add() {	

		$data = $this->post_fixer($_POST);

		$data['settings'] = serialize($data['settings']);

		/**
		 * Add chart.
		 *
		 */
		$this->add(\WPGenealogy\Models\Chart::class, $data);
	}

	/**
	 * Function to update chart.
	 *
	 * @since    3.0.0
	 */
	public function chart_update() {
		$data = $this->post_fixer($_POST);
		
		$data['settings'] = serialize($data['settings']);
		/**
		 * Update chart.
		 *
		 */
		$this->update(\WPGenealogy\Models\Chart::class, $data, array( 
			'id' => $data['id']
		));
	}

	/**
	 * Function to delete chart.
	 *
	 * @since    3.0.0
	 */
	public function chart_delete() {

		$data = $this->post_fixer($_POST);

		/**
		 * Update delete.
		 *
		 */
		$this->delete(\WPGenealogy\Models\Chart::class, $data['selected']);
	}

}
