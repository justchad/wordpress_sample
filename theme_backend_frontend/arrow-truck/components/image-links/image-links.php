<?php
/**
* Image Links
* -----------------------------------------------------------------------------
*
* Image Links component
*/

$defaults = [
  'links'     => []
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
<section class="image-links <?php echo ll_format_bg( 'bg-white' ) ?> <?php echo implode( " ", $classes ); ?>" <?php echo ( $component_id ? 'id="'.$component_id.'"' : '' ) ?> data-component="image-links">

  <div class="container">

    <div class="row justify-center">

      <div class="col w-full xl:w-10/12">

        <div class="row items-stretch">

          <?php foreach ( $component_data['links'] as$key => $link ) : ?>

            <a href="<?php echo $link['link']['url']; ?>" target="<?php echo $link['link']['target']; ?>" class="col link-wrapper block w-full lg:w-1/2" title="<?php echo $link['link']['title']; ?> - <?php echo $link['description']; ?>">

              <div class="image-wrapper relative py-30 px-6 text-center w-full overflow-hidden h-full flex justify-center items-center text-white">

                <?php
                  ll_include_component(
                    'fit-image',
                    [
                      'image_id'            => $link['image_id'],
                      'thumbnail_size'      => 'full',
                      'position'            => $link['image_focus_point']
                    ]
                  );
                ?>

                <div class="flex-initial relative z-10">

                  <h2 class="hdg-1 text-white mb-4"><?php echo $link['link']['title']; ?></h2>

                  <p class="text-lg text-white"><?php echo $link['description']; ?></p>

                </div> <!-- /.flex-initial -->

              </div> <!-- /.relative -->

            </a> <!-- /.col -->

          <?php endforeach; ?>

        </div> <!-- /.row -->

      </div> <!-- /.col -->

    </div> <!-- /.row -->

  </div> <!-- /.container -->

</section> <!-- /.image-links -->
