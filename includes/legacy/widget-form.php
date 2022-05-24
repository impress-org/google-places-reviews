<?php
/**
 * Widget Form
 *
 * Widget form options in WP-Admin
 *
 * @since 1.0
 */
?>

<!-- Title -->
<p>
    <label
        for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php _e( 'Widget Title', 'google-places-reviews' ); ?></label>
    <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"
           name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php esc_attr_e( $title ); ?>" />
</p>


<div class="gpr-place-search-wrap clearfix">
    <!-- Google Maps Autocomplete Label -->
    <p class="gpr-autocomplete-wrap">
        <label
            for="<?php echo esc_attr($this->get_field_id( 'autocomplete' )); ?>"><?php _e( 'Location Lookup', 'google-places-reviews' ); ?>
            : <?php echo gpr_admin_tooltip( 'autocomplete' ); ?></label>
        <input class="widefat gpr-autocomplete" id="<?php echo esc_attr($this->get_field_id( 'autocomplete' )); ?>"
               name="<?php echo esc_attr($this->get_field_name( 'autocomplete' )); ?>" type="text"
               value="<?php echo( empty( $autocomplete ) ? '' : esc_attr__( $autocomplete ) ); ?>" />
    </p>
    <!-- Google Maps Autocomplete Label -->
    <p class="gpr-place-type">
        <label
            for="<?php echo esc_attr__($this->get_field_id( 'place_type' )); ?>"><?php _e( 'Place Type', 'google-places-reviews' ); ?>
            : <?php echo gpr_admin_tooltip( 'place_type' ); ?></label>

        <select name="<?php echo esc_attr__($this->get_field_name( 'place_type' )); ?>"
                id="<?php echo esc_attr__($this->get_field_id( 'place_type' )); ?>" class="widefat gpr-types">
            <option value="all"
                <?php
                if ( $place_type == 'all' ) {
                    echo "selected='selected'";
                }
                ?>
            ><?php _e( 'All', 'google-places-reviews' ); ?>
            </option>
            <option value="address"
                <?php
                if ( $place_type == 'address' ) {
                    echo "selected='selected'";
                }
                ?>
            ><?php _e( 'Addresses', 'google-places-reviews' ); ?>
            </option>
            <option value="establishment"
                <?php
                if ( empty( $place_type ) || $place_type == 'establishment' ) {
                    echo "selected='selected'";
                }
                ?>
            ><?php _e( 'Establishments', 'google-places-reviews' ); ?>
            </option>
            <option value="(regions)"
                <?php
                if ( $place_type == '(regions)' ) {
                    echo "selected='selected'";
                }
                ?>
            ><?php _e( 'Regions', 'google-places-reviews' ); ?>
            </option>
        </select>

    </p>
</div>
<!-- Google Maps Reference Field -->
<div class="set-business"
    <?php
    if ( empty( $location ) ) {
        echo "style='display:none;'";
    }
    ?>
