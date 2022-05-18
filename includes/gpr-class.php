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

        add_action( 'rest_api_init', [ $this, 'google_api_rest_endpoint' ] );

        // Register the Google Places widget.
        add_action( 'widgets_init', [ $this, 'setup_widget' ] );

        // Gutenberg block
        if ( function_exists( 'register_block_type' ) ) {
            add_action( 'init', [ $this, 'register_block' ], 999 );
            add_action( 'block_categories_all', [ $this, 'register_block_category' ] );
        }

        add_action( 'wp_ajax_gpr_free_clear_widget_cache', [ $this, 'clear_widget_cache' ] );
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
        // Include the widget
        if ( ! class_exists( 'Google_Places_Reviews' ) ) {
            // Include the widget
            require GPR_PLUGIN_PATH . 'includes/legacy/widget.php';
            register_widget( 'Google_Places_Reviews' );
        }

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
                'callback'            => [$this, 'google_api_rest_callback'],
                'permission_callback' => '__return_true',
            ]
        );
    }



    /**
     * Custom REST endpoint callback to output Google API data.
     *
     * @param WP_REST_Request $request
     *
     * @return WP_Error|WP_REST_Response
     */
    function google_api_rest_callback( WP_REST_Request $request ) {

        // Get parameters from request
        $params = $request->get_params();



    }

    /**
     * Register Gutenberg block
     */
    function register_block() {

        $assets = require( GPR_PLUGIN_PATH . 'build/google-block.asset.php' );

        wp_register_script(
            'reviews-block-google-script',
            plugins_url( 'build/google-block.js', GPR_PLUGIN_FILE ),
            $assets['dependencies'],
            $assets['version']
        );

        register_block_type(
            GPR_PLUGIN_PATH,
            [
                'render_callback' => [ $this, 'render_block' ],
            ]
        );
    }

    /**
     * @param $attributes
     * @param $content
     *
     * @return false|string
     */
    function render_block( $attributes, $content ) {

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
                echo esc_html( $value ); ?>"
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

        if ( isset( $_POST['transient_id_1'], $_POST['transient_id_2'] ) ) {

            delete_transient( $_POST['transient_id_1'] );
            delete_transient( $_POST['transient_id_2'] );
            esc_html_e( 'Cache cleared', 'google-places-reviews' );

        } else {
            esc_html_e( 'Error: Transient ID not set. Cache not cleared.', 'google-places-reviews' );
        }

        wp_die();

    }


} //end class
