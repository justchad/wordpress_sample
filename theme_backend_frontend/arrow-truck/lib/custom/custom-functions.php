<?php
/**
 *
 * Lifted Logic custom functions
 *
 */

    // function login_redirect( $redirect_to, $request, $user ){
    //     var_error_log($redirect_to);
    //     var_error_log($request);
    //     var_error_log($user);
    //     var_error_log('<>---------<>---------<>');
    // }
    // add_filter( 'login_redirect', 'login_redirect', 10, 3 );


//  function admin_default_page() {
//      var_error_log(get_the_permalink());
//    // return '/new-dashboard-url';
//  }
//
//  add_filter('login_redirect', 'admin_default_page');
//
//
// add_action( 'wp', 'sc_capture_before_login_page_url' );
// function sc_capture_before_login_page_url(){
//     if( !is_user_logged_in() ):
//     $_SESSION['referer_url'] = get_the_permalink();
//     var_error_log($_SESSION['referer_url']);
//     endif;
// }
//
// /*@ After login redirection */
// if( !function_exists('sc_after_login_redirection') ):
//     function sc_after_login_redirection() {
//
//     $redirect_url = home_url('/');
//     if ( isset($_SESSION['referer_url']) ):
//         $redirect_url = $_SESSION['referer_url'];
//         unset( $_SESSION['referer_url'] );
//     endif;
//
//     return $redirect_url;
//     exit;
//    }
//    add_filter('login_redirect', 'sc_after_login_redirection');
// endif;

 // function userToArrowApi( $data ){
 //
 //     $address_street = get_user_meta( $data->ID, 'address_street', true );
 //     $address_city = get_user_meta( $data->ID, 'address_city', true );
 //     $address_state = get_user_meta( $data->ID, 'address_state', true );
 //     $address_zip = get_user_meta( $data->ID, 'address_zip', true );
 //
 //     $name = explode(' ', $data->display_name, 2);
 //
 //     $formObject = (object) [
 //         'email' => $data->user_email,
 //         'originalEmail' => $data->user_email,
 //         'firstName' => $name[0],
 //         'lastName' => $name[1],
 //         'billingStreet' => $address_street,
 //         'billingCity' => $address_city,
 //         'billingST' => $address_state,
 //         'billingZip' => $address_zip,
 //         'phone' => null,
 //         'ecommerceSource' => 'C46',
 //         'comments' => '',
 //         'equipmentPreference' => null,
 //         'makePreference' => null,
 //     ];
 //
 //     $formObjectData = json_encode($formObject);
 //
 //     $curl = curl_init();
 //     curl_setopt_array($curl, array(
 //       CURLOPT_URL => 'https://arrowtruckservices.com/arrowapi2/api/account',
 //       CURLOPT_RETURNTRANSFER => true,
 //       CURLOPT_ENCODING => '',
 //       CURLOPT_MAXREDIRS => 10,
 //       CURLOPT_TIMEOUT => 0,
 //       CURLOPT_FOLLOWLOCATION => true,
 //       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
 //       CURLOPT_CUSTOMREQUEST => 'POST',
 //       CURLOPT_POSTFIELDS => $formObjectData,
 //       CURLOPT_HTTPHEADER => array(
 //         'Api-Token: ab3c8c16708986299980187b990b3aa07362008d61d6ce1e8c9982ed34d721cc41dd610d',
 //         'Content-Type: application/json'
 //       ),
 //     ));
 //     $response = curl_exec($curl);
 //     $httpStatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
 //     curl_close($curl);
 //
 //     return $httpStatus;
 // }
 //
 //
 // function sendNewUserToArrow( $user_id ) {
 //     $user = get_userdata( $user_id );
 //     // return true;
 //     return userToArrowApi($user);
 // }
 //

 function ll_get_users(){
     $request = new WP_REST_Request( 'GET', '/wp-json/ll/api/v1/employee/sync' );
     return $request;
 }

 function ll_get_inventory(){
     $request = new WP_REST_Request( 'GET', '/wp-json/ll/api/v1/inventory/sync');
     return $request;
 }

 function ll_get_location(){
     $request = new WP_REST_Request( 'GET', '/wp-json/ll/api/v1/location/sync');
     return $request;
 }
 // add_action('user_register', 'sendNewUserToArrow');

    add_action( 'll_cron_hook_users', 'll_get_users' );
    if ( ! wp_next_scheduled( 'll_cron_hook_users' ) ) {
        wp_schedule_event( time(), 'hourly', 'll_cron_hook_users' );
    }
    add_action( 'll_cron_hook_inventory', 'll_get_inventory' );
    if ( ! wp_next_scheduled( 'll_cron_hook_inventory' ) ) {
        wp_schedule_event( time(), 'twicedaily', 'll_cron_hook_inventory' );
    }
    add_action( 'll_cron_hook_location', 'll_get_location' );
    if ( ! wp_next_scheduled( 'll_cron_hook_location' ) ) {
        wp_schedule_event( time(), 'twicedaily', 'll_cron_hook_location' );
    }

 function ll_show_all_locations_on_archive($query) {
   if ( !is_admin() && $query->is_main_query() && is_post_type_archive( 'll_location' ) ) {
     $query->set( 'posts_per_page', '-1' );
   }
 }
 add_action( 'pre_get_posts', 'll_show_all_locations_on_archive' );

