<?php

namespace TrafficForecast;

class Endpoint_Installer {
	public function run() {
		update_option( 'Traffic_Forecast_use_custom_endpoint', $this->install_optimized_endpoint_file(), true );
	}

	private function install_optimized_endpoint_file() {
		/* Do nothing if running Multisite (because Multisite has separate uploads directory per site) */
		if ( is_multisite() ) {
			return false;
		}

		/* Do nothing if Traffic_Forecast_CUSTOM_ENDPOINT is defined (means users disabled this feature or is using their own version of it) */
		if ( defined( 'Traffic_Forecast_CUSTOM_ENDPOINT' ) ) {
			return false;
		}

		/* If we made it this far we ideally want to use the custom endpoint file */
		/* Therefore we schedule a recurring health check event to periodically re-attempt and re-test */
		if ( ! wp_next_scheduled( 'Traffic_Forecast_test_custom_endpoint' ) ) {
			wp_schedule_event( time() + HOUR_IN_SECONDS, 'hourly', 'Traffic_Forecast_test_custom_endpoint' );
		}

		/* Attempt to put the file into place if it does not exist already */
		if ( ! file_exists( ABSPATH . '/Traffic-Forecast-collect.php' ) ) {
			$success = file_put_contents( ABSPATH . '/Traffic-Forecast-collect.php', $this->get_file_contents() );
			if ( ! $success ) {
				return false;
			}
		}

		/* Send an HTTP request to the custom endpoint to see if it's working properly */
		$works = $this->test();
		if ( ! $works ) {
			/* Remove the file */
			unlink( ABSPATH . '/Traffic-Forecast-collect.php' );
			return false;
		}

		/* All looks good! Custom endpoint file exists and returns the correct response */
		return true;
	}

	public function get_file_contents() {
		$buffer_filename = get_buffer_filename();
		$functions_filename = Traffic_Forecast_PLUGIN_DIR . '/src/functions.php';
		return <<<EOT
<?php
/**
 * @package Traffic-Forecast
 * @license GPL-3.0+
 * @author TrailBlazers
 *
 * This file acts as an optimized endpoint file for the Traffic Forecast plugin.
 */

// path to pageviews.php file in uploads directory
define('Traffic_Forecast_BUFFER_FILE', '$buffer_filename');

// path to functions.php file in Traffic Forecast plugin directory
require '$functions_filename';

// function call to collect the request data
TrafficForecast\collect_request();
EOT;
	}

	/**
	 * Check for correct HTTP response from custom endpoint file.
	 *
	 * @see collect_request()
	 * @return bool
	 */
	private function test() {
		$tracker_url = site_url( '/Traffic-Forecast-collect.php?nv=1&p=0&up=1&test=1' );
		$response = wp_remote_get( $tracker_url );
		if ( is_wp_error( $response ) ) {
			return false;
		}

		$status = wp_remote_retrieve_response_code( $response );
		$headers = wp_remote_retrieve_headers( $response );
		$body = wp_remote_retrieve_body( $response );
		if ( $status !== 200 || $headers['Content-Type'] !== 'image/gif' || $body !== base64_decode( 'R0lGODlhAQABAIAAAAAAAAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==' ) ) {
			return false;
		}

		return true;
	}
}
