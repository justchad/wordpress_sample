<?php

/**
 * Class ArrowApiEmployee
 *
 * @package ArrowApi
 */
class ArrowApiEmployee
{
    const ENDPOINT = '/Employee';

    public static function cron( $method )
    {
        if ( ! $method ) {
            return false;
        }

        if ( ! ARROW_CRON_ENABLED ) {
            return false;
        }

        $args = null;

        $cron = null;

        if ( $method == 'SYNC' ) {
            $args = [
                'INIT'          => true,
                'PREFLIGHT'     => true,
                'REQUESTED'     => 'SYSTEM->CRON',
                'MODE'          => $request['mode'] ?? ARROW_API_ENVIRONMENT // TESTING, LOCAL, DEV, STAGE, PRODUCTION
            ];

            $cron = ArrowApiEmployee::sync_V2( $args );
        }

        if ( $method == 'PURGE' ) {
            $args = [
                'REQUESTED'     => 'SYSTEM->CRON',
                'MODE'          => $request['mode'] ?? ARROW_API_ENVIRONMENT // TESTING, LOCAL, DEV, STAGE, PRODUCTION
            ];

            $cron = ArrowApiEmployee::purge_V2( $args );
        }

        return $cron;
    }

    public static function purge( WP_REST_Request $request )
    {
        if ( ! $request ) {
            var_error_log( "[!] :: Error" );
            var_error_log( "       [...] Arrow API: WP_REST->PURGE. Request object null" );
            return false;
        }

        $request = $request->get_params() ?? null;

        $args = [
            'REQUESTED'     => 'SYSTEM->REST', // ENCODED: [ WP_ID => WordPress User ID, SLS => Arrow Employee SLS Number, NAME => Full Name, EMAIL => User Email ]
            'MODE'          => $request['mode'] ?? ARROW_API_ENVIRONMENT // TESTING, LOCAL, DEV, STAGE, PRODUCTION
        ];

        $purge = ArrowApiEmployee::purge_V2( $args );

        return new WP_REST_Response( $purge, 200 );
    }

    public static function purge_V2( $args )
    {
        $sync_start = microtime(true);

        $purge = [
            'status'    => "OK",
            'message'   => "Employees purged. Employee Transient purged.",
            '_info'     => $args[ 'REQUESTED' ],
            'mode'      => $args[ 'MODE' ]
        ];

        $transient = ARROW_EMPLOYEE_TRANSIENT;
        $transient_timestamp = ARROW_EMPLOYEE_TRANSIENT . '_timestamp';

        delete_transient( $transient_timestamp );
        var_error_log( "[-] :: Deleted" );
        var_error_log( "       [...] Transient '{$transient_timestamp}'" );

        delete_transient( $transient );
        var_error_log( "[-] :: Deleted" );
        var_error_log( "       [...] Transient '{$transient}'" );


        $delete_users = get_users( [ 'role__in' => ARROW_USER_ROLES ] );

        if ( count( $delete_users ) > 0 ) {
            foreach ($delete_users as $delete_user) {
                wp_delete_user( (int) $delete_user->ID, true );
                var_error_log( "[-] :: Deleted" );
                var_error_log( "       [...] User {$delete_user->ID}" );
            }
        } else {
            var_error_log( "[i] :: None Found" );
            var_error_log( "       [...] No users with role(s) ." );
        }

        $sync_end = microtime(true);

        $execution_time = $sync_end - $sync_start;

        $purge[ 'execution_time' ] = $execution_time;

        return $purge;
    }

    public static function sync( WP_REST_Request $request )
    {
        if ( ! $request ) {
            var_error_log( "[!] :: Error" );
            var_error_log( "       [...] Arrow API: WP_REST->SYNC. Request object null" );
            return false;
        }

        $request = $request->get_params();

        $args = [
            'INIT'          => $request['init'] ?? true, // Will delete all records and transients before sync update/create
            'PREFLIGHT'     => $request['preflight'] ?? true,
            'REQUESTED'     => 'SYSTEM->REST', // ENCODED: [ WP_ID => WordPress User ID, SLS => Arrow Employee SLS Number, NAME => Full Name, EMAIL => User Email ]
            'MODE'          => $request['mode'] ?? ARROW_API_ENVIRONMENT // TESTING, LOCAL, DEV, STAGE, PRODUCTION
        ];

        $sync = ArrowApiEmployee::sync_V2( $args );

        return new WP_REST_Response( $sync, 200 );
    }

