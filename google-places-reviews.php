<?php
/**
 * Plugin Name: Google Places Reviews
 * Plugin URI: https://wpbusinessreviews.com/
 * Description: Display Google Places Reviews for one or many businesses anywhere on your WordPress site using an easy to use and intuitive widget.
 * Version: 1.5.2
 * Author: Impress.org
 * Author URI: https://impress.org/
 * Text Domain: google-places-reviews
 * License: GPL2
 */

// Define Constants
define('GPR_VERSION', '1.5.2');
define('GPR_PLUGIN_FILE', __FILE__);
define('GPR_PLUGIN_NAME', plugin_basename(GPR_PLUGIN_FILE));
define('GPR_PLUGIN_PATH', plugin_dir_path(GPR_PLUGIN_FILE));
define('GPR_PLUGIN_URL', untrailingslashit(plugin_dir_url(GPR_PLUGIN_FILE)));

require 'includes/gpr-class.php';

/**
 * Returns the main instance of Google Places Reviews.
 *
 * @since  1.4
 * @return WP_Google_Places_Reviews_Free()
 */
function WP_Google_Places_Reviews_Free() {
	return WP_Google_Places_Reviews_Free::instance();
}

WP_Google_Places_Reviews_Free();


