<?php
  global $wp;
?>

<div class="error-page my-24 mx-0 text-center overflow-hidden">

  <div class="container">

    <h1 class="relative text-5xl mb-4 mx-auto">Not Found</h1>

    <div class="error__desc paragraph-large">

      <div class="alert alert-warning">

        <?php _e('Sorry, but the page you were trying to view does not exist.', 'roots'); ?>

      </div>

      <p class="mt-4"><?php _e('It looks like this was the result of either:', 'roots'); ?></p>

      <ul class="m-0 p-0">

        <li><?php _e('a mistyped address', 'roots'); ?></li>

        <li><?php _e('an out-of-date link', 'roots'); ?></li>

      </ul>

    </div>

  </div>

</div>
