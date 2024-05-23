<?php
/**
* Content Box
* -----------------------------------------------------------------------------
*
* Content Box component
*/

$defaults = [
  'image'               => null,
  'image_position'      => null,
  'content'             => null,
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
$background_color = ( isset( $component_data['background_color'] ) ? $component_data['background_color'] : '' );
?>

<?php if ( ll_empty( $component_data ) ) return; ?>
<section class="content-box overflow-hidden <?php echo ll_format_bg( 'bg-white', '32', '32' ) ?> <?php echo ll_format_bg( $background_color ) ?> <?php echo implode( " ", $classes ); ?>" <?php echo ( $component_id ? 'id="'.$component_id.'"' : '' ) ?> data-component="content-box">

  <div class="container">

    <div class="row lg:flex-no-wrap">

      <div class="col w-11/12 lg:w-7/12 xl:w-6/12 xl:offset-1">

        <div class="image-wrapper relative aspect-square">

          <?php
            if ( isset( $component_data['image'] ) ) {
              ll_include_component(
                'fit-image',
                [
                  'image_id'            => $component_data['image'],
                  'thumbnail_size'      => 'full',
                  'position'            => $component_data['image_position']
                ]
              );
            }
          ?>

        </div> <!-- /.image-wrapper -->

      </div> <!-- /.col -->

      <div class="col w-11/12 content-column">

        <div class="bg-brand-dark-gray text-white py-12 px-12 wysiwyg">

          <?php echo $component_data['content']; ?>

        </div> <!-- /.bg-brand-dark-gray -->

      </div> <!-- /.col -->

    </div> <!-- /.row -->

  </div> <!-- /.container -->

</section> <!-- /.content-box -->
