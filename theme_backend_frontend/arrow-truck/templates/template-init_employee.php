<?php
/*
Template Name: Init Employee

*/

date_default_timezone_set('America/Chicago');
$override = false;
$parse = $_GET[ 'parse' ] ?? false;
$run_parse = false;
$response = [
    'sync'     => date( "F j, Y, g:i a", get_transient( ARROW_EMPLOYEE_TRANSIENT . '_timestamp' ) ),
    'run_reps' => get_site_url() . $_SERVER['REQUEST_URI'] . '?parse=true'
];

if ( wp_get_current_user()->data->user_login === "hellojustchad" ) {

    $reps = get_transient( ARROW_EMPLOYEE_TRANSIENT );

    if ( $reps == false ) {
        $run_parse = true;
    }

    if ( $parse == true ) {
        $run_parse = true;
    }

    if ( $run_parse === true || $override === true ) {
        $args = [
            'INIT'          => true,
            'PREFLIGHT'     => true,
            'REQUESTED'     => 'SYSTEM->TEMPLATE_INIT_EMPLOYEE',
            'MODE'          => ARROW_API_ENVIRONMENT
        ];

        ArrowApiEmployee::sync_V2( $args );

        $reps = get_transient( ARROW_EMPLOYEE_TRANSIENT );
    }

    SEE( $response );
    SEE( $reps );

} else {
    wp_redirect( home_url() );
}
