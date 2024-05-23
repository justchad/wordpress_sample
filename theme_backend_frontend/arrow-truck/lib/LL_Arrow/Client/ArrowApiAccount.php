<?php

/**
 * Class ArrowApiInventory
 *
 * @package ArrowApi
 */
class ArrowApiAccount
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
  public static function accountRequest( $data )
  {

      // var_error_log('>>------------------------------------->-> UPDATE <<-------------------------------------<-<');
      // var_error_log($data);

        $response = Arrow()->post( ArrowApiAccount::ENDPOINT, $data );
        header("Refresh: 0");
        return $response;
        // header("Refresh: 0");
        // return true;
  }
}

add_action( 'arrow_account_request', function( $user_id, $old_email=false ) {

    $user = get_user_by( 'id', $user_id );
  if ( !$user )
    return;

  $user = new LL_WP_User( $user );

  if ( !$old_email ) {
    $old_email = $user->user_email;
  }

  if($user->brand_key == 'NONE'){
      $user_brand_key = null;
  }else{
      $user_brand_key = $user->brand_key;
  }

  if($user->equipment_key == 'NONE'){
      $user_equipment_key = null;
  }else{
      $user_equipment_key = $user->equipment_key;
  }

  if($user->languagePreference_key == 'NONE'){
      $user_languagePreference_key = null;
  }else{
      $user_languagePreference_key = $user->languagePreference_key;
  }

  ArrowApiAccount::accountRequest( [
    'email'         => $user->user_email,
    'originalEmail' => $old_email,
    'firstName'     => $user->first_name,
    'lastName'      => $user->last_name,
    'billingStreet' => $user->address_street,
    'billingCity'   => $user->address_city,
    'billingST'     => $user->address_state,
    'billingZip'    => $user->address_zip,
    'phone'         => preg_replace('/[^0-9]/', '', strip_phone( $user->phone )),
    'company'       => $user->company,
    'ecommerceSource' => 'UPD',
    'makePreference' => $user_brand_key,
    'equipmentPreference' => $user_equipment_key,
    'languagePreference' => $user_languagePreference_key,
  ] );
}, 10, 2 );


add_action( 'arrow_new_user_request', function( $data, $other_data=[] ) {

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

    ArrowApiAccount::accountRequest( [
            'firstName'       => $data['firstName'],
            'originalEmail'   => null,
            'lastName'        => $data['lastName'],
            'email'           => $data['email'],
            'company'         => $data['company'],
            'phone'           => preg_replace('/[^0-9]/', '', strip_phone( $data['phone'] )),
            'billingStreet'   => $data['billingStreet'],
            'billingStreet2'  => $data['billingStreet2'],
            'billingCity'     => $data['billingCity'],
            'billingST'       => $data['billingST'],
            'billingZip'      => $data['billingZip'],
            'billingCountry'  => $data['billingCountry'],
            'ecommerceSource' => 'C46',
            'comments'        => null,
            'salesmanNumber'  => $data['salesmanNumber'],
            'makePreference'  => $user_brand_key,
        'equipmentPreference' => $user_equipment_key,
        'languagePreference'  => $user_languagePreference_key,
    ] );
}, 10, 2 );
