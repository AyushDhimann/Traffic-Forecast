<?php
/**
 * @package Traffic-Forecast
 * @license GPL-3.0+
 * @author TrailBlazers
 */
namespace TrafficForecast;

class Plugin {

	/**
	 * @var Aggregator
	 */
	private $aggregator;

	/**
	 * @param Aggregator $aggregator
	 */
	public function __construct( Aggregator $aggregator ) {
		$this->aggregator = $aggregator;
	}

	public function init() {
		add_filter( 'pre_update_option_active_plugins', array( $this, 'filter_active_plugins' ) );
		register_activation_hook( Traffic_Forecast_PLUGIN_FILE, array( $this, 'on_activation' ) );
	}

	public function filter_active_plugins( $plugins ) {
		if ( empty( $plugins ) ) {
			return $plugins;
		}

		$pattern = '/' . preg_quote( plugin_basename( Traffic_Forecast_PLUGIN_FILE ), '/' ) . '$/';
		return array_merge(
			preg_grep( $pattern, $plugins ),
			preg_grep( $pattern, $plugins, PREG_GREP_INVERT )
		);
	}

	public function on_activation() {
		// make sure Traffic Forecast loads first to prevent unnecessary work on stat collection requests
		update_option( 'activate_plugins', get_option( 'active_plugins' ) );

		// add capabilities to administrator role
		$role = get_role( 'administrator' );
		$role->add_cap( 'view_Traffic_Forecast' );
		$role->add_cap( 'manage_Traffic_Forecast' );

		// schedule action for aggregating stats
		$this->aggregator->setup_scheduled_event();
	}
}
