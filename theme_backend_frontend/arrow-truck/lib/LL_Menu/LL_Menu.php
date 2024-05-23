<?php
class LL_Menu {
  public $items = null;
  public $ID;
  public $name;
  public $location;
  public $fields;
  public $wp_menu;

  public function __construct( $slug ) {
    $this->location = $slug;
    $locations      = get_nav_menu_locations();

    if( ! isset( $locations[$slug] ) ){
        return false;
    }

    if ( ! $locations[$slug] ) {
      return new WP_Error( 'Menu location not found.' );
    }

    $this->ID = $locations[$slug];

    if ( $this->ID ) {
      $this->wp_menu = wp_get_nav_menu_object( $this->ID );
      $this->name    = $this->wp_menu->name;
      $this->fields  = get_fields( $this->wp_menu );
    }

    $this->init();
  }

  private function init() {
    $this->items = wp_get_nav_menu_items( $this->ID );
    $this::sort_children();
    $this->hasItems = count( $this->items ) > 0;
  }

  private function sort_children() {
    /*
     * Convert all items to LL_MenuItem
     */
    $this->items = array_map( function( $menu_item ) {
      $menu_item = new LL_MenuItem( $menu_item );
      return $menu_item;
    }, $this->items );

    /*
     * Arrange all child menu items into a nested tree structure
     */
    $this->items = array_map( function( $menu_item ) {
      $menu_item->children = array_values( array_filter( $this->items, function( $child ) use ($menu_item) {
        return $menu_item->ID === $child->parent_id;
      } ) );

      $menu_item->hasChildren = count( $menu_item->children ) > 0;

      return $menu_item;
    }, $this->items );

    /*
     * Remove Children from top level
     */
    $this->items = array_values( array_filter( $this->items, function( $menu_item ) {
      return $menu_item->parent_id === 0;
    } ) );
  }
}
