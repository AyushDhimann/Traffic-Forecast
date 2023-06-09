<?php defined( 'ABSPATH' ) or exit; ?>
<div class="wrap" id="Traffic-Forecast-admin">

	<?php
	if ( false === $is_cron_event_working ) {
		echo '<div class="notice notice-warning inline Traffic-Forecast-cron-warning"><p>';
		echo esc_html__( 'There seems to be an issue with your site\'s WP Cron configuration that prevents Traffic Forecast from automatically processing your statistics.', 'Traffic-Forecast' );
		echo ' ';
		echo esc_html__( 'If you\'re not sure what this is about, please ask your webhost to look into this.', 'Traffic-Forecast' );
		echo '</p></div>';
	}

	if ( false === $is_buffer_dir_writable ) {
		echo  '<div class="notice notice-warning inline"><p>';
		echo wp_kses( sprintf( __( 'Traffic Forecast is unable to write to the <code>%s</code> directory. Please update the file permissions so that your web server can write to it.', 'Traffic-Forecast' ), $buffer_dirname ), array( 'code' => array() ) );
		echo '</p></div>';
	}
	?>

	<noscript>
		<?php echo esc_html__( 'Please enable JavaScript for this page to work.', 'Traffic-Forecast' ); ?>
	</noscript>

	<div id="Traffic-Forecast-mount"></div>
</div>
