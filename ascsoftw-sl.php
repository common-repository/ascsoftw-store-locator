<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://github.com/ascsoftw
 * @since             1.0.0
 * @package           Ascsoftw_Sl
 *
 * @wordpress-plugin
 * Plugin Name:       Ascsoftw Store Locator
 * Description:       Ascsoftw Store Locator is a powerful plugin which lets your users Search the Nearest Stores and display them in highly customized Google Maps. Search Form and Search Results provide a wide variety of configurable options.
 * Version:           1.0.0
 * Author:            Sunil Guleria
 * Author URI:        http://github.com/ascsoftw
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ascsoftw-store-locator
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 */
define( 'ASCSOFTW_SL_VERSION', '1.0.0' );

/**
 * Plugin base File.
 * Start at version 1.0.0.
 */
define( 'ASCSOFTW_SL_PLUGINS_FILE', __FILE__ );

/**
 * Plugin base dir path.
 * Start at version 1.0.0.
 */
define( 'ASCSOFTW_SL_PLUGINS_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Plugin base url.
 * Start at version 1.0.0.
 */
define( 'ASCSOFTW_SL_PLUGINS_URL', plugin_dir_url( __FILE__ ) );

if ( ! function_exists( 'activate_ascsoftw_sl' ) ) {
	/**
	 * The code that runs during plugin activation.
	 * This action is documented in includes/class-ascsoftw_sl-activator.php
	 */
	function activate_ascsoftw_sl() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-ascsoftw-sl-activator.php';
		Ascsoftw_Sl_Activator::activate();
	}
}

if ( ! function_exists( 'deactivate_ascsoftw_sl' ) ) {
	/**
	 * The code that runs during plugin deactivation.
	 * This action is documented in includes/class-ascsoftw_sl-deactivator.php
	 */
	function deactivate_ascsoftw_sl() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-ascsoftw-sl-deactivator.php';
		Ascsoftw_Sl_Deactivator::deactivate();
	}
}

register_activation_hook( __FILE__, 'activate_ascsoftw_sl' );
register_deactivation_hook( __FILE__, 'deactivate_ascsoftw_sl' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ascsoftw-sl.php';

if ( ! function_exists( 'run_ascsoftw_sl' ) ) {
	/**
	 * Begins execution of the plugin.
	 *
	 * Since everything within the plugin is registered via hooks,
	 * then kicking off the plugin from this point in the file does
	 * not affect the page life cycle.
	 *
	 * @since    1.0.0
	 */
	function run_ascsoftw_sl() {

		$plugin = new Ascsoftw_Sl();
		$plugin->run();

	}
}
run_ascsoftw_sl();
