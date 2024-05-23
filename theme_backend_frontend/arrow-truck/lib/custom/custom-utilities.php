<?php
/**
 *
 * Lifted Logic custom utilities
 * Utilities, filters, etc.
 *
 */

/**
 * Because browser sync changes the HTTP host, we need to add
 * the new one (generally localhost:3000) to the list of allowed
 * http hosts so that any ajax calls work, e.g. WooCommerce.
 *
 * @param  array  $allowed_origins List of allowed origins
 * @return array                   The edited list of allowed origins
 */
function ll_allow_browser_sync_ajax( $allowed_origins ) {

  $environment = get_field( 'global_environment', 'option' );

  if ( isset( $environment ) && $environment == 'development' ) {

    $http_host = $_SERVER['HTTP_HOST'];

    $allowed_origins[] = 'http://' . $http_host;
    $allowed_origins[] = 'https://' . $http_host;
  }

  return $allowed_origins;
}
add_filter( 'allowed_http_origins', 'll_allow_browser_sync_ajax' );

/**
 * Magnific Popup - automatically add magnific rel attributes to embedded images
 */
function ll_insert_magnific_rel( $content ) {
  $pattern = '/<a(.*?)href="(.*?).(bmp|gif|jpeg|jpg|png)"(.*?)>/i';
  $replacement = '<a$1href="$2.$3" rel=\'magnific\'$4>';
  $content = preg_replace( $pattern, $replacement, $content );
  return $content;
}
add_filter( 'the_content', 'll_insert_magnific_rel' );
add_filter( 'acf_the_content', 'll_insert_magnific_rel' );

/**
 * Fix Gravity Form Tabindex Conflicts
 * http://gravitywiz.com/fix-gravity-form-tabindex-conflicts/
 */
function ll_gform_tabindexer( $tab_index, $form = false ) {
  $starting_index = 1000; // if you need a higher tabindex, update this number
  if( $form )
    add_filter( 'gform_tabindex_' . $form['id'], 'll_gform_tabindexer' );
  return GFCommon::$tab_index >= $starting_index ? GFCommon::$tab_index : $starting_index;
}
add_filter( 'gform_tabindex', 'll_gform_tabindexer', 10, 2 );

/**
 * Add page slug to body class
 * [post-type]-[post-name]
 */
function ll_add_slug_body_class( $classes ) {
  global $post;
  if ( isset( $post ) ) {
    $classes[] = $post->post_type . '-' . $post->post_name;
  }
  return $classes;
}
add_filter( 'body_class', 'll_add_slug_body_class' );

/**
 * Remove classes from post_class
 */
function ll_post_class($classes) {
  $classes = array_diff($classes, array('post'));
  return $classes;
}
add_filter('post_class','ll_post_class');

/**
 * Remove version info from head and feeds
 */
function ll_remove_wp_version() {
  return '';
}
add_filter('the_generator', 'll_remove_wp_version');

/**
 * Remove Emoji styles/scripts that were Introduced In WordPress 4.2
 * Comment out function to enable them
 */
function ll_remove_wp_emoji()  {
  // Remove from comment feed and RSS
  remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
  remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

  // Remove from emails
  remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

  // Remove from head tag
  remove_action( 'wp_head', 'print_emoji_detection_script', 7 );

  // Remove from print related styling
  remove_action( 'wp_print_styles', 'print_emoji_styles' );

  // Remove from admin area
  remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
  remove_action( 'admin_print_styles', 'print_emoji_styles' );
}
add_action( 'init', 'll_remove_wp_emoji' );


function ll_wp() {

  global $ll_page_id, $post, $environment;

  $environment = get_field( 'global_environment', 'option' );

  if ( isset( $post ) ) {
      $ll_page_id = $post->ID;
  }

  /*
   * will potentially need to add more checks for other custom post type archives
   */
  if ( is_home() ) {
    $ll_page_id = get_option( 'page_for_posts' );
  }

}
add_action( 'wp', 'll_wp' );

/**
 * Add wistia to whitelist of available oembed providers
 */
wp_oembed_add_provider( '/https?:\/\/(.+)?(wistia.com|wi.st)\/(medias|embed)\/.*/', 'http://fast.wistia.com/oembed', true);


