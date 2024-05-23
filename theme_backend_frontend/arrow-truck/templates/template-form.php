<?php
/*
Template Name: Form
*/
?>
<?php while (have_posts()) : the_post(); ?>
  <?php
    $truck = false;
    if ( isset( $_GET['truck'] ) ) {
      $arrow_inv_api = new ArrowApiInventory;
      $truck = $arrow_inv_api->getTruck( $_GET['truck'] )->all();
      $truck = new ArrowTruck( $truck );
    }
  ?>
  <?php

    $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

    $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

      if(get_field('form_page_require_login')){
          if(!is_user_logged_in()) {
              $redirect_url = '/login/?redirect_to=' . urlencode($url) . '&reauth=1';
              echo "<script>location.href = '$redirect_url';</script>";
          }
      }
  ?>
  <?php if ( $truck ) : ?>
    <div class="image-half-color-header">

      <div class="container">
        <a class="relative back-btn mt-6" href="<?php echo $truck->link; ?>">
          <span class="sr-only">Back</span>
          <svg class="icon icon-left-arrow" aria-hidden="true"><use xlink:href="#icon-left-arrow"></use></svg>
        </a>

        <div class="image-wrapper relative aspect-square">
          <?php
            ll_include_component(
              'fit-image',
              [
                'image_id'       => $truck->images->first()
              ]
            );
          ?>
        </div>
      </div>
    </div>
    <p class="hdg-4 mb-3 text-center"><?php echo $truck->name; ?></p>
    <p class="text-brand-primary text-base font-semibold text-center"><?php echo $truck->INVPRICE; ?></p>
  <?php endif; ?>
  <section class="container <?php echo $truck ? 'has-truck' : '' ?>">
    <div class="row justify-center">
      <div class="col w-full md:w-10/12 pt-16 pb-24">
        <div class="text-center">
          <?php if ( get_field('form_page_icon') ) : ?>
            <?php echo wp_get_attachment_image(get_field('form_page_icon'), 'full', '', ['class' => 'h-13 w-13 mx-auto mb-10']); ?>
          <?php endif; ?>
          <h1 class="hdg-5"><?php echo get_the_title(); ?></h1>
        </div>
        <?php echo gravity_form( get_field('form_page_form_id'), false, false, false, '', true ); ?>
      </div>
    </div>
  </section>
<?php endwhile; ?>

<script type="text/javascript">


    // $(document).ready(function() {
    //     jQuery(document).on('gform_post_render', function(e){
    //
    //         console.log('Post Render Event: ', e);
    //
    //         $('#gform_7').submit(function( event ) {
    //           console.log( "Handler for .submit() called." );
    //           event.preventDefault();
    //         });
    //
    //     });
    //
    //
    // });


</script>
