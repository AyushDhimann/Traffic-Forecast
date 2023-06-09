<?php
add_filter('Traffic_Forecast_load_tracking_script', function() {
	// do not load tracking script if URL starts with "/wp-admin/"
	if ( stripos( $_SERVER['REQUEST_URI'], '/wp-admin/' ) === 0 ) {
		return false;
	}

	return true;
});
