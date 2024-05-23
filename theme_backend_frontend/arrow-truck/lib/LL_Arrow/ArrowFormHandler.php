<?php
/**
 * Handle custom frontend forms.
 *
 */

class ArrowFormHandler {
  public static function init() {
    add_action( 'wp_loaded', array( __CLASS__, 'process_edit_personal_information' ) );
    add_action( 'wp_loaded', array( __CLASS__, 'process_edit_billing_address' ) );
    add_action( 'wp_loaded', array( __CLASS__, 'process_edit_shipping_address' ) );
    add_action( 'wp_loaded', array( __CLASS__, 'process_edit_password' ) );
    add_action( 'wp_loaded', array( __CLASS__, 'process_login' ), 20 );
    add_action( 'wp_loaded', array( __CLASS__, 'process_registration' ), 20 );
    add_action('login_form_lostpassword', array( __CLASS__, 'process_lost_password' ), 10 );
    add_action('login_form_rp', array( __CLASS__, 'redirect_to_password_reset' ), 10 );
    add_action('login_form_resetpass', array( __CLASS__, 'redirect_to_password_reset' ), 10 );
    add_action('login_form_rp', array( __CLASS__, 'process_reset_password' ), 10 );
    add_action('login_form_resetpass', array( __CLASS__, 'process_reset_password' ), 10 );
    add_action('load-ll_inventory_page_arrow-filters', array( __CLASS__, 'process_filter_display_options' ), 0 );
  }


  /**
   * Process the login form.
   *
   * @throws LL_Exception On login error.
   */
  public static function process_login() {
    if ( is_admin() )
      return;

    $nonce_value = ll_get_var( $_REQUEST['arrow-login-nonce'] );

    if ( isset( $_POST['login'], $_POST['username'], $_POST['password'] ) && wp_verify_nonce( $nonce_value, 'arrow-login' ) ) {
      try {
        $creds = array(
          'user_login'    => trim( wp_unslash( $_POST['username'] ) ),
          'user_password' => $_POST['password'],
          'remember'      => isset( $_POST['rememberme'] ),
        );

        $validation_error = new WP_Error();

        if ( $validation_error->get_error_code() ) {
          $message = [
            'code' => $validation_error->get_error_message(),
            'message' =>  $validation_error->get_error_message()
          ];

          throw new LL_Exception( $message );
        }

        if ( empty( $creds['user_login'] ) ) {
          $validation_error->add( 'empty_email', 'Email is required' );
          $message = [
            'code' => $validation_error->get_error_code(),
            'message' =>  $validation_error->get_error_message()
          ];

          throw new LL_Exception( $message );
        }

        // Perform the login.
        $user = wp_signon( $creds, is_ssl() );

        if ( is_wp_error( $user ) ) {
          $message = [
            'code' => $user->get_error_code(),
            'message' =>  $user->get_error_message()
          ];
          throw new LL_Exception( $message );
        } else {
          if ( ! empty( $_GET['redirect_to'] ) ) {
            $redirect = wp_unslash( $_GET['redirect_to'] );
          } elseif ( ll_get_raw_referer() ) {
            $redirect = ll_get_raw_referer();
          } else {
            $redirect = home_url();
          }

          wp_redirect( wp_validate_redirect( $redirect ) );
          exit;
        }
      } catch ( LL_Exception $e ) {
        LL_FlashData()->add_notice( $e->getErrorMessage() );
      }
    }
  }

