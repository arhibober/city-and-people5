<?php

namespace WPGenealogy;

use \Illuminate\Database\Capsule\Manager;

/**
 * Fired during plugin activation
 *
 * @link       https://bitbucket.org/blackandwhitedigital
 * @since      1.0.0
 *
 * @package    WPGenealogy
 * @subpackage WPGenealogy/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    WPGenealogy
 * @subpackage WPGenealogy/includes
 * @author     Black and White Digital <paul@blackandwhitedigital.com>
 */
class WPGenealogy_Activator {


	/**
	 * Plugin activation hook callback.
	 *
	 * This function fire when plugin agot ativate from wordpress dashboard
	 * it create table, default pages, and insert default data.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		self::role();
		
		self::save_default_settings();
		//self::create_pages();
		self::create_tables();
		self::insert_data();
		self::installed_required_plugin();
	}

	/**
	 * Plugin meta data.
	 *
	 * 
	 *
	 * @since    3.0.0
	 */
	public static function role() {

		add_role( 'guest', 'Guest', array(

		) );
		add_role( 'subm', 'Submitter', array(
			'allow_add_suggestion' => true,
		) );

		add_role( 'contrib', 'Contributor', array(
			'allow_add_data' => true,
		) );

		add_role( 'mcontrib', 'Media Contributor', array(
			'allow_add_media' => true,
		) );

		add_role( 'meditor', 'Media Editor', array(
			'allow_add_media' => true,
			'allow_edit_media' => true,
		) );

		add_role( 'custom', 'Custom', array(

		) );


		// get the the role object
		$editor_role_object = get_role( 'editor' );

		$editor_role_object->add_cap('allow_add_data', true);
		$editor_role_object->add_cap('allow_edit_data', true);


		$admin_role_object = get_role( 'administrator' );

		$admin_role_object->add_cap('allow_add_data', true);
		$admin_role_object->add_cap('allow_add_media', true);
		$admin_role_object->add_cap('allow_edit_data', true);
		$admin_role_object->add_cap('allow_edit_media', true);
		$admin_role_object->add_cap('allow_add_suggestion', true);
		$admin_role_object->add_cap('allow_delete_data', true);
		$admin_role_object->add_cap('allow_delete_media', true);

		$admin_role_object->add_cap('allow_living', true);
		$admin_role_object->add_cap('allow_private', true);
		$admin_role_object->add_cap('allow_ged', true);
		$admin_role_object->add_cap('allow_pdf', true);
		$admin_role_object->add_cap('allow_lds', true);
		$admin_role_object->add_cap('allow_profile', true);

	}



	/**
	 * Plugin meta data.
	 *
	 * 
	 *
	 * @since    3.0.0
	 */
	public static function installed_required_plugin() {

		$get_mu_plugin_dir = WP_PLUGIN_DIR . '/mu-plugins';

		if (!file_exists($get_mu_plugin_dir)) {
			mkdir($get_mu_plugin_dir, 0777, true);
		}

		if (file_exists($get_mu_plugin_dir)) {
			$gedtext = dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/includes/Helpers/mu-plugins/wpgenealogy-helpers.php';
			copy($gedtext, $get_mu_plugin_dir.'/wpgenealogy-helpers.php');
		}
	}

	/**
	 * Insert data to table.
	 *
	 * 
	 *
	 * @since    3.0.0
	 */
	public static function insert_data() {

		$defaultData = array(
			 1 => array('ADOP', 'Adoption', 'I'),
			 2 => array('BIRT', 'Birth', 'I'),
			 3 => array('BAPM', 'Baptism', 'I'),
			 8 => array('CENS', 'Census', 'I'),
			 9 => array('CHR', 'Christening', 'I'),
			 11 => array('CONF', 'Confirmation', 'I'),
			 12 => array('DEAT', 'Death', 'I'),
			 14 => array('EMIG', 'Emigration', 'I'),
			 15 => array('GRAD', 'Graduation', 'I'),
			 16 => array('IMMI', 'Immigration', 'I'),
			 17 => array('MARR', 'Marriage', 'I'),
			 18 => array('NATU', 'Naturalization', 'I'),
			 19 => array('ORDN', 'Ordination', 'I'),
			 21 => array('PROB', 'Probate', 'I'),
			 22 => array('WILL', 'Will', 'I'),
			 23 => array('CENS', 'Census', 'F'),
			 24 => array('MARR', 'Marriage', 'F'),
			 32 => array('DIV', 'Divorce', 'F'),

		);
		foreach ($defaultData as $key => $data) {
			$EventType = \WPGenealogy\Models\EventType::where('tag', $data[0])->first();
			if(!$EventType){
				$EventType = new \WPGenealogy\Models\EventType;
				$EventType->eventtypeID = $data[0];
				$EventType->tag = $data[0];
				$EventType->display = $data[1];
				$EventType->type = $data[2];
				$EventType->save();
			}
		}
	}
	

