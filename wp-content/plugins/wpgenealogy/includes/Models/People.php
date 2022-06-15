<?php
namespace WPGenealogy\Models;

use WPGenealogy\Model;
use WPGenealogy\Models\Family;
use WPGenealogy\Models\Children;
use WPGenealogy\Models\Event;
use WPGenealogy\Models\NoteLink;
use WPGenealogy\Models\Citation;

/**
 * Class People 
 *
 * @package WPGenealogy\Models\People
 */
class People extends Model {

	protected $table = 'tp_peoples';

	protected $appends = [
		'name', 
		'image', 
		'age', 
		'date', 
		'events',
		'citations',
		'note_links'
	];

	protected $fillable = [
		'id',
		'created_by',
		'gedcom',
		'personID',
		'lnprefix',
		'lastname',
		'firstname',
		'birthdate',
		'birthdatetr',
		'sex',
		'birthplace',
		'deathdate',
		'deathdatetr',
		'deathplace',
		'altbirthdate',
		'altbirthdatetr',
		'altbirthplace',
		'burialdate',
		'burialdatetr',
		'burialplace',
		'burialtype',
		'baptdate',
		'baptdatetr',
		'baptplace',
		'confdate',
		'confdatetr',
		'confplace',
		'initdate',
		'initdatetr',
		'initplace',
		'endldate',
		'endldatetr',
		'endlplace',
		'changedate',
		'nickname',
		'title',
		'prefix',
		'suffix',
		'nameorder',
		'famc',
		'metaphone',
		'living',
		'private',
		'branch',
		'changedby',
		'edituser',
		'edittime',
	];

	protected $dates = [
		'created_at',
		'updated_at',
	];

	/**
	 *
	 *
	 * @since    3.0.0
	 */
	public function spouseW() {
		return $this->hasManyThrough(
			$this,
			Family::class,
			'wife',
			'personID',
			'personID',
			'wife'
		);
	}

	/**
	 *
	 *
	 * @since    3.0.0
	 */
	public function tree() {
		return $this->belongsTo(Tree::class, 'gedcom', 'gedcom');
	}

	/**
	 * 
	 *
	 * @since    3.0.0
	 */
	public function spouseH() {
		return $this->hasManyThrough(
			$this,
			Family::class,
			'husband',
			'personID',
			'personID',
			'husband'
		);
	}

	/**
	 *
	 *
	 * @since    3.0.0
	 */
	public function getLastnameAttribute(){
		return preg_replace('/\\\\/', '', $this->attributes['lastname']);
	}

	/**
	 *
	 *
	 * @since    3.0.0
	 */
	public function getNameAttribute() {
		return $this->getName();
	}

	/**
	 *
	 *
	 * @since    3.0.0
	 */
	public function getImageAttribute() {

		$ProfileImage = ProfileImage::where('personID', $this->personID)->where('gedcom', $this->gedcom)->orderBy('id', 'DESC')->first();

		if(!$ProfileImage) {
			if($this->sex == 'M') {
				return array(
					'placholder' => true,
					'url' => WPGENEALOGY_URL.'public/src/assets/images/male.jpg',
					'file' => WPGENEALOGY_PATH.'public/src/assets/images/male.jpg',
				);
			}
			if($this->sex == 'F') {
				return array(
					'placholder' => true,
					'url' => WPGENEALOGY_URL.'public/src/assets/images/female.jpg',
					'file' => WPGENEALOGY_PATH.'public/src/assets/images/female.jpg',
				);
			}
			return array(
				'placholder' => true,
				'url' => WPGENEALOGY_URL.'public/src/assets/images/unknown.jpg',
				'file' => WPGENEALOGY_PATH.'public/src/assets/images/unknown.jpg',
			);
		}

		return $ProfileImage;
	}

	/**
	 *
	 *
	 * @since    3.0.0
	 */
	public function getAgeAttribute() {
		$tz	= new \DateTimeZone('Europe/Brussels');
		$birthdate = \DateTime::createFromFormat('d M Y', $this->birthdate, $tz);
		$deathdate = \DateTime::createFromFormat('d M Y', $this->deathdate, $tz);
		if($birthdate && !$deathdate) {
			$age = $birthdate->diff(new \DateTime('now', $tz))->y;
			return;
		}
		if($birthdate && $deathdate) {
			$age = $birthdate->diff($deathdate)->y;
			return $age;
		}
	}

	/**
	 *
	 *
	 * @since    3.0.0
	 */
	public function getDateAttribute() {
		$date = '';
		if ($this->birthdate || $this->deathdate) {
			$date .= ' (';
		}
		if ($this->birthdate) {
			$date .= 'b. ' . $this->birthdate;
		}
		if ($this->birthdate && $this->deathdate) {
			$date .= ', ';
		}
		if ($this->deathdate) {
			$date .= 'd. ' . $this->deathdate;
		}
		if ($this->birthdate || $this->deathdate) {
			$date .= ')';
		}
		return $date;
	}
	
	/**
	 *
	 *
	 * @since    3.0.0
	 */
	public function getEventsAttribute() {
		return Event::where('persfamID', $this->personID)->where('gedcom', $this->gedcom)->get();
	}

	/**
	 *
	 *
	 * @since    3.0.0
	 */
	public function getCitationsAttribute() {
		return Citation::where('persfamID', $this->personID)->where('gedcom', $this->gedcom)->get();
	}

	/**
	 *
	 *
	 * @since    3.0.0
	 */
	public function getNoteLinksAttribute() {
		return NoteLink::where('persfamID', $this->personID)->where('gedcom', $this->gedcom)->get();
	}

	/**
	 *
	 *
	 * @since    3.0.0
	 */
	public function getName() {
		$order = !empty($this->nameorder) ? $this->nameorder : 1;
		$firstname = $this->firstname;
		$lastname = trim( $this->lnprefix.' '.$this->lastname );
		$title = $this->title && ($this->title == $this->prefix) ? $this->title : trim($this->title . ' ' . $this->prefix);
		$suffix = isset($this->suffix) ? $this->suffix : '';
		return $this->nameConstruct($firstname, $lastname, $title, $suffix, $order);
	}

	/**
	 *
	 *
	 * @since    3.0.0
	 */
	public function nameConstruct($firstnames, $lastnames, $title, $suffix, $order) {
		if($title) {
			$title .= ' ';
		}
		if($firstnames) {
			$firstnames .= ' ';
		}
		if($lastnames) {
			$lastnames .= ' ';
		}
		switch($order) {
			case '3':
				if($lastnames && $firstnames) {
					$lastnames .=	', ';
				}
				if($lastnames) {
					$lastnames .= ' ';
				}
				$namestr = trim($lastnames.$title.$firstnames.$suffix);
				break;
			case '2':
				if($lastnames) {
					$lastnames .= " ";
				}
				$namestr = trim($title.$lastnames.$firstnames);
				if($suffix) {
					$namestr .= ', '. $suffix;
				}
				break;
			default:
				$namestr = trim($title.$firstnames.$lastnames);
				if($suffix) {
					$namestr .= ', '. $suffix;
				}
				break;
		}
		return preg_replace('/\s\s+/', ' ', $namestr);
	}
}