  /*
   * Process Registration form
   */
  public static function process_registration() {
    if ( is_admin() )
      return;

    global $arrow_errors, $arrow_message;

    $nonce_value = ll_get_var( $_REQUEST['arrow-registration-nonce'] );

    if ( isset( $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['phone'], $_POST['pwd_1'], $_POST['pwd_2'] ) && wp_verify_nonce( $nonce_value, 'arrow-registration' ) ) {

        $languages = array(
             'NONE' => 'Select Language',
             '01' => 'English',
             '02' => 'Albanian',
             '03' => 'Amharic',
             '31' => 'Arabic',
             '04' => 'Bosnian',
             '05' => 'Bulgarian',
             '06' => 'Chinese',
             '07' => 'Croation',
             '08' => 'Darl',
             '09' => 'Farsi',
             '10' => 'French',
             '11' => 'German',
             '12' => 'Greek',
             '13' => 'Hindi',
             '14' => 'Italian',
             '15' => 'Japanese',
             '16' => 'Macedonian',
             '17' => 'Mandarin',
             '18' => 'Polish',
             '19' => 'Portuguese',
             '20' => 'Punjabi',
             '21' => 'Russian',
             '22' => 'Serbian',
             '23' => 'Somali',
             '24' => 'Spanish',
             '25' => 'Swahili',
             '26' => 'Tigrinya',
             '27' => 'Turkish',
             '28' => 'Ukraine',
             '29' => 'Urdu',
             '30' => 'Vietnamese'
        );

        $brands = array(
            'NONE' => 'Select Brand',
            'FORD' => 'Ford',
            'FL' => 'Freightliner',
            'GMC' => 'GMC',
            'HINO' => 'Hino',
            'INTL' => 'International',
            'ISUZU' => 'Isuzu',
            'KW' => 'Kenworth',
            'MACK' => 'Mack',
            'PETE' => 'Peterbilt',
            'STERLING' => 'Sterling',
            'VOLVO' => 'Volvo',
            'WSTAR' => 'Western Star'
        );

        $equipment = array(
            'NONE' => 'Select Equipment',
            'A0' => 'All Sleepers',
            'A3' => 'Raised Roof Sleeper',
            'A2' => 'Mid Roof Sleeper',
            'A1' => 'Flat Roof Sleeper',
            'P5' => 'C and C',
            'E1' => 'Day Cab',
            'OO' => 'Dump Truck',
            'O5' => 'Roll Off',
            'K2' => 'Spotter (Yard Dog)',
            'P1' => 'Straight Truck Dry Van',
            'P3' => 'Straight Truck Reefer',
            'P2' => 'Straight Truck Flat',
            'P6' => 'Straight Truck Moving Van',
            'T4' => 'Trailer Belly Dump',
            'T7' => 'Trailer Car Hauler',
            'T5' => 'Trailer Drop Deck',
            'TA' => 'Trailer Dry Van',
            'T8' => 'Trailer End Dump',
            'T1' => 'Trailer Flat',
            'T6' => 'Trailer Grain',
            'T9' => 'Trailer Low Boy',
            'TP' => 'Trailer Pneumatic',
            'T2' => 'Trailer Reefer',
            'T3' => 'Trailer Tanker'
        );

      $first_name     = sanitize_text_field( $_POST['first_name'] );
      $last_name      = sanitize_text_field( $_POST['last_name'] );
      $phone          = sanitize_text_field( $_POST['phone'] );
      $company          = sanitize_text_field( $_POST['company'] );
      $email          = sanitize_text_field( wp_unslash( $_POST['email'] ) );
      $password       = $_POST['pwd_1'];
      $confirm_pass   = $_POST['pwd_2'];
      $address_street = sanitize_text_field( $_POST['address_street'] );
      $address_city   = sanitize_text_field( $_POST['address_city'] );
      $address_state  = sanitize_text_field( $_POST['address_state'] );
      $address_zip    = sanitize_text_field( $_POST['address_zip'] );
      $brand_preference    = sanitize_text_field( $brands[$_POST['brand_preference']] );
      $equipment_preference    = sanitize_text_field( $equipment[$_POST['equipment_preference']] );
      $brand_key    = sanitize_text_field( $_POST['brand_preference'] );
      $equipment_key    = sanitize_text_field( $_POST['equipment_preference'] );
      $ecommerce_source = sanitize_text_field( $_POST['ecommerce_source'] );
      $languagePreference    = sanitize_text_field( $languages[$_POST['languagePreference']] );
      $languagePreference_key    = sanitize_text_field( $_POST['languagePreference'] );


      $billing_street = sanitize_text_field( $_POST['address_street'] );
      $billing_street_2 = null;
      $billing_city = sanitize_text_field( $_POST['address_city'] );
      $billing_st = sanitize_text_field( $_POST['address_state'] );
      $billing_zip = sanitize_text_field( $_POST['address_zip'] );
      $billing_country = null;


      if ( empty( $email ) || !LL_Validation::is_email( $email ) ) {
        $arrow_errors['email'] = 'Please provide a valid email address';
      }

      if ( email_exists( $email ) ) {
        $arrow_errors['email'] = 'An account is already registered with your email address. <a href="'.arrow_page_url( 'login' ).'" class="text-brand-primary underline">Please log in.</a>';
      }

      if ( empty( $address_street ) ) {
        $arrow_errors['address_street'] = 'Street Address is required';
      }

      if ( empty( $address_city ) ) {
        $arrow_errors['address_city'] = 'City is required';
      }

      if ( empty( $address_state ) ) {
        $arrow_errors['address_state'] = 'State is required';
      }

      if ( empty( $address_zip ) ) {
        $arrow_errors['address_zip'] = 'Zipcode is required';
      }

      if ( !empty( $address_zip ) && !LL_Validation::is_postcode( $address_zip, 'US' ) ) {
        $arrow_errors['address_zip'] = 'Please enter a valid zipcode';
      }

      $username = ll_create_new_customer_username( $email, [] );

      if ( empty( $username ) || ! validate_username( $username ) || username_exists( $username ) ) {
        $arrow_errors['username'] = 'Please enter a valid account username.';
      }

      if ( empty( $password ) && empty( $confirm_pass ) ) {
        $arrow_errors['pwd_1'] = 'Password is required.';
        $arrow_errors['pwd_2'] = 'Confirm Password is required.';
      } elseif ( ! empty( $password ) && empty( $confirm_pass ) ) {
        $arrow_errors['pwd_2'] = 'Please re-enter your password.';
      } elseif ( ( ! empty( $password ) || ! empty( $confirm_pass ) ) && $password !== $confirm_pass ) {
        $arrow_errors['pwd_2'] = 'Passwords do not match.';
      }

      if ( empty( $first_name ) ) {
        $arrow_errors['first_name'] = 'First Name is required';
      }

      if ( empty( $last_name ) ) {
        $arrow_errors['last_name'] = 'Last Name is required';
      }

      if ( empty( $phone ) || !LL_Validation::is_phone( $phone ) ) {
        $arrow_errors['phone'] = 'Please enter a valid phone number';
      }

      if(strlen($phone) != 10){
        $arrow_errors['phone'] = 'Not enough or too many digits in phone number.';
      }

      try {
        if ( collect( $arrow_errors )->count() > 0 ) {
          throw new LL_Exception( $arrow_errors );
        }

        $new_user = wp_insert_user( [
          'user_login' => $username,
          'user_pass'  => $pwd_1,
          'user_email' => $email,
          'first_name' => $first_name,
          'last_name'  => $last_name,
          'role'       => 'customer'
        ] );

        if ( is_wp_error( $new_user ) ) {
          $arrow_errors['new_user'] = $new_user->get_error_message();
          throw new LL_Exception( $new_user->get_error_message() );
        }

        wp_set_current_user( $new_user );
        wp_set_auth_cookie( $new_user, true );

        update_usermeta( $new_user, 'phone', strip_phone( $phone ) );
        update_usermeta( $new_user, 'company', strip_phone( $company ) );
        update_usermeta( $new_user, 'address_street', $address_street );
        update_usermeta( $new_user, 'address_city', $address_city );
        update_usermeta( $new_user, 'address_state', $address_state );
        update_usermeta( $new_user, 'address_zip', ll_format_postcode( $address_zip, 'US' ) );
        update_usermeta( $new_user, 'brand_preference', $brand_preference );
        update_usermeta( $new_user, 'equipment_preference', $equipment_preference );
        update_usermeta( $new_user, 'brand_key', $brand_key );
        update_usermeta( $new_user, 'equipment_key', $equipment_key );

        update_usermeta( $new_user, 'languagePreference', $languagePreference );
        update_usermeta( $new_user, 'languagePreference_key', $languagePreference_key );

        update_usermeta( $new_user, 'ecommerce_source', $ecommerce_source );

        $canDoAction = true;

        if(isset($_COOKIE['rep'])) {
            $repArray = ll_decode_cookie_handler('rep', true);
            $rep = $repArray[0]->SLSREPNO;
        }else{
            $rep = null;
        }

        if(is_null($email)){
            $canDoAction = false;
        }

        if($canDoAction == true){

            do_action( 'arrow_new_user_request', [
              'firstName'       => $first_name,
              'lastName'        => $last_name,
              'email'           => $email,
              'phone'           => $phone,
              'billingStreet'   => $billing_street,
              'billingStreet2'  => $billing_street_2,
              'billingCity'     => $billing_city,
              'billingST'       => $billing_st,
              'billingZip'      => $billing_zip,
              'billingCountry'  => $billing_country,
              'makePreference'  => $brand_key,
              'equipmentPreference' => $equipment_key,
              'ecommerceSource' => $ecommerce_source,
              'comments'        => null,
              'salesmanNumber'  => $rep
            ] );

        }

        wp_redirect( wp_validate_redirect( arrow_page_url( 'account' ) ) );
        exit;
      } catch ( Exception $e ) {
      }
    }
  }

