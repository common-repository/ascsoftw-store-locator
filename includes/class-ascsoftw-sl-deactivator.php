<?php
/**
 * Fired during plugin deactivation
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

if ( ! class_exists( 'Ascsoftw_Sl_Deactivator' ) ) {
	/**
	 * Fired during plugin deactivation.
	 *
	 * This class defines all code necessary to run during the plugin's deactivation.
	 *
	 * @since      1.0.0
	 * @package    Ascsoftw_Sl
	 * @subpackage Ascsoftw_Sl/includes
	 * @author     Sunil Guleria <guleria.sunil2004@gmail.com>
	 */
	class Ascsoftw_Sl_Deactivator {

		/**
		 * Code to Deactivate Plugin.
		 *
		 * @since    1.0.0
		 */
		public static function deactivate() {
			flush_rewrite_rules();
		}

	}
}
