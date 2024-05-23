<?php
class Allow_Numeric_Stubs {

  /**
   * Allow_Numeric_Stubs constructor. Registers the plugin's hooks.
   */
  function __construct() {
    // Modify the rewrite rules for pages to swap in numeric slug support instead of paging
    add_filter( 'page_rewrite_rules', array( $this, 'page_rewrite_rules' ) );

    // Filter the result of wp_unique_post_slug() to allow numeric slugs for pages
    add_filter( 'wp_unique_post_slug', array( $this, 'wp_unique_post_slug_allow_numeric_page_slugs' ), 10, 6 );
  }

  /**
   * Remove the rewrite rule that prevents numeric slugs from working (paged content)
   * and replace it with a new rule that allows them to.
   *
   * @param array $rules The existing rewrite rules for pages.
   *
   * @return array The modified rewrite rules for pages.
   */
  function page_rewrite_rules( $rules ) {
    unset( $rules['(.?.+?)(/[0-9]+)?/?$'] );     // Before WordPress 4.4
    unset( $rules['(.?.+?)(?:/([0-9]+))?/?$'] ); // After WordPress 4.4, see https://core.trac.wordpress.org/changeset/34492

    $rules['(.?.+?)?/?$'] = 'index.php?pagename=$matches[1]';

    return $rules;
  }

  /**
   * Undoes the work of wp_unique_post_slug() for pages with a numeric slug,
   * but only if they don't conflict with any existing sibling pages.
   *
   * @since 3.0.0
   *
   * @param string $slug          The slug that wp_unique_post_slug() suggests using.
   * @param int    $post_ID       The post (page) ID that the slug belongs to.
   * @param string $post_status   The status of post (page) that the slug belongs to.
   * @param string $post_type     The post_type of the post that we're currently filtering. Aborts for everything but "page".
   * @param int    $post_parent   Post parent ID.
   * @param string $original_slug The originally requested slug, which may or may not be unique.
   */
  public function wp_unique_post_slug_allow_numeric_page_slugs( $slug, $post_ID, $post_status, $post_type, $post_parent, $original_slug ) {
    global $wpdb;

    // We're only interested in pages with attempted numeric slugs that got changed
    if ( 'll_inventory' != $post_type || ! is_numeric( $original_slug ) || $slug === $original_slug ) {
      return $slug;
    }

    // Was there actually a conflict or was a suffix just added due to the preg_match() call in wp_unique_post_slug() ?
    $post_name_check = $wpdb->get_var( $wpdb->prepare(
      "SELECT post_name FROM $wpdb->posts WHERE post_name = %s AND post_type IN ( %s, 'attachment' ) AND ID != %d AND post_parent = %d LIMIT 1",
      $original_slug, $post_type, $post_ID, $post_parent
    ) );

    // There really is a conflict due to an existing page so keep the modified slug
    if ( $post_name_check ) {
      return $slug;
    }

    // Otherwise give us the slug we wanted
    return $original_slug;
  }
}

$GLOBALS['Allow_Numeric_Stubs'] = new Allow_Numeric_Stubs();