  /*
   * Process request password reset form
   */
  public static function process_lost_password() {
    if ('POST' == $_SERVER['REQUEST_METHOD']) {
      $errors = retrieve_password();
      if (is_wp_error($errors)) {
        $redirect_url = arrow_page_url( 'login' ) . '?action=lostpassword';
        $redirect_url = add_query_arg('errors', join(',', $errors->get_error_codes()), $redirect_url);
        $message = json_encode( [
          'code' => $errors->get_error_code(),
          'message' =>  $errors->get_error_message()
        ] );

        LL_FlashData()->add_notice( json_decode( $message ) );
      } else {
        $redirect_url = arrow_page_url( 'login' ) . '?action=lostpassword';
        $redirect_url = add_query_arg('checkemail', 'confirm', $redirect_url);
      }

      wp_redirect($redirect_url);
      exit;
    }
  }

  /*
   * Redirect reset link to custom login page
   */
  public static function redirect_to_password_reset() {
    if ('GET' == $_SERVER['REQUEST_METHOD']) {
      $user = check_password_reset_key($_REQUEST['key'], $_REQUEST['login']);
      if (!$user || is_wp_error($user)) {
        if ($user && $user->get_error_code() === 'expired_key') {
          wp_redirect( arrow_page_url( 'login' ) . '?login=expiredkey' );
        } else {
          wp_redirect( arrow_page_url( 'login' ) . '?login=invalidkey' );
        }
        exit;
      }

      $redirect_url = arrow_page_url( 'login' ) . '?action=lostpassword&reset=true';
      $redirect_url = add_query_arg('login', esc_attr($_REQUEST['login']), $redirect_url);
      $redirect_url = add_query_arg('key', esc_attr($_REQUEST['key']), $redirect_url);

      wp_redirect($redirect_url);
      exit;
    }
  }

