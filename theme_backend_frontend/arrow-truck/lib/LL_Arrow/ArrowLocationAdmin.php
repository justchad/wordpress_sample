<?php
/*
 * Back end Views
 */
class ArrowLocationsAdmin {

    private $title      = 'Arrow Location API';
    private $parent     = 'edit.php?post_type=ll_location';
    private $capability = 'manage_options';
    private $slug       = 'arrow-locations';
    private $menu_name;

    function __construct() {
        $this->menu_name = $this->title;
        $this::add_settings_page();
    }

    function add_settings_page() {
        add_submenu_page( $this->parent, $this->title, $this->menu_name, $this->capability, $this->slug, array( __CLASS__, 'render_settings_page' ) );
    }

    static function render_settings_page() {
        $locations_on_site = get_posts( array(
            'post_type' => 'll_location',
            'posts_per_page' => -1
        ) );
        include_once( get_stylesheet_directory() . '/templates/admin/admin-locations.php' );
    }

    static function setup_data_box() {
        add_action( 'add_meta_boxes', function() {
            add_meta_box(
                'arrow-location-api-data',
                'API Data',
                '\ArrowLocationsAdmin::render_data_box',
                'll_location',
                'side',
                'low'
            );
        } );
    }

    static function render_data_box( $post ) {
        $location = new ArrowApiLocation();
        $info = ll_safe_decode( get_post_meta( $post->ID, 'arrow_data', true ) );
        if ( $post->post_name == 'buyers' ) {
            $data = $location->get( 'KC' )->first();
        } else {
            $data = $location->get( $info->BRANCH )->first();
        }
        include_once( get_stylesheet_directory() . '/templates/admin/data-box-locations.php' );
    }

}

add_action( 'admin_menu', function() {
  new ArrowLocationsAdmin();
} );

add_action( 'load-post.php', '\ArrowLocationsAdmin::setup_data_box' );
add_action( 'load-post-new.php','\ArrowLocationsAdmin::setup_data_box' );