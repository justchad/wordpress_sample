<?php
/**
* Content Grid
* -----------------------------------------------------------------------------
*
* Content Grid component
*/

$defaults = [
  'columns'   => 3
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

if ( isset( $component_data['hdg']['text'] ) || isset( $component_data['content'] ) ) {
  $intro = true;
}

if ( ! isset( $intro ) ) {
  $t = '10';
  $b = '24';
}
?>

<?php if ( ll_empty( $component_data ) ) return; ?>
<section class="content-grid <?php echo ll_format_bg( $component_data['background_color'], $t, $b ) ?> <?php echo implode( " ", $classes ); ?>" <?php echo ( $component_id ? 'id="'.$component_id.'"' : '' ) ?> data-component="content-grid">

  <?php if ( isset( $intro ) ) :
    ll_include_component(
      'one-column-wysiwyg',
      array(
        'hdg'                     => $component_data['hdg'] ?? null,
        'content'                 => $component_data['content'] ?? null,
        'narrow'                  => $component_data['narrow'] ?? null,
        'background_color'        => $component_data['background_color'] ?? null,
        'inside_component'        => true
      )
    );
  endif; ?>

  <div class="container">

    <div class="row justify-center">

      <div class="col w-full xl:w-10/12">

        <div class="relative <?php echo ( isset( $intro ) ) ? 'border-t border-gray-200' : ''; ?>">

          <div class="row">

            <?php if ( isset($component_data['items']) ) : ?>

                <?php foreach ( $component_data['items'] as $key => $item ) : ?>

                  <div class="col w-full md:w-1/2 lg:w-1/<?php echo $component_data['columns']; ?> mt-16">

                    <?php if ( isset( $component_data['show_numbers'] ) ) : ?>

                      <div class="num-wrapper"><span><?php echo $key + 1; ?></span></div>

                    <?php endif; ?>

                    <div class="wysiwyg">

                      <?php echo $item['content']; ?>

                    </div> <!-- /.wysiwyg -->

                  </div> <!-- /.col -->

                <?php endforeach; ?>

            <?php endif; ?>

          </div> <!-- /.row -->

        </div> <!-- /.relative -->

      </div> <!-- /.col -->

    </div> <!-- /.row -->

  </div> <!-- /.container -->

</section> <!-- /.content-grid -->
