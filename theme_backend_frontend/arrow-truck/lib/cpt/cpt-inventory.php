<?php
/**
 * Register the custom post type
 */
if ( ! function_exists('register_inventory_custom_post_type') ) {

  // Register Custom Post Type
  function register_inventory_custom_post_type() {

    /*
     * Checks if you've setup a custom page to use as
     * the archive for this post type. To do that, use the
     * commented out block at the bottom to register the settings page.
     * This makes it behave like the default page_for_posts so that
     * content can change the slug to be more seo friendly.
     */
    if (class_exists('ACF')) {
      $id = get_field( 'page_for_inventory', 'option' );
    }
    $slug = 'inventory';

    if ( $id ) {
      $slug = ll_get_the_slug( $id );
    }

    $labels = array(
      'name'                => 'Inventory',
      'singular_name'       => 'Inventory',
      'menu_name'           => 'Inventory',
      'parent_item_colon'   => 'Parent Inventory',
      'all_items'           => 'All Inventory',
      'view_item'           => 'View Inventory',
      'add_new_item'        => 'Add New Inventory',
      'add_new'             => 'New Inventory',
      'edit_item'           => 'Edit Inventory',
      'update_item'         => 'Update Inventory',
      'search_items'        => 'Search Inventory',
      'not_found'           => 'No inventory found',
      'not_found_in_trash'  => 'No inventory found in Trash',
    );
    $args = array(
      'label'               => 'inventory',
      'description'         => 'Inventory description',
      'labels'              => $labels,
      'supports'            => array( 'title', 'page-attributes', 'editor' ),
      // 'taxonomies'          => array( 'category', 'post_tag' ),
      'hierarchical'        => true,
      'public'              => true,
      'show_ui'             => true,
      'show_in_menu'        => true,
      'show_in_nav_menus'   => false,
      'show_in_admin_bar'   => true,
      'menu_position'       => 20,
      'menu_icon'           => 'data:image/svg+xml;base64,PHN2ZyBhcmlhLWhpZGRlbj0idHJ1ZSIgZm9jdXNhYmxlPSJmYWxzZSIgZGF0YS1wcmVmaXg9ImZhcyIgZGF0YS1pY29uPSJ0cnVjayIgY2xhc3M9InN2Zy1pbmxpbmUtLWZhIGZhLXRydWNrIGZhLXctMjAiIHJvbGU9ImltZyIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB2aWV3Qm94PSIwIDAgNjQwIDUxMiI+PHBhdGggZmlsbD0iY3VycmVudENvbG9yIiBkPSJNNjI0IDM1MmgtMTZWMjQzLjljMC0xMi43LTUuMS0yNC45LTE0LjEtMzMuOUw0OTQgMTEwLjFjLTktOS0yMS4yLTE0LjEtMzMuOS0xNC4xSDQxNlY0OGMwLTI2LjUtMjEuNS00OC00OC00OEg0OEMyMS41IDAgMCAyMS41IDAgNDh2MzIwYzAgMjYuNSAyMS41IDQ4IDQ4IDQ4aDE2YzAgNTMgNDMgOTYgOTYgOTZzOTYtNDMgOTYtOTZoMTI4YzAgNTMgNDMgOTYgOTYgOTZzOTYtNDMgOTYtOTZoNDhjOC44IDAgMTYtNy4yIDE2LTE2di0zMmMwLTguOC03LjItMTYtMTYtMTZ6TTE2MCA0NjRjLTI2LjUgMC00OC0yMS41LTQ4LTQ4czIxLjUtNDggNDgtNDggNDggMjEuNSA0OCA0OC0yMS41IDQ4LTQ4IDQ4em0zMjAgMGMtMjYuNSAwLTQ4LTIxLjUtNDgtNDhzMjEuNS00OCA0OC00OCA0OCAyMS41IDQ4IDQ4LTIxLjUgNDgtNDggNDh6bTgwLTIwOEg0MTZWMTQ0aDQ0LjFsOTkuOSA5OS45VjI1NnoiPjwvcGF0aD48L3N2Zz4=',
      'can_export'          => true,
      'has_archive'         => true,
      'exclude_from_search' => true,
      'publicly_queryable'  => true,
      'capability_type'     => 'post',
      'rewrite'             => array( 'slug' => $slug )
    );
    register_post_type( 'll_inventory', $args );

  }

  // Hook into the 'init' action
  add_action( 'init', 'register_inventory_custom_post_type', 0 );

}

