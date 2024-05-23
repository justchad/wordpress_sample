<?php
/**
* Photo Divider
* -----------------------------------------------------------------------------
*
* Photo Divider component
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
<section class="photo-divider relative overflow-hidden <?php echo implode( " ", $classes ); ?>" <?php echo ( $component_id ? 'id="'.$component_id.'"' : '' ) ?> data-component="photo-divider">

  <?php
    ll_include_component(
      'fit-image',
      [
        'image_id'            => $component_data['image'],
        'thumbnail_size'      => 'full'
      ]
    );
  ?>

</section> <!-- /.photo-divider -->
