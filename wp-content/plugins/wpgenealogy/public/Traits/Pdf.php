<?php 
namespace WPGenealogy_Public\Traits;

trait Pdf {
	/**
	 * function to generate pdf individual report.
	 *
	 * @since    3.0.0
	 */
	public function generate_pdf_individual_report(){
		$data = $this->post_fixer($_POST);

		$pdf = new  \Dompdf\Dompdf();
		$html = '';
		$query = $data['query'];
		$page = $query['page'];
		if($query['people']) {
			$people = \WPGenealogy\Models\People::find($query['people']);
			$families = \WPGenealogy\Models\Family::where('husband', $people->personID)->orWhere('wife', $people->personID)->where('gedcom', $people->gedcom)->get();
			if($families) {
				foreach ($families as $key => $family) {
					if($family->husband){
						$family->husbObj = \WPGenealogy\Models\People::where('personID', $family->husband)->where('gedcom', $family->gedcom)->first();
					}
					if($family->wife){
						$family->wifeObj = \WPGenealogy\Models\People::where('personID', $family->wife)->where('gedcom', $family->gedcom)->first();
					}
					$family->childrens = [];
					$childrens = \WPGenealogy\Models\Children::where('familyID', $family->familyID)->where('gedcom', $family->gedcom)->get();
					if($childrens) {
						foreach ($childrens as $key => $children) {
							$children->person = \WPGenealogy\Models\People::where('personID', $children->personID)->where('gedcom', $children->gedcom)->first();
						}
					}
					$family->childrens = $childrens;
				}
			}
			$people->families = $families;
			$parents = \WPGenealogy\Models\Children::where('personID', $people->personID)->where('gedcom', $people->gedcom)->get();
			if($parents) {
				foreach ($parents as $key => $parent) {
					$parent->family = \WPGenealogy\Models\Family::where('familyID', $parent->familyID)->where('gedcom', $parent->gedcom)->first();
					if($parent->family->husband){
						$parent->family->husbObj = \WPGenealogy\Models\People::where('personID', $parent->family->husband)->where('gedcom', $parent->family->gedcom)->first();
					}
					if($parent->family->wife){
						$parent->family->wifeObj = \WPGenealogy\Models\People::where('personID', $parent->family->wife)->where('gedcom', $parent->family->gedcom)->first();
					}
				}
			}

			$people->parents = $parents;

			$html .= '
			<style>
				td {
					border: 1px solid #111111;
					padding: 1px;
					font-size: 16px;
				}
				@page { margin: 1in .5in; }
			</style>

			<table style="border-collapse: collapse;" collspan="0" rowspan="0" width="100%">';
				
				$html .= '<tr>';
					$html .= '<td><strong> Name </strong></td>';
					$html .= '<td><strong>'.$people->name.'</strong></td>';
					$html .= '<td><strong> Gender </strong></td>';
					$html .= '<td> '.$people->sex.' </td>';
				$html .= '</tr>';

				$html .= '<tr>';
					$html .= '<td><strong> Born </strong></td>';
					$html .= '<td>'.$people->birthdate.'</td>';
					$html .= '<td><strong> Place </strong></td>';
					$html .= '<td>'.$people->birthplace.'</td>';
				$html .= '</tr>';

				$html .= '<tr>';
					$html .= '<td><strong> Christened </strong></td>';
					$html .= '<td>'.$people->altbirthdate.'</td>';
					$html .= '<td> <strong>Place </strong></td>';
					$html .= '<td>'.$people->altbirthplace.'</td>';
				$html .= '</tr>';

				$html .= '<tr>';
					$html .= '<td><strong>  Died</strong></td>';
					$html .= '<td>'.$people->deathdate.'</td>';
					$html .= '<td><strong> Place </strong></td>';
					$html .= '<td>'.$people->deathplace.'</td>';
				$html .= '</tr>';

				$html .= '<tr>';
					$html .= '<td><strong> Buried </strong></td>';
					$html .= '<td>'.$people->burialdate.'</td>';
					$html .= '<td><strong> Place </strong></td>';
					$html .= '<td>'.$people->burialplace.'</td>';
				$html .= '</tr>';

				$html .= '<tr>';
					$html .= '<td><strong> Baptized (LDS) </strong></td>';
					$html .= '<td>'.$people->baptdate.'</td>';
					$html .= '<td> <strong> Place </strong> </td>';
					$html .= '<td>'.$people->baptplace.'</td>';
				$html .= '</tr>';

				$html .= '<tr>';
					$html .= '<td><strong> Confirmed (LDS) </strong></td>';
					$html .= '<td>'.$people->confdate.'</td>';
					$html .= '<td><strong> Place </strong></td>';
					$html .= '<td>'.$people->confplace.'</td>';
				$html .= '</tr>';

				$html .= '<tr>';
					$html .= '<td><strong> Initiatory (LDS) </strong></td>';
					$html .= '<td>'.$people->initdate.'</td>';
					$html .= '<td><strong> Place </strong></td>';
					$html .= '<td>'.$people->initplace.'</td>';
				$html .= '</tr>';

				$html .= '<tr>';
					$html .= '<td><strong> Endowed (LDS) </strong></td>';
					$html .= '<td>'.$people->endldate.'</td>';
					$html .= '<td><strong> Place </strong></td>';
					$html .= '<td>'.$people->endlplace.'</td>';
				$html .= '</tr>';

				if($people->parents) {
					foreach ($people->parents as $key => $parent) {
					$html .= '<tr>';
						$html .= '<td><strong> Father: </strong></td>';
						$html .= '<td colspan="3">'.$parent->family->husbObj->name.'</td>';
					$html .= '</tr>';
					$html .= '<tr>';
						$html .= '<td><strong> Mother: </strong></td>';
						$html .= '<td colspan="3">'.$parent->family->wifeObj->name.'</td>';
					$html .= '</tr>';
					$html .= '<tr>';
						$html .= '<td><strong> Sealed P (LDS): </strong></td>';
						$html .= '<td>'.$parent->sealdate.'</td>';
						$html .= '<td><strong> Place </strong></td>';
						$html .= '<td>'.$parent->sealplace.'</td>';
					$html .= '</tr>';

					}
				}

				if($people->families) {
					foreach ($people->families as $key => $family) {
						if($people->id != $family->husbObj->id) {
							$html .= '<tr>';
								$html .= '<td><strong> Spouse: </strong></td>';
								$html .= '<td colspan="3">'.$family->husbObj->name.'</td>';
							$html .= '</tr>';
						}
						if($people->id != $family->wifeObj->id) {
							$html .= '<tr>';
								$html .= '<td><strong> Spouse: </strong></td>';
								$html .= '<td colspan="3">'.$family->wifeObj->name.'</td>';
							$html .= '</tr>';
						}
						$html .= '<tr>';
							$html .= '<td><strong> Married: </strong></td>';
							$html .= '<td>'.$family->marrdate.'</td>';
							$html .= '<td><strong> Place </strong></td>';
							$html .= '<td>'.$family->marrplace.'</td>';
						$html .= '</tr>';

						$html .= '<tr>';
							$html .= '<td><strong> Sealed S (LDS): </strong></td>';
							$html .= '<td>'.$family->sealdate.'</td>';
							$html .= '<td><strong> Place </strong></td>';
							$html .= '<td>'.$family->sealplace.'</td>';
						$html .= '</tr>';

						if($family->childrens) {
							foreach ($family->childrens as $key => $children) {
								$html .= '<tr>';
									$html .= '<td><strong> Children : '.($key+1).' </strong></td>';
									$html .= '<td colspan="3"> '.$children->person->name.'</td>';
								$html .= '</tr>';
							}
						}
					}
				}

				$html .= '<tr>';
					$html .= '<td colspan="4"><strong> General </strong></td>';
				$html .= '</tr>';
				$html .= '<tr>';
				foreach ($people->note_links as $key => $note_link) {
					$html .= '<td colspan="4">'.nl2br($note_link->note->note).'</td>';
				}
				$html .= '</tr>';

				$html .= '<tr>';
					$html .= '<td colspan="4"><strong> Sources </strong></td>';
				$html .= '</tr>';

				$html .= '<tr>';
					$html .= '<td colspan="4">';
					foreach ($people->citations as $key => $citation) {
						if($citation->source_obj){
							$html .= ($key+1).'. ['.$citation->sourceID.']';
							if($citation->source_obj->shorttitle ){
								$html .= $citation->source_obj->shorttitle;
							} else {
								$html .= $citation->source_obj->title;
							}
							if($citation->source_obj->author ){
								$html .= ', '. $citation->source_obj->author;
							}
							if($citation->source_obj->publisher ){
								$html .= ', ('. $citation->source_obj->publisher.')';
							}
							if($citation->source_obj->callnum ){
								$html .= ', '. $citation->source_obj->callnum;
							}
							$html .= '<small>';
								if($citation->page){
									$html .= ', '.$citation->page;
								}
								if($citation->citedate){
									$html .= ', '.$citation->citedate;
								}
								if($citation->citetext){
									$html .= '<br>'.$citation->citetext;
								}
								if($citation->note){
									$html .= '<br>'.$citation->note;
								}
							$html .= '</small>';
						}
						$html .= '<br>';
					}
					$html .= '</td>';
				$html .= '</tr>';
			$html .= '</table>';
		}

		if (!file_exists(WP_CONTENT_DIR.'/temp')) {
			mkdir(WP_CONTENT_DIR.'/temp', 0777, true);
		}
		
		$pdf->loadHtml($html);

		$pdf->setPaper('A4', 'portrait');

		$pdf->render();

		$font = $pdf->getFontMetrics()->get_font("helvetica", "bold");

		$header = 'Individual Report for '.$people->name.' ('.$people->personID.')';

		$pdf->getCanvas()->page_text(36, 36, $header, $font, 14, array(0,0,0));

		$date = date('l jS \of F Y h:i:s A');

		$pdf->getCanvas()->page_text(36, 792, $date, $font, 12, array(0,0,0));

		$page = " Page {PAGE_NUM}";

		$pdf->getCanvas()->page_text(36, 808, $page, $font, 10, array(0,0,0));

		$output = $pdf->output();

		file_put_contents(WP_CONTENT_DIR.'/temp/'.$header.'.pdf', $output);

		wp_send_json(WP_CONTENT_URL.'/temp/'.$header.'.pdf', 200);

		die();
	}

