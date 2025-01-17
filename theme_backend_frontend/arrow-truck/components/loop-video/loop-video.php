<?php
/**
* Loop Video
* -----------------------------------------------------------------------------
*
* Loop Video component
*/

$defaults = [
  'video' => null,
  'fallback' => null
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

if ( $component_data['fallback'] ) {
 $style = 'style="background-image: url( '.$component_data['fallback'].' );"';
} else {
 $style = '';
}

?>

<?php if ( ll_empty( $component_data ) ) return; ?>
<div class="loop-video-container <?php echo implode( " ", $classes ); ?>" <?php echo ( $component_id ? 'id="'.$component_id.'"' : '' ) ?> data-component="loop-video" <?php echo $style; ?>>
  <video class="loop-video" muted autoplay loop playsinline poster="<?php echo $component_data['fallback']; ?>">
    <source src="<?php echo $component_data['video']; ?>">
  </video>
</div>
