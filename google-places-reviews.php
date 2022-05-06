<?php
/**
 * Plugin Name: Google Places Reviews Block
 * Plugin URI: https://wpbusinessreviews.com/
 * Description: Easily display Google business reviews and ratings with a simple and intuitive WordPress block.
 * Version: 2.0.0
 * Author: WP Business Reviews
 * Author URI: https://wpbusinessreviews.com/
 * Text Domain: google-places-reviews
 * License: GPLv2
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; version 2 of the License.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

// Define Constants
define('GPR_VERSION', '2.0.0');
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


