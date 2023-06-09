<?php
defined( 'ABSPATH' ) or exit;

global $wpdb;

$wpdb->query( "ALTER TABLE {$wpdb->prefix}Traffic_Forecast_referrer_urls MODIFY id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT" );
