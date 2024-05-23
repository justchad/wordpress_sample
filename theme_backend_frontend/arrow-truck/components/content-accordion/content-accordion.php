<?php
/**
* Content Accordion
* -----------------------------------------------------------------------------
*
* Content Accordion component
*/

$defaults = [
  'intro'     => '',
  'chevron'   => false,
  'items'     => []
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
<section class="content-accordion <?php echo ll_format_bg( 'bg-white' ) ?> <?php echo implode( " ", $classes ); ?>" <?php echo ( $component_id ? 'id="'.$component_id.'"' : '' ) ?> data-component="content-accordion">

  <div class="container">

    <div class="w-full lg:w-10/12 xl:w-8/12 mx-auto">

      <?php if ( $component_data['intro'] ) : ?>

        <div class="js-animate-down content-accordion__title w-full mx-auto mb-8">

          <div class="wysiwyg text-center">

            <?php echo $component_data['intro']; ?>

          </div> <!-- /.wysiwyg -->

        </div> <!-- /.content-accordion__title -->

      <?php endif; ?>

      <div class="content-accordion__items w-full mx-auto js-animate-down">

        <?php foreach ( $component_data['items'] as $key => $item ) : ?>

          <div class="content-accordion__item py-4 px-6">

            <div class="content-accordion__item-content">

              <div class="content-accordion__item-title flex flex-wrap justify-between items-center">

                <h3 class="item-title mb-0">
                  <button class="mb-0 accordion-trigger" aria-expanded="false" aria-controls="<?php echo $component_id . '-' . $key;?>-content" id="label-<?php echo $component_id . '-' . $key;?>" aria-disabled="false">
                    <?php echo $item['title']; ?>
                  </button>
                </h3> <!-- /.font-medium -->

                <?php if ( $component_data['chevron'] ) : ?>
                  <svg class="icon icon-chevron-down text-4xl"><use xlink:href="#icon-chevron-down"></use></svg>
                <?php else : ?>
                  <svg class="icon icon-plus text-brand-primary"><use xlink:href="#icon-plus"></use></svg>
                  <svg class="icon icon-minus text-brand-primary hidden"><use xlink:href="#icon-minus"></use></svg>
                <?php endif; ?>

              </div> <!-- /.content-accordion__item-title -->

              <div class="content-accordion__item-answer pb-2 hidden mt-4" id="<?php echo $component_id . '-' . $key;?>-content" role="region" aria-labelledby="<?php echo $item['title']; ?> - Content">

                <div class="flex">

                  <div class="flex-fill sm:pr-4">

                    <div class="wysiwyg lg:pl-8">

                      <?php echo format_text($item['content']); ?>

                    </div> <!-- /.wysiwyg -->

                    <?php if ( $item['images'] ) : ?>

                      <div class="row images">

                        <?php foreach ( $item['images'] as $image_key => $image ) : ?>
                          <?php $external_link = get_field( 'image_external_link', $image ); ?>
                          <div class="col w-1/2 md:w-1/3 lg:w-1/4 mt-8">
                            <?php if ( $external_link ) : ?>
                              <a href="<?php echo $external_link; ?>" target="_blank" rel="nofollow">
                            <?php endif; ?>

                            <?php echo wp_get_attachment_image($image, 'full'); ?>

                            <?php if ( $external_link ) : ?>
                              </a>
                            <?php endif; ?>

                          </div> <!-- /.col -->

                        <?php endforeach; ?>

                      </div> <!-- /.row -->

                    <?php endif; ?>

                  </div> <!-- /.flex-fill -->

                </div> <!-- /.flex -->

              </div> <!-- /.text-gray -->

            </div> <!-- /.content-accordion__item-content -->

          </div> <!-- /.content-accordion__item -->

        <?php endforeach; ?>

      </div> <!-- /.content-accordion__items -->

    </div> <!-- /.shadow -->

  </div> <!-- /.container -->

</section> <!-- /.content-accordion -->
