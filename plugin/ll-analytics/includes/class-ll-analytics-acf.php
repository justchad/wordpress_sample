<?php

/**
 * Define ACF pages and settings.
 *
 * @since      1.0.0
 * @package    LL_ANALYTICS
 * @subpackage LL_ANALYTICS/includes
 * @author     Chad McElwain
 */
class LL_ANALYTICS_ACF {

  /**
   * Create options pages and custom fields.
   */
  public function __construct() {
    $this->ll_analytics_add_options_pages();
  }

  /**
   * Defines the save location for the acf-json file
   */
  public function set_acf_save_point( $paths ) {
    $paths = plugin_dir_path( __DIR__ ) . '/acf-json';
    return $paths;
  }

  /**
   * Defines the load location for the acf-json file
   */
  public function set_acf_load_point( $paths ) {
    $paths[] = plugin_dir_path( __DIR__ ) . '/acf-json';
    return $paths;
  }


  /**
   * Create options pages.
   */
  private function ll_analytics_add_options_pages() {
    acf_add_options_page( array(
      'page_title'  => 'Analytics Settings',
      'menu_title'  => 'Analytics',
      'menu_slug'   => 'analytics-settings',
      'position'    => 3,
      'icon_url'    => 'dashicons-chart-area',
      'redirect'   => false
    ) );
  }
}
