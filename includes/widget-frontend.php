<?php
/**
 *  Widget Frontend Display
 *
 * Responsible for the frontend display of the legacy widget.
 * @since      : 1.0
 */

//Handle website link
//use g+ page

$website = isset( $response['result']['url'] ) ? $response['result']['url'] : '';
if ( ! isset( $response['result']['url'] ) || empty( $response['result']['url'] ) ) {
	//use website link if for some reason G+ page not in response
	$website = isset( $response['result']['website'] ) ? $response['result']['website'] : '';
}
$name = isset( $response['result']['name'] ) ? $response['result']['name'] : __( 'Sorry, this business does not have a proper Place ID set.', 'google-places-reviews' );

$ratings_count = isset( $response['result']['user_ratings_total'] ) ? intval( $response['result']['user_ratings_total'] ) : 0;

$place_avatar = isset( $response['place_avatar'] ) ? $response['place_avatar'] : "url('" . GPR_PLUGIN_URL . '/dist/images/default-img.png' . "')";

?>

	<div id="gpr_widget" class="gpr-<?php echo sanitize_title( $widget_style ); ?> gpr-widget-inner">

		<?php

		//Business Information
		if ( ! boolval( $hide_header ) ) {
			?>

			<div class="gpr-business-header gpr-clearfix">
				<div class="gpr-business-avatar" style="background-image: url('<?php echo $place_avatar; ?>');"></div>

				<div class="gpr-header-content-wrap gpr-clearfix">
					<span class="gpr-business-name"><a href="<?php echo $website; ?>"
													   title="<?php echo $name; ?>" <?php echo $target_blank . $no_follow; ?>><span><?php echo $name; ?></span></a></span>

					<?php
					//Overall rating for biz display:
					$overall_rating = isset( $response['result']['rating'] ) ? $response['result']['rating'] : '';
					if ( $overall_rating ) {
						echo $this->get_star_rating( $overall_rating, null, $hide_out_of_rating, $hide_google_image );
					} //No rating for this biz yet:
					else {
						?>

						<span class="no-reviews-header"><?php
							$googleplus_page = isset( $response['result']['url'] ) ? $response['result']['url'] : '';
							echo sprintf( __( '<a href="%1$s" class="leave-review" target="_blank" class="new-window">Write a review</a>', 'google-places-reviews' ), esc_url( $googleplus_page ) ); ?></span>

					<?php } ?>

				</div>
			</div>

		<?php } ?>


		<?php
		if ( isset( $response['gpr_reviews'] ) && ! empty( $response['gpr_reviews'] ) ) {
			?>

			<div class="gpr-reviews-wrap">
				<?php
				$counter      = 0;
				$review_limit = isset( $review_limit ) ? $review_limit : 3;

				//Loop Google Places reviews
				foreach ( $response['gpr_reviews'] as $review ) {

					//Set review vars
					$author_name    = $review['author_name'];
					$author_url     = isset( $review['author_url'] ) ? $review['author_url'] : '';
					$overall_rating = $review['rating'];
					$review_text    = $review['text'];
					$time           = $review['time'];
					$avatar         = isset( $review['avatar'] ) ? $review['avatar'] : GPR_PLUGIN_URL . '/assets/images/mystery-man.png';
					$review_filter  = isset( $review_filter ) ? $review_filter : '';
					$counter ++;


					//Review filter set OR count limit reached?
					if ( $overall_rating >= $review_filter && $counter <= $review_limit ) {
						?>

						<div class="gpr-review gpr-review-<?php echo $counter; ?>">

							<div class="gpr-review-header gpr-clearfix">
								<div class="gpr-review-avatar">
									<img src="<?php echo $avatar; ?>" alt="<?php echo $author_name; ?>"
										 title="<?php echo $author_name; ?>"/>
								</div>
								<div class="gpr-review-info gpr-clearfix">
									<span class="grp-reviewer-name">
										<?php if ( ! empty( $author_url ) ) { ?>
											<a href="<?php echo $author_url; ?>"
											   title="<?php _e( 'View this profile.', 'google-places-reviews' ); ?>" <?php echo $target_blank . $no_follow; ?>><span><?php echo $author_name; ?></span></a>
										<?php } else { ?>
											<?php echo $author_name; ?>
										<?php } ?>
									</span>
									<?php echo $this->get_star_rating( $overall_rating, $time, $hide_out_of_rating, false ); ?>
								</div>
							</div>


							<div class="gpr-review-content">
								<?php echo wpautop( $review_text ); ?>
							</div>

						</div><!--/.gpr-review -->

					<?php } //endif review filter ?>

				<?php } //end review loop	?>

				<?php
				$googleplus_page = isset( $response['result']['url'] ) ? $response['result']['url'] : '';
				if ( $ratings_count > 5 && ! empty( $googleplus_page ) ) {

					?>
					<div class="gpr-read-all-reviews">
						<a href="<?php echo esc_url( $googleplus_page ) ?>" <?php echo $target_blank . $no_follow; ?>><?php printf( esc_html__( 'Read All %d Reviews', 'google-places-reviews' ), $ratings_count ); ?></a>
					</div>
				<?php } // endif read all reviews button ?>

			</div><!--/.gpr-reviews -->

			<?php
		} //end review if
		else {
			//No Reviews for this location
			?>

			<div class="gpr-no-reviews-wrap">
				<p class="no-reviews"><?php
					$googleplus_page = isset( $response['result']['url'] ) ? $response['result']['url'] : '';
					echo sprintf( __( 'There are no reviews yet for this business. <a href="%1$s" class="leave-review" target="_blank">Be the first to review</a>', 'google-places-reviews' ), esc_url( $googleplus_page ) ); ?></p>

			</div>

		<?php } ?>


	</div>


<?php
//after widget
echo ! empty( $after_widget ) ? $after_widget : '</div>';
