<?php

/**
 * Class Google_Places_Reviews
 */
class Google_Places_Reviews extends WP_Widget {

    /**
     * Plugin Options from Options Panel
     *
     * @var mixed|void
     */
    public $options;

    /**
     * Google API key
     *
     * @var string
     */
    public $api_key;

    /**
     * Array of Private Options
     *
     * @since    1.0.0
     *
     * @var array
     */
    public $widget_fields = array(
        'title'                => '',
        'location'             => '',
        'reference'            => '',
        'place_id'             => '',
        'place_type'           => '',
        'cache'                => '',
        'disable_title_output' => '',
        'widget_style'         => 'Minimal Light',
        'review_filter'        => '',
        'review_limit'         => '5',
        'review_characters'    => '1',
        'hide_header'          => '',
        'hide_out_of_rating'   => '',
        'hide_google_image'    => '',
        'target_blank'         => '1',
        'no_follow'            => '1',
    );


    /**
     * Register widget with WordPress.
     */
    public function __construct() {

        parent::__construct(
            'gpr_widget', // Base ID
            'Reviews Widget for Google', // Name
            array(
                'classname'   => 'google-places-reviews',
                'description' => esc_html__( 'Display user reviews for any location found on Google Places.', 'google-places-reviews' ),
            )
        );

        $this->options = get_option( 'googleplacesreviews_options', [ 'google_places_api_key' => null ] );
        $this->api_key = $this->options['google_places_api_key'];

        // Hooks
        add_action( 'wp_enqueue_scripts', [ $this, 'frontend_widget_scripts' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'admin_widget_scripts' ] );

    }

    /**
     * Admin Widget Scripts
     *
     * Load Widget JS Script ONLY on Widget page
     *
     * @param $hook
     */
    function admin_widget_scripts( $hook ) {
        if (
            $hook == 'widgets.php'
            || ( $hook == 'customize.php' && defined( 'SITEORIGIN_PANELS_VERSION' ) )
        ) {

            wp_register_script( 'gpr_google_places_gmaps', 'https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=' . $this->api_key, [ 'jquery' ] );
            wp_enqueue_script( 'gpr_google_places_gmaps' );

            wp_register_script( 'gpr_widget_admin_scripts', GPR_PLUGIN_URL . '/build/google-widget-admin.js', [ 'jquery' ] );
            wp_localize_script(
                'gpr_widget_admin_scripts', 'gpr_ajax_object', [
                    'ajax_url' => admin_url( 'admin-ajax.php' ),
                    'i18n'     => [
                        'google_auth_error' => sprintf( __( '%1$sGoogle API Error:%2$s Due to recent changes by Google you must now add the Maps API to your existing API key in order to use the Location Lookup feature of the Google Places Widget. %3$sView documentation here%4$s', 'google-places-reviews' ), '<strong>', '</strong>', '<br><a href="" target="_blank" class="new-window">', '</a>' ),
                    ],
                ]
            );
            wp_enqueue_script( 'gpr_widget_admin_scripts' );

            wp_register_style( 'gpr_widget_admin_css', GPR_PLUGIN_URL . '/build/google-widget-admin-styles.css' );
            wp_enqueue_style( 'gpr_widget_admin_css' );

        }

    }

    /**
     * Frontend Scripts
     */
    function frontend_widget_scripts() {

        if ( isset( $this->options['disable_css'] ) ) {
            return;
        }

        wp_register_style( 'gpr_widget', GPR_PLUGIN_URL . '/build/google-widget-public-styles.css' );

    }

