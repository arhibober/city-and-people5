<?php 
namespace WPGenealogy_Public\Traits;

trait Export {
	public function export_gedcom(){

		$data = $this->post_fixer($_POST);


		$gedcom = $data['gedcom'];



		$text = '';
		$text .= '0 HEAD'. "\n";
		$text .= '1 SOUR WP Genealogy'. "\n";
		$text .= '2 NAME WP Genealogy - Family Trees on WordPress'. "\n";
		$text .= '2 VERS 1.0.0'. "\n";
		$text .= '2 CORP Black and White Digital Ltd'. "\n";
		$text .= '3 ADDR Unit F, 44-48 Shepherdess Walk'. "\n";
		$text .= '4 CONT London, N1 7JP'. "\n";
		$text .= '4 CONT UK'. "\n";
		$text .= '1 DATE 18 JAN 2019'. "\n";
		$text .= '2 TIME 20:59:40'. "\n";
		$text .= '1 FILE test.ged'. "\n";
		$text .= '1 SUBM @SUBM@'. "\n";
		$text .= '1 GEDC'. "\n";
		$text .= '2 VERS 5.5'. "\n";
		$text .= '2 FORM LINEAGE-LINKED'. "\n";
		$text .= '1 CHAR UTF-8'. "\n";

		$peoples = \WPGenealogy\Models\People::where('gedcom', $gedcom)->get();
		foreach ($peoples as $key => $people) {
			$text .= '0 @I' . $people->personID . '@ INDI' . "\n";
			$text .= '1 NAME /' . $people->name . "/\n";
			$text .= '1 SEX ' . $people->sex . "\n";
			//
			if ($people->birthdate || $people->birthplace) {
				$text .= '1 BIRT' . "\n";
			}
			if ($people->birthdate) {
				$text .= '2 DATE ' . $people->birthdate . "\n";
			}
			if ($people->birthplace) {
				$text .= '2 PLAC ' . $people->birthplace . "\n";
			}
			if ($people->birthdate || $people->birthplace) {
				$citations = \WPGenealogy\Models\Citation::where('gedcom', $gedcom)->where('persfamID', $people->personID)->where('eventID', 'BIRT')->get();
				foreach ($citations as $key => $citation) {
					$text .= '2 SOUR @' . $citation->source_obj->sourceID .'@'. "\n";
					if($citation->page) {
						$text .= '3 PAGE ' . $citation->page . "\n";
					}
					if($citation->citetext) {
						$text .= '3 DATA ' . "\n";
						$text .= '4 TEXT ' . $citation->citetext . "\n";
					}
				}
			}
			//
			if ($people->deathdate || $people->deathplace) {
				$text .= '1 DEAT' . "\n";
			}
			if ($people->deathdate) {
				$text .= '2 DATE ' . $people->deathdate . "\n";
			}
			if ($people->deathplace) {
				$text .= '2 PLAC ' . $people->deathplace . "\n";
			}
			if ($people->deathdate || $people->deathplace) {
				$citations = \WPGenealogy\Models\Citation::where('gedcom', $gedcom)->where('persfamID', $people->personID)->where('eventID', 'DEAT')->get();
				foreach ($citations as $key => $citation) {
					$text .= '2 SOUR @' . $citation->source_obj->sourceID .'@'. "\n";
					if($citation->page) {
						$text .= '3 PAGE ' . $citation->page . "\n";
					}
					if($citation->citetext) {
						$text .= '3 DATA ' . "\n";
						$text .= '4 TEXT ' . $citation->citetext . "\n";
					}
				}
			}
			//
			if ($people->altbirthdate || $people->altbirthplace) {
				$text .= '1 CHR' . "\n";
			}
			if ($people->altbirthdate) {
				$text .= '2 DATE ' . $people->altbirthdate . "\n";
			}
			if ($people->altbirthplace) {
				$text .= '2 PLAC ' . $people->altbirthplace . "\n";
			}
			if ($people->altbirthdate || $people->altbirthplace) {
				$citations = \WPGenealogy\Models\Citation::where('gedcom', $gedcom)->where('persfamID', $people->personID)->where('eventID', 'CHR')->get();
				foreach ($citations as $key => $citation) {
					$text .= '2 SOUR @' . $citation->source_obj->sourceID .'@'. "\n";
					if($citation->page) {
						$text .= '3 PAGE ' . $citation->page . "\n";
					}
					if($citation->citetext) {
						$text .= '3 DATA ' . "\n";
						$text .= '4 TEXT ' . $citation->citetext . "\n";
					}
				}
			}
			//
			if ($people->burialdate || $people->burialplace) {
				$text .= '1 BURI' . "\n";
			}
			if ($people->burialdate) {
				$text .= '2 DATE ' . $people->burialdate . "\n";
			}
			if ($people->burialplace) {
				$text .= '2 PLAC ' . $people->burialplace . "\n";
			}
			if ($people->burialdate || $people->burialplace) {
				$citations = \WPGenealogy\Models\Citation::where('gedcom', $gedcom)->where('persfamID', $people->personID)->where('eventID', 'BURI')->get();
				foreach ($citations as $key => $citation) {
					$text .= '2 SOUR @' . $citation->source_obj->sourceID .'@'. "\n";
					if($citation->page) {
						$text .= '3 PAGE ' . $citation->page . "\n";
					}
					if($citation->citetext) {
						$text .= '3 DATA ' . "\n";
						$text .= '4 TEXT ' . $citation->citetext . "\n";
					}
				}
			}
			//
			if ($people->baptdate || $people->baptplace) {
				$text .= '1 BAPL' . "\n";
			}
			if ($people->baptdate) {
				$text .= '2 DATE ' . $people->baptdate . "\n";
			}
			if ($people->baptplace) {
				$text .= '2 PLAC ' . $people->baptplace . "\n";
			}
			if ($people->baptdate || $people->baptplace) {
				$citations = \WPGenealogy\Models\Citation::where('gedcom', $gedcom)->where('persfamID', $people->personID)->where('eventID', 'BAPL')->get();
				foreach ($citations as $key => $citation) {
					$text .= '2 SOUR @' . $citation->source_obj->sourceID .'@'. "\n";
					if($citation->page) {
						$text .= '3 PAGE ' . $citation->page . "\n";
					}
					if($citation->citetext) {
						$text .= '3 DATA ' . "\n";
						$text .= '4 TEXT ' . $citation->citetext . "\n";
					}
				}
			}
			//
			if ($people->confdate || $people->confplace) {
				$text .= '1 CONL' . "\n";
			}
			if ($people->confdate) {
				$text .= '2 DATE ' . $people->confdate . "\n";
			}
			if ($people->confplace) {
				$text .= '2 PLAC ' . $people->confplace . "\n";
			}
			if ($people->confdate || $people->confplace) {
				$citations = \WPGenealogy\Models\Citation::where('gedcom', $gedcom)->where('persfamID', $people->personID)->where('eventID', 'CONL')->get();
				foreach ($citations as $key => $citation) {
					$text .= '2 SOUR @' . $citation->source_obj->sourceID .'@'. "\n";
					if($citation->page) {
						$text .= '3 PAGE ' . $citation->page . "\n";
					}
					if($citation->citetext) {
						$text .= '3 DATA ' . "\n";
						$text .= '4 TEXT ' . $citation->citetext . "\n";
					}
				}
			}
			//
			if ($people->initdate || $people->initplace) {
				$text .= '1 INIT' . "\n";
			}
			if ($people->initdate) {
				$text .= '2 DATE ' . $people->initdate . "\n";
			}
			if ($people->initplace) {
				$text .= '2 PLAC ' . $people->initplace . "\n";
			}
			if ($people->initdate || $people->initplace) {
				$citations = \WPGenealogy\Models\Citation::where('gedcom', $gedcom)->where('persfamID', $people->personID)->where('eventID', 'INIT')->get();
				foreach ($citations as $key => $citation) {
					$text .= '2 SOUR @' . $citation->source_obj->sourceID .'@'. "\n";
					if($citation->page) {
						$text .= '3 PAGE ' . $citation->page . "\n";
					}
					if($citation->citetext) {
						$text .= '3 DATA ' . "\n";
						$text .= '4 TEXT ' . $citation->citetext . "\n";
					}
				}
			}
			//
			if ($people->endldate || $people->endlplace) {
				$text .= '1 ENDL' . "\n";
			}
			if ($people->endldate) {
				$text .= '2 DATE ' . $people->endldate . "\n";
			}
			if ($people->endlplace) {
				$text .= '2 PLAC ' . $people->endlplace . "\n";
			}
			if ($people->endldate || $people->endlplace) {
				$citations = \WPGenealogy\Models\Citation::where('gedcom', $gedcom)->where('persfamID', $people->personID)->where('eventID', 'ENDL')->get();
				foreach ($citations as $key => $citation) {
					$text .= '2 SOUR @' . $citation->source_obj->sourceID .'@'. "\n";
					if($citation->page) {
						$text .= '3 PAGE ' . $citation->page . "\n";
					}
					if($citation->citetext) {
						$text .= '3 DATA ' . "\n";
						$text .= '4 TEXT ' . $citation->citetext . "\n";
					}
				}
			}

			$note_links = \WPGenealogy\Models\NoteLink::where('gedcom', $gedcom)->where('persfamID', $people->personID)->where('eventID', 'GENERAL')->get();
			foreach ($note_links as $key => $note_link) {
				$text .= '1 NOTE @' . $note_link->noteID .'@'. "\n";
			}
			$famcs = \WPGenealogy\Models\Children::where('gedcom', $gedcom)->where('personID', $people->personID)->get();
			foreach ($famcs as $key => $famc) {
				$text .= '1 FAMC @' . $famc->familyID .'@'. "\n";
			}
		}

		$families = \WPGenealogy\Models\Family::where('gedcom', $gedcom)->get();

		foreach ($families as $key => $family) {
			$text .= '0 @'.$family->familyID.'@ FAM' . "\n";
			$text .= '1 HUSB @'.$family->husband.'@' . "\n";
			$text .= '1 WIFE @'.$family->wife.'@' . "\n";
			$childrens = \WPGenealogy\Models\Children::where('gedcom', $gedcom)->where('familyID', $family->familyID)->get();
			foreach($childrens  as $key => $children) {
				$text .= '1 CHIL @'.$children->personID.'@' . "\n";
				$text .= '2 _FREL '.$children->frel . "\n";
				$text .= '2 _MREL '.$children->mrel . "\n";
			}

			//
			if ($family->marrdate || $family->marrplace) {
				$text .= '1 MARR' . "\n";
			}
			if ($family->marrdate) {
				$text .= '2 DATE ' . $family->marrdate . "\n";
			}
			if ($family->marrplace) {
				$text .= '2 PLAC ' . $family->marrplace . "\n";
			}
			if ($family->marrdate || $family->marrplace) {
				$citations = \WPGenealogy\Models\Citation::where('gedcom', $gedcom)->where('persfamID', $family->personID)->where('eventID', 'MARR')->get();
				foreach ($citations as $key => $citation) {
					$text .= '2 SOUR @' . $citation->source_obj->sourceID .'@'. "\n";
					if($citation->page) {
						$text .= '3 PAGE ' . $citation->page . "\n";
					}
					if($citation->citetext) {
						$text .= '3 DATA ' . "\n";
						$text .= '4 TEXT ' . $citation->citetext . "\n";
					}
				}
			}

			//
			if ($family->divdate || $family->divplace) {
				$text .= '1 DIV' . "\n";
			}
			if ($family->divdate) {
				$text .= '2 DATE ' . $family->divdate . "\n";
			}
			if ($family->divplace) {
				$text .= '2 PLAC ' . $family->divplace . "\n";
			}
			if ($family->divdate || $family->divplace) {
				$citations = \WPGenealogy\Models\Citation::where('gedcom', $gedcom)->where('persfamID', $family->personID)->where('eventID', 'DIV')->get();
				foreach ($citations as $key => $citation) {
					$text .= '2 SOUR @' . $citation->source_obj->sourceID .'@'. "\n";
					if($citation->page) {
						$text .= '3 PAGE ' . $citation->page . "\n";
					}
					if($citation->citetext) {
						$text .= '3 DATA ' . "\n";
						$text .= '4 TEXT ' . $citation->citetext . "\n";
					}
				}
			}

			//
			if ($family->sealdate || $family->sealplace) {
				$text .= '1 SLGS' . "\n";
			}
			if ($family->sealdate) {
				$text .= '2 DATE ' . $family->sealdate . "\n";
			}
			if ($family->sealplace) {
				$text .= '2 PLAC ' . $family->sealplace . "\n";
			}
			if ($family->sealdate || $family->sealplace) {
				$citations = \WPGenealogy\Models\Citation::where('gedcom', $gedcom)->where('persfamID', $family->personID)->where('eventID', 'SLGS')->get();
				foreach ($citations as $key => $citation) {
					$text .= '2 SOUR @' . $citation->source_obj->sourceID .'@'. "\n";
					if($citation->page) {
						$text .= '3 PAGE ' . $citation->page . "\n";
					}
					if($citation->citetext) {
						$text .= '3 DATA ' . "\n";
						$text .= '4 TEXT ' . $citation->citetext . "\n";
					}
				}
			}

			$note_links = \WPGenealogy\Models\NoteLink::where('gedcom', $gedcom)->where('persfamID', $family->personID)->where('eventID', 'GENERAL')->get();
			foreach ($note_links as $key => $note_link) {
				$text .= '1 NOTE @' . $note_link->noteID .'@'. "\n";
			}
		}

		$notes = \WPGenealogy\Models\Note::where('gedcom', $gedcom)->get();
		foreach ($notes as $key => $note) {
			$text .= '0 @' . $note->noteID .'@ NOTE'. "\n";
			foreach(preg_split("/((\r?\n)|(\r\n?))/", $note->note) as $line){
				$text .= '1 CONC '. $line . "\n";
			}
		}

		$sources = \WPGenealogy\Models\Source::where('gedcom', $gedcom)->get();
		foreach ($sources as $key => $source) {
			$text .= '0 @' . $source->sourceID .'@ SOUR'. "\n";
			$text .= '1 TITL '. $source->title . "\n";
		}

		$text .= '0 TRLR';


		
		if (!file_exists(WP_CONTENT_DIR . '/wpgenealogy/temp')) {
			mkdir(WP_CONTENT_DIR . '/wpgenealogy/temp', 0777, true);
		}

		$myfile = fopen(WP_CONTENT_DIR . '/wpgenealogy/temp/' . $gedcom . '.ged', 'w') or die('Unable to open file!');

		fwrite($myfile, $text);

		fclose($myfile);

		$zip = new \ZipArchive();

		if ($zip->open(WP_CONTENT_DIR . '/wpgenealogy/temp/' . $gedcom . '.zip', \ZipArchive::CREATE) != TRUE) {
			die("Could not open archive");
		}

		$zip->addFile(WP_CONTENT_DIR . '/wpgenealogy/temp/' . $gedcom . '.ged', $gedcom . '.ged');
		
		$zip->close();

		$file_path = WP_CONTENT_DIR . '/wpgenealogy/temp/' . $gedcom . '.zip';

		$file_url = WP_CONTENT_URL . '/wpgenealogy/temp/' . $gedcom . '.zip';

		wp_send_json($file_url, 200);
	}

}