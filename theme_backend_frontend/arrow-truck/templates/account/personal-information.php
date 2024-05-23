<div class="min-h-screen">
  <div class="bg-red-400 py-9 bg-red-400 text-white">
    <div class="container grid lg:grid-cols-12 lg:gap-13">
       <div class="lg:col-span-6 lg:col-start-4 flex items-center justify-center relative">
        <a class="absolute left-0 top-1/2 transform -translate-y-1/2 back-btn" href="<?php echo arrow_page_url( 'account' ) ?>"><span class="sr-only">Back</span> <svg class="icon icon-left-arrow" aria-hidden="true"><use xlink:href="#icon-left-arrow"></use></svg></a>
        <h1 class=" hdg-6 text-white text-center">
          Personal Information
        </h1>
      </div>
    </div>
  </div>

  <div class="container grid lg:grid-cols-12 lg:gap-x-13">
    <section class="mt-10 lg:col-span-6 lg:col-start-4" id="personal-information">
      <div class="flex justify-between items-center mb-5">
        <h2 class="text-lg font-bold text-gray-400">Personal Information</h2>
        <a class="text-red-400 font-bold uppercase tracking-widest text-xs" href="<?php echo arrow_page_url( 'account' ); ?>?view=edit-personal-information"><svg class="icon icon-pencil" aria-hidden="true"><use xlink:href="#icon-pencil"></use></svg> Edit</a>
      </div>
      <ul>
        <li class="flex justify-between items-center tab-row rounded py-2 px-6 bg-gray-100 mb-2">
          <span class="text-sm font-bold text-left">Name</span>
          <span class="text-left"><?php echo $account_user->full_name(); ?></span>
        </li>
        <li class="flex justify-between items-center tab-row rounded py-2 px-6 bg-gray-100 mb-2">
          <span class="text-sm font-bold text-left">Email</span>
          <span class="text-left"><?php echo $account_user->user_email; ?></span>
        </li>
        <li class="flex justify-between items-center tab-row rounded py-2 px-6 bg-gray-100 mb-2">
          <span class="text-sm font-bold text-left">Phone</span>
          <span class="text-left"><?php echo $account_user->phone; ?></span>
        </li>
        <li class="flex justify-between items-center tab-row rounded py-2 px-6 bg-gray-100 mb-2">
          <span class="text-sm font-bold text-left">Company Name</span>
          <span class="text-left"><?php echo $account_user->company; ?></span>
        </li>
      </ul>
    </section>

    <div class="container grid lg:grid-cols-12 lg:gap-x-13">
      <section class="mt-10 lg:col-span-6 lg:col-start-4" id="preferences">
        <div class="flex justify-between items-center mb-5">
          <h2 class="text-lg font-bold text-gray-400">Preferences</h2>
          <a class="text-red-400 font-bold uppercase tracking-widest text-xs" href="<?php echo arrow_page_url( 'account' ); ?>?view=edit-personal-information"><svg class="icon icon-pencil" aria-hidden="true"><use xlink:href="#icon-pencil"></use></svg> Edit</a>
        </div>
        <ul>
          <li class="flex justify-between items-center tab-row rounded py-2 px-6 bg-gray-100 mb-2">
            <span class="text-sm font-bold text-left">Language Preference</span>
            <span class="text-left"><?php echo $account_user->languagePreference; ?></span>
          </li>
          <li class="flex justify-between items-center tab-row rounded py-2 px-6 bg-gray-100 mb-2">
            <span class="text-sm font-bold text-left">Brand Preference</span>
            <span class="text-left"><?php echo $account_user->brand_preference; ?></span>
          </li>
          <li class="flex justify-between items-center tab-row rounded py-2 px-6 bg-gray-100 mb-2">
            <span class="text-sm font-bold text-left">Equipment Preference</span>
            <span class="text-left"><?php echo $account_user->equipment_preference; ?></span>
          </li>
        </ul>
      </section>

    <section class="mt-10 lg:col-span-6 lg:col-start-4" id="password">
      <div class="flex justify-between items-center mb-5">
        <h2 class="text-lg font-bold text-gray-400">Password</h2>
        <a class="text-red-400 font-bold uppercase tracking-widest text-xs" href="<?php echo arrow_page_url( 'account' ); ?>?view=edit-password"><svg class="icon icon-pencil" aria-hidden="true"><use xlink:href="#icon-pencil"></use></svg> Edit</a>
      </div>
      <ul>
        <li class="flex justify-between items-center tab-row rounded py-2 px-6 bg-gray-100 mb-2">
          <span class="text-sm font-bold text-left">Password</span>
          <span class="text-left text-lg"><?php for ($i=0; $i < 12; $i++) { echo '&bull;';} ?></span>
        </li>
      </ul>
    </section>

    <section class="mt-10 lg:col-span-6 lg:col-start-4" id="billing-address">
      <div class="flex justify-between items-center mb-5">
        <h2 class="text-lg font-bold text-gray-400">Billing Address</h2>
        <a class="text-red-400 font-bold uppercase tracking-widest text-xs" href="<?php echo arrow_page_url( 'account' ); ?>?view=edit-billing-address"><svg class="icon icon-pencil" aria-hidden="true"><use xlink:href="#icon-pencil"></use></svg> Edit</a>
      </div>
      <ul>
        <li class="flex justify-between items-center tab-row rounded py-2 px-6 bg-gray-100 mb-2">
          <span class="text-sm font-bold text-left">Street</span>
          <span class="text-left"><?php echo $account_user->address_street; ?></span>
        </li>
        <li class="flex justify-between items-center tab-row rounded py-2 px-6 bg-gray-100 mb-2">
          <span class="text-sm font-bold text-left">City</span>
          <span class="text-left"><?php echo $account_user->address_city; ?></span>
        </li>
        <li class="flex justify-between items-center tab-row rounded py-2 px-6 bg-gray-100 mb-2">
          <span class="text-sm font-bold text-left">State</span>
          <span class="text-left"><?php echo $account_user->address_state; ?></span>
        </li>
        <li class="flex justify-between items-center tab-row rounded py-2 px-6 bg-gray-100 mb-2">
          <span class="text-sm font-bold text-left">Zip</span>
          <span class="text-left"><?php echo $account_user->address_zip; ?></span>
        </li>
      </ul>
    </section>

    <section class="mt-10 mb-24 lg:col-span-6 lg:col-start-4" id="shipping-address">
      <div class="flex justify-between items-center mb-5">
        <h2 class="text-lg font-bold text-gray-400">Shipping Address</h2>
        <a class="text-red-400 font-bold uppercase tracking-widest text-xs" href="<?php echo arrow_page_url( 'account' ); ?>?view=edit-shipping-address"><svg class="icon icon-pencil" aria-hidden="true"><use xlink:href="#icon-pencil"></use></svg> Edit</a>
      </div>
      <ul>
        <li class="flex justify-between items-center tab-row rounded py-2 px-6 bg-gray-100 mb-2">
          <span class="text-sm font-bold text-left">Street</span>
          <span class="text-left"><?php echo $account_user->shipping_street; ?></span>
        </li>
        <li class="flex justify-between items-center tab-row rounded py-2 px-6 bg-gray-100 mb-2">
          <span class="text-sm font-bold text-left">City</span>
          <span class="text-left"><?php echo $account_user->shipping_city; ?></span>
        </li>
        <li class="flex justify-between items-center tab-row rounded py-2 px-6 bg-gray-100 mb-2">
          <span class="text-sm font-bold text-left">State</span>
          <span class="text-left"><?php echo $account_user->shipping_state; ?></span>
        </li>
        <li class="flex justify-between items-center tab-row rounded py-2 px-6 bg-gray-100 mb-2">
          <span class="text-sm font-bold text-left">Zip</span>
          <span class="text-left"><?php echo $account_user->shipping_zip; ?></span>
        </li>
      </ul>
    </section>
  </div>
</div>
