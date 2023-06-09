<?php

defined( 'ABSPATH' ) or exit;

global $wpdb;

$wpdb->query( "ALTER TABLE {$wpdb->prefix}Traffic_Forecast_site_stats CONVERT TO CHARACTER SET ascii;" );
$wpdb->query( "ALTER TABLE {$wpdb->prefix}Traffic_Forecast_post_stats CONVERT TO CHARACTER SET ascii;" );
$wpdb->query( "ALTER TABLE {$wpdb->prefix}Traffic_Forecast_referrer_stats CONVERT TO CHARACTER SET ascii;" );
$wpdb->query( "ALTER TABLE {$wpdb->prefix}Traffic_Forecast_referrer_urls CONVERT TO CHARACTER SET ascii;" );
