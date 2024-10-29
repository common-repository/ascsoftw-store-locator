<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://github.com/ascsoftw
 * @since      1.0.0
 *
 * @package    Ascsoftw_Sl
 * @subpackage Ascsoftw_Sl/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! class_exists( 'Ascsoftw_Sl_Admin' ) ) {

	/**
	 * The admin-specific functionality of the plugin.
	 *
	 * Defines the plugin name, version, and two examples hooks for how to
	 * enqueue the admin-specific stylesheet and JavaScript.
	 *
	 * @package    Ascsoftw_Sl
	 * @subpackage Ascsoftw_Sl/admin
	 * @author     Sunil Guleria <guleria.sunil2004@gmail.com>
	 */
	class Ascsoftw_Sl_Admin {

		/**
		 * The ID of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $plugin_name    The ID of this plugin.
		 */
		private $plugin_name;

		/**
		 * The version of this plugin.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    string $version The current version of this plugin.
		 */
		private $version;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 * @param string $plugin_name The name of this plugin.
		 * @param string $version The version of this plugin.
		 */
		public function __construct( $plugin_name, $version ) {

			$this->plugin_name = $plugin_name;
			$this->version     = $version;

		}

		/**
		 * Register the stylesheets for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles() {
			$screen = get_current_screen();
			if ( 'ascsoftw_sl' === $screen->post_type ) {
				wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ascsoftw-sl-admin.css', array(), $this->version, 'all' );
			}
		}

		/**
		 * Register the scripts for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts() {
			$screen = get_current_screen();
			if ( 'ascsoftw_sl' === $screen->post_type ) {
				wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ascsoftw-sl-admin.js', array(), $this->version, 'all' );
			}
		}

		/**
		 * Add Admin Plugin Menu and Settings Page
		 */
		public function add_admin_menu_using_helper() {

			$zoom_options = range( 1, 15 );
			$zoom_options = array_combine( $zoom_options, $zoom_options );

			$map_types = array( 'roadmap', 'satellite', 'hybrid', 'terrain' );
			$map_types = array_combine( $map_types, $map_types );

			$map_height = array( '300', '400', '500', '600', '700' );
			$map_height = array_combine( $map_height, $map_height );

			$radius_search_unit = array(
				'KM'    => __( 'KM', 'ascsoftw-store-locator' ),
				'Miles' => __( 'Miles', 'ascsoftw-store-locator' ),
			);

			$radio_options = array(
				'yes' => __( 'Yes', 'ascsoftw-store-locator' ),
				'no'  => __( 'No', 'ascsoftw-store-locator' ),
			);

			$google_map_positions = array(
				''   => __( 'Default', 'ascsoftw-store-locator' ),
				'1'  => __( 'Top Left', 'ascsoftw-store-locator' ),
				'2'  => __( 'Top Center', 'ascsoftw-store-locator' ),
				'3'  => __( 'Top Right', 'ascsoftw-store-locator' ),
				'4'  => __( 'Left Center', 'ascsoftw-store-locator' ),
				'5'  => __( 'Left Top', 'ascsoftw-store-locator' ),
				'6'  => __( 'Left Bottom', 'ascsoftw-store-locator' ),
				'7'  => __( 'Right', 'ascsoftw-store-locator' ),
				'8'  => __( 'Right Center', 'ascsoftw-store-locator' ),
				'9'  => __( 'Right Bottom', 'ascsoftw-store-locator' ),
				'10' => __( 'Bottom Left', 'ascsoftw-store-locator' ),
				'11' => __( 'Bottom Center', 'ascsoftw-store-locator' ),
				'12' => __( 'Bottom Right', 'ascsoftw-store-locator' ),
				'13' => __( 'Center', 'ascsoftw-store-locator' ),
			);

			$google_map_marker_note = '<p>' . __( 'Note: Kindly use following placeholders', 'ascsoftw-store-locator' ) . '</p><p>{$title}, {$email} , {$address} , {$address_2} , {$city}, {$state}, {$zip}, {$country}, {$phone}, </p>';

			require_once ASCSOFTW_SL_PLUGINS_DIR . 'vendor/boo-settings-helper/class-boo-settings-helper.php';
			$settings = array(
				'menu'     => array(
					'slug'       => 'ascsoftw_sl',
					'page_title' => __( 'Store Locator Settings', 'ascsoftw-store-locator' ),
					'menu_title' => __( 'Store Locator Settings', 'ascsoftw-store-locator' ),
					'submenu'    => true,
					'parent'     => 'edit.php?post_type=ascsoftw_sl',
				),
				'sections' => array(
					array(
						'id'    => 'ascsoftw-sl-initial-section',
						'title' => __( 'Initial Section', 'ascsoftw-store-locator' ),
						'desc'  => __( 'This Section contains mandatory fields required to get you started', 'ascsoftw-store-locator' ),
					),
					array(
						'id'    => 'ascsoftw-sl-search-section',
						'title' => __( 'Search Form', 'ascsoftw-store-locator' ),
						'desc'  => __( 'Customize your Search Form Settings', 'ascsoftw-store-locator' ),
					),
					array(
						'id'    => 'ascsoftw-sl-result-section',
						'title' => __( 'Result Section', 'ascsoftw-store-locator' ),
						'desc'  => __( 'Customize your Result Section', 'ascsoftw-store-locator' ),
					),
					array(
						'id'    => 'ascsoftw-sl-map-section',
						'title' => __( 'Map', 'ascsoftw-store-locator' ),
						'desc'  => __( 'Customize your Google Maps', 'ascsoftw-store-locator' ),
					),
				),
				'fields'   => array(
					'ascsoftw-sl-initial-section' => array(
						array(
							'id'    => 'ascsoftw_sl_google_apikey',
							'label' => __( 'Google API Key', 'ascsoftw-store-locator' ),
						),
					),
					'ascsoftw-sl-result-section'  => array(
						array(
							'id'      => 'ascsoftw_sl_result_show',
							'label'   => __( 'Display Result Section?', 'ascsoftw-store-locator' ),
							'type'    => 'checkbox',
							'default' => 1,
							'desc'    => __( 'Show Result Section next to Google Maps.', 'ascsoftw-store-locator' ),
						),
						array(
							'id'      => 'ascsoftw_sl_result_position',
							'label'   => __( 'Position', 'ascsoftw-store-locator' ),
							'type'    => 'select',
							'options' => array(
								'left'  => __( 'Left', 'ascsoftw-store-locator' ),
								'right' => __( 'Right', 'ascsoftw-store-locator' ),
							),
							'default' => 'left',
							'desc'    => __( 'Controls whether Section is shown Left or Right to Google Map.', 'ascsoftw-store-locator' ),
						),
						array(
							'id'    => 'ascsoftw_sl_result_sc',
							'label' => __( 'Show Country?', 'ascsoftw-store-locator' ),
							'type'  => 'checkbox',
							'desc'  => __( 'Show Country in Result Listing.', 'ascsoftw-store-locator' ),
						),
						array(
							'id'    => 'ascsoftw_sl_result_sz',
							'label' => __( 'Show Zip Code?', 'ascsoftw-store-locator' ),
							'type'  => 'checkbox',
							'desc'  => __( 'Show Zip Code Field in Result Listing.', 'ascsoftw-store-locator' ),
						),
						array(
							'id'    => 'ascsoftw_sl_result_ss',
							'label' => __( 'Show State?', 'ascsoftw-store-locator' ),
							'type'  => 'checkbox',
							'desc'  => __( 'Show State in Result Listing.', 'ascsoftw-store-locator' ),
						),
						array(
							'id'    => 'ascsoftw_sl_result_sa2',
							'label' => __( 'Show Address 2?', 'ascsoftw-store-locator' ),
							'type'  => 'checkbox',
							'desc'  => __( 'Show Address 2 in Result Listing.', 'ascsoftw-store-locator' ),
						),
						array(
							'id'      => 'ascsoftw_sl_result_su',
							'label'   => __( 'Link URL to Store Name?', 'ascsoftw-store-locator' ),
							'type'    => 'checkbox',
							'default' => 1,
							'desc'    => __( 'If checked, clicking on Store Name will open Store URL.', 'ascsoftw-store-locator' ),
						),
						array(
							'id'      => 'ascsoftw_sl_result_bm',
							'label'   => __( 'Bounce on Mouseover?', 'ascsoftw-store-locator' ),
							'type'    => 'checkbox',
							'default' => 1,
							'desc'    => __( 'When User moves mouse over the Result Listing correponsing Marker on Google Maps will Bounce.', 'ascsoftw-store-locator' ),
						),
						array(
							'id'      => 'ascsoftw_sl_result_om',
							'label'   => __( 'Open Marker on Click?', 'ascsoftw-store-locator' ),
							'type'    => 'checkbox',
							'default' => 1,
							'desc'    => __( 'Clicking on Result Listing will open corresponding Marker on Google Maps', 'ascsoftw-store-locator' ),
						),
					),
					'ascsoftw-sl-search-section'  => array(
						array(
							'id'      => 'ascsoftw_sl_sf_sm',
							'label'   => __( 'Max Result', 'ascsoftw-store-locator' ),
							'type'    => 'radio',
							'options' => $radio_options,
							'default' => 'yes',
							'desc'    => __( 'Show Max Result Dropdown on Search Form', 'ascsoftw-store-locator' ),
						),
						array(
							'id'      => 'ascsoftw_sl_sf_sr',
							'label'   => __( 'Distance Search', 'ascsoftw-store-locator' ),
							'type'    => 'radio',
							'options' => $radio_options,
							'default' => 'yes',
							'desc'    => __( 'Allow User to do Distance Search', 'ascsoftw-store-locator' ),
						),
						array(
							'id'      => 'ascsoftw_sl_radius_search_unit',
							'label'   => __( 'Distance Unit', 'ascsoftw-store-locator' ),
							'type'    => 'radio',
							'options' => $radius_search_unit,
							'default' => 'KM',
							'desc'    => __( 'Unit to be used for Distance Search', 'ascsoftw-store-locator' ),
						),
						array(
							'id'      => 'ascsoftw_sl_sf_sc',
							'label'   => __( 'Category Filter', 'ascsoftw-store-locator' ),
							'type'    => 'radio',
							'options' => $radio_options,
							'default' => 'yes',
							'desc'    => __( 'Show Dropdown to Filter Search by Category', 'ascsoftw-store-locator' ),
						),
						array(
							'id'      => 'ascsoftw_sl_sf_ss',
							'label'   => __( 'Display Number of Results', 'ascsoftw-store-locator' ),
							'type'    => 'checkbox',
							'default' => 1,
							'desc'    => __( 'Show number of results returned after searching.', 'ascsoftw-store-locator' ),
						),
					),
					'ascsoftw-sl-map-section'     => array(
						array(
							'id'      => 'ascsoftw_sl_map_height',
							'label'   => __( 'Map Height (px)', 'ascsoftw-store-locator' ),
							'type'    => 'select',
							'options' => $map_height,
							'default' => '400',
							'desc'    => __( 'Height of the Map', 'ascsoftw-store-locator' ),
						),
						array(
							'id'      => 'ascsoftw_sl_map_zoom',
							'label'   => __( 'Zoom Level', 'ascsoftw-store-locator' ),
							'type'    => 'select',
							'options' => $zoom_options,
							'default' => 5,
							'desc'    => __( 'Default Zoom Level of the Map', 'ascsoftw-store-locator' ),
						),
						array(
							'id'      => 'ascsoftw_sl_map_type',
							'label'   => __( 'Map type', 'ascsoftw-store-locator' ),
							'type'    => 'select',
							'options' => $map_types,
							'default' => 'roadmap',
							'desc'    => __( 'Default Map Type', 'ascsoftw-store-locator' ),
						),
						array(
							'id'      => 'ascsoftw_sl_control_zoom',
							'label'   => __( 'Disable Zoom Control', 'ascsoftw-store-locator' ),
							'type'    => 'radio',
							'options' => $radio_options,
							'default' => 'no',
							'desc'    => __( 'This will hide the Zoom In-Out Button', 'ascsoftw-store-locator' ),
						),
						array(
							'id'      => 'ascsoftw_sl_control_map_type',
							'label'   => __( 'Disable Map Type Control', 'ascsoftw-store-locator' ),
							'type'    => 'radio',
							'options' => $radio_options,
							'default' => 'no',
							'desc'    => __( 'This will hide the button which allows User to change Map Type', 'ascsoftw-store-locator' ),
						),
						array(
							'id'      => 'ascsoftw_sl_control_full_screen',
							'label'   => __( 'Disable Full Screen Control', 'ascsoftw-store-locator' ),
							'type'    => 'radio',
							'options' => $radio_options,
							'default' => 'no',
							'desc'    => __( 'This will hide the Google Map Full Screen Button', 'ascsoftw-store-locator' ),
						),
						array(
							'id'      => 'ascsoftw_sl_zoom_position',
							'label'   => __( 'Zoom Position', 'ascsoftw-store-locator' ),
							'type'    => 'select',
							'options' => $google_map_positions,
							'default' => '',
							'desc'    => __( 'Configure where the Zoom Button is shown on Google Maps.', 'ascsoftw-store-locator' ),
						),
						array(
							'id'      => 'ascsoftw_sl_maptype_position',
							'label'   => __( 'Map Type Position', 'ascsoftw-store-locator' ),
							'type'    => 'select',
							'options' => $google_map_positions,
							'default' => '',
							'desc'    => __( 'Configure where the Map Type Button is shown on Google Maps.', 'ascsoftw-store-locator' ),
						),
						array(
							'id'      => 'ascsoftw_sl_fullscreen_position',
							'label'   => __( 'Full Screen Position', 'ascsoftw-store-locator' ),
							'type'    => 'select',
							'options' => $google_map_positions,
							'default' => '',
							'desc'    => __( 'Configure where the Full Screen Button is shown on Google Maps.', 'ascsoftw-store-locator' ),
						),
						array(
							'id'    => 'ascsoftw_sl_map_content',
							'label' => __( 'InfoWindow Content', 'ascsoftw-store-locator' ),
							'type'  => 'textarea',
							'desc'  => __( 'Configure the Content Shown in the InfoWindow of Google Maps.', 'ascsoftw-store-locator' ),
						),
						array(
							'id'   => 'ascsoftw_sl_content_note',
							'type' => 'html',
							'desc' => $google_map_marker_note,
						),
					),
				),
				'links'    => array(
					'plugin_basename' => plugin_basename( ASCSOFTW_SL_PLUGINS_FILE ),
					'action_links'    => array(
						array(
							'type' => 'default',
							'text' => __( 'Settings', 'ascsoftw-store-locator' ),
						),
					),
				),
			);

			$setting_helper = new Boo_Settings_Helper( $settings );

		}
	}
}
