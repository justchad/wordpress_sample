<?php

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    LL_Analytics
 * @subpackage LL_Analytics/includes
 * @author     Your Name <email@example.com>
 */
class LL_Analytics_i18n {


  /**
   * Load the plugin text domain for translation.
   *
   * @since    1.0.0
   */
  public function load_plugin_textdomain() {

    load_plugin_textdomain(
      'll-analytics',
      false,
      dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
    );

  }



}
