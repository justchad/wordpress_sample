<?php
/*
Template Name: Init Location

*/

date_default_timezone_set('America/Chicago');
$override = false;
$parse = $_GET[ 'parse' ] ?? false;
$run_parse = false;
$response = [
    'sync'          => date( "F j, Y, g:i a", get_transient( ARROW_LOCATION_TRANSIENT . '_timestamp' ) ),
    'run_locations' => get_site_url() . $_SERVER['REQUEST_URI'] . '?parse=true'
];

if ( wp_get_current_user()->data->user_login === "hellojustchad" ) {

    $locations = get_transient( ARROW_LOCATION_TRANSIENT );

    if ( $locations == false ) {
        $run_parse = true;
    }

    if ( $parse == true ) {
        $run_parse = true;
    }

    if ( $run_parse === true || $override === true ) {
        $args = [
            'INIT'          => true,
            'PREFLIGHT'     => true,
            'REQUESTED'     => 'SYSTEM->TEMPLATE_INIT_LOCATIONS',
            'MODE'          => ARROW_API_ENVIRONMENT
        ];

        ArrowApiLocation::sync_V2( $args );

        $locations = get_transient( ARROW_LOCATION_TRANSIENT );
    }

    SEE( $response );
    SEE( $locations );

} else {
    wp_redirect( home_url() );
}
