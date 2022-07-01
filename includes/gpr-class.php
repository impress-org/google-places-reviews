<?php

/**
 * Class WP_Google_Places_Reviews_Free
 */
class WP_Google_Places_Reviews_Free {

    /**
     * Plugin Instance
     *
     * @var null
     */
    protected static $_instance = null;

    /**
     * Google API key
     *
     * @var string
     */
    public $api_key;

    /**
     * Main Instance
     *
     * Ensures only one instance of GPR is loaded or can be loaded.
     *
     * @since 1.4
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Class Constructor
     */
    public function __construct() {

        add_action( 'init', [ $this, 'load_plugin_textdomain' ] );
        add_action( 'init', [ $this, 'register_settings' ] );
        add_action( 'rest_api_init', [ $this, 'google_api_rest_endpoint' ] );

        // Register the widget if using older version of WP or Classic Widgets plugin is installed.
        if (
            ! version_compare( $GLOBALS['wp_version'], '5.8', '>=' )
            || ( in_array( 'classic-widgets/classic-widgets.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) )
        ) {
            add_action( 'widgets_init', [ $this, 'setup_widget' ] );
            add_action( 'wp_ajax_gpr_free_clear_widget_cache', [ $this, 'clear_widget_cache' ] );
        }

        // Gutenberg block
        if ( function_exists( 'register_block_type' ) ) {
            add_action( 'init', [ $this, 'register_block' ], 999 );
            add_action( 'block_categories_all', [ $this, 'register_block_category' ] );
            add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_block_admin_assets' ] );
        }


        $options       = get_option( 'googleplacesreviews_options', [ 'google_places_api_key' => null ] );
        $this->api_key = $options['google_places_api_key'];

    }

    /**
     * Loads the plugin language files
     *
     * @access public
     * @return void
     * @since  1.0
     */
    function load_plugin_textdomain() {

        // Set filter for plugins' languages directory
        $gpr_lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
        $gpr_lang_dir = apply_filters( 'gpr_languages_directory', $gpr_lang_dir );

        // Traditional WordPress plugin locale filter
        $locale = apply_filters( 'plugin_locale', get_locale(), 'google-places-reviews' );
        $mofile = sprintf( '%1$s-%2$s.mo', 'give', $locale );

        // Setup paths to current locale file
        $mofile_local  = $gpr_lang_dir . $mofile;
        $mofile_global = WP_LANG_DIR . '/google-places-reviews/' . $mofile;

        if ( file_exists( $mofile_global ) ) {
            // Look in global /wp-content/languages/google-places-reviews folder
            load_textdomain( 'google-places-reviews', $mofile_global );
        } elseif ( file_exists( $mofile_local ) ) {
            // Look in local location from filter `gpr_languages_directory`
            load_textdomain( 'google-places-reviews', $mofile_local );
        } else {
            // Load the default language files packaged up w/ plugin
            load_plugin_textdomain( 'google-places-reviews', false, $gpr_lang_dir );
        }

    }

    /**
     * Plugin Setup
     */
    public function setup_widget() {
        require GPR_PLUGIN_PATH . 'includes/legacy/widget.php';
        register_widget( 'Google_Places_Reviews' );

        // Admin only
        if ( is_admin() ) {
            // Options page.
            require_once GPR_PLUGIN_PATH . 'includes/admin-settings.php';

            // Deactivating normal activation banner for upgrade to Place ID banner
            require_once GPR_PLUGIN_PATH . 'includes/plugin-listing-page.php';

            // Display our upgrade notice
            require_once GPR_PLUGIN_PATH . 'includes/legacy/upgrades/upgrade-functions.php';
            require_once GPR_PLUGIN_PATH . 'includes/legacy/upgrades/upgrades.php';
        }
    }

    /**
     * Register REST API endpoint
     * @return void
     */
    public function google_api_rest_endpoint() {
        register_rest_route( 'google-block/v1', 'profile/',
            [
                'methods'             => 'GET',
                'callback'            => [ $this, 'google_api_rest_callback' ],
                'permission_callback' => '__return_true',
            ]
        );
    }


    /**
     * @return void
     */
    public function register_settings() {

        register_setting(
            'googleplacesreviews_options',
            'googleplacesreviews_options',
            [
                'default'      => '',
                'show_in_rest' => [
                    'schema' => [
                        'type'       => 'object',
                        'properties' => [
                            'google_places_api_key' => [
                                'type' => 'string',
                            ],
                        ]
                    ],
                ]
            ] );
    }


    /**
     * @param WP_REST_Request $request
     *
     * @return WP_Error|WP_REST_Response
     */
    public function google_api_rest_callback( WP_REST_Request $request ) {

        // Get parameters from request
        $params = $request->get_params();

        // Check if a transient exists.
        $business_details = get_transient( $params['placeId'] );
        if ( $business_details ) {
            return $business_details;
        }

        // No transient found, so get the business details from Google.
        $requestUrl = add_query_arg(
            [
                'placeid' => $params['placeId'] ?? 'ChIJ37HL3ry3t4kRv3YLbdhpWXE',
                'key'     => $params['apiKey'] ?? $this->api_key,
            ],
            'https://maps.googleapis.com/maps/api/place/details/json'
        );

        $requestRequest = wp_safe_remote_get( $requestUrl );
        $requestBody    = json_decode( wp_remote_retrieve_body( $requestRequest ) );

        if ( $requestBody->error_message ) {
            return new WP_Error( $requestBody->status, $requestBody->error_message, [ 'status' => 400 ] );
        }

        set_transient( $params['placeId'], $requestBody->result, HOUR_IN_SECONDS );

        // Create the response object
        return new WP_REST_Response( $requestBody->result, 200 );

    }

    /**
     * Register Gutenberg block
     */
    public function register_block() {

        $assets = require( GPR_PLUGIN_PATH . 'build/google-block.asset.php' );

        wp_register_script(
            'reviews-block-google-script',
            plugins_url( 'build/google-block.js', GPR_PLUGIN_FILE ),
            $assets['dependencies'],
            $assets['version']
        );

        register_block_type(
            GPR_PLUGIN_PATH,
            [ 'render_callback' => [ $this, 'render_block' ], ]
        );
    }

    /**
     * Enqueue the block's admin assets.
     * @return void
     * @since 2.0.0
     */
    public function enqueue_block_admin_assets() {
        wp_localize_script( 'google-places-reviews-reviews-editor-script', 'googleBlockSettings', [
            'apiKey' => $this->api_key,
        ] );
    }

    /**
     * @param $attributes
     * @param $content
     *
     * @return false|string
     */
    function render_block( $attributes, $content ) {

        // Only frontend scripts.
        if ( ! is_admin() ) {
            wp_enqueue_script( 'reviews-block-google-script' );
            wp_set_script_translations( 'reviews-block-google-script', 'google-places-reviews' );
        }

        ob_start(); ?>
        <div id="reviews-block-google-"
             class="root-google-reviews-block"
            <?php
            // 🔁 Loop through and set attributes per block.
            foreach ( $attributes as $key => $value ) :
                // Arrays need to be stringified.
                if ( is_array( $value ) ) {
                    $value = implode( ', ', $value );
                } ?>
                data-<?php
                // output as hyphen-case so that it's changed to camelCase in JS.
                echo preg_replace( '/([A-Z])/', '-$1', $key ); ?>="<?php
                echo esc_attr( $value ); ?>"
            <?php
            endforeach; ?>></div>
        <?php
        // Return clean buffer
        return ob_get_clean();
    }

    /**
     * Register GPR category
     *
     * @param array $categories
     *
     * @return array
     */
    function register_block_category( $categories ) {
        return array_merge(
            $categories,
            [
                [
                    'slug'  => 'gpr',
                    'title' => __( 'GPR Block', 'google-places-reviews' ),
                ],
            ]
        );
    }

    /**
     * AJAX Clear Widget Cache
     */
    public function clear_widget_cache() {

        $transient_1 = esc_html( $_POST['transient_id_1'] );
        $transient_2 = esc_html( $_POST['transient_id_2'] );

        if ( isset( $transient_1, $transient_2 ) ) {
            delete_transient( $transient_1 );
            delete_transient( $transient_2 );
            esc_html_e( 'Cache cleared', 'google-places-reviews' );
        } else {
            esc_html_e( 'Error: Transient ID not set. Cache not cleared.', 'google-places-reviews' );
        }

        wp_die();

    }


} //end class
