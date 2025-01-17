<?php
  $logo  = get_field( 'global_footer_logo', 'option' );
  /*
   * create as an array to loop through
   * if they're in a list, or create them individually
   *
   * Call individually
   * $footer_menu_one = new LL_Menu( 'footer_menu_one' );
   * $footer_menu_two = new LL_Menu( 'footer_menu_two' );
  */

  $menus = array(
    new LL_Menu( 'footer_menu_one' ),
    new LL_Menu( 'footer_menu_two' )
  );
?>
<footer class="footer bg-red-400 text-white" role="contentinfo">
  <div class="container py-12 lg:py-20">
    <div class="row">
      <div class="col w-full xl:w-10/12 mx-auto">
        <div class="row">

          <div class="col w-full lg:w-3/12 mb-10 lg:mb-0">
            <a class="logo-wrapper mx-auto lg:mx-0" href="<?php echo esc_url(home_url('/')); ?>">
              <img class="logo logo--footer max-w-64" src="<?php echo $logo['url']; ?>" alt="<?php bloginfo('name'); ?>">
            </a>
          </div>

          <?php foreach ( $menus as $menu ) : ?>
            <div class="col w-full lg:w-3/12 mb-12 lg:mb-0">
              <?php if ( isset( $menu->hasItems ) ) : ?>
                <nav class="mb-7 last:mb-0">
                  <h4 class="mb-2 text-lg font-bold text-white"><?php echo $menu->name; ?></h4>
                  <ul>
                    <?php foreach( $menu->items as $menu_item ): ?>
                      <li class="mb-2 last:mb-0">
                        <a class="text-sm hover:underline" href="<?php echo $menu_item->url ?>" target="<?php echo $menu_item->target; ?>"><?php echo $menu_item->title; ?></a>
                      </li>
                    <?php endforeach; ?>
                  </ul>
                </nav>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>

          <div class="col w-full lg:w-3/12">
            <?php echo ll_get_social_list(); ?>

            <p class="mt-4">
              <a href="tel:+1<?php echo strip_phone(get_field('contact_phone_number', 'option'));?>" class="text-sm hover:underline"><?php echo get_field('contact_phone_number', 'option'); ?></a>
            </p>

            <div class="w-full flex flex-col justify-evenly align-center justify-center">
              <button onclick="location.href='https://www.arrowtruck.com'" class="font-medium text-sm mt-4 footer-country-nav-toggle hover:underline self-start" data-toggle-target=".footer-country-dropdown" data-toggle-class="is-open"><span class="flag-circle mr-2"><svg class="icon icon-America"><use xlink:href="#icon-America"></use></svg></span> United States</button>
              <button onclick="location.href='http://www.arrowtruck.ca'" class="font-medium text-sm mt-4 footer-country-nav-toggle hover:underline self-start" data-toggle-target=".footer-country-dropdown" data-toggle-class="is-open"><span class="flag-circle mr-2"><svg class="icon icon-Canada"><use xlink:href="#icon-Canada"></use></svg></span> Canada</button>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
  <div class=" w-full py-4 signupforeamils px-6">
    <p class="text-black text-center">
      If you want to be the first to hear about special offers or news and events, be sure to <a class="" href="/sign-up-for-email">sign up for our emails</a>.
    </p>
  </div>

  <div class="bg-red-200 py-4">
    <div class="container">
      <div class="row justify-center text-white">
        <div class="footer-bottom col w-full xl:w-10/12 flex items-center justify-start lg:justify-between flex-wrap">
          <div class="text-left text-sm mr-4 lg:mr-0">
            <?php bloginfo('name'); ?> &copy; <?php echo date('Y'); ?> All Rights Reserved
          </div>

          <div class="text-center text-sm mr-4 lg:mr-0">
            <a href="<?php echo get_permalink(get_field('page_id_privacy_policy', 'option')); ?>" class="hover:underline">Privacy Policy and Legal Notice</a>
          </div>

          <div class="text-center text-sm mr-4 lg:mr-0">
            <a href="/disclaimer" class="hover:underline">Disclaimer</a>
          </div>

          <div class="text-right text-sm mt-2 lg:mt-0">
            <a class="hover:underline" href="https://liftedlogic.com/" target="_blank">Web Design in Kansas City</a> by <a class="hover:underline" href="https://liftedlogic.com/" target="_blank">Lifted Logic</a>
          </div>
        </div>

      </div>
    </div>
  </div>
</footer>