//removes default margin of admin bar
add_action('admin_bar_init', 'remove_admin_login_header');
function remove_admin_login_header() {
  remove_action('wp_head', '_admin_bar_bump_cb');
}

/**
 * Get post meta shortcut
 */
function meta( $key ) {
  global $post;
  return get_post_meta($post->ID, $key, true);
}

/**
 * Formats text like the default WordPress wysiwyg
 */
function format_text( $content ) {
  $content = apply_filters('the_content', $content);
  return $content;
}

/**
 * var_dump variable
 * wrap it in a <pre> tag
 */
function _pre_var() {
  ini_set('xdebug.var_display_max_depth', -1);
  ini_set('xdebug.var_display_max_children', -1);
  ini_set('xdebug.var_display_max_data', -1);
  $args = func_get_args();

  echo '<pre>';
  call_user_func_array( 'var_dump', $args );
  echo '</pre>';
}

/**
* Converts phone numbers to the formatting standard
*
* @param   String   $num   A unformatted phone number
* @return  String   Returns the formatted phone number
*/
function format_phone( $num,$area = false,$sep='-' ) {

  $num = preg_replace( '/[^0-9]/', '', $num );
  $len = strlen( $num );

  if( $len == 7 ) {

    $num = preg_replace( '/([0-9]{3})([0-9]{4})/', '$1'.$sep.'$2', $num );
  }
  elseif( $len == 10 ) {

    if ( $area )
      $num = preg_replace( '/([0-9]{3})([0-9]{3})([0-9]{4})/','($1) $2'.$sep.'$3', $num );
    else
      $num = preg_replace( '/([0-9]{3})([0-9]{3})([0-9]{4})/','$1'.$sep.'$2'.$sep.'$3', $num );
  }
  elseif( $len > 10 ) {

    if ( $area )
      $num = preg_replace( '/([0-9]{3})([0-9]{3})([0-9]{4})([0-9])/','($1) $2'.$sep.'$3'.' ext. $4', $num );
    else
      $num = preg_replace( '/([0-9]{3})([0-9]{3})([0-9]{4})([0-9])/','$1'.$sep.'$2'.$sep.'$3'.' ext. $4', $num );
  }

  return $num;
}

    /**
    * Strips all non-numeric characters from a string
    *
    * @param   String   $num   A unformatted phone number
    * @return  String   Returns number without any special characters or spaces
    */
    function strip_phone( $num ) {

        $num = preg_replace('/[^0-9]/','',$num);

        if ( strlen( $num ) > 10 ) {

            $num = substr_replace( $num, ',', 10, 0 );

        }

        return $num;
    }

/**
 * returns values from custom site options
 * @param  string $context context name of option i.e global, contact
 * @param  String $option_name key of the option i.e _logo_ or _facebook_
 * @return mixed
 */
