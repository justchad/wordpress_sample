<?php
/*
Template Name: Account
*/
global $arrow_errors, $arrow_message;
$account_user = new LL_WP_User( wp_get_current_user() );
?>

<?php while (have_posts()) : the_post(); ?>
  <?php
    $view = ll_get_var( $_GET['view'] );
    $template = false;

    if ( $view ) {
      $template = locate_template( 'templates/account/'.sanitize_title( $view ).'.php' );
    }

    if ( !$template ) {
      $template = locate_template( 'templates/account/dashboard.php' );
    }

    include( $template );
  ?>
<?php endwhile; ?>
