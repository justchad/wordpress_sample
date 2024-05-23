<?php

/**
 * Class ArrowApiInventory
 * /lib/LL_Arrow/Client/ArrowApiInventory.php
 *
 * @package ArrowApi
 */
class ArrowApiInventory
{
    const ENDPOINT_INVENTORY = '/Inventory';
    const ENDPOINT_SEARCH = '/search';
    const OFFSET = 30;
    const UPDATE_ON_SYNC = true;

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

            $cron = ArrowApiInventory::sync_V2( $args );
        }

        if ( $method == 'PURGE' ) {
            $args = [
                'REQUESTED'     => 'SYSTEM->CRON',
                'MODE'          => $request['mode'] ?? ARROW_API_ENVIRONMENT // TESTING, LOCAL, DEV, STAGE, PRODUCTION
            ];

            $cron = ArrowApiInventory::purge_V2( $args );
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

        $purge = ArrowApiInventory::purge_V2( $args );

        return new WP_REST_Response( $purge, 200 );
    }

    public static function purge_V2( $args )
    {
        $sync_start = microtime(true);

        $purge = [
            'status'    => "OK",
            'message'   => "Inventory Posts purged. Inventory Transient purged.",
            '_info'     => $args[ 'REQUESTED' ],
            'mode'      => $args[ 'MODE' ]
        ];

        $post_type = ARROW_INVENTORY_POST_TYPE;
        $transient = ARROW_INVENTORY_TRANSIENT;
        $transient_timestamp = ARROW_INVENTORY_TRANSIENT . '_timestamp';

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

    // public static function sync_V2( WP_REST_Request $request )
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

        $post_type                          = ARROW_INVENTORY_POST_TYPE;

        $transient                          = ARROW_INVENTORY_TRANSIENT;

        $transient_timestamp                = ARROW_INVENTORY_TRANSIENT . '_timestamp';

        $network_pass                       = true; // Set check to true by default. If preflight check is enabled, only a false return will cause an early return.

        if ( $args[ 'MODE' ] == 'TESTING' ) {
            $testing = true; // Development only feature. Shortens processing time, and allows for quicker iteration.
        }

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
            $api_inventory  = Arrow()->get_all_inventory( ARROW_COUNTRY === "CAN" ? "TO" : "US" );
        } else {
            // Network unavailable
            $sync[ 'status' ]   = "ERROR";

            $sync[ 'message' ]  = "Arrow API Network unavailable.";

            var_error_log( "[!] :: Error" );
            var_error_log( "       [...] Arrow API Network unavailable." );
            return $sync;
        }

        if ( ! isset( $api_inventory ) ) {
            // Nothing returned from API
            $sync[ 'status' ]   = "ERROR";

            $sync[ 'message' ]  = "Arrow API Network unavailable.";

            var_error_log( "[!] :: Error" );
            var_error_log( "       [...] Arrow API Network returned no results." );

            return $sync;
        }

        foreach( $api_inventory as $batch_key => $batch_array )
        {
            // Loop through each and format the inventory object
            foreach( $batch_array as $inventory_key => $inventory_item )
            {
                if ( ! $inventory_item ) {
                    continue;
                }

                if ( $testing === true && $inventory_key > 30 ) {
                    continue;
                }

                $inventory = ArrowParse::inventory( $inventory_item );

                if ( ! $inventory ) {
                    continue;
                }

                // POSTS
                $post_name      = $inventory->ID;

                $post_title     = $inventory->title;

                $post_content   = $inventory->details;

                $post_meta      = [
                    'STOCK_NUMBER'  => $inventory->ID,
                    'BRANCH_ID'     => $inventory->branch_ID,
                    'DATA'          => $inventory,
                    'SPECS'         => null,
                    'TYPE'          => $inventory->type,
                    '_LAST_SYNC'    => date( "m-d-Y g:i:s a", $inventory->_api->_last_sync ),
                    '_NEXT_SYNC'    => date( "m-d-Y g:i:s a", $inventory->_api->_next_sync )
                ];

                // INSERT OR UPDATE POST
                $create_post_args = [
                    'ID'            => 0,
                    'post_author'   => 1,
                    'post_type'     => $post_type,
                    'post_title'    => $post_title,
                    'post_name'     => $post_name,
                    'post_excerpt'  => ArrowParse::format_exerpt( $post_content ),
                    'post_content'  => $post_content,
                    'meta_input'    => $post_meta,
                    'post_status'   => $inventory->active === true ? 'publish' : 'pending'
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

                $inventory_details                  = (array) Arrow()->get_inventory_details( $inventory->ID )[0];

                $inventory->specs                   = ArrowParse::set_specs( $inventory_details );

                $inventory->media                   = ArrowParse::set_media( $inventory_details );

                $inventory->system->city            = $inventory_details[ 'IADCITY' ];

                $inventory->system->state           = $inventory_details[ 'IADSTATE' ];

                $inventory->system->media           = $inventory->media;

                $inventory->system->specs           = $inventory->specs;

                $inventory->_api->_raw_detail       = ll_safe_encode( $inventory_details );

                $inventory->post_ID                 = $WP_POST;

                $inventory->WP                      = ArrowParse::get_object_post_data( $WP_POST );

                $inventory->display                 = ArrowParse::set_inventory_display( (array) $inventory_item, $inventory->media->image, $inventory->specs );

                $inventory->card                    = ArrowParse::set_inventory_card( (array) $inventory_item, $inventory->media->image, $WP_POST );

                $inventory->_info       = (object) [
                    'process'   => "ArrowApiLocation::sync_V2()",
                    'valid'     => date( "m-d-Y g:i:s a", $inventory->_api->_last_sync ),
                    'expires'   => date( "m-d-Y g:i:s a", $inventory->_api->_next_sync ),
                    'ttl'       => ARROW_DATA_REFRESH_FREQUENCY_IN_HOURS . " Hours",
                    'country'   => ARROW_COUNTRY
                ];


                $inventory_key = $inventory->ID;

                update_post_meta( $WP_POST, 'SPECS', $inventory->specs );

                $inventory = ll_safe_encode( $inventory );

                update_post_meta( $WP_POST, 'DATA', $inventory );

                $inventory_transient_array[ $inventory_key ] = $inventory;
            }
        }

        $paged_transient = $inventory_transient_array;

        $sync_end = microtime(true);

        $execution_time = $sync_end - $sync_start;

        $inventory_set_transient_array = [
            'count'             => count( $inventory_transient_array ),
            'items'             => $inventory_transient_array,
            'execution_time'    => $execution_time,
            'requested'         => $args[ 'REQUESTED' ] ?? 'SYSTEM->ADMIN'
        ];

        set_transient( $transient_timestamp, time(), ARROW_TRANSIENT_TTL );

        set_transient( $transient, $inventory_set_transient_array, ARROW_TRANSIENT_TTL );

        $sync[ 'records' ] = count( $inventory_transient_array );

        $sync[ 'transient' ] = $transient;

        $sync[ 'transient_timestamp' ] = $transient_timestamp;

        $sync[ 'execution_time' ] = $execution_time;

        return $sync;
    }


    public static function getAll( $offset = 0, $limit = 20 )
    {
        $response = Arrow()->get( ArrowApiInventory::ENDPOINT_INVENTORY .'/all' );
        return $response;
    }

    public static function getTruck( $id )
    {
        $response = Arrow()->get( 'Inventory' . '/' . $id );

        var_error_log('->>----(> Promotion <)----<<-');
        $promo_args = array(
            'post_type' => 'll_promotion',
            'posts_per_page' => -1
        );
        $current_promotions = new WP_Query( $promo_args );

        //Loop through promotions
        $promotions = array();
        foreach($current_promotions->posts as $key => $promo){
            var_error_log('->>----(> Promotion Each <)----<<-');
            // var_error_log($promo);

            if(!$promo->ID){
                continue;
            }

            $p = get_field("promotions_builder", $promo->ID);

            $promo_array = [
                'promotionenabled' => get_field( "promotion_enable_promotion", $promo->ID ),
                'promoid' => $promo->ID,
                'link' => '/commercial-truck-sales', //$promo->guid
                'promotitle' => $promo->post_title,
                'description' => get_field( "promotion_description", $promo->ID ),
                'disclaimer' => get_field( "promotion_disclaimer", $promo->ID ),
                'make' => $p["promo_builder_make"]->name,
                'model' => $p["promo_builder_model"]->name,
                'minmileage' => $p["promo_builder_min_mileage"],
                'maxmileage' => $p["promo_builder_mileage"],
                'minprice' => $p["promo_builder_min_price"],
                'maxprice' => $p["promo_builder_price"],
                'fleetcode' => $p["promo_builder_fleet_code"],
                'minyear' => $p["promo_builder_min_year"],
                'maxyear' => $p["promo_builder_max_year"],
                'startdate' => $p["promo_builder_start_date"],
                'enddate' => $p["promo_builder_end_date"]
            ];

            $promotions[] = $promo_array;
        }

        foreach($response as $k => $t ){

            $promo_array_each = array();

            foreach($promotions as $p_k => $p_v){
                if($p_v['promotionenabled'] === false){
                    continue;
                }

                $currentDate = date('m/d/Y');
                $currentDate = date('m/d/Y', strtotime($currentDate));

                $startDate = date('m/d/Y', strtotime($p_v['startdate']));
                $endDate = date('m/d/Y', strtotime($p_v['enddate']));

                $can_parse_promo = false;

                if (($currentDate >= $startDate) && ($currentDate <= $endDate)){
                    $can_parse_promo = true;
                }else{
                    continue;
                }

                $eligible = false;

                // left off at zero checks..
                if( (int)$p_v["minyear"] != 0 && (int)$p_v["maxyear"] != 0 ){
                    if( (int)$t->YEAR >= (int)$p_v["minyear"] && (int)$t->YEAR <= (int)$p_v["maxyear"] ){
                        var_error_log('->>----(> Promotion: Year (' . (int)$t->YEAR . ') [' . (int)$p_v["minyear"] . '<>' . (int)$p_v["maxyear"] . '] <)----<<-');
                        $eligible = true;
                    }
                }

                if( (int)$p_v["minprice"] != 0 && (int)$p_v["maxprice"] != 0 ){
                    if( (int)$t->PRICE >= (int)$p_v["minprice"] && (int)$t->PRICE <= (int)$p_v["maxprice"]){
                        var_error_log('->>----(> Promotion: Price (' . (int)$t->PRICE . ') [' . (int)$p_v["minprice"] . '<>' . (int)$p_v["maxprice"] . '] <)----<<-');
                        $eligible = true;
                    }
                }

                if( (int)$p_v["minmileage"] != 0 && (int)$p_v["maxmileage"] != 0 ){
                    if( (int)$t->MILEAGE >= (int)$p_v["minmileage"] && (int)$t->MILEAGE <= (int)$p_v["maxmileage"]){
                        var_error_log('->>----(> Promotion: Mileage ( ' . (int)$t->MILEAGE . ' ) [' . (int)$p_v["minmileage"] . '<>' . (int)$p_v["maxmileage"] . '] <)----<<-');
                        $eligible = true;
                    }
                }

                if( $p_v["make"] ){
                    if( $t->MANUFACTURER == $p_v["make"] ){
                        var_error_log('->>----(> Promotion: Make ( ' . $t->MANUFACTURER . ' ) [' . $p_v["make"] . '] <)----<<-');
                        $eligible = true;
                    }
                }

                if( $p_v["model"] ){
                    if( $t->MODEL == $p_v["model"] ){
                        var_error_log('->>----(> Promotion: Model ( ' . $t->MODEL . ' ) [' . $p_v["model"] . '] <)----<<-');
                        $eligible = true;
                    }
                }

                $fleetcodestring = $p_v["fleetcode"];
                $fleet_array = explode(',', $fleetcodestring);

                if( $p_v["fleetcode"] ){
                    if( in_array($t->INVFLEET, $fleet_array) ){
                        var_error_log('->>----(> Promotion: Fleet Code ( ' . $t->INVFLEET . ' ) [' . $p_v["fleetcode"] . '] <)----<<-');
                        $eligible = true;
                    }
                }

                if($eligible == true){
                    $promo_array_each[] = $p_v["promotitle"] . '|' . $p_v["link"];
                }

            }

            $t->PROMO = $promo_array_each;

            if($t->PROMO){
                var_error_log($t);
            }

        }

        return collect( $response->first() );
    }

    public static function getTruckByLocation( $branch_id = null )
    {
        $params = [
            'LOCATION'  => $branch_id
        ];

        $response = Arrow()->get( ArrowApiInventory::ENDPOINT_SEARCH, $params );
        return $response;
    }

    public static function getFeatured()
    {
        $params = [
            'FEATURED'  => 'RANDOM'
        ];

        $response = Arrow()->get( ArrowApiInventory::ENDPOINT_SEARCH, $params );
        return $response;
    }

    public static function getFeaturedTruckByLocation( $branch_id = null )
    {
        $params = [
            'FEATURED'  => 'RANDOM',
            'LOCATION'  => $branch_id
        ];

        $response = Arrow()->get( ArrowApiInventory::ENDPOINT_SEARCH, $params );
        return $response;
    }

    public static function search( $params=[] )
    {
        $response = Arrow()->get( ArrowApiInventory::ENDPOINT_SEARCH, $params );
        return $response;
    }

    public static function search_inventory( $params = [] )
    {
        // [
        //     'INVMAKE'       => null,
        //     'INVMODL'       => null,
        //     'INVYEAR_S'     => null,
        //     'INVYEAR_E'     => null,
        //     'INVMILAG_S'    => null,
        //     'INVMILAG_E'    => null,
        //     'INVPRICE_S'    => null,
        //     'INVPRICE_E'    => null,
        //     'FEATURED'      => null, // "Y", "N"
        //     'RANDOM'        => null, // "Y", "N"
        //     'LOCATION'      => 'US', // AT, CH, CN, DA, FR, FT, HS, JX, KC, NJ, PH, PX, SA, SL, SP, ST, TA, TO, OK, US
        //     'INVFLEET'      => null, // PFC300,TDI002,N135STAR,
        //     'NEWEST'        => null,
        //     'LATELOW'       => null, // "Y", "N"
        //     'INDUSTRY'      => null, // "Y", "N"
        //     'BUDGET'        => null, // "Y", "N"
        //     'OFFERS'        => null, // "Y", "N"
        //     'OUTLET'        => null, // "Y", "N"
        //     'TRAILER'       => null, // T% -> ALL, T2
        // ];

        $params[ 'LOCATION' ] = "US";

        $response = Arrow()->get( ArrowApiInventory::ENDPOINT_SEARCH, $params );
        return $response;
    }

    public static function getModel( $params=[] )
    {
        $response = Arrow()->get( 'Inventory' . '/' . 'MODELS-STOCK' );
        return $response;
    }

    public static function filter( WP_REST_Request $request )
    {

        $request_data = $request->get_params();
        $request_order = $request->get_params();

        $request_params = $request_data['params'];


        var_error_log('->>----(> Promotion <)----<<-');
        $promo_args = array(
            'post_type' => 'll_promotion',
            'posts_per_page' => -1
        );
        $current_promotions = new WP_Query( $promo_args );

        //Loop through promotions
        $promotions = array();
        foreach($current_promotions->posts as $key => $promo){
            var_error_log('->>----(> Promotion Each <)----<<-');
            // var_error_log($promo);

            if(!$promo->ID){
                continue;
            }

            $p = get_field("promotions_builder", $promo->ID);

            $promo_array = [
                'promotionenabled' => get_field( "promotion_enable_promotion", $promo->ID ),
                'promoid' => $promo->ID,
                'link' => '/commercial-truck-sales', //$promo->guid
                'promotitle' => $promo->post_title,
                'description' => get_field( "promotion_description", $promo->ID ),
                'disclaimer' => get_field( "promotion_disclaimer", $promo->ID ),
                'make' => $p["promo_builder_make"]->name,
                'model' => $p["promo_builder_model"]->name,
                'minmileage' => $p["promo_builder_min_mileage"],
                'maxmileage' => $p["promo_builder_mileage"],
                'minprice' => $p["promo_builder_min_price"],
                'maxprice' => $p["promo_builder_price"],
                'fleetcode' => $p["promo_builder_fleet_code"],
                'minyear' => $p["promo_builder_min_year"],
                'maxyear' => $p["promo_builder_max_year"],
                'startdate' => $p["promo_builder_start_date"],
                'enddate' => $p["promo_builder_end_date"]
            ];

            $promotions[] = $promo_array;
        }

        // var_error_log($promotions);

        if ( $request_params['stock-num'] ) {
          $truck_by_stock = get_posts( [
            'post_type' => 'll_inventory',
            'post_status' => 'published',
            'posts_per_page' => 1,
            'meta_key' => 'INVSTKNO',
            'meta_value' => $request_params['stock-num']
          ] );

          if ( $truck_by_stock ) {
            $truck_by_stock = $truck_by_stock[0];

            return new WP_REST_Response( [
              'truck' => $truck_by_stock,
              'link' => get_permalink( $truck_by_stock->ID )
            ], 200 );
          }
        }

        /*
         * remove stock-num from filterable parameters since the api doesn't handle it
         */
        unset( $request_params['stock-num'] );

        $yes_values = ['FEATURED', 'BUDGET', 'NEWEST', 'LATELOW', 'OUTLET', 'OFFERS'];
        $search_params = collect( $request_params )->mapWithKeys( function( $value, $key ) use ( $yes_values ) {
          $key = LL_StringUtil::uppercase( $key );
          if ( in_array( $key, $yes_values ) ) {
            $value = 'Y';
          } elseif ( LL_StringUtil::ends_with( $key, '_E' ) || LL_StringUtil::ends_with($key, '_S') ) {
            if ( !$value ) {
              if ( LL_StringUtil::ends_with( $key, '_E' ) ) {
                $value = '9999999999';
              } else {
                $value = '0';
              }
            } else {
              $value = preg_replace("/[^0-9]/", "", $value );
            }
          } else {
            $value = LL_StringUtil::uppercase( $value );
          }

          return [ $key => $value ];
        } );


        //called here..
        $truck = ArrowApiInventory::search( $search_params );
        $trucks = $truck;
        $result_count = $trucks->count();
        $cookie = ll_safe_encode( $trucks );


        //probably the best place to add my own argument to each truck...
        var_error_log('->>----(> Trucks <)----<<-');

        foreach($trucks as $k => $t ){

            $promo_array_each = array();

            var_error_log($promotions);

            foreach($promotions as $p_k => $p_v){
                if($p_v['promotionenabled'] === false){
                    continue;
                }

                $currentDate = date('m/d/Y');
                $currentDate = date('m/d/Y', strtotime($currentDate));

                $startDate = date('m/d/Y', strtotime($p_v['startdate']));
                $endDate = date('m/d/Y', strtotime($p_v['enddate']));

                $can_parse_promo = false;

                if (($currentDate >= $startDate) && ($currentDate <= $endDate)){
                    $can_parse_promo = true;
                }else{
                    continue;
                }

                $eligible = false;

                // left off at zero checks..
                if( (int)$p_v["minyear"] != 0 && (int)$p_v["maxyear"] != 0 ){
                    if( (int)$t->YEAR >= (int)$p_v["minyear"] && (int)$t->YEAR <= (int)$p_v["maxyear"] ){
                        var_error_log('->>----(> Promotion: Year (' . (int)$t->YEAR . ') [' . (int)$p_v["minyear"] . '<>' . (int)$p_v["maxyear"] . '] <)----<<-');
                        $eligible = true;
                    }
                }

                if( (int)$p_v["minprice"] != 0 && (int)$p_v["maxprice"] != 0 ){
                    if( (int)$t->PRICE >= (int)$p_v["minprice"] && (int)$t->PRICE <= (int)$p_v["maxprice"]){
                        var_error_log('->>----(> Promotion: Price (' . (int)$t->PRICE . ') [' . (int)$p_v["minprice"] . '<>' . (int)$p_v["maxprice"] . '] <)----<<-');
                        $eligible = true;
                    }
                }

                if( (int)$p_v["minmileage"] != 0 && (int)$p_v["maxmileage"] != 0 ){
                    if( (int)$t->MILEAGE >= (int)$p_v["minmileage"] && (int)$t->MILEAGE <= (int)$p_v["maxmileage"]){
                        var_error_log('->>----(> Promotion: Mileage ( ' . (int)$t->MILEAGE . ' ) [' . (int)$p_v["minmileage"] . '<>' . (int)$p_v["maxmileage"] . '] <)----<<-');
                        $eligible = true;
                    }
                }

                if( $p_v["make"] ){
                    if( $t->MANUFACTURER == $p_v["make"] ){
                        var_error_log('->>----(> Promotion: Make ( ' . $t->MANUFACTURER . ' ) [' . $p_v["make"] . '] <)----<<-');
                        $eligible = true;
                    }
                }

                if( $p_v["model"] ){
                    if( $t->MODEL == $p_v["model"] ){
                        var_error_log('->>----(> Promotion: Model ( ' . $t->MODEL . ' ) [' . $p_v["model"] . '] <)----<<-');
                        $eligible = true;
                    }
                }

                $fleetcodestring = $p_v["fleetcode"];
                $fleet_array = explode(',', $fleetcodestring);

                if( $p_v["fleetcode"] ){
                    if( in_array($t->INVFLEET, $fleet_array) ){
                        var_error_log('->>----(> Promotion: Fleet Code ( ' . $t->INVFLEET . ' ) [' . $p_v["fleetcode"] . '] <)----<<-');
                        $eligible = true;
                    }
                }

                if($eligible == true){
                    $promo_array_each[] = $p_v["promotitle"] . '|' . $p_v["link"];
                }

            }

            $t->PROMO = $promo_array_each;

            if($t->PROMO){
                var_error_log($t);
            }

        }


        $trucks = $trucks->chunk( ArrowApiInventory::OFFSET );
        $page_start = 1;
        $page_end = ArrowApiInventory::OFFSET;

        /* Cache data inside a cookie for pagination */
        if ( $result_count > 0  ) {
          $html = ll_include_component(
            'featured-trucks-grid',
            array(
              'mobile_slider'   => false,
              'list'            => false,
              'all_trucks'      => true,
              'trucks'          => $trucks->first(),
            ),
            array(
              'id'   => 'trucks-list-view',
            ),
            true
          );
        } else {
          $html = ll_include_component(
            'featured-trucks-grid',
            array(
              'mobile_slider'   => false,
              'list'            => false,
              'all_trucks'      => true,
              'trucks'          => [],
            ),
            array(
              'id'   => 'trucks-list-view',
            ),
            true
          );
        }

        $results = $page_start . '-' . $page_end;

        return new WP_REST_Response( [
          'response'    => $html,
          'pages'       => $trucks->count(),
          'cache'       => $cookie,
          'count'       => $result_count,
          'results'     => $results,
          'truck_data'  => $trucks,
        ], 200 );
    }

    public static function sort( WP_REST_Request $request )
    {
        $request_data = $request->get_params();

        if ( $request_data['stock-num'] ) {
          $truck_by_stock = get_posts( [
            'post_type' => 'll_inventory',
            'post_status' => 'published',
            'posts_per_page' => 1,
            'meta_key' => 'INVSTKNO',
            'meta_value' => $request_data['stock-num']
          ] );

          if ( $truck_by_stock ) {
            $truck_by_stock = $truck_by_stock[0];

            return new WP_REST_Response( [
              'truck' => $truck_by_stock,
              'link' => get_permalink( $truck_by_stock->ID )
            ], 200 );
          }
        }

        /*
         * remove stock-num from filterable parameters since the api doesn't handle it
         */
        unset( $request_data['stock-num'] );

        $yes_values = ['FEATURED', 'BUDGET', 'NEWEST', 'LATELOW', 'OUTLET', 'OFFERS'];
        $search_params = collect( $request_data )->mapWithKeys( function( $value, $key ) use ( $yes_values ) {
          $key = LL_StringUtil::uppercase( $key );
          if ( in_array( $key, $yes_values ) ) {
            $value = 'Y';
          } elseif ( LL_StringUtil::ends_with( $key, '_E' ) || LL_StringUtil::ends_with($key, '_S') ) {
            if ( !$value ) {
              if ( LL_StringUtil::ends_with( $key, '_E' ) ) {
                $value = '9999999999';
              } else {
                $value = '0';
              }
            } else {
              $value = preg_replace("/[^0-9]/", "", $value );
            }
          } else {
            $value = LL_StringUtil::uppercase( $value );
          }

          return [ $key => $value ];
        } );


        // if(isset($search_params['INVMAKE']) && $search_params['INVMAKE'] != ''){
        //     $truckModels = ArrowApiInventory::getModel( $search_params );
        // }

        $trucks = ArrowApiInventory::search( $search_params );
        $result_count = $trucks->count();
        $cookie = ll_safe_encode( $trucks );
        $trucks = $trucks->chunk( ArrowApiInventory::OFFSET );
        $page_start = 1;
        $page_end = ArrowApiInventory::OFFSET;

        /* Cache data inside a cookie for pagination */
        if ( $result_count > 0  ) {
          $html = ll_include_component(
            'featured-trucks-grid',
            array(
              'mobile_slider'   => false,
              'list'            => false,
              'all_trucks'      => true,
              'trucks'          => $trucks->first(),
            ),
            array(
              'id'   => 'trucks-list-view',
            ),
            true
          );
        } else {
          $html = ll_include_component(
            'featured-trucks-grid',
            array(
              'mobile_slider'   => false,
              'list'            => false,
              'all_trucks'      => true,
              'trucks'          => [],
            ),
            array(
              'id'   => 'trucks-list-view',
            ),
            true
          );
        }

        $results = $page_start . '-' . $page_end;

        return new WP_REST_Response( [
          'response'    => $html,
          'pages'       => $trucks->count(),
          'cache'       => $cookie,
          'count'       => $result_count,
          'results'     => $results,
          'truck_data'  => $trucks,
        ], 200 );
    }

    public static function paginate( WP_REST_Request $request )
    {
        $request_data = $request->get_params();
        $page = $request_data['page'];
        $trucks = ll_safe_decode( $request_data['cache'] );
        //var_error_log($trucks);

        if ( $page > $trucks->chunk( ArrowApiInventory::OFFSET )->count() ) {
          return new WP_REST_Response( [
            'response' => false,
          ], 200 );
        }

        $page_start = ArrowApiInventory::OFFSET * ( $page - 1 ) + 1;
        if ( $page_start <= 0 ) {
            // call search api here if it is page 1
            $page_start = 1;
        }

        $page_end = ArrowApiInventory::OFFSET + $page_start - 1;

        if ( $page_end > $trucks->count() ) {
          $page_end = $trucks->count();
        }

        $results = $page_start . '-' . $page_end;




        $html = ll_include_component(
          'featured-trucks-grid',
          array(
            'mobile_slider'   => false,
            'list'            => false,
            'all_trucks'      => true,
            'trucks'          => $trucks->chunk( ArrowApiInventory::OFFSET )[$page - 1]
          ),
          array(
            'id'   => 'trucks-list-view',
          ),
          true
        );

        return new WP_REST_Response( [
          'response' => $html,
          'results'  => $results,
        ], 200 );
    }

    public static function favorite( WP_REST_Request $request )
    {
        $request_data = $request->get_params();
        $user_id = get_current_user_id();
        $truck = $request_data['truck'];
        $favorites = get_user_meta( $user_id, 'arrow_favorites', true ) ?? [];
        $favorites[] = $truck;
        update_user_meta( $user_id, 'arrow_favorites', $favorites );

        return new WP_REST_Response( [
          'success' => $favorites
        ] );
    }

}



