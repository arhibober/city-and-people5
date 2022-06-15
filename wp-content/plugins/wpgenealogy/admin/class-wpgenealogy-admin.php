<?php
 
namespace WPGenealogy_Admin;
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://bitbucket.org/blackandwhitedigital
 * @since      1.0.0
 *
 * @package    WPGenealogy
 * @subpackage WPGenealogy/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WPGenealogy
 * @subpackage WPGenealogy/admin
 * @author     Black and White Digital <paul@blackandwhitedigital.com>
 */
class WPGenealogy_Admin {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;
	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WPGenealogy_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WPGenealogy_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if ( file_exists( plugin_dir_path( __FILE__ ) . 'css/wpgenealogy-admin-vendor.css' )) {
			wp_enqueue_style( $this->plugin_name . '-vendor', plugin_dir_url( __FILE__ ) . 'css/wpgenealogy-admin-vendor.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wpgenealogy-admin.css', array( $this->plugin_name . '-vendor' ), $this->version, 'all' );
		} else {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wpgenealogy-admin.css', array( ), $this->version, 'all' );
		}
	}
	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WPGenealogy_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WPGenealogy_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */


        $wpgenealogy_local = array(
            'site_url' => site_url(),
        );

		if ( file_exists( plugin_dir_path( __FILE__ ) . 'js/wpgenealogy-admin-vendor.js' )) {
			wp_enqueue_script( $this->plugin_name . '-vendor', plugin_dir_url( __FILE__ ) . 'js/wpgenealogy-admin-vendor.js', array('jquery'), $this->version, true );
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wpgenealogy-admin.js', array( 'jquery', $this->plugin_name . '-vendor' ), $this->version, true );
		} else {
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wpgenealogy-admin.js', array( 'jquery'), $this->version, true );
			wp_localize_script($this->plugin_name , 'wpGenealogy', $wpgenealogy_local);
		}
	}
	/**
	 * Ajax callback to swetch back to version 2.x.
	 *
	 * @since    3.0.0
	 */
	public function admin_menu() {
		add_menu_page(
			__('WP Genealogy', 'wpgenealogy'), 
			__('WP Genealogy', 'wpgenealogy'), 
			'manage_options', 
			'wpgenealogy', 
			array($this, 'options_panel'), 
			'dashicons-networking', 
			40
		);

	}
	/**
	 * Ajax callback to swetch back to version 2.x.
	 *
	 * @since    3.0.0
	 */
	public function account_settings() {

	}


	public function my_custom_redirect() {

		if ( wpg_fs()->is_activation_mode() ) {
		    return;
		}
		
		if ( isset( $_GET['page'] ) &&  $_GET['page'] == 'wpgenealogy' && !isset( $_GET['tab'] ) ) {

			$setup_wizard =  get_option('setup_wizard', null);
			if(!$setup_wizard) {
				$redirect = esc_url(site_url()).'/wp-admin/admin.php?page=wpgenealogy&tab=setup-wizard';
				wp_redirect($redirect);
				exit;
			}

		}
	}


	/**
	 * Ajax callback to swetch back to version 2.x.
	 *
	 * @since    3.0.0
	 */
	public function options_panel() {
		$setting =  get_option('wpgenealogy_settings');




		echo "<pre> ";
		// print_r($setting);
		echo "</pre> ";

		//$wpgenealogy_page_id = $setting['configuration']['general']['pages']['wpgenealogy_page_id'];
		$wpgenealogy_page_id = get_option('wpgenealogy_page_id', null);

		if($wpgenealogy_page_id){
			$wpg_page_name = get_post($wpgenealogy_page_id) ? get_post($wpgenealogy_page_id)->post_title : '';
		} else {
			$wpg_page_name = NULL;
		}

		if($wpgenealogy_page_id && $wpg_page_name) {
		} else {
			$wpgenealogy_page_id = NULL;

		}


		$wpg_home_content_heading = $setting['configuration']['general']['home_content']['heading'];
		$wpg_home_content_paragraph = $setting['configuration']['general']['home_content']['paragraph'];
		$wpg_home_content_button_text = $setting['configuration']['general']['home_content']['button']['text'];
		$wpg_home_content_button_url = $setting['configuration']['general']['home_content']['button']['url'];
		$wpg_home_content_image_url = $setting['configuration']['general']['home_content']['image_url'];

		$tree = \WPGenealogy\Models\Tree::count();

		$setup_wizard =  get_option('setup_wizard', null);
		$setup_wizard =  NULL;

		if(isset($_GET['tab']) && $_GET['tab']=='setup-wizard' && !$setup_wizard ){
			?>
			<style type="text/css">
				.wpgenealogy-setup-wizard {
					max-width: 1000px;
					display: block;
					margin: 0 auto;
				}

				.wpgenealogy-setup-wizard ul>li {
					display: inline-block;
				}

				.wpgenealogy-setup-wizard ul>li>a {
					display: block;
					padding: 25px 50px;
					background: #c5e0b4;
					font-weight: bold;
					font-size: 20px;
					color: #548235;
					text-decoration: none;
					position: relative;
					padding-right: 35px;
					padding-left: 65px;
				}


				.wpgenealogy-setup-wizard ul>li>a:focus,
				.wpgenealogy-setup-wizard ul>li>a:active {
					outline: 0;
					box-shadow: none;
				}

				.wpgenealogy-setup-wizard ul>li>a:after {
					position: absolute;
					content: "";
					border: 5px solid #f0f0f1;
					height: 48px;
					top: 7.5px;
					width: 48px;
					transform: rotate(45deg);
					background: #c5e0b4;
				}

				.wpgenealogy-setup-wizard ul>li>a:after {
					right: -30px;
					border-left: 0px;
					border-bottom: 0;
					z-index: 2;
				}

				.wpgenealogy-setup-wizard ul>li>a.active:after,
				.wpgenealogy-setup-wizard ul>li>a.active {
					background: #527f34;
					color: #fff;
				}


				.inner-stage {
					max-width: 800px;
					display: block;
					margin: 0 auto;
				}
				.wpgenealogy-setup-wizard h5 {
					font-size: 15px;
				}
				.wpgenealogy-setup-wizard .box {
					background: #c5e0b4;
					max-width: 900px;
					padding: 25px 50px;
					border-radius: 30px;
				}
				.wpgenealogy-setup-wizard .nav_cont {
					max-width: 900px;
					padding: 25px 0px;
				}
				.wpgenealogy-setup-wizard .nav_cont a {
					font-size: 18px;
				}
				.wpgenealogy-setup-wizard table {
					width: 100%;
				}
				.wpgenealogy-setup-wizard table td {
					padding:5px;
				}
				.wpgenealogy-setup-wizard .box input,
				.wpgenealogy-setup-wizard .box textarea {
					padding: 7px 25px;
					border-radius: 15px;
					width: 100%;
					font-size: 18px;
					border-width: 2px;
					margin-right: 15px;
				}
				.wpgenealogy-setup-wizard .box button,
				.wpgenealogy-setup-wizard .box a.button{
					background-color: #527f34;
					color: #fff;
					padding: 15px 50px;
					font-size: 20px;
					border:0px;
					border-radius: 15px;
					line-height: inherit;
				}
			</style>
			<div class="wrap wpgenealogy-setup-wizard">
				<ul class="stages-nav">
					<li>
						<a data-target="stage-01" class="active" href="<?php echo esc_attr( esc_url(site_url())); ?>/wp-admin/admin.php?page=wpgenealogy&tab=setup-wizard">STAGE 01</a>
					</li>
					<li>
						<a data-target="stage-02" href="<?php echo esc_attr( esc_url(site_url())); ?>/wp-admin/admin.php?page=wpgenealogy&tab=setup-wizard">STAGE 02</a>
					</li>
					<li>
						<a data-target="stage-03" href="<?php echo esc_attr( esc_url(site_url())); ?>/wp-admin/admin.php?page=wpgenealogy&tab=setup-wizard">STAGE 03</a>
					</li>
					<li>
						<a data-target="stage-04" href="<?php echo esc_attr( esc_url(site_url())); ?>/wp-admin/admin.php?page=wpgenealogy&tab=setup-wizard">STAGE 04</a>
					</li>
					<li>
						<a data-target="stage-05" href="<?php echo esc_attr( esc_url(site_url())); ?>/wp-admin/admin.php?page=wpgenealogy&tab=setup-wizard">STAGE 05</a>
					</li>
				</ul>
				<div class="stages">
					<div style="display: block;" class="stage-01">
						<div class="inner-stage">
							<h3>1. Create a Front End ‘Hub’ Page to Host Your Family History Site</h3>
							<div class="box">
								<p>WPGenealogy will display your family history information on the publicly viewable ‘front end’ of your site within a page you define. To create a ‘hub’ page on the front end and insert the default [wpgenealogy] shortcode, add a page below.</p>
								<p>Note: Your family history information will appear as www.yoursite.com/page-name-set-below</p>
								<table>
									<tr>
										<td><strong>Page Name *</strong></td>
									</tr>
									<tr>
										<td>
											
											<input <?php if($wpgenealogy_page_id ) {echo "disabled"; } ?> placeholder="Page Name *" value="<?php echo $wpg_page_name; ?>" type="text" name="wpg_page_name">
											<input <?php if($wpgenealogy_page_id ) {echo "disabled"; } ?> type="hidden" name="wpg_page_id" value="<?php echo $wpgenealogy_page_id; ?>">
										</td>
										<td>
											<button <?php if($wpgenealogy_page_id ) {echo "disabled"; } ?> class="wpg_create_page_name"> Create Page</button>
										</td>
									</tr>
								</table>

								<?php if($wpgenealogy_page_id ){ echo "✔️ Completed";} ?>






							</div>
							<div class="nav_cont">
								<table>
									<tr>
										<td>
										</td>
										<td align="right">
											<a data-target="stage-02" href="">Next<a>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
					<div style="display: none;" class="stage-02">
						<div class="inner-stage">
							<h3>2. Add Content to Your Hub Page</h3>
							<div class="box">
								<p>You can update some simple content to welcome people to your family history hub page.</p>
								<table>
									<tr>
										<td><strong>Page Heading </strong> <small><i>e.g. ‘Welcome to the Jones Family History Site</i></small></td>
									</tr>
									<tr>
										<td>
											<input value="<?php echo $wpg_home_content_heading; ?>" type="text" name="wpg_page_heading" placeholder="Page Heading e.g. ‘Welcome to the Jones Family History Site’">
											<?php if($wpg_home_content_heading){ echo "✔️ Completed";} ?>
										</td>
									</tr>
									<tr>
										<td><strong> Introductory Text</strong> <small><i>A short paragraph introducing the site.</i></small></td>
									</tr>
									<tr>
										<td>
											<textarea  placeholder="Introductory Text.  A short paragraph introducing the site." name="wpg_page_text"><?php echo $wpg_home_content_paragraph; ?></textarea>
											<div style="padding: 5px 0;"><?php if($wpg_home_content_paragraph){ echo "✔️ Completed";} ?></div>
										</td>
									</tr>
									<tr>
										<td><strong> Button Text </strong> <small><i>e.g. ‘Start Your Search Here’</i></small></td>
									</tr>
									<tr>
										<td>
											<input value="<?php echo $wpg_home_content_button_text; ?>" type="text" placeholder="Button Text e.g. ‘Start Your Search Here’" name="wpg_button_text">
											<div style="padding: 5px 0;"><?php if($wpg_home_content_button_text){ echo "✔️ Completed";} ?></div>
										</td>
									</tr>
									<tr>
										<td><strong> Button Link </strong><small><i>e.g. ‘site.com/wpg-page/#/surename/all’</i></small></td>
									</tr>
									<tr>
										<td>
											<input value="<?php echo $wpg_home_content_button_url; ?>" type="text" placeholder="Button Link " name="wpg_button_link">
											<div style="padding: 5px 0;"><?php if($wpg_home_content_button_url){ echo "✔️ Completed";} ?></div>
										</td>
									</tr>
									<tr>
										<td><strong> Image Url</strong> <small><i>Add an image to personalise your family history hub page</i></small></td>
									</tr>
									<tr>
										<td>
											<input value="<?php echo $wpg_home_content_image_url; ?>" type="text" placeholder="Image Url. Add an image to personalise your family history hub page " name="wpg_image_link">
											<div style="padding: 5px 0;"><?php if($wpg_home_content_image_url){ echo "✔️ Completed";} ?></div>
										</td>
									</tr>
									<tr>
										<td>
											<button class="save_hub_content">Save</button>
										</td>
									</tr>
								</table>
							</div>
							<div class="nav_cont">
								<table>
									<tr>
										<td>
											<a data-target="stage-01" href="">Back</a>
										</td>
										<td align="right">
											<a data-target="stage-03" href="">Next<a>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
					<div style="display: none;" class="stage-03">
						<div class="inner-stage">
							<h3>3. Create a Tree and Branch</h3>
							<div class="box">
								<h5 style="padding-top: 0px; margin-top: 0px;">Define a Tree (Required) </h5>
								<p>WP Genealogy uses ‘Trees’ to organise your family information.  Anyone you add to the plugin must be assigned to a Tree. Define your first Tree below.</p>
								<table>
									<tr>
										<td><strong> Tree name </strong> <small><i>e.g. ‘Jones Family’</i></small></td>
									</tr>
									<tr>
										<td>
											<input <?php if($tree){ echo "disabled";} ?> type="text" placeholder="Tree name e.g. ‘Jones Family’" name="tree_name">
										</td>
										<td>
											<button class="wpg_create_tree" <?php if($tree){ echo "disabled";} ?> > Save </button>
										</td>
									</tr>
								</table>
								<?php if($tree){ echo "✔️ Completed";} ?>
							</div>
							<div class="box" style="margin-top: 30px;">
								<h5 style="padding-top: 0px; margin-top: 0px;">Define a Branch (Optional)</h5>
								<p>A Branch is a set of individuals, within a tree, that all share a common label. An individual in the tree may belong to more than one Branch. A Branch enables you to fine tune some elements, such as reports and charts, derived from a Tree.  For example, you can make a descendent chart starting from a specific family member (called the ‘Root’) that will show only their descendants and not the whole family.</p>
								<table>
									<tr>
										<td><strong> Branch name</strong> <small><i>e.g. ‘Descendants of David Jones’</i></small></td>
									</tr>
									<tr>
										<td>
											<input <?php if($tree){ echo "disabled";} ?> type="text" placeholder="Branch name e.g. ‘Descendants of David Jones’" name="branch_name">
										</td>
										<td>
											<button class="wpg_create_branch" <?php if($tree){ echo "disabled";} ?>> Save </button>
										</td>
									</tr>
								</table>
								<?php if($tree){ echo "✔️ Completed";} ?>
							</div>
							<div class="nav_cont">
								<table>
									<tr>
										<td>
											<a data-target="stage-02" href="">Back</a>
										</td>
										<td align="right">
											<a data-target="stage-04" href="">Next<a>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
					<div style="display: none;" class="stage-04">
						<div class="inner-stage">
							<h3>4. Start Creating Your Family</h3>
							<div class="box">
								<h5 style="padding-top: 0px; margin-top: 0px;">Import a Gedcom file (Premium Version only)</h5>
								<p>With the Premium version of WPGenealogy you can import a Gedcom file. Please note that the file must be Gedcom Version 5.5 and dates should be in the format. To upgrade, go to the Account button in the WPGenealogy sidebar menu.</p>

								<?php 
								if($wpgenealogy_page_id){ 
									echo '<a class="button import_people" href="'.get_permalink( $wpgenealogy_page_id ).'#/dashboard/migrate"> Import </a>';
								} else {
									echo '<a disabled class="button import_people" href="#"> Import </a>';
								}
								?>

								<p><small>* Stage 1 should be completed to activate this link.</small></p>
							</div>
							<div class="box" style="margin-top: 30px;">
								<h5 style="padding-top: 0px; margin-top: 0px;">Add People</h5>
								<p>Start adding people directly into WPGenealogy. </p>
								<ul>
									<li>Enter some basic details for the person – first name, family name, nickname, gender etc.</li>
									<li>Assign the person to a Tree and (optional) a Branch.</li>
									<li>Add some core life event dates – e.g. birth, christening, death, burial if known.</li>
									<li>Click save and continue (additional information can be added later).</li>
								</ul>

								<?php 
								if($wpgenealogy_page_id){ 
									echo '<a class="button add_people" href="'.get_permalink( $wpgenealogy_page_id ).'#/dashboard/people/add"> Add People </a>';
								} else {
									echo '<a disabled class="button add_people" href="#"> Add People </a>';
								}
								?>

								
								<p><small>* Stage 1 should be completed to activate this link.</small></p>
							</div>
							<div class="nav_cont">
								<table>
									<tr>
										<td>
											<a data-target="stage-03" href="">Back</a>
										</td>
										<td align="right">
											<a data-target="stage-05" href="">Next<a>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
					<div style="display: none;" class="stage-05">
						<div class="inner-stage">
							<h3>5. Further Help</h3>
							<div class="box">
								<h5 style="padding-top: 0px; margin-top: 0px;">Support Documentation</h5>
								<p>We have a growing documentation section at </p>
								<p> https://www.wpgenealogy.net/knowledge-base/</p>
								<h5 style="padding-top: 0px; margin-top: 0px;">WordPress Support Forum</h5>
								<p>Support queries for bugs can be posted at:</p>
								<p> https://wordpress.org/support/plugin/wpgenealogy/</p>
								<button class="save_hub_content_fnish">Finish</button>
							</div>
							<div class="nav_cont">
								<table>
									<tr>
										<td>
											<a data-target="stage-04" href="">Back</a>
										</td>
										<td align="right">
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>

			</div>

			<?php
		} else {
			?>
			<div class="wrap wpgenealogy-options">
				<?php 
					$wpgenealogy_page_id = get_option('wpgenealogy_page_id');
					$wpgenealogy_page = get_the_permalink($wpgenealogy_page_id);
				?>
				<style type="text/css">
					.wpgenealogy-dashboard-admin {
						color: #fff;
					}
					.wpgenealogy-dashboard-admin td {
						vertical-align: top;
						background:#609a45; 
						border-radius: 15px; 
						margin: 10px;
					}
					.wpgenealogy-dashboard-admin td div {
						padding: 20px; 
					}
					.wpgenealogy-dashboard-admin td div h3{
						margin-top: 0; color: #fff;
					}
					.wpgenealogy-dashboard-admin p {
						color: #e9e9e9;
					}
					.wpgenealogy-dashboard-admin p.button-p {
						text-align: right;margin-bottom: 0px;
					}
					.wpgenealogy-dashboard-admin p.button-p a {
						background: #f6911b; 
						border: 1px solid #fff; 
						border-radius: 10px; 
						padding: 2px 25px;
						color: #fff; 
						font-weight: bold;
					}
				</style>
				<div class="wpgenealogy-dashboard-admin" style="">
					<table cellspacing="15">
						<tr>
							<td>
								<div>
									<h3>WP Genealogy Dashboard</h3>
									<p>
										Links to to all WP Genealogy features. Manage your<br>
										family members, information, and settings, etc
									</p>
									<p class="button-p">
										<a href="<?php echo esc_attr( esc_url($wpgenealogy_page)); ?>/#/dashboard" class="button"> Go </a>
									</p>
								</div>
							</td>
							<td>
								<div>
									<h3>WP Genealogy Frontend Hub</h3>
									<p>
										A frontend view for logged in users.<br>&nbsp;
									</p>
									<p class="button-p">
										<a href="<?php echo esc_attr( esc_url($wpgenealogy_page)); ?>" class="button"> Go </a>
									</p>
								</div>
							</td>

							<?php 
							$setup_wizard =  get_option('setup_wizard', null);
							$setup_wizard =  NULL;

							if(!$setup_wizard) {
							?>
							<td>
								<div>
									<h3>WP Genealogy Setup Wizard</h3>
									<p>
										Setup Wizard<br>&nbsp;
									</p>
									<p class="button-p">
										<a href="<?php echo esc_attr( esc_url(site_url())); ?>/wp-admin/admin.php?page=wpgenealogy&tab=setup-wizard" class="button"> Go </a>
									</p>
								</div>
							</td>
							<?php } ?>
						</tr>
						<tr>
							<td colspan="3" style="    color: #842029;
    background-color: #f8d7da;
    border-color: #f5c2c7;">
								
<div style="padding: 30px;">
<h4 style="margin-top: 0px;"> Important Announcement</h4>

<p style="color: #842029;">This plugin is in Beta and still being tested.<br>
If you find any issues, please let us know here<br>
<a href="https://www.wpgenealogy.net/support/#/" target="_blank">https://www.wpgenealogy.net/support/#/</a></p>

<p style="color: #842029;">Support documents can be found here:<br>
<a href="https://www.wpgenealogy.net/knowledge-base/#/" target="_blank">https://www.wpgenealogy.net/knowledge-base/#/</a></p>

<p style="margin-bottom: 0px;color: #842029;">Quick Start Guide:<br>
<a href="https://www.wpgenealogy.net/knowledge-base/quick-start-guide/#/" target="_blank">https://www.wpgenealogy.net/knowledge-base/quick-start-guide/#/</a></p>

	</div>


							</td>
						</tr>
					</table>
				</div>
			</div>
			<?php
		}
	}
	/**
	 * Ajax callback to swetch back to version 2.x.
	 *
	 * @since    3.0.0
	 */
	public function switch_now() {
		/**
		 * delete option 'wpgenealogy_3_active' from database.
		 *
		 */
		delete_option('wpgenealogy_3_active');
		/**
		 * send data to frontend.
		 *
		 */
		wp_send_json(array(
			'data'=>'ok',
			'url' => site_url().'/wp-admin/edit.php?post_type=member'
		), 200);
		die();
	}
	public function user_last_login( $user_login, $user ) {
		update_user_meta( $user->ID, 'last_login', time() );
	}
}