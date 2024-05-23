<?php

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    LL_Analytics
 * @subpackage LL_Analytics/includes
 * @author     Your Name <email@example.com>
 */
class LL_Analytics_Deactivator {

  /**
   * Short Description. (use period)
   *
   * Long Description.
   *
   * @since    1.0.0
   */
  public static function deactivate() {
    if ( function_exists('delete_field') ) {
      delete_field( 'll_head_scripts', 'option' );
      delete_field( 'll_beginning_body_scripts', 'option' );
      delete_field( 'll_end_body_scripts', 'option' );
      delete_field( 'll_analytics_events', 'option' );
      delete_field( 'll_head', 'option', 'option' );
      delete_field( 'll_beginning_body', 'option' );
      delete_field( 'll_end_body', 'option' );
    }
  }

}
