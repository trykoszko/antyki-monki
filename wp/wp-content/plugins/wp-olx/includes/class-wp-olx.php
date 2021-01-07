<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/trykoszko
 * @since      1.0.0
 *
 * @package    Wp_Olx
 * @subpackage Wp_Olx/includes
 */

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
 * @package    Wp_Olx
 * @subpackage Wp_Olx/includes
 * @author     Michal Trykoszko <trykoszkom@gmail.com>
 */
class Wp_Olx {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wp_Olx_Loader    $loader    Maintains and registers all hooks for the plugin.
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
		if ( defined( 'WP_OLX_VERSION' ) ) {
			$this->version = WP_OLX_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wp-olx';

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
	 * - Wp_Olx_Loader. Orchestrates the hooks of the plugin.
	 * - Wp_Olx_i18n. Defines internationalization functionality.
	 * - Wp_Olx_Admin. Defines all hooks for the admin area.
	 * - Wp_Olx_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-olx-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-olx-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-olx-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-olx-public.php';

		/**
		 * The class responsible for OLX connection.
		 */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-olx.php';

        /**
		 * The class responsible for OLX response caching.
		 */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-olx-cache.php';

        /**
		 * The class responsible for OLX AJAX functions.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-olx-ajax.php';

        /**
         * Collection of helper functions
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/helper-functions.php';

		$this->loader = new Wp_Olx_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wp_Olx_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wp_Olx_i18n();

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

        $plugin_admin = new Wp_Olx_Admin( $this->get_plugin_name(), $this->get_version() );

        // scripts and styles
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );

        // modify admin area
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_page_menu_item' );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings' );

        // modify admin columns
        $this->loader->add_filter( 'manage_product_posts_columns', $plugin_admin, 'modify_product_admin_columns' );
        $this->loader->add_action( 'manage_product_posts_custom_column', $plugin_admin, 'product_custom_column', 10, 2 );

        // plugin list links
        $this->loader->add_filter( 'plugin_action_links_wp-olx/wp-olx.php', $plugin_admin, 'add_action_links' );

        // CRON
        if ( Olx::has_tokens() ) {
            $olx = new Olx();
            $this->loader->add_action( 'wp_olx_cron_hook', $olx, 'refresh_adverts_data' );
            $this->loader->add_action( 'wp_olx_cron_hook', $olx, 'get_adverts' );
        }

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

        $plugin_public = new Wp_Olx_Public( $this->get_plugin_name(), $this->get_version() );
		$plugin_ajax = new Olx_Ajax();

		$this->loader->add_action( 'init', $plugin_public, 'custom_post_statuses' );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

        // AJAX hooks
		$this->loader->add_action( 'wp_ajax_ajax_add_advert', $plugin_ajax, 'ajax_add_advert' );
		$this->loader->add_action( 'wp_ajax_nopriv_ajax_add_advert', $plugin_ajax, 'ajax_add_advert' );

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
	 * @return    Wp_Olx_Loader    Orchestrates the hooks of the plugin.
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