/**
 * Custom wp_parse_args function
 * - Allows for recursive parsing
 *
 * @param  array $a arguments array
 * i.e:
 * array(
 *   'example' => 'foo',
 *   'example-array' => array(
 *     'nested' => 'bar'
 *   )
 * )
 *
 * @param  array $b default value array i.e array( 'example' => null )
 * i.e:
 * array(
 *   'example' => null,
 *   'example-array' => array(
 *     'nested' => null
 *   )
 * )
 *
 * @return array
 */
function ll_parse_args( &$a, $b ) {
  $a = (array) $a;
  $b = (array) $b;
  $result = $b;

  foreach ( $a as $k => &$v ) {
    if ( $v ) {
      if ( is_array( $v ) && isset( $result[ $k ] ) ) {
        $result[ $k ] = ll_parse_args( $v, $result[ $k ] );
      } else {
        $result[ $k ] = $v;
      }
    }

  }

  return $result;
}

/**
 * Takes in a multideminsional array and removes null values recursively
 *
 * @param  [array]
 * @return [array]
 */
function ll_filter_array( $component_data ) {

  foreach ( $component_data as $key => $value ) {
    if ( is_array( $value ) ) {
      $component_data[$key] = ll_filter_array( $component_data[$key] );
    }

    if ( empty( $component_data[$key] ) ) {
      unset( $component_data[$key] );
    }
  }

  return $component_data;
}


/**
 * Checks if an array is empty after recursively removing null values
 *
 * @param  [array]
 * @return [boolean]
 */
function ll_empty( $array ) {

  $filter = ll_filter_array( $array );

  if( empty( $filter ) ) {
    return  true;
  }

  else {
    return false;
  }
}

function ll_get_the_slug( $id=null ){
  if( empty($id) ):
    global $post;
    if( empty($post) )
      return ''; // No global $post var available.
    $id = $post->ID;
  endif;

  $slug = basename( get_permalink($id) );
  return $slug;
}

// Update Select2 in ACF
function my_acf_init()
{
  acf_update_setting( 'select2_version', 4 );
}
add_action('acf/init', 'my_acf_init');

/**
 * Ajax function that can run any function passed to it and return json data
 *
 * Example Usage:
 */
// $.post(
//   site_info.ajax_url,
//   {
//     action: 'll_run_function',
//     function: 'll_function_name_here',
//     params: {
//       parameter1: value1,
//       parameter2: value2,
//     }
//   },
//   function(data, textStatus, xhr) {
//     data = $.parseJSON( data );
//     if ( data.status === 'success' ) {
//       // Do stuff
//     }
//   }
// );

function ll_run_function_ajax() {

  $response['status'] = 'success';

  if ( $_POST['function'] ) {
    $params = $_POST['params'] ?? [];
    $response['response'] = call_user_func_array( $_POST['function'], $params );
  } else {
    $response['status'] = 'failure';
  }

  echo json_encode( $response );
  wp_die();
}
add_action( 'wp_ajax_ll_run_function', 'll_run_function_ajax' );
add_action( 'wp_ajax_nopriv_ll_run_function', 'll_run_function_ajax' );

/**
 * Emulates var_dump into the log file.
 * Useful for var_dumping AJAX calls
 */
function var_error_log( $object=null ) {
  ob_start();
  $object = json_encode($object);
  echo $object;
  $contents = ob_get_contents();
  ob_end_clean();
  error_log( $contents );
}

/**
 * Escape JSON for use on HTML or attribute text nodes.
 * Taken from Woocommerce, wc_esc_json
 * @since 3.5.5
 * @param string $json JSON to escape.
 * @param bool   $html True if escaping for HTML text node, false for attributes. Determines how quotes are handled.
 * @return string Escaped JSON.
 */
function ll_esc_json( $json, $html = false ) {
  return _wp_specialchars(
    $json,
    $html ? ENT_NOQUOTES : ENT_QUOTES, // Escape quotes in attribute nodes only.
    'UTF-8',                           // json_encode() outputs UTF-8 (really just ASCII), not the blog's charset.
    true                               // Double escape entities: `&amp;` -> `&amp;amp;`.
  );
}

function ll_safe_encode( $value ) {
  return base64_encode( maybe_serialize( $value ) );
}

function ll_safe_decode( $value ) {
  if ( gettype($value) !== 'string' )
    return $value;

  return maybe_unserialize( base64_decode( $value ) );
}