>
    <p><?php _e( '<strong>Place Set:</strong> You have successfully set the place for this widget. To switch the place use the Location Lookup field above.', 'google-places-reviews' ); ?></p>

    <p>
        <label
            for="<?php echo esc_attr__($this->get_field_id( 'location' )); ?>"><?php _e( 'Location', 'google-places-reviews' ); ?>
            : <?php echo gpr_admin_tooltip( 'location' ); ?></label>
        <input class="widefat location" onClick="this.setSelectionRange(0, this.value.length)" readonly
               id="<?php echo esc_attr__($this->get_field_id( 'location' )); ?>"
               name="<?php echo esc_attr__($this->get_field_name( 'location' )); ?>" type="text"
               placeholder="<?php echo( empty( $location ) ? 'No location set' : '' ); ?>"
               value="<?php esc_attr_e( $location ); ?>" />
    </p>

    <p style="display:none;">
        <label
            for="<?php echo esc_attr__($this->get_field_id( 'reference' )); ?>"><?php _e( 'Location Reference ID', 'google-places-reviews' ); ?>
            :</label>
        <input class="widefat reference" onClick="this.setSelectionRange(0, this.value.length)" readonly
               id="<?php echo esc_attr__($this->get_field_id( 'reference' )); ?>"
               name="<?php echo esc_attr__($this->get_field_name( 'reference' )); ?>" type="text"
               placeholder="<?php echo( empty( $reference ) ? 'No location set' : '' ); ?>"
               value="<?php esc_attr_e( $reference ); ?>" />
    </p>

    <p style="margin-bottom:0;">
        <label
            for="<?php echo esc_attr__($this->get_field_id( 'place_id' )); ?>"><?php _e( 'Location Place ID', 'google-places-reviews' ); ?>
            : <?php echo gpr_admin_tooltip( 'place_id' ); ?></label>
        <input class="widefat place_id" onClick="this.setSelectionRange(0, this.value.length)" readonly
               id="<?php echo esc_attr__($this->get_field_id( 'place_id' ) ); ?>"
               name="<?php echo esc_attr__($this->get_field_name( 'place_id' )); ?>" type="text"
               placeholder="<?php echo( empty( $place_id ) ? 'No location set' : '' ); ?>"
               value="<?php esc_attr_e( $place_id ); ?>" />
    </p>

</div>

<h4 class="gpr-widget-toggler"><?php _e( 'Review Options', 'google-places-reviews' ); ?>:<span></span></h4>

<div class="review-options toggle-item">


    <!-- Filter Reviews -->
    <p class="pro-field">
        <label
            for="<?php echo esc_attr__($this->get_field_id( 'review_filter' )); ?>"><?php _e( 'Minimum Review Rating:', 'google-places-reviews' ); ?><?php echo gpr_admin_tooltip( 'review_filter' ); ?></label>

        <select id="<?php echo esc_attr__($this->get_field_id( 'review_filter' )); ?>" class="widefat"
                name="<?php echo esc_attr__($this->get_field_name( 'review_filter' )); ?>" disabled>

            <option value="none"
                <?php
                if ( empty( $review_filter ) || $review_filter == 'No filter' ) {
                    echo "selected='selected'";
                }
                ?>
            ><?php _e( 'No filter', 'google-places-reviews' ); ?>
            </option>
            <option value="5"
                <?php
                if ( $review_filter == '5' ) {
                    echo "selected='selected'";
                }
                ?>
            ><?php _e( '5 Stars', 'google-places-reviews' ); ?>
            </option>
            <option value="4"
                <?php
                if ( $review_filter == '4' ) {
                    echo "selected='selected'";
                }
                ?>
            ><?php _e( '4 Stars', 'google-places-reviews' ); ?>
            </option>
            <option value="3"
                <?php
                if ( $review_filter == '3' ) {
                    echo "selected='selected'";
                }
                ?>
            ><?php _e( '3 Stars', 'google-places-reviews' ); ?>
            </option>
            <option value="2"
                <?php
                if ( $review_filter == '2' ) {
                    echo "selected='selected'";
                }
                ?>
            ><?php _e( '2 Stars', 'google-places-reviews' ); ?>
            </option>
            <option value="1"
                <?php
                if ( $review_filter == '1' ) {
                    echo "selected='selected'";
                }
                ?>
            ><?php _e( '1 Star', 'google-places-reviews' ); ?>
            </option>

        </select>

    </p>

    <!-- Review Limit -->
    <p>
        <label
            for="<?php echo esc_attr__($this->get_field_id( 'review_limit' )); ?>"><?php _e( 'Limit Number of Reviews:', 'google-places-reviews' ); ?><?php echo gpr_admin_tooltip( 'review_limit' ); ?></label>
        <select name="<?php echo esc_attr__($this->get_field_name( 'review_limit' )); ?>"
                id="<?php echo esc_attr__($this->get_field_id( 'review_limit' )); ?>"
                class="widefat">
            <?php
            $options = array( '3', '2', '1' );
            foreach ( $options as $option ) {
                ?>

                <option value="<?php echo esc_attr__($option); ?>"
                        id="<?php echo esc_attr__($option); ?>"
                    <?php
                    if ( $review_limit == $option || empty( $review_limit ) && $option == '4' ) {
                        echo 'selected="selected"';
                    }
                    ?>
                ><?php echo esc_html__($option); ?></option>

            <?php } ?>
        </select>
    </p>

