<?php
/**
 *  Handles Upgrade Functionality
 *
 * @description : This is the Reference ID that Place ID
 * @copyright   Copyright (c) 2018, Impress.org
 * @since       : 1.3
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Display Upgrade Notices
 *
 * @since 1.3
 * @return void
 */
function gpr_show_upgrade_notices() {

	// Uncomment for testing ONLY - Never leave uncommented unless testing:
	// delete_option( 'gpr_refid_upgraded' );
	// Don't show notices on the upgrades page
	if (
		isset( $_GET['page'] )
		&& 'gpr-upgrades' === $_GET['page']
	) {
		return;
	}

	$gpr_widget_version = get_option( 'gpr_widget_version' );

	if ( ! $gpr_widget_version ) {
		// 1.3 is the first version to use this option so we must add it
		$gpr_widget_version = '1.3';
	}

	$gpr_widget_version = preg_replace( '/[^0-9.].*/', '', $gpr_widget_version );
	$upgraded_already = get_option( 'gpr_refid_upgraded' );

	if (
		version_compare( $gpr_widget_version, '1.3', '<=' )
		&& ! $upgraded_already
		&& is_active_widget( false, false, 'gpr_widget', true )
	) {
		printf(
			'<div class="updated"><p><strong>Reviews Block for Google  Notice:</strong> ' . esc_html__( 'Hey there! We noticed you have active Reviews Block for Google  widget(s). Google has updated their API to use the new Google Places ID rather than previous Reference ID, which will soon be deprecated and eventually go offline. We are being proactive and would like to update your widgets to use the new Places ID for you. Once you upgrade, your widgets should work just fine. If you choose not to upgrade Google will eventually take the old reference ID offline (no date has been given). Please contact %1$1sSupport%2$2s if you have any further questions or issues. %3$3sClick here to upgrade your widgets to use the new Places ID%4$4s.', 'google-places-reviews' ) . '</p></div>',
			'<a href="http://wordpress.org/support/plugin/google-places-reviews" target="_blank">',
			'</a>',
			'<br><br><strong><a href="' . esc_url( admin_url( 'options.php?page=gpr-upgrades' ) ) . '">',
			'</a></strong>'
		);
	}

	$plugin_data = get_plugin_data( GPR_PLUGIN_FILE );
	update_option( 'gpr_widget_version', $plugin_data['Version'] );

}

add_action( 'admin_notices', 'gpr_show_upgrade_notices' );


/**
 * Creates the upgrade page
 *
 * links to global variables
 *
 * @since 1.3
 */
function gpr_add_upgrade_submenu_page() {

	$gpr_upgrades_screen = add_submenu_page( null, __( 'Reviews Block for Google  Upgrades', 'google-places-reviews' ), __( 'Reviews Block for Google  Upgrades', 'google-places-reviews' ), 'activate_plugins', 'gpr-upgrades', 'gpr_upgrades_screen' );

}

add_action( 'admin_menu', 'gpr_add_upgrade_submenu_page', 10 );

/**
 * Triggers all upgrade functions
 *
 * This function is usually triggered via AJAX
 *
 * @since 1.3
 * @return void
 */
function gpr_trigger_upgrades() {

	if ( ! current_user_can( 'activate_plugins' ) ) {
		wp_die( __( 'You do not have permission to do plugin upgrades', 'google-places-reviews' ), __( 'Error', 'google-places-reviews' ), array( 'response' => 403 ) );
	}

	$gpr_widget_version = get_option( 'gpr_widget_version' );

	if ( ! $gpr_widget_version ) {
		// 1.3 is the first version to use this option so we must add it
		$gpr_widget_version = '1.3';
		add_option( 'gpr_widget_version', $gpr_widget_version );
	}

	$plugin_data = get_plugin_data( GPR_PLUGIN_FILE );

	if ( version_compare( $plugin_data['Version'], $gpr_widget_version, '>=' ) && ! get_option( 'gpr_refid_upgraded' ) ) {
		gpr_v13_upgrades();
	}

	update_option( 'gpr_widget_version', $gpr_widget_version );

	if ( DOING_AJAX ) {
		die( 'complete' );
	} // Let AJAX know that the upgrade is complete
}

add_action( 'wp_ajax_gpr_trigger_upgrades', 'gpr_trigger_upgrades' );


/**
 * Upgrade from Google Reference ID to Places ID
 *
 * @since 1.3
 * @uses  WP_Query
 * @return void
 */
function gpr_v13_upgrades() {

	// Upgrade the Reference ID
	$gpr_widget_options = get_option( 'widget_gpr_widget' );
	$plugin_options     = get_option( 'googleplacesreviews_options' );
	$google_api_key     = $plugin_options['google_places_api_key'];

	// Loop through widgets' options
	foreach ( $gpr_widget_options as $key => $widget ) {

		$ref_id   = isset( $widget['reference'] ) ? $widget['reference'] : '';
		$place_id = isset( $widget['place_id'] ) ? $widget['place_id'] : '';

		// If no place AND there's a ref ID proceed
		if ( empty( $place_id ) && ! empty( $ref_id ) ) {

			// Google API for the Google Place ID
			$google_places_url = add_query_arg(
				array(
					'reference' => $ref_id,
					'key'       => $google_api_key,
				),
				'https://maps.googleapis.com/maps/api/place/details/json'
			);

			$response = wp_safe_remote_get(
				$google_places_url,
				array(
					'timeout'   => 15,
					'sslverify' => false,
				)
			);

			// make sure the response came back okay
			if ( is_wp_error( $response ) ) {
				return;
			}

			// decode the license data
			$response = json_decode( $response['body'], true );

			// Place ID is there, now let's update the widget data
			if ( isset( $response['result']['place_id'] ) ) {

				// Add Place ID to GPR widgets options array
				$gpr_widget_options[ $key ]['place_id'] = $response['result']['place_id'];

			}
		}
		// Pause for 3 seconds so we don't overwhelm the Google API with requests
		sleep( 2 );
	}

	// Update our options and GTF out
	update_option( 'gpr_refid_upgraded', 'upgraded' );
	update_option( 'widget_gpr_widget', $gpr_widget_options );

}
