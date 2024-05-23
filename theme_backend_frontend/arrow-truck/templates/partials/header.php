<?php
  $logo           = get_field( 'global_logo', 'option' );
  $primary_menu   = new LL_Menu( 'primary_navigation' );
  $secondary_menu = new LL_Menu( 'secondary_navigation' );
  $user           = new LL_WP_User( wp_get_current_user() );
  $filters        = new ArrowFilters;
?>

<?php include( locate_template( 'templates/partials/new_push.php' ) ); ?>

<header class="navbar" role="banner">
  <div class="relative navbar-top-wrapper">
    <div class="action-nav bg-brand-primary py-1 lg:py-1/2 hidden lg:block" id="action-nav">
      <nav class="bg-none container flex justify-between" role="navigation" aria-label="Secondary Navigation">
        <?php if(isset($_COOKIE['rep_no'])): ?>
            <?php

                $slug = ll_decode_cookie_handler('salesrep', false);

                $slug = str_replace(' ', '-', $slug); // Replaces all spaces with hyphens.

                $slug = preg_replace('/[^A-Za-z0-9\-]/', '', $slug);

                $thisUser = get_user_by( 'slug', $slug );

                $showButton = false;

                if($thisUser){
                    if($thisUser->roles[0] != "arrow_buyer"){
                        $sales_rep = new ArrowSalesRep( $thisUser, true );
                        $showButton = true;
                    }
                }

                $nameArr = explode("-", $slug);
                $fname = $nameArr[0];
                $lname = $nameArr[1];
                $first = ucfirst($fname);
                $last = ucfirst($lname[0]);
                $full = $first . ' ' . $last;
            ?>

            <?php if($showButton == true): ?>
                <button id="location-action-2" class="text-sm text-white w-full inline-flex justify-between items-center lg:w-auto" data-toggle-target="#location-information-dropdown" data-toggle-class="is-open">
                  <span class="font-bold mr-1/2" id="location-header-2">Sales Contact:</span>
                  <span class="font-normal">
                    <span id="location-value-2"><?php echo $full; ?></span>
                    <svg class="icon icon-chevron-down" aria-hidden="true"><use xlink:href="#icon-chevron-down"></use></svg>
                  </span>
                </button>
            <?php endif; ?>


        <?php endif; ?>

        <?php if ( is_user_logged_in() ) : ?>
            <?php if( isset( $showButton ) && $showButton == true ): ?>
                <button id="location-action" class="text-sm text-white invisible w-full inline-flex justify-between items-center lg:w-auto" data-toggle-target="#location-information-dropdown" data-toggle-class="is-open">
                  <span class="font-bold mr-1/2" id="location-header">Location:</span>
                  <span class="font-normal">
                    <span id="location-value"></span>
                    <svg class="icon icon-chevron-down" aria-hidden="true"><use xlink:href="#icon-chevron-down"></use></svg>
                  </span>
                </button>
            <?php endif; ?>
        <?php endif; ?>
        <?php if ( $secondary_menu->items ) : ?>
          <ul class="hidden xl:block ml-auto">
            <?php foreach ( $secondary_menu->items as $menu_item ) : ?>
              <li class="inline-block ml-8">
                <a class="leading-zero text-white text-base font-medium inline-flex items-center" href="<?php echo $menu_item->url ?>" role="menuitem" target="<?php echo $menu_item->target; ?>">
                  <?php if ( isset( $menu_item->fields['item_svg_icon'] ) ) : ?>
                  <svg class="mr-3 icon icon-<?php echo $menu_item->fields['item_svg_icon']; ?>" aria-hidden="true"><use xlink:href="#icon-<?php echo $menu_item->fields['item_svg_icon']; ?>"></use></svg>
                <?php endif; ?>
                  <?php echo $menu_item->title; ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </nav>
    </div>
    <div class="container py-4">
      <div class="flex flex-wrap lg:flex-nowrap items-center justify-between">

        <div class="flex-initial flex items-center justify-start">

          <div class="flex items-center justify-start">

            <?php if ( $logo ) : ?>
              <a class="flex-initial" href="<?php echo esc_url(home_url('/')); ?>">
                <img class="logo logo--header" src="<?php echo $logo['url']; ?>" alt="<?php bloginfo('name'); ?>">
              </a>
            <?php endif; ?>

            <div class="relative">
              <div class="nav-title" id="nav-title-menu">| MENU</div>
              <div class="nav-title hdg-6 text-brand-primary" id="favorites-title-menu">My Favorites</div>
              <div class="nav-title hdg-6 text-brand-primary" id="search-title-menu">Refine Search</div>
            </div>
          </div>

          <nav class="primary-nav hidden lg:block text-gray-300 font-medium lg:ml-4 xl:ml-9" id="primary-nav" role="navigation" aria-label="Primary Navigation">
            <ul class="lg:flex flex-initial" role="menubar">
              <li class="primary-menu-item border-b border-gray-200 lg:border-0 py-4 lg:py-0 lg:hidden mx-auto" role="presentation">
                <?php if ( is_user_logged_in() ) : ?>
                  <button class="font-medium account-nav-toggle flex justify-between items-center w-full" data-toggle-target=".account-nav-list" data-toggle-class="is-open"><span class="flex-initial"><svg class="icon icon-user text-brand-primary text-2xl mr-2"><use xlink:href="#icon-user"></use></svg> <?php echo $user->get_name(); ?></span> <svg class="icon icon-plus inline-block lg:hidden text-brand-primary flex-initial" aria-hidden="true"><use xlink:href="#icon-plus"></use></svg><svg class="icon icon-minus hidden text-brand-primary flex-initial" aria-hidden="true"><use xlink:href="#icon-minus"></use></svg></button>
                  <ul class="account-nav-list account-nav-list--mobile">

                    <li>
                      <a href="<?php echo arrow_page_url( 'account' ) ?>" class="hover:text-brand-primary">My Account</a>
                    </li>

                    <li>
                      <a href="/sell-my-truck" class="hover:text-brand-primary">Sell My Truck</a>
                    </li>

                    <li>
                      <a href="<?php echo wp_logout_url() ?>" class="hover:text-brand-primary">Log Out</a>
                    </li>
                  </ul>
                <?php else : ?>
                  <a href="/login" class="lg:relative flex justify-between lg:block" role="menuitem">
                    Log In
                    <svg class="icon icon-right-arrow inline-block lg:hidden text-brand-primary text-xl" aria-hidden="true"><use xlink:href="#icon-right-arrow"></use></svg>
                  </a>
                <?php endif; ?>
              </li>
              <li class="primary-menu-item border-b border-gray-200 lg:border-0 py-4 lg:py-0 lg:hidden mx-auto" role="presentation">
                <a href="/commercial-truck-sales" class="lg:relative flex justify-between lg:block" role="menuitem">
                  Promotions
                  <svg class="icon icon-right-arrow inline-block lg:hidden text-brand-primary text-xl" aria-hidden="true"><use xlink:href="#icon-right-arrow"></use></svg>
                </a>
              </li>
              <?php if ( $primary_menu->items ) : ?>
                <?php foreach( $primary_menu->items as $main_key => $menu_item ) : ?>
                  <li class="primary-menu-item border-b border-gray-200 lg:border-0 py-4 lg:py-0 mx-auto <?php echo $main_key == count( $primary_menu->items ) - 1 ? 'lg:mr-0' : 'lg:mr-8'; ?>" role="presentation">
                    <?php if ( $menu_item->hasChildren ) : ?>
                      <a class="lg:relative flex justify-between lg:block" data-toggle-class="is-open" data-toggle-target="#menu-<?php echo $menu_item->ID; ?>" aria-expanded="false" data-toggle-group="menu-accordions" href="#" id="item-<?php echo $menu_item->ID; ?>" role="menuitem">
                        <?php echo $menu_item->title; ?>
                        <svg class="icon icon-chevron-down hidden lg:inline-block" aria-hidden="true"><use xlink:href="#icon-chevron-down"></use></svg>
                        <svg class="icon icon-plus inline-block lg:hidden text-brand-primary" aria-hidden="true"><use xlink:href="#icon-plus"></use></svg>
                        <svg class="icon icon-minus hidden text-brand-primary" aria-hidden="true"><use xlink:href="#icon-minus"></use></svg>
                      </a>
                      <div class="sub-menu hidden pt-8 pb-8 lg:pb-0 overflow-auto" id="menu-<?php echo $menu_item->ID; ?>" aria-hidden="true" aria-labelledby="item-<?php echo $menu_item->ID; ?>">
                        <div class="container">
                          <div class="row justify-center">
                            <div class="col w-full xl:w-10/12">
                              <ul class="row " role="menubar" style="max-height: 300px;">
                                <?php foreach ( $menu_item->children as $key => $child_item ) : ?>
                                  <li class="menu-item col w-full <?php echo $child_item->children ? 'lg:w-1/3 mb-10 lg:mb-3' : 'mb-3'; ?> font-bold xl:pl-20 last:mb-0 <?php echo implode( ' ', $child_item->classes ) ?>" role="presentation">
                                    <?php if ( $child_item->children ) : ?>
                                      <span class="hdg-6 block mb-4 text-brand-primary"><?php echo $child_item->title; ?></span>
                                      <ul class="sub-sub-menu" role="menubar">
                                        <?php foreach ( $child_item->children as $sub_child_item ) : ?>
                                          <li class="menu-item mb-3 last:mb-0 <?php echo implode( ' ', $sub_child_item->classes ) ?>" role="presentation">
                                            <?php if ( in_array( 'filter-btn', $sub_child_item->classes ) ) : ?>
                                              <button type="button" data-toggle-target="#advanced-search, #search-title-menu, #search-nav-toggle" data-toggle-class="is-open"><?php echo $sub_child_item->title; ?></button>
                                            <?php else : ?>
                                              <a href="<?php echo $sub_child_item->url; ?>" class="hover:text-brand-primary" role="menuitem" target="<?php echo $sub_child_item->target; ?>">
                                                <?php echo $sub_child_item->title; ?>
                                              </a>
                                            <?php endif; ?>
                                          </li>
                                        <?php endforeach; ?>
                                      </ul>
                                    <?php else : ?>
                                      <a href="<?php echo $child_item->url; ?>" class="hover:text-brand-primary" role="menuitem" target="<?php echo $child_item->target; ?>"><?php echo $child_item->title; ?></a>
                                    <?php endif; ?>
                                  </li>
                                <?php endforeach; ?>
                              </ul>
                            </div> <!-- /.col -->
                          </div> <!-- /.row -->
                        </div> <!-- /.container -->
                      </div> <!-- /.sub-menu -->
                    <?php else : ?>
                      <a href="<?php echo $menu_item->url ?>" role="menuitem" target="<?php echo $menu_item->target; ?>"><?php echo $menu_item->title; ?></a>
                    <?php endif; ?>
                  </li> <!-- /.primary--menu-item -->
                <?php endforeach; ?>
              <?php endif; ?>
              <li class="primary-menu-item border-b border-gray-200 lg:border-0 py-4 lg:py-0 lg:hidden mx-auto" role="presentation">
                <button onclick="location.href='https://www.arrowtruck.com'" class="font-medium account-nav-toggle" data-toggle-target=".country-dropdown" data-toggle-class="is-open"><span class="flag-circle mr-2"><svg class="icon icon-America"><use xlink:href="#icon-America"></use></svg></span> United States <svg class="icon icon-chevron-down text-base ml-1"><use xlink:href="#icon-chevron-down"></use></svg></button>
              </li>
              <li class="primary-menu-item border-b border-gray-200 lg:border-0 py-4 lg:py-0 lg:hidden mx-auto" role="presentation">
                <button onclick="location.href='http://www.arrowtruck.ca'" class="font-medium account-nav-toggle" data-toggle-target=".country-dropdown" data-toggle-class="is-open"><span class="flag-circle mr-2"><svg class="icon icon-Canada"><use xlink:href="#icon-Canada"></use></svg></span> Canada <svg class="icon icon-chevron-down text-base ml-1"><use xlink:href="#icon-chevron-down"></use></svg></button>
              </li>
            </ul>

            <div class="bottom-nav px-3 text-center grid lg:hidden grid-cols-2 gap-x-3">
                <a href="tel:+1<?php echo strip_phone(get_field('contact_phone_number', 'option'));?>" class="btn is-plain"><svg class="icon icon-phone-alt text-lg svg-align mr-2"><use xlink:href="#icon-phone-alt"></use></svg> Call us</a>

                <a href="<?php echo get_permalink(get_field('page_id_quote', 'option')); ?>" class="btn is-plain"><svg class="icon icon-dollar text-xl svg-align mr-2"><use xlink:href="#icon-dollar"></use></svg> Get a Quote</a>
            </div> <!-- /.bottom-nav -->
          </nav>

        </div> <!-- /.primary-nav -->

        <div class="flex-initial text-right inline-flex items-center">

          <!-- <a href="#" class="mr-4 text-gray-200 hover:text-brand-primary wishlist-link leading-zero" title="Wishlist" data-toggle-class="is-open" data-toggle-target="#favorites-dropdown, #favorites-title-menu" data-toggle-group="menu-toggles">
            <svg class="pointer-events-none icon icon-heart text-2xl "><use xlink:href="#icon-heart"></use></svg>
            <span class="pointer-events-none wishlist-num"><span class="wishlist-count">0</span></span>
          </a> -->

          <div class="hidden lg:inline-block">

            <?php if ( is_user_logged_in() ) : ?>

              <button class="font-medium account-nav-toggle" data-toggle-target=".account-nav-list" data-toggle-outside data-toggle-escape data-toggle-class="is-open"><svg class="icon icon-user text-brand-primary text-2xl mr-1"><use xlink:href="#icon-user"></use></svg> <?php echo $user->get_name(); ?> <svg class="icon icon-chevron-down text-base ml-1"><use xlink:href="#icon-chevron-down"></use></svg></button>

              <ul class="account-nav-list account-nav-list--desktop">

                <li>
                  <a href="<?php echo arrow_page_url( 'account' ) ?>" class="hover:text-brand-primary">My Account</a>
                </li>

                <li>
                  <a href="/sell-my-truck" class="hover:text-brand-primary">Sell My Truck</a>
                </li>

                <li>
                  <a href="<?php echo wp_logout_url() ?>" class="hover:text-brand-primary">Log Out</a>
                </li>

              </ul>

            <?php else : ?>

              <a href="<?php echo arrow_page_url( 'login' ) ?>">Sign In</a>

            <?php endif; ?>

          </div> <!-- /.hidden -->

          <button id="main-nav-toggle" type="button" class="navbar-toggle flex-initial lg:hidden" data-toggle-group="menu-toggles" data-toggle-class="is-open" data-toggle-target="#primary-nav, #nav-title-menu">
            <span class="navbar-toggle-icon"></span>
            <span class="sr-only">Main Menu</span>
          </button>

          <button id="search-nav-toggle" type="button" class="navbar-toggle flex-initial hidden lg:hidden" data-toggle-class="is-open" data-toggle-target="#advanced-search, #search-title-menu">
            <span class="navbar-toggle-icon"></span>
            <span class="sr-only">Close Advanced Search</span>
          </button>

        </div>
      </div>
    </div>
  </div>

  <div class="absolute left-0 w-full grid lg:grid-cols-12 lg:gap-13 h-screen overflow-y-auto bg-white hidden px-gutter" id="location-information-dropdown" style="top: var(--topnavHeight);">
  </div>



  <div class="hidden" id="favorites-dropdown" style="top: var(--navbarHeight);">
    <div id="favorites-list" class="absolute left-0 w-full h-screen overflow-y-auto bg-white md:grid-cols-4 md:gap-gutter items-start hidden">
    </div>

    <div class="absolute favorites-tooltip search-dropdown px-5 py-6 hidden">
      <span class="arrow"></span>
      <h3 class="paragraph-large font-bold text-white mb-1/2">Favorites</h3>
      <?php if ( is_user_logged_in() ) : ?>
        <p class="text-white paragraph-small">Click the heart icon on the trucks that you want to save to look at later.</p>
      <?php else : ?>
        <p class="text-white paragraph-small">Create an account/Log In, to favorite trucks and save them to your account.</p>
        <div class="flex items-center mt-5">
          <a class="btn-outline mr-5" href="<?php echo arrow_page_url( 'register' ); ?>">Create Account</a>
          <a class="btn-outline" href="<?php echo arrow_page_url( 'login' ); ?>">Log In</a>
        </div>
      <?php endif; ?>
    </div>
  </div>

</header>
