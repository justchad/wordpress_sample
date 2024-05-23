<?php
/*
 * Change the submit button to be an actual button element rather
 * than input submit. This is so we can style the inputs the exact
 * same as other buttons, which often have pseudo elements that aren't
 * allowed on inputs
 */

$GLOBALS['sub'] = array();

function ll_custom_gform_submit( $submit_button, $form ) {

  if(strpos( $form['cssClass'], 'submit-to-arrow' ) !== false ){
      // $subbut = $submit_button . "<span class='status-submit-loader-wrapper-text-former'>Submitting to Arrow..</span>";
      // $subbut = $submit_button;
      $subbut = "<div class='status-submit-container'>";
      $subbut .=   "<button class='btn status-submit' data-formid='gform_{$form['id']}' id='gform_submit_button_{$form['id']}' type='submit'>{$form['button']['text']}</button>";
      $subbut .=   "<span class='status-submit-loader-wrapper gfrom_submit_button_loader{$form['id']} hidden-important'>";
      $subbut .=       "<img id='status-submit-loader' data-url='' src='/wp-content/themes/arrow-truck/assets/img/substatus.gif' />";
      $subbut .=   "Submitting to Arrow..</span>";
      $subbut .= "</div>";
  }
  elseif ( strpos( $form['cssClass'], 'form-skin' ) !== false || strpos( $form['cssClass'], 'inline-form' ) !== false ) {
    $subbut = "<div class='status-submit-container'>";
    $subbut .=   "<button class='btn status-submit' data-formid='gform_{$form['id']}' id='gform_submit_button_{$form['id']}' type='submit'>{$form['button']['text']}</button>";
    $subbut .=   "<span class='status-submit-loader-wrapper gfrom_submit_button_loader{$form['id']} hidden-important'>";
    $subbut .=       "<img id='status-submit-loader' data-url='' src='/wp-content/themes/arrow-truck/assets/img/substatus.gif' />";
    $subbut .=   "Submitting to Arrow..</span>";
    $subbut .= "</div>";
    }else{
        $subbut = $submit_button;
    }

  return $subbut;
}

add_filter( 'gform_submit_button', 'll_custom_gform_submit', 10, 2 );


add_filter( 'gform_ajax_spinner_url', 'spinner_url', 10, 2 );
function spinner_url( $image_src, $form ) {
    if(strpos( $form['cssClass'], 'submit-to-arrow' ) !== false ){
        return "/wp-content/themes/arrow-truck/assets/img/substatus.gif";
    }

        return $image_src;
}

function ll_edit_choice_fields_markup( $field_content, $field ) {

  /*
   * Only continue if we're not on the form editor screen
   * and we're not on the entry screen. This is to ensure
   * we're only editing markup on the front end of the site
   */
  if ( $field->is_entry_detail() || $field->is_form_editor() )
    return $field_content;

  switch ( $field->type ) {
    case 'select':
      /*
       * Add a chevron icon right after select inputs
       */
      $field_content =  str_replace( '</select>', '</select><svg class="icon icon-chevron-down pointer-events-none fill-current select-dropdown-arrow"><use xlink:href="#icon-chevron-down"></use></svg>' , $field_content );
      break;

    /*
     * Add selected / unselected icons for radios and checkboxes
     */
    case 'checkbox':
      if ( $field->choices ) {
        foreach( $field->choices as $field_choice ) {
          $field_content =  str_replace( "{$field_choice['text']}</label>", "<svg class='icon icon-checkbox fill-current'><use xlink:href='#icon-checkbox'></use></svg><svg class='icon icon-checkbox-checked fill-current'><use xlink:href='#icon-checkbox-checked'></use></svg>{$field_choice['text']}</label>" , $field_content );
        }

        $field_content = format_for_list_classes( $field_content, $field );
      }
      break;
    case 'radio':
      if ( $field->choices ) {
        foreach( $field->choices as $field_choice ) {
          $field_content =  str_replace( "{$field_choice['text']}</label>", "<svg class='icon icon-radio fill-current'><use xlink:href='#icon-radio'></use></svg><svg class='icon icon-radio-selected fill-current'><use xlink:href='#icon-radio-selected'></use></svg>{$field_choice['text']}</label>" , $field_content );
        }

        $field_content = format_for_list_classes( $field_content, $field );
      }
      break;

    /*
    * add tailwind layout classes to known complex style inputs
     */
    case 'address':
      /*
       * Currently targeting basic classes. Could add specific classes such as address_city or address_state
       * for more fine controlled layout
       */
      $field_content =  str_replace( 'ginput_full', "w-full" , $field_content );
      $field_content =  str_replace( array('ginput_left','ginput_right'), "w-full md:w-1/2" , $field_content );
      break;
    case 'name':
      /*
       * Could use specific classes such as name_first or name_suffix for finer layout control. Right now
       * we'll just force them all to be w-1/2
       */
      $field_content =  str_replace( array('name_first', 'name_last', 'name_middle', 'name_prefix', 'name_suffix'), "w-full md:w-1/2" , $field_content );
      break;
    default:
      break;
  }

  return $field_content;
}

