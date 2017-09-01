<?php
/**
 * Better Asset Versioning
 *
 * @package     Deftly\BetterAssetVersioning
 * @author      Jeff Cleverley
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: Better Asset Versioning
 * Plugin URI:  https://github.com/JeffCleverley/KTC_betterAssetVersioning
 * Description: Improve asset version control by embedding the version number into the URL instead of as an optional query parameter.
 * Version:     1.0.0
 * Author:      Jeff Cleverley
 * Author URI:  https://jeffcleverley.com
 * Text Domain: better-asset-versioning
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

namespace Deftly\BetterAssetVersioning;

if ( ! defined ( 'ABSPATH' ) ) {
	exit( 'Cheatin&#8217; uh?' );
}

add_action ( 'wp_enqueue_scripts', __NAMESPACE__ . '\launch', 9999 );
/**
 * Launch the plugin
 *
 * @since 1.0.0
 *
 * @return void
 */
function launch() {
	$config = require ( 'config/url-converter.php' );
	if ( $config['is_enabled'] ) {
		new URLConverter( $config );
	}
}

function load_dependencies() {
	$files      = array(
		'src/support/asset-helpers.php',
		'src/support/string-helpers.php',
		'src/URLConverter.php',
	);
	$plugin_dir = trailingslashit ( __DIR__ );

	foreach ( $files as $filename ) {
		require_once ( $plugin_dir . $filename );
	}
}

load_dependencies ();

