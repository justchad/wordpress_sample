<div class="min-h-screen">
  <div class="bg-red-400 py-9 bg-red-400 text-white">
    <div class="container grid lg:grid-cols-12 lg:gap-13">
       <div class="lg:col-span-6 lg:col-start-4 flex items-center justify-center relative">
        <a class="absolute left-0 top-1/2 transform -translate-y-1/2 back-btn" href="<?php echo get_the_permalink() ?>?view=personal-information"><span class="sr-only">Back</span> <svg class="icon icon-left-arrow" aria-hidden="true"><use xlink:href="#icon-left-arrow"></use></svg></a>
        <h1 class=" hdg-6 text-white text-center">
          Edit Billing Address
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
        <h2 class="text-lg font-bold text-gray-400">Billing Address</h2>
      </div>
      <form class="form-skin" action="<?php echo get_the_permalink() ?>?view=edit-billing-address" method="post">
        <div class="mb-4">
          <label class="gfield_label text-sm" for="address_street">Street</label>
          <input class="<?php echo ( isset( $arrow_errors['address_street'] ) ? 'error-field' : '' ) ?>" id="address_street" name="address_street" type="text" value="<?php echo ( $_POST && isset( $_POST['address_street'] ) ? $_POST['address_street'] : $account_user->address_street ); ?>" placeholder="Enter street address">
          <?php if ( isset( $arrow_errors['address_street'] ) ) : ?>
           <span class="text-sm block mt-1 text-red-100"><?php echo $arrow_errors['address_street']; ?></span>
          <?php endif; ?>
        </div>

        <div class="mb-4">
          <label class="gfield_label text-sm" for="address_city">City</label>
          <input class="<?php echo ( isset( $arrow_errors['address_city'] ) ? 'error-field' : '' ) ?>" id="address_city" name="address_city" type="text" value="<?php echo ( $_POST && isset( $_POST['address_city'] ) ? $_POST['address_city'] : $account_user->address_city ); ?>" placeholder="Enter city">

          <?php if ( isset( $arrow_errors['address_city'] ) ) : ?>
           <span class="text-sm block mt-1 text-red-100"><?php echo $arrow_errors['address_city']; ?></span>
          <?php endif; ?>
        </div>

        <div class="mb-4">
          <label class="gfield_label text-sm" for="address_state">State</label>
          <select class="<?php echo ( isset( $arrow_errors['address_state'] ) ? 'error-field' : '' ) ?>" id="address_state" name="address_state">
              <?php
              $state = array(
                  'NONE' => 'Select State',
                  'AL' => 'Alabama',
                  'AK' => 'Alaska',
                  'AZ' => 'Arizona',
                  'AR' => 'Arkansas',
                  'CA' => 'California',
                  'CO' => 'Colorado',
                  'CT' => 'Connecticut',
                  'DE' => 'Delaware',
                  'DC' => 'District Of Columbia',
                  'FL' => 'Florida',
                  'GA' => 'Georgia',
                  'HI' => 'Hawaii',
                  'ID' => 'Idaho',
                  'IL' => 'Illinois',
                  'IN' => 'Indiana',
                  'IA' => 'Iowa',
                  'KS' => 'Kansas',
                  'KY' => 'Kentucky',
                  'LA' => 'Louisiana',
                  'ME' => 'Maine',
                  'MD' => 'Maryland',
                  'MA' => 'Massachusetts',
                  'MI' => 'Michigan',
                  'MN' => 'Minnesota',
                  'MS' => 'Mississippi',
                  'MO' => 'Missouri',
                  'MT' => 'Montana',
                  'NE' => 'Nebraska',
                  'NV' => 'Nevada',
                  'NH' => 'New Hampshire',
                  'NJ' => 'New Jersey',
                  'NM' => 'New Mexico',
                  'NY' => 'New York',
                  'NC' => 'North Carolina',
                  'ND' => 'North Dakota',
                  'OH' => 'Ohio',
                  'OK' => 'Oklahoma',
                  'OR' => 'Oregon',
                  'PA' => 'Pennsylvania',
                  'RI' => 'Rhode Island',
                  'SC' => 'South Carolina',
                  'SD' => 'South Dakota',
                  'TN' => 'Tennessee',
                  'TX' => 'Texas',
                  'UT' => 'Utah',
                  'VT' => 'Vermont',
                  'VA' => 'Virginia',
                  'WA' => 'Washington',
                  'WV' => 'West Virginia',
                  'WI' => 'Wisconsin',
                  'WY' => 'Wyoming',
                  'AB' => 'Alberta',
                  'BC' => 'British Columbia',
                  'MB' => 'Manitoba',
                  'NB' => 'New Brunswick',
                  'NL' => 'Newfoundland and Labrador',
                  'NS' => 'Nova Scotia',
                  'ON' => 'Ontario',
                  'PE' => 'Prince Edward Island',
                  'QC' => 'Quebec',
                  'SK' => 'Saskatchewan',
                  'NT' => 'Northwest Territories',
                  'NU' => 'Nunavut',
                  'YT' => 'Yukon',
              );



                  $selected = $_POST && isset( $_POST['address_state'] ) ? $_POST['address_state'] : $account_user->address_state;
                  $selected_key = $account_user->address_state;

                  if(array_key_exists($selected, $state)){
                      foreach ($state as $code => $label) {
                          echo '<option value="' . $code . '"';
                          if ($selected_key == $code) {
                              $is_matched = true;
                              echo ' selected="selected"';
                          }
                          echo '>' . $label . '</option>';
                      }
                  }else{
                      foreach ($state as $code => $label) {
                          echo '<option value="' . $code . '"';
                          if ('NONE' == $code) {
                              $is_matched = true;
                              echo ' selected="selected"';
                          }
                          echo '>' . $label . '</option>';
                      }
                  }
              ?>
        </select>

          <?php if ( isset($arrow_errors['address_state']) ) : ?>
           <span class="text-sm block mt-1 text-red-100"><?php echo $arrow_errors['address_state']; ?></span>
          <?php endif; ?>
        </div>

        <div class="mb-12">
          <label class="gfield_label text-sm" for="address_zip">Zip / Postal Code</label>
          <input class="<?php echo ( isset($arrow_errors['address_zip']) ? 'error-field' : '' ) ?>" id="address_zip" name="address_zip" type="text" value="<?php echo ( $_POST && isset( $_POST['address_zip'] ) ? $_POST['address_zip'] : $account_user->address_zip ); ?>" placeholder="Enter zipcode">

          <?php if ( isset( $arrow_errors['address_zip'] ) ) : ?>
           <span class="text-sm block mt-1 text-red-100"><?php echo $arrow_errors['address_zip']; ?></span>
          <?php endif; ?>
        </div>

        <?php wp_nonce_field( 'arrow-edit-billing-address', 'arrow-edit-billing-address-nonce' ); ?>

        <button class="btn is-plain text-center w-full" type="submit">Save Changes</button>
      </form>
    </section>
  </div>
</div>