add_filter( 'gform_field_content', 'll_edit_choice_fields_markup', 10, 2 );

/*
 * Find any layout helper classes and convert them into tailwind width classes
 */
function ll_gform_layout_classes( $field_container, $field, $form, $css_class, $style, $field_content ) {
  $half_options   = array('gfield_half','ginput_left','gf_left_half','gfield_left','ginput_right','gfield_right','gf_right_half');
  $third_options  = array('gfield_third','ginput_third','gf_left_third','gf_middle_third','gf_right_third');
  $fourth_options = array('gfield_fourth','ginput_fourth','gf_first_quarter','gf_second_quarter','gf_third_quarter','gf_fourth_quarter');
  $full_options   = array('gfield_full','ginput_full');

  $field_container = str_replace( $half_options, 'md:w-1/2', $field_container );
  $field_container = str_replace( $third_options, 'md:w-1/3', $field_container );
  $field_container = str_replace( $fourth_options, 'sm:w-1/2 md:w-1/4', $field_container );
  $field_container = str_replace( $full_options, 'md:w-full', $field_container );
  $field_container = str_replace( 'gfield ', 'gfield w-full ', $field_container);
  return $field_container;
}

add_filter( 'gform_field_container', 'll_gform_layout_classes', 10, 6 );

/*
 * Detetcs if any of the column list classes have
 * been applied for styling radio / checkbox inputs
 * https://docs.gravityforms.com/css-ready-classes/#list-classes
 */
function format_for_list_classes( $field_content, $field ) {
  if ( strpos( $field->cssClass, 'gf_list_' ) === false )
    return $field_content;

  $field_content =  str_replace( 'gfield_checkbox', 'gfield_checkbox row flex-wrap', $field_content );
  $field_content =  str_replace( 'gfield_radio', 'gfield_radio row flex-wrap', $field_content );

  if ( strpos( $field->cssClass, 'gf_list_2col' ) !== false ) {
    $field_content =  str_replace( 'gchoice_', 'col w-full sm:w-1/2 gchoice_', $field_content );
  } elseif ( strpos( $field->cssClass, 'gf_list_3col' ) !== false ) {
    $field_content =  str_replace( 'gchoice_', 'col w-full sm:w-1/3 gchoice_', $field_content );
  } elseif ( strpos( $field->cssClass, 'gf_list_4col' ) !== false ) {
    $field_content =  str_replace( 'gchoice_', 'col w-full sm:w-1/2 md:w-1/4 gchoice_', $field_content );
  } elseif ( strpos( $field->cssClass, 'gf_list_5col' ) !== false ) {
    $field_content =  str_replace( 'gchoice_', 'col w-full sm:w-1/5 md:w-1/5 gchoice_', $field_content );
  } elseif ( strpos( $field->cssClass, 'gf_list_inline' ) !== false ) {
    $field_content =  str_replace( 'gchoice_', 'col gchoice_', $field_content );
  }

  return $field_content;
}

add_filter( 'gform_progress_steps', 'progress_steps_markup', 10, 3 );

function progress_steps_markup( $progress_steps, $form, $page ) {
  $pages = $form['pagination']['pages'];

  foreach ( $pages as $key => $single_page ) {
    $progress_steps = str_replace( '>' . ( $key + 1 ) .'<', '>' . str_pad($key + 1, 2, '0', STR_PAD_LEFT) .'<', $progress_steps );
  }

  $progress_steps .= '<div class="current-page-num">' . str_pad($page, 2, '0', STR_PAD_LEFT) . '</div>';

  return $progress_steps;
}

add_filter( 'gform_field_css_class', 'add_gfield_type_class', 10, 3 );

function add_gfield_type_class( $classes, $field, $form ) {
  $classes .= ' ll_gfield_type_' . $field->type;

  if ( $field->type == 'hidden' ) {
    $classes .= ' '.sanitize_title( $field->label );
  }

  return $classes;
}

add_filter( 'gform_field_container', 'add_special_label', 10, 6 );

function add_special_label( $field_container, $field, $form, $css_class, $style, $field_content ) {
  if ( $field->type == 'fileupload' ) {
    $new_field = str_replace( 'ginput_container_fileupload\'>', 'ginput_container_fileupload\'><span class="file-upload-label">No file selected</span><span class="file-upload-button">Browse</span>', $field_content );
    return str_replace( '{FIELD_CONTENT}', $new_field , $field_container );
  }
  return $field_container;
}

function keyExists($key, $array){

    if(array_key_exists($key, $array)){
        return $array[$key];
    }else{
        return null;
    }

}

function getStateFromZip($zip){

    $values = array();

    $thezip = str_replace(' ', '', $zip);

    $ep = 'https://api.zippopotam.us/ca/' . wordwrap($thezip , 3 , ' ' , true );

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => $ep,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
        'Api-Token: 8b04ee16-d643-4353-984d-2be5ab9859af'
      ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    $stateData = json_decode($response);
    $state = $stateData->places[0]->{'state abbreviation'};
    $city = $stateData->places[0]->{'place name'};

    $values['state'] = $state;
    $values['city'] = $city;

    return $values;
}

