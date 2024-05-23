<?php get_template_part('templates/partials/header', 'page'); ?>

<?php if (!have_posts()) : ?>
  <div class="alert alert-warning">
    <?php _e('Sorry, no results were found.', 'roots'); ?>
  </div>
  <?php get_search_form(); ?>
<?php endif; ?>

<div class="container">
  <div class="row">
    <?php while (have_posts()) : the_post(); ?>
      <?php get_template_part('templates/contents/content'); ?>
    <?php endwhile; ?>
  </div>
</div>

<?php if ($wp_query->max_num_pages > 1) : ?>
  <nav class="pagination">
    <?php
      echo paginate_links( array(
        'format'  => 'page/%#%',
        'current' => max( 1, get_query_var('paged') ),
        'total'   => $wp_query->max_num_pages,
        'mid_size'        => 5,
        'prev_text'       => __('<svg class="icon icon-chevron-left"><use xlink:href="#icon-chevron-left"></use></svg>'),
        'next_text'       => __('<svg class="icon icon-chevron-right"><use xlink:href="#icon-chevron-right"></use></svg>')
      ) );
    ?>
  </nav>
<?php endif; ?>
