<?php
/**
 * The file that defines this Plugin Options
 *
 * @link       http://github.com/ascosftw
 * @since      1.0.0
 *
 * @package    Ascsoftw_Sl
 * @subpackage Ascsoftw_Sl /includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! class_exists( 'Ascsoftw_Sl_Options' ) ) {
	/**
	 * The core plugin class.
	 *
	 * This is used to hold all the Options of this Plugin
	 *
	 * @since      1.0.0
	 * @package    Ascsoftw_Sl
	 * @subpackage Ascsoftw_Sl/includes
	 * @author     Sunil Guleria <guleria.sunil2004@gmail.com>
	 */
	class Ascsoftw_Sl_Options {

		/**
		 * Constructor
		 *
		 * @since    1.0.0
		 */
		public function __construct() {
			$this->get_all_options();
		}

		/**
		 * Get All Options.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function get_all_options() {

			$this->api_key = get_option( 'ascsoftw_sl_google_apikey' );

			$this->result_format              = new stdClass();
			$this->result_format->position    = get_option( 'ascsoftw_sl_result_position', 'left' );
			$this->result_format->show        = (int) get_option( 'ascsoftw_sl_result_show', 1 );
			$this->result_format->country     = (int) get_option( 'ascsoftw_sl_result_sc', 0 );
			$this->result_format->zip         = (int) get_option( 'ascsoftw_sl_result_sz', 0 );
			$this->result_format->state       = (int) get_option( 'ascsoftw_sl_result_ss', 0 );
			$this->result_format->address2    = (int) get_option( 'ascsoftw_sl_result_sa2', 0 );
			$this->result_format->url         = (int) get_option( 'ascsoftw_sl_result_su', 1 );
			$this->result_format->bounce      = (int) get_option( 'ascsoftw_sl_result_bm', 1 );
			$this->result_format->open_marker = (int) get_option( 'ascsoftw_sl_result_om', 1 );

			$this->search_form                    = new stdClass();
			$this->search_form->max_dropdown      = get_option( 'ascsoftw_sl_sf_sm', 'yes' );
			$this->search_form->radius_dropdown   = get_option( 'ascsoftw_sl_sf_sr', 'yes' );
			$this->search_form->category_dropdown = get_option( 'ascsoftw_sl_sf_sc', 'yes' );
			$this->search_form->distance_unit     = get_option( 'ascsoftw_sl_radius_search_unit', 'KM' );
			$this->search_form->show_summary      = (int) get_option( 'ascsoftw_sl_sf_ss', 1 );

			$this->map_settings           = new stdClass();
			$this->map_settings->height   = get_option( 'ascsoftw_sl_map_height', '400' );
			$this->map_settings->zoom     = (int) get_option( 'ascsoftw_sl_map_zoom', 5 );
			$this->map_settings->map_type = get_option( 'ascsoftw_sl_map_type', 'roadmap' );

			$this->map_settings->disable_zoom_flag        = get_option( 'ascsoftw_sl_control_zoom', 'no' );
			$this->map_settings->disable_map_type_flag    = get_option( 'ascsoftw_sl_control_map_type', 'no' );
			$this->map_settings->disable_full_screen_flag = get_option( 'ascsoftw_sl_control_zoom', 'no' );
			$this->map_settings->zoom_position            = get_option( 'ascsoftw_sl_zoom_position', '' );
			$this->map_settings->map_type_position        = get_option( 'ascsoftw_sl_maptype_position', '' );
			$this->map_settings->fullscreen_position      = get_option( 'ascsoftw_sl_fullscreen_position', '' );
			$this->map_settings->infowindow_content       = get_option( 'ascsoftw_sl_map_content', '' );
			if ( empty( $this->map_settings->infowindow_content ) ) {
				$this->map_settings->infowindow_content = '{$title}';
			}
		}

		/**
		 * Return Map Options.
		 *
		 * @since 1.0.0
		 * @return array   $map_options Array as Google Map wants
		 */
		public function get_map_options() {

			$map_options              = array();
			$map_options['zoom']      = (int) $this->map_settings->zoom;
			$map_options['mapTypeId'] = $this->map_settings->map_type;

			if ( 'yes' === $this->map_settings->disable_zoom_flag ) {
				$map_options['zoomControl'] = false;
			} else {
				if ( ! empty( $this->map_settings->zoom_position ) ) {
					$map_options['zoomControlOptions'] = array( 'position' => $this->map_settings->zoom_position );
				}
			}

			if ( 'yes' === $this->map_settings->disable_map_type_flag ) {
				$map_options['mapTypeControl'] = false;
			} else {
				if ( ! empty( $this->map_settings->map_type_position ) ) {
					$map_options['mapTypeControlOptions'] = array( 'position' => $this->map_settings->map_type_position );
				}
			}

			if ( 'yes' === $this->map_settings->disable_full_screen_flag ) {
				$map_options['fullscreenControl'] = false;
			} else {
				if ( ! empty( $this->map_settings->fullscreen_position ) ) {
					$map_options['fullscreenControlOptions'] = array( 'position' => $this->map_settings->fullscreen_position );
				}
			}
			return $map_options;
		}

		/**
		 * Get Distance Option
		 *
		 * @since 1.0.0
		 * @return array options to be used in distance dropdown.
		 */
		public function get_distance_options() {

			return array(
				'1'   => '1',
				'5'   => '5',
				'10'  => '10',
				'50'  => '50',
				'100' => '100',
				'500' => '500',
			);
		}

		/**
		 * Get Dropdown to show max results
		 *
		 * @since 1.0.0
		 * @return array options to be used in max results dropdown.
		 */
		public function get_max_results_options() {

			return array(
				'5'  => '5',
				'10' => '10',
				'20' => '20',
				'50' => '50',
			);
		}

		/**
		 * Get Default number of results to be shownn
		 *
		 * @since 1.0.0
		 * @return int number of results to be shown initially.
		 */
		public function get_default_results() {
			return 10;
		}
	}
}
