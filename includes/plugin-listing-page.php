<?php
/**
 * Plugin listing page code.
 *
 * Contains activation banner and plugin meta links.
 */

/**
 * Plugin activation banner.
 */
function gpr_activation_admin_notice() {

    global $current_user, $pagenow;

    // Check that the user hasn't already clicked to ignore the message
    if (
        'plugins.php' === $pagenow
        && ! get_user_meta( $current_user->ID, 'gpr_activation_ignore_notice' )
    ) : ?>
        <style>
            div.updated.wpbr {
                border-left: 4px solid #3498db;
                background: #FFF;
                margin: 1rem 0 2rem 0;
                -webkit-box-shadow: 0 1px 1px 1px rgba(0, 0, 0, 0.1);
                box-shadow: 0 1px 1px 1px rgba(0, 0, 0, 0.1);
                overflow: hidden;
            }

            div.updated.wpbr header {
                position: relative;
            }

            div.updated.wpbr header h2 {
                display: inline-block;
                margin: 0 0 10px;
            }

            div.updated.wpbr header img.wpbr-logo {
                max-width: 80px;
                display: inline-block;
                margin: 1rem;
                float: left;
            }

            div.updated.wpbr .wpbr-intro-text {
                font-size: 12px;
                margin: 0 0 12px;
            }

            .wpbr-banner-icon {
                width: 20px;
                height: 20px;
                float: left;
                margin: -1px 5px 0 0;
                fill: #0073aa;
            }

            .wpbr-actions {
                margin: 15px 0 15px 1rem;
                padding: 0;
                float: left;
                width: 680px;
            }

            .wpbr-action a {
                text-decoration: none;
            }

            .wpbr-upsell-action a {
                color: #ee4835;
                font-weight: bold;
            }

            .wpbr-upsell-action svg {
                fill: #ee4835;
            }

            .wpbr-action {
                float: left;
                padding: 4px 20px 0 0;
                width: auto;
            }


            /* Dismiss button */
            div.updated.wpbr a {
                outline: none;
            }

            div.updated.wpbr a.dismiss {
                display: block;
                position: absolute;
                left: auto;
                top: 10px;
                right: 0;
                color: #cacaca;
                text-align: center;
            }

            .wpbr a.dismiss:before {
                font-family: 'Dashicons';
                content: "\f153";
                font-size: 20px;
                display: inline-block;
            }

            div.updated.wpbr a.dismiss:hover {
                color: #777;
            }

            @media (max-width: 930px) {
                div.updated.wpbr header img.wpbr-logo {
                    display: none;
                }

                .wpbr-actions {
                    width: auto;
                }

                #mce-EMAIL {
                    width: 150px;
                }

                .wpbr-intro-text br {
                    display: none;
                }
            }


        </style>
        <div class="updated wpbr">
            <header>
                <img src="<?php echo GPR_PLUGIN_URL; ?>/includes/legacy/assets/images/platform-icon-wpbr.png"
                     class="wpbr-logo"/>
                <?php printf( __( '<a href="%1$s" class="dismiss"></a>', 'google-places-reviews' ), '?gpr_nag_ignore=0' ); ?>

                <div class="wpbr-actions">
                    <?php $current_user = wp_get_current_user(); ?>
                    <h2><?php printf( __( 'Welcome to Reviews Block for Google  by <a href="%s" target="_blank">WP Business Reviews</a>', 'google-places-reviews' ), 'https://wpbusinessreviews.com' ); ?></h2>
                    <p class="wpbr-intro-text"><?php esc_html_e( 'Display Google Places Reviews for one or many businesses anywhere on your WordPress site using this powerful block.', 'google-places-reviews' ); ?></p>
                    <div class="wpbr-action">
                        <a href="<?php echo admin_url( 'options-general.php?page=google_places_reviews' ); ?>">
                            <svg class="wpbr-settings-icon wpbr-banner-icon" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 20 20">
                                <rect x="0" fill="none" width="20" height="20"/>
                                <g>
                                    <path
                                        d="M18 16V4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v12c0 .55.45 1 1 1h13c.55 0 1-.45 1-1zM8 11h1c.55 0 1 .45 1 1s-.45 1-1 1H8v1.5c0 .28-.22.5-.5.5s-.5-.22-.5-.5V13H6c-.55 0-1-.45-1-1s.45-1 1-1h1V5.5c0-.28.22-.5.5-.5s.5.22.5.5V11zm5-2h-1c-.55 0-1-.45-1-1s.45-1 1-1h1V5.5c0-.28.22-.5.5-.5s.5.22.5.5V7h1c.55 0 1 .45 1 1s-.45 1-1 1h-1v5.5c0 .28-.22.5-.5.5s-.5-.22-.5-.5V9z"/>
                                </g>
                            </svg>
                            <?php _e( 'Go to Settings', 'google-places-reviews' ); ?>
                        </a>
                    </div>

                    <div class="wpbr-action wpbr-upsell-action">
                        <a href="https://wpbusinessreviews.com/" target="_blank">
                            <svg class="wpbr-star-icon wpbr-banner-icon" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 20 20">
                                <rect x="0" fill="none" width="20"
                                      height="20"/>
                                <g>
                                    <path d="M10 1l3 6 6 .75-4.12 4.62L16 19l-6-3-6 3 1.13-6.63L1 7.75 7 7z"/>
                                </g>
                            </svg><?php _e( 'Upgrade to WP Business Reviews', 'google-places-reviews' ); ?>
                        </a>
                    </div>

                </div>

            </header>
        </div>
    <?php
    endif;
}