    /**
     * Front-end display of widget.
     *
     * @param array $args
     * @param array $instance
     *
     * @return bool
     * @see WP_Widget::widget()
     */
    function widget( $args, $instance ) {

        // @TODO: Remove usage
        extract( $args );

        wp_enqueue_style( 'gpr_widget' );

        // loop through options array and save variables for usage within function
        foreach ( $instance as $variable => $value ) {
            ${$variable} = ! isset( $value ) ? $this->widget_fields[ $variable ] : esc_attr( $value );
        }

        // Check for a reference. If none, output error
        if ( isset( $place_id ) && 'No location set' === $place_id || empty( $place_id ) ) {
            $this->output_error_message( esc_html__( 'There is no location set for this widget yet.', 'google-places-reviews' ), 'error' );

            return false;
        }

        // Title filter
        if ( isset( $title ) ) {
            $title = apply_filters( 'widget_title', $title );
        }

        // Open link in new window if set
        if ( isset( $target_blank ) && $target_blank ) {
            $target_blank = '_blank';
        } else {
            $target_blank = '';
        }

        // Add nofollow relation if set
        if ( isset( $no_follow ) && $no_follow ) {
            $no_follow = 'nofollow';
        } else {
            $no_follow = '';
        }

        /**
         * Use the new Google Places ID (rather than reference) - but don't break older widgets/shortcodes
         */
        if ( ( empty( $reference ) || $reference === 'No Location Set' && ! empty( $place_id ) && $place_id !== 'No location set' ) || strlen( $place_id ) < 80 ) {
            $google_places_url = add_query_arg(
                array(
                    'placeid' => $place_id,
                    'key'     => $this->api_key,
                ),
                'https://maps.googleapis.com/maps/api/place/details/json'
            );
        } else {
            // User is on old Google's reference ID
            $google_places_url = add_query_arg(
                array(
                    'reference' => $reference,
                    'key'       => $this->api_key,
                ),
                'https://maps.googleapis.com/maps/api/place/details/json'
            );
        }
        // serialize($instance) sets the transient cache from the $instance variable which can easily bust the cache once options are changed
        $transient_unique_id = substr( $place_id, 0, 25 );
        $response            = get_transient( 'gpr_widget_api_' . $transient_unique_id );
        $widget_options      = get_transient( 'gpr_widget_options_' . $transient_unique_id );
        $serialized_instance = serialize( $instance );
        $cache               = isset( $cache ) ? strtolower( $cache ) : '1 Day';

        // Cache: cache option is enabled
        if ( $cache !== 'none' ) {

            // Check for an existing copy of our cached/transient data
            // also check to see if widget options have updated; this will bust the cache
            if ( $response === false || $serialized_instance !== $widget_options ) {

                // It wasn't there, so regenerate the data and save the transient
                // Get Time to Cache Data
                $expiration = $cache;

                // Assign Time to appropriate Math
                switch ( $expiration ) {
                    case '1 hour':
                        $expiration = 3600;
                        break;
                    case '3 hours':
                        $expiration = 3600 * 3;
                        break;
                    case '6 hours':
                        $expiration = 3600 * 6;
                        break;
                    case '12 hours':
                        $expiration = 60 * 60 * 12;
                        break;
                    case '1 day':
                        $expiration = 60 * 60 * 24;
                        break;
                    case '2 days':
                        $expiration = 60 * 60 * 48;
                        break;
                    case '1 week':
                        $expiration = 60 * 60 * 168;
                        break;
                }

                // Cache data wasn't there, so regenerate the data and save the transient
                $response = $this->get_reviews( $google_places_url );
                set_transient( 'gpr_widget_api_' . $transient_unique_id, $response, $expiration );
                set_transient( 'gpr_widget_options_' . $transient_unique_id, $serialized_instance, $expiration );

            } //end response
        } else {

            // No Cache option enabled;
            $response = $this->get_reviews( $google_places_url );

        }

        // Error message
        if ( ! empty( $response->error_message ) ) {

            $this->output_error_message( $response->error_message, 'error' );
            $this->delete_transient_cache( $transient_unique_id );

            return false;

        } //No Place ID or Reference set for this widget
        elseif ( empty( $reference ) && empty( $place_id ) ) {

            $this->output_error_message( __( '<strong>INVALID REQUEST</strong>: Please check that this widget has a Google Place ID set.', 'google-places-reviews' ), 'error' );
            $this->delete_transient_cache( $transient_unique_id );

            return false;

        } elseif ( isset( $response['error_message'] ) && ! empty( $response['error_message'] ) ) {

            $error = '<strong>' . $response['status'] . '</strong>: ' . $response['error_message'];
            $this->output_error_message( $error, 'error' );
            $this->delete_transient_cache( $transient_unique_id );

            return false;

        }

        $widget_style = ( isset( $widget_style ) && ! empty( $widget_style ) ) ? $widget_style : 'Minimal Light';

        // Widget Style
        $style = 'gpr-' . sanitize_title( $widget_style ) . '-style';
        // no 'class' attribute - add one with the value of width
        // @see http://wordpress.stackexchange.com/questions/18942/add-class-to-before-widget-from-within-a-custom-widget
        if ( ! empty( $before_widget ) && strpos( $before_widget, 'class' ) === false ) {
            $before_widget = str_replace( '>', 'class="' . $style . '"', $before_widget );
        } // there is 'class' attribute - append width value to it
        elseif ( ! empty( $before_widget ) && strpos( $before_widget, 'class' ) !== false ) {
            $before_widget = str_replace( 'class="', 'class="' . $style . ' ', $before_widget );
        } //no 'before_widget' at all so wrap widget with div
        else {
            $before_widget = '<div class="google-places-reviews">';
            $before_widget = str_replace( 'class="', 'class="' . $style . ' ', $before_widget );
        }

        /* Before widget */
        echo wp_kses_post( $before_widget );


        // if the title is set & the user hasn't disabled title output
        if ( ! empty( $title ) && isset( $disable_title_output ) && ! $disable_title_output ) {
            /*
             Add class to before_widget from within a custom widget
            http://wordpress.stackexchange.com/questions/18942/add-class-to-before-widget-from-within-a-custom-widget
            */
            // no 'class' attribute - add one with the value of width
            if ( ! empty( $before_title ) && strpos( $before_title, 'class' ) === false ) {
                $before_title = str_replace( '>', ' class="gpr-widget-title">', $before_title );
            } //widget title has 'class' attribute
            elseif ( ! empty( $before_title ) && strpos( $before_title, 'class' ) !== false ) {
                $before_title = str_replace( 'class="', 'class="gpr-widget-title ', $before_title );
            } //no 'title' at all so wrap widget with div
            else {
                $before_title = '<h3 class="">';
                $before_title = str_replace( 'class="', 'class="gpr-widget-title ', $before_title );
            }
            $after_title = empty( $after_title ) ? '</h3>' : $after_title;

            echo wp_kses_post( $before_title . $title . $after_title );
        }

        include GPR_PLUGIN_PATH . 'includes/legacy/widget-frontend.php';

    }

