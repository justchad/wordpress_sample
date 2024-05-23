<?php
  /*
   * Right now, preview.php is hardcoded. While it does cause extra work in the sense
   * that you are creating two files for 1 component, this gives you the ability to
   * put any variations of the component on one preview.
   *
   */
?>
<section class="hero-banner relative h-screen flex justify-center items-center" id="hero-banner-preview" data-component="hero-banner">
  <?php
    ll_include_component(
      'fit-image',
      array(
        'image_id' => 69,
        'thumbnail_size' => 'full',
        'position' => 'object-center'
      )
    );
  ?>

    <div class="container relative text-white">
      <h2 class="hdg-2 js-fade">Just Some Text</h2>
    </div>
</section>