  /*
   * Process password reset form
   */
  public static function process_reset_password() {
    if ('POST' == $_SERVER['REQUEST_METHOD']) {
      $rp_key = $_REQUEST['rp_key'];
      $rp_login = $_REQUEST['rp_login'];

      $user = check_password_reset_key($rp_key, $rp_login);

      if (!$user || is_wp_error($user)) {
        if ($user && $user->get_error_code() === 'expired_key') {
          wp_redirect( arrow_page_url( 'login' ) . '?login=expiredkey' );
        } else {
          wp_redirect( arrow_page_url( 'login' ) . '?login=invalidkey' );
        }
        exit;
      }

      if (isset($_POST['pass1'])) {
        if ($_POST['pass1'] != $_POST['pass2']) {
          $redirect_url = arrow_page_url( 'login' ) . '?action=lostpassword&reset=true';

          $redirect_url = add_query_arg('key', $rp_key, $redirect_url);
          $redirect_url = add_query_arg('login', $rp_login, $redirect_url);
          $redirect_url = add_query_arg('errors', 'password_reset_mismatch', $redirect_url);

          wp_redirect($redirect_url);
          exit;
        }

        if (empty($_POST['pass1'])) {
          $redirect_url = arrow_page_url( 'login' ) . '?action=lostpassword&reset=true';

          $redirect_url = add_query_arg('key', $rp_key, $redirect_url);
          $redirect_url = add_query_arg('login', $rp_login, $redirect_url);
          $redirect_url = add_query_arg('errors', 'password_reset_empty', $redirect_url);

          wp_redirect($redirect_url);
          exit;
        }

        reset_password($user, $_POST['pass1']);
        wp_redirect( arrow_page_url( 'login' ) .'?password=changed' );
      } else {
        echo "Invalid request.";
      }

      exit;
    }
  }

