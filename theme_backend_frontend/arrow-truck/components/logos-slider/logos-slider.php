<?php
/**
* Logos Slider
* -----------------------------------------------------------------------------
*
* Logos Slider component
*/

$defaults = [
  'logos'   => []
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
<section class="logos-slider <?php echo ll_format_bg( $component_data['background_color'] ); ?> <?php echo implode( " ", $classes ); ?>" <?php echo ( $component_id ? 'id="'.$component_id.'"' : '' ) ?> data-component="logos-slider">

  <div class="container">

    <div class="row justify-center">

      <div class="col w-full xl:w-10/12">

        <div class="row logos items-center">

          <?php foreach ( $component_data['logos'] as $key => $logo ) : ?>

            <div class="col slick-slider-each-item">

              <a href="<?php echo get_field( 'image_external_link', $logo ); ?>">
                <?php echo wp_get_attachment_image($logo, 'full'); ?>
              </a>

            </div> <!-- /.col -->

          <?php endforeach; ?>

        </div> <!-- /.col -->

      </div> <!-- /.col -->

    </div> <!-- /.row -->

  </div> <!-- /.container -->

</section> <!-- /.logos-slider -->
