<?php
/**
* Two Column WYSIWYG
* -----------------------------------------------------------------------------
*
* Two Column WYSIWYG component
*/

$defaults = [
  'left_content'      => null,
  'right_content'     => null,
  'background_color'  => null,
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

// if ( $component_data['hdg']['text'] ) {
//   $t = '36';
// }

?>

<?php if ( ll_empty( $component_data ) ) return; ?>
<section style="margin-top: -1px;" class="two-column-wysiwyg relative z-10 <?php echo ll_format_bg( $component_data['background_color'], $t, $b ); ?> <?php echo implode( " ", $classes ); ?>" <?php echo ( $component_id ? 'id="'.$component_id.'"' : '' ) ?> data-component="two-column-wysiwyg">

  <div class="container">

    <div class="row justify-center">

      <div class="col w-full xl:w-10/12">

        <div class="row <?php echo $component_data['hdg']['text'] ? 'mt-16' : ''; ?>">

          <div class="col w-full lg:w-1/2 mb-5 lg:mb-0">

            <div class="relative">

              <?php if ( $component_data['hdg']['text'] ) : ?>

                <<?php echo $component_data['hdg']['tag']; ?> class="hdg-1 large-title opacity-20"><?php echo $component_data['hdg']['text']; ?></<?php echo $component_data['hdg']['tag']; ?>>

              <?php endif; ?>

              <div class="wysiwyg">

                <?php echo $component_data['left_content'] ?>

              </div> <!-- /.wysiwyg -->

            </div> <!-- /.relative -->

          </div> <!-- /.col -->

          <div class="col w-full lg:w-1/2 wysiwyg">

            <?php echo $component_data['right_content'] ?>

          </div> <!-- /.col -->

        </div> <!-- /.row -->

      </div> <!-- /.col -->

    </div> <!-- /.row -->

  </div> <!-- /.container -->

</section> <!-- /.two-column-wysiwyg -->
