<?php
/*
 * Back end Views
 */
class ArrowInventoryAdmin {
    private $title      = 'Arrow Inventory API';
    private $parent     = 'edit.php?post_type=ll_inventory';
    private $capability = 'manage_options';
    private $slug       = 'arrow-inventory';
    private $menu_name;

    function __construct() {
        $this->menu_name = $this->title;
        $this::add_settings_page();
    }

    function add_settings_page() {
        add_submenu_page( $this->parent, $this->title, $this->menu_name, $this->capability, $this->slug, array( __CLASS__, 'render_settings_page' ) );
        add_submenu_page( $this->parent, 'Filter Display', 'Filter Display', $this->capability, 'arrow-filters', array( __CLASS__, 'render_filters_page' ) );
    }

    static function render_settings_page() {
        $inventory_on_site = get_posts( array(
            'post_type' => 'll_inventory',
            'posts_per_page' => -1
        ) );
        include_once( get_stylesheet_directory() . '/templates/admin/admin-inventory.php' );
    }

    static function render_filters_page() {
        $taxonomies = get_object_taxonomies( 'll_inventory', 'objects' );
        $display_data = ll_safe_decode( get_option( 'arrow_filter_display_options' ) );
        include_once( get_stylesheet_directory() . '/templates/admin/admin-filters.php' );
    }

    static function setup_data_box() {
        add_action( 'add_meta_boxes', function() {
            add_meta_box(
                'arrow-location-api-data',
                'API Data',
                '\ArrowInventoryAdmin::render_data_box',
                'll_inventory',
                'side',
                'low'
            );
        } );
    }

    static function render_data_box( $post ) {
        $data = ll_safe_decode( get_post_meta( $post->ID, 'arrow_data', true ) );
        include_once( get_stylesheet_directory() . '/templates/admin/data-box-locations.php' );
    }

}

add_action( 'admin_menu', function() {
    new ArrowInventoryAdmin();
} );

add_action( 'load-post.php', '\ArrowInventoryAdmin::setup_data_box' );
add_action( 'load-post-new.php','\ArrowInventoryAdmin::setup_data_box' );
