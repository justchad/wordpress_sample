<?php
/**
* Content Slider
* -----------------------------------------------------------------------------
*
* Content Slider component
*/

$defaults = [
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
<section class="content-slider <?php echo ll_format_bg( 'bg-white' ) ?> <?php echo implode( " ", $classes ); ?>" <?php echo ( $component_id ? 'id="'.$component_id.'"' : '' ) ?> data-component="content-slider">

  <div class="container">

    <div class="row justify-center">

      <div class="col w-full xl:w-10/12">

        <div class="row">

          <div class="col w-full lg:w-6/12 xl:w-5/12 mb-12 lg:mb-0">

            <div class="wysiwyg">

              <?php echo $component_data['content']; ?>

            </div> <!-- /.wysiwyg -->

            <div class="slider-nav hidden lg:flex items-center justify-start mt-12">

              <button class="previous-slide mr-6" disabled>
                <svg class="icon icon-chevron-left"><use xlink:href="#icon-chevron-left"></use></svg>
              </button>

              <button class="next-slide">
                <svg class="icon icon-chevron-right"><use xlink:href="#icon-chevron-right"></use></svg>
              </button>

            </div> <!-- /.slider-nav -->

          </div> <!-- /.col -->

          <div class="col w-full lg:w-6/12 xl:offset-1">

            <div class="border border-red-50 rounded slides py-12 px-12">

              <?php foreach ( $component_data['slides'] as $key => $slide ) : ?>

                <div class="slide">

                  <div class="icon-wrapper mb-6">

                    <svg class="icon icon-<?php echo $slide['svg_icon']; ?>"><use xlink:href="#icon-<?php echo $slide['svg_icon']; ?>"></use></svg>

                  </div> <!-- /.icon-wrapper -->

                  <div class="wysiwyg">

                    <?php echo $slide['content']; ?>

                  </div> <!-- /.wysiwyg -->

                </div> <!-- /.slide -->

              <?php endforeach; ?>

            </div> <!-- /.border -->

          </div> <!-- /.col -->

        </div> <!-- /.row -->

      </div> <!-- /.col -->

    </div> <!-- /.row -->

  </div> <!-- /.container -->

</section> <!-- /.content-slider -->
