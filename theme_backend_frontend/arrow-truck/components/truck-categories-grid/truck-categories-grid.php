<?php
/**
* Truck Categories Grid
* -----------------------------------------------------------------------------
*
* Truck Categories Grid component
*/

$defaults = [
  'hdg'             => [],
  'categories'      => [0, 1, 2, 3, 4, 5],
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
<section class="truck-categories-grid <?php echo ll_format_bg( 'bg-white' ) ?> <?php echo implode( " ", $classes ); ?>" <?php echo ( $component_id ? 'id="'.$component_id.'"' : '' ) ?> data-component="truck-categories-grid">

  <div class="container">

    <div class="row justify-center">

      <div class="col w-full xl:w-10/12">

        <<?php echo $component_data['hdg']['tag']; ?> class="hdg-2 text-lg md:text-3xl lg:text-4xl"><?php echo $component_data['hdg']['text']; ?></<?php echo $component_data['hdg']['tag']; ?>>

        <div class="row flex-slider" data-slides-to-show="sm:1 md:2" data-arrows="false" data-dots="true">

          <?php foreach ( $component_data['categories'] as $key => $category ) : ?>

            <?php if ( ! is_int( $category ) ) : ?>

                <div class="col w-full md:w-1/2 lg:w-1/3 mt-10">

                  <a href="<?php echo $category['link']; ?>" class="relative block overflow-hidden cat-card aspect-square">

                    <?php
                      ll_include_component(
                        'fit-image',
                        [
                          'image_id'            => $category['image_id'],
                          'thumbnail_size'      => 'full',
                          'position'            => $category['image_focus_point']
                        ]
                      );
                    ?>

                    <p><?php echo $category['title']; ?></p>

                  </a> <!-- /.relative -->

                </div> <!-- /.col -->

            <?php endif; ?>

          <?php endforeach; ?>

        </div> <!-- /.row -->

      </div> <!-- /.col -->

    </div> <!-- /.row -->

  </div> <!-- /.container -->

</section> <!-- /.truck-categories-grid -->
