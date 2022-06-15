<?php 
namespace WPGenealogy_Public\Traits;

trait Report {
	
	/**
	 * Function to get trees.
	 *
	 * @since    3.0.0
	 */
	public function reports_get() {
		
		/**
		 * get all report
		 *
		 */
		$this->get(\WPGenealogy\Models\Report::class);
	}
	/**
	 * Function to get trees.
	 *
	 * @since    3.0.0
	 */
	public function reports_get_alt() {
		
		/**
		 * get all report
		 *
		 */
		$data = $this->post_fixer($_POST);
		$this->get_alt(\WPGenealogy\Models\Report::class, $data);
	}

	/**
	 * Function to get trees.
	 *
	 * @since    3.0.0
	 */
	public function report_get_alt() {
		/**
		 * get all report
		 *
		 */
		$data = $this->post_fixer($_POST);
		$report = $this->single(\WPGenealogy\Models\Report::class, $data, true);
		$report->display = unserialize($report->display) ? unserialize($report->display) : [];
		$report->criteria = unserialize($report->criteria) ? unserialize($report->criteria) : [];
		$report->orderby = unserialize($report->orderby) ? unserialize($report->orderby) : [];
		wp_send_json($report, 200);
	}



	/**
	 * Function to get trees.
	 *
	 * @since    3.0.0
	 */

	public function report_get_alt_calc() {
		/**
		 * get all report
		 *
		 */
		$data = $this->post_fixer($_POST);


		$report = $this->single(\WPGenealogy\Models\Report::class, $data, true);
		$report->display = unserialize($report->display) ? unserialize($report->display) : [];
		$report->criteria = unserialize($report->criteria) ? unserialize($report->criteria) : [];
		$report->orderby = unserialize($report->orderby) ? unserialize($report->orderby) : [];

        $perPage = isset($data['per_page']) ? sanitize_text_field($data['per_page']) : 25;
        $columns = isset($data['columns']) ? sanitize_text_field($data['columns']) : ['*'];
        $pageName = isset($data['page_name']) ? sanitize_text_field($data['page_name']) : 'page';
        $page = isset($data['current_page']) ? sanitize_text_field($data['current_page']) : 1;
        $sort = isset($data['sort']) ? sanitize_text_field($data['sort']) : 'created_at';
        $order = isset($data['order']) ? sanitize_text_field($data['order']) : 'DESC';

        $gedcom = isset($data['gedcom']) ? sanitize_text_field($data['gedcom']) : '';


		$q = \WPGenealogy\Models\People::query();

		if($gedcom ) {
			$q->where('gedcom', '=', $gedcom);
		}

		foreach ($report->criteria as $key => $criteria) {
			$field = $criteria['field'];

			$oparator = html_entity_decode($criteria['oparator']);

			if($oparator=='greater') {
				$oparator = '>';
			}
			if($oparator=='less') {
				$oparator = '<';
			}
			if($oparator=='greater=') {
				$oparator = '>=';
			}
			if($oparator=='less=') {
				$oparator = '<=';
			}


			$value = $criteria['value'];

			if (strpos($field, 'date') !== false) {
				if (strpos($field, '_day') !== false) {
					$field = str_replace('_day', '', $field);
					if($value == 'Current Day') {
						$value = date('d');
					}
					$q->whereDay($field, $oparator, $value);
				} elseif (strpos($field, '_month') !== false) {
					$field = str_replace('_month', '', $field);
					if($value == 'Current Month') {
						$value = date('m');
					}
					$q->whereMonth($field, $oparator, $value);
				} elseif (strpos($field, '_year') !== false) {
					$field = str_replace('_year', '', $field);
					if($value == 'Current Year') {
						$value = date('Y');
					}
					$q->whereMonth($field, $oparator, $value);
				} else {
					if($value) {
						$q->whereDate($field, $oparator, $value);
					} else {
						$q->whereDate($field, $oparator, '0000-00-00');
					}
				}
			} else {
				if($report->criteria[$key-1]['oparatorPrefix'] == 'OR') {
					$q->orWhere($field, $oparator, $value);
				} else {
					$q->where($field, $oparator, $value);
				}
			}
		}
		$result = $q->orderBy($sort, $order)->paginate($perPage, $columns, $pageName, $page);
		wp_send_json($result, 200);
	}


	/**
	 * Function to get trees.
	 *
	 * @since    3.0.0
	 */
	public function report_update() {
		$data = $this->post_fixer($_POST);

		$data['display'] = serialize($data['display']);
		$data['criteria'] = serialize($_POST['criteria']);
		$data['orderby'] = serialize($data['orderby']);

		/**
		 * get all report
		 *
		 */
		$this->update(\WPGenealogy\Models\Report::class, $data, ['id' => $data['id']]);
	}

	/**
	 * Function to get trees.
	 *
	 * @since    3.0.0
	 */
	public function report_add() {

		$data = $this->post_fixer($_POST);





		$data['display'] = serialize($data['display']);
		$data['criteria'] = serialize($_POST['criteria']);
		$data['orderby'] = serialize($data['orderby']);
		/**
		 * get all report
		 *
		 */
		$this->add(\WPGenealogy\Models\Report::class, $data);
	}

	/**
	 * Function to delete people.
	 *
	 * @since    3.0.0
	 */
	public function report_delete() {
		/**
		 * delete note_link.
		 *
		 */
		$data = $this->post_fixer($_POST);
		$this->delete(\WPGenealogy\Models\Report::class, $data['selected']);
	}
}