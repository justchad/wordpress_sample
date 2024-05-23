<?php
/**
 * Register the custom post type
 */
if ( ! function_exists('register_service_custom_post_type') ) {

  // Register Custom Post Type
  function register_service_custom_post_type() {

    /*
     * Checks if you've setup a custom page to use as
     * the archive for this post type. To do that, use the
     * commented out block at the bottom to register the settings page.
     * This makes it behave like the default page_for_posts so that
     * content can change the slug to be more seo friendly.
     */
    if (class_exists('ACF')) {
      $id = get_field( 'page_for_services', 'option' );
    }
    $slug = 'services';

    if ( $id ) {
      $slug = ll_get_the_slug( $id );
    }

    $labels = array(
      'name'                => 'Services',
      'singular_name'       => 'Service',
      'menu_name'           => 'Services',
      'parent_item_colon'   => 'Parent Service',
      'all_items'           => 'All Services',
      'view_item'           => 'View Service',
      'add_new_item'        => 'Add New Service',
      'add_new'             => 'New Service',
      'edit_item'           => 'Edit Service',
      'update_item'         => 'Update Service',
      'search_items'        => 'Search Service',
      'not_found'           => 'No service found',
      'not_found_in_trash'  => 'No service found in Trash',
    );
    $args = array(
      'label'               => 'service',
      'description'         => 'Service description',
      'labels'              => $labels,
      'supports'            => array( 'title', 'page-attributes' ),
      // 'taxonomies'          => array( 'category', 'post_tag' ),
      'hierarchical'        => true,
      'public'              => true,
      'show_ui'             => true,
      'show_in_menu'        => true,
      'show_in_nav_menus'   => false,
      'show_in_admin_bar'   => true,
      'menu_position'       => 20,
      'menu_icon'           => 'dashicons-groups',
      'can_export'          => true,
      'has_archive'         => true,
      'exclude_from_search' => true,
      'publicly_queryable'  => true,
      'capability_type'     => 'post',
      'rewrite'             => array( 'slug' => $slug )
    );
    register_post_type( 'll_service', $args );

  }

  // Hook into the 'init' action
  add_action( 'init', 'register_service_custom_post_type', 0 );

}

/**
 * Custom taxonomies
 */
if ( ! function_exists('register_service_taxonomies') ) {

  function register_service_taxonomies() {

    // Add new taxonomy, make it hierarchical (like categories)
    $labels = array(
      'name'                => _x( 'Category', 'taxonomy general name' ),
      'singular_name'       => _x( 'Category', 'taxonomy singular name' ),
      'search_items'        => __( 'Search Categories' ),
      'all_items'           => __( 'All Categories' ),
      'parent_item'         => __( 'Parent Category' ),
      'parent_item_colon'   => __( 'Parent Category:' ),
      'edit_item'           => __( 'Edit Category' ),
      'update_item'         => __( 'Update Category' ),
      'add_new_item'        => __( 'Add New Category' ),
      'new_item_name'       => __( 'New Category Name' ),
      'menu_name'           => __( 'Categories' )
    );

    $args = array(
      'hierarchical'        => true,
      'labels'              => $labels,
      'show_ui'             => true,
      'show_admin_column'   => true,
      'query_var'           => true,
      'rewrite'             => array( 'slug' => 'service-category' )
    );

    register_taxonomy( 'll_service_category', array( 'll_service' ), $args ); // Must include custom post type name

    // Add new taxonomy, NOT hierarchical (like tags)
    $labels = array(
      'name'                         => _x( 'Tags', 'taxonomy general name' ),
      'singular_name'                => _x( 'Tag', 'taxonomy singular name' ),
      'search_items'                 => __( 'Search Service Tags' ),
      'popular_items'                => __( 'Popular Service Tags' ),
      'all_items'                    => __( 'All Service Tags' ),
      'parent_item'                  => null,
      'parent_item_colon'            => null,
      'edit_item'                    => __( 'Edit Service Tag' ),
      'update_item'                  => __( 'Update Service Tag' ),
      'add_new_item'                 => __( 'Add New Service Tag' ),
      'new_item_name'                => __( 'New Service Tag' ),
      'separate_items_with_commas'   => __( 'Separate Service Tags with commas' ),
      'add_or_remove_items'          => __( 'Add or remove Service Tag' ),
      'choose_from_most_used'        => __( 'Choose from the most used Service Tags' ),
      'not_found'                    => __( 'No Service found.' ),
      'menu_name'                    => __( 'Tags' )
    );

    $args = array(
      'hierarchical'            => false,
      'labels'                  => $labels,
      'show_ui'                 => true,
      'show_admin_column'       => true,
      'update_count_callback'   => '_update_post_term_count',
      'query_var'               => true,
      'rewrite'                 => array( 'slug' => 'service-tag' )
    );

    register_taxonomy( 'll_service_tag', 'll_service', $args ); // Must include custom post type name

  }

  add_action( 'init', 'register_service_taxonomies', 0 );

}

/**
 * Create ACF setting page under CPT menu
 */

// if ( function_exists( 'acf_add_options_sub_page' ) ){
//   acf_add_options_sub_page(array(
//     'page_title' => 'Service Settings',
//     'menu_title' => 'Settings',
//     'menu_slug'  => 'll-service-settings',
//     'parent'     => 'edit.php?post_type=ll_service',
//     'capability' => 'edit_posts'
//   ));
// }
