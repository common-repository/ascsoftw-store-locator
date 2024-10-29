<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
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
if ( ! class_exists( 'Ascsoftw_Sl' ) ) {
	/**
	 * The core plugin class.
	 *
	 * This is used to define internationalization, admin-specific hooks, and
	 * public-facing site hooks.
	 *
	 * Also maintains the unique identifier of this plugin as well as the current
	 * version of the plugin.
	 *
	 * @since      1.0.0
	 * @package    Ascsoftw_Sl
	 * @subpackage Ascsoftw_Sl/includes
	 * @author     Sunil Guleria <guleria.sunil2004@gmail.com>
	 */
	class Ascsoftw_Sl {

		/**
		 * The loader that's responsible for maintaining and registering all hooks that power
		 * the plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      Ascsoftw_Sl_Loader    $loader    Maintains and registers all hooks for the plugin.
		 */
		protected $loader;

		/**
		 * The unique identifier of this plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
		 */
		protected $plugin_name;

		/**
		 * The current version of the plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string    $version    The current version of the plugin.
		 */
		protected $version;

		/**
		 * Define the core functionality of the plugin.
		 *
		 * Set the plugin name and the plugin version that can be used throughout the plugin.
		 * Load the dependencies, define the locale, and set the hooks for the admin area and
		 * the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function __construct() {
			if ( defined( 'ASCSOFTW_SL_VERSION' ) ) {
				$this->version = ASCSOFTW_SL_VERSION;
			} else {
				$this->version = '1.0.0';
			}
			$this->plugin_name = 'ascsoftw-sl';

			$this->load_dependencies();
			$this->set_locale();
			$this->define_admin_hooks();
			$this->define_public_hooks();

		}

		/**
		 * Load the required dependencies for this plugin.
		 *
		 * Include the following files that make up the plugin:
		 *
		 * - Ascsoftw_Sl_Loader. Orchestrates the hooks of the plugin.
		 * - Ascsoftw_Sl_i18n. Defines internationalization functionality.
		 * - Ascsoftw_Sl_Admin. Defines all hooks for the admin area.
		 * - Ascsoftw_Sl_Public. Defines all hooks for the public side of the site.
		 *
		 * Create an instance of the loader which will be used to register the hooks
		 * with WordPress.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function load_dependencies() {

			/**
			 * The class responsible for orchestrating the actions and filters of the
			 * core plugin.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ascsoftw-sl-loader.php';

			/**
			 * The class responsible for defining internationalization functionality
			 * of the plugin.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ascsoftw-sl-i18n.php';

			/**
			 * The class responsible for defining all actions that occur in the admin area.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ascsoftw-sl-admin.php';

			/**
			 * The class responsible for defining all actions that occur in the public-facing
			 * side of the site.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-ascsoftw-sl-public.php';

			/**
			 * Require cmb2 init file.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/cmb2/init.php';

			/**
			 * Require CMB2 Map init file.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/cmb_field_map-master/cmb-field-map.php';

			/**
			 * Custom Post Types.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ascsoftw-sl-post-types.php';

			/**
			 * Ascsoftw_Sl Option Class.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ascsoftw-sl-options.php';

			$this->loader = new Ascsoftw_Sl_Loader();

		}

		/**
		 * Define the locale for this plugin for internationalization.
		 *
		 * Uses the Ascsoftw_Sl_i18n class in order to set the domain and to register the hook
		 * with WordPress.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function set_locale() {

			$plugin_i18n = new Ascsoftw_Sl_I18n();

			$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

		}

		/**
		 * Register all of the hooks related to the admin area functionality
		 * of the plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function define_admin_hooks() {

			$plugin_admin = new Ascsoftw_Sl_Admin( $this->get_plugin_name(), $this->get_version() );

			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

			$plugin_post_types = new Ascsoftw_Sl_Post_Types();
			$this->loader->add_action( 'init', $plugin_post_types, 'create_custom_post_types' );

			$this->loader->add_action( 'cmb2_admin_init', $plugin_post_types, 'register_cmb2_metabox_book' );

			$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_admin_menu_using_helper' );

		}

		/**
		 * Register all of the hooks related to the public-facing functionality
		 * of the plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function define_public_hooks() {

			$plugin_public = new Ascsoftw_Sl_Public( $this->get_plugin_name(), $this->get_version() );

			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );

			/**
			 * Ajax call to get locations by category and location parameters
			 */
			$this->loader->add_action( 'wp_ajax_get_store_search', $plugin_public, 'get_store_search' );
			$this->loader->add_action( 'wp_ajax_nopriv_get_store_search', $plugin_public, 'get_store_search' );

			$this->loader->add_shortcode( 'ascsoftw_sl', $plugin_public, 'ascsoftw_sl_shortcode' );
		}

		/**
		 * Run the loader to execute all of the hooks with WordPress.
		 *
		 * @since    1.0.0
		 */
		public function run() {
			$this->loader->run();
		}

		/**
		 * The name of the plugin used to uniquely identify it within the context of
		 * WordPress and to define internationalization functionality.
		 *
		 * @since     1.0.0
		 * @return    string    The name of the plugin.
		 */
		public function get_plugin_name() {
			return $this->plugin_name;
		}

		/**
		 * The reference to the class that orchestrates the hooks with the plugin.
		 *
		 * @since     1.0.0
		 * @return    Ascsoftw_Sl_Loader    Orchestrates the hooks of the plugin.
		 */
		public function get_loader() {
			return $this->loader;
		}

		/**
		 * Retrieve the version number of the plugin.
		 *
		 * @since     1.0.0
		 * @return    string    The version number of the plugin.
		 */
		public function get_version() {
			return $this->version;
		}

	}
}
