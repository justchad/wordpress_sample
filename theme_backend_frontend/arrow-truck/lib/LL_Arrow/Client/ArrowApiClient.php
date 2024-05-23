<?php
/*
 * Base API Client for Arrow Trucks various
 * apis. All constants are defined in
 * wp-config
 */


class ArrowApiClient
{
    private $env = ARROW_ENV;
    private $base_lead_url = ARROW_BASE_LEAD_URL;
    private $base_url = ARROW_BASE_URL; // https://arrowtruckservices.com/ArrowAPI/api/
    private $request_headers;
    protected static $_instance = null;
    const USER_AGENT = 'Arrow Trucks PHP API SDK 1.0';

    public static function instance()
    {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function get( $path, $data=[], $params=[] )
    {
        return $this->request( 'GET', $path, $params, $data );
    }

    public function post( $path, $data=[], $params=[] )
    {
        return $this->request( 'POST', $path, $params, $data );
    }

    public function delete( $path, $data=[] )
    {
        return $this->request( 'DELETE', $path, $data );
    }

    public function put( $path, $data=[] )
    {
        return $this->request( 'PUT', $path, $data );
    }

    public function get_language_list()
    {
        $url    = "{$this->base_url}employee/LangList";

        $token = ARROW_INVENTORY_API_KEY;

        $curl = curl_init();

        curl_setopt_array( $curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'Api-Token: ' . $token
            ],
        ]);

        $response = curl_exec( $curl );

        $errorNumber    = curl_errno( $curl );

        $error          = curl_error( $curl );

        curl_close( $curl );

        if ( $errorNumber ) {
            throw new Exception( 'CURL: ' . $error, $errorNumber );
        }

        $response = json_decode( $response, true );

        if ( isset( $response['Message'], $response ) ) {
            $e = new Exception( $response['MessageDetail'] );
            $e->rawResponse = $response['MessageDetail'];
            throw $e;
        }