    /**
     * Update Widget
     *
     * Saves the widget options
     *
     * @param array $new_instance
     * @param array $old_instance
     *
     * @return array
     */
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        // loop through options array and save to new instance
        foreach ( $this->widget_fields as $field => $value ) {
            $instance[ $field ] = isset( $new_instance[ $field ] ) ? strip_tags( stripslashes( $new_instance[ $field ] ) ) : '';
        }

        return $instance;
    }

    /**
     * Widget Form
     *
     * Responsible for outputting the backend widget form.
     *
     * @see WP_Widget::form()
     */
    function form( $instance ) {

        // API Key Check:
        if ( ! isset( $this->options['google_places_api_key'] ) || empty( $this->options['google_places_api_key'] ) ) {
            $api_key_error = sprintf( __( '<p><strong>Notice: </strong>No Google Places API key detected. You will need to create an API key to use the plugin. API keys are manage through the <a href="%1$s" class="new-window" target="_blank">Google API Console</a>. For more information please see <a href="%2$s"  target="_blank"  class="new-window" title="Google Places API Introduction">this article</a>.</p> <p>Once you have obtained your API key enter it in the <a href="%3$s" title="Visit the plugin settings page">plugin settings page</a>.</p>', 'google-places-reviews' ), esc_url( 'https://code.google.com/apis/console/?noredirect' ), esc_url( 'https://developers.google.com/places/documentation/#Authentication' ), admin_url( '/options-general.php?page=googleplacesreviews' ) );
            $this->output_error_message( $api_key_error, 'error' );

            return;
        }

        // loop through options array and save options to new instance
        foreach ( $this->widget_fields as $field => $value ) {
            ${$field} = ! isset( $instance[ $field ] ) ? $value : esc_attr( $instance[ $field ] );
        }
        // Get the widget form
        include GPR_PLUGIN_PATH . 'includes/legacy/widget-form.php';

    }


    /**
     * Get Reviews.
     *
     * @param $url
     *
     * @return array|mixed
     */
    function get_reviews( $url ) {

        $data = wp_safe_remote_get( esc_url_raw( $url ) );

        if ( is_wp_error( $data ) ) {
            $error_message = $data->get_error_message();
            $this->output_error_message( "Something went wrong: $error_message", 'error' );
        }

        $response = json_decode( wp_remote_retrieve_body( $data ), true );

        // Get Reviewers Avatars
        $response = $this->get_reviewers_avatars( $response );

        // Get Business Avatar
        $response = $this->get_business_avatar( $response );

        // Google response data in JSON format
        return $response;

    }

    /**
     * Get Reviewers Avatars
     *
     * Get avatar from Places API response or provide placeholder.
     *
     * @return array
     */
    function get_reviewers_avatars( $response ) {
        // GPR Reviews Array.
        $gpr_reviews = [];

        // Includes Avatar image from user.
        if ( isset( $response['result']['reviews'] ) && ! empty( $response['result']['reviews'] ) ) {

            // Loop Google Places reviews.
            foreach ( $response['result']['reviews'] as $review ) {
                // Check to see if image is empty (no broken images).
                if ( ! empty( $review['profile_photo_url'] ) ) {
                    $avatar_img = $review['profile_photo_url'] . '?sz=100';
                } else {
                    $avatar_img = GPR_PLUGIN_URL . '/assets/images/mystery-man.png';
                }

                // Add array image to review array.
                $review = array_merge( $review, array( 'avatar' => $avatar_img ) );
                // Add full review to $gpr_views.
                $gpr_reviews[] = $review;
            }

            // Merge custom reviews array with response.
            $response = array_merge( $response, array( 'gpr_reviews' => $gpr_reviews ) );
        }

        return $response;
    }

    /**
     * Get Business Avatar
     *
     * @param $response
     *
     * @return array
     */
    function get_business_avatar( $response ) {

        // Business Avatar
        if ( isset( $response['result']['photos'] ) ) {

            $request_url = add_query_arg(
                array(
                    'photoreference' => $response['result']['photos'][0]['photo_reference'],
                    'key'            => $this->api_key,
                    'maxwidth'       => '300',
                    'maxheight'      => '300',
                ),
                'https://maps.googleapis.com/maps/api/place/photo'
            );

            $response = array_merge( $response, array( 'place_avatar' => esc_url( $request_url ) ) );

        }

        return $response;

    }

    /**
     * Output Error Message
     *
     * @param $message
     * @param $style
     */
    function output_error_message( $message, $style ) {

        switch ( $style ) {
            case 'error':
                $style = 'gpr-error';
                break;
            case 'warning':
                $style = 'gpr-warning';
                break;
            default:
                $style = 'gpr-warning';
        }

        $output = '<div class="gpr-alert ' . $style . '">';
        $output .= $message;
        $output .= '</div>';

        echo wp_kses_post( $output );

    }

    /**
     * Get Star Rating
     *
     * Returns the necessary output for Google Star Ratings
     *
     * @param $rating
     * @param $unix_timestamp
     * @param $hide_out_of_rating
     * @param $hide_google_image
     *
     * @return string
     */
    function get_star_rating( $rating, $unix_timestamp, $hide_out_of_rating, $hide_google_image ) {

        $output        = '';
        $rating_value  = '<p class="gpr-rating-value" ' . ( ( esc_attr( $hide_out_of_rating ) ) ? ' style="display:none;"' : '' ) . '><span>' . $rating . '</span>' . esc_attr__( ' out of 5 stars', 'google-places-reviews' ) . '</p>';
        $is_gpr_header = true;

        // AVATAR
        $google_img = '<div class="gpr-google-logo-wrap"' . ( ( esc_attr( $hide_google_image ) ) ? ' style="display:none;"' : '' ) . '><img src="' . GPR_PLUGIN_URL . '/build/images/google-logo-small.png' . '" class="gpr-google-logo-header" title=" ' . esc_attr__( 'Reviewed from Google', 'google-places-reviews' ) . '" alt="' . esc_attr__( 'Reviewed from Google', 'google-places-reviews' ) . '" /></div>';

        // Header doesn't have a timestamp
        if ( $unix_timestamp ) {
            $is_gpr_header = false;
        }

        // continue with output
        $output .= '<div class="star-rating-wrap">';
        $output .= '<div class="star-rating-size" style="width:' . ( 65 * esc_attr( $rating ) / 5 ) . 'px;"></div>';
        $output .= '</div>';

        // Output rating next to stars for individual reviews
        if ( $is_gpr_header === false ) {
            $output .= $rating_value;
        }

        // Show timestamp for reviews
        if ( $unix_timestamp ) {
            $output .= '<span class="gpr-rating-time">' . $this->get_time_since( $unix_timestamp ) . '</span>';
        }

        // Show overall rating value of review
        if ( $is_gpr_header === true ) {
            // Google logo
            if ( isset( $hide_google_image ) && $hide_google_image ) {
                $output .= $google_img;
            }
            $output .= $rating_value;
        }

        echo wp_kses_post( $output );

    }

    /**
     * Time Since
     * Works out the time since the entry post, takes a an argument in unix time (seconds)
     */
    public static function get_time_since( $date, $granularity = 1 ) {
        $difference = time() - $date;
        $retval     = '';
        $periods    = array(
            'decade' => 315360000,
            'year'   => 31536000,
            'month'  => 2628000,
            'week'   => 604800,
            'day'    => 86400,
            'hour'   => 3600,
            'minute' => 60,
            'second' => 1,
        );

        foreach ( $periods as $key => $value ) {
            if ( $difference >= $value ) {
                $time       = floor( $difference / $value );
                $difference %= $value;
                $retval     .= ( $retval ? ' ' : '' ) . $time . ' ';
                $retval     .= ( ( $time > 1 ) ? $key . 's' : $key );
                $granularity --;
            }
            if ( $granularity == '0' ) {
                break;
            }
        }

        return ' posted ' . $retval . ' ago';
    }

    /**
     * Delete Transient Cache
     *
     * Removes the transient cache when an error is displayed as to not cache error results
     *
     * @param $transient_unique_id
     */
    function delete_transient_cache( $transient_unique_id ) {
        delete_transient( 'gpr_widget_api_' . esc_html( $transient_unique_id ) );
        delete_transient( 'gpr_widget_options_' . esc_html( $transient_unique_id ) );
    }

}


