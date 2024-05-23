<?php
  $list = false;

  if ( isset( $component_data['list'] ) && $component_data['list'] ) {
    $list = true;
  }

  $all = false;
  if ( isset( $component_data['all_trucks'] ) && $component_data['all_trucks'] ) {
    $all = true;
  }
?>


<div class="relative block overflow-hidden truck-card bg-gray-100 <?php echo $list ? 'p-2 flex justify-start items-start' : ''; ?>">
  <button class="favorite-remove absolute circle-icon is-small top-0 right-0 mr-4 mt-4 z-10" data-truck="<?php echo $truck->INVSTKNO; ?>"><svg class="pointer-events-none icon icon-close" aria-hidden="true"><use xlink:href="#icon-close"></use></svg></button>
  <div class="image-wrapper relative  <?php echo $list ? 'w-1/3 list-image-wrapper rounded overflow-hidden aspect-4/3' : 'aspect-4/3'; ?>">

    <?php
      ll_include_component(
        'fit-image',
        [
          'image_id'        => $truck->images->first(),
          'thumbnail_size'  => 'full',
		  'default_asset_url' => 'https://www.arrowtruckhost.com/images/NoImage2.jpg'
        ]
      );
    ?>

    <?php if ( !$list && $truck->INVSPCD == 'Y' ) : ?>

      <div class="tag-wrapper">

        <span>Featured</span>

      </div> <!-- /.tag-wrapper -->

    <?php endif; ?>

  </div> <!-- /.image-wrapper -->

  <div class="bg-gray-100 <?php echo $list ? 'pl-2 w-2/3' : ' p-4'; ?>">

    <h3 class="truck-title <?php echo $all ? 'text-sm md:text-lg' : 'text-sm'; ?> font-bold text-gray-400 mb-2"><?php echo $truck->name; ?></h3>
    <p class="<?php echo $all ? 'text-sm md:text-base' : 'text-sm'; ?> mb-3 font-medium">
      <span class="text-brand-primary inline-block"><?php echo $truck->INVPRICE; ?></span> <span class="inline-block mx-1"> <?php if ( $truck->INVMILAG > 0 ) : ?>|</span> <span class="text-gray-300 inline-block"><?php echo $truck->INVMILAG; ?> Miles</span><?php endif; ?>
    </p>

    <div class="<?php echo $list ? '' : 'flex justify-between items-center'; ?> text-gray-300 <?php echo $all ? 'text-xs md:text-base' : 'text-xs'; ?>">

      <?php if ( $truck->INVEMAKE ) : ?>
        <p class="<?php echo $list ? 'mb-2' : ''; ?>"><svg class="icon icon-speedometer text-lg mr-1"><use xlink:href="#icon-speedometer"></use></svg> Engine: <?php echo $truck->INVEMAKE; ?></p>
      <?php endif; ?>

      <p><svg class="icon icon-pin svg-align text-lg mr-1"><use xlink:href="#icon-pin"></use></svg> <?php echo $truck->location() ?></p>

    </div> <!-- /.flex -->

    <a class="btn mt-7" href="<?php echo $truck->link ?>">View Details</a>
  </div> <!-- /.bg-gray-100 -->
</div> <!-- /.relative -->
