<?php 
namespace WPGenealogy_Public\Traits;
use WPGenealogy\Eloquent\Facades\DB;

trait FamilyTree {

	/**
	 * 
	 * wpgenealogy tree function.
	 */	
	public function wpgenealogy_tree_func( $atts ) {
		
		$html = '';

		/**
		 * 
		 * get shortcode atts.
		 */	
		$data = shortcode_atts( array(
			'tree'   => '',
			'branch' => '',
			'settings' => ''
		), $atts );

		/**
		 * 
		 * check if tree value exist.
		 */
		if(!$data['tree']) {
			return 'Require tree id.';
		}

		/**
		 * 
		 * check if branch value exist.
		 */
		if(!$data['branch']) {
			return 'Require branch id.';
		}


		$settings = unserialize($data['settings']);

		//print_r('<pre>');
		//print_r($settings);
		//print_r('</pre>');

		/**
		 * 
		 * get all required data to build tree.
		 */
		$branch     = \WPGenealogy\Models\Branch::where('gedcom', $data['tree'])->where('branch', $data['branch'])->first();

		$peoples    = \WPGenealogy\Models\People::where('gedcom', $data['tree'])->get();
		$families   = \WPGenealogy\Models\Family::where('gedcom', $data['tree'])->get();
		$childrens  = \WPGenealogy\Models\Children::where('gedcom', $data['tree'])->get();
		
		/**
		 * 
		 * get branch starting personID.
		 */
		$personID = $branch->personID;

		/**
		 * 
		 * check if branch starting personID.
		 */
		if(!$personID) {
			return 'Require personID of starting individuals on branch.';
		}

		/**
		 * 
		 * generate tree by provided data.
		 */
		$tree = $this->generate_raw_tree($peoples, $families, $childrens, array(), $personID);
		
		/**
		 * 
		 * generate rand string.
		 */
		$rand = str_shuffle('sTriN3gStr12');

		/**
		 * 
		 * get wpgenealogy page link.
		 */
		$wpgenealogy_settings =  get_option('wpgenealogy_settings');
		$wpgenealogy_page_id  = $wpgenealogy_settings['configuration']['general']['pages']['wpgenealogy_page_id'];
		$wpgenealogy_page_link  = get_the_permalink($wpgenealogy_page_id);


		/**
		 * 
		 * tree style.
		 */
		$html .= '
		<style>
			.wpgenealogy-default-tree-'.$rand.' {
				white-space: nowrap;
				position: relative;
				margin: 0px !important;
				padding: 0px !important;
			}
			.wpgenealogy-default-tree-'.$rand.' > .wpgenealogy-default-tree-conetainer > .childs:before {
				display: none;
			}
			.wpgenealogy-default-tree-'.$rand.' ul,
			.wpgenealogy-default-tree-'.$rand.' li {
				vertical-align: top;
				text-align: center;
				margin: 0 !important;
				padding: 0 !important;
				list-style: none;
			}
			.wpgenealogy-default-tree-'.$rand.' li {
				margin-top: 10px !important
			}
			.wpgenealogy-default-tree-'.$rand.' .d-inline-b {
				display: inline-block;
				vertical-align: top;
			}
			.wpgenealogy-default-tree-'.$rand.' .person {
			    margin-top: 10px;
			    padding-left: 4px;
			    padding-right: 4px;
			}
			.wpgenealogy-default-tree-'.$rand.' .person .n-a .indi-info{
				padding: 5px;
				font-size: 11px;
			}
			.wpgenealogy-default-tree-'.$rand.' .person>div>div {
				border: 1px solid;
			    background: #e3e5e7;
			    display: inline-block;
			    margin: 0 auto;
			    position: relative;
			}
			.wpgenealogy-default-tree-'.$rand.'  span.spouse_divorced {
			    position: absolute;
			    background: #ff00008a;
			    left: 4px;
			    top: 3px;
			    width: 50px;
			    text-align: center;
			    border-radius: 3px;
			    font-size: 10px;
			}
			.wpgenealogy-default-tree-'.$rand.' .person table ,
			.wpgenealogy-default-tree-'.$rand.' .person table tr{
				margin: 0px !important;
				padding: 0px !important;
			}
			.wpgenealogy-default-tree-'.$rand.' .person table,
			.wpgenealogy-default-tree-'.$rand.' .person table td {
				border:0px;
				display: inline-flex;
				vertical-align: top;
				padding: 0px;
			}
			.wpgenealogy-default-tree-'.$rand.' .person table td {
				padding: 3px !important;
			}
			.wpgenealogy-default-tree-'.$rand.' .person table td .tt-cont {
				padding: 5px;
				font-size: 11px;
			}
			.wpgenealogy-default-tree-'.$rand.' .person table td .image {
				position: relative;
			}
			.wpgenealogy-default-tree-'.$rand.' .person table td .image img {
				display: block;
				max-width: 50px;
			}
			.wpgenealogy-default-tree-'.$rand.' .childs {
				position: relative;
			}
			.wpgenealogy-default-tree-'.$rand.' .childs:before {
				content: "";
				position: absolute;
				top: 10px;
				left: 35px;
				right: 50%;
				height: 10px;
				border-top: 1px solid;
				border-left: 1px solid;
			}
			.wpgenealogy-default-tree-'.$rand.' .childs > li {
				display: inline-block;
				position: relative;
			}
			.wpgenealogy-default-tree-'.$rand.' .childs>li>div>.person {
				position: relative;
			}
			.wpgenealogy-default-tree-'.$rand.' .childs>li>div.spouse>.person:before {
				content: "";
				position: absolute;
				top: 20px;
				left: -4px;
				right: -4px;
				/* z-index: -1; */
				border-top: 1px solid;
				height: 4px;
				margin-top: -2px;
				border-bottom: 1px solid;
			}
			.wpgenealogy-default-tree-'.$rand.' .childs>li>div.spouse:last-child:not(:only-child)>.person:before {
				right: 50%;
			}
			.wpgenealogy-default-tree-'.$rand.' .childs>li>div.spouse.haschill>.person:after {
				content: "";
				position: absolute;
				bottom: -11px;
				height: 11px;
				left: 50%;
				right: 50%;
				/* z-index: -1; */
				border-left: 1px solid;
			}
			.wpgenealogy-default-tree-'.$rand.' .childs>li:not(:only-child):not(:first-child)>.person:before {
				content: "";
				position: absolute;
				left: -4px;
				height: 10px;
				border-top: 1px solid;
				top: 0px;
				width: 35px;
				border-right: 1px solid;
			}
			.wpgenealogy-default-tree-'.$rand.' .childs>li:not(:first-child):not(:last-child)>.person:before {
				content: "";
				position: absolute;
				left: -4px;
				height: 10px;
				border-top: 1px solid;
				top: 0px;
				width: 35px;
				border-right: 0px solid;
				right: -4px;
				width: auto;
			}
			.wpgenealogy-default-tree-'.$rand.' .childs>li:not(:first-child):not(:last-child)>.person:after {
				content: "";
				position: absolute;
				border-left: 1px solid;
				left: 20px;
				height: 10px;
				top: 0px;
			}
			.wpgenealogy-default-tree-'.$rand.' .childs>li:first-child:not(:only-child):before {
				content: "";
				position: absolute;
				right: 0px;
				left: 35px;
				border-top: 1px solid;
			}
			.wpgenealogy-default-tree-'.$rand.'>ul.childs:before {
				border-top: 0px solid !important;
				border-left: 0px solid !important;
			}
			.wpgenealogy-default-tree-'.$rand.' {
				overflow: hidden;
				background: '.$settings['color'].';
				max-width: 100% !important;
				margin: 50px 50px 50px 50px !important;
				padding: 50px 50px 50px 50px !important;
			}
			.wpgenealogy-default-tree-'.$rand.' .d-inline-b.person>div>div {
				background-color: '.$settings['box']['color']['other'].';
				border-style: '.$settings['box']['border']['style'].';
				border-width: '.$settings['box']['border']['weight'].';
				border-radius: '.$settings['box']['border']['radius'].';
				border-color: '.$settings['box']['border']['color']['other'].';
			}
			.wpgenealogy-default-tree-'.$rand.' .d-inline-b.person.M>div>div {
				background-color: '.$settings['box']['color']['male'].';
				border-style: '.$settings['box']['border']['style'].';
				border-width: '.$settings['box']['border']['weight'].';
				border-radius: '.$settings['box']['border']['radius'].';
				border-color: '.$settings['box']['border']['color']['male'].';
			}
			.wpgenealogy-default-tree-'.$rand.' .d-inline-b.person.F>div>div {
				background-color: '.$settings['box']['color']['female'].';
				border-style: '.$settings['box']['border']['style'].';
				border-width: '.$settings['box']['border']['weight'].';
				border-radius: '.$settings['box']['border']['radius'].';
				border-color: '.$settings['box']['border']['color']['female'].';
			}';
			if(!isset($settings['box']['show'])) {
				$html .= '
					.wpgenealogy-default-tree-'.$rand.' .d-inline-b.person>div>div {
						border:0px !important;
						border-radius:0px !important;
					}
				';
			}
			$html .= '
			.wpgenealogy-default-tree-'.$rand.' .person table td .image {
				border-style: '.$settings['thumb']['border']['style'].';
				border-width: '.$settings['thumb']['border']['weight'].';
				border-radius: '.$settings['thumb']['border']['radius'].';
			}';
			if(!isset($settings['thumb']['show'])) {
				$html .= '
					.wpgenealogy-default-tree-'.$rand.' .person table td .image {
						display: none;
					}
				';
			}
			$html .= '
			.wpgenealogy-default-tree-'.$rand.' li > div.person-box .image {
				border-style: '.$settings['thumb']['border']['style'].';
				border-width: '.$settings['thumb']['border']['weight'].';
				border-radius: '.$settings['thumb']['border']['radius'].';
			}
			.wpgenealogy-default-tree-'.$rand.' .person .info .person-name{
				font-name: '.$settings['font']['name']['name'].';
				font-size: '.$settings['font']['name']['size'].';
				color: '.$settings['font']['name']['color'].';
			}
			.wpgenealogy-default-tree-'.$rand.' .person .info .bd-info{
				font-name: '.$settings['font']['other']['name'].';
				font-size: '.$settings['font']['other']['size'].';
				color: '.$settings['font']['other']['color'].';
			}
			';
			$html .= '
		</style>
		';

		/**
		 * 
		 * create tree.
		 */
		if($tree['ind']) {
			$html .= '<div id="wpgenealogy-default-tree-'.$rand.'" class="wpgenealogy-default-tree wpgenealogy-default-tree-'.$rand.'">';
				$html .= '<div class="wpgenealogy-default-tree-conetainer">';
				$html .= '<ul class="childs">';
				//print_r($settings);

					$html .= $this->html_raw_tree($tree, $wpgenealogy_page_link, $settings);
				$html .= '</ul>';
				$html .= '</div>';
			$html .= '</div>';
		}
		/**
		 * 
		 * tree style.
		 */
		$html .= '
		<script>
		jQuery(window).on(\'load\', function() {
			var element = document.querySelector(\'#wpgenealogy-default-tree-'.$rand.' > .wpgenealogy-default-tree-conetainer\');
			if(element){
				panzoom(element)
			}
		})
		</script>
		';
		return $html;
	}