</div><!-- /.review-options -->

<h4 class="gpr-widget-toggler"><?php esc_html_e( 'Display Options', 'google-places-reviews' ); ?>:<span></span></h4>

<div class="display-options toggle-item">

    <!-- Widget Theme -->
    <p>
        <label
            for="<?php echo esc_attr__($this->get_field_id( 'widget_style' )); ?>"><?php esc_html_e( 'Widget Theme', 'google-places-reviews' ); ?>
            : <?php echo gpr_admin_tooltip( 'widget_style' ); ?></label>
        <select name="<?php echo esc_attr__($this->get_field_name( 'widget_style' )); ?>" class="widefat profield">
            <?php
            $options = array(
                __( 'Bare Bones', 'google-places-reviews' ),
                __( 'Minimal Light', 'google-places-reviews' ),
                __( 'Minimal Dark', 'google-places-reviews' ),
                __( 'Shadow Light', 'google-places-reviews' ),
                __( 'Shadow Dark', 'google-places-reviews' ),
            );
            // Counter for Option Values
            $counter = 0;

            foreach ( $options as $option ) {
                echo '<option value="' . $option . '" id="' . $option . '"', $widget_style == $option ? ' selected="selected"' : '', '>', $option, '</option>';
                $counter ++;
            }
            ?>
        </select>
    </p>

    <!-- Hide Places Header -->
    <p>
        <input id="<?php echo esc_attr__($this->get_field_id( 'hide_header' )); ?>"
               name="<?php echo esc_attr__($this->get_field_name( 'hide_header' ) ); ?>" type="checkbox"
               value="1" <?php checked( '1', $hide_header ); ?> />
        <label
            for="<?php echo esc_attr__($this->get_field_id( 'hide_header' )); ?>"><?php _e( 'Hide Business Information', 'google-places-reviews' ); ?><?php echo gpr_admin_tooltip( 'hide_header' ); ?></label>
    </p>


    <!-- Hide x Rating -->
    <p>
        <input id="<?php echo esc_attr__($this->get_field_id( 'hide_out_of_rating' )); ?>"
               name="<?php echo esc_attr__($this->get_field_name( 'hide_out_of_rating' )); ?>" type="checkbox"
               value="1" <?php checked( '1', $hide_out_of_rating ); ?> />
        <label
            for="<?php echo esc_attr__($this->get_field_id( 'hide_out_of_rating' )); ?>"><?php _e( 'Hide "x out of 5 stars" text', 'google-places-reviews' ); ?><?php echo gpr_admin_tooltip( 'hide_out_of_rating' ); ?></label>
    </p>

    <!-- Show Google Image -->
    <p>
        <input id="<?php echo esc_attr__($this->get_field_id( 'hide_google_image' )); ?>"
               name="<?php echo esc_attr__($this->get_field_name( 'hide_google_image' )); ?>" type="checkbox"
               value="1" <?php checked( '1', $hide_google_image ); ?> />
        <label
            for="<?php echo esc_attr__($this->get_field_id( 'hide_google_image' )); ?>"><?php _e( 'Hide Google logo', 'google-places-reviews' ); ?><?php echo gpr_admin_tooltip( 'google_image' ); ?> </label>
    </p>


</div>
<!--/.display-options -->

<h4 class="gpr-widget-toggler"><?php esc_html_e( 'Advanced Options:', 'google-places-reviews' ); ?><span></span></h4>


