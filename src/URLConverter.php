<?php
/**
 * URL Converter
 *
 * @package     Deftly\BetterAssetVersioning
 * @since       0.0.1
 * @author      Jeff Cleverley
 * @link        https://github.com/JeffCleverley/
 * @license     GNU General Public License 2.0+
 *
 */

namespace Deftly\BetterAssetVersioning;


class URLConverter {

	protected $config;
	protected $local_url = '';

	public function __construct( array $config ) {
		$this->config = $config;
		$this->init_parameters();
		$this->init_events();
	}

	protected function init_parameters() {
		$parsed_site_url = parse_url( home_url() );
		if ( isset( $parsed_site_url['host'] ) ) {
			$this->local_url = $parsed_site_url['host'];
		}
	}

	protected function init_events() {
		add_filter( 'script_loader_src', array( $this, 'run' ), 9999, 2 );
		add_filter( 'style_loader_src', array( $this, 'run' ), 9999, 2 );
	}

	public function run( $asset_url, $handle ){

		if ( is_admin() ) {
			return $asset_url;
		}

		if ( $this->skip_this_asset( $handle ) ) {
			return $asset_url;
		}

		$parsed_url = parse_url( $asset_url );

		if ( ! $this->is_well_formed( $parsed_url ) ){
			return $asset_url;
		}

		if ( ! $this->has_version_query_string( $parsed_url) ) {
			return $asset_url;
		}

		if ( ! $this->is_local_asset( $parsed_url['host'] ) ) {
			return remove_query_arg( 'ver', $asset_url );
		}

		return $this->do_conversion( $asset_url );
	}

	protected function is_well_formed( $parsed_url ) {
		return isset( $parsed_url['host'] );
	}

	protected function has_version_query_string( $parsed_url) {
		if (! isset( $parsed_url['query'] ) ) {
			return false;
		}

		return str_contains_substr( $parsed_url['query'], $this->config['version_query_key'] );
	}

	protected function skip_this_asset( $handle ) {
		return in_array( $handle, $this->config['skip_these_assets'] );
	}

	protected function is_local_asset( $asset_url_host ) {
		return ( $asset_url_host === $this->local_url );
	}

	protected function do_conversion( $asset_url ) {
		return $converted_url = preg_replace(
			'/\.(min.js|js|min.css|css)\?ver=(.+)$/',
			'.$2.$1',
			$asset_url
		);
	}
}