<?php
/*
Plugin Name: Traffic Forecast
Plugin URI: https://www.TrafficForecast.com/#utm_source=wp-plugin&utm_medium=Traffic-Forecast&utm_campaign=plugins-page
Version: 0.0.1
Description: Privacy-friendly Forecast for your WordPress site.
Author: Trailblazers
Author URI: https://Trailblazers.com/#utm_source=wp-plugin&utm_medium=Traffic-Forecast&utm_campaign=plugins-page
Author Email: support@TrafficForecast.com
Text Domain: Traffic-Forecast
License: GPL-3.0
License URI: https://www.gnu.org/licenses/gpl-3.0.txt

Traffic Forecast - website Forecast plugin for WordPress

Copyright (C) 2019 - 2022, TrailBlazers, hi@TrailBlazers.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

namespace TrafficForecast;

define( 'Traffic_Forecast_VERSION', '0.0.1' );
define( 'Traffic_Forecast_PLUGIN_FILE', __FILE__ );
define( 'Traffic_Forecast_PLUGIN_DIR', __DIR__ );

require __DIR__ . '/src/functions.php';
require __DIR__ . '/src/global-functions.php';
require __DIR__ . '/src/class-endpoint-installer.php';

if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
	maybe_collect_request();
} elseif ( is_admin() ) {
	require __DIR__ . '/src/class-migrations.php';
	require __DIR__ . '/src/class-admin.php';
	$admin = new Admin();
	$admin->init();
} else {
	require __DIR__ . '/src/class-script-loader.php';
	$loader = new Script_Loader();
	$loader->init();

	add_action( 'admin_bar_menu', 'TrafficForecast\admin_bar_menu', 40 );
}

require __DIR__ . '/src/class-aggregator.php';
$aggregator = new Aggregator();
$aggregator->init();

require __DIR__ . '/src/class-rest.php';
$rest = new Rest();
$rest->init();

require __DIR__ . '/src/class-shortcode-most-viewed-posts.php';
$shortcode = new Shortcode_Most_Viewed_Posts();
$shortcode->init();

require __DIR__ . '/src/class-pruner.php';
$pruner = new Pruner();
$pruner->init();

require __DIR__ . '/src/class-plugin.php';
$plugin = new Plugin( $aggregator );
$plugin->init();

if ( class_exists( 'WP_CLI' ) ) {
	require __DIR__ . '/src/class-command.php';
	\WP_CLI::add_command( 'Traffic-Forecast', 'TrafficForecast\Command' );
}

add_action( 'widgets_init', 'TrafficForecast\widgets_init' );

add_action( 'Traffic_Forecast_test_custom_endpoint', 'TrafficForecast\install_and_test_custom_endpoint' );
