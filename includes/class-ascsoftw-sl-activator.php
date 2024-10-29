<?php
/**
 * Fired during plugin activation
 *
 * @link       http://github.com/ascsoftw
 * @since      1.0.0
 *
 * @package    Ascsoftw_Sl
 * @subpackage Ascsoftw_Sl/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! class_exists( 'Ascsoftw_Sl_Activator' ) ) {
	/**
	 * Fired during plugin activation.
	 *
	 * This class defines all code necessary to run during the plugin's activation.
	 *
	 * @since      1.0.0
	 * @package    Ascsoftw_Sl
	 * @subpackage Ascsoftw_Sl/includes
	 * @author     Sunil Guleria <guleria.sunil2004@gmail.com>
	 */
	class Ascsoftw_Sl_Activator {

		/**
		 * Code Fired when Plugin is activated.
		 *
		 * @since    1.0.0
		 */
		public static function activate() {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ascsoftw-sl-post-types.php';
			$plugin_post_types = new Ascsoftw_Sl_Post_Types();
			$plugin_post_types->create_custom_post_types();
			flush_rewrite_rules();
		}

	}
}
