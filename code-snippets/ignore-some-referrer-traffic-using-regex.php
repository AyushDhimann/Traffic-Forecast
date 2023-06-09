<?php

// If all you need is to ignore data from a certain domain
// Use https://github.com/Trailblazers/Traffic-Forecast/blob/master/code-snippets/add-domains-to-referrer-blocklist.php instead.

// This filter is only for more advanced filtering, like requiring a regex.
add_filter( 'Traffic_Forecast_ignore_referrer_url', function( $url ) {
	if ( preg_match( '/spambot(.+)/', $url ) ) {
		return true;
	}

	// Returning false instructs Traffic Forecast to not ignore this URL
	return false;
});
