<?php
/*
Template Name: Basic
*/
?>
<?php while (have_posts()) : the_post(); ?>
  <section class="container">
    <div class="row justify-center">
      <div class="col w-full md:w-10/12">
        <div class="wysiwyg">
          <?php the_content(); ?>
        </div>
      </div>
    </div>
  </section>
<?php endwhile; ?>
