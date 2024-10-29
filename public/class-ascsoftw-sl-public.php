<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://github.com/ascsoftw
 * @since      1.0.0
 *
 * @package    Ascsoftw_Sl
 * @subpackage Ascsoftw_Sl/public
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! class_exists( 'Ascsoftw_Sl_Public' ) ) {
	/**
	 * The public-facing functionality of the plugin.
	 *
	 * Defines the plugin name, version, and two examples hooks for how to
	 * enqueue the public-facing stylesheet and JavaScript.
	 *
	 * @package    Ascsoftw_Sl
	 * @subpackage Ascsoftw_Sl/public
	 * @author     Sunil Guleria <guleria.sunil2004@gmail.com>
	 */
	class Ascsoftw_Sl_Public {

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
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $version    The current version of this plugin.
		 */
		private $version;

		/**
		 * The options of this plugin.
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      array    $options    All the Options of this plugin
		 */
		public $options;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 * @param string $plugin_name The name of the plugin.
		 * @param string $version The version of this plugin.
		 */
		public function __construct( $plugin_name, $version ) {

			$this->plugin_name = $plugin_name;
			$this->version     = $version;
		}

		/**
		 * Register the stylesheets for the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles() {

			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ascsoftw-sl-public.css', array(), $this->version, 'all' );
		}

		/**
		 * Display the Shortcode for this Plugin
		 *
		 * @since 1.0.0
		 * @param  array  $atts Attributes passed to the Shortcode.
		 * @param  string $content Content passed inside the shortcode.
		 */
		public function ascsoftw_sl_shortcode( $atts, $content ) {

			if ( is_admin() ) {
				return;
			}
			$this->options = new Ascsoftw_Sl_Options();

			wp_enqueue_script( 'ascsoftw_sl_map_api', 'https://maps.googleapis.com/maps/api/js?key=' . $this->options->api_key . '&libraries=places', array(), $this->version, true );
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ascsoftw-sl-public.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( 'wp-util' );

			$distance_options     = $this->options->get_distance_options();
			$max_results_options  = $this->options->get_max_results_options();
			$max_results_selected = $this->options->get_default_results();
			$map_options          = $this->options->get_map_options();

			if ( 'yes' === $this->options->search_form->category_dropdown ) {
				$terms = get_terms(
					array(
						'taxonomy'   => 'ascsoftw_sl',
						'hide_empty' => true,
					)
				);
			}

			wp_register_script( 'ascsoftw-sl-shortcodes', false, array(), $this->version, true );
			$inline_script  = 'var ascsoftw_locations = ' . wp_json_encode( array() ) . ';';
			$inline_script .= 'var ascsoftw_map_options = ' . wp_json_encode( $map_options ) . ';';
			$inline_script .= 'var ascsoftw_result_options = ' . wp_json_encode( $this->options->result_format ) . ';';
			wp_add_inline_script( 'ascsoftw-sl-shortcodes', $inline_script );
			wp_enqueue_script( 'ascsoftw-sl-shortcodes' );

			if ( $this->options->result_format->show ) {
				add_action( 'wp_footer', array( $this, 'result_listing_template' ) );
			}

			list( $first_latitude, $first_longitude ) = $this->get_first_store();

			$this->enqueue_inline_css();

			ob_start();
			include 'partials/ascsoftw-sl-public-display.php';
			return ob_get_clean();
		}


		/**
		 * Function which displays the Result Listing.
		 *
		 * @since    1.0.0
		 */
		public function result_listing_template() {
			include 'partials/ascsoftw-sl-result-listing-template.php';
		}

		/**
		 * Ajax function to get stores by category and location search
		 *
		 * @since    1.0.0
		 */
		public function get_store_search() {

			check_ajax_referer( 'ascsoftw_sl_ajax_nonce', 'security' );

			$category_id   = isset( $_POST['category_id'] ) ? sanitize_text_field( wp_unslash( $_POST['category_id'] ) ) : '';
			$distance      = isset( $_POST['distance'] ) ? sanitize_text_field( wp_unslash( $_POST['distance'] ) ) : 0;
			$distance_unit = isset( $_POST['distance_unit'] ) ? sanitize_text_field( wp_unslash( $_POST['distance_unit'] ) ) : 'KM';
			$latitude      = isset( $_POST['lat'] ) ? sanitize_text_field( wp_unslash( $_POST['lat'] ) ) : '';
			$longitude     = isset( $_POST['long'] ) ? sanitize_text_field( wp_unslash( $_POST['long'] ) ) : '';
			$max_results   = isset( $_POST['max_results'] ) ? sanitize_text_field( wp_unslash( $_POST['max_results'] ) ) : 0;

			$this->options = new Ascsoftw_Sl_Options();
			if ( ! $max_results > 0 ) {
				$max_results = $this->options->get_default_results();
			}

			$post_ids = $this->distance_results(
				array(
					'latitude'      => $latitude,
					'longitude'     => $longitude,
					'distance_unit' => $distance_unit,
					'distance'      => $distance,
					'max_results'   => $max_results,
					'category_id'   => $category_id,
				)
			);

			$response = array();
			if ( empty( $post_ids ) ) {
				echo wp_json_encode( $response );
				wp_die();
			}

			$response = $this->format_results( $post_ids );
			echo wp_json_encode( $response );
			wp_die();

		}

		/**
		 * Get Posts based on Options.
		 *
		 * @since 1.0.0
		 * @param $options $options should contain following keys.
		 * latitude Latitude from which Distance Needs to be calcualted.
		 * longitude Longitude from which Distance Needs to be calcualted.
		 * distance_unit Miles or KM.
		 * distance The Distance below which data needs to be returned.
		 * category_id Category to Filter the Results.
		 * max_results Number of Results to be returned.
		 * @return array   $post_ids PostIds which satisfy the criteria.
		 */
		private function distance_results( $options ) {

			global $wpdb;
			$post_ids = array();

			if ( 'KM' === $options['distance_unit'] ) {
				$search_radius = floor( $options['distance'] * 1.6 );
				$multiplier    = 6371;
			} else {
				$search_radius = $options['distance'];
				$multiplier    = 3959;
			}

			$params = array();

			$select = "SELECT p.ID, pm1.meta_value as lat, pm2.meta_value as lon, ACOS(SIN(RADIANS( %f))*SIN(RADIANS(pm1.meta_value))+COS(RADIANS( %f ))*COS(RADIANS(pm1.meta_value))*COS(RADIANS(pm2.meta_value)-RADIANS( %f ))) * %f AS distance 
			FROM $wpdb->posts p 
			INNER JOIN $wpdb->postmeta pm1 ON p.id = pm1.post_id AND pm1.meta_key = 'ascsoftw_sl_latitude' 
			INNER JOIN $wpdb->postmeta pm2 ON p.id = pm2.post_id AND pm2.meta_key = 'ascsoftw_sl_longitude'";

			$params = array( $options['latitude'], $options['latitude'], $options['longitude'], $multiplier );

			$where = "WHERE post_type = 'ascsoftw_sl' AND post_status = 'publish'";

			if ( $options['category_id'] > 0 ) {
				$select .= " LEFT JOIN $wpdb->term_relationships ON p.ID = wp_term_relationships.object_id";
				$where  .= ' AND wp_term_relationships.term_taxonomy_id = %d';
				array_push( $params, $options['category_id'] );
			}

			$having = '';
			if ( $options['distance'] > 0 ) {
				$having = 'HAVING distance < %d ';
				array_push( $params, $options['distance'] );
			}

			$order_by = 'ORDER BY distance ASC';

			$limit = 'LIMIT %d';
			array_push( $params, $options['max_results'] );

			$sql = $select . ' ' . $where . ' ' . $having . ' ' . $order_by . ' ' . $limit;

			$query = $wpdb->prepare(
				$sql,
				$params
			);

			$results = $wpdb->get_results( $query );

			foreach ( $results as $result ) {
				$post_ids[] = $result->ID;
			}

			return $post_ids;
		}

		/**
		 * Get Additional Details and Format the Results.
		 *
		 * @since 1.0.0
		 * @param array $post_ids Arguments to Get Locations.
		 * @return array The Formatted Array
		 */
		private function format_results( $post_ids ) {

			$locations = array();

			$marker_content = $this->options->map_settings->infowindow_content;

			$results = get_posts(
				array(
					'post_type' => 'ascsoftw_sl',
					'include'   => $post_ids,
				)
			);

			foreach ( $results as $result ) {
				$store_information = array(
					'title'     => get_the_title( $result->ID ),
					'email'     => get_post_meta( $result->ID, 'ascsoftw_sl_email', true ),
					'phone'     => get_post_meta( $result->ID, 'ascsoftw_sl_phone', true ),
					'city'      => get_post_meta( $result->ID, 'ascsoftw_sl_city', true ),
					'state'     => get_post_meta( $result->ID, 'ascsoftw_sl_state', true ),
					'zip'       => get_post_meta( $result->ID, 'ascsoftw_sl_zipcode', true ),
					'country'   => get_post_meta( $result->ID, 'ascsoftw_sl_country', true ),
					'address'   => get_post_meta( $result->ID, 'ascsoftw_sl_address', true ),
					'address_2' => get_post_meta( $result->ID, 'ascsoftw_sl_address_2', true ),
					'url'       => get_post_meta( $result->ID, 'ascsoftw_sl_url', true ),
					'lat'       => get_post_meta( $result->ID, 'ascsoftw_sl_latitude', true ),
					'long'      => get_post_meta( $result->ID, 'ascsoftw_sl_longitude', true ),
				);

				$infowindow_html = $this->get_infowindow_html( $store_information, $marker_content );

				$locations[] = array(
					'id'              => $result->ID,
					'infowindow_html' => $infowindow_html,
					'attributes'      => $store_information,
				);
			}

			return $locations;
		}

		/**
		 * Search and replace function for infowindow content
		 *
		 * @since    1.0.0
		 * @param  array  $store_information The array containing Store Information.
		 * @param  string $marker_content String containing markup of HTML.
		 * @return string The actual html to be used as infowindow content
		 */
		private function get_infowindow_html( $store_information, $marker_content ) {

			$infowindow_html = preg_replace_callback(
				'~\{\$(.*?)\}~si',
				function( $match ) use ( $store_information ) {
					return str_replace(
						$match[0],
						isset( $store_information[ $match[1] ] ) ? $store_information[ $match[1] ] : $match[0],
						$match[0]
					);
				},
				$marker_content
			);

			$infowindow_html = str_replace( array( "\r\n", "\r", "\n" ), '<br/>', $infowindow_html );
			$infowindow_html = str_replace( ', ,', '', $infowindow_html );

			return $infowindow_html;

		}

		/**
		 * Enqueue Inline Conditional CSS
		 *
		 * @since 1.0.0
		 * @return void
		 */
		private function enqueue_inline_css() {

			$css = '';

			if ( 1 === $this->options->result_format->open_marker ) {
				$css = '.ascsoftw_sl_result { cursor: pointer; }';
			}
			if ( empty( $css ) ) {
				return;
			}
			wp_register_style( 'ascsoftw-sl-shortcodes', false, array(), $this->version, true );
			wp_add_inline_style( 'ascsoftw-sl-shortcodes', $css );
			wp_enqueue_style( 'ascsoftw-sl-shortcodes' );
		}

		/**
		 * Get First Store
		 *
		 * @since 1.0.0
		 * @return array
		 */
		private function get_first_store() {
			$first_latitude  = '';
			$first_longitude = '';

			$args = array(
				'post_type'      => 'ascsoftw_sl',
				'posts_per_page' => 1,
				'orderby'        => 'ID',
				'status'         => 'publish',
			);

			$query = new WP_Query( $args );
			while ( $query->have_posts() ) {
				$query->the_post();
				$first_latitude  = get_post_meta( get_the_ID(), 'ascsoftw_sl_latitude', true );
				$first_longitude = get_post_meta( get_the_ID(), 'ascsoftw_sl_longitude', true );
			}

			return array( $first_latitude, $first_longitude );

		}

	}
}
