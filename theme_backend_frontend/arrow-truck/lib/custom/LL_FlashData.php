<?php
class LL_FlashData {
  protected static $_instance = null;
  public $notice;

  public static function instance()
  {
    if ( is_null( self::$_instance ) ) {
      self::$_instance = new self();
    }

    return self::$_instance;
  }

  public function __construct() {
    $this->notice = null;
  }

  public function add_notice( $message ) {
    $this->notice = $message;
    $this->map();
  }

  private function map() {
    switch ($this->notice->code) {
      case 'empty_password':
        $this->notice->message = 'Password is required';
        break;

      case 'invalid_email':
        $this->notice->message = 'Email entered has not been registered';
        break;

      case 'empty_username':
        $this->notice->message = 'Email is required';
        break;

      case 'incorrect_password':
        $this->notice->message = 'Email and Password combination do not match';
        break;

      case 'password_reset_mismatch':
        $this->notice->message = 'Passwords do not match';
        break;

      case 'password_reset_empty':
        $this->notice->message = 'Password is required';
        break;

      case 'invalid_username':
        $this->notice->message = 'Invalid Email format';
        break;
      default:
        break;
    }

    return $this;
  }

  public function check( $prop ) {
    if ( !$this->notice )
      return false;

    if ( is_array( $prop ) ) {
      return in_array( $this->notice->code, $prop );
    } else {
      return $this->notice->code == $prop;
    }
  }
}

function LL_FlashData() {
  return LL_FlashData::instance();
}

class LL_Exception extends \Exception {
  public function __construct( $message = null, $code = 0, Exception $previous = null ) {
    parent::__construct(json_encode($message), $code, $previous);
  }

  public function getErrorMessage() {
    return json_decode( $this->getMessage() );
  }
}