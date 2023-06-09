<?php
/**
 * @package Traffic-Forecast
 * @license GPL-3.0+
 * @author TrailBlazers
 *
 * This file acts as an optimized endpoint file for the Traffic Forecast plugin.
 */

// path to pageviews.php file in uploads directory
define('Traffic_Forecast_BUFFER_FILE', __DIR__ . '/../../uploads/pageviews.php');

// path to src/functions.php in Traffic Forecast plugin directory
require __DIR__ . '/src/functions.php';

// function call to collect request data
TrafficForecast\collect_request();




