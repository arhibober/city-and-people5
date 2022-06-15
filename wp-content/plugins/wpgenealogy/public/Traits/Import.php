<?php 
namespace WPGenealogy_Public\Traits;

trait Import {

	/**
	 * wpgenealogy function to import ged file.
	 *
	 * @since    3.0.0
	 */
	public function import_ged() {

		$data = $this->post_fixer($_POST);
		if ( isset( $_FILES['file'] ) ) {
			$file = $_FILES['file']['tmp_name'];
			$file_name = sanitize_file_name($_FILES['file']['name']);
			$extension = pathinfo($file_name, PATHINFO_EXTENSION);
			if (!in_array($extension, array('ged'))) {
				return wp_send_json(esc_html( __( 'Only .ged format file is allowed here.', 'wpgenealogy' ) ) , 200);
				die;
			}
		} else {
			return wp_send_json(esc_html( __( 'File required', 'wpgenealogy' ) ) , 200);
			die;
		}

		$gedcom = isset($data['gedcom']) ? $data['gedcom'] : '' ;
		$branch = isset($data['branch']) ? $data['branch'] : '' ;

		$tree = \WPGenealogy\Models\Tree::where('gedcom', $gedcom)->first();
		$importfilename = $tree->importfilename ? $tree->importfilename .',' : '';
		$importfilename = $importfilename . $file_name;
		$tree->importfilename = $importfilename . $file_name;
		$tree->lastimportdate = date('Y-m-d h:i:sa');
		$tree->save();

		/**
		 * wpgenealogy function to import ged file.
		 *
		 * @since    3.0.0
		 */
		$this->process_import($file, $gedcom, $branch);

		return wp_send_json('ok', 200);
	}

