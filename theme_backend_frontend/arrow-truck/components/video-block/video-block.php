<?php
    /**
    * Video Block
    * -----------------------------------------------------------------------------
    *
    * Video Block component
    */

    $defaults = [];

    $component_data = ll_parse_args( $component_data, $defaults );
?>

<?php

    $classes = ( isset( $component_args['classes'] ) ? $component_args['classes'] : array() );

if ( ! isset( $component_data['content'] ) ) {
  $video_classes = 'col w-full lg:w-10/12 xl:offset-1';
}

?>

<?php if ( ll_empty( $component_data ) ) return; ?>
<section class="video-block <?php echo ll_format_bg( $component_data['background_color'] ) ?> <?php echo implode( " ", $classes ); ?>" <?php echo ( ( isset( $component_id) ) ? 'id="'.$component_id.'"' : '' ) ?> data-component="video-block">

  <div class="container px-0 lg:px-7 overflow-x-hidden">

    <div class="row items-center">


    <?php if ( isset( $video_classes ) ) : ?>
      <div class="<?php echo $video_classes; ?>">
        <?php if ( isset( $component_data['video_url'] ) ) : ?>
          <a href="<?php echo $component_data['video_url'] ?>" class="image-wrapper block js-init-video relative aspect-2/3 mb-8 lg:mb-0" title="Watch popup video - <?php echo $component_data['video_title'] ?>">

            <?php
              ll_include_component(
                'fit-image',
                [
                  'image_id'            => $component_data['thumbnail'],
                  'thumbnail_size'      => 'full',
                  'position'            => $component_data['thumbnail_position']
                ]
              );
            ?>

            <span class="popup-circle">

              <svg class="icon icon-play"><use xlink:href="#icon-play"></use></svg>

            </span> <!-- /.popup-circle -->

          </a> <!-- /.image-wrapper -->
        <?php endif; ?>
      </div> <!-- /.col -->
      <?php endif; ?>
      <?php if ( isset( $component_data['content'] ) ) : ?>
        <div class="col w-full lg:w-4/12 xl:w-3/12 wysiwyg">

          <?php echo $component_data['content']; ?>

        </div> <!-- /.col -->
      <?php endif; ?>

    </div> <!-- /.row -->

  </div> <!-- /.container -->

</section> <!-- /.video-block -->
