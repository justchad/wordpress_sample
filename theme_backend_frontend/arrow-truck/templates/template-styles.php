<?php
/*
Template Name: Styles
*/
?>
<?php while (have_posts()) : the_post(); ?>
  <section class="container">
    <div class="row justify-center">
      <div class="col w-full md:w-10/12">
        <div class="wysiwyg">
          <?php the_content(); ?>
        </div>

        <div class="mb-5">
          <div class="form-skin">
            <div class="gfield">
              <label for="email" class="gfield_label">Email</label>
              <div class="ginput_container">
                <input type="text" placeholder="Email">
              </div>
            </div>
            <div class="gfield validation_error">
              <label for="email" class="gfield_label">Email</label>
              <div class="ginput_container">
                <input type="text" placeholder="Email">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
<?php endwhile; ?>
