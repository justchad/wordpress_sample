<?php

class LL_Middleware {

  private static $instance;

  public static function getInstance()
  {
    if ( is_null( self::$instance ) )
    {
      self::$instance = new self();
    }
    return self::$instance;
  }

  public static function open()
  {
    return true;
  }

  public static function isAdmin( $request )
  {
    if ( !current_user_can('administrator') ) {
      if ( $request->is_server_request ) {
        wp_safe_redirect( home_url() );
        exit;
      } else {
        return new WP_Error(
          'rest_forbidden_context',
          __( 'Sorry, you do not have correct permissions.' ),
          array( 'status' => rest_authorization_required_code() )
        );
      }
    }

    return true;
  }

  public static function isEditor( $request )
  {
    if ( !current_user_can('editor') && !current_user_can('administrator') ) {
      wp_safe_redirect( home_url() );
      exit;
    }

    return true;
  }

  public static function isLoggedIn( $request )
  {
    if ( !is_user_logged_in() ) {
      wp_safe_redirect( home_url() );
      exit;
    }

    return true;
  }
}
