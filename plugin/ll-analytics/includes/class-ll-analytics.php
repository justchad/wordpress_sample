<?php

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
 * @package    LL_Analytics
 * @subpackage LL_Analytics/includes
 * @author     Your Name <email@example.com>
 */
class LL_Analytics {

  /**
   * The loader that's responsible for maintaining and registering all hooks that power
   * the plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      LL_Analytics_Loader    $loader    Maintains and registers all hooks for the plugin.
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
    if ( defined( 'LL_ANALYTICS_VERSION' ) ) {
      $this->version = LL_ANALYTICS_VERSION;
    } else {
      $this->version = '1.0.0';
    }
    $this->plugin_name = 'll-analytics';
    $this->load_dependencies();
    $this->set_locale();
    $this->register_acf_fields();
    $this->define_theme_hooks();
  }

  /**
   * Load the required dependencies for this plugin.
   *
   * Include the following files that make up the plugin:
   *
   * - LL_Analytics_Loader. Orchestrates the hooks of the plugin.
   * - LL_Analytics_i18n. Defines internationalization functionality.
   * - LL_Analytics_Admin. Defines all hooks for the admin area.
   * - LL_Analytics_Public. Defines all hooks for the public side of the site.
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
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ll-analytics-loader.php';

    /**
     * The class responsible for defining internationalization functionality
     * of the plugin.
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ll-analytics-i18n.php';

    /*
     * The class responsible for registering ACF settings page and field groups
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ll-analytics-acf.php';


    /*
     * The class responsible for registering ACF settings page and field groups
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ll-analytics-utilities.php';

    $this->loader = new LL_Analytics_Loader();

  }

  /**
   * Define the locale for this plugin for internationalization.
   *
   * Uses the LL_Analytics_i18n class in order to set the domain and to register the hook
   * with WordPress.
   *
   * @since    1.0.0
   * @access   private
   */
  private function set_locale() {

    $plugin_i18n = new LL_Analytics_i18n();

    $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

  }

  /**
   * Register ACF fields
   * @since    1.0.0
   * @access   private
   */
  private function register_acf_fields() {
    $plugin_acf = new LL_ANALYTICS_ACF();

    // $this->loader->add_filter( 'acf/settings/save_json', $plugin_acf, 'set_acf_save_point' );
    $this->loader->add_filter( 'acf/settings/load_json', $plugin_acf, 'set_acf_load_point', 10, 1 );
  }

  /**
   * Register hooks to output fields into the theme
   * @since    1.0.0
   * @access   private
   */
  private function define_theme_hooks() {
    if ( is_admin() )
      return;

    $plugin_utilities = new LL_ANALYTICS_UTILITIES();
    $this->loader->add_action( 'wp', $plugin_utilities, 'set_global_page_id', 10 );
    $plugin_utilities->output_analytics( 'head' );
    $plugin_utilities->output_analytics( 'beginning_body' );
    $plugin_utilities->output_analytics( 'end_body' );
    $this->loader->add_action( 'wp_footer', $plugin_utilities, 'output_custom_events', 10 );
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
   * @return    LL_Analytics_Loader    Orchestrates the hooks of the plugin.
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
