<?php
/**
* One Column WYSIWYG
* -----------------------------------------------------------------------------
*
* One Column WYSIWYG component
*/

$defaults = [
  'hdg'                     => [],
  'content'                 => null,
  'narrow'                  => null,
  'background_color'        => null,
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

$t = '24';
$b = '24';

if ( isset( $component_data['inside_component'] ) ) {
  $t = '0';
  $b = '16';
}
?>

<?php if ( ll_empty( $component_data ) ) return; ?>
<section class="one-column-wysiwyg <?php echo ll_format_bg( $component_data['background_color'], $t, $b ); ?> <?php echo implode( " ", $classes ); ?>" <?php echo ( $component_id ? 'id="'.$component_id.'"' : '' ) ?> data-component="one-column-wysiwyg">

  <div class="container">

    <div class="row justify-center">

      <div class="col w-full <?php echo $component_data['narrow'] ? 'md:w-10/12 lg:w-8/12 xl:w-6/12 text-center' : 'xl:w-10/12'; ?>">

        <?php if ( isset( $component_data['hdg']['text'] ) ) : ?>

          <<?php echo $component_data['hdg']['tag']; ?> class="hdg-hero text-white opacity-20"><?php echo $component_data['hdg']['text']; ?></<?php echo $component_data['hdg']['tag']; ?>>

        <?php endif; ?>

        <div class="wysiwyg">

          <?php echo $component_data['content']; ?>

        </div> <!-- /.wysiwyg -->

      </div> <!-- /.col -->

    </div> <!-- /.row -->

  </div> <!-- /.container -->

</section> <!-- /.one-column-wysiwyg -->
