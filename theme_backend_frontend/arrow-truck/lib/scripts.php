<?php
/**
 * Scripts and stylesheets
 *
 * Enqueue stylesheets in the following order:
 * 1. /theme/assets/css/main.css
 *
 * Enqueue scripts in the following order:
 * 1. jquery-2.1.4.min.js via Google CDN
 * 2. /theme/assets/js/vendor/modernizr.min.js
 * 3. /theme/assets/js/scripts.js (in footer)
 *
 * Google Analytics is loaded after enqueued scripts if:
 * - An ID has been defined in config.php
 * - You're not logged in as an administrator
 */
function roots_scripts() {

  $street_address = get_field( 'contact_street_address', 'option' );
  $city = get_field( 'contact_city', 'option' );
  $state = get_field( 'contact_state', 'option' );
  $zip = get_field( 'contact_zip', 'option' );
  $coords = get_field( 'location_coords', 'option' );

  /**
   * The build task in Grunt renames production assets with a hash
   * Read the asset names from assets-manifest.json
   */
  if ( WP_ENV === 'development' ) {
    $assets = array(
      'css'       => '/assets/css/main.min.css',
      'vendor'    => '/assets/js/ll_vendor.min.js',
      'js'        => '/assets/js/scripts.min.js',
      'jquery'    => '//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.js'
    );
  } else {
    $get_assets = file_get_contents(get_template_directory() . '/assets/mix-manifest.json');
    $assets     = json_decode($get_assets, true);
    $assets     = array(
      'css'       => '/assets'.$assets['/css/main.min.css'],
      'vendor'    => '/assets'.$assets['/js/ll_vendor.min.js'],
      'js'        => '/assets'.$assets['/js/scripts.min.js'],
      'jquery'    => '//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'
    );
  }

  wp_enqueue_style( 'nouislider', get_template_directory_uri() . '/assets/css/nouislider.min.css', false, null );
  wp_enqueue_style('roots_css', get_template_directory_uri() . $assets['css'], false, null);
  wp_enqueue_script( 'polyfill', 'https://cdn.polyfill.io/v2/polyfill.min.js?features=Event,Symbol,Array.prototype.@@iterator,Array.from,Element.prototype.closest,Promise.prototype.finally,Promise' );

  /**
   * jQuery is loaded using the same method from HTML5 Boilerplate:
   * Grab Google CDN's latest jQuery with a protocol relative URL; fallback to local if offline
   * It's kept in the header instead of footer to avoid conflicts with plugins.
   */
  if (!is_admin() && current_theme_supports('jquery-cdn')) {
    wp_deregister_script('jquery');
    wp_register_script('jquery', $assets['jquery'], array(), null, false);
    add_filter('script_loader_src', 'roots_jquery_local_fallback', 10, 2);
  }

  if (is_single() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }

  if ( get_field( 'components' ) || ( isset( $_GET['component'] ) && $_GET['component'] == 'map' ) || is_post_type_archive( 'll_location' ) ) {
    $components = get_field( 'components' ) ?? array();
    $components = array_filter( $components, function( $component ) {
      return $component['acf_fc_layout'] == 'map';
    } );

    if ( is_post_type_archive( 'll_location' ) || !empty( $components ) || ( isset( $_GET['component'] ) && $_GET['component'] == 'map' ) ) {
      wp_enqueue_script( 'mapbox', 'https://api.mapbox.com/mapbox-gl-js/v1.12.0/mapbox-gl.js' );
      wp_enqueue_style( 'mapbox', 'https://api.mapbox.com/mapbox-gl-js/v1.12.0/mapbox-gl.css' );
      wp_localize_script( 'mapbox', 'll_mapbox', array(
        'token' => get_field( 'global_mapbox_token', 'option' ),
        'style' => get_field( 'global_mapbox_style', 'option' ),
        'pin'   => get_stylesheet_directory_uri() . '/assets/img/map-pin.png',
      ) );
    }
  }

  wp_enqueue_script('jquery');
  wp_enqueue_script('nouislider', get_template_directory_uri() . '/assets/js/nouislider.min.js', null, true);
  wp_enqueue_script('vendor_js', get_template_directory_uri() . $assets['vendor'], array(), null, true);
  wp_enqueue_script('roots_js', get_template_directory_uri() . $assets['js'], array(), null, true);

  wp_localize_script( 'roots_js', 'site_info', array(
    'url'     => home_url(),
    'name'    => get_bloginfo('name'),
    'address' => array(
      'street' => $street_address,
      'city'   => $city,
      'state'  => $state,
      'zip'    => $zip,
      'coords' => $coords
    ),
    'asset_url'     => get_stylesheet_directory_uri() . '/assets',
    'ajax_url'      => admin_url( 'admin-ajax.php' ),
    'ajax_nonce'    => wp_create_nonce('ll_nonce'),
    'referer'       => ll_get_raw_referer(),
    'wpApiSettings' => array(
      'root'  => esc_url_raw( rest_url() ),
      'll'    => esc_url_raw( rest_url() ) .'ll/api/v1/',
      'nonce' => wp_create_nonce( 'wp_rest' ),
    )
  ) );
}
add_action('wp_enqueue_scripts', 'roots_scripts', 100);

// http://wordpress.stackexchange.com/a/12450
function roots_jquery_local_fallback($src, $handle = null) {
  static $add_jquery_fallback = false;

  if ($add_jquery_fallback) {
    echo '<script>window.jQuery || document.write(\'<script src="' . get_template_directory_uri() . '/assets/vendor/jquery/dist/jquery.min.js?2.1.4"><\/script>\')</script>' . "\n";
    $add_jquery_fallback = false;
  }

  if ($handle === 'jquery') {
    $add_jquery_fallback = true;
  }

  return $src;
}
add_action('wp_head', 'roots_jquery_local_fallback');
