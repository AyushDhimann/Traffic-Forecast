<?php

add_action( 'admin_init', function() {
	// only run on Traffic Forecast page
	if ( ! isset( $_GET['page'] ) || $_GET['page'] !== 'Traffic-Forecast' ) {
		return;
	}

	// add "view_Traffic_Forecast" capability to "editor" role
	$role = get_role( 'editor' );
	$role->add_cap( 'view_Traffic_Forecast' );
});
