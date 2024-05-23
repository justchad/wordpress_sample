<!doctype html>
<html class="no-js antialiased" <?php language_attributes(); ?>>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta name="format-detection" content="telephone=no">
  <meta name="robots" content="noindex">

  <?php wp_head(); ?>
</head>
<body class="component-preview" data-component="animations">
  <?php //ll_output_analytics( $beginning_body_scripts ); ?>
  <?php include_once( get_stylesheet_directory() . '/assets/img/symbol-defs.svg' ); ?>
  <?php include_once( get_stylesheet_directory() .'/assets/img/symbol-defs-ui.svg' ); ?>
  <!--[if lt IE 8]>
  <div class="alert alert-warning">
  <?php _e('You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.', 'roots'); ?>
  </div>
  <![endif]-->

  <?php
    do_action('get_header');
    $component = $_GET['component'];
  ?>

  <div class="wrap" role="document">
    <main class="main" role="main">
      <?php get_template_part( 'components/'.$component.'/preview' ); ?>
    </main><!-- /.main -->
  </div><!-- /.wrap -->


  <?php wp_footer(); ?>
</body>
</html>
