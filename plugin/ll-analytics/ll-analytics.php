<?php

/**
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/justchad
 * @since             1.0
 * @package           LL_ANALYTICS
 *
 * @wordpress-plugin
 * Plugin Name:       Analytics Options
 * Plugin URI:        https://github.com/justchad
 * Description:       Adds ACF fields for inserting analytics scripts at various spots on a page as well as the ability to create custom analytics events.
 * Version:           1.0.0
 * Author:            Chad McElwain
 * Author URI:        https://github.com/justchad
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ll-analytics
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'LL_ANALYTICS_VERSION', '1.0.0' );
define( 'LL_ANALYTICS_SLUG', 'll-analytics' );
define( 'LL_ANALYTICS_PATH', plugin_dir_path( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ll-analytics-activator.php
 */
function activate_ll_analytics() {
  require_once plugin_dir_path( __FILE__ ) . 'includes/class-ll-analytics-activator.php';
  LL_ANALYTICS_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ll-analytics-deactivator.php
 */
function deactivate_ll_analytics() {
  require_once plugin_dir_path( __FILE__ ) . 'includes/class-ll-analytics-deactivator.php';
  LL_ANALYTICS_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ll_analytics' );
register_deactivation_hook( __FILE__, 'deactivate_ll_analytics' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ll-analytics.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ll_analytics() {

  $plugin = new LL_ANALYTICS();
  $plugin->run();

}

if ( ! class_exists( 'acf_pro' ) ) {
  function ll_analytics_acf_not_actived_notice() {
    ?>
    <div class="error notice">
      <p><?php _e( 'Advanced Custom Fields PRO must be installed and activated in order to use Analytics.', 'll-analytics' ); ?></p>
    </div>
    <?php
  }
  add_action( 'admin_notices', 'll_analytics_acf_not_actived_notice' );
} else {
  run_ll_analytics();
}
