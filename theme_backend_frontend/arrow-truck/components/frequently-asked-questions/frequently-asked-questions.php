<?php
/**
* Frequently Asked Questions
* -----------------------------------------------------------------------------
*
* Frequently Asked Questions component
*/

$defaults = [
  'chevron'   => false,
  'intro'     => null,
  'questions' => null
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

$args = array(
  'post_type'       => 'll_faq',
  'post__in'        => $component_data['questions'],
  'post_status'     => 'publish',
  'orderby'         => [
    'post__in'      => 'ASC',
    'menu_order'    => 'ASC'
  ],
  'posts_per_page'  => -1,
);

$questions    = get_posts( $args );
$questions = wp_list_pluck( $questions, 'ID' );
?>

<?php if ( ll_empty( $component_data ) ) return; ?>
<section class="faq <?php echo ll_format_bg( 'bg-white' ) ?> <?php echo implode( " ", $classes ); ?>" <?php echo ( $component_id ? 'id="'.$component_id.'"' : '' ) ?> data-component="faq">

  <div class="container">

    <div class="w-full lg:w-10/12 xl:w-8/12 mx-auto">

      <?php if ( $component_data['intro'] ) : ?>

        <div class="js-animate-down faq__title w-full mx-auto mb-8">

          <div class="wysiwyg">

            <?php echo $component_data['intro']; ?>

          </div> <!-- /.wysiwyg -->

        </div> <!-- /.faq__title -->

      <?php endif; ?>

      <div class="faq__items w-full mx-auto js-animate-down">

        <?php foreach ( $questions as $key => $question ) : ?>

          <div class="faq__item py-4 px-2">

            <div class="faq__item-content">

              <div class="faq__item-title flex flex-wrap justify-between items-center text-gray-400">

                <h3 class="item-title mb-0">
                  <button class="mb-0 accordion-trigger font-bold text-gray-400" aria-expanded="false" aria-controls="<?php echo $component_id . '-' . $key;?>-content" id="label-<?php echo $component_id . '-' . $key;?>" aria-disabled="false">
                    <?php echo get_the_title( $question ); ?>
                  </button>
                </h3> <!-- /.font-medium -->

                <?php if ( $component_data['chevron'] ) : ?>
                  <svg class="icon icon-chevron-down text-4xl"><use xlink:href="#icon-chevron-down"></use></svg>
                <?php else : ?>
                  <svg class="icon icon-plus text-brand-primary"><use xlink:href="#icon-plus"></use></svg>
                  <svg class="icon icon-minus text-brand-primary hidden"><use xlink:href="#icon-minus"></use></svg>
                <?php endif; ?>

              </div> <!-- /.faq__item-title -->

              <div class="faq__item-answer pb-2 hidden mt-4" id="<?php echo $component_id . '-' . $key;?>-content" role="region" aria-labelledby="<?php echo get_the_title( $question ); ?> - Content">

                <div class="flex">

                  <div class="flex-fill sm:pr-4">

                    <div class="wysiwyg lg:pl-8">

                      <?php echo get_the_content('', '', $question); ?>

                    </div> <!-- /.wysiwyg -->

                  </div> <!-- /.flex-fill -->

                </div> <!-- /.flex -->

              </div> <!-- /.text-gray -->

            </div> <!-- /.faq__item-content -->

          </div> <!-- /.faq__item -->

        <?php endforeach; ?>

      </div> <!-- /.faq__items -->

    </div> <!-- /.shadow -->

  </div> <!-- /.container -->

</section>
