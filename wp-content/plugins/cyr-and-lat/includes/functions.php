<?php
/**
 * Helpers functions
 *
 * @author Webcraftic <wordpress.webraftic@gmail.com>
 * @copyright (c) 11.12.2018, Webcraftic
 * @version 1.0
 */

defined( 'ABSPATH' ) || die( 'Cheatin&#8217; uh?' );

if ( ! function_exists( 'wbcr_ctlr_sanitize_title' ) ) {
	/**
	 * Filters all action calls sinitize_title and sanitize_file_name, returns the converted string in Latin.
	 *
	 * @param string $title processed header
	 *
	 * @return string
	 */
	function wbcr_ctlr_sanitize_title( $title ) {
		global $wpdb;
		
		$origin_title = $title;
		
		$is_term   = false;
		$backtrace = debug_backtrace();
		foreach ( $backtrace as $backtrace_entry ) {
			if ( $backtrace_entry['function'] == 'wp_insert_term' ) {
				$is_term = true;
				break;
			}
		}
		
		if ( ! is_admin() ) {
			foreach ( $backtrace as $backtrace_entry ) {
				if ( isset( $backtrace_entry['function'] ) && isset( $backtrace_entry['class'] ) ) {
					$is_query = in_array( $backtrace_entry['function'], array(
						'query_posts',
						'get_terms'
					) ) and in_array( $backtrace_entry['class'], array( 'WP', 'WP_Term_Query' ) );
					
					if ( $is_query ) {
						return $origin_title;
					}
				}
			}
		}
		
		$term = $is_term ? $wpdb->get_var( $wpdb->prepare( "SELECT slug FROM {$wpdb->terms} WHERE name = '%s'", $title ) ) : '';
		
		if ( empty( $term ) ) {
			$title = wbcr_ctlr_transliterate( $title );
		} else {
			$title = $term;
		}
		
		return apply_filters( 'wbcr_ctl_sanitize_title', $title, $origin_title );
	}
}

if ( ! function_exists( 'wbcr_ctlr_transliterate' ) ) {
	/**
	 * Clears special characters and converts all characters to Latin characters.
	 *
	 * @since 1.1.1
	 *
	 * @param string $titles
	 * @param bool $ignore_special_symbols
	 *
	 * @return string
	 */
	function wbcr_ctlr_transliterate( $title, $ignore_special_symbols = false ) {
		$origin_title = $title;
		$iso9_table   = wbcr_ctlr_get_symbols_pack();
		
		$title = strtr( $title, $iso9_table );
		
		if ( function_exists( 'iconv' ) ) {
			$title = iconv( 'UTF-8', 'UTF-8//TRANSLIT//IGNORE', $title );
		}
		
		if ( ! $ignore_special_symbols ) {
			$title = preg_replace( "/[^A-Za-z0-9'_\-\.]/", '-', $title );
			$title = preg_replace( '/\-+/', '-', $title );
			$title = preg_replace( '/^-+/', '', $title );
			$title = preg_replace( '/-+$/', '', $title );
		}
		
		return apply_filters( 'wbcr_ctl_transliterate', $title, $origin_title, $iso9_table );
	}
}