/**
 * Get data if set, otherwise return a default value or null. Prevents notices when data is not set.
 *
 */
function ll_get_var( &$var, $default = null ) {
  return isset( $var ) ? $var : $default;
}

function ll_get_raw_referer() {
  if ( function_exists( 'wp_get_raw_referer' ) ) {
    return wp_get_raw_referer();
  }

  if ( ! empty( $_REQUEST['_wp_http_referer'] ) ) {
    return wp_unslash( $_REQUEST['_wp_http_referer'] );
  } elseif ( ! empty( $_SERVER['HTTP_REFERER'] ) ) {
    return wp_unslash( $_SERVER['HTTP_REFERER'] );
  }

  return false;
}

function ll_setcookie( $name, $value, $expire = 0, $secure = false, $httponly = false ) {
  if ( ! headers_sent() ) {
    setcookie( $name, $value, $expire, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, $secure, false );
  } elseif ( Constants::is_true( 'WP_DEBUG' ) ) {
    headers_sent( $file, $line );
    trigger_error( "{$name} cookie cannot be set - headers already sent by {$file} on line {$line}", E_USER_NOTICE );
  }
}

    function arrow_page_url( $slug ) {

        return get_permalink( get_field( 'page_id_'.$slug, 'option' ) );

    }

function ll_login_url() {
  return get_permalink( get_field( 'page_id_login', 'option' ) );
}

function ll_inventory_url() {
  return get_permalink( get_field( 'page_id_search_inventory', 'option' ) );
}


function ll_normalize_postcode( $postcode ) {
  return preg_replace( '/[\s\-]/', '', trim( LL_StringUtil::uppercase( $postcode ) ) );
}

function ll_format_postcode( $postcode, $country ) {
  $postcode = ll_normalize_postcode( $postcode );

  switch ( $country ) {
    case 'CA':
    case 'GB':
      $postcode = substr_replace( $postcode, ' ', -3, 0 );
      break;
    case 'IE':
      $postcode = substr_replace( $postcode, ' ', 3, 0 );
      break;
    case 'BR':
    case 'PL':
      $postcode = substr_replace( $postcode, '-', -3, 0 );
      break;
    case 'JP':
      $postcode = substr_replace( $postcode, '-', 3, 0 );
      break;
    case 'PT':
      $postcode = substr_replace( $postcode, '-', 4, 0 );
      break;
    case 'US':
      $postcode = rtrim( substr_replace( $postcode, '-', 5, 0 ), '-' );
      break;
    case 'NL':
      $postcode = substr_replace( $postcode, ' ', 4, 0 );
      break;
  }

  return trim( $postcode );
}

function ll_maybe_define_constant( $name, $value ) {
  if ( ! defined( $name ) ) {
    define( $name, $value );
  }
}

function ll_nocache_headers() {
  ll_maybe_define_constant( 'DONOTCACHEPAGE', true );
  ll_maybe_define_constant( 'DONOTCACHEOBJECT', true );
  ll_maybe_define_constant( 'DONOTCACHEDB', true );
  nocache_headers();
}

