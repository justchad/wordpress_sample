<?php
/**
 * Register the custom post type
 */
if ( ! function_exists('register_promotion_custom_post_type') ) {

  // Register Custom Post Type
  function register_promotion_custom_post_type() {

    /*
     * Checks if you've setup a custom page to use as
     * the archive for this post type. To do that, use the
     * commented out block at the bottom to register the settings page.
     * This makes it behave like the default page_for_posts so that
     * content can change the slug to be more seo friendly.
     */
    if (class_exists('ACF')) {
      $id = get_field( 'page_for_promotions', 'option' );
    }
    $slug = 'promotion';

    if ( $id ) {
      $slug = ll_get_the_slug( $id );
    }

    $labels = array(
      'name'                => 'Promotions',
      'singular_name'       => 'Promotion',
      'menu_name'           => 'Promotions',
      'parent_item_colon'   => 'Parent Promotion',
      'all_items'           => 'All Promotions',
      'view_item'           => 'View Promotion',
      'add_new_item'        => 'Add New Promotion',
      'add_new'             => 'New Promotion',
      'edit_item'           => 'Edit Promotion',
      'update_item'         => 'Update Promotion',
      'search_items'        => 'Search Promotion',
      'not_found'           => 'No Promotion found',
      'not_found_in_trash'  => 'No Promotion found in Trash',
    );
    $args = array(
      'label'               => 'promotion',
      'description'         => 'Promotion description',
      'labels'              => $labels,
      'supports'            => array( 'title', 'page-attributes', 'thumbnail', 'editor' ),
      // 'taxonomies'          => array( 'category', 'post_tag' ),
      'hierarchical'        => true,
      'public'              => true,
      'show_ui'             => true,
      'show_in_menu'        => true,
      'show_in_nav_menus'   => false,
      'show_in_admin_bar'   => true,
      'show_in_rest'        => true,
      'menu_position'       => 20,
      'menu_icon'           => 'dashicons-tag',
      'can_export'          => true,
      'has_archive'         => false,
      'exclude_from_search' => true,
      'publicly_queryable'  => true,
      'capability_type'     => 'post',
      'rewrite'             => array( 'slug' => $slug )
    );
    register_post_type( 'll_promotion', $args );

  }

  // Hook into the 'init' action
  add_action( 'init', 'register_promotion_custom_post_type', 0 );

}

/**
 * Custom taxonomies
 */
// if ( ! function_exists('register_promotion_taxonomies') ) {

//   function register_promotion_taxonomies() {

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
//       'rewrite'             => array( 'slug' => 'promotion-category' )
//     );

//     register_taxonomy( 'll_promotion_category', array( 'll_promotion' ), $args ); // Must include custom post type name

//     // Add new taxonomy, NOT hierarchical (like tags)
//     $labels = array(
//       'name'                         => _x( 'Tags', 'taxonomy general name' ),
//       'singular_name'                => _x( 'Tag', 'taxonomy singular name' ),
//       'search_items'                 => __( 'Search Promotion Tags' ),
//       'popular_items'                => __( 'Popular Promotion Tags' ),
//       'all_items'                    => __( 'All Promotion Tags' ),
//       'parent_item'                  => null,
//       'parent_item_colon'            => null,
//       'edit_item'                    => __( 'Edit Promotion Tag' ),
//       'update_item'                  => __( 'Update Promotion Tag' ),
//       'add_new_item'                 => __( 'Add New Promotion Tag' ),
//       'new_item_name'                => __( 'New Promotion Tag' ),
//       'separate_items_with_commas'   => __( 'Separate Promotion Tags with commas' ),
//       'add_or_remove_items'          => __( 'Add or remove Promotion Tag' ),
//       'choose_from_most_used'        => __( 'Choose from the most used Promotion Tags' ),
//       'not_found'                    => __( 'No Promotion found.' ),
//       'menu_name'                    => __( 'Tags' )
//     );

//     $args = array(
//       'hierarchical'            => false,
//       'labels'                  => $labels,
//       'show_ui'                 => true,
//       'show_admin_column'       => true,
//       'update_count_callback'   => '_update_post_term_count',
//       'query_var'               => true,
//       'rewrite'                 => array( 'slug' => 'promotion-tag' )
//     );

//     register_taxonomy( 'll_promotion_tag', 'll_promotion', $args ); // Must include custom post type name

//   }

//   add_action( 'init', 'register_promotion_taxonomies', 0 );

// }

/**
 * Create ACF setting page under CPT menu
 */

// if ( function_exists( 'acf_add_options_sub_page' ) ){
//   acf_add_options_sub_page(array(
//     'page_title' => 'Promotion Settings',
//     'menu_title' => 'Settings',
//     'menu_slug'  => 'll-promotion-settings',
//     'parent'     => 'edit.php?post_type=ll_service',
//     'capability' => 'edit_posts'
//   ));
// }
