<div class="container py-16">

  <div class="row justify-center">

    <div class="col w-full xl:w-10/12">

      <div class="wysiwyg">

        <?php echo get_the_content(); ?>

      </div> <!-- /.wysiwyg -->

      <div class="row">

        <?php while (have_posts()) : the_post(); ?>
          <?php get_template_part('templates/partials/components'); ?>
        <?php endwhile; ?>

      </div> <!-- /.row -->

    </div> <!-- /.col -->

  </div> <!-- /.row -->

</div> <!-- /.container -->