function ll_create_new_customer_username( $email, $new_user_args = array(), $suffix = '' ) {
  $username_parts = array();

  if ( isset( $new_user_args['first_name'] ) ) {
    $username_parts[] = sanitize_user( $new_user_args['first_name'], true );
  }

  if ( isset( $new_user_args['last_name'] ) ) {
    $username_parts[] = sanitize_user( $new_user_args['last_name'], true );
  }

  // Remove empty parts.
  $username_parts = array_filter( $username_parts );

  // If there are no parts, e.g. name had unicode chars, or was not provided, fallback to email.
  if ( empty( $username_parts ) ) {
    $email_parts    = explode( '@', $email );
    $email_username = $email_parts[0];

    // Exclude common prefixes.
    if ( in_array(
      $email_username,
      array(
        'sales',
        'hello',
        'mail',
        'contact',
        'info',
      ),
      true
    ) ) {
      // Get the domain part.
      $email_username = $email_parts[1];
    }

    $username_parts[] = sanitize_user( $email_username, true );
  }

  $username = LL_StringUtil::lowercase( implode( '.', $username_parts ) );

  if ( $suffix ) {
    $username .= $suffix;
  }

  /**
   * WordPress 4.4 - filters the list of blocked usernames.
   *
   * @since 3.7.0
   * @param array $usernames Array of blocked usernames.
   */
  $illegal_logins = (array) apply_filters( 'illegal_user_logins', array() );

  // Stop illegal logins and generate a new random username.
  if ( in_array( strtolower( $username ), array_map( 'strtolower', $illegal_logins ), true ) ) {
    $new_args = array();

    /**
     * Filter generated customer username.
     *
     * @since 3.7.0
     * @param string $username      Generated username.
     * @param string $email         New customer email address.
     * @param array  $new_user_args Array of new user args, maybe including first and last names.
     * @param string $suffix        Append string to username to make it unique.
     */
    $new_args['first_name'] = 'arrow__user_' . zeroise( wp_rand( 0, 9999 ), 4 );

    return ll_create_new_customer_username( $email, $new_args, $suffix );
  }

  if ( username_exists( $username ) ) {
    // Generate something unique to append to the username in case of a conflict with another user.
    $suffix = '-' . zeroise( wp_rand( 0, 9999 ), 4 );
    return ll_create_new_customer_username( $email, $new_user_args, $suffix );
  }

  return $username;
}

add_filter( 'https_local_ssl_verify', '__return_true' );

function ll_file_get_contents( $file ) {
  $stream_context = stream_context_create(
    array(
      "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false
      )
    )
  );

  return file_get_contents( $file, false, $stream_context );
}

function ll_expire_cookie_handler($cookiename){

    setcookie($cookiename, '', time() - 3600);

    // return $cookiename . ': expired';

}

function ll_decode_cookie_handler($cookiename, $array){

    $ck = ll_safe_decode($_COOKIE[$cookiename]);

    if($array == true){
        $ckArr = json_decode($ck);
    }else{
        $ckArr = $ck;
    }

    return $ckArr;

}

function ll_encode_cookie_handler($data, $cookiename){

    ll_expire_cookie_handler($cookiename);

    setcookie($cookiename, $data, time() + (86400 * 30), "/");

    return $_COOKIE[$cookiename];

}

function jc_safe_decode( $encoded_value )
{

    if ( gettype( $encoded_value ) !== 'string' ) {
        var_error_log('     [!] Error, encoded_value must be a encoded string.');
        return false;
    }

    $decoded_value = maybe_unserialize( base64_decode( $encoded_value ) );

    return json_encode( $decoded_value );
}

function jc_update_post_meta( $post_ID, $meta )
{

    if ( ! $post_ID ) {
        var_error_log("     [!] Error, post_ID not set.");
        return false;
    }

    if ( ! $meta ) {
        var_error_log("     [!] Error, meta not set.");
        return false;
    }

    if ( ! get_post( $post_ID ) || ! is_array( $meta ) ) {
        var_error_log("     [!] Error, no posts found with id {$post_ID} -or- meta is not an array.");
        return false;
    }

    $updatePostMeta = (object) [
        'post_ID'   => $post_ID,
        'meta'      => null,
        'status'    => false
    ];

    $meta_UPDATED = [];

    foreach ( $meta as $meta_KEY => $meta_VALUE ) {
        update_post_meta( $post_ID, $meta_KEY, $meta_VALUE );
        $meta_UPDATED[$meta_KEY] = $meta_VALUE;
    }

    $updatePostMeta->meta = (object) $meta_UPDATED;

    $updatePostMeta->status = true;

    return $updatePostMeta;
}

function SEE( $data, $log = false )
{
    if ( ! $data ) {
        return false;
    }

    if ( $log === false || $log === null || $log === "" || ! isset( $log ) ) {
        echo '<pre>';
            echo gettype( $data );
            echo '<br/>';
            print_r( $data );
        echo '</pre>';
        return true;
    }

    if ( is_array( $data ) || is_object( $data ) ) {
        $data = json_encode( $data );
    }

    var_error_log( gettype( $data ) );

    var_error_log( $data );

    return true;
}

function propercase( $allcaps_string )
{
    if ( ! $allcaps_string ) {
        return "";
    }
    return ucfirst( strtolower( $allcaps_string ));
}

function fullname( $first, $last )
{
    if ( ! $first || ! $last ) {
        return "";
    }

    return ucfirst( strtolower( $first ) ) . " " . ucfirst( strtolower( $last ) );
}

