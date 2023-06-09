<?php
add_filter('Traffic_Forecast_load_tracking_script', function() {
	return ! is_404();
});