function ll_get_option( $context = false, $option_name = 'option' ) {
  global $ll_options;
  $ll_options = get_fields( $option_name );

  if ( $context ) {
    return $ll_options[ $context ];
  } else {
    return $ll_options;
  }
}

/**
 * Get all social media options as a key=>value pair of
 * "social_name" => "social_link". To use, make sure all social media
 * options under "Contact Options" are prefixed with _options_contact_social.
 * Example: _options_contact_social_facebook
 * @return array array of social media sites and links
 */
function ll_get_social_list($args=array()) {
  $social_media_sites = array(
    'facebook' => get_field( 'social_facebook', 'option' ),
    'twitter' => get_field( 'social_twitter', 'option' ),
    'instagram' => get_field( 'social_instagram', 'option' ),
    'google_plus' => get_field( 'social_google_plus', 'option' ),
    'youtube' => get_field( 'social_youtube', 'option' ),
    'linkedin' => get_field( 'social_linkedin', 'option' ),
    'pinterest' => get_field( 'social_pinterest', 'option' ),
    'blog' => get_field( 'social_blog', 'option' ),
  );

  $social_media_sites = ll_filter_array( $social_media_sites );

  if( !isset( $args['icon_classes'] ) ) {
    $icon_classes = 'inline-block relative border border-white text-white rounded-full h-7 w-7 social-list__link hover:bg-white hover:text-brand-primary';
  } else {
    $icon_classes = $args['icon_classes'];
  }

  if( !isset( $args['list_item_classes'] ) ) {
    $list_item_classes = 'social-list__item inline-block leading-none mr-2';
  } else {
    $list_item_classes = $args['list_item_classes'];
  }

  if( !isset( $args['list_classes'] ) ) {
    $list_classes = 'social-list flex items-center justify-start';
  } else {
    $list_classes = $args['list_classes'];
  }


  if ( $social_media_sites ) {
    echo '<ul class="'.$list_classes.'">';
      foreach ( $social_media_sites as $social => $link ) {
        echo '<li class="'.$list_item_classes.'"><a class="'.$icon_classes.' '.$social.'" href="'.$link.'" target="_blank"><span class="sr-only">'.implode( " ", explode( '_', $social ) ).'</span><svg class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-sm icon icon-'.$social.'"><use xlink:href="#icon-'.$social.'"></use></svg></a></li>';
      }
    echo '</ul>';
  }
}



/**
 * Set custom logo for the Wordpress login page
 */
function ll_custom_login_logo() {

  $logo = get_field( 'global_logo', 'option' );

  if ( $logo ) : ?>
    <style type="text/css">
      #login h1 a, .login h1 a {
        background-image: url(<?php echo $logo['url']; ?> );
        width: 100%;
        height: auto;
        min-height: 100px;
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center center;
      }
    </style>
  <?php endif; ?>
<?php }
add_action( 'login_enqueue_scripts', 'll_custom_login_logo' );

function ll_custom_login_logo_url() {
  return home_url();
}
add_filter( 'login_headerurl', 'll_custom_login_logo_url' );

function ll_custom_login_logo_url_title() {
  return get_bloginfo( 'description' );
}
add_filter( 'login_headertitle', 'll_custom_login_logo_url_title' );

/**
* ll_stop_reordering_my_categories
* -----------------------------------------------------------------------------
* Keep categories and taxonomies in their hierarchical order rather than showing selected on top.
*
*/
function ll_stop_reordering_my_categories($args) {
  $args['checked_ontop'] = false;
  return $args;
}
add_filter('wp_terms_checklist_args','ll_stop_reordering_my_categories');

