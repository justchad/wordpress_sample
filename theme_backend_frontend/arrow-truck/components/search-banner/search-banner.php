<?php
/**
* Search Banner
* -----------------------------------------------------------------------------
*
* Search Banner component
*/
global $wp;

$defaults = [
  'hdg'            => [],
  'image'          => null,
  'image_focus'    => null,
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
$filters = new ArrowFilters;
?>

<?php if ( ll_empty( $component_data ) ) return; ?>
<section class="search-banner relative <?php echo implode( " ", $classes ); ?>" <?php echo ( $component_id ? 'id="'.$component_id.'"' : '' ) ?> data-component="search-banner">
  <?php
    if ( isset( $component_data['video_url'] ) )  {
      ll_include_component(
        'loop-video',
        [
          'video' => $component_data['video_url']
        ]
      );
    } else {
      ll_include_component(
        'fit-image',
        [
          'image_id'            => $component_data['image'],
          'thumbnail_size'      => 'full',
          'position'            => $component_data['image_focus']
        ]
      );
    }
  ?>

  <div class="alignment-container flex justify-center items-end lg:items-center relative z-10">

    <div class="container px-5 lg:px-7">

      <div class="w-full flex py-12 flex-col">

        <div class="w-full">
          <<?php echo $component_data['hdg']['tag']; ?> class="hdg-2 mb-8 text-white text-center video-header-headline"><?php echo $component_data['hdg']['text']; ?></<?php echo $component_data['hdg']['tag']; ?>>
        </div>

        <div class="w-full flex-col md:flex-row lg:flex-row xl:flex-row justify-center align-center flex video-button-wrapper pt-8">
          <a class="btn my-4 mx-2 md:my-4 mb-4 video-header-button-1" href="/in-house-semi-truck-financing" data-arrow-btn="true"><svg class="mr-3 icon icon-badge" aria-hidden="true"><use xlink:href="#icon-badge"></use></svg>Get Pre Qualified</a>
          <a class="btn my-4 mx-2 md:my-4 mb-4 video-header-button-2" href="/search-inventory" data-arrow-btn="true"><svg class="mr-3 icon icon-semi-truck" aria-hidden="true"><use xlink:href="#icon-semi-truck"></use></svg>Shop All Trucks</a>
        </div>


      </div> <!-- /.col -->


    </div> <!-- /.container -->

  </div> <!-- /.alignment-container -->

</section> <!-- /.search-banner -->