if ( ! function_exists( 'wbcr_ctlr_get_symbols_pack' ) ) {
	
	/**
	 * Function returns the base of characters depending on the installed locale.
	 *
	 * @since 1.1.1
	 * @return array
	 */
	function wbcr_ctlr_get_symbols_pack() {
		$loc = get_locale();
		
		$ret = array(
			// russian
			'??'  => 'A',
			'??'  => 'a',
			'??'  => 'B',
			'??'  => 'b',
			'??'  => 'V',
			'??'  => 'v',
			'??'  => 'G',
			'??'  => 'g',
			'??'  => 'D',
			'??'  => 'd',
			'??'  => 'E',
			'??'  => 'e',
			'??'  => 'Jo',
			'??'  => 'jo',
			'??'  => 'Zh',
			'??'  => 'zh',
			'??'  => 'Z',
			'??'  => 'z',
			'??'  => 'I',
			'??'  => 'i',
			'??'  => 'J',
			'??'  => 'j',
			'??'  => 'K',
			'??'  => 'k',
			'??'  => 'L',
			'??'  => 'l',
			'??'  => 'M',
			'??'  => 'm',
			'??'  => 'N',
			'??'  => 'n',
			'??'  => 'O',
			'??'  => 'o',
			'??'  => 'P',
			'??'  => 'p',
			'??'  => 'R',
			'??'  => 'r',
			'??'  => 'S',
			'??'  => 's',
			'??'  => 'T',
			'??'  => 't',
			'??'  => 'U',
			'??'  => 'u',
			'??'  => 'F',
			'??'  => 'f',
			'??'  => 'H',
			'??'  => 'h',
			'??'  => 'C',
			'??'  => 'c',
			'??'  => 'Ch',
			'??'  => 'ch',
			'??'  => 'Sh',
			'??'  => 'sh',
			'??'  => 'Shh',
			'??'  => 'shh',
			'??'  => '',
			'??'  => '',
			'??'  => 'Y',
			'??'  => 'y',
			'??'  => '',
			'??'  => '',
			'??'  => 'E',
			'??'  => 'e',
			'??'  => 'Ju',
			'??'  => 'ju',
			'??'  => 'Ya',
			'??'  => 'ya',
			// global
			'??'  => 'G',
			'??'  => 'g',
			'??'  => 'Ie',
			'??'  => 'ie',
			'??'  => 'I',
			'??'  => 'i',
			'??'  => 'I',
			'??'  => 'i',
			'????' => 'i',
			'????' => 'i',
			'????' => 'Jo',
			'????' => 'jo',
			'????' => 'i',
			'????' => 'I'
		);
		
		// ukrainian
		if ( $loc == 'uk' ) {
			$ret = array_merge( $ret, array(
				'??' => 'H',
				'??' => 'h',
				'??' => 'Y',
				'??' => 'y',
				'??' => 'Kh',
				'??' => 'kh',
				'??' => 'Ts',
				'??' => 'ts',
				'??' => 'Shch',
				'??' => 'shch',
				'??' => 'Iu',
				'??' => 'iu',
				'??' => 'Ia',
				'??' => 'ia',
			
			) );
			//bulgarian
		} elseif ( $loc == 'bg' || $loc == 'bg_BG' ) {
			$ret = array_merge( $ret, array(
				'??' => 'Sht',
				'??' => 'sht',
				'??' => 'a',
				'??' => 'a'
			) );
		}
		
		// Georgian
		if ( $loc == 'ka_GE' ) {
			$ret = array_merge( $ret, array(
				'???' => 'a',
				'???' => 'b',
				'???' => 'g',
				'???' => 'd',
				'???' => 'e',
				'???' => 'v',
				'???' => 'z',
				'???' => 'th',
				'???' => 'i',
				'???' => 'k',
				'???' => 'l',
				'???' => 'm',
				'???' => 'n',
				'???' => 'o',
				'???' => 'p',
				'???' => 'zh',
				'???' => 'r',
				'???' => 's',
				'???' => 't',
				'???' => 'u',
				'???' => 'ph',
				'???' => 'q',
				'???' => 'gh',
				'???' => 'qh',
				'???' => 'sh',
				'???' => 'ch',
				'???' => 'ts',
				'???' => 'dz',
				'???' => 'ts',
				'???' => 'tch',
				'???' => 'kh',
				'???' => 'j',
				'???' => 'h'
			) );
		}
		
		// Greek
		if ( $loc == 'el' ) {
			$ret = array_merge( $ret, array(
				'??' => 'a',
				'??' => 'v',
				'??' => 'g',
				'??' => 'd',
				'??' => 'e',
				'??' => 'z',
				'??' => 'h',
				'??' => 'th',
				'??' => 'i',
				'??' => 'k',
				'??' => 'l',
				'??' => 'm',
				'??' => 'n',
				'??' => 'x',
				'??' => 'o',
				'??' => 'p',
				'??' => 'r',
				'??' => 's',
				'??' => 's',
				'??' => 't',
				'??' => 'u',
				'??' => 'f',
				'??' => 'ch',
				'??' => 'ps',
				'??' => 'o',
				'??' => 'A',
				'??' => 'V',
				'??' => 'G',
				'??' => 'D',
				'??' => 'E',
				'??' => 'Z',
				'??' => 'H',
				'??' => 'TH',
				'??' => 'I',
				'??' => 'K',
				'??' => 'L',
				'??' => 'M',
				'??' => 'N',
				'??' => 'X',
				'??' => 'O',
				'??' => 'P',
				'??' => 'R',
				'??' => 'S',
				'??' => 'T',
				'??' => 'U',
				'??' => 'F',
				'??' => 'CH',
				'??' => 'PS',
				'??' => 'O',
				'??' => 'a',
				'??' => 'e',
				'??' => 'h',
				'??' => 'i',
				'??' => 'o',
				'??' => 'u',
				'??' => 'o',
				'??' => 'A',
				'??' => 'E',
				'??' => 'H',
				'??' => 'I',
				'??' => 'O',
				'??' => 'U',
				'??' => 'O',
				'??' => 'i',
				'??' => 'i',
				'??' => 'u',
				'??' => 'u',
				'??' => 'I',
				'??' => 'U'
			) );
		}
		
		// Armenian
		if ( $loc == 'hy' ) {
			$ret = array_merge( $ret, array(
				'??'  => 'A',
				'??'  => 'a',
				'??'  => 'B',
				'??'  => 'b',
				'??'  => 'G',
				'??'  => 'g',
				'??'  => 'D',
				'??'  => 'd',
				' ??' => ' Ye',
				'??'  => 'E',
				' ??' => ' ye',
				'??'  => 'e',
				'??'  => 'Z',
				'??'  => 'z',
				'??'  => 'E',
				'??'  => 'e',
				'??'  => 'Y',
				'??'  => 'y',
				'??'  => 'T',
				'??'  => 't',
				'??'  => 'Zh',
				'??'  => 'zh',
				'??'  => 'I',
				'??'  => 'i',
				'??'  => 'L',
				'??'  => 'l',
				'??'  => 'KH',
				'??'  => 'kh',
				'??'  => 'TS',
				'??'  => 'ts',
				'??'  => 'K',
				'??'  => 'K',
				'??'  => 'H',
				'??'  => 'h',
				'??'  => 'DZ',
				'??'  => 'dz',
				'??'  => 'GH',
				'??'  => 'gh',
				'??'  => 'J',
				'??'  => 'j',
				'??'  => 'M',
				'??'  => 'm',
				'??'  => 'Y',
				'??'  => 'y',
				'??'  => 'N',
				'??'  => 'n',
				'??'  => 'SH',
				'??'  => 'sh',
				' ??' => 'VO',
				'??'  => 'VO',
				' ??' => ' vo',
				'??'  => 'o',
				'??'  => 'Ch',
				'??'  => 'ch',
				'??'  => 'P',
				'??'  => 'p',
				'??'  => 'J',
				'??'  => 'j',
				'??'  => 'R',
				'??'  => 'r',
				'??'  => 'S',
				'??'  => 's',
				'??'  => 'V',
				'??'  => 'v',
				'??'  => 'T',
				'??'  => 't',
				'??'  => 'R',
				'??'  => 'r',
				'??'  => 'C',
				'??'  => 'c',
				'????' => 'U',
				'????' => 'u',
				'??'  => 'P',
				'??'  => 'p',
				'??'  => 'Q',
				'??'  => 'q',
				'????' => 'EV',
				'??'  => 'ev',
				'??'  => 'O',
				'??'  => 'o',
				'??'  => 'F',
				'??'  => 'f'
			) );
		}
		
		// Serbian
		if ( $loc == 'sr_RS' ) {
			$ret = array_merge( $ret, array(
				"??"  => "DJ",
				"??"  => "Z",
				"??"  => "Z",
				"??"  => "LJ",
				"??"  => "NJ",
				"??"  => "S",
				"??"  => "C",
				"??"  => "C",
				"??"  => "C",
				"??"  => "DZ",
				"??"  => "dj",
				"??"  => "z",
				"??"  => "z",
				"??"  => "i",
				"??"  => "lj",
				"??"  => "nj",
				"??"  => "s",
				"??"  => "c",
				"??"  => "c",
				"??"  => "dz",
				"????" => "Nja",
				"????" => "Nje",
				"????" => "Nji",
				"????" => "Njo",
				"????" => "Nju",
				"????" => "Lja",
				"????" => "Lje",
				"????" => "Lji",
				"????" => "Ljo",
				"????" => "Lju",
				"????" => "Dza",
				"????" => "Dze",
				"????" => "Dzi",
				"????" => "Dzo",
				"????" => "Dzu"
			) );
		}
		
		return apply_filters( 'wbcr_ctl_default_symbols_pack', $ret );
	}
}