	/**
	 * 
	 * ind birth day
	 */	
	public function ind_birth_day($inid, $settings = []){

		$html = '';
		
		if($inid->birthdate || $inid->deathdate) {
			$html .= '<small>(';
		}

		if($inid->birthdate) {
			if($settings['dob']['format']=='full') {
				$html .= 'b.'.$inid->birthdate;
			} else {
				$html .= 'b.'.date('Y', strtotime($inid->birthdatetr));

			}

		}
		if($inid->birthdate && $inid->deathdate) {
			$html .= ', ';
		}

		if($inid->deathdate) {
			if($settings['dod']['format']=='full') {
				$html .= 'd.'.$inid->deathdate;
			} else {
				$html .= 'b.'.date('Y', strtotime($inid->deathdatetr));
			}
		}
		if($inid->birthdate || $inid->deathdate) {
			$html .= ')</small>';
		}
		return $html;
	}
	/**
	 * 
	 * html raw tree.
	 */	
	public function html_raw_tree($tree, $wpgenealogy_page_link, $settings = []){



		$html ='';
		$html .= '<li>';
			$html .= '<div class="d-inline-b person '.$tree['ind']->sex.'">';
				$html .= '<div>';
					$html .= '<div>';
						$html .= '<table>';
							$html .= '<tr>';
								$html .= '<td>';
									$html .= '<div class="image">';
										$html .= '<img class="w-100" src="'.$tree['ind']->image['url'].'">';
									$html .= '</div>';
								$html .= '</td>';
								$html .= '<td>';
									$html .= '<div class="info">';
										$html .= '<a class="person-name" href="'.$wpgenealogy_page_link.'/#people/'.$tree['ind']->id.'">';

										if($settings['name']['format']=='full') {
											$html .= $tree['ind']->name;
										} else {
											$html .= $tree['ind']->firstname;
										}

										$html .= '</a>';
										$html .= '<div class="bd-info">';
											$html .= $this->ind_birth_day($tree['ind'], $settings);
										$html .= '</div>';
									$html .= '</div>';
								$html .= '</td>';
							$html .= '</tr>';
						$html .= '</table>';
					$html .= '</div>';
				$html .= '</div>';
				$html .= '</div>';



				if(count($tree['families']) > 0) {
					foreach ($tree['families'] as $key => $family) {
						if(count($family['childrens']) > 0){
							$haschill = 'haschill';
						} else {
							$haschill = false;
						}
						$html .= '<div class="d-inline-b spouse spouse-'.$key.' '.$haschill.'">';
							$html .= '<div class="person">';
								$html .= '<div>';
									$html .= '<div>';
									$husband = $wife  = '';
									if($family->husband && $tree['ind']->personID != $family->husband->personID) {
										$html .= '<div class="person-box  '.$family->husband->sex.'">';
											$html .= '<table>';
												$html .= '<tr>';
													$html .= '<td>';
														$html .= '<div class="image">';
														$html .= '<img class="w-100" src="'.$family->husband->image['url'].'">';
														$html .= '</div>';
													$html .= '</td>';
													$html .= '<td>';
														$html .= '<div class="info">';
															$html .= '<a class="person-name" href="'.$wpgenealogy_page_link.'/#people/'.$family->husband->id.'">';

															if($settings['name']['format']=='full') {
																$html .= $family->husband->name;
															} else {
																$html .= $family->husband->firstname;
															}

															$html .= '</a>';
															$html .= '<div class="bd-info">';
																$html .= $this->ind_birth_day($family->husband, $settings);
															$html .= '</div>';
														$html .= '</div>';
													$html .= '</td>';
												$html .= '</tr>';
											$html .= '</table>';
										$html .= '</div>';
									}  else if($family->wife && $tree['ind']->personID != $family->wife->personID) {
										$html .= '<div class="person-box '.$family->wife->sex.'">';
											$html .= '<table>';
												$html .= '<tr>';
													$html .= '<td>';
														$html .= '<div class="image">';
														$html .= '<img class="w-100" src="'.$family->wife->image['url'].'">';
														$html .= '</div>';
													$html .= '</td>';
													$html .= '<td>';
														$html .= '<div class="info">';
															$html .= '<a class="person-name" href="'.$wpgenealogy_page_link.'/#people/'.$family->wife->id.'">';

															if($settings['name']['format']=='full') {
																$html .= $family->wife->name;
															} else {
																$html .= $family->wife->firstname;
															}

															$html .= '</a>';
															$html .= '<div class="bd-info">';
																$html .= $this->ind_birth_day($family->wife, $settings);
															$html .= '</div>';
														$html .= '</div>';
													$html .= '</td>';
												$html .= '</tr>';
											$html .= '</table>';
										$html .= '</div>';
									} else {
										$html .= '<div class="person-box">';
											$html .= '<table>';
												$html .= '<tr>';
													$html .= '<td>';

													$html .= '</td>';
													$html .= '<td>';
														$html .= '<div class="person-name">';
															$html .= '[N/A]';
														$html .= '</div>';
													$html .= '</td>';
												$html .= '</tr>';
											$html .= '</table>';
									$html .= '</div>';
									}
								$html .= '</div>';
							$html .= '</div>';
						$html .= '</div>';

					if(count($family['childrens']) > 0) {
						$html .= '<ul class="childs">';
						foreach ($family['childrens'] as $key => $children) {
							if($children['ind'] && $children['ind']->id){
								$html .= $this->html_raw_tree($children, $wpgenealogy_page_link, $settings);
							}
						}
						$html .= '</ul>';
					}
				$html .= '</div>';
				}
		}
		$html .= '</li>';
		return $html;
	}