function sendToArrowApiEstimate($data, $formtype, $sub, $type){

    $formObject = (object) [
        'CTYPE' => $type,
        'TPRICE' => $data[1],
        'Q1' => $data[5],
        'Q2' => $data[6],
        'Q3' => $data[7],
        'STOCK' => $sub['stock'],
        'TMILES' => $sub['mileage']
    ];

    $formSent = array (
        'CTYPE' => $type,
        'TPRICE' => $data[1],
        'Q1' => $data[5],
        'Q2' => $data[6],
        'Q3' => $data[7],
        'STOCK' => $sub['stock'],
        'TMILES' => $sub['mileage']
    );

    $formObjectData = json_encode($formObject);

    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://arrowtruckservices.com/ArrowAPI/api/Calculate',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_POSTFIELDS => $formObjectData,
      CURLOPT_HTTPHEADER => array(
        'Api-Token: 8b04ee16-d643-4353-984d-2be5ab9859af',
        'Content-Type: application/json'
      ),
    ));
    $response = curl_exec($curl);
    $httpStatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    $GLOBALS['sub']['CALCULATION']['DATA_SENT'] = $formSent;

    // var_dump($formObjectData);

    return $response;
}

function key_conversion($entry, $form, $classification) {

    $values = array();
    $info = array();
    $checkbox = array();
    $commentString = "";
    $checkboxString = "";
    $answerString = "";
    $equipmentPreference = null;
    $brandPreference = null;
    $hasCheckbox = false;
    $hasRadio = false;

    $current_user = wp_get_current_user();
    $current_user_id = $current_user->ID;
    $userMeta_equipment = get_user_meta( $current_user_id, 'equipment_preference' );
    $userMeta_brand = get_user_meta( $current_user_id, 'brand_preference' );
    $userMeta_equipment_key = get_user_meta( $current_user_id, 'equipment_key' );
    $userMeta_brand_key = get_user_meta( $current_user_id, 'brand_key' );


    $userMeta_languagePreference = get_user_meta( $current_user_id, 'languagePreference' );
    $userMeta_languagePreference_key = get_user_meta( $current_user_id, 'languagePreference_key' );



    //equipment check
    if($userMeta_equipment_key[0]){
        $equipmentPreference = $userMeta_equipment_key[0];
    }else{
        $equipmentPreference = null;
    }

    //brand check
    if($userMeta_brand_key[0]){
        $brandPreference = $userMeta_brand_key[0];
    }else{
        $brandPreference = null;
    }


    if(isset($_COOKIE['rep'])) {
        $repArray = ll_decode_cookie_handler('rep', true);
        $rep = $repArray[0]->SLSREPNO;
    }else{
        $rep = null;
    }

    $values['salesmanNumber'] = $rep;


    foreach ( $form['fields'] as $field ) {

        $fid = $field->id;
        $adminLabel = $field->inputName;
        $fieldLabel = $field->label;
        $isCheckbox = false;


        if($field->type == 'checkbox'){

            $hasCheckbox = true;
            $fid = $field->id;
            $checkBoxChoices = $field->choices;
            $checkOptionLength = count($checkBoxChoices);

            $checkbox['question']['label'] = $fieldLabel;
            $checkbox['question']['admin_label'] = $adminLabel;

            $checkboxString .= $checkbox['question']['label'] . ': ';

            $c = 0;
            $optionArray = array();
            foreach($checkBoxChoices as $id) {
                $c ++;
                $optionArray[] = $fid . '.' . $c;
            }

            $answerArray = array();
            foreach($optionArray as $oid){
                if($entry[$oid] != ''){
                    $answerArray[] = $entry[$oid];
                }
            }

            $numItems = count($answerArray);
            $i = 0;

            foreach($answerArray as $key => $cbid){
                $checkbox['response'][] = $cbid;
                if(++$i === $numItems) {
                    $checkboxString .= 'and ' . $cbid . '.';
                    $answerString .= 'and ' . $cbid . '.';
                }else {
                    $checkboxString .= $cbid . ', ';
                    $answerString .= $cbid . ', ';
                }
            }

            $commentString .= $checkbox['question']['label'] . ': ' . $answerString . ', ';
        }


        if(substr($adminLabel, 0, 4) == 'info'){
            $field_value = $field->get_value_export( $entry, $field->id, true );
            foreach ( $entry as $key1=>$sub1 ){

                if ($key1 == $fid) {
                    if(!empty($field_value)){
                        $info[str_replace(array('\'', '"'), '', $fieldLabel)] = $field_value;
                        $commentString .= $fieldLabel . ': ' . $field_value . ', ';
                    }
                }
            }
            //checkbox check and add to string
            if($hasCheckbox == true){
                $info[str_replace(array('\'', '"'), '', $checkbox['question']['label'])] = $answerString;
            }
        }else{
            if($field->inputs == null){
                foreach ( $entry as $key2=>$sub2 ){
                    if ($key2 == $fid) {
                        if(!empty($sub2)){

                            if($adminLabel == 'salesmanNumber'){
                                $values[$adminLabel] = $rep;
                            }else{
                                $values[$adminLabel] = $sub2;
                            }

                        }
                    }
                }
            }else{
                foreach ( $field->inputs as $childKey=>$childValue ){
                    $childLabel = $childValue['name'];
                    foreach ( $entry as $key3=>$sub3 ){
                        if ($key3 == $childValue['id']) {
                            if(!empty($sub3)){
                                $values[$childLabel] = $sub3;
                            }
                        }
                    }
                }
            }
        }
    }

    if(count($info) != 0){
        $values['formInfo'] = $info;
    }

    $values['commentString'] = $commentString;
    $values['equipmentPreference'] = $equipmentPreference;
    $values['makePreference'] = $brandPreference;
    $values['formClassification'] = $classification;
    $GLOBALS['sub']['DATA_COLLECTED'] = $values;

    return $values;

}

