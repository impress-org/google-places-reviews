<?php
/**
 * Admin options page.
 */


/**
 * Google Options Page.
 */
function gpr_widget_add_options_page() {
    add_submenu_page(
        'options-general.php', // The parent page of this menu
        __( 'Reviews Block for Google Settings', 'google-places-reviews' ), // The Page Title
        __( 'Google Reviews', 'google-places-reviews' ), // The Menu Title
        'manage_options', // The capability required for access to this item
        'google_places_reviews', // the slug to use for the page in the URL
        'google_places_reviews'
    );
}

add_action( 'admin_menu', 'gpr_widget_add_options_page' );

/**
 * Enqueue scripts.
 *
 * Add scripts to admin head only the appropriate pages.
 *
 * @param $hook
 */
function gpr_admin_scripts( $hook ) {

    if (
        'widgets.php' === $hook
        || 'settings_page_google_places_reviews' === $hook
    ) {
        wp_register_script( 'gpr_widget_admin_scripts', GPR_PLUGIN_URL . '/build/google-widget-admin.js' );
        wp_enqueue_script( 'gpr_widget_admin_scripts' );

        wp_register_style( 'gpr_widget_admin_css', GPR_PLUGIN_URL . '/build/google-widget-admin-styles.css' );
        wp_enqueue_style( 'gpr_widget_admin_css' );
    }

}

add_action( 'admin_enqueue_scripts', 'gpr_admin_scripts', 10, 1 );


/**
 * Outputs the gpr_widget option setting value.
 *
 * @param $setting
 * @param $options
 *
 * @return mixed|string
 */
function gpr_widget_option( $setting, $options ) {
    $value = '';
    // If the old setting is set, output that
    if ( get_option( $setting ) != '' ) {
        $value = get_option( $setting );
    } elseif ( is_array( $options ) ) {
        $value = $options[ $setting ];
    }

    return $value;

}

/**
 * Recursively sanitizes a given value.
 *
 * @param string|array $value Value to be sanitized.
 *
 * @return string|array Array of clean values or single clean value.
 * @since 1.5.0
 *
 */
function gpr_widget_clean( $value ) {
    if ( is_array( $value ) ) {
        return array_map( 'gpr_widget_clean', $value );
    } else {
        return is_scalar( $value ) ? sanitize_text_field( $value ) : '';
    }
}

/**
 * Admin Options.
 */