	/**
	 * wpgenealogy import process.
	 *
	 * @since    3.0.0
	 */
	public function process_import($file, $gedcom, $branch){

		$created_by = wp_get_current_user()->ID;
		
		$parser = new \PhpGedcom\Parser();
		
		$data = $parser->parse($file);
		
		/**
		 * Check if any repository exist.
		 * 
		 */
		if($data->getRepo() && is_array($data->getRepo())) {
			foreach ($data->getRepo() as $key => $repo) {

				$addressID = '';

				/**
				 * Check if any address exist.
				 * 
				 */
				if($repo->getAddr()) {
					$addr = $repo->getAddr();

					/**
					 * Create address and get id.
					 * 
					 */
					$address = new \WPGenealogy\Models\Address;
					$address = $address->create(array(
						'created_by' => $created_by,
						//'addressID' => ,
						'address1' => $addr->getAdr1(),
						'address2' => $addr->getAdr2(),
						'city' => $addr->getCity(),
						'state' => $addr->getStae(),
						'zip' => $addr->getPost(),
						//'country' => ,
						//'www' => ,
						//'email' => ,
						//'phone' => ,
						'gedcom' => $gedcom,
					));

					$addressID = $address->id;
				}

				/**
				 * Create repository.
				 * 
				 */
				$repository = new \WPGenealogy\Models\Repository;
				$repository = $repository->create(array(
					'created_by' => $created_by,

					'repoID' => $repo->getRepo(),
					'reponame' => $repo->getName(),
					'gedcom' => $gedcom,
					'addressID' => $addressID,
					//'changedate' => ,
					//'changedby' => ,
				));
			}
		}

		/**
		 * Check if any note exist.
		 * 
		 */
		if($data->getNote() && is_array($data->getNote())) {
			foreach($data->getNote() as $key => $note) {

				$noteCreate = new \WPGenealogy\Models\Note;
				$noteCreate = $noteCreate->create(array(
					'created_by' => $created_by,
					'noteID' => $note->getId(),
					'gedcom' => $gedcom,
					'note' => $note->getNote(),
				));
			}
		}

		/**
		 * Check if any repository exist.
		 * 
		 */
		if($data->getSour()) {
			foreach ($data->getSour() as $key => $sour) {

				$repoID =  $callnum = '';

				/**
				 * Check if repoID and callnum exist.
				 * 
				 */
				if($sour->getRepo()) {
					if($sour->getRepo()->getRepo()){
						$repoID = $sour->getRepo()->getRepo();
					}
					if($sour->getRepo()->getCaln()){
						$caln = current($sour->getRepo()->getCaln());
						$callnum = $caln->getCaln();
					}
				}

				/**
				 * Create source.
				 * 
				 */
				$source = new \WPGenealogy\Models\Source;
				$source = $source->create(array(
					'created_by' => $created_by,
					'gedcom' => $gedcom, 
					'sourceID' => str_replace('@', '', $sour->getSour()), 
					'callnum' => $callnum, 
					//'type' => , 
					'title' => $sour->getTitl(), 
					'author' => $sour->getAuth(), 
					'publisher' => $sour->getPubl(), 
					//'other' => , 
					//'shorttitle' => , 
					//'comments' => , 
					'actualtext' => $sour->getText(), 
					'repoID' => $repoID, 
					//'changedate' => , 
					//'changedby' => , 
				));

			}
		}


		/**
		 * Check if any individual exist.
		 * 
		 */
		if($data->getIndi()) {
			foreach ($data->getIndi() as $key => $indi) {

				$persfamID = $indi->getId();

				$lastname = $firstname = $nickname = $title = $prefix = $suffix = $lnprefix =  '';
				
				/**
				 * Check if name Object.
				 * 
				 */
				if($indi->getName()){
					$name = current($indi->getName());
					$nameAlt = $name->getName();
					$nameArray = explode(' /', $nameAlt);
					$fname = $nameArray ? $nameArray[0] : ''; 
					$lname = '';
					if($nameArray) {
						$nameArrayKey = 1;
						foreach ($nameArray  as $key => $value) {
							if(isset($nameArray[$nameArrayKey])){
								$lname .= ' '.$nameArray[$nameArrayKey] ? $nameArray[$nameArrayKey] : ''; 
								$nameArrayKey++;
							}
						}
					}
					$lastname = $name->getSurn() ? $name->getSurn() : str_replace(array('\\', '/'), array('', ''), $lname);
					$firstname = $name->getGivn() ? $name->getGivn() : str_replace(array('\\', '/'), array('', ''), $fname);

					$nickname = $name->getNick();
					// $title = $name->get();
					$prefix = $name->getNpfx();
					$suffix = $name->getNsfx();
					$lnprefix = $name->getSpfx();

					/**
					 * Check if source for name.
					 * 
					 */
					if($name->getSour()) {
						foreach ($name->getSour() as $key => $sour) {
							$citetext = '';
							if($sour->getData()) {
								$citetext = $sour->getData()->getText();
							}
							$citation = new \WPGenealogy\Models\Citation;
							$citation = $citation->create(array(
								'created_by' => $created_by,
								'gedcom' => $gedcom,
								'persfamID' => $indi->getId(),
								'eventID' => 'NAME',
								'sourceID' => str_replace('@', '', $sour->getSour()) ,
								// 'ordernum' => get(),
								// 'description' => get(),
								// 'citedate' => get(),
								// 'citedatetr' => get(),
								'citetext' => $citetext,
								'page' => $sour->getPage(),
								'quay' => $sour->getQuay(),
								// 'note' => get(),
							));

						}
					}
				}

				/**
				 * Check if note for individual.
				 * 
				 */

				if($indi->getNote()){
					foreach ($indi->getNote() as $key => $noteLink) {
						$note_link = new \WPGenealogy\Models\NoteLink;
						$note_link = $note_link->create(array(
							'created_by' => $created_by,
							'persfamID' => $indi->getId(),
							'gedcom' => $gedcom,
							'noteID' => $noteLink->getNote(),
							'eventID' => 'GENERAL',
							// 'ordernum' => ,
							// 'secret' => ,
						));

						$noteID = 'N'.$note_link->id;

						/**
						 * Check if source for name.
						 * 
						 */
						if($noteLink->getSour()) {
							foreach ($noteLink->getSour() as $key => $sour) {
								$citetext = '';
								if($sour->getData()) {
									$citetext = $sour->getData()->getText();
								}
								$citation = new \WPGenealogy\Models\Citation;
								$citation = $citation->create(array(
									'created_by' => $created_by,
									'gedcom' => $gedcom,
									'persfamID' => $indi->getId(),
									'eventID' => $noteID,
									'sourceID' => str_replace('@', '', $sour->getSour()) ,
									// 'ordernum' => get(),
									// 'description' => get(),
									// 'citedate' => get(),
									// 'citedatetr' => get(),
									'citetext' => $citetext,
									'page' => $sour->getPage(),
									'quay' => $sour->getQuay(),
									// 'note' => get(),
								));

							}
						}
					}
				}

				
				/**
				 * Check if event for individual.
				 * 
				 */
				$event = $this->importEvent($gedcom, $persfamID, $indi->getEven(), 'I');

				/**
				 * Create people
				 * 
				 */
				$people = new \WPGenealogy\Models\People;
				$people = $people->create(array(
					'created_by' => $created_by,
					'gedcom' => $gedcom,
					'personID' => $indi->getId(),
					'lnprefix' => $lnprefix,
					'lastname' => $lastname,
					'firstname' => $firstname,
					'sex' => $indi->getSex(),
					'birthdate' => $this->removeSpace($event->birthdate),
					'birthdatetr' => $this->convertDate($this->removeSpace($event->birthdate)),
					'birthplace' => $event->birthplace,
					'deathdate' => $this->removeSpace($event->deathdate),
					'deathdatetr' => $this->convertDate($this->removeSpace($event->deathdate)),
					'deathplace' => $event->deathplace,
					'altbirthdate' => $this->removeSpace($event->altbirthdate),
					'altbirthdatetr' => $this->convertDate($this->removeSpace($event->altbirthdate)),
					'altbirthplace' => $event->altbirthplace,
					'burialdate' => $this->removeSpace($event->burialdate),
					'burialdatetr' => $this->convertDate($this->removeSpace($event->burialdate)),
					'burialplace' => $event->burialplace,
					'baptdate' => $this->removeSpace($event->baptdate),
					'baptdatetr' => $this->convertDate($this->removeSpace($event->baptdate)),
					'baptplace' => $event->baptplace,
					'confdate' => $this->removeSpace($event->confdate),
					'confdatetr' => $this->convertDate($this->removeSpace($event->confdate)),
					'confplace' => $event->confplace,
					'initdate' => $this->removeSpace($event->initdate),
					'initdatetr' => $this->convertDate($this->removeSpace($event->initdate)),
					'initplace' => $event->initplace,
					'endldate' => $this->removeSpace($event->endldate),
					'endldatetr' => $this->convertDate($this->removeSpace($event->endldate)),
					'endlplace' => $event->endlplace,
					'nickname' => $nickname,
					'title' => $title,
					'prefix' => $prefix,
					'suffix' => $suffix,
					'branch' => $branch,
				));

				if(!$people->deathdatetr && $people->birthdatetr ) {
					$date = (new \DateTime('-100 year'))->format('Y-m-d');
					if($people->birthdatetr > $date) {
						$people->living = true; 
						$people->save(); 
					}
				}

				/**
				 * Check family where spouse.
				 * 
				 */
				if($indi->getFams()) {

				}
				
				/**
				 * Check family where individual is children
				 * 
				 */
				if($indi->getFamc()) {

					/**
					 * Check each family
					 * 
					 */
					foreach ($indi->getFamc() as $key => $famc) {

						/**
						 * Create individual children
						 * 
						 */
						$children = new \WPGenealogy\Models\Children;
						$children = $children->create(array(
							'created_by' => $created_by,
							'gedcom' => $gedcom,
							'familyID' => $famc->getFamc(),
							'personID' => $indi->getId(),
							//'frel' => ,
							//'mrel' => ,
							//'sealdate' => ,
							//'sealdatetr' => ,
							//'sealplace' => ,
							//'haskids' => ,
							//'ordernum' => ,
							//'parentorder' => ,
						));

					}
				}
			}
		}

		/**
		 * Check if fam
		 * 
		 */
		if($data->getFam()) {

			/**
			 * Check if each fam
			 * 
			 */
			foreach ($data->getFam() as $key => $fam) {

				if($fam->getChil()){
					foreach ($fam->getChil() as $key => $chill) {
						$children = \WPGenealogy\Models\Children::where('familyID', $fam->getId())->where('personID', $chill)->first();
						if(!$children){
							$children = new \WPGenealogy\Models\Children;
							$children = $children->create(array(
								'created_by' => $created_by,
								'gedcom' => $gedcom,
								'familyID' => $fam->getId(),
								'personID' => $chill,
								//'frel' => ,
								//'mrel' => ,
								//'sealdate' => ,
								//'sealdatetr' => ,
								//'sealplace' => ,
								//'haskids' => ,
								//'ordernum' => ,
								//'parentorder' => ,
							));
						}
					}
				}

				$persfamID = $fam->getId();
				if($fam->getNote()){
					foreach ($fam->getNote() as $key => $noteLink) {
						$note_link = new \WPGenealogy\Models\NoteLink;
						$note_link = $note_link->create(array(
							'created_by' => $created_by,
							'persfamID' => $persfamID,
							'gedcom' => $gedcom,
							'noteID' => $noteLink->getNote(),
							'eventID' => 'GENERAL',
							// 'ordernum' => ,
							// 'secret' => ,
						));
					}
				}


				/**
				 * Check fam even.
				 * 
				 */
				$event = $this->importEvent($gedcom, $persfamID, $fam->getEven(), 'F');
				
				


				$changedate = '';

				if($fam->getChan()) {
					$changedate = $fam->getChan()->getDate();
				}

				/**
				 * Create family
				 * 
				 */
				$family = new \WPGenealogy\Models\Family;
				$family = $family->create(array(
					'created_by' => $created_by,
					'gedcom' => $gedcom,
					'familyID' => $persfamID,
					'husband' => $fam->getHusb(),
					'wife' => $fam->getWife(),
					'marrdate' => $this->removeSpace($event->marrdate),
					'marrdatetr' => $this->convertDate($this->removeSpace($event->marrdate)),
					'marrplace' => $event->marrplace,
					//'marrtype' => ,
					'divdate' => $this->removeSpace($event->divdate),
					'divdatetr' => $this->convertDate($this->removeSpace($event->divdate)),
					'divplace' => $event->divplace,
					//'status' => ,
					'sealdate' => $this->removeSpace($event->sealdate),
					'sealdatetr' => $this->convertDate($this->removeSpace($event->sealdate)),
					'sealplace' => $event->sealplace,
					//'husborder' => ,
					//'wifeorder' => ,
					'changedate' => $changedate,
					//'living' => ,
					//'private' => ,
					'branch' => $branch,
					//'changedby' => ,
					//'edituser' => ,
					//'edittime' => ,
				));
			}

		}
	}