add_action( 'admin_notices', 'gpr_activation_admin_notice' );

/**
 * Nag ignore.
 *
 * When the
 */
function gpr_nag_ignore() {
    global $current_user;

    // If user clicks to ignore the notice, add that to their user meta
    if ( isset( $_GET['gpr_nag_ignore'] ) && '0' == $_GET['gpr_nag_ignore'] ) {
        add_user_meta( $current_user->ID, 'gpr_activation_ignore_notice', 'true', true );
    }
}

add_action( 'admin_init', 'gpr_nag_ignore' );


/**
 * Add links to Plugin listings view
 *
 * @param $links
 *
 * @return mixed
 */
function gpr_add_plugin_page_links( $links, $file ) {

    if ( $file == GPR_PLUGIN_NAME ) {
        // Add Widget Page link to our plugin

        $settings_link = '<a href="' . admin_url( 'options-general.php?page=google_places_reviews' ) . '">' . __( 'Settings', 'google-places-reviews' ) . '</a>';
        array_unshift( $links, $settings_link );

        // Add Support Forum link to our plugin
        $support_link = '<a href="https://wordpress.org/support/plugin/google-places-reviews" target="_blank" rel="noopener noreferrer" title="Get Support">' . __( 'Support', 'google-places-reviews' ) . '</a>';

        array_unshift( $links, $support_link );
    }

    return $links;
}

add_filter( 'plugin_action_links', 'gpr_add_plugin_page_links', 10, 2 );


/**
 * Plugin page meta links.
 *
 * @param $meta
 * @param $file
 *
 * @return array
 */
function gpr_add_plugin_meta_links( $meta, $file ) {
    if ( $file == GPR_PLUGIN_NAME ) {
        $meta[] = "<a href='http://wordpress.org/support/view/plugin-reviews/google-places-reviews' target='_blank' rel='noopener noreferrer' title='" . __( 'Rate Reviews Block for Google ', 'google-places-reviews' ) . "'>" . __( 'Rate Plugin', 'google-places-reviews' ) . '</a>';
        $meta[] = "<a href='https://wpbusinessreviews.com/' target='_blank' rel='noopener noreferrer' title='" . __( 'Upgrade to WP Business Reviews', 'google-places-reviews' ) . "'>" . __( 'Upgrade to WP Business Reviews', 'google-places-reviews' ) . '</a>';
    }

    return $meta;
}

add_filter( 'plugin_row_meta', 'gpr_add_plugin_meta_links', 10, 2 );
