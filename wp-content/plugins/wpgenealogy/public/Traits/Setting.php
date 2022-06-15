<?php 
namespace WPGenealogy_Public\Traits;

trait Setting {
	/**
	 * wpgenealogy function to make pluralize a word.
	 *
	 * @since    3.0.0
	 */
	public function settings_get(){
		$pages = get_posts(array('post_type' => 'page'));
		$ids = array();
		foreach ($pages as $key => $value) {
			array_push($ids, array(
				'ID' => $value->ID,
				'post_title' => $value->post_title,
				'url' => get_the_permalink($value->ID),
			));
		}
		$setting =  get_option('wpgenealogy_settings');
		$base = $this->setting();
		$settings = $this->merge_setting($base, $setting);
		$settings['configuration']['general']['pages']['all'] = $ids;
		$setting['configuration']['general']['pages']['wpgenealogy_page_id'] = get_option('wpgenealogy_page_id');
		wp_send_json($settings, 200 );
	}
	/**
	 * wpgenealogy function to make pluralize a word.
	 *
	 * @since    3.0.0
	 */
	public function setting_update(){

		$get_user = wp_get_current_user();
		$base = $this->setting();

		$input = $this->post_fixer($_POST);


		$setting = $this->merge_setting($base, $input);

		$wpgenealogy_page_id = $setting['configuration']['general']['pages']['wpgenealogy_page_id'];


		update_option('wpgenealogy_settings', $setting);
		update_option('wpgenealogy_page_id', $wpgenealogy_page_id);
		wp_send_json(true, 200 );
	}
	/**
	 * wpgenealogy merge setting.
	 *
	 * @since    3.0.0
	 */
	public function merge_setting($base, $input, $final = array()){
		foreach ($base as $key => $value) {
			if($value && is_array($value) && isset($input[$key]) && $input[$key] && is_array($input[$key])) {
				$final[$key] = $this->merge_setting($value, $input[$key]);
			} else if((!is_array($value) && isset($input[$key]) && $input[$key] && !is_array($input[$key])) || (isset($input[$key]) && ($input[$key]=='0' || $input[$key]=='1' || $input[$key]=='false' || $input[$key]=='true' || $input[$key]==0 || $input[$key]==1 || $input[$key]==false || $input[$key]==true) )) {
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
}