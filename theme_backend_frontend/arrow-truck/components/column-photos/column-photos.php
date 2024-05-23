<?php
/**
* Column Photos
* -----------------------------------------------------------------------------
*
* Column Photos component
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
<section class="column-photos overflow-hidden bg-gray-500 py-20 <?php echo implode( " ", $classes ); ?>" <?php echo ( $component_id ? 'id="'.$component_id.'"' : '' ) ?> data-component="column-photos">

  <div class="row">

    <div class="col w-3/12 offset-1">

      <div class="relative">

        <?php
          ll_include_component(
            'fit-image',
            [
              'image_id'            => $component_data['photos'][0],
              'thumbnail_size'      => 'full'
            ]
          );
        ?>

      </div> <!-- /.relative -->

    </div> <!-- /.col -->

    <div class="col w-4/12">

      <div class="relative">

        <?php
          ll_include_component(
            'fit-image',
            [
              'image_id'            => $component_data['photos'][1],
              'thumbnail_size'      => 'full'
            ]
          );
        ?>

      </div> <!-- /.relative -->

    </div> <!-- /.col -->

    <div class="col w-3/12">

      <div class="relative">

        <?php
          ll_include_component(
            'fit-image',
            [
              'image_id'            => $component_data['photos'][2],
              'thumbnail_size'      => 'full'
            ]
          );
        ?>

      </div> <!-- /.relative -->

    </div> <!-- /.col -->

  </div> <!-- /.row -->

</section> <!-- /.column-photos -->