function sendToArrowApi( $data ){

    $commentSerial = serialize(keyExists('formInfo', $data));
    $ecommerceSource = keyExists('ecommerceSource', $data);
    $email = keyExists('email', $data);
    $originalEmail = keyExists('email', $data);

    if($data['firstName']){
        $firstName = keyExists('firstName', $data);
    }else{
        $firstName = keyExists('firstname', $data);
    }

    if($data['lastName']){
        $lastName = keyExists('lastName', $data);
    }else{
        $lastName = keyExists('lastname', $data);
    }

    $billingStreet = keyExists('billingStreet', $data);
    $billingCity = keyExists('billingCity', $data);
    $billingST = keyExists('billingST', $data);
    $billingZip = keyExists('billingZip', $data);
    $comments = keyExists('commentString', $data);
    $salesmanNumber = keyExists('salesmanNumber', $data);;
    $equipmentPreference = keyExists('equipmentPreference', $data);
    $makePreference = keyExists('makePreference', $data);
    $phone = preg_replace('/[^0-9]/', '', preg_replace('/[^0-9]/', '', keyExists('phone', $data)));
    $price = keyExists('price', $data);
    $formName = keyExists('formClassification', $data);

    if($billingZip == null){
        $state = null;
        $city = null;
    }else{
        if($billingST == null){
            $state = getStateFromZip($billingZip)['state'];
        }else{
            $state = $billingST;
        }
        if($billingCity == null){
            $city = getStateFromZip($billingZip)['city'];
        }else{
            $city = $billingCity;
        }
    }

    $formObject = (object) [
        'email' => $email,
        'originalEmail' => $originalEmail,
        'firstName' => $firstName,
        'lastName' => $lastName,
        'billingStreet' => $billingStreet,
        'billingCity' => $city,
        'billingST' => $state,
        'billingZip' => $billingZip,
        'phone' => $phone,
        'ecommerceSource' => $ecommerceSource,
        'comments' => $comments,
        'equipmentPreference' => $equipmentPreference,
        'makePreference' => $makePreference,
        'salesmanNumber' => $salesmanNumber,
    ];

    $formSent = array (
        'email' => $email,
        'originalEmail' => $originalEmail,
        'firstName' => $firstName,
        'lastName' => $lastName,
        'billingStreet' => $billingStreet,
        'billingCity' => $city,
        'billingST' => $state,
        'billingZip' => $billingZip,
        'phone' => $phone,
        'ecommerceSource' => $ecommerceSource,
        'comments' => $comments,
        'equipmentPreference' => $equipmentPreference,
        'makePreference' => $makePreference,
        'salesmanNumber' => $salesmanNumber,
    );

    $formObjectData = json_encode($formObject);

    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://arrowtruckservices.com/arrowapi2/api/account',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => $formObjectData,
      CURLOPT_HTTPHEADER => array(
        'Api-Token: ab3c8c16708986299980187b990b3aa07362008d61d6ce1e8c9982ed34d721cc41dd610d',
        'Content-Type: application/json'
      ),
    ));
    $response = curl_exec($curl);
    $httpStatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    $GLOBALS['sub']['ACTIVITY']['DATA_SENT'] = $formSent;

    return $response;
}

