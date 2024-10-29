<?php
/**
 * The file that defines the Custom Post Types
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

if ( ! class_exists( 'Ascsoftw_Sl_Post_Types' ) ) {
	/**
	 * The core plugin class.
	 *
	 * This is used to define all the Custom Post Types & Taxonomy.
	 *
	 * @since      1.0.0
	 * @package    Ascsoftw_Sl
	 * @subpackage Ascsoftw_Sl/includes
	 * @author     Sunil Guleria <guleria.sunil2004@gmail.com>
	 */
	class Ascsoftw_Sl_Post_Types {

		/**
		 * Constructor
		 *
		 * @since    1.0.0
		 */
		public function __construct() {

		}

		/**
		 * Create Custom Post Types.
		 *
		 * @since    1.0.0
		 * @access   public
		 */
		public function create_custom_post_types() {
			$this->register_ascsoftw_sl_post_type();
			$this->register_taxonomy_cat();
		}

		/**
		 *  Register the custom post type.
		 *
		 * @since 1.0.0
		 */
		public function register_ascsoftw_sl_post_type() {

			$labels = array(
				'name'               => _x( 'Stores', 'post type general name', 'ascsoftw-store-locator' ),
				'singular_name'      => _x( 'Store', 'post type singular name', 'ascsoftw-store-locator' ),
				'menu_name'          => _x( 'Store Locator', 'admin menu', 'ascsoftw-store-locator' ),
				'name_admin_bar'     => _x( 'Store', 'add new on admin bar', 'ascsoftw-store-locator' ),
				'add_new'            => _x( 'Add New', 'ascsoftw_sl', 'ascsoftw-store-locator' ),
				'add_new_item'       => __( 'Add New Store', 'ascsoftw-store-locator' ),
				'new_item'           => __( 'New Store', 'ascsoftw-store-locator' ),
				'edit_item'          => __( 'Edit Store', 'ascsoftw-store-locator' ),
				'view_item'          => __( 'View Store', 'ascsoftw-store-locator' ),
				'all_items'          => __( 'All Stores', 'ascsoftw-store-locator' ),
				'search_items'       => __( 'Search Stores', 'ascsoftw-store-locator' ),
				'parent_item_colon'  => __( 'Parent Stores:', 'ascsoftw-store-locator' ),
				'not_found'          => __( 'No store found.', 'ascsoftw-store-locator' ),
				'not_found_in_trash' => __( 'No stores found in Trash.', 'ascsoftw-store-locator' ),
			);

			$args = array(
				'labels'               => $labels,
				'description'          => __( 'Stores.', 'ascsoftw-store-locator' ),
				'public'               => true,
				'publicly_queryable'   => false,
				'show_ui'              => true,
				'show_in_menu'         => true,
				'query_var'            => false,
				'menu_icon'            => 'dashicons-store',
				'rewrite'              => array( 'slug' => 'ascsoftw_sl' ),
				'capability_type'      => 'post',
				'taxonomies'           => array( 'ascsoftw_sl' ),
				'has_archive'          => true,
				'hierarchical'         => false,
				'menu_position'        => 21,
				'register_meta_box_cb' => null,
				'supports'             => array( 'title' ),
			);
			register_post_type( 'ascsoftw_sl', $args );

		}

		/**
		 *  Register Taxonomy Genre
		 *
		 * @since 1.0.0
		 */
		public function register_taxonomy_cat() {
			register_taxonomy(
				'ascsoftw_sl',
				'ascsoftw_sl',
				array(
					'label'        => __( 'Category' ),
					'rewrite'      => array( 'slug' => 'ascsoftw_sl' ),
					'hierarchical' => false,
				)
			);
		}

		/**
		 * Display Metabox usinb cmb2.
		 */
		public function register_cmb2_metabox_book() {
			$metabox = new_cmb2_box(
				array(
					'id'           => 'ascsoftw_sl_store_details',
					'title'        => __( 'Store Details', 'ascsoftw-store-locator' ),
					'object_types' => array( 'ascsoftw_sl' ),
					'context'      => 'normal',
				)
			);

			$metabox->add_field(
				array(
					'id'           => 'ascsoftw_sl',
					'name'         => __( 'Location', 'ascsoftw-store-locator' ) . '*',
					'desc'         => __( 'Drag the marker to set the exact location', 'ascsoftw-store-locator' ),
					'type'         => 'pw_map',
					'split_values' => true,
				)
			);

			$metabox->add_field(
				array(
					'id'         => 'ascsoftw_sl_address',
					'name'       => __( 'Address', 'ascsoftw-store-locator' ) . '*',
					'type'       => 'text',
					'attributes' => array(
						'class'    => 'regular-text',
						'required' => 'required',
					),
				)
			);
			$metabox->add_field(
				array(
					'id'   => 'ascsoftw_sl_address_2',
					'name' => __( 'Address 2', 'ascsoftw-store-locator' ),
					'type' => 'text',
				)
			);
			$metabox->add_field(
				array(
					'id'         => 'ascsoftw_sl_city',
					'name'       => __( 'City', 'ascsoftw-store-locator' ) . '*',
					'type'       => 'text',
					'attributes' => array(
						'class'    => 'regular-text',
						'required' => 'required',
					),
				)
			);
			$metabox->add_field(
				array(
					'id'   => 'ascsoftw_sl_state',
					'name' => __( 'State', 'ascsoftw-store-locator' ),
					'type' => 'text',
				)
			);
			$metabox->add_field(
				array(
					'id'   => 'ascsoftw_sl_zipcode',
					'name' => __( 'ZipCode', 'ascsoftw-store-locator' ),
					'type' => 'text',
				)
			);
			$metabox->add_field(
				array(
					'id'   => 'ascsoftw_sl_country',
					'name' => __( 'Country', 'ascsoftw-store-locator' ),
					'type' => 'text',
				)
			);
			$metabox->add_field(
				array(
					'id'   => 'ascsoftw_sl_phone',
					'name' => __( 'Phone', 'ascsoftw-store-locator' ),
					'type' => 'text',
				)
			);
			$metabox->add_field(
				array(
					'id'         => 'ascsoftw_sl_email',
					'name'       => __( 'Email', 'ascsoftw-store-locator' ),
					'type'       => 'text_email',
					'attributes' => array(
						'class' => 'regular-text',
					),
				)
			);
			$metabox->add_field(
				array(
					'id'   => 'ascsoftw_sl_url',
					'name' => __( 'Url', 'ascsoftw-store-locator' ),
					'type' => 'text_url',
				)
			);
		}
	}
}
