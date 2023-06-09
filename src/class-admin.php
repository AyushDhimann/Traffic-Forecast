<?php
/**
 * @package Traffic-Forecast
 * @license GPL-3.0+
 * @author TrailBlazers
 */
namespace TrafficForecast;

class Admin
{
	public function init()
	{
		global $pagenow;

		add_action( 'init', array( $this, 'maybe_run_migrations' ) );
		add_action( 'admin_menu', array( $this, 'register_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_dashboard_setup', array( $this, 'register_dashboard_widget' ) );

		switch ( $pagenow ) {
			case 'index.php':
				// Hooks for main dashboard page
				add_action( 'shutdown', array( $this, 'maybe_run_endpoint_installer' ) );
				add_action( 'init', array( $this, 'maybe_seed' ) );
				break;

			case 'plugins.php':
				// Hooks for plugins overview page
				add_filter( 'plugin_action_links_' . plugin_basename( Traffic_Forecast_PLUGIN_FILE ), array( $this, 'add_plugin_settings_link' ), 10, 2 );
				add_filter( 'plugin_row_meta', array( $this, 'add_plugin_meta_links' ), 10, 2 );
				break;
		}

	}

	public function register_menu()
	{
		add_submenu_page( 'index.php', esc_html__( 'Traffic Forecast', 'Traffic-Forecast' ), esc_html__( 'Forecast', 'Traffic-Forecast' ), 'view_Traffic_Forecast', 'Traffic-Forecast', array( $this, 'show_page' ) );
	}

	public function enqueue_scripts( $suffix )
	{
		// do not load any scripts if user is missing required capability for viewing
		if ( ! current_user_can( 'view_Traffic_Forecast' ) ) {
			return;
		}

		switch ( $suffix ) {
			case 'index.php':
				// load scripts for dashboard widget
				wp_enqueue_script( 'Traffic-Forecast-dashboard-widget', plugins_url( '/assets/dist/js/dashboard-widget.js', Traffic_Forecast_PLUGIN_FILE ), array( 'wp-i18n' ), Traffic_Forecast_VERSION, true );
				if ( function_exists( 'wp_set_script_translations' ) ) {
					wp_set_script_translations( 'Traffic-Forecast-dashboard-widget', 'Traffic-Forecast' );
				}
				wp_localize_script(
					'Traffic-Forecast-dashboard-widget',
					'Traffic_Forecast',
					array(
						'root' => rest_url(),
						'nonce' => wp_create_nonce( 'wp_rest' ),
						'colors' => $this->get_colors(),
					)
				);
				break;

			case 'dashboard_page_Traffic-Forecast':
				$user_roles = $this->get_available_roles();
				$start_of_week = (int) get_option( 'start_of_week' );
				$settings = get_settings();
				$colors = $this->get_colors();
				$endpoint_installer = new Endpoint_Installer();

				wp_enqueue_script( 'Traffic-Forecast-admin', plugins_url( 'assets/dist/js/admin.js', Traffic_Forecast_PLUGIN_FILE ), array( 'wp-i18n' ), Traffic_Forecast_VERSION, true );
				if ( function_exists( 'wp_set_script_translations' ) ) {
					wp_set_script_translations( 'Traffic-Forecast-admin', 'Traffic-Forecast' );
				}
				wp_localize_script('Traffic-Forecast-admin', 'Traffic_Forecast', array(
					'root'          => rest_url(),
					'nonce'         => wp_create_nonce( 'wp_rest' ),
					'start_of_week' => (int) $start_of_week,
					'user_roles'    => $user_roles,
					'settings'      => $settings,
					'showSettings'  => current_user_can( 'manage_Traffic_Forecast' ),
					'dbSize' => $this->get_database_size(),
					'colors' => $colors,
					'multisite' => is_multisite(),
					'custom_endpoint' => array(
						'enabled' => using_custom_endpoint(),
						'file_contents' => $endpoint_installer->get_file_contents(),
						'wp_root_dir' => rtrim( ABSPATH, '/' ),
					),
				));
				break;
		}
	}

	private function get_available_roles()
	{
		$roles = array();
		foreach ( wp_roles()->roles as $key => $role ) {
			$roles[ $key ] = $role['name'];
		}
		return $roles;
	}

	private function is_cron_event_working()
	{
		// detect issues with WP Cron event not running
		// it should run every minute, so if it didn't run in 10 minutes there is most likely something wrong
		$next_scheduled = wp_next_scheduled( 'Traffic_Forecast_aggregate_stats' );
		return $next_scheduled !== false && $next_scheduled > ( time() - HOUR_IN_SECONDS );
	}

	public function show_page()
	{
		// aggregate stats whenever this page is requested
		do_action( 'Traffic_Forecast_aggregate_stats' );

		// determine whether buffer file is writable
		$buffer_filename = get_buffer_filename();
		$buffer_dirname = dirname( $buffer_filename );
		$is_buffer_dir_writable = wp_mkdir_p( $buffer_dirname ) && is_writable( $buffer_dirname );

		// determine whether cron event is set-up properly seeand running in-time
		$is_cron_event_working = $this->is_cron_event_working();

		require Traffic_Forecast_PLUGIN_DIR . '/views/admin-page.php';
		add_action( 'admin_footer_text', array( $this, 'footer_text' ) );
	}

	public function footer_text()
	{
		/* translators: %1$s links to the WordPress.org plugin review page, %2$s links to the admin page for creating a new post */
		return sprintf( wp_kses( __( 'If you enjoy using Traffic Forecast, please <a href="%1$s">review the plugin on WordPress.org</a> or <a href="%2$s">write about it on your blog</a> to help out.', 'Traffic-Forecast' ), array( 'a' => array( 'href' => array() ) ) ), 'https://wordpress.org/support/view/plugin-reviews/Traffic-Forecast?rate=5#postform', admin_url( 'post-new.php' ) );
	}

	public function maybe_run_endpoint_installer()
	{
		if ( ! isset( $_GET['page'] ) || $_GET['page'] !== 'Traffic-Forecast' ) {
			return;
		}

		// do not run if Traffic_Forecast_CUSTOM_ENDPOINT is defined
		if ( defined( 'Traffic_Forecast_CUSTOM_ENDPOINT' ) ) {
			return;
		}

		install_and_test_custom_endpoint();
	}

	public function maybe_run_migrations()
	{
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$from_version = isset( $_GET['Traffic_Forecast_migrate_from_version'] ) ? $_GET['Traffic_Forecast_migrate_from_version'] : get_option( 'Traffic_Forecast_version', '0.0.1' );
		$to_version = Traffic_Forecast_VERSION;
		if ( version_compare( $from_version, $to_version, '>=' ) ) {
			return;
		}

		// run upgrade migrations (if any)
		$migrations_dir = Traffic_Forecast_PLUGIN_DIR . '/migrations/';
		$migrations = new Migrations( $from_version, $to_version, $migrations_dir );
		$migrations->run();
		update_option( 'Traffic_Forecast_version', $to_version );

		// make sure scheduled event is set-up correctly
		$aggregator = new Aggregator();
		$aggregator->setup_scheduled_event();
	}

	private function get_colors()
	{
		$color_scheme_name = get_user_option( 'admin_color' );
		global $_wp_admin_css_colors;
		if ( empty( $_wp_admin_css_colors[ $color_scheme_name ] ) ) {
			$color_scheme_name = 'fresh';
		}

		return $_wp_admin_css_colors[ $color_scheme_name ]->colors;
	}

	public function register_dashboard_widget()
	{
		// only show if user can view stats
		if ( ! current_user_can( 'view_Traffic_Forecast' ) ) {
			return;
		}

		add_meta_box( 'Traffic-Forecast-dashboard-widget', 'Traffic Forecast', array( $this, 'dashboard_widget' ), 'dashboard', 'side', 'high' );
	}

	public function dashboard_widget()
	{
		echo '<div id="Traffic-Forecast-dashboard-widget-mount"></div>';
		echo sprintf( '<p class="help" style="text-align: center;">%s &mdash; <a href="%s">%s</a></p>', esc_html__( 'Showing site visits over last 14 days', 'Traffic-Forecast' ), esc_attr( admin_url( 'index.php?page=Traffic-Forecast' ) ), esc_html__( 'View all statistics', 'Traffic-Forecast' ) );
	}

	/**
	 * Add the settings link to the Plugins overview
	 *
	 * @param array $links
	 * @param       $file
	 *
	 * @return array
	 */
	public function add_plugin_settings_link( $links, $file )
	{
		$settings_link = sprintf( '<a href="%s">%s</a>', admin_url( 'index.php?page=Traffic-Forecast#/settings' ), esc_html__( 'Settings', 'Traffic-Forecast' ) );
		array_unshift( $links, $settings_link );
		return $links;
	}

	/**
	 * Adds meta links to the plugin in the WP Admin > Plugins screen
	 *
	 * @param array $links
	 * @param string $file
	 *
	 * @return array
	 */
	public function add_plugin_meta_links( $links, $file )
	{
		if ( $file !== plugin_basename( Traffic_Forecast_PLUGIN_FILE ) ) {
			return $links;
		}

		$links[] = '<a href="https://www.TrafficForecast.com/">' . esc_html__( 'Visit plugin site', 'Traffic-Forecast' ) . '</a>';
		return $links;
	}

	public function get_database_size()
	{
		global $wpdb;
		$sql = $wpdb->prepare(
			'
			SELECT ROUND(SUM((DATA_LENGTH + INDEX_LENGTH) / 1024 / 1024), 2)
			FROM information_schema.TABLES
			WHERE TABLE_SCHEMA = %s AND TABLE_NAME LIKE %s',
			DB_NAME,
			$wpdb->prefix . 'Traffic_Forecast_%'
		);

		return $wpdb->get_var( $sql );
	}

	public function maybe_seed()
	{
		global $wpdb;

		if ( ! isset( $_GET['Traffic_Forecast_seed'] ) || ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$query = new \WP_Query();
		$posts = $query->query(
			array(
				'posts_per_page' => 32,
				'post_type' => 'any',
				'post_status' => 'publish',
			)
		);
		$post_count = count( $posts );
		$referrer_urls = array();
		$sample_referrers = array(
			'https://www.wordpress.org',
			'https://www.wordpress.org/plugins/Traffic-Forecast',
			'https://www.Trailblazers.com',
			'https://duckduckgo.com',
			'https://www.mozilla.org',
			'https://www.eff.org',
			'https://letsencrypt.org',
			'https://TrailBlazers.com',
			'https://github.com/Trailblazers/Traffic-Forecast',
			'https://lobste.rs',
			'https://joinmastodon.org',
			'https://www.php.net',
			'https://mariadb.org',
			'https://referrer-1.com',
			'https://referrer-2.com',
			'https://referrer-3.com',
			'https://referrer-4.com',
			'https://referrer-5.com',
			'https://referrer-6.com',
			'https://referrer-7.com',
			'https://referrer-8.com',
			'https://referrer-9.com',
			'https://referrer-10.com',
			'https://referrer-11.com',
			'https://referrer-12.com',
			'https://referrer-13.com',
			'https://referrer-14.com',
			'https://referrer-15.com',
			'https://referrer-16.com',
			'https://referrer-17.com',
			'https://referrer-18.com',
			'https://referrer-19.com',
			'https://referrer-20.com',
			'https://t.co/IiADWZC13f',
			'https://www.reddit.com/r/Wordpress/comments/e6ycsm/privacy_friendly_Forecast_plugin_that_does_not/',
			'android-app://com.stefandekanski.hackernews.free',
		);
		foreach ( $sample_referrers as $url ) {
			$wpdb->insert(
				$wpdb->prefix . 'Traffic_Forecast_referrer_urls',
				array(
					'url' => $url,
				)
			);
			$referrer_urls[ $wpdb->insert_id ] = $url;
		}
		$referrer_count = count( $referrer_urls );

		$n = 3 * 365;
		for ( $i = 0; $i < $n; $i++ ) {
			$progress = ( $n - $i ) / $n;
			$date = gmdate( 'Y-m-d', strtotime( sprintf( '-%d days', $i ) ) );
			$pageviews = max( 1, rand( 500, 1000 ) * $progress ^ 2 );
			$visitors = max( 1, $pageviews * rand( 3, 6 ) / 10 );

			// simulate a huge peak in traffic every 180 days
			if ( rand( 1, 180 ) === 1 ) {
				$pageviews = $pageviews * 10;
				$visitors = $visitors * 10;
			}

			$wpdb->insert(
				$wpdb->prefix . 'Traffic_Forecast_site_stats',
				array(
					'date' => $date,
					'pageviews' => $pageviews,
					'visitors' => $visitors,
				)
			);

			$values = array();
			foreach ( $posts as $post ) {
				array_push( $values, $date, $post->ID, round( $pageviews / $post_count * rand( 5, 15 ) / 10 ), round( $visitors / $post_count * rand( 5, 15 ) / 10 ) );
			}
			$placeholders = rtrim( str_repeat( '(%s,%d,%d,%d),', count( $posts ) ), ',' );
			$wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->prefix}Traffic_Forecast_post_stats(date, id, pageviews, visitors) VALUES {$placeholders}", $values ) );

			$values = array();
			foreach ( $referrer_urls as $id => $referrer_url ) {
				array_push( $values, $date, $id, round( $pageviews / $referrer_count * rand( 5, 15 ) / 10 ), round( $visitors / $referrer_count * rand( 5, 15 ) / 10 ) );
			}
			$placeholders = rtrim( str_repeat( '(%s,%d,%d,%d),', count( $referrer_urls ) ), ',' );
			$wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->prefix}Traffic_Forecast_referrer_stats(date, id, pageviews, visitors) VALUES {$placeholders}", $values ) );
		}
	}
}