<div class="advanced-options toggle-item">

    <!-- Transient / Cache -->
    <p>
        <label
            for="<?php echo esc_attr__($this->get_field_id( 'cache' )); ?>"><?php _e( 'Cache Data:', 'google-places-reviews' ); ?><?php echo gpr_admin_tooltip( 'cache' ); ?></label>

        <select name="<?php echo esc_attr__($this->get_field_name( 'cache' )); ?>"
                id="<?php echo esc_attr__($this->get_field_id( 'cache' )); ?>"
                class="widefat">
            <?php
            $options = array(
                __( 'None', 'google-places-reviews' ),
                __( '1 Hour', 'google-places-reviews' ),
                __( '3 Hours', 'google-places-reviews' ),
                __( '6 Hours', 'google-places-reviews' ),
                __( '12 Hours', 'google-places-reviews' ),
                __( '1 Day', 'google-places-reviews' ),
                __( '2 Days', 'google-places-reviews' ),
                __( '1 Week', 'google-places-reviews' ),
            );
            /**
             * Output Cache Options (set 2 Days as default for new widgets)
             */
            foreach ( $options as $option ) {
                ?>
                <option value="<?php echo esc_attr__($option); ?>"
                        id="<?php echo esc_attr__($option); ?>"
                    <?php
                    if ( $cache == $option || empty( $cache ) && $option == '1 Day' ) {
                        echo ' selected="selected" ';
                    }
                    ?>
                >
                    <?php esc_html_e( $option ); ?>
                </option>
                <?php
                $counter ++;
            }
            ?>
        </select>


    </p>

    <!-- Clear Cache Button -->
    <p class="clearfix">
        <span class="cache-message"></span>
        <a href="#" class="button gpr-clear-cache" title="<?php esc_attr_e( 'Clear', 'google-places-reviews' ); ?>"
           data-transient-id-1="gpr_widget_api_<?php echo esc_attr__( substr( $place_id, 0, 25 )); ?>"
           data-transient-id-2="gpr_widget_options_<?php echo esc_attr__(substr( $place_id, 0, 25 )); ?>"><?php _e( 'Clear Cache', 'google-places-reviews' ); ?></a>
        <span class="cache-clearing-loading spinner"></span>
    </p>


    <!-- Disable title output checkbox -->
    <p>
        <input id="<?php echo esc_attr__($this->get_field_id( 'disable_title_output' )); ?>"
               name="<?php echo esc_attr__($this->get_field_name( 'disable_title_output' )); ?>" type="checkbox"
               value="1" <?php checked( '1', $disable_title_output ); ?>/>
        <label
            for="<?php echo esc_attr__($this->get_field_id( 'disable_title_output' )); ?>"><?php _e( 'Disable Title Output', 'google-places-reviews' ); ?><?php echo gpr_admin_tooltip( 'disable_title_output' ); ?></label>
    </p>

    <!-- Open Links in New Window -->
    <p>
        <input id="<?php echo esc_attr__($this->get_field_id( 'target_blank' )); ?>"
               name="<?php echo esc_attr__($this->get_field_name( 'target_blank' )); ?>" type="checkbox"
               value="1" <?php checked( '1', $target_blank ); ?>/>
        <label
            for="<?php echo esc_attr__($this->get_field_id( 'target_blank' )); ?>"><?php _e( 'Open Links in New Window', 'google-places-reviews' ); ?><?php echo gpr_admin_tooltip( 'target_blank' ); ?></label>
    </p>

    <!-- No Follow Links -->
    <p>
        <input id="<?php echo esc_attr__($this->get_field_id( 'no_follow' )); ?>"
               name="<?php echo esc_attr__($this->get_field_name( 'no_follow' )); ?>" type="checkbox"
               value="1" <?php checked( '1', $no_follow ); ?> />
        <label
            for="<?php echo esc_attr__($this->get_field_id( 'no_follow' )); ?>"><?php _e( 'No Follow Links', 'google-places-reviews' ); ?><?php echo gpr_admin_tooltip( 'no_follow' ); ?></label>
    </p>

</div>

<div class="gpr-widget-alert">
    <a href="https://wpbusinessreviews.com/"
       target="_blank"><?php _e( 'Upgrade to WP Business Reviews', 'google-places-reviews' ); ?>
        <span></span></a>
</div>

<p class="gpr-widget-footer-links clearfix">
    <span class="google-power"></span>
</p>

