<?php
/**
 * Upgrade Screen
 *
 * @package     GPR
 * @subpackage  Inc/Upgrades
 * @copyright   Copyright (c) 2016, WordImpress
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.3
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render Upgrades Screen
 *
 * @since 1.3
 * @return void
 */
function gpr_upgrades_screen() {
	$action = isset( $_GET['gpr-upgrade'] ) ? sanitize_text_field( $_GET['gpr-upgrade'] ) : '';
	$step   = isset( $_GET['step'] ) ? absint( $_GET['step'] ) : 1;
	$total  = isset( $_GET['total'] ) ? absint( $_GET['total'] ) : false;
	$custom = isset( $_GET['custom'] ) ? absint( $_GET['custom'] ) : 0;
	$number = isset( $_GET['number'] ) ? absint( $_GET['number'] ) : 100;
	$steps  = round( ( $total / $number ), 0 );

	$doing_upgrade_args = array(
		'page'        => 'gpr-upgrades',
		'gpr-upgrade' => $action,
		'step'        => $step,
		'total'       => $total,
		'custom'      => $custom,
		'steps'       => $steps
	);
	update_option( 'gpr_doing_upgrade', $doing_upgrade_args );
	if ( $step > $steps ) {
		// Prevent a weird case where the estimate was off. Usually only a couple.
		$steps = $step;
	}
	?>
	<div class="wrap">
		<h2><?php _e( 'Google Places Review - Upgrade', 'google-places-reviews' ); ?></h2>

		<?php if ( ! empty( $action ) ) : ?>

			<div id="gpr-upgrade-status">
				<p><?php _e( 'The upgrade process has started, please be patient. This could take several minutes. You will be automatically redirected when the upgrade is finished.', 'google-places-reviews' ); ?></p>

				<?php if ( ! empty( $total ) ) : ?>
					<p>
						<strong><?php printf( __( 'Step %d of approximately %d running', 'google-places-reviews' ), $step, $steps ); ?></strong>
					</p>
				<?php endif; ?>
			</div>
			<script type="text/javascript">
				setTimeout( function () {
					document.location.href = "index.php?gpr_action=<?php echo $action; ?>&step=<?php echo $step; ?>&total=<?php echo $total; ?>&custom=<?php echo $custom; ?>";
				}, 250 );
			</script>

		<?php else : ?>

			<div id="gpr-upgrade-status" class="updated">
				<p>
					<?php _e( 'The upgrade process has started, please be patient. This could take several minutes. You will be automatically redirected when the upgrade is finished.', 'google-places-reviews' ); ?>
					<img src="<?php echo GPR_PLUGIN_URL . '/assets/images/loading.gif'; ?>" id="gpr-upgrade-loader" />
				</p>
			</div>
			<script type="text/javascript">
				jQuery( document ).ready( function () {
					// Trigger upgrades on page load
					var data = {action: 'gpr_trigger_upgrades'};
					var el_upgrade_status = jQuery( '#gpr-upgrade-status' );

					jQuery.post( ajaxurl, data, function ( response ) {

						if ( response == 'complete' ) {

							el_upgrade_status.hide();
							el_upgrade_status.after( '<div class="updated"><p>Yippee! The upgrade process has completed successfully. Please check your widgets to ensure they appear as you expect. You will now be redirected back to your previous page.</p></div>' );

							//Send user back to prev page
							setTimeout(function(){history.back();}, 4000);

						}
					} );
				} );
			</script>

		<?php endif; ?>

	</div>
<?php
}