function language_list( $user )
{
    $language_list = [];


    $languages = [
        $user->SLSLANG1 ?? null,
        $user->SLSLANG2 ?? null,
        $user->SLSLANG3 ?? null,
        $user->SLSLANG4 ?? null,
        $user->SLSLANG5 ?? null,
        $user->SLSLANG6 ?? null,
        $user->SLSLANG7 ?? null,
        $user->SLSLANG8 ?? null,
        $user->SLSLANG9 ?? null,
        $user->SLSLANG10 ?? null
    ];

    foreach ( $languages as $language ) {
        if ( $language ) {

            $language_FORMAT = ucfirst( strtolower( $language ) );

            $language_FLAG = get_stylesheet_directory() . "/assets/img/flags/{$language}.svg";

            $language_list[] = ( object ) [
                "_api"  => $language,
                "name"  => $language_FORMAT,
                "flag"  => $language_FLAG
            ];

        }
    }

    return $language_list;
}

function job_title( $title )
{
    if ( ! $title ) {
        return false;
    }

    switch ( $title ) {
        case "BUYERS":
            $job_title = "Buyer";
            break;

        case "MGR":
        case "SATMGR":
            $job_title = "Branch Manager";
            break;

        case "FIMGR/ADMIN":
            $job_title = "Finance and Insurance Manager";
            break;

        case "ASTMGR":
            $job_title = "Assistant Branch Manager";
            break;

        case "SALESMGR":
        case "SALES MANAGER":
            $job_title = "Sales Manager";
            break;

        case "SALES/PURCH MGR":
            $job_title = "Sales and Purchasing Manager";
            break;

        case "LEAD SALE ASSC":
            $job_title = "Lead Sales Associate";
            break;

        case "SALES":
            $job_title = "Retail Sales Consultant";
            break;

        case "ADMIN":
        case "ADMIN ASSIST":
            $job_title = "Administrative Assistant";
            break;

        case "SHOP":
        case "SHOP2":
            $job_title = "Inventory Coordinator";
            break;

        default:
            $job_title = $title;
    }

    return $job_title;
}

function process_reps( $arrow_reps )
{
    if( ! $arrow_reps ) {
        return false;
    }

    $process_reps = [];

    foreach( $arrow_reps as $arrow_rep ) {

        if ( ! isset( $arrow_rep ) ) {
            continue;
        }

        $languages = language_list( $arrow_rep->arrow_data );

        $title = job_title( $arrow_rep->arrow_data->USRJOBTL );

        $rep = ( object ) [
            'id'            => $arrow_rep->arrow_data->SLSREPNO,
            'name'          => ( object ) [
                'first'         => propercase( $arrow_rep->arrow_data->SLSFNAME ),
                'last'          => propercase( $arrow_rep->arrow_data->SLSLNAME ),
                'full'          => fullname( $arrow_rep->arrow_data->SLSFNAME, $arrow_rep->arrow_data->SLSLNAME )
            ],
            'bio'           => $arrow_rep->arrow_data->SLSBIO,
            'email'         => strtolower( $arrow_rep->arrow_data->SLSEMAIL ),
            'phone'         => $arrow_rep->arrow_data->SLSPHONE,
            'title'         => $title,
            'profile'       => ( $arrow_rep->image ) ? $arrow_rep->image : 'https://www.arrowtruck.com/images/NoImageHead2.jpg',
            'multilingual'  => ( count( $languages ) === 0 ) ? false : true,
            'languages'     => null,
            'page'          => $arrow_rep->the_permalink,
            'wp'            => ( isset( $arrow_rep->wp_data->data->ID ) ) ? $arrow_rep->wp_data->data->ID : null,
            'raw'           => $arrow_rep->arrow_data
        ];


        if ( $rep->multilingual === true ) {
            $rep->languages = $languages;
        }

        $process_reps[] = $rep;
    }



    return $process_reps;
}

function assign_state( $city, $state )
{
    if ( ! $city ) {
        return false;
    }

    $city = strtoupper( $city );

    switch ( $city ) {
        case "TROY":
            $assign_state = "IL";
            break;

        default:
            $assign_state = $state;
    }

    return $assign_state;
}