	/**
	 * Create pages.
	 *
	 * This function create a page with shortcode to use
	 * as default page to manage frontend dashboard.
	 *
	 * @since    3.0.0
	 */
	public static function create_pages() {
		$wpgenealogy_page_id = self::wpgenealogy_page_id();

		$wpgenealogy_page = get_post($wpgenealogy_page_id);

		
		if( ! $wpgenealogy_page ) {
			
			$content = '[wpgenealogy]';
			
			$my_post = array(
				'post_title'   => 'WPGenealogy-Page',
				'post_type'    => 'page',
				'post_content' => $content,
				'post_status'  => 'publish',

			);

			$page_id = wp_insert_post( $my_post, FALSE );

			$base = self::setting();
			$input =  get_option('wpgenealogy_settings');
			$input['configuration']['general']['pages']['wpgenealogy_page_id'] = $page_id;
			$setting = self::merge_setting($base, $input);
			
			update_option('wpgenealogy_settings', $setting);
			update_option('wpgenealogy_page_id', $page_id);
		}
	}
	

	/**
	 * Create pages.
	 *
	 * This function create a page with shortcode to use
	 * as default page to manage frontend dashboard.
	 *
	 * @since    3.0.0
	 */
	public static function save_default_settings() {
		$base = self::setting();
		$input =  get_option('wpgenealogy_settings');
		$setting = self::merge_setting($base, $input);
		update_option('wpgenealogy_settings', $setting);
	}





	/**
	 * Merge associative arrays
	 * 
	 *
	 * @since    3.0.0
	 */
	public static function merge_setting($base, $input, $final = array()){

		foreach ($base as $key => $value) {
			if($value && is_array($value) && isset($input[$key]) && $input[$key] && is_array($input[$key])) {
				$final[$key] = self::merge_setting($value, $input[$key]);
			} else if(!is_array($value) && isset($input[$key]) && $input[$key] && !is_array($input[$key])) {
				$final[$key] = $input[$key];
			} else {
				$final[$key] = $base[$key];
			}
		}
		return $final;
	}