        return collect( collect( json_decode( $response ) )->first() );
    }

    public function get_employee_by_email( $email )
    {
        if ( ! $email ) {
            return false;
        }

        $url    = "{$this->base_url}employee";

        $token = ARROW_INVENTORY_API_KEY;

        $curl = curl_init();

        curl_setopt_array( $curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS =>"{SLSEMAIL: \"{$email}\"}",
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Api-Token: ' . $token
            ),
        ));

        $response = curl_exec( $curl );

        $errorNumber    = curl_errno( $curl );

        $error          = curl_error( $curl );

        curl_close( $curl );

        if ( $errorNumber ) {
            throw new Exception( 'CURL: ' . $error, $errorNumber );
        }

        $response = json_decode( $response, true );

        if ( isset( $response['Message'], $response ) ) {
            $e = new Exception( $response['MessageDetail'] );
            $e->rawResponse = $response['MessageDetail'];
            throw $e;
        }

        return collect( collect( json_decode( $response ) )->first() );
    }

    public function get_all_employees()
    {
        $url_suffix = ( ARROW_COUNTRY == 'USA') ? "ALL" : "TO";

        $url    = "{$this->base_url}Employee/" . $url_suffix;

        $token = ARROW_INVENTORY_API_KEY;

        $curl = curl_init();

        curl_setopt_array( $curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'Api-Token: ' . $token
            ],
        ]);

        $response = curl_exec( $curl );

        $errorNumber    = curl_errno( $curl );

        $error          = curl_error( $curl );

        curl_close( $curl );

        if ( $errorNumber ) {
            throw new Exception( 'CURL: ' . $error, $errorNumber );
        }

        $response = json_decode( $response, true );

        if ( isset( $response['Message'], $response ) ) {
            $e = new Exception( $response['MessageDetail'] );
            $e->rawResponse = $response['MessageDetail'];
            throw $e;
        }

        return collect( collect( json_decode( $response ) )->first() );
    }

    public function get_all_inventory( $country = 'US' )
    {
        // If canada $country = 'TO'
        $curl = curl_init();

        $url    = "{$this->base_url}search";

        $token = ARROW_INVENTORY_API_KEY;

        curl_setopt_array( $curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS =>'{LOCATION: \'' . $country . '\'} ',
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Api-Token: ' . $token
            ],
        ]);

        $response = curl_exec( $curl );

        $errorNumber    = curl_errno( $curl );

        $error          = curl_error( $curl );

        curl_close( $curl );

        if ( $errorNumber ) {
            throw new Exception( 'CURL: ' . $error, $errorNumber );
        }

        $response = json_decode( $response, true );

        if ( isset( $response['Message'], $response ) ) {
            $e = new Exception( $response['MessageDetail'] );
            $e->rawResponse = $response['MessageDetail'];
            throw $e;
        }

        return collect( collect( json_decode( $response ) )->first() );
    }

    public function get_all_locations( $country = 'US' )
    {
        // If canada $country = 'TO'
        $curl = curl_init();

        $url    = "{$this->base_url}Location/all";

        $token = ARROW_INVENTORY_API_KEY;

        curl_setopt_array( $curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'Api-Token: ' . $token
            ],
        ]);

        $response = curl_exec( $curl );

        $errorNumber    = curl_errno( $curl );

        $error          = curl_error( $curl );

        curl_close( $curl );

        if ( $errorNumber ) {
            throw new Exception( 'CURL: ' . $error, $errorNumber );
        }

        $response = json_decode( $response, true );

        if ( isset( $response['Message'], $response ) ) {
            $e = new Exception( $response['MessageDetail'] );
            $e->rawResponse = $response['MessageDetail'];
            throw $e;
        }

        return collect( collect( json_decode( $response ) )->first() );
    }

    public function get_reps_for_location( $branch_id )
    {
        if ( ! $branch_id ) {
            return false;
        }

        $url = "{$this->base_url}employee";

        $token = ARROW_INVENTORY_API_KEY;

        $curl = curl_init();

        curl_setopt_array( $curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS =>'{BRANCH: \'' . $branch_id . '\'} ',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Api-Token: ' . $token
            ),
        ));

        $response = curl_exec( $curl );

        $errorNumber    = curl_errno( $curl );

        $error          = curl_error( $curl );

        curl_close( $curl );

        if ( $errorNumber ) {
            throw new Exception( 'CURL: ' . $error, $errorNumber );
        }

        $response = json_decode( $response, true );

        if ( isset( $response['Message'], $response ) ) {
            $e = new Exception( $response['MessageDetail'] );
            $e->rawResponse = $response['MessageDetail'];
            throw $e;
        }

        return collect( collect( json_decode( $response ) )->first() );
    }

    public function get_location_reps( $branch_id )
    {
        $id = strtolower( $branch_id );

        $curl = curl_init();

        $url    = "{$this->base_url}employee/{$id}";

        $token = ARROW_INVENTORY_API_KEY;

        curl_setopt_array( $curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'Api-Token: ' . $token
            ],
        ]);

        $response       = curl_exec( $curl );
        $errorNumber    = curl_errno( $curl );
        $error          = curl_error( $curl );

        curl_close( $curl );

        if ( $errorNumber ) {
            throw new Exception( 'CURL: ' . $error, $errorNumber );
        }

        $response = json_decode( $response, true );

        if ( isset( $response['Message'], $response ) ) {
            $e = new Exception( $response['MessageDetail'] );
            $e->rawResponse = $response['MessageDetail'];
            throw $e;
        }

        return collect( collect( json_decode( $response ) )->first() );

    }

    public function get_location_trucks( $branch_id )
    {
        $id = strtolower( $branch_id );
        $url    = "{$this->base_url}search";
        $token = ARROW_INVENTORY_API_KEY;

        $curl = curl_init();
        curl_setopt_array( $curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS =>'{FEATURED: \'RANDOM\', LOCATION: \'' . $id . '\'} ',
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Api-Token: ' . $token
            ],
        ]);
        $response       = curl_exec( $curl );
        $errorNumber    = curl_errno( $curl );
        $error          = curl_error( $curl );

        curl_close( $curl );

        if ( $errorNumber ) {
            throw new Exception( 'CURL: ' . $error, $errorNumber );
        }

        $response = json_decode( $response, true );

        if ( isset( $response['Message'], $response ) ) {
            $e = new Exception( $response['MessageDetail'] );
            $e->rawResponse = $response['MessageDetail'];
            throw $e;
        }

        return collect( collect( json_decode( $response ) )->first() );
    }

    public function get_inventory_details( $stock_number )
    {
        $url    = "{$this->base_url}Inventory/{$stock_number}";

        $token = ARROW_INVENTORY_API_KEY;

        $curl = curl_init();

        curl_setopt_array( $curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'Api-Token: ' . $token
            ],
        ]);

        $response = curl_exec( $curl );

        $errorNumber    = curl_errno( $curl );

        $error          = curl_error( $curl );

        curl_close( $curl );

        if ( $errorNumber ) {
            throw new Exception( 'CURL: ' . $error, $errorNumber );
        }

        $response = json_decode( $response, true );

        if ( isset( $response['Message'], $response ) ) {
            $e = new Exception( $response['MessageDetail'] );
            $e->rawResponse = $response['MessageDetail'];
            throw $e;
        }

        return collect( collect( json_decode( $response ) )->first() );
    }

    public function check()
    {
        $url    = "{$this->base_url}Inventory/CHECK";

        $token = ARROW_INVENTORY_API_KEY;

        $curl   = curl_init();

        curl_setopt_array( $curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'Api-Token: ' . $token
            ],
        ]);

        $response = curl_exec( $curl );

        curl_close( $curl );

        return ( json_decode( $response ) === "200" ) ? true : false;
    }

    private function request( $method, $path, array $params = [], $data = null )
    {
        $url     = trim( $path, '/' );
        $org_url = $url;

        if ( ! empty( $params )) {
            $url .= '?' . http_build_query( $params );
        }

        if ( $url == 'Lead' || $url == 'Account' ) {

            $url = $this->base_lead_url . $url;
            $token = ARROW_LEAD_API_KEY;

        } else {

            $url = $this->base_url . $url;
            $token = ARROW_INVENTORY_API_KEY;

        }

        $curl = curl_init();

        curl_setopt_array( $curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => [
                'Api-Token: ' . $token,
                'Content-Type: application/json'
            ],
        ]);


        if ( $data !== null && ! empty( $data ) ) {
            curl_setopt( $curl, CURLOPT_POSTFIELDS, json_encode( $data ) );
        }

        $response       = curl_exec( $curl );

        $errorNumber    = curl_errno( $curl );

        $error          = curl_error( $curl );

        curl_close( $curl );

        if ( $errorNumber ) {
            throw new Exception( 'CURL: ' . $error, $errorNumber );
        }

        $response = json_decode( $response, true );

        if ( isset( $response['Message'], $response ) ) {
            $e = new Exception( $response['MessageDetail'] );
            $e->rawResponse = $response['MessageDetail'];
            throw $e;
        }

        return collect( collect( json_decode( $response ) )->first() );
    }

    public function is_rest_api_request()
    {
        if ( empty( $_SERVER['REQUEST_URI'] ) ) {
            return false;
        }

        $rest_prefix         = trailingslashit( rest_get_url_prefix() );
        $is_rest_api_request = ( false !== strpos( $_SERVER['REQUEST_URI'], $rest_prefix ) ); // phpcs:disable WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

        return $is_rest_api_request;
    }

    private function is_request( $type )
    {
        switch ( $type ) {
            case 'admin':
                return is_admin();
            case 'ajax':
                return defined( 'DOING_AJAX' );
            case 'cron':
                return defined( 'DOING_CRON' );
            case 'rest':
                return $this->is_rest_api_request();
            case 'frontend':
                return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' ) && ! $this->is_rest_api_request();
        }
    }
}

function Arrow()
{
    return ArrowApiClient::instance();
}
