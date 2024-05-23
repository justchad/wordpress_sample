<?php
/**
* Left Right Accordion
* -----------------------------------------------------------------------------
*
* Left Right Accordion component
*/

$defaults = [
  'items'   => [],
  'background_color'    => null
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
?>

<?php if ( ll_empty( $component_data ) ) return; ?>
<section class="left-right-accordion <?php echo ll_format_bg( $component_data['background_color'] ) ?> <?php echo implode( " ", $classes ); ?>" <?php echo ( $component_id ? 'id="'.$component_id.'"' : '' ) ?> data-component="left-right-accordion">

  <div class="container">

    <div class="row justify-center">

      <div class="col w-full xl:w-10/12">

        <div class="row items-center">

          <div class="col w-full lg:w-7/12 mb-8 lg:mb-0">

            <div class="image-area relative">

              <?php foreach ( $component_data['items'] as $key => $item ) : ?>

                <div class="image-wrapper aspect-2/3 <?php echo $key == 0 ? 'is-open' : ''; ?>" data-key="<?php echo $key; ?>" aria-hidden="true">

                  <?php
                    ll_include_component(
                      'fit-image',
                      [
                        'image_id'            => $item['image_image_id'],
                        'thumbnail_size'      => 'full'
                      ]
                    );
                  ?>

                </div> <!-- /.image-wrapper -->

              <?php endforeach; ?>

            </div> <!-- /.image-area -->

          </div> <!-- /.col -->

          <div class="col w-full lg:w-4/12">

            <div class="left-right-accordion__items w-full mx-auto js-animate-down">

              <?php foreach ( $component_data['items'] as $key => $item ) : ?>

                <div class="left-right-accordion__item <?php echo $key == 0 ? 'is-open' : ''; ?> pl-6 mb-6 last:mb-0 border-l-2 text-gray-200">

                  <div class="left-right-accordion__item-content">

                    <div class="left-right-accordion__item-title flex flex-wrap justify-between items-center" data-key="<?php echo $key; ?>">

                      <h2 class="item-title mb-0">
                        <button class="mb-0 accordion-trigger font-bold relative" aria-expanded="false" aria-controls="<?php echo $component_id . '-' . $key;?>-content" id="label-<?php echo $component_id . '-' . $key;?>" aria-disabled="false">
                          <span class="num-wrapper"><span><?php echo str_pad($key + 1, 2, '0', STR_PAD_LEFT); ?></span></span>
                          <span class="title"><?php echo $item['title']; ?></span>
                        </button>
                      </h2> <!-- /.item-title -->

                    </div> <!-- /.left-right-accordion__item-title -->

                    <div class="left-right-accordion__item-answer hidden mt-4" id="<?php echo $component_id . '-' . $key;?>-content" role="region" aria-labelledby="<?php echo $item['title']; ?> - Content">

                      <div class="text-base">

                        <?php echo $item['description']; ?>

                      </div> <!-- /.text-base -->

                      <?php echo wp_get_attachment_image($item['image_image_id'], 'full', '', ['class' => 'sr-only']); ?>

                    </div> <!-- /.text-gray -->

                  </div> <!-- /.left-right-accordion__item-content -->

                </div> <!-- /.left-right-accordion__item -->

              <?php endforeach; ?>

            </div> <!-- /.left-right-accordion__items -->

          </div> <!-- /.col -->

        </div> <!-- /.row -->

      </div> <!-- /.col -->

    </div> <!-- /.row -->

  </div> <!-- /.container -->

</section> <!-- /.left-right-accordion -->