// // IS EXISTING UPDATE
// $existing_post = reset(
//     get_posts( [
//         'post_type'         => $post_type,
//         'posts_per_page'    => 1,
//         'fields'            => 'ids',
//         'meta_key'          => $meta_key,
//         'meta_value'        => $meta_value
//     ] )
// );

// $existing_post_ID = $existing_post ?? 0;
//
// if ( (int) $existing_post_ID !== 0 ) {
//
//     $update_post_args = [
//         'ID'            => $existing_post_ID,
//         'post_author'   => 1,
//         'post_title'    => $post_title,
//         'post_name'     => $post_name,
//         'post_excerpt'  => $post_content,
//         'post_content'  => $post_content
//     ];
//     $update_post = wp_update_post( $update_post_args, true );
//
//     // UPDATE ERROR
//     if ( is_wp_error( $update_post ) ) {
//     	$post_error = $update_post->get_error_messages();
//         foreach ( $post_error as $error ) {
//     		var_error_log( "[!^] :: Error Updating" );
//             var_error_log( "       [...] Post {$existing_post_ID}" );
//             var_error_log( "       [...] {$error}" );
//     	}
//
//         continue;
//     }
//
//     jc_update_post_meta( $existing_post_ID, $post_meta );
//     var_error_log( "[^] :: Updated" );
//     var_error_log( "       [...] Post title, post name, post content and post meta. ({$existing_post_ID})" );
//
//     continue;
// }