	public function addPlace($place, $gedcom){

		$created_by = wp_get_current_user()->ID;

		/**
		 * Get existing place.
		 * 
		 */
		$checkPlaces = \WPGenealogy\Models\Place::where('place', $place)->first();

		if(!$checkPlaces){
			/**
			 * Create place.
			 * 
			 */
			$placeCreate = new \WPGenealogy\Models\Place;
			$placeCreate->create(array(
				'created_by' => $created_by,
				'gedcom' => $gedcom,
				'place' => $place
			));
		}
	}


	public function importEvent($gedcom, $persfamID, $event, $type){

		$created_by = wp_get_current_user()->ID;

		$birthdate = '';
		$birthplace = '';

		$deathdate = '';
		$deathplace = '';

		$burialdate = '';
		$burialplace = '';

		$altbirthdate = '';
		$altbirthplace = '';

		$baptdate = '';
		$baptplace = '';

		$confdate = '';
		$confplace = '';

		$initdate = '';
		$initplace = '';

		$endldate = '';
		$endlplace = '';

		$marrdate = '';
		$marrplace = '';

		$divdate = '';
		$divplace = '';

		$sealdate = '';
		$sealplace = '';
		
		if(!$event){
			return (object) array(
				'birthdate' => $birthdate,
				'birthplace' => $birthplace,
				'deathdate' => $deathdate,
				'deathplace' => $deathplace,
				'burialdate' => $burialdate,
				'burialplace' => $burialplace,
				'altbirthdate' => $altbirthdate,
				'altbirthplace' => $altbirthplace,
				'baptdate' => $baptdate,
				'baptplace' => $baptplace,
				'confdate' => $confdate,
				'confplace' => $confplace,
				'initdate' => $initdate,
				'initplace' => $initplace,
				'endldate' => $endldate,
				'endlplace' => $endlplace,
				'marrdate' => $marrdate,
				'marrplace' => $marrplace,
				'divdate' => $divdate,
				'divplace' => $divplace,
				'sealdate' => $sealdate,
				'sealplace' => $sealplace,
			);
		}

		foreach ($event as $key => $even) {

			/**
			 * Check if even type DEAT
			 * 
			 */
			if($even->getType() == 'BIRT') {
				$birthdate = $even->getDate();
				if($even->getPlac()) {
					$birthplace = $even->getPlac()->getPlac();
					$this->addPlace($birthplace, $gedcom);
				}
			}

			/**
			 * Check if even type DEAT
			 * 
			 */
			if($even->getType() == 'DEAT') {
				$deathdate = $even->getDate();
				if($even->getPlac()) {
					$deathplace = $even->getPlac()->getPlac();
					$this->addPlace($deathplace, $gedcom);

				}
			}

			/**
			 * Check if even type BURI
			 * 
			 */
			if($even->getType() == 'BURI') {
				$burialdate = $even->getDate();
				if($even->getPlac()) {
					$burialplace = $even->getPlac()->getPlac();
					$this->addPlace($burialplace, $gedcom);

				}
			}

			/**
			 * Check if even type CHR
			 * 
			 */
			if($even->getType() == 'CHR') {
				$altbirthdate = $even->getDate();
				if($even->getPlac()) {
					$altbirthplace = $even->getPlac()->getPlac();
					$this->addPlace($altbirthplace, $gedcom);

				}
			}

			/**
			 * Check if even type BAPL
			 * 
			 */
			if($even->getType() == 'BAPL') {
				$baptdate = $even->getDate();
				if($even->getPlac()) {
					$baptplace = $even->getPlac()->getPlac();
					$this->addPlace($baptplace, $gedcom);

				}
			}

			/**
			 * Check if even type CONL
			 * 
			 */
			if($even->getType() == 'CONL') {
				$confdate = $even->getDate();
				if($even->getPlac()) {
					$confplace = $even->getPlac()->getPlac();
					$this->addPlace($confplace, $gedcom);

				}
			}

			/**
			 * Check if even type INIT
			 * 
			 */
			if($even->getType() == 'INIT') {
				$initdate = $even->getDate();
				if($even->getPlac()) {
					$initplace = $even->getPlac()->getPlac();
					$this->addPlace($initplace, $gedcom);

				}
			}

			/**
			 * Check if even type ENDL
			 * 
			 */
			if($even->getType() == 'ENDL') {
				$endldate = $even->getDate();
				if($even->getPlac()) {
					$endlplace = $even->getPlac()->getPlac();
					$this->addPlace($endlplace, $gedcom);

				}
			}

			/**
			 *
			 * Check marrdate and marrplace
			 *
			 */
			if($even->getType() == 'MARR') {
				$marrdate = $even->getDate();
				if($even->getPlac()) {
					$marrplace = $even->getPlac()->getPlac();
					$this->addPlace($marrplace, $gedcom);
				}
			}
				
			/**
			 * Check sealdate and sealplace
			 *
			 */
			if($even->getType() == 'SLGS') {
				$sealdate = $even->getDate();
				if($even->getPlac()) {
					$sealplace = $even->getPlac()->getPlac();
					$this->addPlace($sealplace, $gedcom);
				}
			}
				
			/**
			 * Check divdate and divplace
			 *
			 */
			if($even->getType() == 'DIV') {

				$divdate = $even->getDate();
				if($even->getPlac()) {
					$divplace = $even->getPlac()->getPlac();
					$this->addPlace($divplace, $gedcom);
				}
			}
			/**
			 * Prepear to create new Event
			 *
			 */

			$eventtypeID = $this->getEventTypeId($even, $type);
			
			$eventdate = $even->getDate();
			$eventplace = '';
			if($even->getPlac()) {
				$eventplace = $even->getPlac()->getPlac();
				$this->addPlace($eventplace, $gedcom);
			}

			$age = $even->getAge();
			$agency = $even->getAgnc();
			$cause = $even->getCaus();

			$addressID = $this->getAddressID($even->getAddr(), $gedcom);

			$parenttag = $even->getType();

			$event = new \WPGenealogy\Models\Event;
			$event = $event->create(array(
				'created_by' => $created_by,
				//'eventID' => , 
				'gedcom' => $gedcom, 
				'persfamID' => $persfamID,
				'eventtypeID' => $eventtypeID, 
				'eventdate' => $eventdate, 
				'eventdatetr' => $this->convertDate($this->removeSpace($eventdate)), 
				'eventplace' => $eventplace, 
				'age' => $age, 
				'agency' => $agency, 
				'cause' => $cause, 
				'addressID' => $addressID, 
				'parenttag' => $parenttag, 
				//'info' => , 
			));


			$eventID = $event->id;

			if(
				$even->getType() == 'BIRT' 
				|| 
				$even->getType() == 'DEAT' 
				|| 
				$even->getType() == 'BURI' 
				|| 
				$even->getType() == 'CHR' 
				|| 
				$even->getType() == 'BAPL' 
				|| 
				$even->getType() == 'CONL' 
				|| 
				$even->getType() == 'INIT' 
				|| 
				$even->getType() == 'ENDL' 
				|| 
				$even->getType() == 'MARR' 
				|| 
				$even->getType() == 'SLGS' 
				|| 
				$even->getType() == 'DIV' 
			) {
				$eventID = $even->getType();
			}
			
			/**
			 * Check if even sour
			 * 
			 */
			if($even->getSour()) {

				/**
				 * Check if even each sour
				 * 
				 */
				foreach ($even->getSour() as $key => $sour) {
					/**
					 * Check if sour data text.
					 * 
					 */
					$citetext = $citedate = '';
					if($sour->getData()) {
						$citetext = $sour->getData()->getText();
						$citedate = $sour->getData()->getDate();
					}

					$note = '';
					if($sour->getNote()) {
						$note = current($sour->getNote())->getNote();
					}

					/**
					 * Create citation
					 * 
					 */
					$citation = new \WPGenealogy\Models\Citation;
					$citation = $citation->create(array(
						'created_by' => $created_by,
						'gedcom' => $gedcom,
						'persfamID' => $persfamID,
						'eventID' => $eventID,
						'sourceID' => str_replace('@', '', $sour->getSour()) ,
						//'ordernum' => ,
						//'description' => ,
						'citedate' => $this->removeSpace($citedate),
						'citedatetr' => $this->convertDate($this->removeSpace($event->citedate)),
						'citetext' => $citetext,
						'page' => $sour->getPage(),
						'quay' => $sour->getQuay(),
						'note' => $note,
					));
				}
			}

			/**
			 * Check if other event note.
			 * 
			 */

			if($even->getNote()) {
				foreach ($even->getNote() as $key => $note) {
					/**
					 * Create note
					 * 
					 */
					$noteCreate = new \WPGenealogy\Models\Note;
					$noteCreate = $noteCreate->create(array(
						'created_by' => $created_by,
						//'noteID',
						'gedcom' => $gedcom,
						'note' => $note->getNote(),
					));

					$noteId ='NTP'.$noteCreate->id;
					$noteCreate->noteID = $noteId;
					$noteCreate = $noteCreate->save();

					/**
					 * Create note link
					 * 
					 */
					$note_link = new \WPGenealogy\Models\NoteLink;
					$note_link = $note_link->create(array(
						'created_by' => $created_by,
						'persfamID' => $persfamID,
						'gedcom' => $gedcom,
						'noteID' => $noteId,
						'eventID' => $eventID,
						// 'ordernum' => ,
						// 'secret' => ,
					));

				}
			}
		}

		return (object) array(
			'birthdate' => $birthdate,
			'birthplace' => $birthplace,
			'deathdate' => $deathdate,
			'deathplace' => $deathplace,
			'burialdate' => $burialdate,
			'burialplace' => $burialplace,
			'altbirthdate' => $altbirthdate,
			'altbirthplace' => $altbirthplace,
			'baptdate' => $baptdate,
			'baptplace' => $baptplace,
			'confdate' => $confdate,
			'confplace' => $confplace,
			'initdate' => $initdate,
			'initplace' => $initplace,
			'endldate' => $endldate,
			'endlplace' => $endlplace,
			'marrdate' => $marrdate,
			'marrplace' => $marrplace,
			'divdate' => $divdate,
			'divplace' => $divplace,
			'sealdate' => $sealdate,
			'sealplace' => $sealplace,
		);


	}

