<?php 
namespace WPGenealogy_Public\Traits;

trait Relationship {

	/**
	 * wpgenealogy function to get data for counter.
	 *
	 * @since    3.0.0
	 */
	public function get_relationship(){
		$data = $this->post_fixer($_POST);

		$pid = $data['query']['primaryID'];
		$sid = $data['query']['secondryID'];
		$gedcom = $data['query']['gedcom'];

		$primaryName = $data['query']['primaryName'];
		$secondryName = $data['query']['secondryName'];
		$primarySex = $data['query']['primarySex'];
		$secondrySex = $data['query']['secondrySex'];

		$relationship = $this->find_relationship($pid, $primaryName, $primarySex, $sid, $secondryName, $secondrySex, 10, $gedcom);

		wp_send_json($relationship, 200);
	}

	/**
	 * wpgenealogy function to get data for counter.
	 *
	 * @since    3.0.0
	 */
	public function find_relationship($pid, $pname, $psex, $sid, $sname, $ssex, $gen=15, $gedcom) {
		$peoples    = \WPGenealogy\Models\People::where('gedcom', $gedcom)->get();
		$families   = \WPGenealogy\Models\Family::where('gedcom', $gedcom)->get();
		$childrens  = \WPGenealogy\Models\Children::where('gedcom', $gedcom)->get();
		$data = $this->generate_raw_tree_bs($peoples, $families, $childrens, array(), $pid);
		$result =  $this->get_parent($pid, $sid, $data);
		if(!empty($result)) {
			if($result['relation']['fsid']==1 && $result['relation']['sp']) {
				if($result['relation']['level']==1) {
					$rel = 'spouse';
					if($psex=='m' || $psex=='M') {
						$rel = 'husband';
					} 
					if($psex=='f' || $psex=='F') {
						$rel = 'wife';
					}
					$result['message'] = $pname.' is '.$rel.' of '.$sname;
				}
				if($result['relation']['level']==2) {
					$rel = 'parent-in-law';
					if($psex=='m' || $psex=='M') {
						$rel = 'father-in-law ';
					} 
					if($psex=='f' || $psex=='F') {
						$rel = 'mother-in-law';
					}
					$result['message'] = $pname.' is the '.$rel.' of '.$sname;
				}
				if($result['relation']['level']==3) {
					$rel2 = 'spouse';
					if($ssex=='m' || $ssex=='M') {
						$rel2 = 'wife';
					}
					if($ssex=='f' || $ssex=='F') {
						$rel2 = 'husband';
					}
					$rel = 'parent of the '.$rel2.' ';
					if($psex=='m' || $psex=='M') {
						$rel = 'grand father of the '.$rel2.' ';
					} 
					if($psex=='f' || $psex=='F') {
						$rel = 'grand mother of the '.$rel2.' ';
					}
					$result['message'] = $pname.' is the '.$rel.' of '.$sname;
				}
				if($result['relation']['level']==4) {
					$rel2 = 'spouse';
					if($ssex=='m' || $ssex=='M') {
						$rel2 = 'wife';
					}
					if($ssex=='f' || $ssex=='F') {
						$rel2 = 'husband';
					}
					$rel = 'parent of the '.$rel2.' ';
					if($psex=='m' || $psex=='M') {
						$rel = 'great grand father of the '.$rel2.' ';
					} 
					if($psex=='f' || $psex=='F') {
						$rel = 'great grand mother of the '.$rel2.' ';
					}
					$result['message'] = $pname.' is the '.$rel.' of '.$sname;
				}
				if($result['relation']['level'] > 4) {
					$x = $result['relation']['level']-3;
					$rel2 = 'spouse';
					if($ssex=='m' || $ssex=='M') {
						$rel2 = 'wife';
					}
					if($ssex=='f' || $ssex=='F') {
						$rel2 = 'husband';
					}
					$rel = 'parent of the '.$rel2.' ';
					if($psex=='m' || $psex=='M') {
						$rel = ($x+1).'x great grand father of the '.$rel2.' ';
					} 
					if($psex=='f' || $psex=='F') {
						$rel = ($x+1).'x great grand mother of the '.$rel2.' ';
					}
					$result['message'] = $pname.' is the '.$rel.' of '.$sname;
				}
			}
			if($result['relation']['fsid']==1 && !$result['relation']['sp']) {
				if($result['relation']['level']==2) {
					$rel = 'parent';
					if($psex=='m' || $psex=='M') {
						$rel = 'father';
					} 
					if($psex=='f' || $psex=='F') {
						$rel = 'mother';
					}
					$result['message'] = $pname.' is '.$rel.' of '.$sname;
				}
				if($result['relation']['level']==3) {
					$rel = 'grandparent';
					if($psex=='m' || $psex=='M') {
						$rel = 'grandfather';
					} 
					if($psex=='f' || $psex=='F') {
						$rel = 'grandmother';
					}
					$result['message'] = $pname.' is the '.$rel.' of '.$sname;
				}
				if($result['relation']['level']==4) {
					$rel = 'grandparent';
					if($psex=='m' || $psex=='M') {
						$rel = 'great grandfather';
					} 
					if($psex=='f' || $psex=='F') {
						$rel = 'great grandmother';
					}
					$result['message'] = $pname.' is the '.$rel.' of '.$sname;
				}
				if($result['relation']['level'] > 4) {
					$x = $result['relation']['level']-3;
					$rel = 'grandparent';
					if($psex=='m' || $psex=='M') {
						$rel = ($x+1).'x great grandfather';
					} 
					if($psex=='f' || $psex=='F') {
						$rel = ($x+1).'x great grandmother';
					}
					$result['message'] = $pname.' is the '.$rel.' of '.$sname;
				}
			}
		}
		if(empty($result)) {
			$data = $this->generate_raw_tree_bs($peoples, $families, $childrens, array(), $sid);
			$result =  $this->get_parent($sid, $pid, $data);
			if(!empty($result)) {
				if($result['relation']['fsid']==1 && $result['relation']['sp']) {
					if($result['relation']['level']==1) {
						$rel = 'spouse';
						if($ssex=='m' || $ssex=='M') {
							$rel = 'husband';
						} 
						if($ssex=='f' || $ssex=='F') {
							$rel = 'wife';
						}
						$result['message'] = $sname.' is '.$rel.' of '.$pname;
					}
					if($result['relation']['level']==2) {
						$rel = 'parent-in-law';
						if($ssex=='m' || $ssex=='M') {
							$rel = 'father-in-law ';
						} 
						if($ssex=='f' || $ssex=='F') {
							$rel = 'mother-in-law';
						}
						$result['message'] = $sname.' is the '.$rel.' of '.$pname;
					}
					if($result['relation']['level']==3) {
						$rel2 = 'spouse';
						if($psex=='m' || $psex=='M') {
							$rel2 = 'wife';
						}
						if($psex=='f' || $psex=='F') {
							$rel2 = 'husband';
						}
						$rel = 'parent of the '.$rel2.' ';
						if($ssex=='m' || $ssex=='M') {
							$rel = 'grand father of the '.$rel2.' ';
						} 
						if($ssex=='f' || $ssex=='F') {
							$rel = 'grand mother of the '.$rel2.' ';
						}
						$result['message'] = $sname.' is the '.$rel.' of '.$pname;
					}
					if($result['relation']['level']==4) {
						$rel2 = 'spouse';
						if($psex=='m' || $psex=='M') {
							$rel2 = 'wife';
						}
						if($psex=='f' || $psex=='F') {
							$rel2 = 'husband';
						}
						$rel = 'parent of the '.$rel2.' ';
						if($ssex=='m' || $ssex=='M') {
							$rel = 'great grand father of the '.$rel2.' ';
						} 
						if($ssex=='f' || $ssex=='F') {
							$rel = 'great grand mother of the '.$rel2.' ';
						}
						$result['message'] = $sname.' is the '.$rel.' of '.$pname;
					}
					if($result['relation']['level'] > 4) {
						$x = $result['relation']['level']-3;
						$rel2 = 'spouse';
						if($psex=='m' || $psex=='M') {
							$rel2 = 'wife';
						}
						if($psex=='f' || $psex=='F') {
							$rel2 = 'husband';
						}
						$rel = 'parent of the '.$rel2.' ';
						if($ssex=='m' || $ssex=='M') {
							$rel = ($x+1).'x great grand father of the '.$rel2.' ';
						} 
						if($ssex=='f' || $ssex=='F') {
							$rel = ($x+1).'x great grand mother of the '.$rel2.' ';
						}
						$result['message'] = $sname.' is the '.$rel.' of '.$pname;
					}
				}
				if($result['relation']['fsid']==1 && !$result['relation']['sp']) {
					if($result['relation']['level']==2) {
						$rel = 'parent';
						if($ssex=='m' || $ssex=='M') {
							$rel = 'father';
						} 
						if($ssex=='f' || $ssex=='F') {
							$rel = 'mother';
						}
						$result['message'] = $sname.' is '.$rel.' of '.$pname;
					}
					if($result['relation']['level']==3) {
						$rel = 'grandparent';
						if($ssex=='m' || $ssex=='M') {
							$rel = 'grandfather';
						} 
						if($ssex=='f' || $ssex=='F') {
							$rel = 'grandmother';
						}
						$result['message'] = $sname.' is the '.$rel.' of '.$pname;
					}
					if($result['relation']['level']==4) {
						$rel = 'grandparent';
						if($ssex=='m' || $ssex=='M') {
							$rel = 'great grandfather';
						} 
						if($ssex=='f' || $ssex=='F') {
							$rel = 'great grandmother';
						}
						$result['message'] = $sname.' is the '.$rel.' of '.$pname;
					}
					if($result['relation']['level'] > 4) {
						$x = $result['relation']['level']-3;
						$rel = 'grandparent';
						if($ssex=='m' || $ssex=='M') {
							$rel = ($x+1).'x great grandfather';
						} 
						if($ssex=='f' || $ssex=='F') {
							$rel = ($x+1).'x great grandmother';
						}
						$result['message'] = $sname.' is the '.$rel.' of '.$pname;
					}
				}
			}
			if(empty($result)) {
				$result = $this->get_cousin_through_parent($peoples, $families, $childrens, array(), $pid, $sid);
				if(!empty($result)) {
					if($result['level-up'] && $result['relation']['level']){
						if($result['level-up'] < $result['relation']['level']) {
							$result['path_reverse'] = array_slice($result['relation']['path'], 0, $result['level-up']);
							$media = last($result['path_reverse']);
						}
						if($result['level-up'] > $result['relation']['level']) {
							$result['path_reverse'] = array_slice($result['path'], -$result['relation']['level']);;
							$media = current($result['path_reverse']);
						}
						$media_mem = $peoples->first(function($people) use ($media) { 
							if( $people->personID == $media ) {
								return $people;
							} 
						});
						if($result['level-up']==2) {
							$rel1 = 'sibling';
						}
						if($result['level-up']==3) {
							$rel1 = 'cusin';
						}
						if($result['level-up'] > 3) {
							$x = $result['level-up'] - 3;
							$rel1 = $x+1 .'x cusin';
						}
						if($result['level-up'] == $result['relation']['level']) {
							$result['message'] = $sname.' is  '.$rel1.' of '.$pname;
						}
						if($result['level-up'] < $result['relation']['level']) {
							$result['level-diff'] = $result['relation']['level'] - $result['level-up'];
						}
						if($result['level-up'] > $result['relation']['level']) {
							$result['level-diff'] = $result['level-up'] - $result['relation']['level'] ;
						}
						if($result['level-diff']==1) {
							$rel2 = 'father/mother';
						}
						if($result['level-diff']==2) {
							$rel2 = 'grand father/mother';
						}
						if($result['level-diff']==3) {
							$rel2 = 'great grand father/mother';
						}
						if($result['level-diff'] > 3) {
							$x = $result['level-diff'] - 3;
							$rel2 = $x+1 .'x great grand father/mother';
						}
						if($result['level-up'] < $result['relation']['level']) {
							$result['message'] = $sname.'\'s '.$rel2 .' '.$media_mem->name.' is '.$rel1.' of '.$pname;
						}
						if($result['level-up'] > $result['relation']['level']) {
							$result['message'] = $pname.'\'s '.$rel2 .' '.$media_mem->name.' is '.$rel1.' of '.$sname;
						}
					}
				}

				if(empty($result)) {
					$result = $this->get_cousin_through_parent($peoples, $families, $childrens, array(), $sid, $pid);
					if(!empty($result)) {
						if($result['level-up'] && $result['relation']['level']){
							if($result['level-up'] < $result['relation']['level']) {
								$result['path_reverse'] = array_slice($result['relation']['path'], 0, $result['level-up']);
								$media = last($result['path_reverse']);
							}
							if($result['level-up'] > $result['relation']['level']) {
								$result['path_reverse'] = array_slice($result['path'], -$result['relation']['level']);;
								$media = current($result['path_reverse']);
							}
							$media_mem = $peoples->first(function($people) use ($media) { 
								if( $people->personID == $media ) {
									return $people;
								} 
							});
							if($result['level-up']==2) {
								$rel1 = 'sibling';
							}
							if($result['level-up']==3) {
								$rel1 = 'cusin';
							}
							if($result['level-up'] > 3) {
								$x = $result['level-up'] - 3;
								$rel1 = $x+1 .'x cusin';
							}
							if($result['level-up'] == $result['relation']['level']) {
								$result['message'] = $pname.' is  '.$rel1.' of '.$sname;
							}
							if($result['level-up'] < $result['relation']['level']) {
								$result['level-diff'] = $result['relation']['level'] - $result['level-up'];
							}
							if($result['level-up'] > $result['relation']['level']) {
								$result['level-diff'] = $result['level-up'] - $result['relation']['level'] ;
							}
							if($result['level-diff']==1) {
								$rel2 = 'father/mother';
							}
							if($result['level-diff']==2) {
								$rel2 = 'grand father/mother';
							}
							if($result['level-diff']==3) {
								$rel2 = 'great grand father/mother';
							}
							if($result['level-diff'] > 3) {
								$x = $result['level-diff'] - 3;
								$rel2 = $x+1 .'x great grand father/mother';
							}
							if($result['level-up'] < $result['relation']['level']) {
								$result['message'] = $pname.'\'s '.$rel2 .' '.$media_mem->name.' is '.$rel1.' of '.$sname;
							}
							if($result['level-up'] > $result['relation']['level']) {
								$result['message'] = $sname.'\'s '.$rel2 .' '.$media_mem->name.' is '.$rel1.' of '.$pname;
							}
						}
					}
				}
			}
		}





		if(!empty($result)) {
			$html = '';
			if($result['relation']['path'] && !$result['path']) {
				$personID = current($result['relation']['path']);
				$html .= '<ul>';
				if($result['relation']) {
					if($result['relation']['path']) {
						foreach ($result['relation']['path'] as $key => $value) {
							$people = $peoples->first(function($people) use ($value) { 
								if( $people->personID == $value ) {
									return $people;
								}
							});
							$html .= '<li>
							<div>'.$people->name.'</div>
							</li>';
						}
						if($result['relation']['spID']) {
							$personID = $result['relation']['spID'];

							$people = $peoples->first(function($people) use ($personID) { 
								if( $people->personID == $personID ) {
									return $people;
								}
							});
							$html .= '<li>
							<div class="spu">'.$people->name.'</div>
							</li>';
						}
					}
				}
				$html .= '</ul>';
			}
			if($result['relation']['path'] && $result['path']) {
				$html .= '<ul>';
				$personID = current($result['relation']['path']);
				$people = $peoples->first(function($people) use ($personID) { 
					if( $people->personID == $personID ) {
						return $people;
					} 
				});
				$html .= '<li><div>'.$people->name.'</div></li>';
				$html .= '<li><ul>';
				if($result['relation']) {
					if($result['relation']['path']) {
						$html .= '<li><ul>';
						$x = 0;
						foreach ($result['relation']['path'] as $key => $value) {
							if($x!=0){
								$people = $peoples->first(function($people) use ($value) { 
									if( $people->personID == $value ) {
										return $people;
									} 
								});
								$html .= '<li><div>'.$people->name.'</div></li>';
							}
							$x++;
						}
						$html .= '</ul></li>';
					}
				}
				if($result['path']) {
					$html .= '<li><ul>';
					$x = 0;
					foreach ( array_reverse($result['path']) as $key => $value) {
						if($x!=0){
							$people = $peoples->first(function($people) use ($value) { 
								if( $people->personID == $value ) {
									return $people;
								} 
							});
							$html .= '<li><div>'.$people->name.'</div></li>';
						}
						$x++;
					}
					$html .= '</ul></li>';
				}
				$html .= '</ul></li>';
				$html .= '</ul>';
			}
			$result['html'] = $html;
		}
		if(empty($result)) {
			$result['message'] = 'No relationship found';
		}
		return $result;
	}

