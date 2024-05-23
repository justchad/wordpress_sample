<?php
/**
* Promotions Slider
* -----------------------------------------------------------------------------
*
* Promotions Slider component
*/

$defaults = [
  'large_hdg'               => [],
  'small_hdg'               => [],
  'promotions'              => [],
  'background_color'        => null,
  'promotions_target'       => null,
];

$component_data = ll_parse_args( $component_data, $defaults );
?>

<?php
/**
 * Any additional classes to apply to the main component container.
 *
 * @var array
 * @see args['classes']
 */
$classes = ( isset( $component_args['classes'] ) ? $component_args['classes'] : array() );
/**
 * ID to apply to the main component container.
 *
 * @var array
 * @see args['id']
 */
$component_id   = ( isset( $component_args['id'] ) ? $component_args['id'] : false );

$promoCount = count($component_data['promotions']);



if($promoCount === 1){
    $dynClass = 'md:w-1/2 lg:w-1/3';
    $slidesToShow = 1;
}elseif($promoCount === 2){
    $dynClass = 'md:w-1/2 lg:w-1/3';
    $slidesToShow = 2;
}else{
    $dynClass = 'md:w-1/2 lg:w-1/3';
    $slidesToShow = 3;
}
?>

<?php if ( ll_empty( $component_data ) ) return; ?>
<section id="fp-promotions-slider" class="promotions-slider overflow-x-hidden <?php echo ll_format_bg( $component_data['background_color'] ) ?> <?php echo implode( " ", $classes ); ?>" <?php echo ( $component_id ? 'id="'.$component_id.'"' : '' ) ?> data-component="promotions-slider">

  <div class="container">

    <div class="row justify-center">

      <div class="col w-full xl:w-10/12">

        <div class="text-center">

          <?php if ( $component_data['large_hdg']['text'] ) : ?>

            <<?php echo $component_data['large_hdg']['tag']; ?> class="hdg-hero -mb-8 opacity-20"><?php echo $component_data['large_hdg']['text']; ?></<?php echo $component_data['large_hdg']['tag']; ?>>

          <?php endif; ?>

          <<?php echo $component_data['small_hdg']['tag']; ?> class="hdg-3"><?php echo $component_data['small_hdg']['text']; ?></<?php echo $component_data['small_hdg']['tag']; ?>>

        </div> <!-- /.text-center -->

        <div id="promotions-slider-wrapper" class="row promotions" style="display: block !important;" data-promocount="<?php echo $promoCount; ?>">

          <?php foreach ( $component_data['promotions'] as $key => $promotion ) : ?>

            <div class="col w-full <?php echo $dynClass; ?> mt-16 promotion">

              <a href="<?php echo get_the_permalink($component_data['promotions_target']); ?>">
              <div class="relative overflow-hidden image-wrapper py-6 h-full">

                <?php
                  ll_include_component(
                    'fit-image',
                    [
                      'image_id'            => get_post_thumbnail_id( $promotion ),
                      'thumbnail_size'      => 'full'
                    ]
                  );
                ?>

                <div class="flex items-end justify-between flex-col relative z-10">

                  <div class="tag-wrapper flex-0">

                    <span>Promo</span>

                  </div> <!-- /.tag-wrapper -->

                  <div class="flex-0 text-center w-full px-10 mt-12">

                    <span class="icon-wrapper bg-white"><svg class="icon icon-estimate text-brand-primary"><use xlink:href="#icon-estimate"></use></svg></span>

                    <h3 class="hdg-5 mb-2 text-white"><?php echo get_the_title( $promotion ); ?></h3>

                    <p class="text-sm mb-2 text-white"><?php echo get_field( 'promotion_description', $promotion ); ?></p>

                    <p class="text-xs text-gray-200 mt-1"><?php echo get_field( 'promotion_disclaimer', $promotion ); ?></p>

                  </div> <!-- /.flex-0 -->

                </div> <!-- /.flex -->

              </div> <!-- /.relative -->
              </a>
            </div> <!-- /.col -->

          <?php endforeach; ?>

        </div> <!-- /.row -->

      </div> <!-- /.col -->

    </div> <!-- /.row -->

  </div> <!-- /.container -->

</section> <!-- /.promotions-slider -->