	/**
	 * 
	 * generate raw tree
	 */	
	public function generate_raw_tree($peoples, $families, $childrens, $tree, $personID){

		/**
		 * 
		 * get tree root people.
		 */	
		$tree['ind'] = $peoples->first(function($people) use ($personID) { 
			if( $people->personID == $personID ) {
				return $people;
			} 
		});

		$people = $tree['ind'];
		
		/**
		 * 
		 * get families of root people.
		 */	
		$tree['families'] = $families->filter(function($family) use ($people) { 
			if( $people && ($family->husband == $people->personID || $family->wife == $people->personID)) {
				return $family;
			} 
		});

		/**
		 * 
		 * check if family exist of root people.
		 */	
		if($tree['families']) {
			foreach ($tree['families'] as $keyFamily => $family) {
				
				/**
				 * 
				 * check if family exist husband.
				 */	
				if($family->husband){
					$tree['families'][$keyFamily]->husband = $peoples->first(function($people) use ($family) { 
						if( $people->personID == $family->husband ) {
							return $people;
						} 
					});
				}
				
				/**
				 * 
				 * check if family exist wife.
				 */	
				if($family->wife){
					$tree['families'][$keyFamily]->wife = $peoples->first(function($people) use ($family) { 
						if( $people->personID == $family->wife ) {
							return $people;
						} 
					});
				}
				
				/**
				 * 
				 * check if family exist childrens.
				 */	
				$tree['families'][$keyFamily]['childrens'] = $childrens->filter(function($children) use ($family) { 
					if( $family->familyID == $children->familyID) {
						return $children;
					} 
				});

				/**
				 * 
				 * create tree for each children.
				 */	
				foreach ($tree['families'][$keyFamily]['childrens'] as $keyChildren => $children) {
					$tree['families'][$keyFamily]['childrens'][$keyChildren] = $this->generate_raw_tree($peoples, $families, $childrens, $tree, $children->personID);
				}
			}
		}
		return $tree;
	}

