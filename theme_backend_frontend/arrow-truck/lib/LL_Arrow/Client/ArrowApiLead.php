<?php

/**
 * This is only for Leads generated programmatically
 * For leads that come from specific gravity form submissions
 * you can set them up using the Webhook settings within the backend
 * of the form. Use that to map the appropriate fields to the
 * appropriate api field. To get the ecommerceSource and salesmanNumber
 * fields, create them in the form as hidden fields with classes set to
 * ecommerceSource and salesmanNumber respectively.
 */
class ArrowApiLead
{
  const ENDPOINT = '/Account';

  /**
   * GET ArrowApiClient request
   *
   * @param int $offset
   * @param int $limit
   * @return Collection
   * @throws Exception
   */
  public static function sendLead( $data )
  {
        // var_error_log('>->------------------------------------->> ADD <<-------------------------------------<-<');
        // var_error_log($data);
        if($data == null){
            $response = Arrow()->post( ArrowApiLead::ENDPOINT, $data );
            // header("Refresh: 0");
            return $response;
                // return true;
        }

  }
}

add_action( 'arrow_lead_request', function( $data, $other_data=[] ) {

    if($data['makePreference'] == 'NONE'){
        $user_brand_key = null;
    }else{
        $user_brand_key = $data['makePreference'];
    }

    if($data['equipmentPreference'] == 'NONE'){
        $user_equipment_key = null;
    }else{
        $user_equipment_key = $data['equipmentPreference'];
    }

    if($data['languagePreference'] == 'NONE'){
        $user_languagePreference_key = null;
    }else{
        $user_languagePreference_key = $data['languagePreference'];
    }

    ArrowApiLead::sendLead( [
        'firstName'       => $data['firstName'],
        'lastName'        => $data['lastName'],
        'email'           => $data['email'],
        'phone'           => preg_replace('/[^0-9]/', '', strip_phone( $data['phone'] )),
        'billingStreet'   => $data['billingStreet'],
        'billingStreet2'  => $data['billingStreet2'],
        'billingCity' => $data['billingCity'],
        'billingST' => $data['billingST'],
        'billingZip' => $data['billingZip'],
        'billingCountry' => $data['billingCountry'],
        'ecommerceSource' => 'C46',
        'comments'        => null,
        'salesmanNumber'  => $data['salesmanNumber'],
        'makePreference' => $user_brand_key,
        'equipmentPreference' => $user_equipment_key,
        'languagePreference'  => $user_languagePreference_key,
    ] );
}, 10, 2 );
