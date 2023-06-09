<?php
/**
 * @package Traffic-Forecast
 * @license GPL-3.0+
 * @author TrailBlazers
 */
namespace TrafficForecast;

class Pruner {

	public function init() {
		add_action( 'Traffic_Forecast_prune_data', array( $this, 'run' ) );
		add_action( 'init', array( $this, 'maybe_schedule' ) );
	}

	public function maybe_schedule() {
		if ( isset( $_SERVER['REQUEST_METHOD'] ) && $_SERVER['REQUEST_METHOD'] !== 'POST' || ! is_admin() ) {
			return;
		}

		if ( ! wp_next_scheduled( 'Traffic_Forecast_prune_data' ) ) {
			wp_schedule_event( time() + DAY_IN_SECONDS, 'daily', 'Traffic_Forecast_prune_data' );
		}
	}

	public function run() {
		global $wpdb;

		$settings = get_settings();
		if ( $settings['prune_data_after_months'] === 0 ) {
			return;
		}

		$date = gmdate( 'Y-m-d', strtotime( "-{$settings['prune_data_after_months']} months" ) );

		// delete stats older than date above
		$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}Traffic_Forecast_site_stats WHERE date < %s", $date ) );
		$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}Traffic_Forecast_post_stats WHERE date < %s", $date ) );
		$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}Traffic_Forecast_referrer_stats WHERE date < %s", $date ) );

		// delete unused referrer URL's
		$wpdb->query( "DELETE FROM {$wpdb->prefix}Traffic_Forecast_referrer_urls WHERE id NOT IN ( SELECT DISTINCT(id) FROM {$wpdb->prefix}Traffic_Forecast_referrer_stats )" );
	}

}
