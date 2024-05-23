<?php
/**
 * Register the custom post type
 */
if ( ! function_exists('register_location_custom_post_type') ) {

  // Register Custom Post Type
  function register_location_custom_post_type() {

    /*
     * Checks if you've setup a custom page to use as
     * the archive for this post type. To do that, use the
     * commented out block at the bottom to register the settings page.
     * This makes it behave like the default page_for_posts so that
     * content can change the slug to be more seo friendly.
     */
    if (class_exists('ACF')) {
      $id = get_field( 'page_for_locations', 'option' );
    }
    $slug = 'location';

    if ( $id ) {
      $slug = ll_get_the_slug( $id );
    }

    $labels = array(
      'name'                => 'Locations',
      'singular_name'       => 'Location',
      'menu_name'           => 'Locations',
      'parent_item_colon'   => 'Parent Location',
      'all_items'           => 'All Locations',
      'view_item'           => 'View Location',
      'add_new_item'        => 'Add New Location',
      'add_new'             => 'New Location',
      'edit_item'           => 'Edit Location',
      'update_item'         => 'Update Location',
      'search_items'        => 'Search Location',
      'not_found'           => 'No Location found',
      'not_found_in_trash'  => 'No Location found in Trash',
    );
    $args = array(
      'label'               => 'location',
      'description'         => 'Location description',
      'labels'              => $labels,
      'supports'            => array( 'title', 'page-attributes', 'thumbnail', 'editor' ),
      // 'taxonomies'          => array( 'category', 'post_tag' ),
      'hierarchical'        => true,
      'public'              => true,
      'show_ui'             => true,
      'show_in_menu'        => true,
      'show_in_nav_menus'   => false,
      'show_in_admin_bar'   => true,
      'menu_position'       => 20,
      'menu_icon'           => 'dashicons-tag',
      'can_export'          => true,
      'has_archive'         => 'locations',
      'exclude_from_search' => true,
      'publicly_queryable'  => true,
      'capability_type'     => 'post',
      'rewrite'             => array( 'slug' => $slug )
    );
    register_post_type( 'll_location', $args );

  }

  // Hook into the 'init' action
  add_action( 'init', 'register_location_custom_post_type', 0 );

}

/**
 * Custom taxonomies
 */
// if ( ! function_exists('register_location_taxonomies') ) {

//   function register_location_taxonomies() {

//     // Add new taxonomy, make it hierarchical (like categories)
//     $labels = array(
//       'name'                => _x( 'Category', 'taxonomy general name' ),
//       'singular_name'       => _x( 'Category', 'taxonomy singular name' ),
//       'search_items'        => __( 'Search Categories' ),
//       'all_items'           => __( 'All Categories' ),
//       'parent_item'         => __( 'Parent Category' ),
//       'parent_item_colon'   => __( 'Parent Category:' ),
//       'edit_item'           => __( 'Edit Category' ),
//       'update_item'         => __( 'Update Category' ),
//       'add_new_item'        => __( 'Add New Category' ),
//       'new_item_name'       => __( 'New Category Name' ),
//       'menu_name'           => __( 'Categories' )
//     );

//     $args = array(
//       'hierarchical'        => true,
//       'labels'              => $labels,
//       'show_ui'             => true,
//       'show_admin_column'   => true,
//       'query_var'           => true,
//       'rewrite'             => array( 'slug' => 'location-category' )
//     );

//     register_taxonomy( 'll_location_category', array( 'll_location' ), $args ); // Must include custom post type name

//     // Add new taxonomy, NOT hierarchical (like tags)
//     $labels = array(
//       'name'                         => _x( 'Tags', 'taxonomy general name' ),
//       'singular_name'                => _x( 'Tag', 'taxonomy singular name' ),
//       'search_items'                 => __( 'Search Location Tags' ),
//       'popular_items'                => __( 'Popular Location Tags' ),
//       'all_items'                    => __( 'All Location Tags' ),
//       'parent_item'                  => null,
//       'parent_item_colon'            => null,
//       'edit_item'                    => __( 'Edit Location Tag' ),
//       'update_item'                  => __( 'Update Location Tag' ),
//       'add_new_item'                 => __( 'Add New Location Tag' ),
//       'new_item_name'                => __( 'New Location Tag' ),
//       'separate_items_with_commas'   => __( 'Separate Location Tags with commas' ),
//       'add_or_remove_items'          => __( 'Add or remove Location Tag' ),
//       'choose_from_most_used'        => __( 'Choose from the most used Location Tags' ),
//       'not_found'                    => __( 'No Location found.' ),
//       'menu_name'                    => __( 'Tags' )
//     );

//     $args = array(
//       'hierarchical'            => false,
//       'labels'                  => $labels,
//       'show_ui'                 => true,
//       'show_admin_column'       => true,
//       'update_count_callback'   => '_update_post_term_count',
//       'query_var'               => true,
//       'rewrite'                 => array( 'slug' => 'location-tag' )
//     );

//     register_taxonomy( 'll_location_tag', 'll_location', $args ); // Must include custom post type name

//   }

//   add_action( 'init', 'register_location_taxonomies', 0 );

// }

/**
 * Create ACF setting page under CPT menu
 */

// if ( function_exists( 'acf_add_options_sub_page' ) ){
//   acf_add_options_sub_page(array(
//     'page_title' => 'Location Settings',
//     'menu_title' => 'Settings',
//     'menu_slug'  => 'll-location-settings',
//     'parent'     => 'edit.php?post_type=ll_service',
//     'capability' => 'edit_posts'
//   ));
// }