	/**
	 * function to generate pdf family report.
	 *
	 * @since    3.0.0
	 */
	public function generate_pdf_family_report(){
		$pdf = new  \Dompdf\Dompdf();
		$html = '';

		$query = $this->post_fixer($_POST['query']);

		$page = $query['page'];
		if($query['family']) {

			$family = \WPGenealogy\Models\Family::find($query['family']);
			if($family->husband){
				$family->husbObj = \WPGenealogy\Models\People::where('personID', $family->husband)->where('gedcom', $family->gedcom)->first();
				$parents = \WPGenealogy\Models\Children::where('personID', $family->husbObj->personID)->where('gedcom', $family->husbObj->gedcom)->get();
				if($parents) {
					foreach ($parents as $key => $parent) {
						$parent->family = \WPGenealogy\Models\Family::where('familyID', $parent->familyID)->where('gedcom', $parent->gedcom)->first();
						if($parent->family->husband){
							$parent->family->husbObj = \WPGenealogy\Models\People::where('personID', $parent->family->husband)->where('gedcom', $parent->family->gedcom)->first();
						}
						if($parent->family->wife){
							$parent->family->wifeObj = \WPGenealogy\Models\People::where('personID', $parent->family->wife)->where('gedcom', $parent->family->gedcom)->first();
						}
					}
				}
				$family->husbObj->parents = $parents;
			}
			if($family->wife){
				$family->wifeObj = \WPGenealogy\Models\People::where('personID', $family->wife)->where('gedcom', $family->gedcom)->first();
				$parents = \WPGenealogy\Models\Children::where('personID', $family->wifeObj->personID)->where('gedcom', $family->wifeObj->gedcom)->get();
				if($parents) {
					foreach ($parents as $key => $parent) {
						$parent->family = \WPGenealogy\Models\Family::where('familyID', $parent->familyID)->where('gedcom', $parent->gedcom)->first();
						if($parent->family->husband){
							$parent->family->husbObj = \WPGenealogy\Models\People::where('personID', $parent->family->husband)->where('gedcom', $parent->family->gedcom)->first();
						}
						if($parent->family->wife){
							$parent->family->wifeObj = \WPGenealogy\Models\People::where('personID', $parent->family->wife)->where('gedcom', $parent->family->gedcom)->first();
						}
					}
				}
				$family->wifeObj->parents = $parents;
			}

			$childrens = \WPGenealogy\Models\Children::where('familyID', $family->familyID)->where('gedcom', $family->gedcom)->get();
			
			if($childrens) {
				foreach ($childrens as $key => $children) {
					$children->person = \WPGenealogy\Models\People::where('personID', $children->personID)->where('gedcom', $children->gedcom)->first();
				}
			}

			$family->childrens = $childrens;

			$html .= '
			<style>
				td {
					border: 1px solid #111111;
					padding: 1px;
					font-size: 16px;
				}
				@page { margin: 1in .5in; }
			</style>
			<table style="border-collapse: collapse;" collspan="0" rowspan="0" width="100%">';

			$html .= '<tr>';
				$html .= '<td style="width:120px; background:#d5d5d5;"><strong> Father </strong> </td>';
				$html .= '<td colspan="3" style=" background:#d5d5d5;">';
					$html .= $family->husbObj ? $family->husbObj->name : '';
				$html .= '</td>';
			$html .= '</tr>';

			$html .= '<tr>';
				$html .= '<td><strong>  Born </strong> </td>';
				$html .= '<td> ';
					$html .=  $family->husbObj ? $family->husbObj->birthdate : '';
				$html .= '</td>';
				$html .= '<td colspan="2"> ';
					$html .=  $family->husbObj ? $family->husbObj->birthplace : '';
				$html .= '</td>';
			$html .= '</tr>';

			$html .= '<tr>';
				$html .= '<td><strong>  Christened </strong> </td>';
				$html .= '<td> ';
					$html .=  $family->husbObj ? $family->husbObj->altbirthdate : '';
				$html .= '</td>';
				$html .= '<td colspan="2"> ';
					$html .=  $family->husbObj ? $family->husbObj->altbirthplace : '';
				$html .= '</td>';
			$html .= '</tr>';

			$html .= '<tr>';
				$html .= '<td><strong>  Died </strong> </td>';
				$html .= '<td> ';
					$html .=  $family->husbObj ? $family->husbObj->deathdate : '';
				$html .= '</td>';
				$html .= '<td colspan="2"> ';
					$html .=  $family->husbObj ? $family->husbObj->deathplace : '';
				$html .= '</td>';
			$html .= '</tr>';

			$html .= '<tr>';
				$html .= '<td><strong>  Buried </strong> </td>';
				$html .= '<td> ';
					$html .=  $family->husbObj ? $family->husbObj->burialdate : '';
				$html .= '</td>';
				$html .= '<td colspan="2"> ';
					$html .=  $family->husbObj ? $family->husbObj->burialplace : '';
				$html .= '</td>';
			$html .= '</tr>';

			$html .= '<tr>';
				$html .= '<td><strong>  Baptized (LDS) </strong> </td>';
				$html .= '<td> ';
					$html .=  $family->husbObj ? $family->husbObj->baptdate : '';
				$html .= '</td>';
				$html .= '<td colspan="2"> ';
					$html .=  $family->husbObj ? $family->husbObj->baptplace : '';
				$html .= '</td>';
			$html .= '</tr>';

			$html .= '<tr>';
				$html .= '<td><strong>  Confirmed (LDS) </strong> </td>';
				$html .= '<td> ';
					$html .=  $family->husbObj ? $family->husbObj->confdate : '';
				$html .= '</td>';
				$html .= '<td colspan="2"> ';
					$html .=  $family->husbObj ? $family->husbObj->confplace : '';
				$html .= '</td>';
			$html .= '</tr>';

			$html .= '<tr>';
				$html .= '<td><strong>  Initiatory (LDS)</strong>  </td>';
				$html .= '<td> ';
					$html .=  $family->husbObj ? $family->husbObj->initdate : '';
				$html .= '</td>';
				$html .= '<td colspan="2"> ';
					$html .=  $family->husbObj ? $family->husbObj->initplace : '';
				$html .= '</td>';
			$html .= '</tr>';

			$html .= '<tr>';
				$html .= '<td><strong>  Endowed (LDS)</strong>  </td>';
				$html .= '<td> ';
					$html .=  $family->husbObj ? $family->husbObj->endldate : '';
				$html .= '</td>';
				$html .= '<td colspan="2"> ';
					$html .=  $family->husbObj ? $family->husbObj->endlplace : '';
				$html .= '</td>';
			$html .= '</tr>';

			foreach ($family->husbObj->parents as $key => $parent) {
				$html .= '<tr>';
					$html .= '<td><strong>  Father</strong>  </td>';
					$html .= '<td> ';
						$html .= $parent->family->husbObj->name;
					$html .= '</td>';
					$html .= '<td> <strong>Mother </strong></td>';
					$html .= '<td> ';
						$html .= $parent->family->wifeObj->name;
					$html .= '</td>';
				$html .= '</tr>';
				/*
				$html .= '<tr>';
					$html .= '<td><strong>  Sealed P (LDS)</strong>  </td>';
					$html .= '<td> ';
						$html .= '';
					$html .= '</td>';
					$html .= '<td colspan="2"> ';
						$html .= '';
					$html .= '</td>';
				$html .= '</tr>';
				*/
				$html .= '<tr>';
					$html .= '<td><strong>  Married</strong>  </td>';
					$html .= '<td> ';
						$html .= $parent->family->marrdate;
					$html .= '</td>';
					$html .= '<td colspan="2"> ';
						$html .= $parent->family->marrplace;
					$html .= '</td>';
				$html .= '</tr>';
				$html .= '<tr>';
					$html .= '<td><strong>  Sealed S (LDS)</strong>   </td>';
					$html .= '<td>  ';
						$html .= $parent->family->sealdate;
					$html .= ' </td>';
					$html .= '<td colspan="2"> ';
						$html .= $parent->family->sealplace;
					$html .= '</td>';
				$html .= '</tr>';
			}

			$html .= '<tr>';
				$html .= '<td style="width:120px; background:#d5d5d5;"><strong> Mother </strong> </td>';
				$html .= '<td colspan="3" style=" background:#d5d5d5;">';
					$html .= $family->wifeObj ? $family->wifeObj->name : '';
				$html .= '</td>';
			$html .= '</tr>';

			$html .= '<tr>';
				$html .= '<td><strong>  Born </strong> </td>';
				$html .= '<td> ';
					$html .=  $family->wifeObj ? $family->wifeObj->birthdate : '';
				$html .= '</td>';
				$html .= '<td colspan="2"> ';
					$html .=  $family->wifeObj ? $family->wifeObj->birthplace : '';
				$html .= '</td>';
			$html .= '</tr>';

			$html .= '<tr>';
				$html .= '<td><strong>  Christened </strong> </td>';
				$html .= '<td> ';
					$html .=  $family->wifeObj ? $family->wifeObj->altbirthdate : '';
				$html .= '</td>';
				$html .= '<td colspan="2"> ';
					$html .=  $family->wifeObj ? $family->wifeObj->altbirthplace : '';
				$html .= '</td>';
			$html .= '</tr>';

			$html .= '<tr>';
				$html .= '<td><strong>  Died </strong> </td>';
				$html .= '<td> ';
					$html .=  $family->wifeObj ? $family->wifeObj->deathdate : '';
				$html .= '</td>';
				$html .= '<td colspan="2"> ';
					$html .=  $family->wifeObj ? $family->wifeObj->deathplace : '';
				$html .= '</td>';
			$html .= '</tr>';

			$html .= '<tr>';
				$html .= '<td><strong>  Buried </strong> </td>';
				$html .= '<td> ';
					$html .=  $family->wifeObj ? $family->wifeObj->burialdate : '';
				$html .= '</td>';
				$html .= '<td colspan="2"> ';
					$html .=  $family->wifeObj ? $family->wifeObj->burialplace : '';
				$html .= '</td>';
			$html .= '</tr>';

			$html .= '<tr>';
				$html .= '<td><strong>  Baptized (LDS) </strong> </td>';
				$html .= '<td> ';
					$html .=  $family->wifeObj ? $family->wifeObj->baptdate : '';
				$html .= '</td>';
				$html .= '<td colspan="2"> ';
					$html .=  $family->wifeObj ? $family->wifeObj->baptplace : '';
				$html .= '</td>';
			$html .= '</tr>';

			$html .= '<tr>';
				$html .= '<td><strong>  Confirmed (LDS) </strong> </td>';
				$html .= '<td> ';
					$html .=  $family->wifeObj ? $family->wifeObj->confdate : '';
				$html .= '</td>';
				$html .= '<td colspan="2"> ';
					$html .=  $family->wifeObj ? $family->wifeObj->confplace : '';
				$html .= '</td>';
			$html .= '</tr>';

			$html .= '<tr>';
				$html .= '<td><strong>  Initiatory (LDS)</strong>  </td>';
				$html .= '<td> ';
					$html .=  $family->wifeObj ? $family->wifeObj->initdate : '';
				$html .= '</td>';
				$html .= '<td colspan="2"> ';
					$html .=  $family->wifeObj ? $family->wifeObj->initplace : '';
				$html .= '</td>';
			$html .= '</tr>';

			$html .= '<tr>';
				$html .= '<td><strong>  Endowed (LDS)</strong>  </td>';
				$html .= '<td> ';
					$html .=  $family->wifeObj ? $family->wifeObj->endldate : '';
				$html .= '</td>';
				$html .= '<td colspan="2"> ';
					$html .=  $family->wifeObj ? $family->wifeObj->endlplace : '';
				$html .= '</td>';
			$html .= '</tr>';

			foreach ($family->wifeObj->parents as $key => $parent) {
				$html .= '<tr>';
					$html .= '<td><strong>  Father</strong>  </td>';
					$html .= '<td> ';
						$html .= $parent->family->wifeObj->name;
					$html .= '</td>';
					$html .= '<td> <strong>Mother </strong></td>';
					$html .= '<td> ';
						$html .= $parent->family->wifeObj->name;
					$html .= '</td>';
				$html .= '</tr>';
				/*
				$html .= '<tr>';
					$html .= '<td><strong>  Sealed P (LDS)</strong>  </td>';
					$html .= '<td> ';
						$html .= '';
					$html .= '</td>';
					$html .= '<td colspan="2"> ';
						$html .= '';
					$html .= '</td>';
				$html .= '</tr>';
				*/
				$html .= '<tr>';
					$html .= '<td><strong>  Married</strong>  </td>';
					$html .= '<td> ';
						$html .= $parent->family->marrdate;
					$html .= '</td>';
					$html .= '<td colspan="2"> ';
						$html .= $parent->family->marrplace;
					$html .= '</td>';
				$html .= '</tr>';
				$html .= '<tr>';
					$html .= '<td><strong>  Sealed S (LDS)</strong>   </td>';
					$html .= '<td>  ';
						$html .= $parent->family->sealdate;
					$html .= ' </td>';
					$html .= '<td colspan="2"> ';
						$html .= $parent->family->sealplace;
					$html .= '</td>';
				$html .= '</tr>';
			}


			$html .= '<tr>';
				$html .= '<td colspan="4" style=" background:#d5d5d5;">Childrens</td>';
			$html .= '</tr>';

			foreach ($family->childrens as $key => $children) {
				$html .= '<tr>';
					$html .= '<td style="background:#d5d5d5;">'; 
						$html .= '<strong> '; 
							$html .= ($key+1);
						$html .= '</strong>';
					$html .= '</td>';
					$html .= '<td>  ';
						$html .= $children->person->sex;
					$html .= ' </td>';
					$html .= '<td colspan="2"> ';
						$html .= $children->person->name;
					$html .= '</td>';
				$html .= '</tr>';
				$html .= '<tr>';
					$html .= '<td><strong>Born </strong> </td>';
					$html .= '<td> ';
						$html .=  $children->person ? $children->person->birthdate : '';
					$html .= '</td>';
					$html .= '<td colspan="2"> ';
						$html .=  $children->person ? $children->person->birthplace : '';
					$html .= '</td>';
				$html .= '</tr>';

				$html .= '<tr>';
					$html .= '<td><strong>Christened </strong> </td>';
					$html .= '<td> ';
						$html .=  $children->person ? $children->person->altbirthdate : '';
					$html .= '</td>';
					$html .= '<td colspan="2"> ';
						$html .=  $children->person ? $children->person->altbirthplace : '';
					$html .= '</td>';
				$html .= '</tr>';

				$html .= '<tr>';
					$html .= '<td><strong>Died </strong> </td>';
					$html .= '<td> ';
						$html .=  $children->person ? $children->person->deathdate : '';
					$html .= '</td>';
					$html .= '<td colspan="2"> ';
						$html .=  $children->person ? $children->person->deathplace : '';
					$html .= '</td>';
				$html .= '</tr>';

				$html .= '<tr>';
					$html .= '<td><strong>Buried </strong> </td>';
					$html .= '<td> ';
						$html .=  $children->person ? $children->person->burialdate : '';
					$html .= '</td>';
					$html .= '<td colspan="2"> ';
						$html .=  $children->person ? $children->person->burialplace : '';
					$html .= '</td>';
				$html .= '</tr>';

				$html .= '<tr>';
					$html .= '<td><strong>Baptized (LDS) </strong> </td>';
					$html .= '<td> ';
						$html .=  $children->person ? $children->person->baptdate : '';
					$html .= '</td>';
					$html .= '<td colspan="2"> ';
						$html .=  $children->person ? $children->person->baptplace : '';
					$html .= '</td>';
				$html .= '</tr>';

				$html .= '<tr>';
					$html .= '<td><strong>Confirmed (LDS) </strong> </td>';
					$html .= '<td> ';
						$html .=  $children->person ? $children->person->confdate : '';
					$html .= '</td>';
					$html .= '<td colspan="2"> ';
						$html .=  $children->person ? $children->person->confplace : '';
					$html .= '</td>';
				$html .= '</tr>';

				$html .= '<tr>';
					$html .= '<td><strong>  Initiatory (LDS)</strong>  </td>';
					$html .= '<td> ';
						$html .=  $children->person ? $children->person->initdate : '';
					$html .= '</td>';
					$html .= '<td colspan="2"> ';
						$html .=  $children->person ? $children->person->initplace : '';
					$html .= '</td>';
				$html .= '</tr>';

				$html .= '<tr>';
					$html .= '<td><strong>  Endowed (LDS)</strong>  </td>';
					$html .= '<td> ';
						$html .=  $children->person ? $children->person->endldate : '';
					$html .= '</td>';
					$html .= '<td colspan="2"> ';
						$html .=  $children->person ? $children->person->endlplace : '';
					$html .= '</td>';
				$html .= '</tr>';
			}

			$html .= '<tr>';
				$html .= '<td colspan="4" style=" background:#d5d5d5;"><strong> Sources </strong></td>';
			$html .= '</tr>';

			$html .= '<tr>';
				$html .= '<td colspan="4">';
				foreach ($family->citations as $key => $citation) {
					$html .= ($key+1).'. ['.$citation->sourceID.']';
					if($citation->source_obj){
						if($citation->source_obj->shorttitle ){
							$html .= $citation->source_obj->shorttitle;
						} else {
							$html .= $citation->source_obj->title;
						}
						if($citation->source_obj->author ){
							$html .= ', '. $citation->source_obj->author;
						}
						if($citation->source_obj->publisher ){
							$html .= ', ('. $citation->source_obj->publisher.')';
						}
						if($citation->source_obj->callnum ){
							$html .= ', '. $citation->source_obj->callnum;
						}
						$html .= '<small>';
							if($citation->page){
								$html .= ', '.$citation->page;
							}
							if($citation->citedate){
								$html .= ', '.$citation->citedate;
							}
							if($citation->citetext){
								$html .= '<br>'.$citation->citetext;
							}
							if($citation->note){
								$html .= '<br>'.$citation->note;
							}
						$html .= '</small>';
					}
					$html .= '<br>';
				}
				$html .= '</td>';
			$html .= '</tr>';

			$html .= '</table>';
		}
		$pdf->loadHtml($html);
		$pdf->setPaper('A4', 'portrait');
		$pdf->render();
		$font = $pdf->getFontMetrics()->get_font("helvetica", "bold");
		$name = $family->husbObj ? $family->husbObj->name : '';
		$name .= ($family->husbObj && $family->wifeObj) ? ' and ' : '';
		$name .= $family->wifeObj ? $family->wifeObj->name : '';
		$name .= ' ('.$family->familyID.')';
		$header = 'Family Report for '. $name ;
		$pdf->getCanvas()->page_text(36, 36, $header, $font, 14, array(0,0,0));
		$date = date('l jS \of F Y h:i:s A');
		$pdf->getCanvas()->page_text(36, 792, $date, $font, 10, array(0,0,0));
		$page = " Page {PAGE_NUM}";
		$pdf->getCanvas()->page_text(36, 808, $page, $font, 10, array(0,0,0));
		$output = $pdf->output();
		file_put_contents(WP_CONTENT_DIR.'/temp/'.$header.'.pdf', $output);
		wp_send_json(WP_CONTENT_URL.'/temp/'.$header.'.pdf', 200);
		die();
	}

