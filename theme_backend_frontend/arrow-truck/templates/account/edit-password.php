<div class="min-h-screen">
  <div class="bg-red-400 py-9 bg-red-400 text-white">
    <div class="container grid lg:grid-cols-12 lg:gap-13">
       <div class="lg:col-span-6 lg:col-start-4 flex items-center justify-center relative">
        <a class="absolute left-0 top-1/2 transform -translate-y-1/2 back-btn" href="<?php echo get_the_permalink() ?>?view=personal-information"><span class="sr-only">Back</span> <svg class="icon icon-left-arrow" aria-hidden="true"><use xlink:href="#icon-left-arrow"></use></svg></a>
        <h1 class=" hdg-6 text-white text-center">
          Edit Password
        </h1>
      </div>
    </div>
  </div>

  <div class="container grid lg:grid-cols-12 lg:gap-x-13">
    <section class="mt-10 lg:col-span-6 lg:col-start-4" id="personal-information">
      <?php if ( $arrow_message ) : ?>
        <p class="mb-10 text-lg font-bold text-gray-400 text-center"><?php echo $arrow_message; ?></p>
      <?php endif; ?>

      <div class="flex justify-between items-center mb-5">
        <h2 class="text-lg font-bold text-gray-400">Password</h2>
      </div>
      <form class="form-skin" action="<?php echo get_the_permalink() ?>?view=edit-password" method="post">
        <div class="mb-4">
          <label class="gfield_label text-sm" for="pwd_1">Password</label>
          <input class="<?php echo ( isset( $arrow_errors['pwd_1'] ) ? 'error-field' : '' ) ?>" value="<?php echo ( $_POST && isset( $_POST['pwd_1'] ) && !$arrow_message ? $_POST['pwd_1'] : '' ); ?>" id="pwd_1" name="pwd_1" type="password" autocomplete="off">

          <?php if ( isset( $arrow_errors['pwd_1'] ) ) : ?>
           <span class="text-sm block mt-1 text-red-100"><?php echo $arrow_errors['pwd_1']; ?></span>
          <?php endif; ?>
        </div>

        <div class="mb-12">
          <label class="gfield_label text-sm" for="pwd_2">Confirm Password</label>
          <input class="<?php echo ( isset( $arrow_errors['pwd_2'] ) ? 'error-field' : '' ) ?>" id="pwd_2" name="pwd_2" type="password" autocomplete="off">

          <?php if ( isset( $arrow_errors['pwd_2'] ) ) : ?>
           <span class="text-sm block mt-1 text-red-100"><?php echo $arrow_errors['pwd_2']; ?></span>
          <?php endif; ?>
        </div>

        <?php wp_nonce_field( 'arrow-edit-password', 'arrow-edit-password-nonce' ); ?>

        <button class="btn is-plain text-center w-full" type="submit">Save Changes</button>
      </form>
    </section>
  </div>
</div>