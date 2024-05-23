<?php
/**
* Hero Banner
* -----------------------------------------------------------------------------
*
* Hero Banner component
*/

$defaults = [
  'hdg' => array(
    'tag'  => 'h2',
    'text' => null
  ),
  'image_id' => null,
  'image_focus' => null,
];

$component_data      = ll_parse_args( $component_data, $defaults );
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
$logo  = get_field( 'global_footer_logo', 'option' );
?>

<?php if ( ll_empty( $component_data ) ) return; ?>
<section class="hero-banner relative overflow-hidden <?php echo implode( " ", $classes ); ?>" <?php echo ( $component_id ? 'id="'.$component_id.'"' : '' ) ?> data-component="hero-banner">
  <?php
    if ( isset( $component_data['loop_video_url'] ) ) {
      ll_include_component(
        'loop-video',
        array(
          'video' => $component_data['loop_video_url']
        )
      );
    } else {
      ll_include_component(
        'fit-image',
        array(
          'image_id' => $component_data['image_id'],
          'thumbnail_size' => 'full',
          'position' => $component_data['image_focus'],
        )
      );
    }
  ?>

  <div class="alignment-container flex justify-end items-end relative z-10">
    <div class="container relative text-white">
      <div class="row items-end">
        <div class="col w-3/12 lg:w-3/12  py-2 sm:py-2 lg:py-2 px-6 sm:px-2 lg:px-6 blogwrapper-hero flex justify-end">
          <img class="logo max-w-64" src="<?php echo $logo['url']; ?>" alt="<?php bloginfo('name'); ?>">
        </div>
        <div class="col w-9/12 lg:w-9/12 lg:w-9/12 -ml-8 lg: ml-0">
          <p class="hdg-hero text-brand-primary opacity-60 whitespace-no-wrap"><?php echo $component_data['scroll_hdg']; ?></p>
          <div class="title-tag rounded bg-white py-4 pl-4">
            <<?php echo $component_data['hdg']['tag']; ?> class="paragraph-default text-gray-300"><?php echo ll_format_title($component_data['hdg']['text']); ?></<?php echo $component_data['hdg']['tag']; ?>>

            <?php if(get_post_type() == 'post'): ?>
              <h2 class="paragraph-default text-gray-300 font-bold"><?php the_title(); ?></h2>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
