<article <?php post_class(); ?>>
  <div class="post">
    <?php get_template_part('templates/partials/header', 'page'); ?>
    <div class="post-content">
      <div class="container">
        <div class="row">
          <div class="col w-full md:w-10/12 m-auto pt-10 pb-12 lg:pt-16 lg:pb-20">
            <div class="wysiwyg">
              <?php the_content(); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</article>