    public static function sync_V2( $args )
    {
        // $args[ ' INIT' => true, 'PREFLIGHT' => true, 'REQUESTED' => "", 'MODE' => ""  ];
        // INIT: Removes all user and transient records. Creates or inserts new API data into WP and create new transient.
        // PREFLIGHT: Check to make sure the Arrow API is up and responding with a 200 before any more API calls are made.
        // REQUESTED: Passes the requester info to the function. Added to each record.
        // MODE: As set by the ARROW ENVIRONMENT global variable options: TESTING, LOCAL, DEV, STAGE, PRODUCTION
        $sync_start     = microtime(true);

        require_once( ABSPATH . 'wp-admin/includes/user.php' );

        $sync = [
            'status'    => "OK",
            'records' => 0,
            'deleted'   => $args[ 'INIT' ] ? "TRUE" : "FALSE",
            'preflight' => $args[ 'PREFLIGHT' ] ? "TRUE" : "FALSE",
            'message'   => "All employees synced successfully.",
            '_info'     => $args[ 'REQUESTED' ],
            'mode'      => $args[ 'MODE' ]
        ];

        $testing = false;
        if ( $args[ 'MODE' ] == 'TESTING' ) {
            $testing = true; // Development only feature. Shortens processing time, and allows for quicker iteration.
        }

        $network_pass   = true;

        if ( $args[ 'preflight' ] === true ) {
            $network_pass = Arrow()->check(); // Will return true / false if network is available
        }

        if ( ! $network_pass ) {
            $sync[ 'status' ]   = "ERROR";
            $sync[ 'message' ]  = "Arrow API Network unavailable.";
            var_error_log( "[!] :: Employee Sync Error" );
            var_error_log( "       [...] Arrow API Network unavailable." );
            return $sync;
        }


        $vcf = true;
        $vcf_dir = trailingslashit( wp_upload_dir()['basedir'] ) . ARROW_VCARD_DIRECTORY;
        $vcf_link = trailingslashit( wp_upload_dir()['baseurl'] ) . ARROW_VCARD_DIRECTORY;
        if  ( ! wp_mkdir_p( $vcf_dir ) ) {
            var_error_log('VCARD Directoy does not exist and/or can not be created.');
            $vcf = false;
        }

        $transient = ARROW_EMPLOYEE_TRANSIENT;
        $transient_timestamp = ARROW_EMPLOYEE_TRANSIENT . '_timestamp';

        // DELETE USER ACCOUNTS AND TRANSIENT DATA
        if ( $args[ 'INIT' ] == true ) {

            delete_transient( $transient_timestamp );
            var_error_log( "[-] :: Deleted" );
            var_error_log( "       [...] Transient '{$transient_timestamp}'" );

            delete_transient( $transient );
            var_error_log( "[-] :: Deleted" );
            var_error_log( "       [...] Transient '{$transient}'" );


            $delete_users = get_users( [ 'role__in' => ARROW_USER_ROLES ] );

            if ( count( $delete_users ) > 0 ) {
                foreach ($delete_users as $delete_user) {
                    wp_delete_user( (int) $delete_user->ID, true );
                    var_error_log( "[-] :: Deleted" );
                    var_error_log( "       [...] User {$delete_user->ID}" );
                }
            } else {
                var_error_log( "[i] :: None Found" );
                var_error_log( "       [...] No users with role(s) ." );
            }
        }

        $api_employee  = Arrow()->get_all_employees();

        if ( ! isset( $api_employee ) ) {
            // Nothing returned from API
            $sync[ 'status' ]   = "ERROR";
            $sync[ 'message' ]  = "Arrow API Network unavailable.";

            var_error_log( "[!] :: Error" );
            var_error_log( "       [...] Arrow API Network returned no results." );

            return $sync;
        }

        $employee_transient_array = [];

        // Loop through each and format the inventory object
        foreach( $api_employee as $employee_key => $employee_item )
        {
            if ( ! $employee_item ) {
                continue;
            }

            $employee = ArrowParse::employee( $employee_item );

            $employee_data_cleanup = get_user_by_email( $employee->email );
            if( $employee_data_cleanup ){
                wp_delete_user( $employee_data_cleanup->ID );
            }

            if ( ! $employee ) {
                continue;
            }

            $user_name = substr( $employee->email, 0, strpos( $employee->email, '.' ) );
            $user_name = preg_replace( '/[^a-zA-Z]/', '_', $user_name );
            $pass_word = wp_generate_password( 8, false, false );

            $branch_id = $employee->branch_ID;

            if ( $employee->system->multiple_locations === true ) {
                $branch_id      = $employee->system->member_locations;
            }

            $user_meta      = [
                'DATA'                  => null,
                'SLS_NUMBER'            => $employee->ID,
                'BRANCH_ID'             => $branch_id,
                '_LAST_SYNC'            => date( "m-d-Y g:i:s a", $employee->_api->_last_sync ),
                '_NEXT_SYNC'            => date( "m-d-Y g:i:s a", $employee->_api->_next_sync )
            ];

            // INSERT OR UPDATE USER
            $create_user_args = [
                'user_login'    => $user_name,
                'user_pass'     => $pass_word,
                'user_email'    => $employee->email,
                'display_name'  => $employee->name->full,
                'first_name'    => $employee->name->first,
                'last_name'     => $employee->name->last,
                'description'   => $employee->bio,
                'role'          => $employee->role,
                'meta_input'    => $user_meta
            ];

            $create_user = wp_insert_user( $create_user_args );

            // CREATE ERROR
            if ( is_wp_error( $create_user ) ) {
                $employee_error = $create_user->get_error_messages();
                foreach ( $employee_error as $error ) {
                    $error = json_encode( $error );
                    var_error_log( "[!+] :: Error Creating" );
                    var_error_log( "        [...] USER {$create_user}" );
                    var_error_log( "        [...] {$error}" );
                }
                continue;
            }

            $WP_USER = $create_user;

            var_error_log( "[+] :: Created" );
            var_error_log( "       [...] USER {$WP_USER}" );

            $employee->user_ID  = $WP_USER;
            $employee->media    = ArrowParse::set_profile( $WP_USER, $employee->ID );

            if ( $vcf ) {
                $employee->v_card   = ll_safe_encode( ArrowParse::set_v_card( $employee ) );
                $vcf_name    = strtolower( $employee->name->first ) . '_' . strtolower( $employee->name->last ) . '.vcf';
                $vcf_data = ll_safe_decode( $employee->v_card );

                $vcf_data = preg_replace( "#[\r\n]+[[:space:]]+[\r\n]+#", "\n", $vcf_data );
                $vcf_data = preg_replace( "#[\r\n]+#", PHP_EOL, $vcf_data );
                $vcf_data = trim( $vcf_data );

                if ( $employee->v_card ) {
                    $fp = fopen( "{$vcf_dir}/{$vcf_name}", 'w' );
                    fwrite( $fp, $vcf_data );
                    fclose( $fp );
                }

                $employee->v_card_href = "{$vcf_link}/{$vcf_name}";
            }

            $employee->card     = ArrowParse::set_employee_card( $employee,  );

            $employee->WP       = ArrowParse::get_object_user_data( $WP_USER );

            if ( class_exists( 'ACF' ) ) {
                // ACF :: CONTACT CARD FILE
                if ( $vcf ) {
                    update_field( 'contact_card_file', "{$vcf_link}/{$vcf_name}", "user_{$employee->user_ID}" );
                }
                // ACF :: MULTIPLE LOCATIONS REPEATER
                if ( $employee->manager_of ) {
                    update_field( 'current_branch_locations', $employee->manager_of->repeater, "user_{$employee->user_ID}" );
                }
                // ACF :: MULTIPLE LOCATIONS BOOLEAN
                update_field( 'user_multi_location_rep', $employee->system->multiple_locations, "user_{$employee->user_ID}" );
            }

            $employee->_info       = (object) [
                'process'   => "ArrowApiEmployee::sync_V2()",
                'valid'     => date( "m-d-Y g:i:s a", $employee->_api->_last_sync ),
                'expires'   => date( "m-d-Y g:i:s a", $employee->_api->_next_sync ),
                'ttl'       => ARROW_DATA_REFRESH_FREQUENCY_IN_HOURS . " Hours",
                'country'   => ARROW_COUNTRY
            ];

            $employee_key = $employee->ID;

            $employee = ll_safe_encode( $employee );

            update_user_meta( $WP_USER, 'DATA', $employee );

            $employee_transient_array[ $employee_key ] = $employee;

        }

        $sync_end = microtime(true);
        $execution_time = $sync_end - $sync_start;

        $employee_set_transient_array = [
            'count'             => count( $employee_transient_array ),
            'items'             => $employee_transient_array,
            'execution_time'    => $execution_time,
            'requested'         => $args[ 'REQUESTED' ] ?? 'SYSTEM->ADMIN'
        ];

        set_transient( $transient_timestamp, time(), ARROW_TRANSIENT_TTL );
        set_transient( $transient, $employee_set_transient_array, ARROW_TRANSIENT_TTL );

        $sync[ 'records' ] = count( $employee_transient_array );
        $sync[ 'transient' ] = $transient;
        $sync[ 'transient_timestamp' ] = $transient_timestamp;
        $sync[ 'execution_time' ] = $execution_time;

        return $sync;
    }


  public static function getAll( $offset = 0, $limit = 20 )
  {
    $response = Arrow()->get( ArrowApiEmployee::ENDPOINT .'/all' );
    return $response;
  }

  /**
   * GET ArrowApiClient request
   *
   * @param $id
   * @return Collection
   * @throws Exception
   */
  public function getByLocation( $location_code ){
    $response = Arrow()->get( ArrowApiEmployee::ENDPOINT . '/' . $location_code );
    return $response;
  }
}
