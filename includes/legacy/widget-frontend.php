<?php
/**
 *  Widget Frontend Display
 *
 * Responsible for the frontend display of the legacy widget.
 * @since      : 1.0
 */

$website = $response['result']['url'] ?? '';
if ( empty( $website ) ) {
    //use website link if for some reason G+ page not in response
    $website = $response['result']['website'] ?? '';
}
$name          = $response['result']['name'] ?? esc_html__( 'Sorry, this business does not have a proper Place ID set.', 'google-places-reviews' );
$ratings_count = isset( $response['result']['user_ratings_total'] ) ? intval( $response['result']['user_ratings_total'] ) : 0;
$place_avatar  = $response['place_avatar'] ?? "url('" . GPR_PLUGIN_URL . '/dist/images/default-img.png' . "')";
?>
    <div id="gpr_widget" class="gpr-<?php echo sanitize_title( esc_attr( $widget_style ) ); ?> gpr-widget-inner">
        <?php
        //Business Information
        if ( ! $hide_header ) { ?>
            <div class="gpr-business-header gpr-clearfix">
                <div class="gpr-business-avatar"
                     style="background-image: url('<?php esc_attr_e( $place_avatar ); ?>');"></div>
                <div class="gpr-header-content-wrap gpr-clearfix">
					<span class="gpr-business-name">
                        <a href="<?php esc_attr_e( $website ); ?>"
                           title="<?php esc_attr_e( $name ); ?>"
                           target="<?php esc_attr_e( $target_blank ); ?>"
                           rel="<?php esc_attr_e( $no_follow ); ?>"><span><?php esc_html_e( $name ); ?></span></a></span>
                    <?php
                    //Overall rating for biz display:
                    $overall_rating = $response['result']['rating'] ?? '';
                    if ( $overall_rating ) {
                        $this->get_star_rating( $overall_rating, null, $hide_out_of_rating, $hide_google_image );
                    } //No rating for this biz yet:
                    else { ?>
                        <span class="no-reviews-header"><?php
                            $googleplus_page = $response['result']['url'] ?? '';
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
                $review_limit = $review_limit ?? 3;

                //Loop Google Places reviews
                foreach ( $response['gpr_reviews'] as $review ) {

                    //Set review vars
                    $author_name    = $review['author_name'];
                    $author_url     = $review['author_url'] ?? '';
                    $overall_rating = $review['rating'];
                    $review_text    = $review['text'];
                    $time           = $review['time'];
                    $avatar         = $review['avatar'] ?? GPR_PLUGIN_URL . '/assets/images/mystery-man.png';
                    $review_filter  = $review_filter ?? '';
                    $counter ++;

                    //Review filter set OR count limit reached?
                    if ( $overall_rating >= $review_filter && $counter <= $review_limit ) {
                        ?>

                        <div class="gpr-review gpr-review-<?php esc_attr_e( $counter ); ?>">

                            <div class="gpr-review-header gpr-clearfix">
                                <div class="gpr-review-avatar">
                                    <img src="<?php esc_attr_e( $avatar ); ?>"
                                         alt="<?php esc_attr_e( $author_name ); ?>"
                                         title="<?php esc_attr_e( $author_name ); ?>" />
                                </div>
                                <div class="gpr-review-info gpr-clearfix">
									<span class="grp-reviewer-name">
										<?php if ( ! empty( $author_url ) ) { ?>
                                            <a href="<?php esc_attr_e( $author_url ); ?>"
                                               title="<?php esc_attr_e( 'View this profile.', 'google-places-reviews' ); ?>"
                                               target="<?php esc_attr_e( $target_blank ); ?>"
                                               rel="<?php esc_attr_e( $no_follow ); ?>"><span><?php esc_html_e( $author_name ); ?></span></a>
                                        <?php } else { ?>
                                            <?php esc_html_e( $author_name ); ?>
                                        <?php } ?>
									</span>
                                    <?php $this->get_star_rating( $overall_rating, $time, $hide_out_of_rating, false ); ?>
                                </div>
                            </div>
                            <div class="gpr-review-content">
                                <?php echo wpautop( esc_html( $review_text ) ); ?>
                            </div>
                        </div><!--/.gpr-review -->

                    <?php } //endif review filter ?>

                <?php } //end review loop	?>

                <?php
                $googleplus_page = $response['result']['url'] ?? '';
                if ( $ratings_count > 5 && ! empty( $googleplus_page ) ) { ?>
                    <div class="gpr-read-all-reviews">
                        <a href="<?php echo esc_url( $googleplus_page ) ?>"
                           target="<?php esc_attr_e( $target_blank ); ?>"
                           rel="<?php esc_attr_e( $no_follow ); ?>"
                        ><?php printf( esc_html__( 'Read All %d Reviews', 'google-places-reviews' ), $ratings_count ); ?></a>
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
                    $googleplus_page = $response['result']['url'] ?? '';
                    echo sprintf( __( 'There are no reviews yet for this business. <a href="%1$s" class="leave-review" target="_blank"> Be the first to review</a>', 'google-places-reviews' ), esc_url( $googleplus_page ) ); ?></p>
            </div>
        <?php } ?>


    </div>


<?php
//after widget
echo ! empty( $after_widget ) ? wp_kses_post( $after_widget ) : '</div>';