	/**
	 * wpgenealogy base setting
	 *
	 * @since    3.0.0
	 */
	public function setting(){
		$setting = array(
			'configuration' => array(
				'general' => array(
					'pages' => array(
						'all' => array(),
						'wpgenealogy_page_id' => '',
					),
					'toogle' => array(
						'place' =>'active',
						'anniversary' =>'active',
						'calender' =>'active',
						'report' =>'active',
						'statistics' =>'active',
					),
					'home_content' => array(
						'display' => 'yes',
						'heading' => '',
						'paragraph' => '',
						'button' => array(
							'url' => '',
							'text' => '',
						),
						'image_url' => ''
					),
					'database' => array(
					),
					'table' => array(
					),
					'paths_folders' => array(
						'newrootpath' => '',
						'newsubroot' => '',
						'photopath' => 'photos',
						'documentpath' => 'documents',
						'historypath' => 'histories',
						'headstonepath' => 'headstones',
						'mediapath' => 'media',
						'gendexfile' => 'gendex',
						'backuppath' => 'backups',
						'modspath' => 'mods',
						'extspath' => 'extensions',
					),
					'design_definition' => array(
						'homepage' => '',
						'tngdomain' => '',
						'sitename' => '',
						'site_desc' => '',
						'doctype' => '',
						'dbowner' => '',
						'target' => '_self',
						'customheader' => '',
						'customfooter' => '',
						'tng_footermsg' => '',
						'custommeta' => '',
						'tng_tabs' => '',
						'tng_menu' => '0',
						'showhome' => '0',
						'showsearch' => '0',
						'searchchoice' => '0',
						'showlogin' => '0',
						'showshare' => '0',
						'showprint' => '0',
						'showbmarks' => '0',
						'hidechr' => '0',
						'defaulttree' => ''
					),
					'media' => array(
						'photosext' => 'jpg',
						'showextended' => '1',
						'imgmaxh' => '',
						'imgmaxw' => '',
						'thumbprefix' => 'thumb_',
						'thumbsuffix' => '',
						'thumbmaxh' => '100',
						'thumbmaxw' => '80',
						'tng_usedefthumbs' => '0',
						'tng_maxnoteprev' => '',
						'tng_ssdisabled' => '0',
						'tng_ssrepeat' => '0',
						'tng_imgviewer' => '0',
						'tng_imgvheight' => '0',
						'hidemedia' => '0',
						'tng_mediadel' => '1',
						'tng_mediathumbs' => '1',
						'tng_mediatrees' => '0',
						'favicon' => 'favicon.ico',
					),
					'language' => array(

					),
					'privacy' => array(
						'requirelogin' => '1',
						'treerestrict' => '0',
						'ldsdefault' => '2',
						'livedefault' => '0',
						'nonames' => '0',
						'nnpriv' => '0',
						'tng_cookieapproval' => '0',
						'tng_dataprotect' => '0',
						'tng_askconsent' => '0',
						'rcsitekey' => '',
						'rcsecret' => '',
					),
					'names' => array(
						'nameorder' => '2',
						'ucsurnames' => '0',
						'lnprefixes' => '1',
						'lnpfxnum' => '',
						'specpfx' => '',
					),
					'cemeteries' => array(
						'cemrows' => '',
						'cemblanks' => '0',
					),
					'mail_registration' => array(
						'emailaddr' => '',
						'fromadmin' => '0',
						'disallowreg' => '0',
						'revmail' => '0',
						'autotree' => '0',
						'autoapp' => '0',
						'ackemail' => '0',
						'omitpwd' => '0',
						'usesmtp' => '0',
						'mailhost' => '',
						'mailuser' => '',
						'mailpass' => '',
						'mailport' => '',
						'mailenc' => '',
					),
					'prefixes_suffixes' => array(
						'pprefix' => 'Is',
						'psuffix' => '',
						'fprefix' => 'F',
						'fsuffix' => '',
						'sprefix' => 'S',
						'ssuffix' => '',
						'rprefix' => 'REPO',
						'rsuffix' => '',
						'nprefix' => 'N',
						'nsuffix' => '',
					),
					'mobile' => array(
						'responsivetables' => '1',
						'tabletype' => 'toggle',
						'enablemodeswitch' => '1',
						'enableminimap' => '1',
					),
					'dna_tests' => array(
						'hidedna' => '0',
						'maxdnasearchresults' => '50',
						'showtestnumbers' => '0',
						'autofillancsurnames' => '0',
						'ancsurnameupper' => '0',
						'numgens' => '3',
						'surnameexcl' => '',
						'bgmain' => '#FFFFFF',
						'txtmain' => '#FFFFFF',
						'bgmode' => '#D1EEEE',
						'txtmode' => '#000000',
						'bgfastmut' => '#69001A',
						'txtfastmut' => '#FFFFFF',
						'bg1_12' => '#414E68',
						'txt1_12' => '#FFFFFF',
						'bg13_25' => '#41678A',
						'txt13_25' => '#FFFFFF',
						'bg26_37' => '#2E8899',
						'txt26_37' => '#FFFFFF',
						'bg38_67' => '#44A1B8',
						'txt38_67' => '#FFFFFF',
						'bg111' => '#05B8CC',
						'txt111' => '#FFFFFF',
					),
					'miscellaneous' => array(
						'maxsearchresults' => '50',
						'tng_istart' => '0',
						'notestogether' => '2',
						'scrollcite' => '0',
						'time_offset' => '0',
						'edit_timeout' => '',
						'maxgedcom' => '8',
						'change_cutoff' => '30',
						'change_limit' => '10',
						'prefereuro' => 'false',
						'calstart' => '0',
						'pardata' => '0',
						'lineending' => '\r\n',
						'password_type' => 'md5',
						'places1tree' => '0',
						'placetree' => 'Gray',
						'autogeo' => '0',
						'oldids' => '',
						'lastimport' => '',
						'hidetasks' => '',
						'hidetotals' => '',
						'backupdays' => '30',
						'tng_offline' => '',
						'submit' => 'Save',
						'cmssupport' => '',
						'cmsurl' => '',
						'cmstngpath' => '',
						'cmsmodule' => '',
						'cmscloaklogin' => '',
						'cmscredits' => '',
						'safety' => '1',
					)
				),
				'log' => array(
					'logname' => 'tplog.txt',
					'maxloglines' => '250',
					'badhosts' => '',
					'exusers' => '',
					'adminlogfile' => 'tplog-admin.txt',
					'adminmaxloglines' => '1000',
					'addr_exclude' => '',
					'msg_exclude' => '',

				),
				'map' => array(
					'mapkey' => '',
					'maptype' => 'TERRAIN',
					'mapstlat' => '0',
					'mapstlong' => '10',
					'mapstzoom' => '2',
					'mapfoundzoom' => '10',
					'mapindw' => '100%',
					'mapindh' => '400px',
					'maphstw' => '100%',
					'maphsth' => '400px',
					'mapadmw' => '100%',
					'mapadmh' => '400px',
					'startoff' => '1',
					'pstartoff' => '0',
					'showallpins' => '0',
					'pinplacelevel0' => '006',
					'pinplacelevel1' => '009',
					'pinplacelevel2' => '023',
					'pinplacelevel3' => '038',
					'pinplacelevel4' => '074',
					'pinplacelevel5' => '122',
					'pinplacelevel6' => '155',
				),
				'chart' => array(
					'pedigree' => array(
						'usepopups' => '1',
						'maxgen' => '8',
						'initpedgens' => '4',
						'popupspouses' => '1',
						'popupkids' => '1',
						'popupchartlinks' => '1',
						'hideempty' => '0',
						'boxwidth' => '231',
						'boxheight' => '131',
						'boxalign' => 'left',
						'boxheightshift' => '-2',
						'vwidth' => '100',
						'vheight' => '42',
						'vspacing' => '20',
						'vfontsize' => '7',
					),
					'descendancy' => array(
						'defdesc' => '2',
						'maxdesc' => '12',
						'initdescgens' => '4',
						'stdesc' => '0',
						'regnotes' => '0',
						'regnosp' => '0',
					),
					'relationship' => array(
						'initrels' => '1',
						'maxrels' => '10',
						'maxupgen' => '15',
					),
					'timeline' => array(
						'tcwidth' => '500',
						'simile' => '1',
						'tcheight' => '300',
						'tcevents' => '1',
					),
					'common' => array(
						'leftindent' => '10',
						'boxnamesize' => '11',
						'boxdatessize' => '10',
						'boxcolor' => '#e0e0f7',
						'colorshift' => '20',
						'emptycolor' => '#CCCCCC',
						'bordercolor' => '#777',
						'shadowcolor' => '#999999',
						'shadowoffset' => '4',
						'boxHsep' => '31',
						'boxVsep' => '11',
						'pagesize' => 'letter',
						'linewidth' => '1',
						'borderwidth' => '1',
						'popupcolor' => '#dddddd',
						'popupinfosize' => '10',
						'popuptimer' => '500',
						'pedevent' => 'over',
						'puboxwidth' => '201',
						'puboxheight' => '80',
						'puboxalign' => 'center',
						'puboxheightshift' => '-2',
						'inclphotos' => '1',
					),
				),
				'import' => array(
					'gedpath' => 'gedcom',
					'saveimport' => '1',
					'rrnum' => '100',
					'readmsecs' => '750',
					'defimpopt' => '0',
					'blankchangedt' => '0',
					'livingreqbirth' => '0',
					'maxlivingage' => '110',
					'maxprivyrs' => '',
					'maxdecdyrs' => '',
					'localphotopathdisplay' => '',
					'localhistorypathdisplay' => '',
					'localdocumentpathdisplay' => '',
					'localhspathdisplay' => '',
					'localotherpathdisplay' => '',
					'wholepath' => '0',
					'privnote' => '',
				)
			),
			'diagnostics' => array(
			)
		);
		return $setting;
	}

