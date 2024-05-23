<?php include(locate_template( 'templates/emails/email-header.php' )); ?>

  <h2>Hello, <?php echo $name; ?></h2>
  <p>Thanks for creating an account on <a href="<?php echo $site_link; ?>">Arrow Trucks</a></p>

  <p style="text-align:center;">
    <a href="<?php echo $link; ?>" class="btn btn-primary">Shop Trucks</a>
  </p>

<?php include(locate_template( 'templates/emails/email-footer.php' )); ?>