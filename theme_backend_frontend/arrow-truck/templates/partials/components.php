<?php
  global $post;
  if ( !isset( $post_id ) || !$post_id )
    $post_id = $post->ID;
?>
<?php if ( have_rows( 'components', $post_id ) ) : ?>
  <?php $components = get_field( 'components', $post_id ); ?>
  <?php while( have_rows( 'components', $post_id ) ) : the_row(); ?>
    <?php
      $index              = get_row_index();
      $next_index         = $index; //get_row_index starts at 1 instead of zero
      $previous_index     = $index - 2; //get_row_index starts at 1 instead of zero so to go BACK a component we must go back 2
      $previous_component = isset( $components[$previous_index]['acf_fc_layout'] ) ? $components[$previous_index]['acf_fc_layout'] : false;
      $next_component     = isset( $components[$next_index]['acf_fc_layout'] ) ? $components[$next_index]['acf_fc_layout'] : false;
      // always create an id either from the field target meta, or by an auto
      // generated index. This is so people can create anchor links to any
      // section at any given point in the future.
      $target = sanitize_title( get_sub_field( 'target_name', $post_id ) );
      if ( $target ) {
        $id = $target;
      } else {
        $id = 'component-' . $index;
      }

      $next_target = '';

      if ( isset( $components[$next_index]['target'] ) ) {
        $next_target = sanitize_title( $components[$next_index]['target'] );
        if ( !$next_target ) {
          $next_target = 'component-' . ( $next_index + 1 );
        }
      }
    ?>

    <?php
      switch( get_row_layout() ) {
        case 'hero_banner':
          ll_include_component(
            'hero-banner',
            array(
              'hdg'            => get_sub_field( 'hero_banner_heading', $post_id ),
              'scroll_hdg'     => get_sub_field( 'hero_banner_scroll_heading', $post_id ),
              'image_id'       => get_sub_field( 'hero_banner_image_id', $post_id ),
              'image_focus'    => get_sub_field( 'hero_banner_image_focus_point', $post_id ),
              'loop_video_url' => get_sub_field( 'hero_banner_loop_video_url', $post_id ),
              'next_component' => ( $components[$next_index] ? $next_target : '' )
            ),
            array(
              'id' => $id
            )
          );
          break;

        case 'search_banner':
          ll_include_component(
            'search-banner',
            array(
              'hdg'             => get_sub_field( 'search_banner_heading', $post_id ),
              'image'           => get_sub_field( 'search_banner_background_image_id', $post_id ),
              'image_focus'     => get_sub_field( 'search_banner_background_image_focus_point', $post_id ),
              'video_url'       => get_sub_field( 'search_banner_video_url', $post_id )
            ),
            array(
              'id' => $id
            )
          );
          break;

        case 'map':
          ll_include_component(
            'map',
            array(
              'hdg'       => get_sub_field( 'map_heading', $post_id ),
              'content'   => get_sub_field( 'map_content', $post_id ),
              'locations' => get_sub_field( 'map_locations', $post_id ),
            ),
            array(
              'id' => $id
            )
          );
          break;

        case 'photo_divider':
          ll_include_component(
            'photo-divider',
            array(
              'image' => get_sub_field( 'photo_divider_image_id', $post_id ),
            ),
            array(
              'id' => $id
            )
          );
          break;

        case 'column_photos':
          ll_include_component(
            'column-photos',
            array(
              'photos'       => get_sub_field( 'column_photos_photos', $post_id ),
            ),
            array(
              'id' => $id
            )
          );
          break;

        case 'logos_slider':
          ll_include_component(
            'logos-slider',
            array(
              'logos'                 => get_sub_field( 'logos_slider_logos', $post_id ),
              'background_color'      => get_sub_field( 'logos_slider_background_color', $post_id ),
              'url'                   => get_sub_field( 'logos_slider_url', $post_id ),
            ),
            array(
              'id' => $id
            )
          );
          break;

        case 'one_column_wysiwyg':
          ll_include_component(
            'one-column-wysiwyg',
            array(
              'hdg'                     => get_sub_field( 'one_column_wysiwyg_heading', $post_id ),
              'content'                 => get_sub_field( 'one_column_wysiwyg_content', $post_id ),
              'narrow'                  => get_sub_field( 'one_column_wysiwyg_narrow', $post_id ),
              'background_color'        => get_sub_field( 'one_column_wysiwyg_background_color', $post_id ),
            ),
            array(
              'id' => $id
            )
          );
          break;

        case 'content_grid':
          ll_include_component(
            'content-grid',
            array(
              'hdg'                     => get_sub_field( 'one_column_wysiwyg_heading', $post_id ),
              'content'                 => get_sub_field( 'one_column_wysiwyg_content', $post_id ),
              'narrow'                  => get_sub_field( 'one_column_wysiwyg_narrow', $post_id ),
              'background_color'        => get_sub_field( 'one_column_wysiwyg_background_color', $post_id ),
              'items'                   => get_sub_field( 'content_grid_items', $post_id ),
              'columns'                 => get_sub_field( 'content_grid_columns', $post_id ),
              'show_numbers'            => get_sub_field( 'content_grid_show_numbers', $post_id ),
            ),
            array(
              'id' => $id
            )
          );
          break;

        case 'two_column_wysiwyg':
          ll_include_component(
            'two-column-wysiwyg',
            array(
              'hdg'                     => get_sub_field( 'two_column_wysiwyg_large_heading', $post_id ),
              'left_content'            => get_sub_field( 'two_column_wysiwyg_left_content', $post_id ),
              'right_content'           => get_sub_field( 'two_column_wysiwyg_right_content', $post_id ),
              'background_color'        => get_sub_field( 'two_column_wysiwyg_background_color', $post_id ),
            ),
            array(
              'id' => $id
            )
          );
          break;

        case 'image_links':
          ll_include_component(
            'image-links',
            array(
              'links'            => get_sub_field( 'image_links_links', $post_id ),
            ),
            array(
              'id' => $id
            )
          );
          break;

        case 'content_slider':
          ll_include_component(
            'content-slider',
            array(
              'content'             => get_sub_field( 'content_slider_content', $post_id ),
              'slides'              => get_sub_field( 'content_slider_slides', $post_id ),
            ),
            array(
              'id' => $id
            )
          );
          break;

        case 'alt_content':
          ll_include_component(
            'alternating-content',
            array(
              'style'              => get_sub_field( 'alt_content_style', $post_id ),
              'image'              => get_sub_field( 'alt_content_image', $post_id ),
              'images'             => get_sub_field( 'alt_content_images', $post_id ),
              'definition'         => get_sub_field( 'alt_content_definition', $post_id ),
              'content'            => get_sub_field( 'alt_content_content', $post_id ),
              'links'              => get_sub_field( 'alt_content_links', $post_id ),
              'layout'             => get_sub_field( 'alt_content_layout', $post_id ),
              'background_color'   => get_sub_field( 'alt_content_background_color', $post_id ),
            ),
            array(
              'id' => $id
            )
          );
          break;

        case 'left_right_accordion':
          ll_include_component(
            'left-right-accordion',
            array(
              'items'               => get_sub_field( 'left_right_accordion_items', $post_id ),
              'background_color'    => get_sub_field( 'left_right_accordion_background_color', $post_id ),
            ),
            array(
              'id' => $id
            )
          );
          break;

        case 'video_block':
          ll_include_component(
            'video-block',
            array(
              'thumbnail'           => get_sub_field( 'video_block_thumbnail_image_id', $post_id ),
              'thumbnail_position'  => get_sub_field( 'video_block_thumbnail_image_focus_point', $post_id ),
              'video_url'           => get_sub_field( 'video_block_video_url', $post_id ),
              'video_title'         => get_sub_field( 'video_block_video_title', $post_id ),
              'content'             => get_sub_field( 'video_block_content', $post_id ),
              'background_color'    => get_sub_field( 'video_block_background_color', $post_id ),
            ),
            array(
              'id' => $id
            )
          );
          break;

        case 'content_box':
          ll_include_component(
            'content-box',
            array(
              'image'               => get_sub_field( 'content_box_image_id', $post_id ),
              'image_position'      => get_sub_field( 'content_box_image_focus_point', $post_id ),
              'content'             => get_sub_field( 'content_box_content', $post_id ),
            ),
            array(
              'id' => $id
            )
          );
          break;

        case 'promotions_slider':
          ll_include_component(
            'promotions-slider',
            array(
              'large_hdg'               => get_sub_field( 'promotions_slider_large_heading', $post_id ),
              'small_hdg'               => get_sub_field( 'promotions_slider_small_heading', $post_id ),
              'promotions'              => get_sub_field( 'promotions_slider_promotions', $post_id ),
              'background_color'        => get_sub_field( 'promotions_slider_background_color', $post_id ),
              'promotions_target'       => get_sub_field( 'promotions_target', $post_id ),
            ),
            array(
              'id' => $id
            )
          );
          break;

        case 'frequently_asked_questions':
          ll_include_component(
            'frequently-asked-questions',
            array(
              'intro'         => get_sub_field('frequently_asked_questions_intro'),
              'questions'     => get_sub_field('frequently_asked_questions_questions')
            ),
            array(
              'id' => $id
            )
          );
          break;

        case 'truck_categories_grid':
          ll_include_component(
            'truck-categories-grid',
            array(
              'hdg'             => get_sub_field('truck_categories_grid_heading'),
              'categories'      => get_sub_field('truck_categories_grid_categories')
            ),
            array(
              'id' => $id
            )
          );
          break;

        case 'featured_trucks_grid':
          $trucks = ArrowApiInventory::search([
            'FEATURED' => 'RANDOM'
          ]);

          $num = 6;

          ll_include_component(
            'featured-trucks-grid',
            array(
                'hdg'               => get_sub_field('featured_trucks_grid_heading'),
                'origin'            => null,
                'mobile_slider'     => true,
                'list'              => false,
                'all_trucks'        => false,
                'single'            => false,
                'location'          => null,
                'address'           => null,
                'num'               => $num,
                'view_all'          => null,
                'grid'              => 'default', // default, search, location, rep
                'trucks'            => $trucks->take( $num ),
                'promotion'         => false,
                'promotion_text'    => null,
                'promotion_array'   => []
            ),
            array(
                'id' => $id,
                'classes' => []
            )
          );
          break;

          case 'search_trucks_grid':
            $trucks = ArrowApiInventory::search([
                'FEATURED' => 'RANDOM'
            ]);

            $num = 6;

            ll_include_component(
                'search-trucks-grid',
                array(
                    'hdg'             => get_sub_field('featured_trucks_grid_heading'),
                    'trucks'          => get_sub_field('featured_trucks_grid_trucks'),
                    'mobile_slider'   => true,
                    'list'            => false,
                    'view_all'        => true,
                    'trucks'          => $trucks->take( $num ),
                    'num'             => $num
                ),
                array(
                    'id' => $id
                )
            );
          break;

        case 'promotions_grid':
          ll_include_component(
            'promotions-grid',
            array(
              'small_hdg'               => get_sub_field( 'promotions_grid_small_heading', $post_id ),
              'hdg'                     => get_sub_field( 'promotions_grid_heading', $post_id ),
              'promotions'              => get_sub_field( 'promotions_grid_promotions', $post_id ),
            ),
            array(
              'id' => $id
            )
          );
          break;

        case 'content_accordion':
          ll_include_component(
            'content-accordion',
            array(
              'intro'     => get_sub_field('content_accordion_intro'),
              'items'     => get_sub_field('content_accordion_items')
            ),
            array(
              'id' => $id
            )
          );
          break;

        case 'tabbed_tables':
          ll_include_component(
            'tabbed-tables',
            array(
              'tabs'     => get_sub_field('tabbed_tables_tabs'),
            ),
            array(
              'id' => $id
            )
          );
          break;
      }
    ?>

  <?php endwhile; ?>

<?php endif; ?>