/**
* ll_generate_schema_json
* -----------------------------------------------------------------------------
* Keep categories and taxonomies in their hierarchical order rather than showing selected on top.
*
*/
function ll_generate_schema_json() {
  $schema = array(
    '@context'  => 'http://schema.org',
    '@type'     => get_field('schema_type', 'option'),
    'name'      => get_bloginfo('name'),
    'url'       => get_home_url(),
    'telephone' => strip_phone( get_field('contact_phone_number', 'option') ),
    'email' => get_field('contact_email_address', 'option'),
    'address'   => array(
      '@type'           => 'PostalAddress',
      'streetAddress'   => get_field('contact_street_address', 'option'),
      'postalCode'      => get_field('contact_zip', 'option'),
      'addressLocality' => get_field('contact_city', 'option'),
      'addressRegion'   => get_field('contact_state', 'option'),
      'addressCountry'  => get_field('contact_country', 'option')
    )
  );
  /// LOGO
  if (get_field('schema_logo', 'option')) {
    $schema['logo'] = get_field('schema_logo', 'option');
  }
  /// IMAGE
  if (get_field('schema_building_photo', 'option')) {
    $schema['image'] = get_field('schema_building_photo', 'option');
  }
  /// SOCIAL MEDIA
  // Google only looks for these 6 social media sites (and MySpace)
  $social_media_sites = array(
    'facebook' => get_field( 'social_facebook', 'option' ),
    'twitter' => get_field( 'social_twitter', 'option' ),
    'instagram' => get_field( 'social_instagram', 'option' ),
    'google_plus' => get_field( 'social_google_plus', 'option' ),
    'youtube' => get_field( 'social_youtube', 'option' ),
    'linkedin' => get_field( 'social_linkedin', 'option' )
  );

  if ( ll_filter_array( $social_media_sites ) ) {
    $schema['sameAs'] = array();
    foreach ( $social_media_sites as $key => $social_media ) {
      if ( $social_media ) {
        array_push( $schema['sameAs'], $social_media );
      }
    }
  }
  /// OPENING HOURS
  if (have_rows('scehma_openings', 'option')) {
    $schema['openingHoursSpecification'] = array();
    while (have_rows('scehma_openings', 'option')) : the_row();
    $closed = get_sub_field('closed');
    $from   = $closed ? '00:00' : get_sub_field('from');
    $to     = $closed ? '00:00' : get_sub_field('to');
    $openings = array(
      '@type'     => 'OpeningHoursSpecification',
      'dayOfWeek' => get_sub_field('days'),
      'opens'     => $from,
      'closes'    => $to
      );
    array_push($schema['openingHoursSpecification'], $openings);
    endwhile;
    /// VACATIONS / HOLIDAYS
    if (have_rows('schema_special_days', 'option')) {
      while (have_rows('schema_special_days', 'option')) : the_row();
      $closed    = get_sub_field('closed');
      $date_from = get_sub_field('date_from');
      $date_to   = get_sub_field('date_to');
      $time_from = $closed ? '00:00' : get_sub_field('time_from');
      $time_to   = $closed ? '00:00' : get_sub_field('time_to');
      $special_days = array(
        '@type'        => 'OpeningHoursSpecification',
        'validFrom'    => $date_from,
        'validThrough' => $date_to,
        'opens'        => $time_from,
        'closes'       => $time_to
        );
      array_push($schema['openingHoursSpecification'], $special_days);
      endwhile;
    }
  }
  echo '<script type="application/ld+json">' . json_encode($schema) . '</script>';
}
add_action( 'wp_head', 'll_generate_schema_json'  );

function change_site_visibility() {

  if ( function_exists( 'get_field' ) ) {

    $environment = get_field('global_environment', 'option');

    if ($environment == 'development') {
      update_option('blog_public', '0');
    } else {
      update_option('blog_public', '1');
    }
  }
}
add_action('init', 'change_site_visibility');

if ( function_exists( 'gravity_form' ) ) {
  // add_filter( 'gform_confirmation_anchor', '__return_false' );
}

function ll_get_address() {
  $address = array(
    'streetAddress'   => get_field('contact_street_address', 'option'),
    'addressLocality' => get_field('contact_city', 'option'),
    'addressRegion'   => get_field('contact_state', 'option'),
    'postalCode'      => get_field('contact_zip', 'option'),
  );
  return $address;
}

