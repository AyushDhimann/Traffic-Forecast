<?php

add_filter( 'Traffic_Forecast_referrer_blocklist', function() {
	return array(
		'search.myway.com',
		'bad-website.com',
	);
});
