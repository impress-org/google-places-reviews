<?php
/**
 * Plugin Name: Reviews Block for Google
 * Plugin URI: https://wpbusinessreviews.com/
 * Description: Easily display Google business reviews and ratings with a simple and intuitive WordPress block.
 * Version: 2.0.1
 * Author: WP Business Reviews
 * Author URI: https://wpbusinessreviews.com/
 * Requires PHP: 7.2
 * Requires WP: 4.7
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: google-places-reviews
 */

// Define Constants
define('GPR_VERSION', '2.0.1');
define('GPR_PLUGIN_FILE', __FILE__);
define('GPR_PLUGIN_NAME', plugin_basename(GPR_PLUGIN_FILE));
define('GPR_PLUGIN_PATH', plugin_dir_path(GPR_PLUGIN_FILE));
define('GPR_PLUGIN_URL', untrailingslashit(plugin_dir_url(GPR_PLUGIN_FILE)));

require 'includes/gpr-class.php';

/**
 * Returns the main instance.
 *
 * @since  1.4
 * @return WP_Google_Places_Reviews_Free()
 */
function WP_Google_Places_Reviews_Free() {
	return WP_Google_Places_Reviews_Free::instance();
}

WP_Google_Places_Reviews_Free();