function format_address( $address_object )
{
    if ( ! $address_object ) {
        return false;
    }

    $show_address_two = true;
    $show_country = true;

    $address_2 = "";
    if( $address_object->address_2 !== null ) {
        $address_2 = ", {$address_object->address_2}";
    }

    $country = "";
    if ( $show_country ) {
        $country = ", {$address_object->country}";
    }

    return "{$address_object->address_1}{$address_2}, {$address_object->city}, {$address_object->state} {$address_object->zip}{$country}";
}

function process_team_reps( $team_reps )
{
    if( ! $team_reps ){
        return false;
    }

    $process_team_reps = [];

    foreach( $team_reps as $team_rep ) {

        $user = get_userdata( $team_rep['ID'] );

        $meta = get_user_meta( $team_rep['ID'] );

        $arrow_data = ll_safe_decode( $meta[ 'arrow_data' ][0] );

        $languages = language_list( $arrow_data );

        $rep = ( object ) [
            'id'            => $arrow_data->SLSREPNO,
            'name'          => ( object ) [
                'first'         => propercase( $arrow_data->SLSFNAME ),
                'last'          => propercase( $arrow_data->SLSLNAME ),
                'full'          => fullname( $arrow_data->SLSFNAME, $arrow_data->SLSLNAME )
            ],
            'bio'           => $arrow_data->SLSBIO,
            'email'         => strtolower( $arrow_data->SLSEMAIL ),
            'phone'         => $arrow_data->SLSPHONE,
            'title'         => job_title( $arrow_data->USRJOBTL ),
            'profile'       => ( $meta[ 'profile_raw_data' ][0] ) ? $meta[ 'profile_raw_data' ][0] : 'https://www.arrowtruck.com/images/NoImageHead2.jpg',
            'multilingual'  => ( count( $languages ) === 0 ) ? false : true,
            'languages'     => null,
            'page'          => get_author_posts_url( $team_rep['ID'] ),
            'wp'            => $team_rep['ID']
        ];

        if ( $rep->multilingual === true ) {
            $rep->languages = $languages;
        }

        $process_team_reps[] = $rep;
    }

    return $process_team_reps;
}

function current_user_roles()
{
    if( is_user_logged_in() ) {

        $user = wp_get_current_user();
        $roles = ( array ) $user->roles;

        return $roles;

    } else {
        return false;
    }
}

function is_arrow_staff( )
{
    if( is_user_logged_in() ) {

        $mail_domain = strtolower( 'ARROWTRUCK.COM' );
        $user = wp_get_current_user();
        $email = explode( "@", $user->user_email );
        $user_email_domain = strtolower( $email[1] );

        if ( $user_email_domain === $mail_domain ) {
            return true;
        }

        return false;

    } else {
        return false;
    }
}

function string_to_variable( $string )
{
    if ( ! is_string( $string ) ) {
        return false;
    }

    return str_replace(" ","-", strtolower( $string ) );
}

function set_city_state( $branch_id )
{
    switch ( $branch_id ) {
        case "SL":
            $city_state = "St. Louis, MO";
            break;

        case "CH":
            $city_state = "Chicago, IL";
            break;

        case "AT":
            $city_state = "Atlanta, GA";
            break;

        case "CN":
            $city_state = "Cincinnati, OH";
            break;

        case "DA":
            $city_state = "Dallas, TX";
            break;

        case "FT":
            $city_state = "Fontana, CA";
            break;

        case "FR":
            $city_state = "Fresno, CA";
            break;

        case "HS":
            $city_state = "Houston, TX";
            break;

        case "JX":
            $city_state = "Jacksonville, FL";
            break;

        case "KC":
            $city_state = "Kansas City, MO";
            break;

        case "NJ":
            $city_state = "Newark, NJ";
            break;

        case "PH":
            $city_state = "Philadelphia, PA";
            break;

        case "PX":
            $city_state = "Phoenix, AZ";
            break;

        case "SA":
            $city_state = "San Antonio, TX";
            break;

        case "SP":
            $city_state = "Springfield, MO";
            break;

        case "TA":
            $city_state = "Tampa, FL";
            break;

        case "OK":
            $city_state = "Oklahoma City, OK";
            break;

        case "ST":
            $city_state = "Stockton, CA";
            break;

        default:
            $city_state = $branch_id;
    }

    return $city_state;
}