  /*
   * Process Edit Personal Information Form
   */

  public static function process_edit_personal_information() {
    if ( empty( $_GET ) || !isset( $_GET['view'] ) || $_GET['view'] !== 'edit-personal-information' )
      return;

    global $arrow_errors, $arrow_message;

    $nonce_value = ll_get_var( $_REQUEST['arrow-edit-personal-information-nonce'] );

    if ( isset( $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['phone'] ) && wp_verify_nonce( $nonce_value, 'arrow-edit-personal-information' ) ) {

        $languages = array(
             'NONE' => 'Select Language',
             '01' => 'English',
             '02' => 'Albanian',
             '03' => 'Amharic',
             '31' => 'Arabic',
             '04' => 'Bosnian',
             '05' => 'Bulgarian',
             '06' => 'Chinese',
             '07' => 'Croation',
             '08' => 'Darl',
             '09' => 'Farsi',
             '10' => 'French',
             '11' => 'German',
             '12' => 'Greek',
             '13' => 'Hindi',
             '14' => 'Italian',
             '15' => 'Japanese',
             '16' => 'Macedonian',
             '17' => 'Mandarin',
             '18' => 'Polish',
             '19' => 'Portuguese',
             '20' => 'Punjabi',
             '21' => 'Russian',
             '22' => 'Serbian',
             '23' => 'Somali',
             '24' => 'Spanish',
             '25' => 'Swahili',
             '26' => 'Tigrinya',
             '27' => 'Turkish',
             '28' => 'Ukraine',
             '29' => 'Urdu',
             '30' => 'Vietnamese'
        );

        $brands = array(
            'NONE' => 'Select Brand',
            'FORD' => 'Ford',
            'FL' => 'Freightliner',
            'GMC' => 'GMC',
            'HINO' => 'Hino',
            'INTL' => 'International',
            'ISUZU' => 'Isuzu',
            'KW' => 'Kenworth',
            'MACK' => 'Mack',
            'PETE' => 'Peterbilt',
            'STERLING' => 'Sterling',
            'VOLVO' => 'Volvo',
            'WSTAR' => 'Western Star'
        );

        $equipment = array(
            'NONE' => 'Select Equipment',
            'A0' => 'All Sleepers',
            'A3' => 'Raised Roof Sleeper',
            'A2' => 'Mid Roof Sleeper',
            'A1' => 'Flat Roof Sleeper',
            'P5' => 'C and C',
            'E1' => 'Day Cab',
            'OO' => 'Dump Truck',
            'O5' => 'Roll Off',
            'K2' => 'Spotter (Yard Dog)',
            'P1' => 'Straight Truck Dry Van',
            'P3' => 'Straight Truck Reefer',
            'P2' => 'Straight Truck Flat',
            'P6' => 'Straight Truck Moving Van',
            'T4' => 'Trailer Belly Dump',
            'T7' => 'Trailer Car Hauler',
            'T5' => 'Trailer Drop Deck',
            'TA' => 'Trailer Dry Van',
            'T8' => 'Trailer End Dump',
            'T1' => 'Trailer Flat',
            'T6' => 'Trailer Grain',
            'T9' => 'Trailer Low Boy',
            'TP' => 'Trailer Pneumatic',
            'T2' => 'Trailer Reefer',
            'T3' => 'Trailer Tanker'
        );


      $arrow_errors = [];
      $arrow_message = false;

      ll_nocache_headers();

      $user_id = get_current_user_id();

      if ( $user_id <= 0 ) {
        return;
      }

      $first_name = sanitize_text_field( $_POST['first_name'] );
      $last_name  = sanitize_text_field( $_POST['last_name'] );
      $email      = sanitize_text_field( $_POST['email'] );
      $phone      = sanitize_text_field( $_POST['phone'] );
      $company      = sanitize_text_field( $_POST['company'] );
      $brand_preference    = sanitize_text_field( $brands[$_POST['brand_preference']] );
      $equipment_preference    = sanitize_text_field( $equipment[$_POST['equipment_preference']] );
      $brand_key    = sanitize_text_field( $_POST['brand_preference'] );
      $equipment_key    = sanitize_text_field( $_POST['equipment_preference'] );
      $ecommerce_source = sanitize_text_field( $_POST['ecommerce_source'] );

      $languagePreference    = sanitize_text_field( $languages[$_POST['languagePreference']] );
      $languagePreference_key    = sanitize_text_field( $_POST['languagePreference'] );

      $current_user       = get_user_by( 'id', $user_id );
      $current_first_name = $current_user->first_name;
      $current_last_name  = $current_user->last_name;
      $current_email      = $current_user->user_email;
      $current_equipment_preference      = $current_user->equipment_preference;
      $current_brand_preference      = $current_user->brand_preference;
      $current_equipment_key      = $current_user->equipment_key;
      $current_brand_key      = $current_user->brand_key;
      $current_ecommerce_source = $current_user->ecommerce_source;

      $current_company      = $current_user->company;
      $current_languagePreference      = $current_user->languagePreference;
      $current_languagePreference_key      = $current_user->languagePreference_key;

      // New user data.
      $user               = new stdClass();
      $user->ID           = $user_id;
      $user->first_name   = $first_name;
      $user->last_name    = $last_name;
      $user->company    = $company;
      $user->equipment_preference    = $equipment_preference;
      $user->brand_preference    = $brand_preference;
      $user->equipment_key    = $equipment_key;
      $user->brand_key    = $brand_key;
      $user->ecommerce_source    = $ecommerce_source;
      $user->display_name = $current_user->display_name;

      $user->languagePreference    = $languagePreference;
      $user->languagePreference_key    = $languagePreference_key;

      if ( empty( $first_name ) ) {
        $arrow_errors['first_name'] = 'First Name is required';
      }

      if ( empty( $last_name ) ) {
        $arrow_errors['last_name'] = 'Last Name is required';
      }

      if ( empty( $email ) ) {
        $arrow_errors['email'] = 'Email is required';
      }

      if ( empty( $phone ) ) {
        $arrow_errors['phone'] = 'Phone is required';
      }

      if ( $phone && !LL_Validation::is_phone( $phone ) ) {
        $arrow_errors['phone'] = 'Please enter a valid phone number';
      }

      if(strlen($phone) != 10){
        $arrow_errors['phone'] = 'Not enough or too many digits in phone number.';
      }

      if ( $email ) {
        $email = sanitize_email( $email );
        if ( ! LL_Validation::is_email( $email ) ) {
          $arrow_errors['email'] = 'Please provide a valid email address';
        } elseif ( email_exists( $email ) && $email !== $current_user->user_email ) {
          $arrow_errors['email'] = 'This email address is already registered.';
        }

        $user->user_email = $email;
      }

      if ( collect( $arrow_errors )->count() == 0 ) {

        wp_update_user( $user );
        update_usermeta( $user_id, 'phone', strip_phone( $phone ) );
        update_usermeta( $user_id, 'equipment_preference', $equipment_preference );
        update_usermeta( $user_id, 'brand_preference', $brand_preference );
        update_usermeta( $user_id, 'equipment_key', $equipment_key );
        update_usermeta( $user_id, 'brand_key', $brand_key );
        update_usermeta( $user_id, 'ecommerce_source', $ecommerce_source );

        update_usermeta( $user_id, 'company', strip_phone( $company ) );
        update_usermeta( $user_id, 'languagePreference', $languagePreference );
        update_usermeta( $user_id, 'languagePreference_key', $languagePreference_key );

        $arrow_message = 'Personal Information Updated';

        if ($_POST['post_source'] == 'update') {
            do_action( 'arrow_account_request', $user_id, $current_email );
        }

      }
    }
  }