	/**
	 * 
	 * generate raw tree
	 */	
	public function generate_raw_tree_bs($peoples, $families, $childrens, $tree, $personID){
		/**
		 * 
		 * get tree root people.
		 */	
		$tree['ind'] = $peoples->first(function($people) use ($personID) { 
			if( $people->personID == $personID ) {
				return $people;
			} 
		})->toArray();

		unset($tree['ind']['lnprefix']);
		unset($tree['ind']['lastname']);
		unset($tree['ind']['firstname']);
		unset($tree['ind']['birthdate']);
		unset($tree['ind']['birthdatetr']);
		unset($tree['ind']['birthplace']);
		unset($tree['ind']['deathdate']);
		unset($tree['ind']['deathplace']);
		unset($tree['ind']['deathdatetr']);
		unset($tree['ind']['altbirthdate']);
		unset($tree['ind']['altbirthdatetr']);
		unset($tree['ind']['altbirthplace']);
		unset($tree['ind']['burialdate']);
		unset($tree['ind']['burialdatetr']);
		unset($tree['ind']['burialplace']);
		unset($tree['ind']['baptdate']);
		unset($tree['ind']['baptdatetr']);
		unset($tree['ind']['baptplace']);
		unset($tree['ind']['confdate']);
		unset($tree['ind']['confdatetr']);
		unset($tree['ind']['confplace']);
		unset($tree['ind']['initdate']);
		unset($tree['ind']['initdatetr']);
		unset($tree['ind']['initplace']);
		unset($tree['ind']['endldate']);
		unset($tree['ind']['endldatetr']);
		unset($tree['ind']['endlplace']);
		unset($tree['ind']['sex']);
		unset($tree['ind']['burialtype']);
		unset($tree['ind']['changedate']);
		unset($tree['ind']['nickname']);
		unset($tree['ind']['title']);
		unset($tree['ind']['prefix']);
		unset($tree['ind']['suffix']);
		unset($tree['ind']['nameorder']);
		unset($tree['ind']['famc']);
		unset($tree['ind']['metaphone']);
		unset($tree['ind']['living']);
		unset($tree['ind']['private']);
		unset($tree['ind']['branch']);
		unset($tree['ind']['changedby']);
		unset($tree['ind']['edituser']);
		unset($tree['ind']['edittime']);
		unset($tree['ind']['created_by']);
		unset($tree['ind']['created_at']);
		unset($tree['ind']['updated_at']);
		unset($tree['ind']['image']);
		unset($tree['ind']['age']);
		unset($tree['ind']['date']);
		unset($tree['ind']['events']);
		unset($tree['ind']['citations']);
		unset($tree['ind']['note_links']);

		$people = $tree['ind'];
		
		/**
		 * 
		 * get families of root people.
		 */	
		$tree['families'] = $families->filter(function($family) use ($people) { 
			if( $people && ($family->husband == $people['personID'] || $family->wife == $people['personID'])) {
				return $family;
			} 
		})->toArray();

		/**
		 * 
		 * check if family exist of root people.
		 */	
		if($tree['families']) {
			foreach ($tree['families'] as $keyFamily => $family) {
				
				unset($tree['families'][$keyFamily]['marrdate']);
				unset($tree['families'][$keyFamily]['marrdatetr']);
				unset($tree['families'][$keyFamily]['marrplace']);
				unset($tree['families'][$keyFamily]['marrtype']);
				unset($tree['families'][$keyFamily]['divdate']);
				unset($tree['families'][$keyFamily]['divdatetr']);
				unset($tree['families'][$keyFamily]['divplace']);
				unset($tree['families'][$keyFamily]['status']);
				unset($tree['families'][$keyFamily]['sealdate']);
				unset($tree['families'][$keyFamily]['sealdatetr']);
				unset($tree['families'][$keyFamily]['sealplace']);
				unset($tree['families'][$keyFamily]['husborder']);
				unset($tree['families'][$keyFamily]['wifeorder']);
				unset($tree['families'][$keyFamily]['changedate']);
				unset($tree['families'][$keyFamily]['living']);
				unset($tree['families'][$keyFamily]['private']);
				unset($tree['families'][$keyFamily]['branch']);
				unset($tree['families'][$keyFamily]['changedby']);
				unset($tree['families'][$keyFamily]['edituser']);
				unset($tree['families'][$keyFamily]['edittime']);
				unset($tree['families'][$keyFamily]['created_by']);
				unset($tree['families'][$keyFamily]['created_at']);
				unset($tree['families'][$keyFamily]['updated_at']);
				unset($tree['families'][$keyFamily]['events']);
				unset($tree['families'][$keyFamily]['citations']);
				unset($tree['families'][$keyFamily]['note_links']);
				unset($tree['families'][$keyFamily]['husband_obj']);
				unset($tree['families'][$keyFamily]['wife_obj']);

				/**
				 * 
				 * check if family exist childrens.
				 */	
				$tree['families'][$keyFamily]['childrens'] = $childrens->filter(function($children) use ($family) { 
					if( $family['familyID'] == $children->familyID) {
						return $children;
					} 
				})->toArray();

				/**
				 * 
				 * create tree for each children.
				 */	
				foreach ($tree['families'][$keyFamily]['childrens'] as $keyChildren => $children) {
					$tree['families'][$keyFamily]['childrens'][$keyChildren] = $this->generate_raw_tree_bs($peoples, $families, $childrens, $tree, $children['personID']);
				}
			}
		}
		return $tree;
	}
}