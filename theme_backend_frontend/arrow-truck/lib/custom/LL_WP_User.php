<?php
/*
 * Decorator for WP_User Class
 *
 */
class LL_WP_User {
  /**
   * WP_User object that we are modifying
   * @var WP_User
   */
  protected $wp_user;

  public function get_name() {
    if ( $this->wp_user->first_name ) {
      return $this->wp_user->first_name;
    }

    return $this->wp_user->user_nicename;
  }

  public function full_name() {
    return $this->wp_user->first_name . ' ' . $this->wp_user->last_name;
  }

  public function __construct(WP_User $wp_user) {
    $this->wp_user = $wp_user;
    $this->phone = get_user_meta( $wp_user->ID, 'phone', true );
    if ( $this->phone ) {
      $this->phone = format_phone( $this->phone );
    }

    $this->address_street  = get_user_meta( $wp_user->ID, 'address_street', true );
    $this->address_city    = get_user_meta( $wp_user->ID, 'address_city', true );
    $this->address_state   = get_user_meta( $wp_user->ID, 'address_state', true );
    $this->address_zip     = get_user_meta( $wp_user->ID, 'address_zip', true );
    $this->shipping_street = get_user_meta( $wp_user->ID, 'shipping_street', true );
    $this->shipping_city   = get_user_meta( $wp_user->ID, 'shipping_city', true );
    $this->shipping_state  = get_user_meta( $wp_user->ID, 'shipping_state', true );
    $this->shipping_zip    = get_user_meta( $wp_user->ID, 'shipping_zip', true );
  }

  public function __call($method, $args) {
    return call_user_func_array(array($this->wp_user, $method), $args);
  }

  public function __get($key) {
    return $this->wp_user->$key;
  }

  public function __set($key, $val) {
    return $this->wp_user->$key = $val;
  }

  public static function getData( WP_REST_Request $request ) {
    // $cookie_item = isset( $_COOKIE['wordpress_salesmanNumber'] ) ? $_COOKIE['wordpress_salesmanNumber'] : null;
    $cookie_item_raw = isset( $_COOKIE['rep_no'] ) ? $_COOKIE['rep_no'] : null;
    $cookie_item = ll_decode_cookie_handler('rep_no', false);
    $type = null;

    // var_error_log('<----->>---user/data/api getData---<<----->');
    // var_error_log($cookie_item);

    if ( $cookie_item ) {
      if ( is_numeric( $cookie_item ) ) {
        $type = 'rep';
        $cookie_item = reset( get_users( array(
          'meta_key' => 'SLSREPNO',
          'meta_value' => $cookie_item,
          'number' => 1,
          'count_total' => false
          ) ) );

        if ( $cookie_item ) {
          $cookie_item = new ArrowSalesRep( $cookie_item, true );
        }
      } else {
        $type = 'location';
        $cookie_item = reset( get_posts( [
          'post_type' => 'll_location',
          'posts_per_page' => 1,
          'meta_key' => 'BRNBRNID',
          'meta_value' => $cookie_item
        ] ) );

        if ( $cookie_item ) {
          $cookie_item = new ArrowLocation( $cookie_item, false );
        }
      }
    }

    if ( !$cookie_item ) {
      $cookie_item = null;
    }

    return new WP_REST_Response( [
      'location' => $cookie_item,
      'type'     => $type
    ], 200 );
  }

  public static function getFavorites( WP_REST_Request $request )
  {
    $user_id   = get_current_user_id();
    $favorites = get_user_meta( $user_id, 'arrow_favorites', true ) ?? [];
    $favorites = collect( $favorites )->filter()->all();

    return new WP_REST_Response( [
      'results' => $favorites
    ] );
  }

  public static function addFavorite( WP_REST_Request $request )
  {
    $request_data = $request->get_params();
    $user_id      = get_current_user_id();
    $truck        = $request_data['truck'];
    $favorites    = get_user_meta( $user_id, 'arrow_favorites', true ) ?? [];
    $favorites    = collect( $favorites )->prepend( $truck );
    $favorites    = $favorites->unique()->filter()->values()->all();
    update_user_meta( $user_id, 'arrow_favorites', $favorites );

    return new WP_REST_Response( [
      'results' => $favorites
    ] );
  }

  public static function removeFavorite( WP_REST_Request $request )
  {
    $request_data = $request->get_params();
    $user_id      = get_current_user_id();
    $truck        = $request_data['truck'];
    $favorites    = get_user_meta( $user_id, 'arrow_favorites', true ) ?? [];
    $favorites    = collect( $favorites )->unique()->filter( function( $item ) use ( $truck ) {
      return ll_safe_decode( $item )->INVSTKNO !== $truck;
    } )->values()->all();
    update_user_meta( $user_id, 'arrow_favorites', $favorites );

    return new WP_REST_Response( [
      'results' => $favorites
    ] );
  }
}
