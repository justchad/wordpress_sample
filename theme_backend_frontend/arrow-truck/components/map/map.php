<?php
/**
* Map
* -----------------------------------------------------------------------------
*
* Map component
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
<section class="map mb-8<?php echo implode( " ", $classes ); ?>" <?php echo ( $component_id ? 'id="'.$component_id.'"' : '' ) ?> data-component="map" data-locations="<?php echo ll_esc_json( json_encode( $component_data['locations'] ) ); ?>">
  <div class="container px-0 md:px-13">
    <?php if ( $component_data['locations'] ) : ?>
      <div class="row mx-0 md:-mx-gutter js-reveal">
        <div class="col px-0 md:px-gutter w-full lg:w-10/12 mx-auto">
          <div class="aspect-16/9 relative">
            <div class="map-box absolute top-0 left-0 w-full h-full" id="map" style="position:absolute!important;"></div>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>
