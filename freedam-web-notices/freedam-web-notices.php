<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/freedom-software
 * @since             1.5.1
 * @package           Freedam_Web_Notices
 *
 * @wordpress-plugin
 * Plugin Name:       FreeDAM Web Notices
 * Plugin URI:        https://wordpress.org/plugins/freedam-web-notices
 * Description:       Retrieves your web notices from your FreeDAM database for displaying on your website.
 * Version:           1.5.1
 * Author:            Freedom Software
 * Author URI:        https://freedomsoftware.co.nz
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       freedam-web-notices
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 *
 * @since    1.5.1
 */
define( 'FREEDAM_WEB_NOTICES_VERSION', '1.5.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-freedam-web-notices-activator.php
 *
 * @since    1.0.0
 */
function activate_freedam_web_notices() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-freedam-web-notices-activator.php';
	Freedam_Web_Notices_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-freedam-web-notices-deactivator.php
 *
 * @since    1.0.0
 */
function deactivate_freedam_web_notices() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-freedam-web-notices-deactivator.php';
	Freedam_Web_Notices_Deactivator::deactivate();
}

// Register the hooks with wordpress so they are run
register_activation_hook( __FILE__, 'activate_freedam_web_notices' );
register_deactivation_hook( __FILE__, 'deactivate_freedam_web_notices' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 *
 * @since    1.0.0
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-freedam-web-notices.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_freedam_web_notices() {

	$plugin = new Freedam_Web_Notices();
	$plugin->run();

}
run_freedam_web_notices();