	/**
	 * 
	 * generate raw tree
	 */	
	public function get_cousin_through_parent($peoples, $families, $childrens, $tree, $personID, $spersonID, $level = 0, $result=array(), $st=array()){
		
		print_r($personID);
		print_r('
			');

		$level++;
		$tree = $this->generate_raw_tree_bs($peoples, $families, $childrens, array(), $personID);
		$relation =  $this->get_parent($personID, $spersonID, $tree);
		$st[$level] = $personID;
		if(!empty($relation)) {
			$result = $relation;
			$result['level-up'] = $level;
			$result['path'] = $st;
		} else  {
			$tree = $this->generate_raw_tree_bs($peoples, $families, $childrens, array(), $spersonID);
			$relation =  $this->get_parent($spersonID, $personID, $tree);
			if(!empty($relation)) {
				$result= $relation;
				$result['level-up'] = $level;
				$result['path'] = $st;
			}
		}
		$children = $childrens->first(function($children) use ($personID) { 
			if( $children->personID == $personID ) {
				return $children;
			} 
		});
		if($children) {
			$parent_family_ID = $children->familyID;
			$family = $families->first(function($family) use ($parent_family_ID) { 
				if( $parent_family_ID == $family->familyID) {
					return $family;
				} 
			});
			if($family && $family->husband && empty($result)) {
				$result = $this->get_cousin_through_parent($peoples, $families, $childrens, $tree, $family->husband, $spersonID, $level, $result, $st);
			}
		}
		return $result;
	}





	/**
	 * wpgenealogy function to get data for counter.
	 *
	 * @since    3.0.0
	 */
	public function get_parent($pid, $sid, $data, $level=0, $result = array(), $path=array()){
		$level++;
		$path[$level] = $data['ind']['personID'];
		if($data['ind']) {
			if($data['ind']['personID'] == $sid) {
				$result['relation']['fsid']=1;
				$result['relation']['level']=$level;
				$result['relation']['sp']=false;
				$result['relation']['path']=$path;
			}
			if($data['families']) {
				foreach ($data['families'] as $key => $familiy) {
					if(empty($result)){
						if($familiy['husband'] == $sid || $familiy['wife'] == $sid) {

							$result['relation']['fsid']=1;
							$result['relation']['level']=$level;
							$result['relation']['sp']=true;
							$result['relation']['spID']=$sid;
							$result['relation']['path']=$path;
						}
					}
					if($familiy['childrens']) {
						foreach ($familiy['childrens'] as $key => $children) {
							if(empty($result)){
								$result = $this->get_parent($pid, $sid, $children, $level, $result, $path);
							}
						}
					}
				}
			}
		}
		return $result;
	}

}