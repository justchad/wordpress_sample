<?php
class ArrowFilterTerm
{
  public $children = [];
  public $conditional_ids = [];
  public $has_children = false;

  public function __construct( $data=array() ) {
    if ( is_object( $data ) ) {
      $this->conditional_ids = get_field( 'parent_taxonomy', $data );
      $data = get_object_vars( $data );
    }

    array_map( function( $key, $value ) {
      /*
       * Convert menu_item_parent to parent_id because
       * it makes more sense to me.
       */
      switch( $key ) {
        case 'description':
          $key = 'value';
          break;
      }

      $this->$key = $value;
    }, array_keys( $data ), $data );
  }

  /*
   * helper function to get specific custom meta field
   */
  public function field( $key ) {
    return $this->fields[$key];
  }
}