function sendToArrowApiWarranty( $data ){

    $email = keyExists('email', $data);

    if($data['firstName']){
        $firstName = keyExists('firstName', $data);
    }else{
        $firstName = keyExists('firstname', $data);
    }

    if($data['lastName']){
        $lastName = keyExists('lastName', $data);
    }else{
        $lastName = keyExists('lastname', $data);
    }

    $comments = keyExists('commentString', $data);
    $phone = preg_replace('/[^0-9]/', '', preg_replace('/[^0-9]/', '', keyExists('phone', $data)));
    $vin = keyExists('vin', $data);

    $formObject = (object) [
        'email' => $email,
        'firstName' => $firstName,
        'lastName' => $lastName,
        'phone' => $phone,
        'vin' => $vin,
        'comments' => $comments
    ];

    $formSent = array (
        'email' => $email,
        'firstName' => $firstName,
        'lastName' => $lastName,
        'phone' => $phone,
        'vin' => $vin,
        'comments' => $comments
    );

    $formObjectData = json_encode($formObject);

    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://arrowtruckservices.com/arrowapi2/api/warranty',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => $formObjectData,
      CURLOPT_HTTPHEADER => array(
        'Api-Token: ab3c8c16708986299980187b990b3aa07362008d61d6ce1e8c9982ed34d721cc41dd610d',
        'Content-Type: application/json'
      ),
    ));
    $response = curl_exec($curl);
    $httpStatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    $GLOBALS['sub']['ACTIVITY']['DATA_SENT'] = $formSent;

    return $response;
}

function convertState($name) {
   $states = array(
      array('name'=>'Alabama', 'abbr'=>'AL'),
      array('name'=>'Alaska', 'abbr'=>'AK'),
      array('name'=>'Arizona', 'abbr'=>'AZ'),
      array('name'=>'Arkansas', 'abbr'=>'AR'),
      array('name'=>'California', 'abbr'=>'CA'),
      array('name'=>'Colorado', 'abbr'=>'CO'),
      array('name'=>'Connecticut', 'abbr'=>'CT'),
      array('name'=>'Delaware', 'abbr'=>'DE'),
      array('name'=>'Florida', 'abbr'=>'FL'),
      array('name'=>'Georgia', 'abbr'=>'GA'),
      array('name'=>'Hawaii', 'abbr'=>'HI'),
      array('name'=>'Idaho', 'abbr'=>'ID'),
      array('name'=>'Illinois', 'abbr'=>'IL'),
      array('name'=>'Indiana', 'abbr'=>'IN'),
      array('name'=>'Iowa', 'abbr'=>'IA'),
      array('name'=>'Kansas', 'abbr'=>'KS'),
      array('name'=>'Kentucky', 'abbr'=>'KY'),
      array('name'=>'Louisiana', 'abbr'=>'LA'),
      array('name'=>'Maine', 'abbr'=>'ME'),
      array('name'=>'Maryland', 'abbr'=>'MD'),
      array('name'=>'Massachusetts', 'abbr'=>'MA'),
      array('name'=>'Michigan', 'abbr'=>'MI'),
      array('name'=>'Minnesota', 'abbr'=>'MN'),
      array('name'=>'Mississippi', 'abbr'=>'MS'),
      array('name'=>'Missouri', 'abbr'=>'MO'),
      array('name'=>'Montana', 'abbr'=>'MT'),
      array('name'=>'Nebraska', 'abbr'=>'NE'),
      array('name'=>'Nevada', 'abbr'=>'NV'),
      array('name'=>'New Hampshire', 'abbr'=>'NH'),
      array('name'=>'New Jersey', 'abbr'=>'NJ'),
      array('name'=>'New Mexico', 'abbr'=>'NM'),
      array('name'=>'New York', 'abbr'=>'NY'),
      array('name'=>'North Carolina', 'abbr'=>'NC'),
      array('name'=>'North Dakota', 'abbr'=>'ND'),
      array('name'=>'Ohio', 'abbr'=>'OH'),
      array('name'=>'Oklahoma', 'abbr'=>'OK'),
      array('name'=>'Oregon', 'abbr'=>'OR'),
      array('name'=>'Pennsylvania', 'abbr'=>'PA'),
      array('name'=>'Rhode Island', 'abbr'=>'RI'),
      array('name'=>'South Carolina', 'abbr'=>'SC'),
      array('name'=>'South Dakota', 'abbr'=>'SD'),
      array('name'=>'Tennessee', 'abbr'=>'TN'),
      array('name'=>'Texas', 'abbr'=>'TX'),
      array('name'=>'Utah', 'abbr'=>'UT'),
      array('name'=>'Vermont', 'abbr'=>'VT'),
      array('name'=>'Virginia', 'abbr'=>'VA'),
      array('name'=>'Washington', 'abbr'=>'WA'),
      array('name'=>'West Virginia', 'abbr'=>'WV'),
      array('name'=>'Wisconsin', 'abbr'=>'WI'),
      array('name'=>'Wyoming', 'abbr'=>'WY'),
      array('name'=>'Virgin Islands', 'abbr'=>'V.I.'),
      array('name'=>'Guam', 'abbr'=>'GU'),
      array('name'=>'Puerto Rico', 'abbr'=>'PR')
   );

   $return = false;
   $strlen = strlen($name);

   foreach ($states as $state) :
      if ($strlen < 2) {
         return false;
      } else if ($strlen == 2) {
         if (strtolower($state['abbr']) == strtolower($name)) {
            $return = $state['name'];
            break;
         }
      } else {
         if (strtolower($state['name']) == strtolower($name)) {
            $return = strtoupper($state['abbr']);
            break;
         }
      }
   endforeach;

   return $return;
} // end function convertState()

