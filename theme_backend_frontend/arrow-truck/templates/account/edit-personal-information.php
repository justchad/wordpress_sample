<div class="min-h-screen">
  <div class="bg-red-400 py-9 bg-red-400 text-white">
    <div class="container grid lg:grid-cols-12 lg:gap-13">
       <div class="lg:col-span-6 lg:col-start-4 flex items-center justify-center relative">
        <a class="absolute left-0 top-1/2 transform -translate-y-1/2 back-btn" href="<?php echo get_the_permalink() ?>?view=personal-information"><span class="sr-only">Back</span> <svg class="icon icon-left-arrow" aria-hidden="true"><use xlink:href="#icon-left-arrow"></use></svg></a>
        <h1 class=" hdg-6 text-white text-center">
          Edit Personal Information
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
        <h2 class="text-lg font-bold text-gray-400">Personal Information</h2>
      </div>
      <form class="form-skin" action="<?php echo get_the_permalink() ?>?view=edit-personal-information" method="post" onSubmit="window.location.reload()">
        <div class="mb-4">
          <label class="gfield_label text-sm" for="first_name">First Name</label>
          <input class="<?php echo ( isset( $arrow_errors['first_name'] ) ? 'error-field' : '' ) ?>" id="first_name" name="first_name" type="text" value="<?php echo ( $_POST && isset( $_POST['first_name'] ) ? $_POST['first_name'] : $account_user->first_name ); ?>" placeholder="Enter first name">

          <?php if ( isset( $arrow_errors['first_name'] ) ) : ?>
           <span class="text-sm block mt-1 text-red-100"><?php echo $arrow_errors['first_name']; ?></span>
          <?php endif; ?>
        </div>

        <div class="mb-4">
          <label class="gfield_label text-sm" for="last_name">Last Name</label>
          <input class="<?php echo ( isset( $arrow_errors['last_name'] ) ? 'error-field' : '' ) ?>" id="last_name" name="last_name" type="text" value="<?php echo ( $_POST && isset( $_POST['last_name'] ) ? $_POST['last_name'] : $account_user->last_name ); ?>" placeholder="Enter last name">

          <?php if ( isset( $arrow_errors['last_name'] ) ) : ?>
           <span class="text-sm block mt-1 text-red-100"><?php echo $arrow_errors['last_name']; ?></span>
          <?php endif; ?>
        </div>

        <div class="mb-4">
          <label class="gfield_label text-sm" for="email">Email</label>
          <input class="<?php echo ( isset( $arrow_errors['email'] ) ? 'error-field' : '' ) ?>" id="email" name="email" type="email" value="<?php echo ( $_POST && isset( $_POST['email'] ) ? $_POST['email'] : $account_user->user_email ); ?>" placeholder="Enter email">

          <?php if ( isset( $arrow_errors['email'] ) ) : ?>
           <span class="text-sm block mt-1 text-red-100"><?php echo $arrow_errors['email']; ?></span>
          <?php endif; ?>
        </div>

        <div class="mb-12">
          <label class="gfield_label text-sm" for="phone">Phone</label>
          <input class="<?php echo ( isset( $arrow_errors['phone'] ) ? 'error-field' : '' ) ?>" id="phone" name="phone" type="text" value="<?php echo ( $_POST && isset( $_POST['phone'] ) ? $_POST['phone'] : strip_phone( $account_user->phone ) ); ?>" placeholder="Enter phone">

          <?php if ( isset( $arrow_errors['phone'] ) ) : ?>
           <span class="text-sm block mt-1 text-red-100"><?php echo $arrow_errors['phone']; ?></span>
          <?php endif; ?>
        </div>

        <div class="mb-12">
          <label class="gfield_label text-sm" for="company">Company Name</label>
          <input class="" id="company" name="company" type="text" value="<?php echo ( $_POST && isset( $_POST['company'] ) ? $_POST['company'] : $account_user->company ); ?>" placeholder="Enter Company Name">
        </div>

        <h2 class="hdg-2 text-lg md:text-xl mt-6 mb-6">Preferences</h2>

        <div class="mb-12">
          <label class="gfield_label text-sm" for="languagePreference">Language Preference</label>
          <select  class="" id="languagePreference" name="languagePreference">
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
                    $selected = $_POST && isset( $_POST['languagePreference'] ) ? $_POST['languagePreference'] : $account_user->languagePreference;
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
                    $selected = $_POST && isset( $_POST['brand_preference'] ) ? $_POST['brand_preference'] : $account_user->brand_preference;
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

                    $selected = $_POST && isset( $_POST['equipment_preference'] ) ? $_POST['equipment_preference'] : $account_user->equipment_preference;
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

        <input class="" id="ecommerce_source" name="ecommerce_source" type="hidden" value="UPD"/>

        <input class="" id="post_source" name="post_source" type="hidden" value="update"/>

        <?php wp_nonce_field( 'arrow-edit-personal-information', 'arrow-edit-personal-information-nonce' ); ?>

        <button class="btn is-plain text-center w-full" type="submit">Save Changes</button>
      </form>
    </section>
  </div>
</div>
