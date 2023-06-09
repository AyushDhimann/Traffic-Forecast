<?php

defined( 'ABSPATH' ) or exit;

$role = get_role( 'administrator' );
if ( $role ) {
	$role->add_cap( 'view_Traffic_Forecast' );
	$role->add_cap( 'manage_Traffic_Forecast' );
}
