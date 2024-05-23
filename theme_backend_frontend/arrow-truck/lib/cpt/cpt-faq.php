<?php
/**
 * Register the custom post type
 */
if ( ! function_exists('register_faq_custom_post_type') ) {

  // Register Custom Post Type
  function register_faq_custom_post_type() {

    /*
     * Checks if you've setup a custom page to use as
     * the archive for this post type. To do that, use the
     * commented out block at the bottom to register the settings page.
     * This makes it behave like the default page_for_posts so that
     * content can change the slug to be more seo friendly.
     */
    if (class_exists('ACF')) {
      $id = get_field( 'page_for_faqs', 'option' );
    }
    $slug = 'faq';

    if ( $id ) {
      $slug = ll_get_the_slug( $id );
    }

    $labels = array(
      'name'                => 'FAQs',
      'singular_name'       => 'FAQ',
      'menu_name'           => 'FAQs',
      'parent_item_colon'   => 'Parent FAQ',
      'all_items'           => 'All FAQs',
      'view_item'           => 'View FAQ',
      'add_new_item'        => 'Add New FAQ',
      'add_new'             => 'New FAQ',
      'edit_item'           => 'Edit FAQ',
      'update_item'         => 'Update FAQ',
      'search_items'        => 'Search FAQ',
      'not_found'           => 'No FAQ found',
      'not_found_in_trash'  => 'No FAQ found in Trash',
    );
    $args = array(
      'label'               => 'faq',
      'description'         => 'FAQ description',
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
      'menu_icon'           => 'dashicons-tag',
      'can_export'          => true,
      'has_archive'         => true,
      'exclude_from_search' => true,
      'publicly_queryable'  => true,
      'capability_type'     => 'post',
      'rewrite'             => array( 'slug' => $slug )
    );
    register_post_type( 'll_faq', $args );

  }

  // Hook into the 'init' action
  add_action( 'init', 'register_faq_custom_post_type', 0 );

}

/**
 * Custom taxonomies
 */
// if ( ! function_exists('register_faq_taxonomies') ) {

//   function register_faq_taxonomies() {

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
//       'rewrite'             => array( 'slug' => 'faq-category' )
//     );

//     register_taxonomy( 'll_faq_category', array( 'll_faq' ), $args ); // Must include custom post type name

//     // Add new taxonomy, NOT hierarchical (like tags)
//     $labels = array(
//       'name'                         => _x( 'Tags', 'taxonomy general name' ),
//       'singular_name'                => _x( 'Tag', 'taxonomy singular name' ),
//       'search_items'                 => __( 'Search FAQ Tags' ),
//       'popular_items'                => __( 'Popular FAQ Tags' ),
//       'all_items'                    => __( 'All FAQ Tags' ),
//       'parent_item'                  => null,
//       'parent_item_colon'            => null,
//       'edit_item'                    => __( 'Edit FAQ Tag' ),
//       'update_item'                  => __( 'Update FAQ Tag' ),
//       'add_new_item'                 => __( 'Add New FAQ Tag' ),
//       'new_item_name'                => __( 'New FAQ Tag' ),
//       'separate_items_with_commas'   => __( 'Separate FAQ Tags with commas' ),
//       'add_or_remove_items'          => __( 'Add or remove FAQ Tag' ),
//       'choose_from_most_used'        => __( 'Choose from the most used FAQ Tags' ),
//       'not_found'                    => __( 'No FAQ found.' ),
//       'menu_name'                    => __( 'Tags' )
//     );

//     $args = array(
//       'hierarchical'            => false,
//       'labels'                  => $labels,
//       'show_ui'                 => true,
//       'show_admin_column'       => true,
//       'update_count_callback'   => '_update_post_term_count',
//       'query_var'               => true,
//       'rewrite'                 => array( 'slug' => 'faq-tag' )
//     );

//     register_taxonomy( 'll_faq_tag', 'll_faq', $args ); // Must include custom post type name

//   }

//   add_action( 'init', 'register_faq_taxonomies', 0 );

// }

/**
 * Create ACF setting page under CPT menu
 */

// if ( function_exists( 'acf_add_options_sub_page' ) ){
//   acf_add_options_sub_page(array(
//     'page_title' => 'FAQ Settings',
//     'menu_title' => 'Settings',
//     'menu_slug'  => 'll-faq-settings',
//     'parent'     => 'edit.php?post_type=ll_service',
//     'capability' => 'edit_posts'
//   ));
// }