add_filter('gform_field_value_phone', function($value) {

    $current_user = wp_get_current_user();
    $current_user_id = $current_user->ID;
    $userMeta = get_user_meta( $current_user_id );
    $userMetaPhone = get_user_meta( $current_user_id, 'phone' );

    if( isset( $userMetaPhone[0] ) ){
        return $userMetaPhone[0];
    }

    return false;
});

add_filter('gform_field_value_email', function($value) {

    $current_user = wp_get_current_user();

    return $current_user->user_email;
});

add_filter('gform_field_value_firstName', function($value) {

    $current_user = wp_get_current_user();
    $current_user_id = $current_user->ID;
    $userMeta = get_user_meta( $current_user_id );
    $userMetaFirstName = get_user_meta( $current_user_id, 'first_name' );

    return $userMetaFirstName[0];
});

add_filter('gform_field_value_lastName', function($value) {

    $current_user = wp_get_current_user();
    $current_user_id = $current_user->ID;
    $userMeta = get_user_meta( $current_user_id );
    $userMetaLastName = get_user_meta( $current_user_id, 'last_name' );

    return $userMetaLastName[0];
});

add_filter('gform_field_value_firstname', function($value) {

    $current_user = wp_get_current_user();
    $current_user_id = $current_user->ID;
    $userMeta = get_user_meta( $current_user_id );
    $userMetaFirstName = get_user_meta( $current_user_id, 'first_name' );

    return $userMetaFirstName[0];
});

add_filter('gform_field_value_lastname', function($value) {

    $current_user = wp_get_current_user();
    $current_user_id = $current_user->ID;
    $userMeta = get_user_meta( $current_user_id );
    $userMetaLastName = get_user_meta( $current_user_id, 'last_name' );

    return $userMetaLastName[0];
});

add_filter('gform_field_value_billingZip', function($value) {

    $current_user = wp_get_current_user();
    $current_user_id = $current_user->ID;
    $userMeta = get_user_meta( $current_user_id );
    $userMetaAddressZip = get_user_meta( $current_user_id, 'address_zip' );


    if( isset( $userMetaAddressZip[0] ) ){
        return $userMetaAddressZip[0];
    }

    return false;
});

add_filter('gform_field_value_billingStreet', function($value) {

    $current_user = wp_get_current_user();
    $current_user_id = $current_user->ID;
    $userMeta = get_user_meta( $current_user_id );
    $userMetaAddressStreet = get_user_meta( $current_user_id, 'address_street' );

    return $userMetaAddressStreet[0];
});

add_filter('gform_field_value_billingCity', function($value) {

    $current_user = wp_get_current_user();
    $current_user_id = $current_user->ID;
    $userMeta = get_user_meta( $current_user_id );
    $userMetaAddressCity = get_user_meta( $current_user_id, 'address_city' );

    return $userMetaAddressCity[0];
});

add_filter('gform_field_value_billingST', function($value) {

    $current_user = wp_get_current_user();
    $current_user_id = $current_user->ID;
    $userMeta = get_user_meta( $current_user_id );
    $userMetaAddressState = get_user_meta( $current_user_id, 'address_state' );
    // var_dump($userMeta);
    // var_dump($userMetaAddressZip[0]);

    return convertState($userMetaAddressState[0]);
});

