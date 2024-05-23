<?php

/**
 * Class ArrowApiLocation
 *
 * @package ArrowApi
 */
class ArrowApiLocation
{
    const ENDPOINT = '/Location';

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

            $cron = ArrowApiLocation::sync_V2( $args );
        }

        if ( $method == 'PURGE' ) {
            $args = [
                'REQUESTED'     => 'SYSTEM->CRON',
                'MODE'          => $request['mode'] ?? ARROW_API_ENVIRONMENT // TESTING, LOCAL, DEV, STAGE, PRODUCTION
            ];

            $cron = ArrowApiLocation::purge_V2( $args );
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

        $purge = ArrowApiLocation::purge_V2( $args );

        return new WP_REST_Response( $purge, 200 );
    }

    public static function purge_V2( $args )
    {
        $sync_start = microtime(true);

        $purge = [
            'status'    => "OK",
            'message'   => "Location Posts purged. Location Transient purged.",
            '_info'     => $args[ 'REQUESTED' ],
            'mode'      => $args[ 'MODE' ]
        ];

        $post_type = ARROW_LOCATION_POST_TYPE;
        $transient = ARROW_LOCATION_TRANSIENT;
        $transient_timestamp = ARROW_LOCATION_TRANSIENT . '_timestamp';

        // DELETE INVENTORY POSTS AND TRANSIENT DATA
        delete_transient( $transient_timestamp );
        var_error_log( "[-] :: Deleted" );
        var_error_log( "       [...] Transient '{$transient_timestamp}'" );

        delete_transient( $transient );
        var_error_log( "[-] :: Deleted" );
        var_error_log( "       [...] Transient '{$transient}'" );

        $refresh_posts = get_posts( [
            'post_type'     => $post_type,
            'numberposts'   => -1
        ] );

        if ( count( $refresh_posts ) > 0 ) {
            foreach ($refresh_posts as $refresh_post) {
                wp_delete_post( (int) $refresh_post->ID, true );
                var_error_log( "[-] :: Deleted" );
                var_error_log( "       [...] Post {$refresh_post->ID}" );
            }
        } else {
            $purge[ 'status' ] = "ERROR";
            $purge[ 'message' ] = "No posts of type '{$post_type}'.";
            var_error_log( "[i] :: None Found" );
            var_error_log( "       [...] No posts of type '{$post_type}'." );
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
            'REQUESTED'     => 'SYSTEM->REST',
            'MODE'          => $request['mode'] ?? ARROW_API_ENVIRONMENT // TESTING, LOCAL, DEV, STAGE, PRODUCTION
        ];

        $sync = ArrowApiLocation::sync_V2( $args );

        return new WP_REST_Response( $sync, 200 );
    }

    public static function sync_V2( $args )
    {
        // $args[ ' INIT' => true, 'PREFLIGHT' => true, 'REQUESTED' => "", 'MODE' => ""  ];
        // INIT: Removes all post and transient records. Creates or inserts new API data into WP and create new transient.
        // PREFLIGHT: Check to make sure the Arrow API is up and responding with a 200 before any more API calls are made.
        // REQUESTED: Passes the requester info to the function. Added to each record.
        // MODE: As set by the ARROW ENVIRONMENT global variable options: TESTING, LOCAL, DEV, STAGE, PRODUCTION
        $sync_start = microtime(true);

        $sync = [
            'status'    => "OK",
            'records'   => 0,
            'deleted'   => $args[ 'INIT' ] ? "TRUE" : "FALSE",
            'preflight' => $args[ 'PREFLIGHT' ] ? "TRUE" : "FALSE",
            'message'   => "All locations synced successfully.",
            '_info'     => $args[ 'REQUESTED' ],
            'mode'      => $args[ 'MODE' ]
        ];

        $testing = false;
        if ( $args[ 'MODE' ] == 'TESTING' ) {
            $testing = true; // Development only feature. Shortens processing time, and allows for quicker iteration.
        }

        $post_type                          = ARROW_LOCATION_POST_TYPE;

        $transient                          = ARROW_LOCATION_TRANSIENT;

        $transient_timestamp                = ARROW_LOCATION_TRANSIENT . '_timestamp';

        $network_pass                       = true; // Set check to true by default. If preflight check is enabled, only a false return will cause an early return.

        // DELETE INVENTORY POSTS AND TRANSIENT DATA
        if ( $args[ 'INIT' ] == true ) {

            delete_transient( $transient_timestamp );
            var_error_log( "[-] :: Deleted" );
            var_error_log( "       [...] Transient '{$transient_timestamp}'" );

            delete_transient( $transient );
            var_error_log( "[-] :: Deleted" );
            var_error_log( "       [...] Transient '{$transient}'" );

            $refresh_posts = get_posts( [
                'post_type'     => $post_type,
                'numberposts'   => -1
            ] );

            if ( count( $refresh_posts ) > 0 ) {
                foreach ($refresh_posts as $refresh_post) {
                    wp_delete_post( (int) $refresh_post->ID, true );
                    var_error_log( "[-] :: Deleted" );
                    var_error_log( "       [...] Post {$refresh_post->ID}" );
                }
            } else {
                var_error_log( "[i] :: None Found" );
                var_error_log( "       [...] No posts of type '{$post_type}'." );
            }
        }

        // PROCESS INVENTORY POSTS AND TRANSIENT DATA
        if ( $args[ 'PREFLIGHT' ] === true ) {
            // Will return true / false if network is available
            $network_pass = Arrow()->check();
        }


        if ( $network_pass === true ) {
            // Arrow Inventory Search API
            $api_location  = Arrow()->get_all_locations( ARROW_COUNTRY === "CAN" ? "TO" : "US" );
        } else {
            // Network unavailable
            $sync[ 'status' ]   = "ERROR";
            $sync[ 'message' ]  = "Arrow API Network unavailable.";

            var_error_log( "[!] :: Error" );
            var_error_log( "       [...] Arrow API Network unavailable." );

            return $sync;
        }

        if ( ! isset( $api_location ) ) {
            // Nothing returned from API
            $sync[ 'status' ]   = "ERROR";
            $sync[ 'message' ]  = "Arrow API Network unavailable.";

            var_error_log( "[!] :: Error" );
            var_error_log( "       [...] Arrow API Network returned no results." );

            return $sync;
        }

        $location_transient_array = [];

        // // Loop through each and format the inventory object
        foreach( $api_location as $location_key => $location_item )
        {
            if ( ! $location_item ) {
                continue;
            }

            if ( $testing === true && $location_key > 1 ) {
                continue;
            }

            $location = ArrowParse::location( $location_item );

            if ( ! $location ) {
                continue;
            }

            if ( $location->country != ARROW_COUNTRY ) {
                continue;
            }

            // POSTS
            $post_name      = str_replace(', ', '-', strtolower( $location->address->city_state ) );

            $post_title_array = explode( ', ', $location->address->city_state );

            $post_title     = $post_title_array[0] . ', ' . strtoupper( $post_title_array[1] );

            $post_content   = ArrowParse::location_details( $location );

            $post_meta      = [
                'DATA'                      => $location,
                'BRANCH_ID'                 => $location->ID,
                '_yoast_wpseo_focuskw'      => $location->seo_meta->meta_keyphrase,
                '_yoast_wpseo_metadesc'     => $location->seo_meta->meta_description,
                '_yoast_wpseo_title'        => $location->seo_meta->meta_title,
                '_yoast_wpseo_meta_author'  => $location->seo_meta->meta_author,
                '_LAST_SYNC'                => date( "m-d-Y g:i:s a", $location->_api->_last_sync ),
                '_NEXT_SYNC'                => date( "m-d-Y g:i:s a", $location->_api->_next_sync )
            ];

            $create_post_args = [
                'ID'            => 0,
                'post_author'   => 1,
                'post_type'     => $post_type,
                'post_title'    => $post_title,
                'post_name'     => $post_name,
                'post_excerpt'  => ArrowParse::format_exerpt( $post_content ),
                'post_content'  => $post_content,
                'meta_input'    => $post_meta,
                'post_status'   => $location->active === true ? 'publish' : 'pending'
            ];

            $create_post = wp_insert_post( $create_post_args, true );

            if ( is_wp_error( $create_post ) ) {
                $post_error = $create_post->get_error_messages();
                foreach ( $post_error as $error ) {
                    var_error_log( "[!+] :: Error Creating" );
                    var_error_log( "        [...] Post {$create_post}" );
                    var_error_log( "        [...] {$error}" );
                }
                continue;
            }

            $WP_POST = $create_post;

            var_error_log( "[+] :: Created" );
            var_error_log( "       [...] Post title, post name, post content and post meta. ({$WP_POST})" );

            // WP
            $location->WP = ArrowParse::get_object_post_data( $WP_POST );

            // ACF
            if ( class_exists( 'ACF' ) ) {
                update_field(
                    'location_address',
                    [
                        'street'    => $location->address->line_1,
                        'city'      => $location->address->city,
                        'state'     => $location->address->state,
                        'zip'       => $location->address->zip
                    ],
                    $WP_POST
                );
                update_field(
                    'location_coordinates',
                    [
                        'lat'   => $location->address->geo->latitude,
                        'long'  => $location->address->geo->longitude
                    ],
                    $WP_POST
                );
                update_field(
                    'location_hours',
                    [
                        'weekdays'  => $location->hours->weekdays,
                        'saturday'  => $location->hours->saturday,
                        'sunday'    => $location->hours->sunday,
                    ],
                    $WP_POST
                );
                update_field( 'location_directions', $location->address->exit, $WP_POST );
                update_field( 'location_phone_number', $location->contact->phone_1, $WP_POST );
                update_field( 'location_zip', $location->address->zip, $WP_POST );
                update_field( 'location_content', $post_content, $WP_POST );
                update_field( 'location_languages', $location->languages, $WP_POST );
                $location->about_and_seo = ll_safe_decode( $location->about_and_seo );
                update_field(
                    'location_about_seo',
                    [
                        'title'                                 => $location->about_and_seo->title,
                        'about_section_1'                       => $location->about_and_seo->about_1,
                        'about_section_2'                       => $location->about_and_seo->about_2,
                        'about_section_3'                       => $location->about_and_seo->about_3,
                        'about_section_4'                       => $location->about_and_seo->about_4,
                        'about_section_seo_title'               => "{$location->address->city_state} Semi Trucks for Sale | Arrow Truck Sales, Inc.",
                        'about_section_seo_meta_description'    => "Looking to buy new and used semi trucks in {$location->address->city_state}? Visit Arrow Truck Sales, INC today!",
                        'about_section_seo_meta_author'         => "Arrow Truck Sales"
                    ],
                    $WP_POST
                );
                $location->about_and_seo = ll_safe_encode( $location->about_and_seo );
            }

            $location->_info       = (object) [
                'process'   => "ArrowApiLocation::sync_V2()",
                'valid'     => date( "m-d-Y g:i:s a", $location->_api->_last_sync ),
                'expires'   => date( "m-d-Y g:i:s a", $location->_api->_next_sync ),
                'ttl'       => ARROW_DATA_REFRESH_FREQUENCY_IN_HOURS . " Hours",
                'country'   => ARROW_COUNTRY
            ];

            $location_key = $location->ID;

            $location = ll_safe_encode( $location );

            update_post_meta( $WP_POST, 'DATA', $location );

            $location_transient_array[ $location_key ] = $location;
        }

        $sync_end = microtime(true);
        $execution_time = $sync_end - $sync_start;

        $location_set_transient_array = [
            'count'             => count( $location_transient_array ),
            'items'             => $location_transient_array,
            'execution_time'    => $execution_time,
            'requested'         => $args[ 'REQUESTED' ] ?? 'SYSTEM->ADMIN'
        ];

        set_transient( $transient_timestamp, time(), ARROW_TRANSIENT_TTL );
        set_transient( $transient, $location_set_transient_array, ARROW_TRANSIENT_TTL );

        $sync[ 'records' ] = count( $location_transient_array );
        $sync[ 'transient' ] = $transient;
        $sync[ 'transient_timestamp' ] = $transient_timestamp;
        $sync[ 'execution_time' ] = $execution_time;

        return $sync;
    }

  /**
   * GET ArrowApiClient request
   *
   * @param int $offset
   * @param int $limit
   * @return Collection
   * @throws Exception
   */
  public static function getAll( $offset = 0, $limit = 20 )
  {
    $response = Arrow()->get( ArrowApiLocation::ENDPOINT .'/all' );
    return $response;
  }

  /**
   * GET ArrowApiClient request
   *
   * @param $id
   * @return Collection
   * @throws Exception
   */
  public function get( $location_code )
  {
    $response = Arrow()->get( ArrowApiLocation::ENDPOINT . '/' . $location_code );
    return $response;
  }

}