function google_places_reviews() { ?>

    <div class="wrap" xmlns="http://www.w3.org/1999/html">

        <!-- Plugin Title -->
        <div id="ywp-title-wrap">
            <div id="icon-wpbr" class=""></div>
            <h2><?php _e( 'Reviews Block for Google Settings', 'google-places-reviews' ); ?> </h2>
            <a href="https://wpbusinessreviews.com/" class="wpbr-option-page-upsell"
               title="Upgrade to WP Business Reviews"
               target="_blank" rel="noopener noreferrer" class="update-link new-window">
                <svg class="wpbr-star-icon wpbr-banner-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <rect x="0" fill="none" width="20"
                          height="20" />
                    <g>
                        <path d="M10 1l3 6 6 .75-4.12 4.62L16 19l-6-3-6 3 1.13-6.63L1 7.75 7 7z" />
                    </g>
                </svg><?php _e( 'Upgrade to WP Business Reviews', 'google-places-reviews' ); ?></a>
        </div>

        <form id="gpr-settings" method="post" action="options.php">

            <?php
            /**
             * Tells WordPress that the options we registered are being handled by this form.
             */
            settings_fields( 'googleplacesreviews_options' );

            // Retrieve stored options, if any.
            $options = get_option( 'googleplacesreviews_options' );
            ?>

            <div class="metabox-holder">
                <div class="postbox-container" style="width:75%">
                    <div id="main-sortables" class="meta-box-sortables ui-sortable">
                        <div class="postbox" id="gpr-widget-intro">
                            <div class="handlediv" title="Click to toggle"><br></div>
                            <h3 class="hndle">
                                <span><?php esc_html_e( 'Reviews Block for Google Introduction', 'google-places-reviews' ); ?></span>
                            </h3>

                            <div class="inside">
                                <p>
                                    <strong><?php esc_html_e( 'Thanks for choosing this plugin to display your business reviews. To get started, follow the steps below:', 'google-places-reviews' ); ?></strong>
                                </p>
                                <ol>
                                    <li><?php _e( 'In order for the plugin to access your Google reviews, you must <a href="https://wpbusinessreviews.com/documentation/platforms/google/" target="_blank">create a Google Places API key</a>.', 'google-places-reviews'
                                        ); ?></li>
                                    <li><?php esc_html_e( 'Once you have an API key copy save it in the Google Places API Key field below.', 'google-places-reviews' ); ?></li>
                                    <li><?php esc_html_e( 'You\'re ready to start using the block! Insert the block into your editor to get started.', 'google-places-reviews' ); ?></li>
                                </ol>
                            </div>
                            <!-- /.inside -->
                        </div>
                        <!-- /#gpr-widget-intro -->

                        <div class="postbox" id="gpr-widget-options">
                            <h3 class="hndle">
                                <span><?php esc_html_e( 'Reviews Block for Google Settings', 'google-places-reviews' ); ?></span>
                            </h3>
                            <div class="inside">
                                <div class="control-group">
                                    <div class="control-label">
                                        <label
                                            for="gpr_widget_fusion_api"><?php esc_html_e( 'Google Places API Key:', 'google-places-reviews' ); ?>
                                            <img
                                                src="<?php echo GPR_PLUGIN_URL . '/includes/legacy/assets/images/help.png'; ?>"
                                                title="<?php
                                                _e( 'This is necessary to pull reviews from Google.', 'google-places-reviews' ); ?>"
                                                class="tooltip-info" width="16" height="16" /></label>
                                    </div>
                                    <div class="controls">
                                        <?php $api_key = ! empty( $options['google_places_api_key'] ) ? $options['google_places_api_key'] : ''; ?>
                                        <input type="password" id="google_places_api_key"
                                               name="googleplacesreviews_options[google_places_api_key]"
                                               value="<?php esc_attr_e( $api_key ); ?>"
                                               size="45" /><br />
                                        <small><a href="https://wpbusinessreviews.com/documentation/platforms/google/"
                                                  target="_blank"><?php esc_html_e( 'Learn how to create a Google Places API key', 'google-places-reviews' ); ?></a>
                                        </small>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <div class="control-label">
                                        <label
                                            for="disable_css"><?php esc_html_e( 'Disable Plugin CSS Output:', 'google-places-reviews' ); ?>
                                            <img
                                                src="<?php echo GPR_PLUGIN_URL . '/includes/legacy/assets/images/help.png'; ?>"
                                                title="<?php esc_html_e( 'Disabling the widget\'s CSS output is useful for more complete control over customizing the widget styles. This can be useful for custom theme designs.', 'google-places-reviews' ); ?>"
                                                class="tooltip-info" width="16" height="16" /></label>
                                    </div>
                                    <div class="controls">
                                        <input type="checkbox" id="disable_css"
                                               name="googleplacesreviews_options[disable_css]" value="1"
                                            <?php
                                            $css_option = ! empty( $options['disable_css'] ) ? $options['disable_css'] : '';

                                            // >1.5.0 b/w compat.
                                            if ( 'on' === $css_option ) {
                                                $css_option = 1;
                                            }

                                            checked( 1, $css_option );
                                            ?>
                                        />
                                    </div>
                                </div>
                                <!--/.control-group -->

                            </div>
                            <!-- /.inside -->
                        </div>
                        <!-- /#gpr-widget-options -->

                        <div class="control-group">
                            <div class="controls">
                                <input class="button-primary" type="submit" name="submit-button"
                                       value="<?php _e( 'Update', 'google-places-reviews' ); ?>" />
                            </div>
                        </div>
                    </div>
                    <!-- /#main-sortables -->
                </div>
                <!-- /.postbox-container -->
                <div class="alignright" style="width:24%">
                    <div id="sidebar-sortables" class="meta-box-sortables ui-sortable">

                        <div id="wpr-upsell" class="postbox">
                            <div class="handlediv"><br></div>
                            <h3 class="hndle">
                                <span><?php _e( 'WP Business Reviews', 'google-places-reviews' ); ?></span></h3>

                            <div class="inside">

                                <p><?php _e( '<a href="https://wpbusinessreviews.com">WP Business Reviews</a> is a significant upgrade to Reviews Block for Google  that adds features such as review mashups, the ability to add Google reviews manually, carousel formats, and additional review platforms such as Facebook, Google, and more!', 'google-places-reviews' ); ?></p>

                                <p><?php _e( 'Also included is Priority Support, updates, and well-documented shortcodes to display your Google reviews on any page or post.', 'google-places-reviews' ); ?></p>
                            </div>
                        </div>
                        <!-- /.premium-metabox -->

                    </div>
                    <!-- /.sidebar-sortables -->

                </div>
                <!-- /.alignright -->
            </div>
            <!-- /.metabox-holder -->
        </form>


    </div><!-- /#wrap -->

    <?php
}
