<?php
/*
Template Name: Init Location

*/

date_default_timezone_set('America/Chicago');
$override = false;
$parse = $_GET[ 'parse' ] ?? false;
$create_dir = $_GET[ 'create_dir' ] ?? false;
$init = $_GET[ 'batch' ] ?? false;
$run_parse = false;
$response = [
    'sync'              => date( "F j, Y, g:i a", get_transient( ARROW_INVENTORY_TRANSIENT . '_timestamp' ) ),
    'file_dir'          => ARROW_PROCESSING_DIRECTORY,
    'run_inventory'     => get_site_url() . $_SERVER['REQUEST_URI'] . '?parse=true',
    'create_directory'  => get_site_url() . $_SERVER['REQUEST_URI'] . '?parse=false&create_dir=true',
    'init_file_write'   => get_site_url() . $_SERVER['REQUEST_URI'] . '?parse=false&create_dir=false&batch=init',
    'batch_runs'        => [],
];

SEE( $response );

if ( wp_get_current_user()->data->user_login === "hellojustchad" ) {


    if ( $create_dir ) {
        $file_directory_check = false;
        // Will check if directory exists
        if  ( wp_mkdir_p( ARROW_PROCESSING_DIRECTORY ) ) {
            var_error_log('VCARD Directoy does not exist and/or can not be created.');
            $file_directory_check = true;
        }

        if( $init == 'init' ){
            $init_files = glob( ARROW_PROCESSING_DIRECTORY . '/*' ); // get all file names
            foreach( $init_files as $init_file ){ // iterate files
              if( is_file( $init_file ) ) {
                unlink( $init_file ); // delete file
              }
            }
        }

        SEE( 'File Dir Check: ' . $file_directory_check );


        if ( true == true ) {
            $inventory_file_count = 0;
            $inventory_data         = Arrow()->get_all_inventory( ARROW_COUNTRY === "CAN" ? "TO" : "US" );
            // SEE( $inventory_data );
            $inventory_file_chunk   = array_chunk( $inventory_data->toArray(), 100 );
            $inventory_file_dir     = ARROW_PROCESSING_DIRECTORY;

            // SEE( $inventory_file_chunk );

            $file_array = [];

            foreach( $inventory_file_chunk as $key => $inventory_file ){

                $folder = trailingslashit( wp_upload_dir()['basedir'] ) . 'Processing';

                SEE( $folder );

                $file   = ARROW_PROCESSING_DIRECTORY_BASE_FILENAME . "{$inventory_file_count}.txt";

                if ( ! file_exists( ARROW_PROCESSING_DIRECTORY_BASE_FILENAME. "_{$inventory_file_count}.txt" ) ) {
                    $fp = fopen( ARROW_PROCESSING_DIRECTORY . "/{$file}", 'w' );
                    fwrite( $fp, $inventory_file );
                    fclose( $fp );
                    $inventory_file_count++;
                    $file_array[] = get_site_url() . $_SERVER['REQUEST_URI'] . '?parse=false&create_dir=false&batch=' . $file;
                }
            }

            $response[ 'batch_runs' ] = $file_array;
        }

        SEE( $response );


    }

    // $inventory = get_transient( ARROW_INVENTORY_TRANSIENT );
    //
    // if ( $inventory == false ) {
    //     $run_parse = true;
    // }
    //
    // if ( $parse == true ) {
    //     $run_parse = true;
    // }
    //
    // if ( $run_parse === true || $override === true ) {
    //     $args = [
    //         'INIT'          => true,
    //         'PREFLIGHT'     => true,
    //         'REQUESTED'     => 'SYSTEM->TEMPLATE_INIT_INVENTORY',
    //         'MODE'          => ARROW_API_ENVIRONMENT
    //     ];
    //
    //     ArrowApiInventory::sync_V2( $args );
    //
    //     $inventory = get_transient( ARROW_INVENTORY_TRANSIENT );
    // }
    //
    // SEE( $response );
    // SEE( $inventory );

} else {
    wp_redirect( home_url() );
}
