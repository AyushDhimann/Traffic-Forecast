<?php
/**
 * @package Traffic-Forecast
 * @license GPL-3.0+
 * @author TrailBlazers
 */
namespace TrafficForecast;

use WP_CLI;

class Command {

	/**
	 * Aggregates stats from the pageview buffer file into permanent storage
	 *
	 * ## EXAMPLES
	 *
	 *     wp Traffic-Forecast aggregate
	 */
	function aggregate( $args, $assoc_args ) {
		$aggregator = new Aggregator();
		$aggregator->aggregate();
		WP_CLI::success( 'Stats aggregated.' );
	}
}