  /*
   * Process Edit Billing Address Form
   */
  public static function process_edit_billing_address() {
    if ( empty( $_GET ) || !isset( $_GET['view'] ) || $_GET['view'] !== 'edit-billing-address' )
      return;

    global $arrow_errors, $arrow_message;
    $nonce_value = ll_get_var( $_REQUEST['arrow-edit-billing-address-nonce'] );
    if ( isset( $_POST['address_street'], $_POST['address_city'], $_POST['address_state'], $_POST['address_zip'] ) && wp_verify_nonce( $nonce_value, 'arrow-edit-billing-address' ) ) {

      $arrow_errors = [];
      $arrow_message = false;


      ll_nocache_headers();

      $user_id = get_current_user_id();

      if ( $user_id <= 0 ) {
        return;
      }

      $address_street = sanitize_text_field( $_POST['address_street'] );
      $address_city   = sanitize_text_field( $_POST['address_city'] );
      $address_state  = sanitize_text_field( $_POST['address_state'] );
      $address_zip    = sanitize_text_field( $_POST['address_zip'] );

      if ( empty( $address_street ) ) {
        $arrow_errors['address_street'] = 'Street Address is required';
      }

      if ( empty( $address_city ) ) {
        $arrow_errors['address_city'] = 'City is required';
      }

      if ( empty( $address_state ) ) {
        $arrow_errors['address_state'] = 'State is required';
      }

      if ( empty( $address_zip ) ) {
        $arrow_errors['address_zip'] = 'Zipcode is required';
      }

      if ( !empty( $address_zip ) && !LL_Validation::is_postcode( $address_zip, 'US' ) ) {
        $arrow_errors['address_zip'] = 'Please enter a valid zipcode';
      }

      if ( collect( $arrow_errors )->count() == 0 ) {
        update_usermeta( $user_id, 'address_street', $address_street );
        update_usermeta( $user_id, 'address_city', $address_city );
        update_usermeta( $user_id, 'address_state', $address_state );
        update_usermeta( $user_id, 'address_zip', ll_format_postcode( $address_zip, 'US' ) );
        $arrow_message = 'Billing Address Updated!';


        // var_error_log('>>------------------------------------->-> PROCESS-EDIT-BILLING-ADDRESS <<-------------------------------------<-<');
        do_action( 'arrow_account_request', $user_id );
      }
    }
  }