/*
 * Create a shortcode to pull through a formatted address
 * to a WYSIWYG. If no attributes are passed, it will pull
 * through the address from site options. Example usage
 * [address street="10261 W 87th St" city="Overland Park" state="KS" zip="66212" phone="(816) 298-7018"]
 *
 * can also be called directly from within templates using do_shortcode('address');
 *
 * All attributes passed in will add a class to the span for styling specifics, such as adding
 * display: block to .street or .streetAddress
 *
 * Supports both schema style addresses like the ones from ll_get_address, or using friendly keys
 * [street,city,state,zip]
 */
function ll_address_shortcode( $atts ) {
  if ( !$atts ) {
    $address = ll_get_address();
  } else {
    $address = $atts;
  }

  $output = '';
  if ( $address ) {
    $link = 'https://www.google.com/maps/place/'. urlencode( implode(' ', $address) );
    $output .= '<address class="'.$atts['classes'].'"><a class="plain-link" href="'.$link.'" target="_blank">';

    foreach( $address as $address_key => $address_value ) {
      if( $address_key !== 'phone' ) {
        $output .= ' <span class="'.$address_key.'">'.$address_value.'</span>';
      }
    }

    $output .= '</a>';

    if ( $address['phone'] ) {
      $output .= '<a class="phone" href="tel:'.strip_phone( $address['phone'] ).'">'.$address['phone'].'</a>';
    }

    $output .='</address>';
  }

  return $output;
}
add_shortcode( 'address', 'll_address_shortcode' );

/*
 * Generates an array of sanitized titles from the definitions in  symbol-defs.svg.
 */
function get_icon_list() {
  $file_path = get_template_directory_uri() . '/assets/img/symbol-defs.svg';
  $icon_data = simplexml_load_string( file_get_contents( $file_path,false, stream_context_create( array("ssl"=>array("verify_peer"=>false,"verify_peer_name"=>false))) ) );
  if ( $icon_data ) {
    $icon_list = $icon_data->defs;
    if ( $icon_list ) {
      $icon_list = (array) $icon_list;
      $icon_list = $icon_list['symbol'];
    }
  }
  $icons = array();
  if ( $icon_list ) {
    foreach( $icon_list as $icon_key => $icon ) {
      $icons[] = (string) $icon->title;
    }
  }
  sort($icons);
  return $icons;
}

function ll_get_menu_by_location($location) {
  if (empty($location)) {
    return false;
  }

  $locations = get_nav_menu_locations();
  if (!isset($locations[$location])) {
    return false;
  }

  return get_term($locations[$location], 'nav_menu');
}

function ll_acf_google_map_api( $api ){
  $maps_api = get_field( 'global_google_maps_api_key', 'option' );
  $api['key'] = $maps_api;

  return $api;
}
add_filter('acf/fields/google_map/api', 'll_acf_google_map_api');

//save geocode cordinates on save instead of on page load.
//this is utilizing the address from site options contact info, change fields as needed
function ll_add_location_coords( ) {
  $geocode_api_key = get_field( 'global_google_geocoding_api_key', 'option' );
  if ( get_current_screen()->id !== 'toplevel_page_site-options') {
    return; // if we are not on the toplevel site options page, do nothing
  } else if ( ! get_field( 'contact_street_address', 'options' ) || ! $geocode_api_key ) { // check if the address exists
    return;
  }

  // save the address fields as a space seperated string
  $address = get_field( 'contact_street_address', 'options' ) . ' '. get_field( 'contact_city', 'options' ). ' ' . get_field( 'contact_state', 'options' ) . ' ' . get_field( 'contact_zip', 'options' );
  // url_encode the string
  $geocode = json_decode( file_get_contents( 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode( $address ) . '&key=' . $geocode_api_key ), true );

  if ( $geocode === false ) {
    error_log( 'no success' );
  } else {
    // update the fields that your want to save the coordinates too. This a field found on site options
    update_field( 'location_coords_lat', $geocode['results'][0]['geometry']['location']['lat'], 'options' );
    update_field( 'location_coords_lng', $geocode['results'][0]['geometry']['location']['lng'], 'options' );
  }
}
add_action( 'acf/save_post', 'll_add_location_coords'); // acf/save_post for the site options, use save_post if saving to an actual field on a page