add_filter( 'gform_confirmation', function( $confirmation, $form, $entry, $ajax ) {


    // {tel_usa}
    // {tel_can}

    if(isset($_COOKIE['fromcanada'])){
        $canada_link = '<a class="iscanadaphone btn my-4 md:my-4 mb-4" href="tel:8553139099" data-arrow-btn="true">(855-313-9099)</a>';
        $usa_link = '<a class="hidden btn my-4 md:my-4 mb-4" href="tel:+18003117144" data-arrow-btn="true">svg class="mr-3 icon icon-phone-alt" aria-hidden="true"><use xlink:href="#icon-phone-alt"></use></svg>Call us</a>';
        // var_error_log('[CANADA]');
    }else{
        $canada_link = '<a class="hidden  iscanadaphone btn my-4 md:my-4 mb-4" href="tel:8553139099" data-arrow-btn="true">(855-313-9099)</a>';
        $usa_link = '<a class="btn my-4 md:my-4 mb-4" href="tel:+18003117144" data-arrow-btn="true">svg class="mr-3 icon icon-phone-alt" aria-hidden="true"><use xlink:href="#icon-phone-alt"></use></svg>Call us</a>';
        // var_error_log('[A_M-ERICA]');
    }

    // <a class="btn my-4 md:my-4 mb-4" href="tel:+18003117144" data-arrow-btn="true"><svg class="mr-3 icon icon-phone-alt" aria-hidden="true"><use xlink:href="#icon-phone-alt"></use></svg>Call us</a>

    $confirmation = str_replace( '{tel_usa}', $usa_link, $confirmation );
    $confirmation = str_replace( '{tel_can}', $canada_link, $confirmation );

    var_error_log('[0] confirmation fired.. Top of confirmation.. TOP');

    var_error_log($confirmation);

    var_error_log('[0] confirmation fired.. Top of confirmation.. BELOW');

    //turns access to the api on or off...
    // $submitToApi = false;
    $submitToApi = true;
    $current_user = wp_get_current_user();
    $current_user_id = $current_user->ID;
    $seeSubmission = get_field( 'see_api_submissions', 'user_' . $current_user_id );

    //sends the entry object for parsing into matched key value array. parses nested and non nested field types.
    $sub = key_conversion($entry, $form, $form['title']);

    //Just a simple switch for testing. It either bypasses all logic or sends logic to the arrow api.
    if($submitToApi == true){

        if ( strpos( $form['cssClass'], 'submit-to-arrow' ) ){

            //pre qualify form may give you downpayment
            //estimation form might give you both. if stock number and milage is set you get both, they have to both be set however to be accurate,
            //This is a special case and has to be sent to a different endpoint for calculation.
            if ( strpos( $form['cssClass'], 'estimate-form' ) ){

                //send to general api
                // $sendEntry = sendToArrowApi($sub);
                $sendType = 'D';
                //send to calc api
                //check to see if stock number and milage is set to a value.
                if (array_key_exists("stock",$sub) && array_key_exists("mileage",$sub)){
                  $sendType = 'M';
                }

                $estimateRequest = sendToArrowApiEstimate($entry, 'estimate-form', $sub, $sendType);

                $downPayment_api_encode = json_encode($estimateRequest);
                $downPayment_api_decode = json_decode($downPayment_api_encode, true);
                $dp_arr = explode("'", $downPayment_api_decode);
                $dp_api = number_format($dp_arr[1], 2);

                if($dp_arr[3] != 'NaN'){
                    $mp_api = number_format($dp_arr[3], 2);
                }else{
                    $mp_api = number_format(0, 2);
                }

                if($sub['firstName']){
                    $parseLink = '/down-payment-estimator/?firstName=' . $sub['firstName'] . '&lastName=' . $sub['lastName'] . '&email=' . $sub['email'] . '&billingZip=' . $sub['billingZip'] . '&phone=' . $sub['phone'] . '';
                }else{
                    $parseLink = '/down-payment-estimator/?firstname=' . $sub['firstname'] . '&lastname=' . $sub['lastname'] . '&email=' . $sub['email'] . '&billingZip=' . $sub['billingZip'] . '&phone=' . $sub['phone'] . '';
                }

                if($dp_api != 0.00 || $mp_api != 0.00){
                    //show positive message
                    $confirmation = str_replace( '{dp_headline}', 'Congratulations!', $confirmation );
                    $confirmation = str_replace( '{dp_content}', 'Based on the information you provided, you can get into this truck with the estimated payments shown below, sales tax not included.', $confirmation );
                    $confirmation = str_replace( '{dp_subheadline}', 'Estimated Payments', $confirmation );
                    $confirmation = str_replace( '{down_payment}', 'Down Payment: $' . $dp_api, $confirmation );

                    if($sendType == 'M'){
                        $confirmation = str_replace( '{monthly_payment}', '<li>Monthly Payment: $' . $mp_api . '</li>', $confirmation );
                    }else{
                        $confirmation = str_replace( '{monthly_payment}', '', $confirmation );
                    }

                    $confirmation = str_replace( '{link_to}', $parseLink, $confirmation );
                }else{
                    //show negative message.
                    $confirmation = str_replace( '{dp_headline}', 'Thank You for Submitting!', $confirmation );
                    $confirmation = str_replace( '{dp_content}', 'Based on this quick analysis, we need more information. You will be contacted by an Arrow representative to further discuss your financing opportunities, or, if you would like to expedite the process, click below to fill out our quick and easy credit application.', $confirmation );
                    $confirmation = str_replace( '{dp_subheadline}', '', $confirmation );
                    $confirmation = str_replace( '{down_payment}', '', $confirmation );

                    if($sendType == 'M'){
                        $confirmation = str_replace( '{monthly_payment}', '', $confirmation );
                    }else{
                        $confirmation = str_replace( '{monthly_payment}', '', $confirmation );
                    }

                    $confirmation = str_replace( '{link_to}', $parseLink, $confirmation );

                    // {tel_usa}
                    // {tel_can}
                    // <a class="btn my-4 md:my-4 mb-4" href="tel:8553139099. " data-arrow-btn="true">(855-313-9099)</a>


                }

                //if permission set
                if($seeSubmission){
                    var_dump($GLOBALS['sub']);
                }

                //ok all good send to general arrow Api
                sendToArrowApi($sub);

                var_error_log('[1] confirmation fired.. Top of confirmation.. TOP');

                var_error_log($confirmation);

                var_error_log('[1] confirmation fired.. Top of confirmation.. BELOW');

                return $confirmation;

            }elseif ( strpos( $form['cssClass'], 'prequalify-form' ) ){

                //send to general api
                // $sendEntry = sendToArrowApi($sub);
                //send to calc api
                $estimateRequest = sendToArrowApiEstimate($entry, 'prequalify-form', $sub, 'M');
                // sendToArrowApi($estimateRequest);

                $downPayment_api_encode = json_encode($estimateRequest);
                $downPayment_api_decode = json_decode($downPayment_api_encode, true);
                $dp_arr = explode("'", $downPayment_api_decode);
                $dp_api = number_format($dp_arr[1], 2);

                if($dp_arr[3] != 'NaN'){
                    $mp_api = number_format($dp_arr[3], 2);
                }else{
                    $mp_api = number_format(0, 2);
                }

                if($sub['firstName']){
                    $parseLink = '/pre-qualify/?firstName=' . $sub['firstName'] . '&lastName=' . $sub['lastName'] . '&email=' . $sub['email'] . '&billingZip=' . $sub['billingZip'] . '&phone=' . $sub['phone'] . '';

                }else{
                    $parseLink = '/pre-qualify/?firstname=' . $sub['firstname'] . '&lastname=' . $sub['lastname'] . '&email=' . $sub['email'] . '&billingZip=' . $sub['billingZip'] . '&phone=' . $sub['phone'] . '';
                }

                if($dp_api != 0.00 || $mp_api != 0.00){
                    //show positive message
                    $confirmation = str_replace( '{dp_headline}', 'Congratulations!', $confirmation );
                    $confirmation = str_replace( '{dp_content}', 'Based on the information you provided, your credit may be approved with an approximate down payment of ' . $dp_api . ' to purchase a check for ', $confirmation );
                    $confirmation = str_replace( '{dp_subheadline}', 'Estimated Payments', $confirmation );
                    $confirmation = str_replace( '{down_payment}', 'Down Payment: $' . $dp_api, $confirmation );
                    $confirmation = str_replace( '{monthly_payment}', 'Monthly Payment: $' . $mp_api, $confirmation );
                    $confirmation = str_replace( '{link_to}', $parseLink, $confirmation );
                }else{
                    //show negative message.
                    $confirmation = str_replace( '{dp_headline}', 'Thank You for Submitting!', $confirmation );
                    $confirmation = str_replace( '{dp_content}', 'Based on this quick analysis, we need more information. You will be contacted by an Arrow representative to further discuss your financing opportunities, or, if you would like to expedite the process, click below to fill out our quick and easy credit application.', $confirmation );
                    $confirmation = str_replace( '{dp_subheadline}', '', $confirmation );
                    $confirmation = str_replace( '{down_payment}', '', $confirmation );
                    $confirmation = str_replace( '{monthly_payment}', '', $confirmation );
                    $confirmation = str_replace( '{link_to}', $parseLink, $confirmation );
                }
                //
                // var_error_log('[2] confirmation fired.. Top of confirmation.. TOP');
                // var_error_log($confirmation);
                // var_error_log('[2] confirmation fired.. Top of confirmation.. BELOW');

                return '<div class="wysiwyg">'.$confirmation.'</div>';

            }else{

                if(strpos( $form['cssClass'], 'warranty-form' )){
                    sendToArrowApiWarranty($sub);
                }else{
                    //send to general api
                    sendToArrowApi($sub);
                }

                if($seeSubmission){
                    var_dump($GLOBALS['sub']);
                }
                // var_error_log('[3] confirmation fired.. Top of confirmation.. TOP');
                // var_error_log($confirmation);
                // var_error_log('[3] confirmation fired.. Top of confirmation.. BELOW');
                return '<div class="wysiwyg">'.$confirmation.'</div>';
            }

        }else{
            if($seeSubmission){
                var_dump($GLOBALS['sub']);
            }
            // var_error_log('[4] confirmation fired.. Top of confirmation.. TOP');
            // var_error_log($confirmation);
            // var_error_log('[4] confirmation fired.. Top of confirmation.. BELOW');
            return '<div class="wysiwyg">'.$confirmation.'</div>';
        }

    }else{
        if($seeSubmission){
            var_dump($GLOBALS['sub']);
        }

        // var_error_log('[5] confirmation fired.. Top of confirmation.. TOP');
        // var_error_log($confirmation);
        // var_error_log('[5] confirmation fired.. Top of confirmation.. BELOW');

        return '<div class="wysiwyg">'.$confirmation.'</div>';
    }

}, 10, 4 );

/**
* Gravity Wiz // Gravity Forms // Skip Pages on Multi-Page Form
* http://gravitywiz.com/2012/05/04/pro-tip-skip-pages-on-multi-page-forms/
*/
add_filter("gform_pre_render", "gform_skip_page");

function gform_skip_page($form) {
  if( !rgpost("is_submit_{$form['id']}") && rgget('form_page') == 2 && $form ) {
    echo '<script type="text/javascript">setTimeout(function(){document.querySelector(".gform_page .gform_next_button").click()},0);</script>';
  }

  return $form;
}