	/**
	 * Check if other event note.
	 * 
	 */
	public function getAddressID($addr, $gedcom){

		$created_by = wp_get_current_user()->ID;

		if(!$addr) {
			return 0;
		}

		$address = new \WPGenealogy\Models\Address;
		$address1 = $address2 = $city = $state = $zip = '';
		if(null !== ($addr->getAdr1())) {
			$address1 = $addr->getAdr1();
		}
		if(null !== ($addr->getAdr2())) {
			$address2 = $addr->getAdr2();
		}
		if(null !== ($addr->getCity())) {
			$city = $addr->getCity();
		}
		if(null !== ($addr->getStae())) {
			$state = $addr->getStae();
		}
		if(null !== ($addr->getPost())) {
			$zip = $addr->getPost();
		}
		$address = $address->create(array(
			'created_by' => $created_by,
			//'addressID' => ,
			'address1' => $address1,
			'address2' => $address2,
			'city' => $city,
			'state' => $state,
			'zip' => $zip,
			//'country' => ,
			//'www' => ,
			//'email' => ,
			//'phone' => ,
			'gedcom' => $gedcom,
		));
		return $address->id;
	}


	/**
	 * Check if other event note.
	 * 
	 */
	public function getEventTypeId($even, $type){
		$created_by = wp_get_current_user()->ID;
		/*
		if( 
			$even->getType() == 'BIRT'
			 || 
			$even->getType() == 'DEAT'
			 || 
			$even->getType() == 'BURI'
			 || 
			$even->getType() == 'CHR'
			 || 
			$even->getType() == 'BAPL'
			 || 
			$even->getType() == 'CONL'
			 || 
			$even->getType() == 'INIT'
			 || 
			$even->getType() == 'ENDL'
			 || 
			$even->getType() == 'MARR'
			 || 
			$even->getType() == 'SLGS'
			 || 
			$even->getType() == 'DIV' 
		) {
			return $even->getType();
		}
		*/

		$eventType = \WPGenealogy\Models\EventType::where('tag', $even->getType())->first();

		if(!$eventType) {
			$eventType = new \WPGenealogy\Models\EventType;
			$eventType = $eventType->create(array(
				'created_by' => $created_by,
				//'eventtypeID' => ,
				'tag' => $even->getType(),
				//'description' => ,
				'display' => $even->getType(),
				//'keep' => ,
				//'collapse' => ,
				//'ordernum' => ,
				//'ldsevent' => ,
				'type' => $type,
			));
		}
		return $eventType->tag;
	}




}