function main_content_add_svg_buttons($html) {
  if ( !is_admin() && is_main_query() ) {
    return add_svg_buttons($html);
  }
}

function ll_format_title( $text ) {
  $text = strip_tags($text, '<strong>');
  return $text;
}

function ll_format_bg( $color, $top = '24', $bottom = '24' ) {
  $text_color = '';
  $mp = '';

  if ( $color == 'bg-white' ) {
    // $mp = 'mt-' . $top . ' mb-' . $bottom;
    // $text_color = '';
    $mp = 'pt-' . $top . ' pb-' . $bottom;
  } elseif ( $color == 'bg-brand-light-gray' ) {
    $mp = 'pt-' . $top . ' pb-' . $bottom;
  } elseif ( $color == 'bg-brand-dark-gray' ) {
    $mp = 'pt-' . $top . ' pb-' . $bottom;
    $text_color = 'text-white';
  }

  return $color . ' ' . $mp . ' ' . $text_color;
}

// Google Map URL
function ll_map_address($address) {
  $new_address = sanitize_title( $address );
  $new_address = str_replace('-', '+', $new_address);
  return 'https://www.google.com/maps?q=' . $new_address;
}

add_action( 'pre_get_posts', 'll_sort_locations' );
function ll_sort_locations( $query ) {
  if ( $query->is_main_query() && !is_admin() ) {
    if ( $query->is_post_type_archive('ll_location') ) {
      $query->set('orderby', 'name');
      $query->set('order', 'ASC');
    }
  }
}

function ll_save_location_zip( $post_id ) {

  if( get_field('location_address', $post_id) ) {
    $string = get_field('location_address', $post_id)['zip'];

    update_field('location_zip', $string, $post_id);
  }
}

add_action('acf/save_post', 'll_save_location_zip', 15);

function ll_format_day( $day ) {

  $days_list = [
    'Mo' => 'Monday',
    'Tu' => 'Tuesday',
    'We' => 'Wednesday',
    'Th' => 'Thursday',
    'Fr' => 'Friday',
    'Sa' => 'Saturday',
    'Su' => 'Sunday',
  ];

  return $days_list[$day];

}

