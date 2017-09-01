<?php
/**
 * Automated Asset Versioning
 *
 * @package     Deftly\BetterAssetVersioning\Support
 * @since       0.0.1
 * @author      Jeff Cleverley
 * @link        https://github.com/JeffCleverley/KTC_betterAssetVersioning
 * @license     GNU General Public License 2.0+
 *
 */

if ( ! function_exists( 'get_theme_version' ) ) :
	/**
	 * Get the theme's version.
	 *
	 * When in development/mode mode, it uses the style.css modification time.
	 * Else, it grabs the version number from the stylesheet.
	 *
	 * @since 1.0.0
	 *
	 * @return string|int
	 */
	function get_theme_version() {
		if ( site_is_in_debug_mode() ) {
			return get_asset_current_version_number(
				get_stylesheet_directory() . '/style.css'
			);
		}

		$theme = wp_get_theme();

		return $theme->get( 'Version' );
	}
endif;


if ( ! function_exists( 'get_asset_current_version_number' ) ) :
	/**
	 * Get the specified asset file's current version number.
	 * This function gets the file's modification time when
	 * the site is in development/debug mode.
	 *
	 * @since 1.0.0
	 *
	 * @param $asset_file
	 *
	 * @return bool|int
	 */
	function get_asset_current_version_number( $asset_file ) {
		return filemtime( $asset_file );
	}
endif;


if ( ! function_exists( 'site_is_in_debug_mode' ) ) :
	/**
	 * Checks if the site is in development/debug mode.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	function site_is_in_debug_mode() {
		if ( ! defined( 'SCRIPT_DEBUG' ) ) {
			return false;
		}

		return ( (bool) SCRIPT_DEBUG === true );
	}
endif;

if ( ! function_exists( 'change_theme_stylesheet_uri_to_min_version' ) ) :
	add_filter( 'stylesheet_uri', 'change_theme_stylesheet_uri_to_min_version', 9999, 2 );
	/**
	 * Change the theme's stylesheet URI to minified version when not
	 * in development/debug mode.
	 *
	 * @since 1.0.0
	 *
	 * @param string $stylesheet_uri Stylesheet URI for the current theme/child theme.
	 * @param string $stylesheet_dir_uri Stylesheet directory URI for the current theme/child theme.
	 *
	 * @return string
	 */
	function change_theme_stylesheet_uri_to_min_version( $stylesheet_uri, $stylesheet_dir_uri ) {
		if ( site_is_in_debug_mode() ) {
			return $stylesheet_uri;
		}

		$minified_stylesheet_filename = '/style.min.css';

		if ( ! file_exists( get_stylesheet_directory() . $minified_stylesheet_filename ) ) {
			return $stylesheet_uri;
		}

		return $stylesheet_dir_uri . $minified_stylesheet_filename;
	}
endif;