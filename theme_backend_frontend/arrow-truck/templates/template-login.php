<?php
/*
Template Name: Login
*/
?>
<?php while (have_posts()) : the_post(); ?>
  <?php
    if ( isset( $_GET['errors'] ) ) {
      $message = (object) ['code' => $_GET['errors'], 'message' => ''];
      LL_FlashData()->add_notice( $message );
    }
  ?>
  <section class="container grid md:grid-cols-12 md:gap-13 pb-20 pt-10 <?php echo( LL_FlashData()->notice ? 'has-error' : '' ); ?>">
    <div class="md:col-span-6 md:col-start-4">
      <?php if ( isset( $_GET['action'] ) && $_GET['action'] == 'lostpassword' ) : ?>
        <?php if ( isset( $_GET['checkemail'] ) && $_GET['checkemail'] == 'confirm' ) : ?>
          <?php
            /*
             * Reset sent
             */
          ?>
          <div class="wysiwyg mb-64">
            <h1 class="hdg-2 text-2xl md:text-4xl mb-6">Password Request Sent</h1>
            <p>Check your email for the confirmation link, then visit the <a class="text-brand-primary underline" href="<?php echo wp_login_url(); ?>">login page</a></p>
          </div>

      <?php elseif ( isset( $_GET['reset'] ) ) : ?>
          <?php
            /*
             * Reset Form
             */
          ?>
          <h1 class="hdg-2 text-2xl md:text-4xl mb-6">Password Rest</h1>
          <form class="form-skin" name="resetpassform" id="resetpassform" action="<?php echo site_url('wp-login.php?action=resetpass'); ?>" method="post" autocomplete="off">
            <input type="hidden" id="user_login" name="rp_login" value="<?php echo ll_get_var( $_GET['login'] ); ?>" autocomplete="off"/>
            <input type="hidden" name="rp_key" value="<?php echo ll_get_var( $_GET['key'] ); ?>"/>

            <p class="mb-4">
              <label class="gfield_label text-sm" for="pass1">New Password</label>
              <input class="<?php echo ( LL_FlashData()->check( ['password_reset_empty', 'password_reset_mismatch'] ) ? 'error-field' : '' ) ?>" type="password" name="pass1" id="pass1" class="input" size="20" value="" autocomplete="off"/>

                <?php if ( LL_FlashData()->check( ['password_reset_empty', 'password_reset_mismatch'] ) ) : ?>
                  <span class="text-sm block mt-1 text-red-100"><?php echo LL_FlashData()->notice->message; ?></span>
                <?php endif; ?>

            </p>

            <p class="mb-4">
              <label class="gfield_label text-sm" for="pass2">Confirm New Password</label>
              <input class="<?php echo ( LL_FlashData()->check( ['password_reset_mismatch'] ) ? 'error-field' : '' ) ?>" type="password" name="pass2" id="pass2" class="input" size="20" value="" autocomplete="off"/>

              <?php if ( LL_FlashData()->check( ['password_reset_mismatch'] ) ) : ?>
                <span class="text-sm block mt-1 text-red-100"><?php echo LL_FlashData()->notice->message; ?></span>
              <?php endif; ?>
            </p>

            <p class="paragraph-small mt-6 mb-13"><?php echo wp_get_password_hint(); ?></p>
            <button class="btn is-plain text-center w-full" type="submit" name="submit" id="resetpass-button">Reset Password</button>
          </form>
        <?php else : ?>
          <?php
            /*
             * Request Reset
             */
          ?>
          <div class="mb-48">
            <h1 class="hdg-2 text-2xl md:text-4xl mb-6">Request Password Reset</h1>
            <form class="form-skin" id="lostpasswordform" action="<?php echo wp_lostpassword_url(); ?>" method="post">
              <p class="mb-13">
                <label class="gfield_label text-sm" for="user_login">Email</label>
                <input class="<?php echo( LL_FlashData()->notice ? 'error-field' : '' ) ?>" type="text" name="user_login" id="user_login">
                <?php if ( LL_FlashData()->notice->message ) : ?>
                  <span class="text-sm block mt-1 text-red-100"><?php echo LL_FlashData()->notice->message; ?></span>
                <?php endif; ?>
              </p>

              <button class="btn is-plain text-center w-full" type="submit" name="submit" value="Reset Password">Reset Password</button>
            </form>
          </div>
        <?php endif; ?>
      <?php else : ?>
        <?php
          /*
           * Login
           */
        ?>
        <h1 class="hdg-2 text-2xl md:text-4xl mb-6"><?php the_title() ?></h1>
        <form class="form-skin" method="post">

          <p class="mb-4">
            <label class="gfield_label text-sm" for="username">Email</label>
            <input class="<?php echo ( LL_FlashData()->check( ['empty_email', 'invalid_email', 'invalid_username'] ) ? 'error-field' : '' ) ?>" type="text" name="username" id="username" autocomplete="email" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" />

            <?php if ( LL_FlashData()->check( ['empty_email', 'invalid_email', 'invalid_username'], LL_FlashData()->notice ) ) : ?>
              <span class="text-sm block mt-1 text-red-100"><?php echo LL_FlashData()->notice->message; ?></span>
            <?php endif; ?>
          </p>

          <p>
            <label class="gfield_label text-sm" for="password">Password</label>
            <input class="<?php echo ( LL_FlashData()->check( ['empty_password', 'incorrect_password'] ) ? 'error-field' : '' ) ?>" type="password" name="password" id="password" autocomplete="current-password" />

            <?php if ( LL_FlashData()->check( ['empty_password', 'incorrect_password'] ) ) : ?>
              <span class="text-sm block mt-1 text-red-100"><?php echo LL_FlashData()->notice->message; ?></span>
            <?php endif; ?>
          </p>


          <p class="mt-6 mb-13">
            <label>
              <input name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span>Remember Me</span>
            </label>
          </p>

          <?php wp_nonce_field( 'arrow-login', 'arrow-login-nonce' ); ?>

          <button type="submit" class="btn is-plain text-center w-full" name="login" value="Log in">Log in</button>

        </form>

        <p class="mt-12">
          or <a class="text-brand-primary underline" href="<?php echo arrow_page_url( 'register' ); ?>">Create an Account</a> | <a class="text-brand-primary underline" href="<?php echo arrow_page_url( 'login' ); ?>?action=lostpassword">Forgot Password</a>
        </p>
      <?php endif; ?>

    </div>
  </section>
<?php endwhile; ?>
