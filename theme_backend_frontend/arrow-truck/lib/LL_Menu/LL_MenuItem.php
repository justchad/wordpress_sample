<?php
class LL_MenuItem {
  public $ID;
  public $children = array();
  public $classes;
  public $level = 0;
  public $title;
  public $url;
  public $target;
  public $parent_id = 0;
  public $has_children = false;

  public function __construct( $data=array() ) {
    if ( is_object( $data ) ) {
      $data = get_object_vars( $data );
    }

    if ( $data ) {
      $data = array_filter( $data, function( $value, $key ) {
        return property_exists( $this, $key ) || $key === 'menu_item_parent';
      }, ARRAY_FILTER_USE_BOTH );
    }

    array_map( function( $key, $value ) {
      /*
       * Convert menu_item_parent to parent_id because
       * it makes more sense to me.
       */
      if ( $key === 'menu_item_parent' ) {
        $key = 'parent_id';
      }

      $this->$key = $value;
    }, array_keys( $data ), $data );

    //force parent_id to be an integer because it just should be
    $this->parent_id = intval( $this->parent_id );

    if ( $this->ID ) {
      $this->fields = get_fields( $this->ID );
    }
  }

  /*
   * helper function to get specific custom meta field
   */
  public function field( $key ) {
    return $this->fields[$key];
  }
}
