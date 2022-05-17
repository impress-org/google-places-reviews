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

		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'options_scripts' ) );

		// Register the Google Places widget.
		add_action( 'widgets_init', array( $this, 'setup_widget' ) );

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
			require GPR_PLUGIN_PATH . '/includes/widget.php';
			register_widget( 'Google_Places_Reviews' );
		}

		// Admin only
		if ( is_admin() ) {

			// Options page.
			require_once GPR_PLUGIN_PATH . '/includes/admin-settings.php';

			// Deactivating normal activation banner for upgrade to Place ID banner
			require_once GPR_PLUGIN_PATH . '/includes/plugin-listing-page.php';

			// Display our upgrade notice
			require_once GPR_PLUGIN_PATH . '/includes/upgrades/upgrade-functions.php';
			require_once GPR_PLUGIN_PATH . '/includes/upgrades/upgrades.php';

		}


	}


	/**
	 * Options Scripts
	 *
	 * Custom JS/CSS for Options Page
	 *
	 * @param $hook
	 */
	function options_scripts( $hook ) {

		// Only on settings page
		if ( 'settings_page_google_places_reviews' === $hook ) {
			wp_register_style( 'gpr_custom_options_styles', plugin_dir_url( __FILE__ ) . '/assets/dist/css/admin-main.css' );
			wp_enqueue_style( 'gpr_custom_options_styles' );
		}

		$screen = get_current_screen();

		if ( $screen->is_block_editor && $screen->base !== 'widgets' ) {

			wp_register_style( 'gpr_widget', GPR_PLUGIN_URL . '/assets/dist/css/public-main.css' );
			wp_enqueue_style( 'gpr_widget' );

			wp_enqueue_script(
				'gbr_block',
				GPR_PLUGIN_URL . '/assets/dist/js/block.js',
				[
					'wp-i18n',
					'wp-element',
					'wp-blocks',
					'wp-editor',
					'gpr_google_places_gmaps'
				],
				GPR_VERSION
			);
		}
	}

	/**
	 * Register Gutenberg block
	 */
	function register_block() {
		register_block_type(
			'google-places-reviews/reviews',
			[
				'editor_script'   => 'gbr_block',
				'editor_style'    => 'gpr_widget_admin_css',
				'render_callback' => function ( $attributes ) {
					ob_start();
					the_widget( 'Google_Places_Reviews', null, $attributes );

					return ob_get_clean();
				},
				'attributes'      => [
					'title'                => [
						'type' => 'string'
					],
					'location'             => [
						'type' => 'string'
					],
					'reference'            => [
						'type' => 'string'
					],
					'place_id'             => [
						'type' => 'string'
					],
					'place_type'           => [
						'type' => 'string'
					],
					'cache'                => [
						'type' => 'string'
					],
					'disable_title_output' => [
						'type'    => 'boolean',
						'default' => false,
					],
					'widget_style'         => [
						'type' => 'string'
					],
					'review_filter'        => [
						'type' => 'string'
					],
					'review_limit'         => [
						'type'    => 'number',
						'default' => 3,
					],
					'review_characters'    => [
						'type' => 'string'
					],
					'hide_header'          => [
						'type'    => 'boolean',
						'default' => false,
					],
					'hide_out_of_rating'   => [
						'type'    => 'boolean',
						'default' => false,
					],
					'hide_google_image'    => [
						'type'    => 'boolean',
						'default' => false,
					],
					'target_blank'         => [
						'type'    => 'boolean',
						'default' => true,
					],
					'no_follow'            => [
						'type'    => 'boolean',
						'default' => true,
					]
				]
			]
		);
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
			echo __( 'Cache cleared', 'google-places-reviews' );

		} else {
			echo __( 'Error: Transient ID not set. Cache not cleared.', 'google-places-reviews' );
		}

		wp_die();

	}


} //end class
