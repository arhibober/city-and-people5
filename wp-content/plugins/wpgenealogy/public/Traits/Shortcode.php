<?php 

namespace WPGenealogy_Public\Traits;

trait Shortcode {
	/**
	 * Function wpgenealogy test.
	 *
	 * @since    3.0.0
	 */
	public function wpgenealogy_test_func( $atts ) {
		//print_r("<pre>");
		//$results  = $this->convertDate($this->removeSpace('06 JUN 1942'));
		//print_r($results);
		//print_r("<pre>");
	}

	/**
	 * Function to display wpgenealogy chart.
	 *
	 * @since    3.0.0
	 */
	public function wpgenealogy_chart_func( $atts ) {
		/**
		 * 
		 * get shortcode atts.
		 */	
		$data = shortcode_atts( array(
			'id'   => '',
		), $atts );

		/**
		 * 
		 * check if tree value exist.
		 */
		if(!$data['id']) {
			return 'Require tree id.';
		}
		$chart  = \WPGenealogy\Models\Chart::find($data['id']);

		return do_shortcode("[wpgenealogy-tree tree='".$chart->gedcom."' branch ='".$chart->branch."' settings='".$chart->settings."']");
	}


	public function wpgenealogy_public_wedget(){
		$wpgenealogy_settings =  get_option('wpgenealogy_settings');
		$wpgenealogy_page_id = $wpgenealogy_settings['configuration']['general']['pages']['wpgenealogy_page_id'];
		$wpgenealogy_page_url = esc_attr( esc_url( get_the_permalink($wpgenealogy_page_id)));
		return '
		<div class="row  front-wedget">
			<div class="container">
				<div class="row">
					<div class="col-md-3">
						<ul class="nav flex-column">
							<li class="nav-item">
								<a class="nav-link" href="'.$wpgenealogy_page_url.'#/surname/">Surnames</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="'.$wpgenealogy_page_url.'#/people/search/advanced/">Advanced Search</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="'.$wpgenealogy_page_url.'#/family/search/advanced/">Search Families</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="'.$wpgenealogy_page_url.'#/site/">Search Site</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="'.$wpgenealogy_page_url.'#/place/">Places</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="'.$wpgenealogy_page_url.'#/anniversaries/">Dates and Anniversaries</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="'.$wpgenealogy_page_url.'#/calender/">Calender</a>
							</li>
						</ul>
					</div>
					<!-- 
					<div class="col-md-3">
						<ul class="nav flex-column">
							<li class="nav-item"><a href="" class="nav-link">Cemeteries</a></li>
							<li class="nav-item"><a href="" class="nav-link">Bookmarks</a></li>
							<li class="nav-item"><a href="" class="nav-link">Photos</a></li>
							<li class="nav-item"><a href="" class="nav-link">Documents</a></li>
							<li class="nav-item"><a href="" class="nav-link">Headstones</a></li>
							<li class="nav-item"><a href="" class="nav-link">Histories</a></li>
							<li class="nav-item"><a href="" class="nav-link">Recordings</a></li>
						</ul>
					</div> 
					-->
					<div class="col-md-3">
						<ul class="nav flex-column">
							<!-- 
							<li class="nav-item"><a href="" class="nav-link">Videos</a></li>
							<li class="nav-item"><a href="" class="nav-link">All Media</a></li>
							<li class="nav-item"><a href="" class="nav-link">Albums</a></li> 
							-->
							<li class="nav-item">
								<a class="nav-link" href="'.$wpgenealogy_page_url.'#/new/">What\'s New</a>
							</li>
							<!-- 
							<li class="nav-item"><a href="" class="nav-link">Most Wanted</a></li> 
							-->
							<li class="nav-item">
								<a class="nav-link" href="'.$wpgenealogy_page_url.'#/report/">Reports</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="'.$wpgenealogy_page_url.'#/statistics/">Statistics</a>
							</li>
							
						</ul>
					</div>
					<div class="col-md-3">
						<ul class="nav flex-column">
							<li class="nav-item">
								<a class="nav-link" href="'.$wpgenealogy_page_url.'#/tree/">Trees</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="'.$wpgenealogy_page_url.'#/note/">Notes</a>
							</li>
							<!-- 
							<li class="nav-item"><a href="" class="nav-link">Spources</a></li>
							<li class="nav-item"><a href="" class="nav-link">Repotories</a></li>
							<li class="nav-item"><a href="" class="nav-link">DNA Tests</a></li>
							<li class="nav-item"><a href="" class="nav-link">Access Log</a></li> 
							-->
						</ul>
					</div>
				</div>
			</div>
		</div>';
	}

}