	public function generate_pdf_pedigree(){
		$pdf = new  \Dompdf\Dompdf();
		$html = '';
		$html .= '
		<style>
		.pedigree {
			white-space: nowrap;
			background: #f5f5f5;
			padding: 15px;
			border: 1px solid #e1e1e1;
		}
		.pedigree ul {
			list-style: none;
			display: inline-block;
			margin: 0;
			padding: 0;
			vertical-align: middle;
			position: relative;
			width: -webkit-max-content;
			width: -moz-max-content;
			width: max-content;
		}
		.pedigree li {
			position: relative;
			padding-left: 10px;
			vertical-align: middle;
		}
		.pedigree li > div {
			display: inline-block;
			width: 160px;
			vertical-align: middle;
			border: 1px solid;
			padding: 10px;
			white-space: normal;
			margin: 2px 0;
		}
		</style>
		<div class="pedigree standard">
			<div class="vue-pan-zoom-item vue-pan-zoom-item-mY51fK0gEj0aOyOYyogJ">
				<div class="vue-pan-zoom-scene">
					<div tabindex="0">
						<ul id="g3" style="transform-origin: 0px 0px 0px; transform: matrix(1, 0, 0, 1, 137.505, -50.0237);">
							<li>
								<div class="persion-ind"><a href="#/people/44/" class="tp-name active">Robert Frederick Gray </a> <span class="tp-sex">♂</span>
									<div class="date"> ( b.18 NOV 1907 , d.14 MAY 1990 ) </div>
								</div>
								<ul>
									<li>
										<div class="persion-ind">
											<div>
												<div><a href="#/people/38/" class="tp-name">Frederick Herman Gray</a> <span class="tp-sex">♂</span>
													<div class="date"> ( d.26 JUN 1881 , d.27 OCT 1928 ) </div>
												</div>
											</div>
										</div>
										<ul>
											<li>
												<div class="persion-ind">
													<div>
														<div><a href="#/people/40/" class="tp-name">John William Gray</a> <span class="tp-sex">♂</span>
															<div class="date"> ( d.13 SEP 1857 , d.10 JAN 1928 ) </div>
														</div>
													</div>
												</div>
												
											</li>
											<li>
												<div class="persion-ind">
													<div>
														<div><a href="#/people/52/" class="tp-name">Elizabeth Hurn</a>
															 <span class="tp-sex">♀</span>
															<div class="date"> ( d.26 OCT 1859 , d.18 JUL 1946 ) </div>
														</div>
													</div>
												</div>
												
											</li>
										</ul>
									</li>
									<li>
										<div class="persion-ind">
											<div>
												<div><a href="#/people/27/" class="tp-name">Ruth Maude Elliott</a>
													 <span class="tp-sex">♀</span>
													<div class="date"> ( d.23 JAN 1881 , d.29 SEP 1941 ) </div>
												</div>
											</div>
										</div>
										<ul>
											<li>
												<div class="persion-ind">
													<div>
														<div>
															 <a href="#/dashboard/family/add" class="tp-name">Add New Family</a>
														</div>
													</div>
												</div>
											</li>
											<li>
												<div class="persion-ind">
													<div>
														<div>
															 <a href="#/dashboard/family/add" class="tp-name">Add New Family</a>
														</div>
													</div>
												</div>
											</li>
										</ul>
									</li>
								</ul>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>';
		$pdf->loadHtml($html);
		$pdf->setPaper('A4', 'portrait');
		$pdf->render();
		$font = $pdf->getFontMetrics()->get_font("helvetica", "bold");
		$header = 'Family Report for test';
		$pdf->getCanvas()->page_text(36, 36, $header, $font, 14, array(0,0,0));
		$date = date('l jS \of F Y h:i:s A');
		$pdf->getCanvas()->page_text(36, 792, $date, $font, 10, array(0,0,0));
		$page = " Page {PAGE_NUM}";
		$pdf->getCanvas()->page_text(36, 808, $page, $font, 10, array(0,0,0));
		$output = $pdf->output();
		file_put_contents(WP_CONTENT_DIR.'/temp/'.$header.'.pdf', $output);
		wp_send_json(WP_CONTENT_URL.'/temp/'.$header.'.pdf', 200);
		die();
	}

	public function generate_pdf_descend(){
		//print_r($_POST);
	}
}