	/**
	 * Check frontend dashboard page.
	 *
	 * This function for checking existence of default frontend dashboard page.
	 *
	 * @since    3.0.0
	 */
	public static function wpgenealogy_page_id() {

		return get_option('wpgenealogy_page_id', null);
	}

	/**
	 * Create tables
	 *
	 * 
	 *
	 * @since    3.0.0
	 */
	public static function create_tables() {

		global $wpdb;

		$capsule = new Manager;

		$capsule->addConnection([
			'driver' => 'mysql',
			'host' => DB_HOST,
			'database' => DB_NAME,
			'username' => DB_USER,
			'password' => DB_PASSWORD,
			'charset' => DB_CHARSET,
			'prefix' => $wpdb->prefix,
		]);

		$capsule->setAsGlobal();
		$schema = $capsule->schema();
		$schema->defaultStringLength(191);
		$schema->enableForeignKeyConstraints();

		/**
		 * Repository table
		 */
		if (!$schema->hasTable('tp_event_types')) {
			$schema->create('tp_event_types', function ($table) {
				$table->increments('id');
				$table->string('eventtypeID')->nullable(); // eventtypeID
				$table->string('tag')->nullable();
				$table->string('description')->nullable();
				$table->text('display')->nullable();
				$table->tinyInteger('keep')->nullable();
				$table->tinyInteger('collapse')->nullable();
				$table->smallInteger('ordernum')->nullable();
				$table->tinyInteger('ldsevent')->nullable();
				$table->char('type', 1)->nullable();
				$table->string('created_by');
			  	$table->timestamps();
			});
		}

		/**
		 * Tree table
		 */
		if (!$schema->hasTable('tp_trees')) {
			$schema->create('tp_trees', function ($table) {
				$table->increments('id');
			  	$table->string('gedcom'); // treeID
			  	$table->string('treename')->nullable();
			  	$table->string('description')->nullable();
			  	$table->string('owner')->nullable();
			  	$table->string('email')->nullable();
			  	$table->string('address')->nullable();
			  	$table->string('city')->nullable();
			  	$table->string('state')->nullable();
			  	$table->string('country')->nullable();
			  	$table->string('zip')->nullable();
			  	$table->string('phone')->nullable();
			  	$table->tinyInteger('secret')->nullable();
			  	$table->tinyInteger('disallowgedcreate')->nullable();
			  	$table->tinyInteger('disallowpdf')->nullable();
			  	$table->string('lastimportdate')->nullable();
			  	$table->string('importfilename')->nullable();
			  	$table->string('created_by');
			  	$table->timestamps();
			});
		}

		/**
		 * Branche table
		 */
		if (!$schema->hasTable('tp_branches')) {
			$schema->create('tp_branches', function ($table) {
				$table->increments('id');
				$table->string('branch')->nullable(); // branchID
				$table->string('gedcom')->nullable();
				$table->string('description')->nullable();
				$table->string('personID')->nullable();
				$table->integer('agens')->nullable();
				$table->integer('dgens')->nullable();
				$table->integer('dagens')->nullable();
				$table->tinyInteger('inclspouses')->nullable();
				$table->tinyInteger('action')->nullable();
			  	$table->string('created_by');
				$table->timestamps();
			});
		}

		/**
		 * People table 
		 */
		if (!$schema->hasTable('tp_peoples')) {
			$schema->create('tp_peoples', function ($table) {
			  $table->increments('id');
			  $table->string('gedcom')->nullable();
			  $table->string('personID')->nullable(); // personID // peopleID
			  $table->string('lnprefix')->nullable();
			  $table->string('lastname')->nullable();
			  $table->string('firstname')->nullable();
			  $table->string('birthdate')->nullable();
			  $table->date('birthdatetr')->nullable();
			  $table->string('birthplace')->nullable();
			  $table->string('deathdate')->nullable();
			  $table->string('deathplace')->nullable();
			  $table->date('deathdatetr')->nullable();
			  $table->string('altbirthdate')->nullable();
			  $table->date('altbirthdatetr')->nullable();
			  $table->string('altbirthplace')->nullable();
			  $table->string('burialdate')->nullable();
			  $table->date('burialdatetr')->nullable();
			  $table->string('burialplace')->nullable();
			  $table->string('baptdate')->nullable();
			  $table->date('baptdatetr')->nullable();
			  $table->string('baptplace')->nullable();
			  $table->string('confdate')->nullable();
			  $table->date('confdatetr')->nullable();
			  $table->string('confplace')->nullable();
			  $table->string('initdate')->nullable();
			  $table->date('initdatetr')->nullable();
			  $table->string('initplace')->nullable();
			  $table->string('endldate')->nullable();
			  $table->date('endldatetr')->nullable();
			  $table->string('endlplace')->nullable();
			  $table->mediumText('sex')->nullable();
			  $table->tinyInteger('burialtype')->nullable();
			  $table->dateTime('changedate')->nullable();
			  $table->string('nickname')->nullable();
			  $table->mediumText('title')->nullable();
			  $table->mediumText('prefix')->nullable();
			  $table->mediumText('suffix')->nullable();
			  $table->tinyInteger('nameorder')->nullable();
			  $table->string('famc')->nullable();
			  $table->string('metaphone')->nullable();
			  $table->tinyInteger('living')->nullable();
			  $table->tinyInteger('private')->nullable();
			  $table->string('branch')->nullable();
			  $table->string('changedby')->nullable();
			  $table->string('edituser')->nullable();
			  $table->integer('edittime')->nullable();
			  $table->string('created_by');
				$table->timestamps();
			});
		}

		/**
		 * Family table
		 */
		if (!$schema->hasTable('tp_families')) {
			$schema->create('tp_families', function ($table) {
			  $table->increments('id');
			  $table->string('gedcom')->nullable();
			  $table->string('familyID')->nullable(); // familyID
			  $table->string('husband')->nullable();
			  $table->string('wife')->nullable();
			  $table->string('marrdate')->nullable();
			  $table->date('marrdatetr')->nullable();
			  $table->mediumText('marrplace')->nullable();
			  $table->string('marrtype')->nullable();
			  $table->string('divdate')->nullable();
			  $table->date('divdatetr')->nullable();
			  $table->mediumText('divplace')->nullable();
			  $table->string('status')->nullable();
			  $table->string('sealdate')->nullable();
			  $table->date('sealdatetr')->nullable();
			  $table->mediumText('sealplace')->nullable();
			  $table->tinyInteger('husborder')->nullable();
			  $table->tinyInteger('wifeorder')->nullable();
			  $table->datetime('changedate')->nullable();
			  $table->tinyInteger('living')->nullable();
			  $table->tinyInteger('private')->nullable();
			  $table->string('branch')->nullable();
			  $table->string('changedby')->nullable();
			  $table->string('edituser')->nullable();
			  $table->integer('edittime')->nullable();
			  $table->string('created_by');
			  $table->timestamps();
			});
		}

		/**
		 * Childrens table
		 */
		if (!$schema->hasTable('tp_childrens')) {
			$schema->create('tp_childrens', function ($table) {
				$table->increments('id'); // childrenID
				$table->string('gedcom')->nullable();
				$table->string('familyID')->nullable();
				$table->string('personID')->nullable();
				$table->string('frel')->nullable();
				$table->string('mrel')->nullable();
				$table->string('sealdate')->nullable();
				$table->date('sealdatetr')->nullable();
				$table->text('sealplace')->nullable();
				$table->tinyInteger('haskids')->nullable();
				$table->smallInteger('ordernum')->nullable();
				$table->tinyInteger('parentorder')->nullable();
				$table->string('created_by');
				$table->timestamps();
			});
		}

		/**
		 * Events table
		 */
		if (!$schema->hasTable('tp_events')) {
			$schema->create('tp_events', function ($table) {
				$table->increments('id');
				$table->string('eventID')->nullable(); // eventID
				$table->string('gedcom')->nullable();
				$table->string('persfamID')->nullable();
				$table->string('eventtypeID')->nullable();
				$table->string('eventdate')->nullable();
				$table->mediumText('eventdatetr')->nullable();
				$table->mediumText('eventplace')->nullable();
				$table->string('age')->nullable();
				$table->string('agency')->nullable();
				$table->string('cause')->nullable();
				$table->string('addressID')->nullable();
				$table->string('parenttag')->nullable();
				$table->mediumText('info')->nullable();
			  	$table->string('created_by');
				$table->timestamps();
			});
		}

		/**
		 * Addresses table
		 */
		if (!$schema->hasTable('tp_addresses')) {
			$schema->create('tp_addresses', function ($table) {
				$table->increments('id');
				$table->integer('addressID')->nullable(); //addressID
				$table->string('address1')->nullable();
				$table->string('address2')->nullable();
				$table->string('city')->nullable();
				$table->string('state')->nullable();
				$table->string('zip')->nullable();
				$table->string('country')->nullable();
				$table->string('www')->nullable();
				$table->string('email')->nullable();
				$table->string('phone')->nullable();
				$table->string('gedcom')->nullable();
			  	$table->string('created_by');
				$table->timestamps();
			});
		}
 
		/**
		 * Place table
		 */
		if (!$schema->hasTable('tp_places')) {
			$schema->create('tp_places', function ($table) {
				$table->increments('id'); // placeID
				$table->string('gedcom')->nullable();
				$table->string('place')->nullable();
				$table->string('longitude')->nullable();
				$table->string('latitude')->nullable();
				$table->tinyInteger('zoom')->nullable();
				$table->tinyInteger('placelevel')->nullable();
				$table->tinyInteger('temple')->nullable();
				$table->tinyInteger('geoignore')->nullable();
				$table->mediumText('notes')->nullable();
			  	$table->string('created_by');
				$table->timestamps();
			});
		}

		/** 
		 * Citation table
		 */
		if (!$schema->hasTable('tp_citations')) {
			$schema->create('tp_citations', function ($table) {
				$table->increments('id'); // citationID
				$table->string('gedcom')->nullable();
				$table->string('persfamID')->nullable();
				$table->string('eventID')->nullable();
				$table->string('sourceID')->nullable();
				$table->float('ordernum')->nullable();
				$table->text('description')->nullable();
				$table->string('citedate')->nullable();
				$table->date('citedatetr')->nullable();
				$table->text('citetext')->nullable();
				$table->text('page')->nullable();
				$table->string('quay')->nullable();
				$table->text('note')->nullable();
				$table->string('created_by');
				$table->timestamps();
			});
		}

		/**
		 * Note table
		 */
		if (!$schema->hasTable('tp_notes')) {
			$schema->create('tp_notes', function ($table) {
				$table->increments('id');
				$table->string('noteID')->nullable(); // noteID
				$table->string('gedcom')->nullable();
				$table->text('note')->nullable();
				$table->string('created_by');
				$table->timestamps();
			});
		}

		/**
		 * Note Link table
		 */
		if (!$schema->hasTable('tp_note_links')) {
			$schema->create('tp_note_links', function ($table) {
				$table->increments('id'); //note_linkID
				$table->string('persfamID')->nullable();
				$table->string('gedcom')->nullable();
				$table->string('noteID')->nullable();
				$table->string('eventID')->nullable();
				$table->float('ordernum')->nullable();
				$table->tinyInteger('secret')->nullable();
				$table->string('created_by');
				$table->timestamps();
			});
		}

		/**
		 * Source table
		 */
		if (!$schema->hasTable('tp_sources')) {
			$schema->create('tp_sources', function ($table) {
				$table->increments('id');
				$table->string('gedcom')->nullable(); 
				$table->string('sourceID')->nullable(); // sourceID
				$table->string('callnum')->nullable(); 
				$table->string('type')->nullable(); 
				$table->text('title')->nullable();
				$table->text('author')->nullable();
				$table->text('publisher')->nullable();
				$table->text('other')->nullable();
				$table->text('shorttitle')->nullable();
				$table->text('comments')->nullable();
				$table->text('actualtext')->nullable();
				$table->string('repoID')->nullable(); 
				$table->datetime('changedate')->nullable(); 
				$table->string('changedby')->nullable(); 
				$table->string('created_by');
				$table->timestamps();
			});
		}

		/**
		 * Repository table
		 */
		if (!$schema->hasTable('tp_repositories')) {
			$schema->create('tp_repositories', function ($table) {
				$table->increments('id');
				$table->string('repoID')->nullable(); // repoID
				$table->string('reponame')->nullable(); 
				$table->string('gedcom')->nullable(); 
				$table->integer('addressID')->nullable(); 
				$table->datetime('changedate')->nullable(); 
				$table->string('changedby')->nullable(); 
				$table->string('created_by');
				$table->timestamps();
			});
		}


		/**
		 * Repository table
		 */
		if (!$schema->hasTable('tp_associations')) {
			$schema->create('tp_associations', function ($table) {
				$table->increments('id');
				$table->string('assocID')->nullable(); 
				$table->string('gedcom')->nullable(); 
				$table->string('persfamID')->nullable(); 
				$table->string('passocID')->nullable(); 
				$table->string('reltype')->nullable(); 
				$table->string('relationship')->nullable(); 
				$table->string('created_by');
				$table->timestamps();
			});
		}



		/**
		 * Repository table
		 */
		if (!$schema->hasTable('tp_temp_events')) {
			$schema->create('tp_temp_events', function ($table) {
				$table->increments('id');
				$table->char('type')->nullable();
				$table->string('gedcom')->nullable();
				$table->string('personID')->nullable();
				$table->string('familyID')->nullable();
				$table->string('eventID')->nullable();
				$table->string('eventdate')->nullable();
				$table->text('eventplace')->nullable();
				$table->text('info')->nullable();
				$table->text('note')->nullable();
				$table->string('user')->nullable();
				$table->datetime('postdate')->nullable();
				$table->string('created_by');
				$table->timestamps();
			});
		}

		/**
		 * Repository table
		 */
		if (!$schema->hasTable('tp_timelines')) {
			$schema->create('tp_timelines', function ($table) {
				$table->increments('id');

				$table->string('evday')->nullable();
				$table->string('evmonth')->nullable();
				$table->string('evyear')->nullable();
				$table->string('endday')->nullable();
				$table->string('endmonth')->nullable();
				$table->string('endyear')->nullable();
				$table->string('evtitle')->nullable();
				$table->string('evdetail')->nullable();

				$table->string('created_by');
				$table->timestamps();
			});
		}

		/**
		 * Repository table
		 */
		if (!$schema->hasTable('tp_profile_images')) {
			$schema->create('tp_profile_images', function ($table) {
				$table->increments('id');
				$table->string('gedcom')->nullable();
				$table->string('personID')->nullable();
				$table->string('url')->nullable();
				$table->string('file')->nullable();
				$table->string('created_by');
				$table->timestamps();
			});
		}


		/**
		 * Repository table
		 */
		if (!$schema->hasTable('tp_reports')) {
			$schema->create('tp_reports', function ($table) {
				$table->increments('id');
				$table->string('gedcom')->nullable();
		
				$table->string('reportname')->nullable();
				$table->string('reportdesc')->nullable();
				$table->string('ranking')->nullable();
				$table->text('display')->nullable();
				$table->text('criteria')->nullable();
				$table->string('orderby')->nullable();
				$table->string('sqlselect')->nullable();
				$table->string('active')->nullable();

				$table->string('created_by');
				$table->timestamps();
			});
		}
		// ALTER TABLE `wp_tp_reports` CHANGE `criteria` `criteria` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL;
		/**
		 * Repository table
		 */
		if (!$schema->hasTable('tp_charts')) {
			$schema->create('tp_charts', function ($table) {
				$table->increments('id');
				$table->string('gedcom')->nullable();
				$table->string('branch')->nullable();
				$table->text('settings')->nullable();
				$table->string('created_by');
				$table->timestamps();
			});
		}

		/**
		 * Repository table
		 */
		if (!$schema->hasTable('tp_todos')) {
			$schema->create('tp_todos', function ($table) {
				$table->increments('id');
				$table->date('date');
				$table->text('title');
				$table->text('desc');
				$table->bigInteger('from');
				$table->bigInteger('for')->default('0');
				$table->date('until');
				$table->tinyInteger('status')->default('0');
				$table->tinyInteger('priority')->default('0');
				$table->binary('notify')->nullable();
				$table->string('created_by');
				$table->timestamps();
			});
		}

		/**
		 * Repository table
		 */
		if (!$schema->hasTable('tp_todo_comments')) {
			$schema->create('tp_todo_comments', function ($table) {
				$table->increments('id');
				$table->date('date');
				$table->bigInteger('task');
				$table->text('body');
				$table->bigInteger('from');
				$table->string('created_by');
				$table->timestamps();
			});
		}

		/**
		 * Repository table
		 */
		if (!$schema->hasTable('tp_todo_emails')) {
			$schema->create('tp_todo_emails', function ($table) {
				$table->increments('id');
				$table->text('subject');
				$table->text('body');
				$table->string('created_by');
				$table->timestamps();
			});
		}

	}
}