function ll_get_hour_range( $day ) {
  if ( $day['dayOfWeek'] ) {
    $days = implode(", ", $day['dayOfWeek']);
  } else {
    if ( $day['validFrom'] ) {
      $start_date = DateTime::createFromFormat('d/m/Y', $day['validFrom'])->format('n/j/y');
    }
    if ( $day['validThrough'] ) {
      $end_date = DateTime::createFromFormat('d/m/Y', $day['validThrough'])->format('n/j/y');
    }
    $days = $day['validThrough'] ? $start_date . ' - ' . $end_date : $start_date;
  }

  if ( $day['opens'] == '00:00' && $day['closes'] == '00:00' ) {
    return 'Closed';
  } else {
    return $day['opens'] . ' - ' . $day['closes'];
  }

}

    // Display hours
    function ll_get_location_hours( $id ) {

        $days_list = [
            '0'   => 'Mo',
            '1'   => 'Tu',
            '2'   => 'We',
            '3'   => 'Th',
            '4'   => 'Fr',
            '5'   => 'Sa',
            '6'   => 'Su',
        ];

        $formatted_hours = [];

        if ( have_rows( 'location_scehma_openings', $id ) ) {

            $hours = array();

            while ( have_rows( 'location_scehma_openings', $id ) ) : the_row();

                $closed = get_sub_field( 'closed' );

                $from   = $closed ? '00:00' : get_sub_field('from');

                $to     = $closed ? '00:00' : get_sub_field('to');

                $openings = array(
                    'dayOfWeek' => get_sub_field('days'),
                    'opens'     => $from,
                    'closes'    => $to
                );

                array_push($hours, $openings);

            endwhile;

            $formatted_key = 0; // Used to not overwrite array keys

            if ( count( $hours ) > 0 ) {

                foreach ( $hours as $key => $day ) {

                    $restart_key = 0; // Restarts for next hour range

                    $reformatted_key = 0; // Restarts to keep track of array keys

                    if ( $day['dayOfWeek'] ) {

                        foreach( $day['dayOfWeek'] as $key => $d ) {

                            if ( $restart_key == 0 ) {

                                $formatted_hours[$formatted_key]['days'] .= ll_format_day( $d );

                            } elseif ( ( $day['dayOfWeek'][$key] == $days_list[ $key + $reformatted_key ] ) && ($day['dayOfWeek'][$key + 1] !== $days_list[ $key + 1 + $reformatted_key ] ) ) {

                                $formatted_hours[$formatted_key]['days'] .= ' - ' . ll_format_day( $day['dayOfWeek'][$key] );

                            } elseif ( $day['dayOfWeek'][$key] !== $days_list[ $key + $reformatted_key ] ) {

                                $formatted_hours[$formatted_key]['hours'] = ll_get_hour_range( $day );

                                $formatted_key++;

                                $reformatted_key++;

                                $restart_key = 0;

                                $formatted_hours[$formatted_key]['days'] .= ll_format_day( $day['dayOfWeek'][$key] );
                            }

                            $restart_key++;

                        }

                        $formatted_hours[$formatted_key]['hours'] = ll_get_hour_range( $day );

                        $formatted_key++;

                        $restart_key = 0;
                    };

                    if ( $day['validFrom'] ) {

                        $start_date = DateTime::createFromFormat('d/m/Y', $day['validFrom'])->format('n/j/y');

                        $end_date = DateTime::createFromFormat('d/m/Y', $day['validThrough'])->format('n/j/y');

                        $formatted_hours[$formatted_key]['days'] = $start_date . ' - ' . $end_date;

                        $formatted_hours[$formatted_key]['hours'] = ll_get_hour_range( $day );

                        $formatted_key++;
                    }

                }
            }
        }

        return $formatted_hours;
    }


add_filter( 'gform_us_states', 'us_states' );
     function us_states( $states ) {

     $territories = array(
         'Alberta',
         'British Columbia',
         'Manitoba',
         'New Brunswick',
         'Newfoundland and Labrador',
         'Nova Scotia',
         'Ontario',
         'Prince Edward Island',
         'Quebec',
         'Saskatchewan',
         'Northwest Territories',
         'Nunavut',
         'Yukon'
     );

     foreach ( $territories as $key => $t ) {
         // array_splice( $states, $key, 0, $t );
         // array_unshift( $states, $key, 0, $t );
     }

     // return $states;
     return array_merge($states, $territories);
 }

    function arrow_manage_users_columns( $columns ) {
        // $columns is a key/value array of column slugs and names
        $columns[ 'location_field' ] = 'Location';

        return $columns;
    }

    add_filter( 'manage_users_columns', 'arrow_manage_users_columns', 10, 1 );

    function arrow_manage_users_custom_column( $output, $column_key, $user_id ) {

        switch ( $column_key ) {
            case 'location_field':
            $value = get_user_meta( $user_id, 'branch', true );
            return $value;
            break;
        default:
            break;
        }

        return $output;
    }

    add_filter( 'manage_users_custom_column', 'arrow_manage_users_custom_column', 10, 3 );

    function add_ll_inventory_custom_columns( $columns ) {
        $columns['branch_location'] = 'Location';
        return $columns;
    }

    add_filter( 'manage_ll_inventory_posts_columns','add_ll_inventory_custom_columns');

    function fill_ll_inventory_posts_custom_column( $column_id, $post_id ) {

        $meta_query = get_post_meta( $post_id, 'BRANCH_ID' );

        echo $meta_query[0] ?? null;
    }

    add_action( 'manage_ll_inventory_posts_custom_column','fill_ll_inventory_posts_custom_column', 10, 2);

    function sortable_ll_inventory_posts_columns( $columns ) {
        $columns['branch_location'] = 'Location';
        return $columns;
    }

    add_filter( 'manage_edit-ll_inventory_sortable_columns', 'sortable_ll_inventory_posts_columns' );
