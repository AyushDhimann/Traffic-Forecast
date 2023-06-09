<?php
/**
 * Perform the necessary steps to completely uninstall Traffic Forecast
 */

// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) die;

// delete wp-options
delete_option("Traffic_Forecast_settings");
delete_option("Traffic_Forecast_version");
delete_option("Traffic_Forecast_use_custom_endpoint");
delete_option("Traffic_Forecast_realtime_pageview_count");

// drop Traffic tables
global $wpdb;
$wpdb->query(
    "DROP TABLE IF EXISTS
    {$wpdb->prefix}Traffic_Forecast_site_stats,
    {$wpdb->prefix}Traffic_Forecast_post_stats,
    {$wpdb->prefix}Traffic_Forecast_referrer_stats,
    {$wpdb->prefix}Traffic_Forecast_referrer_urls"
);

// delete custom endpoint file
if (file_exists(ABSPATH . '/Traffic-Forecast-collect.php')) {
	unlink(ABSPATH . '/Traffic-Forecast-collect.php');
}
