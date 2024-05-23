<?php
/*
 * Back end Views
 */
class ArrowSalesRepAdmin {

    private $title      = 'Arrow Employee API';
    private $parent     = 'users.php';
    private $capability = 'manage_options';
    private $slug       = 'arrow-sales-reps';
    private $menu_name;

    function __construct() {
        $this->menu_name = $this->title;
        $this::add_settings_page();
    }

    function add_settings_page() {
        add_submenu_page( $this->parent, $this->title, $this->menu_name, $this->capability, $this->slug, array( __CLASS__, 'render_settings_page' ) );
    }

    static function render_settings_page() {
        $employee               = new ArrowApiEmployee();
        $sales_rep              = get_users( [ 'role' => 'arrow_sales_rep' ] );
        $buyer                  = get_users( [ 'role' => 'arrow_buyer' ] );
        $manager                = get_users( [ 'role' => 'arrow_fandi_manager' ] );
        $admin                  = get_users( [ 'role' => 'administrator' ] );
        $show_customers         = false;
        $show_admins            = false;
        $totals = ( object ) [
            'reps'          => count( $sales_rep ),
            'buyer'         => count( $buyer ),
            'manager'       => count( $manager ),
            'admin'         => count( $admin ),
            'customer'      => 0,
            'sum'           => 0,
            'api'           => 0
        ];
        $totals->api = $employee->getAll()->count();
        $totals->sum = $totals->reps + $totals->buyer + $totals->manager;
        if( $show_customers === true ) {
            $customers = get_users( [ 'role' => 'customer' ] );
            $totals->customer = count( $customers );
        }
        include_once( get_stylesheet_directory() . '/templates/admin/admin-sales-rep.php' );
    }
}

add_action( 'admin_menu', function() {
  new ArrowSalesRepAdmin();
} );
