<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.philosophische-insel.ch/
 * @since             1.0.1
 * @package           Vegan_Cockpit
 *
 * @wordpress-plugin
 * Plugin Name:       Vegan Cockpit
 * Plugin URI:        https://www.philosophische-insel.ch/
 * Description:       Vegan Cockpit - Google Analytics with Wordpress Categories
 * Version:           1.0.6
 * Author:            Sandro Räss
 * Author URI:        https://www.philosophische-insel.ch/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       vegan-cockpit
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'VEGAN_COCKPIT_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-vegan-cockpit-activator.php
 */
function activate_vegan_cockpit() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-vegan-cockpit-activator.php';
	Vegan_Cockpit_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-vegan-cockpit-deactivator.php
 */
function deactivate_vegan_cockpit() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-vegan-cockpit-deactivator.php';
	Vegan_Cockpit_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_vegan_cockpit' );
register_deactivation_hook( __FILE__, 'deactivate_vegan_cockpit' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-vegan-cockpit.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_vegan_cockpit() {

	$plugin = new Vegan_Cockpit();
	$plugin->run();

}
run_vegan_cockpit();
