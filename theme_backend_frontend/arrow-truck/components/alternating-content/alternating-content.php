<?php
/**
* Alternating Content
* -----------------------------------------------------------------------------
*
* Alternating Content component
*/

$defaults = [
  'style'              => null,
  'image'              => [],
  'images'             => [],
  'definition'         => null,
  'content'            => null,
  'links'              => [],
  'layout'             => null,
  'background_color'   => null,
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
<section class="alternating-content <?php echo ll_format_bg( $component_data['background_color'] ); ?> <?php echo implode( " ", $classes ); ?>" <?php echo ( $component_id ? 'id="'.$component_id.'"' : '' ) ?> data-component="alternating-content">

  <div class="container">

    <div class="row justify-center">

      <div class="col w-full xl:w-10/12">

        <div class="row justify-between <?php echo ( $component_data['style'] == 'link-list' && ! isset( $component_data['images']['left_image_id'] ) ) ? 'items-start' : 'items-center' ; ?>">

          <div class="col w-full lg:w-1/2 <?php echo ( $component_data['style'] == 'link-list' && $component_data['layout'] == 'image-content' ) ? 'xl:w-5/12 xl:offset-1' : ''; ?> <?php echo $component_data['style'] == 'link-list' && $component_data['layout'] !== 'image-content' ? 'xl:w-5/12' : ''; ?> order-2 <?php echo $component_data['layout'] == 'image-content' ? '' : 'lg:order-1'; ?>">

            <?php if ( $component_data['style'] == 'definition' ) : ?>

              <div class="border border-red-50 rounded py-12 px-8">

                <div class="flex items-center justify-start">

                  <div class="flex-initial icon-wrapper mr-4">

                    <svg class="icon icon-search"><use xlink:href="#icon-search"></use></svg>

                  </div> <!-- /.flex-initial -->

                  <div class="flex-initial">

                    <h2 class="hdg-4 mb-0 leading-none"><?php echo $component_data['definition']['title']; ?></h2>
                    <p class="text-gray-300"><?php echo $component_data['definition']['pronunciation']; ?></p>

                  </div> <!-- /.flex-initial -->

                </div> <!-- /.flex -->

                <div class="wysiwyg mt-6">

                  <?php echo $component_data['content']; ?>

                </div> <!-- /.wysiwyg -->

              </div> <!-- /.border -->

            <?php elseif ( $component_data['style'] == 'link-list' ) : ?>

              <?php if ( isset( $component_data['images']['left_image_id'] ) ) : ?>
                <div class="wysiwyg">

                  <?php echo $component_data['content']; ?>

                </div> <!-- /.wysiwyg -->
              <?php endif; ?>

              <ul class="mt-8">

                <?php foreach ( $component_data['links'] as $key => $link ) : ?>

                <li class="border-b border-gray-200">

                  <a href="<?php echo $link['link']['url']; ?>" class="flex items-start justify-between py-4 hover:text-brand-primary" target="<?php echo $link['link']['target']; ?>">
                    <span class="flex-initial flex items-start justify-start pr-8 font-semibold">
                      <svg class="icon icon-<?php echo $link['svg_icon']; ?> text-brand-primary w-7 h-7 mr-4 svg-align"><use xlink:href="#icon-<?php echo $link['svg_icon']; ?>"></use></svg>

                      <?php echo $link['link']['title']; ?>
                    </span>

                    <svg class="icon icon-right-arrow svg-align text-3xl flex-0"><use xlink:href="#icon-right-arrow"></use></svg>
                  </a>

                </li>

                <?php endforeach; ?>

              </ul> <!-- /.mt-8 -->

            <?php else : ?>

              <div class="wysiwyg">

                <?php echo $component_data['content']; ?>

              </div> <!-- /.wysiwyg -->

            <?php endif; ?>

          </div> <!-- /.col -->

          <div class="col w-full <?php echo ( $component_data['style'] == 'link-list' && ! isset( $component_data['images']['left_image_id'] ) ) ? 'lg:w-5/12 two-col' : 'lg:w-1/2'; ?> order-1 <?php echo $component_data['style'] == 'default' ? 'mb-8' : ' mb-24'; ?> lg:mb-0 <?php echo $component_data['layout'] == 'image-content' ? '' : 'lg:order-2'; ?> <?php echo $component_data['style'] == 'link-list' && $component_data['layout'] !== 'image-content' ? 'xl:offset-1' : ''; ?>">

            <?php if ( $component_data['style'] == 'definition' || $component_data['style'] == 'link-list' ) : ?>
              <?php if ( $component_data['style'] == 'link-list' && ! isset( $component_data['images']['left_image_id'] ) ) : ?>
                <div class="wysiwyg">
                  <?php echo $component_data['content']; ?>
                </div>
              <?php else : ?>

                <div class="row items-start">
                  <div class="col w-7/12 <?php echo $component_data['style'] == 'definition' ? 'order-1' : 'order-2'; ?>">
                    <div class="image-wrapper image-1">

                        <?php
                            ll_include_component(
                                'fit-image',
                                [
                                    'image_id'            => ( isset( $component_data['images']['left_image_id'] ) ) ? $component_data['images']['left_image_id'] : null,
                                    'thumbnail_size'      => 'full',
                                    'position'            => ( isset( $component_data['images']['left_image_focus_point'] ) ) ? $component_data['images']['left_image_focus_point'] : null,
                                ]
                            );
                        ?>

                    </div> <!-- /.image-wrapper -->

                  </div> <!-- /.col -->

                  <div class="col w-5/12 <?php echo $component_data['style'] == 'definition' ? 'order-2' : 'order-1'; ?>">

                    <div class="image-wrapper image-2">

                      <?php
                        ll_include_component(
                          'fit-image',
                          [
                            'image_id'            => $component_data['images']['right_image_id'],
                            'thumbnail_size'      => 'full',
                            'position'            => $component_data['images']['right_image_focus_point'],
                          ]
                        );
                      ?>

                    </div> <!-- /.image-wrapper -->

                  </div> <!-- /.col -->

                </div> <!-- /.row -->
              <?php endif; ?>

            <?php elseif ( $component_data['style'] == 'default' ) : ?>

              <div class="relative image-wrapper aspect-square">

                 <?php
                  ll_include_component(
                    'fit-image',
                    [
                      'image_id'            => $component_data['image']['image_id'],
                      'thumbnail_size'      => 'full',
                      'position'            => $component_data['image']['image_focus_point'],
                    ]
                  );
                ?>

              </div> <!-- /.relative -->

            <?php endif; ?>

          </div> <!-- /.col -->

        </div> <!-- /.row -->

      </div> <!-- /.col -->

    </div> <!-- /.row -->

  </div> <!-- /.container -->

</section> <!-- /.alternating-content -->
