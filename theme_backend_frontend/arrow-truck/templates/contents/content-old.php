<article <?php post_class('col w-full sm:w-1/2 md:w-1/4'); ?>>
  <header class="entry-header">
    <h2 class="entry-header-title">
      <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </h2>
  </header>
  <div class="entry-summary">
    <?php the_excerpt(); ?>
  </div>
</article>