  /*
   * Process Edit Shipping Address Form
   */
  public static function process_edit_shipping_address() {
    if ( empty( $_GET ) || !isset( $_GET['view'] ) || $_GET['view'] !== 'edit-shipping-address' )
      return;

    global $arrow_errors, $arrow_message;

    $nonce_value = ll_get_var( $_REQUEST['arrow-edit-shipping-address-nonce'] );
    if ( isset( $_POST['shipping_street'], $_POST['shipping_city'], $_POST['shipping_state'], $_POST['shipping_zip'] ) && wp_verify_nonce( $nonce_value, 'arrow-edit-shipping-address' ) ) {
      $arrow_errors = [];
      $arrow_message = false;

      ll_nocache_headers();

      $user_id = get_current_user_id();

      if ( $user_id <= 0 ) {
        return;
      }

      $shipping_street = sanitize_text_field( $_POST['shipping_street'] );
      $shipping_city   = sanitize_text_field( $_POST['shipping_city'] );
      $shipping_state  = sanitize_text_field( $_POST['shipping_state'] );
      $shipping_zip    = sanitize_text_field( $_POST['shipping_zip'] );

      if ( empty( $shipping_street ) ) {
        $arrow_errors['shipping_street'] = 'Street Address is required';
      }

      if ( empty( $shipping_city ) ) {
        $arrow_errors['shipping_city'] = 'City is required';
      }

      if ( empty( $shipping_state ) ) {
        $arrow_errors['shipping_state'] = 'State is required';
      }

      if ( empty( $shipping_zip ) ) {
        $arrow_errors['shipping_zip'] = 'Zipcode is required';
      }

      if ( !empty( $shipping_zip ) && !LL_Validation::is_postcode( $shipping_zip, 'US' ) ) {
        $arrow_errors['shipping_zip'] = 'Please enter a valid zipcode';
      }

      if ( collect( $arrow_errors )->count() == 0 ) {
        update_usermeta( $user_id, 'shipping_street', $shipping_street );
        update_usermeta( $user_id, 'shipping_city', $shipping_city );
        update_usermeta( $user_id, 'shipping_state', $shipping_state );
        update_usermeta( $user_id, 'shipping_zip', ll_format_postcode( $shipping_zip, 'US' ) );
        $arrow_message = 'Shipping Address Updated!';

        // var_error_log('>>------------------------------------->-> PROCESS-EDIT-SHIPPING-ADDRESS <<-------------------------------------<-<');
        do_action( 'arrow_account_request', $user_id );
      }
    }
  }

