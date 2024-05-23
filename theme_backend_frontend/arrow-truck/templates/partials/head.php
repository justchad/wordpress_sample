<?php

    // $rep_list = ArrowSalesRep::getRoles( true );
    // SEE( $rep_list );

?>

<!doctype html>
<html class="no-js antialiased" <?php language_attributes(); ?>>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta name="format-detection" content="telephone=no">

    <!-- Google Tag Manager -->

    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':

    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],

    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=

    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);

    })(window,document,'script','dataLayer','GTM-PXHN8Q3');</script>

<!-- End Google Tag Manager -->

  <?php wp_head(); ?>

  <script type="text/javascript">
      jQuery( document ).ready( function( $ ) {
        $( document ).on( 'gform_post_render', function( event, formId, currentPage ) {
          const salesManInput = document.querySelector( `#gform_${formId} .salesmannumber input` );
          const salesManCookie = getCookie( 'wordpress_salesmanNumber' );
          if ( salesManInput && salesManCookie ) {
            salesManInput.value = salesManCookie;
          }
        } );
      } );
  </script>

  <?php

    if (isset($_GET['rep'])){

        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://arrowtruckservices.com/ArrowAPI/api/employee',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_POSTFIELDS =>'{SLS: "' . $_GET['rep'] . '"}',
        CURLOPT_HTTPHEADER => array(
          'Api-Token: 8b04ee16-d643-4353-984d-2be5ab9859af',
          'Content-Type: application/json'
        ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $res = json_decode($response);
        $response = json_decode($response, true);
        $res = collect( collect( json_decode( $response ) )->first() );
        $resString = json_encode($res);

        $fname = strtolower($res[0]->SLSFNAME);
        $lname = strtolower($res[0]->SLSLNAME);
        $slug = $fname . '-' . $lname;
        $userString = $slug;

        ll_encode_cookie_handler( ll_safe_encode( $resString ), 'rep' );
        ll_encode_cookie_handler( ll_safe_encode( $_GET[ 'rep' ] ), 'rep_no' );
        ll_encode_cookie_handler( $_GET[ 'rep' ], 'rep_no_raw' );
        ll_encode_cookie_handler( ll_safe_encode( $userString ), 'salesrep' );
    }

  ?>

</head>
