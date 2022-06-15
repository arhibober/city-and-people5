<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wordpress.org/plugins/treepress
 * @since      1.0.0
 *
 * @package    Treepress
 * @subpackage Treepress/public
 */
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Treepress
 * @subpackage Treepress/public
 * @author     Md Kabir Uddin <bd.kabiruddin@gmail.com>
 */
class Treepress_Public
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private  $plugin_name ;
    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private  $version ;
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    
    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Treepress_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Treepress_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style(
            $this->plugin_name,
            plugin_dir_url( __FILE__ ) . 'css/treepress-public.css',
            array(),
            $this->version,
            'all'
        );
        wp_enqueue_style(
            $this->plugin_name . '-styles',
            plugin_dir_url( __FILE__ ) . 'css/styles.css?v7',
            array(),
            $this->version,
            'all'
        );
    }
    
    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Treepress_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Treepress_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script(
            $this->plugin_name . '-raphael',
            plugin_dir_url( __FILE__ ) . 'js/raphael.js',
            array( 'jquery' ),
            $this->version,
            false
        );
        wp_enqueue_script(
            $this->plugin_name . '-public',
            plugin_dir_url( __FILE__ ) . 'js/treepress-public.js',
            array( 'jquery' ),
            $this->version,
            false
        );
        wp_enqueue_script(
            $this->plugin_name . '-panzoom',
            'https://unpkg.com/panzoom@8.0.0/dist/panzoom.min.js',
            array( 'jquery' ),
            $this->version,
            false
        );
    }
    
    /* Render a list of individual's nodes. */
    public function treepress_family_lists( $memberid = '' )
    {
        
        if ( class_exists( 'TreepressShortcode' ) ) {
            global  $post ;
            $get_family_id = '';
            $get_family_ids = wp_get_post_terms( $memberid, 'family', array() );
            if ( $get_family_ids ) {
                $get_family_id = $get_family_ids[0]->term_id;
            }
            $treepress = new Treepress();
            $the_family = $treepress->tree->get_tree( $get_family_id );
            //if (isset($the_family[$post->ID])) {
            $html = $the_family[$memberid]->get_html( $the_family );
            // if ( post_password_required() ) {
            //      $content = $content;
            // } else {
            //      $content = $html.$content;
            // }
            return $html;
        }
    
    }
    
    /* Render a list of nodes. */
    public function treepress_family_list( $family = '' )
    {
        $treepress = new Treepress();
        $the_family_all = $treepress->tree->get_tree( $family );
        $the_family = array_chunk( $the_family_all, 200, true )[0];
        $html = "";
        foreach ( $the_family as $fm ) {
            $html .= $fm->get_html( $the_family );
            $html .= '<hr>';
        }
        return $html;
    }
    
    public function ae_detect_ie()
    {
        
        if ( preg_match( '~MSIE|Internet Explorer~i', $_SERVER['HTTP_USER_AGENT'] ) || strpos( $_SERVER['HTTP_USER_AGENT'], 'Trident/7.0; rv:11.0' ) !== false ) {
            return true;
        } else {
            return false;
        }
    
    }
    
    public function name_full( $Itd )
    {
        $name_or = get_the_title( $Itd );
        $name_or = str_replace( '  ', ' ', $name_or );
        if ( get_option( 'bOneNamePerLine' ) == 'true' ) {
            $name_or = str_replace( ' ', '<br>', $name_or );
        }
        return $name_or;
    }
    
    public function name_first( $Itd )
    {
        $name_or = get_the_title( $Itd );
        $name = explode( ' ', $name_or );
        $name = $name[0];
        return $name;
    }
    
    public function dob_full( $Itd )
    {
        $born = get_post_meta( $Itd, 'born', true );
        return $born;
    }
    
    public function dob_year( $Itd )
    {
        $born = get_post_meta( $Itd, 'born', true );
        $born = explode( '-', $born )[0];
        return $born;
    }
    
    public function dod_full( $Itd )
    {
        $died = get_post_meta( $Itd, 'died', true );
        return $died;
    }
    
    public function dod_year( $Itd )
    {
        $died = get_post_meta( $Itd, 'died', true );
        $died = explode( '-', $died )[0];
        return $died;
    }
    
    public function ind_box( $id )
    {
        $html = '';
        $spouse_divorced = get_post_meta( $id, 'spouse_divorced', true );
        $html .= '<div class="tt-cont">';
        
        if ( $spouse_divorced ) {
            $html .= '<span class="spouse_divorced">Divorced';
            $html .= '</span>';
        }
        
        $html .= '<a href="' . get_the_permalink( $id ) . '">';
        $name_full = $this->name_full( $id );
        $name_first = $this->name_first( $id );
        $html .= '<div class="text-name text_name">';
        
        if ( get_option( 'bOnlyFirstName' ) == 'true' ) {
            $html .= '<span class="text-name-first">';
            $html .= $name_first;
            $html .= '</span>';
        } else {
            $html .= '<span class="text-name-full"> ';
            $html .= $name_full;
            $html .= '</span>';
        }
        
        if ( get_option( 'bShowGender' ) == 'true' ) {
            $html .= ' (' . get_post_meta( $id, 'gender', true ) . ')';
        }
        $html .= '</div>';
        $html .= '</a>';
        $dob_full = $this->dob_full( $id );
        $dob_year = $this->dob_year( $id );
        $dod_full = $this->dod_full( $id );
        $dod_year = $this->dod_year( $id );
        $bornp = get_option( 'bBirthDatePrefix' );
        $diedp = get_option( 'bDeathDatePrefix' );
        $html .= '<div class="text-other text_other">';
        if ( get_option( 'bBirthAndDeathDates' ) == 'true' ) {
            
            if ( get_option( 'bConcealLivingDates' ) == 'true' && !$dod_year ) {
                $html .= '';
            } else {
                
                if ( get_option( 'bBirthAndDeathDatesOnlyYear' ) == 'true' ) {
                    
                    if ( $dob_year ) {
                        $html .= '<span class="dob-only-year">';
                        $html .= $bornp . '' . $dob_year;
                        $html .= '</span>';
                    }
                
                } else {
                    
                    if ( $dob_full ) {
                        $html .= '<span class="dob-full">';
                        $html .= $bornp . '' . $dob_full;
                        $html .= '</span>';
                    }
                
                }
                
                if ( ($dob_full || $dob_year) && ($dod_full || $dod_year) ) {
                    $html .= ' - ';
                }
                
                if ( get_option( 'bBirthAndDeathDatesOnlyYear' ) == 'true' ) {
                    
                    if ( $dod_year ) {
                        $html .= '<span class="dod-only-year">';
                        $html .= $diedp . '' . $dod_year;
                        $html .= '</span>';
                    }
                
                } else {
                    
                    if ( $dod_full ) {
                        $html .= '<span class="dod-full">';
                        $html .= $diedp . '' . $dod_full;
                        $html .= '</span>';
                    }
                
                }
            
            }
        
        }
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }
    
    public function name_tre( $Itd )
    {
        $name_or = get_the_title( $Itd );
        $name = str_replace( '  ', ' ', $name_or );
        
        if ( get_option( 'bOneNamePerLine' ) === 'true' ) {
            $name = explode( ' ', $name );
            $name = implode( '<br>', $name );
        }
        
        
        if ( get_option( 'bOnlyFirstName' ) === 'true' ) {
            $name = explode( ' ', $name_or );
            $name = $name[0];
        }
        
        return $name;
    }
    
    public function toolbarlink( $post )
    {
        if ( !get_post( $post ) ) {
            return '';
        }
        $post = get_post( $post );
        $plugloc = treepress_plugin_dir_url;
        $cslink = get_post_meta( $post->ID, 'cslink', true );
        $permalink = get_permalink( $post->ID );
        $terms = wp_get_post_terms( $post->ID, 'family' );
        
        if ( $terms ) {
            $term_id = $terms[0]->term_id;
            $ftlink = get_term_meta( $term_id, 'family_tree_link', true );
        } else {
            $ftlink = '';
        }
        
        
        if ( strpos( $ftlink, '?' ) === false ) {
            $ftlink = $ftlink . '?ancestor=' . $post->ID;
        } else {
            $ftlink = $ftlink . '&ancestor=' . $post->ID;
        }
        
        $html = '';
        
        if ( get_option( 'bShowToolbar' ) == 'true' ) {
            $html .= '<div class="toolbar" id="toolbar' . $post->ID . '">';
            $html .= '<div class="toolbar-inner">';
            if ( get_option( 'treepress_toolbar_blogpage' ) == 'true' ) {
                $html .= '
					<a class="toolbar-blogpage" href="' . $permalink . '" title="' . __( 'View information about', 'treepress' ) . ' ' . htmlspecialchars( $post->name ) . '">
						<img border="0" class="toolbar-blogpage" src="' . $plugloc . 'public/imgs/open-book.png">
					</a>';
            }
            if ( get_option( 'treepress_toolbar_treenav' ) == 'true' ) {
                $html .= '
					<a class="toolbar-treenav" href="' . $ftlink . '" title="' . __( 'View the family of', 'treepress' ) . ' ' . htmlspecialchars( $post->name ) . '">
						<img width="13" border="0" class="toolbar-treenav" src="' . $plugloc . 'public/imgs/tree.gif">
					</a>';
            }
            if ( $cslink ) {
                $html .= '
					<a class="toolbar-treenav" href="' . $cslink . '" title="' . __( 'View the family of', 'treepress' ) . ' ' . htmlspecialchars( $post->name ) . '">
						<img width="13" border="0" class="toolbar-treenav" src="' . $plugloc . 'public/imgs/cslink.jpg">
					</a>';
            }
            $html .= '</div>';
            $html .= '</div>';
            return $html;
        }
    
    }
    
    public function get_the_post_thumbnail_url_tree( $Itd )
    {
        return ( get_the_post_thumbnail_url( $Itd, 'full' ) ? get_the_post_thumbnail_url( $Itd, 'full' ) : 'https://treepress.net/wp-content/plugins/treepress/public/imgs/no-avatar.png' );
    }
    
    public function family_style2( $tree )
    {
        $html = '';
        $html .= '<div class="indi ' . get_post_meta( $tree['ind'], 'gender', true ) . '">';
        $html .= '<div class="indi-info">';
        $html .= '<table>';
        $html .= '<tr>';
        $html .= '<td>';
        $html .= '<div class="thumb">';
        $html .= '<img width="50" src="' . $this->get_the_post_thumbnail_url_tree( $tree['ind'] ) . '">';
        $html .= $this->toolbarlink( $tree['ind'] );
        $html .= '</div>';
        $html .= '</td>';
        $html .= '<td>';
        $html .= $this->ind_box( $tree['ind'] );
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '</div>';
        $html .= '</div>';
        
        if ( isset( $tree['fam'] ) ) {
            $mt_rand = mt_rand();
            
            if ( isset( $tree['fam'][0]['spouse'] ) && get_post( $tree['fam'][0]['spouse'] ) || isset( $tree['fam'][0]['chill'] ) ) {
                $html .= '<ul class="fams">';
                
                if ( get_option( 'bShowSpouse' ) == 'true' ) {
                    
                    if ( get_option( 'bShowOneSpouse' ) == 'true' ) {
                        $temps['fam'] = $tree['fam'][0];
                        $tree['fam'] = array( $temps['fam'] );
                    }
                    
                    foreach ( $tree['fam'] as $key => $fam ) {
                        $rand = md5( uniqid( rand(), true ) );
                        
                        if ( isset( $fam['chill'] ) ) {
                            $haschill = 'haschill';
                        } else {
                            $haschill = 'nochill';
                        }
                        
                        $html .= '<li class="fam ' . $haschill . '">';
                        
                        if ( !isset( $fam['spouse'] ) ) {
                            $html .= '<div class="indi spouse">';
                            $html .= '<div class="indi-info">';
                            $html .= '<table>';
                            $html .= '<tr>';
                            $html .= '<td>';
                            $html .= '<div class="thumb">';
                            $html .= '<img width="50" src="https://treepress.net/wp-content/plugins/treepress/public/imgs/no-avatar.png">';
                            $html .= '</div>';
                            $html .= '</td>';
                            $html .= '<td>';
                            $html .= '<div class="tt-cont">';
                            $html .= '<a href="#">';
                            $html .= '<div class="text-name text_name">';
                            $html .= 'Unknown';
                            $html .= '</div>';
                            $html .= '</a>';
                            $html .= '</div>';
                            $html .= '</td>';
                            $html .= '</tr>';
                            $html .= '</table>';
                            $html .= '</div>';
                            $html .= '</div>';
                        } else {
                            $html .= '<div class="indi spouse ' . get_post_meta( $fam['spouse'], 'gender', true ) . '">';
                            $html .= '<div class="indi-info">';
                            $html .= '<table>';
                            $html .= '<tr>';
                            $html .= '<td>';
                            $html .= '<div class="thumb">';
                            $html .= '<img width="50" src="' . $this->get_the_post_thumbnail_url_tree( $fam['spouse'] ) . '">';
                            $html .= $this->toolbarlink( $fam['spouse'] );
                            $html .= '</div>';
                            $html .= '</td>';
                            $html .= '<td>';
                            $html .= $this->ind_box( $fam['spouse'] );
                            $html .= '</td>';
                            $html .= '</tr>';
                            $html .= '</table>';
                            $html .= '</div>';
                            $html .= '</div>';
                        }
                        
                        
                        if ( isset( $fam['chill'] ) ) {
                            $html .= '<ul class="chills">';
                            foreach ( $fam['chill'] as $key => $chill ) {
                                $html .= '<li class="chill">';
                                $html .= $this->family_style2( $chill );
                                $html .= '</li>';
                            }
                            $html .= '</ul>';
                        }
                        
                        $html .= '</li>';
                    }
                } else {
                    foreach ( $tree['fam'] as $key => $fam ) {
                        if ( isset( $fam['chill'] ) ) {
                            foreach ( $fam['chill'] as $key => $chill ) {
                                $html .= '<li class="chill">';
                                $html .= $this->family_style2( $chill );
                                $html .= '</li>';
                            }
                        }
                    }
                }
                
                $html .= '</ul>';
            }
        
        }
        
        return $html;
    }
    
    public function family_default( $tree )
    {
        $html = '';
        $html .= '<div class="d-inline person">';
        $html .= '<div>';
        $html .= '<div>';
        $html .= '<table>';
        $html .= '<tr>';
        $html .= '<td>';
        $html .= '<div class="thumb">';
        $html .= '<img width="50" src="' . $this->get_the_post_thumbnail_url_tree( $tree['ind'] ) . '">';
        $html .= $this->toolbarlink( $tree['ind'] );
        $html .= '</div>';
        $html .= '</td>';
        $html .= '<td>';
        $html .= $this->ind_box( $tree['ind'] );
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        
        if ( isset( $tree['fam'] ) ) {
            if ( tre_fs()->is_not_paying() ) {
                $tree['fam'] = [ $tree['fam'][0] ];
            }
            foreach ( $tree['fam'] as $key => $fam ) {
                
                if ( isset( $fam['chill'] ) && $fam['chill'] ) {
                    $haschill = 'haschill';
                } else {
                    $haschill = '';
                }
                
                $html .= '<div class="d-inline spouse spouse-' . $key . ' ' . $haschill . '">';
                $html .= '<div class="person">';
                
                if ( isset( $fam['spouse'] ) ) {
                    $html .= '<div>';
                    $html .= '<div>';
                    $html .= '<table>';
                    $html .= '<tr>';
                    $html .= '<td>';
                    $html .= '<div class="thumb">';
                    $html .= '<img width="50" src="' . $this->get_the_post_thumbnail_url_tree( $fam['spouse'] ) . '">';
                    $html .= $this->toolbarlink( $fam['spouse'] );
                    $html .= '</div>';
                    $html .= '</td>';
                    $html .= '<td>';
                    $html .= $this->ind_box( $fam['spouse'] );
                    $html .= '</td>';
                    $html .= '</tr>';
                    $html .= '</table>';
                    $html .= '</div>';
                    $html .= '</div>';
                } else {
                    $html .= '<div class="n-a"> <div class="indi-info">N/A </div></div>';
                }
                
                $html .= '</div>';
                
                if ( isset( $fam['chill'] ) ) {
                    $html .= '<ul class="childs">';
                    foreach ( $fam['chill'] as $keyx => $chill ) {
                        $html .= '<li>';
                        $html .= $this->family_default( $chill );
                        $html .= '</li>';
                    }
                    $html .= '</ul>';
                }
                
                $html .= '</div>';
            }
        }
        
        return $html;
    }
    
    /* Render the tree. */
    public function treepress_family_default( $root = '', $family = '' )
    {
        $html = '';
        if ( !$root ) {
            $root = $this->get_root_by_family( $family );
        }
        $tree = $this->create_tree_by_root( $root );
        $rand = md5( uniqid( rand(), true ) );
        $rant = 'tptc-' . rand();
        $rant_cla = '.' . $rant;
        $html .= '<div class="' . $rant . '">';
        $html .= '<div class="cont_bg">';
        $html .= '<div id="borderBox">';
        $html .= '<div id="dragableElement">';
        $html .= '<div class="tree-default" id="tree-container-default-' . $rand . '">';
        $html .= '<ul class="childs">';
        $html .= '<li>';
        $html .= $this->family_default( $tree );
        $html .= '</li>';
        $html .= '</ul>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        if ( !$this->ae_detect_ie() ) {
            $html .= '<script>
			jQuery(document).ready(function($){
				var scene = jQuery("#tree-container-default-' . $rand . '");
				for (var i = 0; i < scene.length; i++) {
					panzoom(scene[i], {
						onTouch: function(e) {
							return false;
						}
					})
				}
			});
			</script>';
        }
        return $html;
    }
    
    /* 
    Render the default tree. 
    */
    public function treepress_family_tree2( $root = '', $family = '' )
    {
        if ( !$root ) {
            $root = $this->get_root_by_family( $family );
        }
        $tree = $this->create_tree_by_root( $root );
        $html = '';
        $randp = md5( uniqid( rand(), true ) );
        $randp2 = md5( uniqid( rand(), true ) );
        $generationheight = ( get_option( 'generationheight' ) ? get_option( 'generationheight' ) . 'px' : 'auto' );
        $html .= '
		<style>
			.borderBox-' . $randp2 . ' {
				background-color: ' . get_option( 'canvasbgcol' ) . ';
				overflow: hidden;
			}
			.borderBox-' . $randp2 . ' .tree2 .indi-info {
				border-color: ' . get_option( 'nodeoutlinecol' ) . ';
				background-color: ' . get_option( 'nodefillcol' ) . ';
				opacity: ' . get_option( 'nodefillopacity' ) . ';
				color: ' . get_option( 'nodetextcolour' ) . ';
				border-radius: ' . get_option( 'nodecornerradius' ) . 'px;
				min-width: ' . get_option( 'nodeminwidth' ) . 'px;
				height: ' . $generationheight . ';
			}
			.borderBox-' . $randp2 . ' .tree2 .indi-info a {
				color: ' . get_option( 'nodetextcolour' ) . ';
			}
			.borderBox-' . $randp2 . ' .tree2 ul ul:before,
			.borderBox-' . $randp2 . ' .tree2 .fam > .indi:before,
			.borderBox-' . $randp2 . ' .tree2 li:after,
			.borderBox-' . $randp2 . ' .tree2 li:before {
				border-color: ' . get_option( 'nodeoutlinecol' ) . ' !important;
			}
			.borderBox-' . $randp2 . ' .tree2 .fam > .indi:after,
			.borderBox-' . $randp2 . ' .tree2 .fam:only-child > .indi:after,
			.borderBox-' . $randp2 . ' .tree2 .chill:only-child > .indi:after {
				background-color: ' . get_option( 'nodeoutlinecol' ) . ' !important;
			}
		</style>';
        $html .= '<div id="borderBox" class="borderBox-' . $randp2 . '">';
        $html .= '<div id="dragableElement">';
        $html .= '<div id="tree-container2" class="tree-container2-' . $randp . '">';
        $html .= '<ul class="tree2 tree-style-2">';
        $html .= '<li>';
        $html .= '<ul>';
        $html .= '<li>';
        $html .= $this->family_style2( $tree );
        $html .= '</li>';
        $html .= '</ul>';
        $html .= '</li>';
        $html .= '</ul>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        if ( !$this->ae_detect_ie() ) {
            $html .= '
			<script>
			jQuery(document).ready(function($){
				var scene = jQuery(".tree-container2-' . $randp . '");
				for (var i = 0; i < scene.length; i++) {
					panzoom(scene[i], {
						onTouch: function(e) {
							return false;
						}
					})
				}
			});
			</script>';
        }
        $showcreditlinkX = get_option( 'showcreditlink' );
        
        if ( tre_fs()->is_not_paying() ) {
            $showcreditlinkX = 'true';
        } else {
            $showcreditlinkX = false;
        }
        
        if ( $showcreditlinkX == 'true' ) {
            $html .= '<p style="text-align:left"><small>Powered by <a target="_blank" href="http://www.treepress.net">TreePress</a></small></p>' . "\n";
        }
        return $html;
    }
    
    /* Render the tree. */
    public function treepress_family_tree( $root = '', $family = '' )
    {
        $treepress = new Treepress();
        $the_family_all = $treepress->tree->get_tree( $family );
        $the_family = array_chunk( $the_family_all, 200, true )[0];
        $out = '';
        $ancestor = '';
        
        if ( !empty($_GET['ancestor']) ) {
            $ancestor = $_GET['ancestor'];
        } else {
            
            if ( !empty($root) ) {
                $ancestor = $root;
            } else {
                $node = reset( $the_family );
                $ancestor = ( $node !== false ? $node->post_id : '-1' );
            }
        
        }
        
        if ( !is_numeric( $ancestor ) ) {
            $ancestor = $treepress->tree->get_id_by_name( $ancestor, $the_family );
        }
        $render_from_parent = true;
        
        if ( $render_from_parent ) {
            $node = $treepress->tree->get_node_by_id( $ancestor, $the_family );
            
            if ( !empty($node->father) ) {
                $ancestor = $node->father;
            } else {
                if ( !empty($node->mother) ) {
                    $ancestor = $node->mother;
                }
            }
        
        }
        
        $out .= "<script type='text/javascript'>";
        $tree_data_js = "var tree_txt = new Array(\n";
        $the_family_all = $treepress->tree->get_tree( $family );
        $the_family = array_chunk( $the_family_all, 200, true )[0];
        $first = true;
        foreach ( $the_family as $node ) {
            
            if ( !$first ) {
                $tree_data_js .= ',' . "\n";
            } else {
                $first = false;
            }
            
            $str = '"EsscottiFTID=' . $node->post_id . '",' . "\n";
            $str .= '"Name=' . addslashes( $node->name ) . '",' . "\n";
            
            if ( $node->gender == 'm' ) {
                $str .= '"Male",' . "\n";
            } else {
                if ( $node->gender == 'f' ) {
                    $str .= '"Female",' . "\n";
                }
            }
            
            $str .= '"Birthday=' . $node->born . '",' . "\n";
            if ( !empty($node->died) && $node->died != '-' ) {
                $str .= '"Deathday=' . $node->died . '",' . "\n";
            }
            if ( isset( $node->partners ) && is_array( $node->partners ) ) {
                foreach ( $node->partners as $partner ) {
                    if ( is_numeric( $partner ) ) {
                        if ( isset( $the_family[$partner] ) ) {
                            $str .= '"Spouse=' . $the_family[$partner]->post_id . '",' . "\n";
                        }
                    }
                }
            }
            $str .= '"Toolbar=toolbar' . $node->post_id . '",' . "\n";
            $str .= '"Thumbnaildiv=thumbnail' . $node->post_id . '",' . "\n";
            $mother_id = null;
            $father_id = null;
            if ( $node->mother && isset( $the_family[$node->mother] ) ) {
                $mother_id = $the_family[$node->mother]->post_id;
            }
            if ( $node->father && isset( $the_family[$node->father] ) ) {
                $father_id = $the_family[$node->father]->post_id;
            }
            $str .= '"Parent=' . $mother_id . '",' . "\n";
            $str .= '"Parent=' . $father_id . '"';
            $tree_data_js .= $str;
        }
        $tree_data_js .= ');' . "\n";
        $out .= $tree_data_js;
        $out .= 'BOX_LINE_Y_SIZE = "' . $treepress->options->get_option( 'generationheight' ) . '";' . "\n";
        $out .= 'canvasbgcol = "' . $treepress->options->get_option( 'canvasbgcol' ) . '";' . "\n";
        $out .= 'nodeoutlinecol = "' . $treepress->options->get_option( 'nodeoutlinecol' ) . '";' . "\n";
        $out .= 'nodefillcol	= "' . $treepress->options->get_option( 'nodefillcol' ) . '";' . "\n";
        $out .= 'nodefillopacity = ' . $treepress->options->get_option( 'nodefillopacity' ) . ';' . "\n";
        $out .= 'nodetextcolour = "' . $treepress->options->get_option( 'nodetextcolour' ) . '";' . "\n";
        $out .= 'setOneNamePerLine(' . $treepress->options->get_option( 'bOneNamePerLine' ) . ');' . "\n";
        $out .= 'setOnlyFirstName(' . $treepress->options->get_option( 'bOnlyFirstName' ) . ');' . "\n";
        $out .= 'setBirthAndDeathDates(' . $treepress->options->get_option( 'bBirthAndDeathDates' ) . ');' . "\n";
        $out .= 'setBirthAndDeathDatesOnlyYear(' . $treepress->options->get_option( 'bBirthAndDeathDatesOnlyYear' ) . ');' . "\n";
        $out .= 'setBirthDatePrefix("' . $treepress->options->get_option( 'bBirthDatePrefix' ) . '");' . "\n";
        $out .= 'setDeathDatePrefix("' . $treepress->options->get_option( 'bDeathDatePrefix' ) . '");' . "\n";
        $out .= 'setConcealLivingDates(' . $treepress->options->get_option( 'bConcealLivingDates' ) . ');' . "\n";
        $out .= 'setShowSpouse(' . $treepress->options->get_option( 'bShowSpouse' ) . ');' . "\n";
        $out .= 'setShowOneSpouse(' . $treepress->options->get_option( 'bShowOneSpouse' ) . ');' . "\n";
        $out .= 'setVerticalSpouses(' . $treepress->options->get_option( 'bVerticalSpouses' ) . ');' . "\n";
        $out .= 'setMaidenName(' . $treepress->options->get_option( 'bMaidenName' ) . ');' . "\n";
        $out .= 'setShowGender(' . $treepress->options->get_option( 'bShowGender' ) . ');' . "\n";
        $out .= 'setDiagonalConnections(' . $treepress->options->get_option( 'bDiagonalConnections' ) . ');' . "\n";
        $out .= 'setRefocusOnClick(' . $treepress->options->get_option( 'bRefocusOnClick' ) . ');' . "\n";
        $out .= 'setShowToolbar(' . $treepress->options->get_option( 'bShowToolbar' ) . ');' . "\n";
        $out .= 'setNodeRounding(' . $treepress->options->get_option( 'nodecornerradius' ) . ');' . "\n";
        
        if ( $treepress->options->get_option( 'bShowToolbar' ) == 'true' ) {
            $out .= 'setToolbarYPad(20);' . "\n";
        } else {
            $out .= 'setToolbarYPad(0);' . "\n";
        }
        
        $out .= 'setToolbarPos(true, 3, 3);' . "\n";
        $out .= 'setMinBoxWidth(' . $treepress->options->get_option( 'nodeminwidth' ) . ');' . "\n";
        $out .= 'jQuery(document).ready(function($){' . "\n";
        $out .= '	familytreemain();' . "\n";
        $out .= '	var scene = document.getElementById(\'tree-container\');' . "\n";
        if ( !$this->ae_detect_ie() ) {
            $out .= '	panzoom(scene, {
						onTouch: function(e) {
							return false;
						}
					});' . "\n";
        }
        $out .= '});' . "\n";
        $out .= '</script>';
        $out .= '<input type="hidden" size="30" name="focusperson" id="focusperson" value="' . $ancestor . '">' . "\n";
        $out .= '<div id="borderBox" style="background-color:' . $treepress->options->get_option( 'canvasbgcol' ) . '">' . "\n";
        $out .= '<div id="dragableElement">';
        $out .= '<div id="tree-container">' . "\n";
        $out .= '<div id="toolbar-container">' . "\n";
        foreach ( $the_family as $node ) {
            $out .= $node->get_toolbar_div();
        }
        $out .= '</div>' . "\n";
        $out .= '<div id="thumbnail-container">' . "\n";
        foreach ( $the_family as $node ) {
            $out .= $node->get_thumbnail_div();
        }
        $out .= '</div>' . "\n";
        $out .= '<div id="familytree" style="background-color:' . $treepress->options->get_option( 'canvasbgcol' ) . '"></div>' . "\n";
        $out .= '<img name="hoverimage" id="hoverimage" style="visibility:hidden;" >' . "\n";
        $out .= '</div>' . "\n";
        $out .= '</div>' . "\n";
        $out .= '</div>' . "\n";
        $out .= '
		<!--[if IE]>
		<style>
			#dragableElement{
				position:initial !important;
				transform: none !important;
			}
			#borderBox {
				overflow: scroll !important;
			}
		</style>
		<![endif]-->
		' . "\n";
        $showcreditlinkX = $treepress->options->get_option( 'showcreditlink' );
        
        if ( tre_fs()->is_not_paying() ) {
            $showcreditlinkX = 'true';
        } else {
            $showcreditlinkX = false;
        }
        
        if ( $showcreditlinkX == 'true' ) {
            $out .= '<p style="text-align:left"><small>Powered by <a target="_blank" href="http://www.treepress.net">TreePress</a></small></p>' . "\n";
        }
        return $out;
    }
    
    public function treepress_family_list_insert( $content )
    {
        
        if ( preg_match( '{FAMILY-MEMBERS}', $content ) ) {
            $ft_output = $this->treepress_family_list();
            $content = str_replace( '{FAMILY-MEMBERS}', $ft_output, $content );
        }
        
        return $content;
    }
    
    public function treepress_insert( $content )
    {
        
        if ( preg_match( '{FAMILY-TREE}', $content ) ) {
            $ft_output = $this->treepress_family_tree2();
            $content = str_replace( '{FAMILY-TREE}', $ft_output, $content );
        }
        
        return $content;
    }
    
    public function bio_data_insert_in_single_page( $content )
    {
        global  $post ;
        $get_family_id = '';
        $get_family_ids = wp_get_post_terms( $post->ID, 'family', array() );
        if ( $get_family_ids ) {
            $get_family_id = $get_family_ids[0]->term_id;
        }
        $treepress = new Treepress();
        $the_family = $treepress->tree->get_tree( $get_family_id );
        
        if ( isset( $the_family[$post->ID] ) ) {
            $html = $the_family[$post->ID]->get_html( $the_family );
            
            if ( post_password_required() ) {
                $content = $content;
            } else {
                $content = $html . $content;
            }
        
        }
        
        return $content;
    }
    
    public function treepress_treepress_shortcode( $atts, $content = NULL )
    {
        $treepress = new Treepress();
        
        if ( is_array( $atts ) && array_key_exists( 'style', $atts ) ) {
            $style = $atts['style'];
        } else {
            $style = 1;
        }
        
        
        if ( is_array( $atts ) && array_key_exists( 'TreeType', $atts ) ) {
            $TreeType = $atts['TreeType'];
        } else {
            $TreeType = ( $treepress->options->get_option( 'TreeType' ) ? $treepress->options->get_option( 'TreeType' ) : 'svg' );
        }
        
        
        if ( is_array( $atts ) && array_key_exists( 'root', $atts ) ) {
            $root = $atts['root'];
        } else {
            $root = '';
        }
        
        
        if ( is_array( $atts ) && array_key_exists( 'chart', $atts ) ) {
            $chart = $atts['chart'];
        } else {
            $chart = '';
        }
        
        if ( isset( $_GET['ancestor'] ) ) {
            $root = $_GET['ancestor'];
        }
        
        if ( is_array( $atts ) && array_key_exists( 'family', $atts ) ) {
            $family = $atts['family'];
        } else {
            $family = '';
        }
        
        if ( $TreeType == 'svg' && $style == 1 ) {
            return $this->treepress_family_tree( $root, $family );
        }
        
        if ( !class_exists( 'Treepress_Charts' ) ) {
            return $this->treepress_family_default( $root, $family );
        } else {
            if ( $style && $style !== 1 ) {
                return apply_filters(
                    'more_tree',
                    $root,
                    $style,
                    $family
                );
            }
            return $this->treepress_family_default( $root, $family );
        }
    
    }
    
    public function treepress_members_shortcode( $atts, $content = NULL )
    {
        $treepress = new Treepress();
        $memberid = $atts['id'];
        $ft_output = $this->treepress_family_lists( $memberid );
        return $ft_output;
    }
    
    public function treepress_family_members_shortcode( $atts, $content = NULL )
    {
        $treepress = new Treepress();
        
        if ( is_array( $atts ) && array_key_exists( 'root', $atts ) ) {
            $root = $atts['root'];
        } else {
            $root = '';
        }
        
        
        if ( is_array( $atts ) && array_key_exists( 'family', $atts ) ) {
            $family = $atts['family'];
        } else {
            $family = '';
        }
        
        $ft_output = $this->treepress_family_list( $family );
        return $ft_output;
    }
    
    public function get_root_by_family( $family )
    {
        $treepress = new Treepress();
        $the_family_all = $treepress->tree->get_tree( $family );
        $the_family = array_chunk( $the_family_all, 200, true )[0];
        $ancestor = '';
        $node = reset( $the_family );
        $ancestor = ( $node !== false ? $node->post_id : '-1' );
        if ( !is_numeric( $ancestor ) ) {
            $ancestor = $treepress->tree->get_id_by_name( $ancestor, $the_family );
        }
        $render_from_parent = true;
        
        if ( $render_from_parent ) {
            $node = $treepress->tree->get_node_by_id( $ancestor, $the_family );
            
            if ( !empty($node->father) ) {
                $ancestor = $node->father;
            } else {
                if ( !empty($node->mother) ) {
                    $ancestor = $node->mother;
                }
            }
        
        }
        
        return $ancestor;
    }
    
    public function create_tree_by_root_d3_rev( $root )
    {
        $tree = array();
        $tree['ind'] = $root;
        $tree['name'] = get_the_title( $root );
        $tree['sex'] = get_post_meta( $root, 'gender', true );
        $children = array();
        $father = get_post_meta( $root, 'father', true );
        $mother = get_post_meta( $root, 'mother', true );
        if ( $father ) {
            array_push( $children, $father );
        }
        if ( $mother ) {
            array_push( $children, $mother );
        }
        
        if ( $children ) {
            $tree['children'] = $children;
            foreach ( $tree['children'] as $key => $chill ) {
                $tree['children'][$key] = $this->create_tree_by_root_d3_rev( $chill );
            }
        }
        
        return $tree;
    }
    
    public function cmp_birthdates( $a, $b )
    {
        $a = explode( "-", get_post_meta( $a, 'born', true ) );
        $b = explode( "-", get_post_meta( $b, 'born', true ) );
        
        if ( isset( $a[0] ) ) {
            if ( !is_numeric( $a[0] ) ) {
                $a[0] = intval( $a[0] );
            }
        } else {
            $a[0] = 0;
        }
        
        
        if ( isset( $a[1] ) ) {
            if ( !is_numeric( $a[1] ) ) {
                $a[1] = intval( $a[1] );
            }
        } else {
            $a[1] = 0;
        }
        
        
        if ( isset( $a[2] ) ) {
            if ( !is_numeric( $a[2] ) ) {
                $a[2] = intval( $a[2] );
            }
        } else {
            $a[2] = 0;
        }
        
        
        if ( isset( $b[0] ) ) {
            if ( !is_numeric( $b[0] ) ) {
                $b[0] = intval( $b[0] );
            }
        } else {
            $b[0] = 0;
        }
        
        
        if ( isset( $b[1] ) ) {
            if ( !is_numeric( $b[1] ) ) {
                $b[1] = intval( $b[1] );
            }
        } else {
            $b[1] = 0;
        }
        
        
        if ( isset( $b[2] ) ) {
            if ( !is_numeric( $b[2] ) ) {
                $b[2] = intval( $b[2] );
            }
        } else {
            $b[2] = 0;
        }
        
        $yd = $a[0] - $b[0];
        
        if ( $yd != 0 ) {
            return $yd;
        } else {
            $md = $a[1] - $b[1];
            
            if ( $md != 0 ) {
                return $md;
            } else {
                $dd = $a[2] - $b[2];
                return $dd;
            }
        
        }
    
    }
    
    public function create_tree_by_root( $root )
    {
        $tree = array();
        $tree['ind'] = $root;
        $sex = get_post_meta( $root, 'gender', true );
        $tree['sex'] = $sex;
        
        if ( $sex ) {
            $tree['sex'] = $sex;
        } else {
            $query = new WP_Query( array(
                'post_type'      => 'member',
                'posts_per_page' => -1,
                'meta_query'     => array( array(
                'key'     => 'father',
                'value'   => $root,
                'compare' => '=',
            ) ),
            ) );
            if ( $query->posts ) {
                $tree['sex'] = 'm';
            }
            $query = new WP_Query( array(
                'post_type'      => 'member',
                'posts_per_page' => -1,
                'meta_query'     => array( array(
                'key'     => 'mother',
                'value'   => $root,
                'compare' => '=',
            ) ),
            ) );
            if ( $query->posts ) {
                $tree['sex'] = 'f';
            }
        }
        
        $sex = $tree['sex'];
        
        if ( $sex == 'm' ) {
            $query = new WP_Query( array(
                'post_type'      => 'member',
                'posts_per_page' => -1,
                'meta_query'     => array( array(
                'key'     => 'father',
                'value'   => $root,
                'compare' => '=',
            ) ),
            ) );
            $spouse = array();
            if ( $query->posts ) {
                foreach ( $query->posts as $key => $child ) {
                    $mother = get_post_meta( $child->ID, 'mother', true );
                    if ( $mother && get_post( $mother ) ) {
                        array_push( $spouse, $mother );
                    }
                }
            }
            $spousec = get_post_meta( $root, 'spouse', true );
            if ( $spousec && get_post( $spousec ) ) {
                array_push( $spouse, $spousec );
            }
            $query = new WP_Query( array(
                'post_type'      => 'member',
                'posts_per_page' => -1,
                'fields'         => 'ids',
                'meta_query'     => array( array(
                'key'     => 'spouse',
                'value'   => $root,
                'compare' => '=',
            ) ),
            ) );
            if ( $query->posts ) {
                foreach ( $query->posts as $key => $sop ) {
                    if ( $sop ) {
                        array_push( $spouse, $sop );
                    }
                }
            }
            $spouse = array_unique( $spouse );
            $sp = 0;
            $spouse['un'] = 0;
            foreach ( $spouse as $key => $spo ) {
                if ( $spo ) {
                    $tree['fam'][$sp]['spouse'] = $spo;
                }
                $query = new WP_Query( array(
                    'post_type'      => 'member',
                    'posts_per_page' => -1,
                    'fields'         => 'ids',
                    'meta_query'     => array(
                    'relation' => 'AND',
                    array(
                    'key'     => 'father',
                    'value'   => $root,
                    'compare' => '=',
                ),
                    array(
                    'key'     => 'mother',
                    'value'   => $spo,
                    'compare' => '=',
                ),
                ),
                ) );
                
                if ( $query->posts ) {
                    $tree['fam'][$sp]['chill'] = $query->posts;
                    uasort( $tree['fam'][$sp]['chill'], array( $this, "cmp_birthdates" ) );
                    
                    if ( $tree['fam'][$sp]['chill'] ) {
                        $x = 0;
                        foreach ( $tree['fam'][$sp]['chill'] as $keyc => $chill ) {
                            $tree['fam'][$sp]['chill'][$chill] = $this->create_tree_by_root( $chill );
                            unset( $tree['fam'][$sp]['chill'][$keyc] );
                            $x++;
                        }
                    }
                
                }
                
                $query = new WP_Query( array(
                    'post_type'      => 'member',
                    'posts_per_page' => -1,
                    'fields'         => 'ids',
                    'meta_query'     => array(
                    'relation' => 'AND',
                    array(
                    'key'     => 'father',
                    'value'   => $root,
                    'compare' => '=',
                ),
                    array(
                    'relation' => 'OR',
                    array(
                    'key'     => 'mother',
                    'compare' => 'NOT EXISTS',
                ),
                    array(
                    'key'     => 'mother',
                    'value'   => '',
                    'compare' => '=',
                ),
                ),
                ),
                ) );
                
                if ( $query->posts ) {
                    $tree['fam'][$sp]['chill'] = $query->posts;
                    uasort( $tree['fam'][$sp]['chill'], array( $this, "cmp_birthdates" ) );
                    
                    if ( $tree['fam'][$sp]['chill'] ) {
                        $x = 0;
                        foreach ( $tree['fam'][$sp]['chill'] as $keyc => $chill ) {
                            $tree['fam'][$sp]['chill'][$chill] = $this->create_tree_by_root( $chill );
                            unset( $tree['fam'][$sp]['chill'][$keyc] );
                            $x++;
                        }
                    }
                
                }
                
                $sp++;
            }
        }
        
        
        if ( $sex == 'f' ) {
            $query = new WP_Query( array(
                'post_type'      => 'member',
                'posts_per_page' => -1,
                'meta_query'     => array( array(
                'key'     => 'mother',
                'value'   => $root,
                'compare' => '=',
            ) ),
            ) );
            $spouse = array();
            if ( $query->posts ) {
                foreach ( $query->posts as $key => $child ) {
                    $father = get_post_meta( $child->ID, 'father', true );
                    if ( $father && get_post( $father ) ) {
                        array_push( $spouse, $father );
                    }
                }
            }
            $spousec = get_post_meta( $root, 'spouse', true );
            if ( $spousec && get_post( $spousec ) ) {
                array_push( $spouse, $spousec );
            }
            $query = new WP_Query( array(
                'post_type'      => 'member',
                'posts_per_page' => -1,
                'fields'         => 'ids',
                'meta_query'     => array( array(
                'key'     => 'spouse',
                'value'   => $root,
                'compare' => '=',
            ) ),
            ) );
            if ( $query->posts ) {
                foreach ( $query->posts as $key => $sop ) {
                    if ( $sop ) {
                        array_push( $spouse, $sop );
                    }
                }
            }
            $spouse = array_unique( $spouse );
            $sp = 0;
            $spouse['un'] = 0;
            foreach ( $spouse as $key => $spo ) {
                if ( $spo ) {
                    $tree['fam'][$sp]['spouse'] = $spo;
                }
                $query = new WP_Query( array(
                    'post_type'      => 'member',
                    'posts_per_page' => -1,
                    'fields'         => 'ids',
                    'meta_query'     => array(
                    'relation' => 'AND',
                    array(
                    'key'     => 'mother',
                    'value'   => $root,
                    'compare' => '=',
                ),
                    array(
                    'key'     => 'father',
                    'value'   => $spo,
                    'compare' => '=',
                ),
                ),
                ) );
                
                if ( $query->posts ) {
                    $tree['fam'][$sp]['chill'] = $query->posts;
                    uasort( $tree['fam'][$sp]['chill'], array( $this, "cmp_birthdates" ) );
                    
                    if ( $tree['fam'][$sp]['chill'] ) {
                        $x = 0;
                        foreach ( $tree['fam'][$sp]['chill'] as $keyc => $chill ) {
                            $tree['fam'][$sp]['chill'][$chill] = $this->create_tree_by_root( $chill );
                            unset( $tree['fam'][$sp]['chill'][$keyc] );
                            $x++;
                        }
                    }
                
                }
                
                $query = new WP_Query( array(
                    'post_type'      => 'member',
                    'posts_per_page' => -1,
                    'fields'         => 'ids',
                    'meta_query'     => array(
                    'relation' => 'AND',
                    array(
                    'key'     => 'mother',
                    'value'   => $root,
                    'compare' => '=',
                ),
                    array(
                    'relation' => 'OR',
                    array(
                    'key'     => 'father',
                    'compare' => 'NOT EXISTS',
                ),
                    array(
                    'key'     => 'father',
                    'value'   => '',
                    'compare' => '=',
                ),
                ),
                ),
                ) );
                
                if ( $query->posts ) {
                    $tree['fam'][$sp]['chill'] = $query->posts;
                    uasort( $tree['fam'][$sp]['chill'], array( $this, "cmp_birthdates" ) );
                    
                    if ( $tree['fam'][$sp]['chill'] ) {
                        $x = 0;
                        foreach ( $tree['fam'][$sp]['chill'] as $keyc => $chill ) {
                            $tree['fam'][$sp]['chill'][$chill] = $this->create_tree_by_root( $chill );
                            unset( $tree['fam'][$sp]['chill'][$keyc] );
                            $x++;
                        }
                    }
                
                }
                
                $sp++;
            }
        }
        
        return $tree;
    }

}