/**
 * Custom taxonomies
 */
if ( ! function_exists('register_inventory_taxonomies') ) {

  function register_inventory_taxonomies() {
    $filters = [
      [
        'label' => 'Type',
        'slug'  => 'trucktype'
      ],
      [
        'label' => 'Make',
        'slug'  => 'invmake'
      ],
      [
        'label' => 'Model',
        'slug'  => 'invmodl'
      ],
      [
        'label' => 'Engine Make',
        'slug'  => 'invemake'
      ],
      [
        'label' => 'Engine Model',
        'slug'  => 'invmodel'
      ],
      [
        'label' => 'Transmission Type',
        'slug'  => 'invtrnty'
      ],
      [
        'label' => 'Transmission Make',
        'slug'  => 'invtrans'
      ],
      [
        'label' => 'Transmission Model',
        'slug'  => 'invtmodl'
      ],
      [
        'label' => 'Transmission Speed',
        'slug'  => 'invtspd'
      ],
      [
        'label' => 'Suspension',
        'slug'  => 'invsusp'
      ],
      [
        'label' => 'Mileage',
        'slug'  => 'invmilag'
      ],
      [
        'label' => 'Price',
        'slug'  => 'invprice'
      ],
      [
        'label' => 'Price',
        'slug'  => 'invprice'
      ],
      [
        'label' => 'Engine Horsepower',
        'slug'  => 'invhpwr'
      ],
      [
        'label' => 'Axle',
        'slug'  => 'invaxle'
      ],
      [
        'label' => 'Sleeper Type',
        'slug'  => 'invslpr'
      ],
      [
        'label' => 'Sleeper Size',
        'slug'  => 'invslpsz'
      ],
      [
        'label' => 'Axle',
        'slug'  => 'invaxle'
      ],
      [
        'label' => 'Location',
        'slug'  => 'invbrnid'
      ],
      [
        'label' => 'Axle',
        'slug'  => 'invaxle'
      ],
      [
        'label' => 'Industry',
        'slug'  => 'industry'
      ],
      [
        'label' => 'Trailer',
        'slug'  => 'trailer'
      ],
      [
        'label' => 'Fleet Code',
        'slug'  => 'fleetcode'
      ]
    ];

    foreach ( $filters as $filter ) {
      $label = $filter['label'];
      $slug  = 'll_inventory_'.$filter['slug'];

      $labels = array(
        'name'                => _x( $label, 'taxonomy general name' ),
        'singular_name'       => _x( $label, 'taxonomy singular name' ),
        'search_items'        => __( 'Search Categories' ),
        'all_items'           => __( 'All Categories' ),
        'edit_item'           => __( 'Edit ' . $label ),
        'update_item'         => __( 'Update ' . $label ),
        'add_new_item'        => __( 'Add New ' . $label ),
        'new_item_name'       => __( 'New ' . $label . ' Name' ),
        'menu_name'           => __( $label )
      );

      $args = array(
        'hierarchical'        => false,
        'labels'              => $labels,
        'show_ui'             => true,
        'show_admin_column'   => false,
        'query_var'           => true,
        'meta_box_cb'         => false,
        'rewrite'             => array( 'slug' => sanitize_title( $label ) )
      );

      register_taxonomy( $slug, array( 'll_inventory' ), $args );
    }
  }

  add_action( 'init', 'register_inventory_taxonomies', 0 );

}

/**
 * Create ACF setting page under CPT menu
 */

// if ( function_exists( 'acf_add_options_sub_page' ) ){
//   acf_add_options_sub_page(array(
//     'page_title' => 'Inventory Settings',
//     'menu_title' => 'Settings',
//     'menu_slug'  => 'll-inventory-settings',
//     'parent'     => 'edit.php?post_type=ll_inventory',
//     'capability' => 'edit_posts'
//   ));
// }
