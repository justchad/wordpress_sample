<?php
/*
Template Name: Register
*/
global $arrow_errors, $arrow_message;

?>
<?php while (have_posts()) : the_post(); ?>
  <section class="container grid md:grid-cols-12 md:gap-13 pb-20 pt-10 <?php echo( LL_FlashData()->notice ? 'has-error' : '' ); ?>">
    <div class="md:col-span-6 md:col-start-4">
      <h1 class="hdg-2 text-2xl md:text-4xl mb-6"><?php the_title() ?></h1>
      <form class="form-skin" method="post">
        <h2 class="hdg-2 text-lg md:text-xl mt-6 mb-6">Personal Information</h2>
        <div class="mb-4">
          <label class="gfield_label text-sm" for="first_name">First Name</label>
          <input class="<?php echo ( isset( $arrow_errors['first_name'] ) ? 'error-field' : '' ) ?>" id="first_name" name="first_name" type="text" value="<?php echo ( $_POST && isset( $_POST['first_name'] ) ? $_POST['first_name'] : '' ); ?>" placeholder="Enter first name">

          <?php if ( isset( $arrow_errors['first_name'] ) ) : ?>
            <span class="text-sm block mt-1 text-red-100"><?php echo $arrow_errors['first_name']; ?></span>
          <?php endif; ?>
        </div>

        <div class="mb-4">
          <label class="gfield_label text-sm" for="last_name">Last Name</label>
          <input class="<?php echo ( isset( $arrow_errors['last_name'] ) ? 'error-field' : '' ) ?>" id="last_name" name="last_name" type="text" value="<?php echo ( $_POST && isset( $_POST['last_name'] ) ? $_POST['last_name'] : '' ); ?>" placeholder="Enter last name">

          <?php if ( isset( $arrow_errors['last_name'] ) ) : ?>
            <span class="text-sm block mt-1 text-red-100"><?php echo $arrow_errors['last_name']; ?></span>
          <?php endif; ?>
        </div>

        <div class="mb-4">
          <label class="gfield_label text-sm" for="email">Email</label>
          <input class="<?php echo ( isset( $arrow_errors['email'] ) ? 'error-field' : '' ) ?>" id="email" name="email" type="email" value="<?php echo ( $_POST && isset( $_POST['email'] ) ? $_POST['email'] : '' ); ?>" placeholder="Enter email">

          <?php if ( isset( $arrow_errors['email'] ) ) : ?>
            <span class="text-sm block mt-1 text-red-100"><?php echo $arrow_errors['email']; ?></span>
          <?php endif; ?>
        </div>

        <div class="mb-12">
          <label class="gfield_label text-sm" for="phone">Phone</label>
          <input class="<?php echo ( isset( $arrow_errors['phone'] ) ? 'error-field' : '' ) ?>" id="phone" name="phone" type="text" value="<?php echo ( $_POST && isset( $_POST['phone'] ) ? $_POST['phone'] : '' ); ?>" placeholder="Enter phone">

          <?php if ( isset( $arrow_errors['phone'] ) ) : ?>
            <span class="text-sm block mt-1 text-red-100"><?php echo $arrow_errors['phone']; ?></span>
          <?php endif; ?>
        </div>

        <!-- company name -->
        <div class="mb-12">
          <label class="gfield_label text-sm" for="company">Company Name</label>
          <input class="company" id="company" name="company" type="text" value="<?php echo ( $_POST && isset( $_POST['company'] ) ? $_POST['company'] : '' ); ?>" placeholder="Enter Company Name">
        </div>

        <h2 class="hdg-2 text-lg md:text-xl mt-6 mb-6">Preferences</h2>
        <!-- language preference -->
        <div class="mb-12">
          <label class="gfield_label text-sm" for="languagePreference">Language Preference</label>
          <select  class="languagePreference" id="languagePreference" name="languagePreference">
                <?php
                    $languages = array(
                         'NONE' => 'Select Language',
                         '01' => 'English',
                         '02' => 'Albanian',
                         '03' => 'Amharic',
                         '31' => 'Arabic',
                         '04' => 'Bosnian',
                         '05' => 'Bulgarian',
                         '06' => 'Chinese',
                         '07' => 'Croation',
                         '08' => 'Darl',
                         '09' => 'Farsi',
                         '10' => 'French',
                         '11' => 'German',
                         '12' => 'Greek',
                         '13' => 'Hindi',
                         '14' => 'Italian',
                         '15' => 'Japanese',
                         '16' => 'Macedonian',
                         '17' => 'Mandarin',
                         '18' => 'Polish',
                         '19' => 'Portuguese',
                         '20' => 'Punjabi',
                         '21' => 'Russian',
                         '22' => 'Serbian',
                         '23' => 'Somali',
                         '24' => 'Spanish',
                         '25' => 'Swahili',
                         '26' => 'Tigrinya',
                         '27' => 'Turkish',
                         '28' => 'Ukraine',
                         '29' => 'Urdu',
                         '30' => 'Vietnamese'
                    );
                    $selected = $_POST && isset( $_POST['languagePreference'] ) ? $_POST['languagePreference'] : '';
                    $selected_key = $account_user->languagePreference_key;

                    if(in_array($selected, $languages)){
                        foreach ($languages as $code => $label) {
                            echo '<option value="' . $code . '"';
                            if ($selected_key == $code) {
                                $is_matched = true;
                                echo ' selected="selected"';
                            }
                            echo '>' . $label . '</option>';
                        }
                    }else{
                        foreach ($languages as $code => $label) {
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
        </div>
        <!-- brand preference -->
        <div class="mb-12">
          <label class="gfield_label text-sm" for="brand_preference">Brand Preference</label>
          <select  class="" id="brand_preference" name="brand_preference">
                <?php

                    $brands = array(
                        'NONE' => 'Select Brand',
                        'FORD' => 'Ford',
                        'FL' => 'Freightliner',
                        'GMC' => 'GMC',
                        'HINO' => 'Hino',
                        'INTL' => 'International',
                        'ISUZU' => 'Isuzu',
                        'KW' => 'Kenworth',
                        'MACK' => 'Mack',
                        'PETE' => 'Peterbilt',
                        'STERLING' => 'Sterling',
                        'VOLVO' => 'Volvo',
                        'WSTAR' => 'Western Star'
                    );
                    $selected = $_POST && isset( $_POST['brand_preference'] ) ? $_POST['brand_preference'] : '';
                    $selected_key = $account_user->brand_key;

                    if(in_array($selected, $brands)){
                        foreach ($brands as $code => $label) {
                            echo '<option value="' . $code . '"';
                            if ($selected_key == $code) {
                                $is_matched = true;
                                echo ' selected="selected"';
                            }
                            echo '>' . $label . '</option>';
                        }
                    }else{
                        foreach ($brands as $code => $label) {
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
        </div>
        <!-- equipment preference -->
        <div class="mb-12">
          <label class="gfield_label text-sm" for="equipment_preference">Equipment Preference</label>
          <select  class="" id="equipment_preference" name="equipment_preference">
                <?php

                    $equipment = array(
                        'NONE' => 'Select Equipment',
                        'A0' => 'All Sleepers',
                        'A3' => 'Raised Roof Sleeper',
                        'A2' => 'Mid Roof Sleeper',
                        'A1' => 'Flat Roof Sleeper',
                        'P5' => 'C and C',
                        'E1' => 'Day Cab',
                        'OO' => 'Dump Truck',
                        'O5' => 'Roll Off',
                        'K2' => 'Spotter (Yard Dog)',
                        'P1' => 'Straight Truck Dry Van',
                        'P3' => 'Straight Truck Reefer',
                        'P2' => 'Straight Truck Flat',
                        'P6' => 'Straight Truck Moving Van',
                        'T4' => 'Trailer Belly Dump',
                        'T7' => 'Trailer Car Hauler',
                        'T5' => 'Trailer Drop Deck',
                        'TA' => 'Trailer Dry Van',
                        'T8' => 'Trailer End Dump',
                        'T1' => 'Trailer Flat',
                        'T6' => 'Trailer Grain',
                        'T9' => 'Trailer Low Boy',
                        'TP' => 'Trailer Pneumatic',
                        'T2' => 'Trailer Reefer',
                        'T3' => 'Trailer Tanker'
                    );
                    $selected = $_POST && isset( $_POST['equipment_preference'] ) ? $_POST['equipment_preference'] : '';
                    $selected_key = $account_user->equipment_key;

                    if(in_array($selected, $equipment)){
                        foreach ($equipment as $code => $label) {
                            echo '<option value="' . $code . '"';
                            if ($selected_key == $code) {
                                $is_matched = true;
                                echo ' selected="selected"';
                            }
                            echo '>' . $label . '</option>';
                        }
                    }else{
                        foreach ($equipment as $code => $label) {
                            echo '<option value="' . $code . '"';
                            if ('None' == $code) {
                                $is_matched = true;
                                echo ' selected="selected"';
                            }
                            echo '>' . $label . '</option>';
                        }
                    }

                ?>

          </select>
        </div>



        <div class="">
            <h2 class="hdg-2 text-lg md:text-xl mt-6 mb-6">Billing Address</h2>
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

                      foreach ($state as $code => $label) {
                          echo '<option value="' . $code . '"';
                          if ('NONE' == $code) {
                              $is_matched = true;
                              echo ' selected="selected"';
                          }
                          echo '>' . $label . '</option>';
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
        </div>



        <h2 class="hdg-2 text-lg md:text-xl mt-6 mb-6">Password</h2>
        <div class="mb-4">
          <label class="gfield_label text-sm" for="pwd_1">Password</label>
          <input class="<?php echo ( isset( $arrow_errors['pwd_1'] ) ? 'error-field' : '' ) ?>" id="pwd_1" name="pwd_1" type="password" value="<?php echo ( $_POST && isset( $_POST['pwd_1'] ) ? $_POST['pwd_1'] : '' ); ?>" placeholder="Enter password">

          <?php if ( isset( $arrow_errors['pwd_1'] ) ) : ?>
            <span class="text-sm block mt-1 text-red-100"><?php echo $arrow_errors['pwd_1']; ?></span>
          <?php endif; ?>
        </div>

        <div class="mb-4">
          <label class="gfield_label text-sm" for="pwd_2">Confirm Password</label>
          <input class="<?php echo ( isset( $arrow_errors['pwd_2'] ) ? 'error-field' : '' ) ?>" id="pwd_2" name="pwd_2" type="password" value="<?php echo ( $_POST && isset( $_POST['pwd_2'] ) ? $_POST['pwd_2'] : '' ); ?>" placeholder="Re-enter password">

          <?php if ( isset( $arrow_errors['pwd_2'] ) ) : ?>
            <span class="text-sm block mt-1 text-red-100"><?php echo $arrow_errors['pwd_2']; ?></span>
          <?php endif; ?>
        </div>

        <input class="" id="ecommerce_source" name="ecommerce_source" type="hidden" value="C46"/>

        <?php wp_nonce_field( 'arrow-registration', 'arrow-registration-nonce' ); ?>

        <button type="submit" class="btn is-plain text-center w-full" >Create an Account</button>

      </form>

      <div class="wysiwyg mt-12">
        <?php the_content() ?>
      </div>
    </div>
  </section>
<?php endwhile; ?>
