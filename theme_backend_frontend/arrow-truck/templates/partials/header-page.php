<?php
  $image_id = get_post_thumbnail_id( get_option( 'page_for_posts' ) );
  ll_include_component(
    'hero-banner',
    array(
      'main_text' => array(
        'tag' => 'h1',
        'text' => roots_title()
      ),
      'background_image_id' => null,
    ),
    array(
      'classes' => array( 'bg-black' )
    )
  );
?>
