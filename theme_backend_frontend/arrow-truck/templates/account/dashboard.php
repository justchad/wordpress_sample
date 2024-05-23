<?php

  $links = get_field('account_direct_links');

  if(get_field('account_direct_links')){
      $hasLinks = true;
  }else{
      $hasLinks = false;
  }

 ?>

<div class="min-h-screen">
  <div class="bg-red-400 py-9 bg-red-400 text-white">
    <div class="container">
      <h1 class="flex items-center">
        <svg class="icon icon-user text-white text-4xl mr-3"><use xlink:href="#icon-user"></use></svg>
        <span class="font-bold text-lg">Hello, <?php echo $account_user->get_name(); ?></span>
      </h1>
    </div>
  </div>

  <?php if ( $hasLinks ) : ?>
      <div class="account-profile-resources">
          <div class="account-profile-resources-title">
              <div class="container">
                  <h2>View/Edit Details</h2>
              </div>

          </div>
          <div class="container">

              <ul class="lg:col-span-6 lg:col-start-4 vieweditpersonalinformationlist-alt">
                   <li class="border-b border-gray-200">
                     <svg class="dashboard-list-icon icon icon-user text-red text-4xl mr-3"><use xlink:href="#icon-user"></use></svg>
                     <a class="dashboard-list-link py-6 px-gutter lg:px-0 flex justify-between items-center font-medium text-gray-400" href="<?php echo get_the_permalink() ?>?view=personal-information">
                         Personal Details
                     </a>
                     <a class="dashboard-list-edit" href="<?php echo get_the_permalink() ?>?view=edit-personal-information">
                         <img src="/wp-content/themes/arrow-truck/assets/img/edit.png" alt=""> Quick Edit
                     </a>
                     <a class="dashboard-list-arrow py-6 px-gutter lg:px-0 flex justify-between items-center font-medium text-gray-400" href="<?php echo get_the_permalink() ?>?view=personal-information">
                         <svg class="icon icon-right-arrow" aria-hidden="true"><use xlink:href="#icon-right-arrow"></use></svg>
                     </a>
                   </li>
               </ul>

          </div>

      </div>
  <?php endif; ?>

  <?php if ( $hasLinks ) : ?>
      <div class="account-helpful-resources">
          <div class="account-helpful-resources-title">
              <div class="container">
                  <h2>Helpful Site Resources</h2>
              </div>

          </div>
          <div class="container">

                  <ul class="lg:col-span-6 lg:col-start-4">
                      <?php foreach ( $links as $key => $link ) : ?>

                          <li class="border-b border-gray-200">
                            <a class="py-6 px-gutter lg:px-0 flex justify-between items-center font-medium text-gray-400" href="<?php echo get_permalink( $link['pages']->ID ); ?>">
                                <?php echo $link['pages']->post_title ?>
                                <svg class="icon icon-right-arrow" aria-hidden="true"><use xlink:href="#icon-right-arrow"></use></svg></a>
                          </li>

                      <?php endforeach; ?>
                  </ul>

          </div>

      </div>
  <?php endif; ?>


</div>