/**
 * Admin Tooltips
 *
 * @param $tip_name
 *
 * @return bool|string
 */
function gpr_admin_tooltip( $tip_name ) {

    $tip_text = '';

    //Ensure there's a tip requested
    if ( empty( $tip_name ) ) {
        return false;
    }

    switch ( $tip_name ) {
        case 'title':
            $tip_text = esc_html__( 'The title text appears at the very top of the widget above all other elements.', 'google-places-reviews' );
            break;
        case 'autocomplete':
            $tip_text = esc_html__( 'Enter the name of your Google Place in this field to retrieve it\'s Google Place ID. If no information is returned there you may have a conflict with another plugin or theme using Google Maps API.', 'google-places-reviews' );
            break;
        case 'place_type':
            $tip_text = esc_html__( 'Specify how you would like to lookup your Google Places. Address instructs the Place Autocomplete service to return only geocoding results with a precise address. Establishment instructs the Place Autocomplete service to return only business results. The Regions type collection instructs the Places service to return any result matching the following types: locality, sublocality, postal_code, country, administrative_area_level_1, administrative_area_level_2.', 'google-places-reviews' );
            break;
        case 'location':
            $tip_text = esc_html__( 'This is the name of the place returned by Google\'s Places API.', 'google-places-reviews' );
            break;
        case 'place_id':
            $tip_text = esc_html__( 'The Google Place ID is a textual identifier that uniquely identifies a place and can be used to retrieve information about the place. This option is set using the "Location Lookup" field above.', 'google-places-reviews' );
            break;
        case 'review_filter':
            $tip_text = esc_html__( 'PRO FEATURE: Filter bad reviews to prevent them from displaying. Please note that the Google Places API only allows for up to 5 total reviews displayed per location. This option may limit the total number further.', 'google-places-reviews' );
            break;
        case 'review_limit':
            $tip_text = esc_html__( 'Limit the number of reviews displayed for this location to a set number.', 'google-places-reviews' );
            break;
        case 'reviewers_link':
            $tip_text = esc_html__( 'Toggle on or off the link on the reviews name to their Google+ page.', 'google-places-reviews' );
            break;
        case 'review_characters':
            $tip_text = esc_html__( 'Some reviews may be very long and cause the widget to have a very large height. This option uses JavaScript to expand and collapse the text.', 'google-places-reviews' );
            break;
        case 'review_char_limit':
            $tip_text = esc_html__( 'Set the character limit for this review widget. Values are in pixels.', 'google-places-reviews' );
            break;
        case 'widget_style':
            $tip_text = esc_html__( 'Choose from a set of predefined widget styles. Want to style your own? Set it to \'Bare Bones\' for easy CSS styling.', 'google-places-reviews' );
            break;
        case 'hide_header':
            $tip_text = esc_html__( 'Disable the main business information profile image, name, overall rating. Useful for displaying only ratings in the widget.', 'google-places-reviews' );
            break;
        case 'hide_out_of_rating':
            $tip_text = esc_html__( 'Hide the text the appears after the star image displaying \'x out of 5 stars\'. The text will still be output because it is important for SEO but it will be hidden with CSS.', 'google-places-reviews' );
            break;
        case 'google_image':
            $tip_text = esc_html__( 'Prevent the Google logo from displaying in the reviews widget.', 'google-places-reviews' );
            break;
        case 'cache':
            $tip_text = esc_html__( 'Caching data will save Google Place data to your database in order to speed up response times and conserve API requests. The suggested settings is 1 Day.', 'google-places-reviews' );
            break;
        case 'disable_title_output':
            $tip_text = esc_html__( 'The title output is content within the \'Widget Title\' field above. Disabling the title output may be useful for some themes.', 'google-places-reviews' );
            break;
        case 'target_blank':
            $tip_text = esc_html__( 'This option will add target=\'_blank\' to the widget\'s links. This is useful to keep users on your website.', 'google-places-reviews' );
            break;
        case 'no_follow':
            $tip_text = esc_html__( 'This option will add rel=\'nofollow\' to the widget\'s outgoing links. This option may be useful for SEO.', 'google-places-reviews' );
            break;
        case 'alignment':
            $tip_text = esc_html__( 'Choose whether to float the widget to the right or left, or not at all. This is helpful for integrating within post content so text wraps around the widget if wanted. Default value is \'none\'.', 'google-places-reviews' );
            break;
        case 'max_width':
            $tip_text = esc_html__( 'Define a max-width property for the widget. Dimension value can be in pixel or percentage. Default value is \'250px\'.', 'google-places-reviews' );
            break;
        case 'pre_content':
            $tip_text = esc_html__( 'Output content before the main widget content. Useful to provide introductory text.', 'google-places-reviews' );
            break;
        case 'post_content':
            $tip_text = esc_html__( 'Output content after the main widget content. Useful to provide a button or custom text inviting the user to perform an action or read a message.', 'google-places-reviews' );
            break;
    }

    return '<img src="' . GPR_PLUGIN_URL . '/includes/legacy/assets/images/help.png" title="' . $tip_text . '" class="tooltip-info" width="16" height="16" />';

}
