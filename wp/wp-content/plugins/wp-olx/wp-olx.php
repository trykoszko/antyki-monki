<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/trykoszko
 * @since             1.0.0
 * @package           Wp_Olx
 *
 * @wordpress-plugin
 * Plugin Name:       WP OLX
 * Plugin URI:        https://github.com/trykoszko/wp-olx
 * Description:       Integracja WordPress z OLX
 * Version:           1.0.0
 * Author:            Michal Trykoszko
 * Author URI:        https://github.com/trykoszko
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-olx
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Require Composer Autoload
 *
 * @since 1.0.0
 */
require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WP_OLX_VERSION', '2.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-olx-activator.php
 */
function activate_wp_olx() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-olx-activator.php';
	Wp_Olx_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-olx-deactivator.php
 */
function deactivate_wp_olx() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-olx-deactivator.php';
	Wp_Olx_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_olx' );
register_deactivation_hook( __FILE__, 'deactivate_wp_olx' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-olx.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_olx() {

	$plugin = new Wp_Olx();
	$plugin->run();

}
run_wp_olx();