  /*
   * Process Edit Password Form
   */
  public static function process_edit_password() {
    if ( empty( $_GET ) || !isset( $_GET['view'] ) || $_GET['view'] !== 'edit-password' )
      return;

    global $arrow_errors, $arrow_message;

    $nonce_value = ll_get_var( $_REQUEST['arrow-edit-password-nonce'] );
    if ( isset( $_POST['pwd_1'], $_POST['pwd_2'] ) && wp_verify_nonce( $nonce_value, 'arrow-edit-password' ) ) {
      $arrow_errors = [];
      $arrow_message = false;

      ll_nocache_headers();

      $user_id = get_current_user_id();

      if ( $user_id <= 0 ) {
        return;
      }

      // Current user data.
      $current_user       = get_user_by( 'id', $user_id );

      // New user data.
      $user               = new stdClass();
      $user->ID           = $user_id;
      $user->first_name   = $current_user->first_name;
      $user->last_name    = $current_user->last_name;
      $user->user_email   = $current_user->user_email;
      $user->display_name = $current_user->display_name;

      $pass1 = ! empty( $_POST['pwd_1'] ) ? $_POST['pwd_1'] : '';
      $pass2 = ! empty( $_POST['pwd_2'] ) ? $_POST['pwd_2'] : '';
      $save_pass = true;

      if ( empty( $pass1 ) && empty( $pass2 ) ) {
        $arrow_errors['pwd_1'] = 'New Password is required.';
        $arrow_errors['pwd_2'] = 'Confirm New Password is required.';
        $save_pass = false;
      } elseif ( ! empty( $pass1 ) && empty( $pass2 ) ) {
        $arrow_errors['pwd_2'] = 'Please re-enter your password.';
        $save_pass = false;
      } elseif ( ( ! empty( $pass1 ) || ! empty( $pass2 ) ) && $pass1 !== $pass2 ) {
        $arrow_errors['pwd_2'] = 'Passwords do not match.';
        $save_pass = false;
      }

      if ( $pass1 && $save_pass ) {
        $user->user_pass = $pass1;
      }

      if ( collect( $arrow_errors )->count() == 0 ) {
        wp_update_user( $user );
        $arrow_message = 'Password updated';
      }
    }
  }

  public static function process_filter_display_options() {
    if ( !is_admin() && get_current_screen()->id !== 'll_inventory_page_arrow-filters' )
      return;

    if ( !isset( $_POST['_arrow_nonce'] ) || !wp_verify_nonce( $_POST['_arrow_nonce'], 'arrow-filter-admin' ) ) {
      return;
    }

    $_POST['_arrow_nonce'] = false;

    $data = $_POST;
    unset( $data['_arrow_nonce'] );
    unset( $data['_wp_http_referer'] );
    $data = ll_safe_encode( $data );
    update_option( 'arrow_filter_display_options', $data );
  }
}

ArrowFormHandler::init();

/*
 * Rewrite Login URL to allow custom login page to use
 * Wordpress built-in password reset rules and allow
 * correct redirects for auth_redirect()
 *
 * had to tell WP Engine to turn off their login plugin to
 * make this work fully on WPE
 */
add_filter( 'login_url', function( $login_url, $redirect, $force_reauth ) {
  $login_url = get_permalink( get_field( 'page_id_login', 'option' ) );

  if ( $redirect ) {
    $login_url = add_query_arg( 'redirect_to', urlencode( $redirect ), $login_url );
  }

  if ( $force_reauth ) {
    $login_url = add_query_arg( 'reauth', '1', $login_url );
  }

  return $login_url;
}, 10, 3 );
