<?php

    // echo '<pre>'; print_r( $get_reps ); echo '</pre>';
    // /lib/LL_Arrow/ArrowLocation.php
    /**
     * Class ArrowLocation
     * Builds a ArrowLocation from a WP_Post
     *
     * @package ArrowApi
     */
    class ArrowLocation {

        const sales_rep_photo_URL     = 'https://www.arrowtruckhost.com/images/slsphotos';
        const featured_trucks_LIMIT   = 6;

        public function __construct( WP_Post $wp_post, $include_users = true ) {

            $data = ll_safe_decode( get_field( 'arrow_data', $wp_post->ID ) );

            if ( is_object( $data ) ) {

                $data = get_object_vars( ll_safe_decode( get_post_meta( $wp_post->ID, 'arrow_data', true ) ) );

                array_map( function( $key, $value ) {

                    $this->$key = $value;

                }, array_keys( $data ), $data );

            }

            $this->wp_post = $wp_post;

            $this->address = (object) [
                'street' => get_post_meta( $this->wp_post->ID, 'location_address_street', true ),
                'city'   => get_post_meta( $this->wp_post->ID, 'location_address_city', true ),
                'state'  => get_post_meta( $this->wp_post->ID, 'location_address_state', true ),
                'zip'    => get_post_meta( $this->wp_post->ID, 'location_address_zip', true )
            ];

            $this->promotion = (object) [
                'content' => get_field( 'location_promotion_copy', $this->wp_post->ID ),
                'title'   => get_field( 'location_promotion_headline', $this->wp_post->ID )
            ];

            $this->languages      = $this->get_languages();

            $this->permalink      = get_the_permalink( $this->wp_post->ID );

            $this->addressLink    = ll_map_address( $this->address->street . ', ' . $this->address->city . ', ' . $this->address->state . ' ' . $this->address->zip );

            $this->directions     = get_post_meta( $this->wp_post->ID, 'location_directions', true );

            $this->phone          = strip_phone( get_post_meta( $this->wp_post->ID, 'location_phone_number', true ) );

            $this->inventoryLink  = arrow_page_url( 'search_inventory' ) . '?location='.$this->BRANCH;

            $this->title          = LL_StringUtil::sentance_case( get_post_meta( $this->wp_post->ID, 'branch_title', true ) );

            $this->hours          = ( ll_get_location_hours( $this->wp_post->ID ) ) ? ll_get_location_hours( $this->wp_post->ID ) : null;

            $this->coordinates    = get_field( 'location_coordinates', $this->wp_post->ID );

            $this->rawData        = ll_safe_encode( get_field( 'arrow_data', $wp_post->ID ) );

            $this->salesReps      = [];

            if ( $include_users ) {
                $this->salesReps  = $this->get_reps();
            }

            $this->featuredTrucks = $this->featured_trucks();

            $this->branch_id      = $this->BRANCH;
        }

        public function get_field( $meta_key, $property ) {

            if ( !$this->wp_post->ID )
                return $this->$property;

            if ( !$this->$meta_key ) {

                $value = get_field( $meta_key, $this->wp_post->ID );

            }

            if ( $value ) {

                $this->$meta_key = $value;

                return $value;
            }

            return $this->$property;
        }

        private function get_languages(){

            $response = Arrow()->get( ArrowApiEmployee::ENDPOINT . '/' . strtolower( $this->BRANCH ) );

            $language = [];

            foreach( $response as $user ){

                if ( $user->SLSLANG1 ) {
                    array_push( $language, $user->SLSLANG1 );
                }

                if ( $user->SLSLANG2 ) {
                    array_push( $language, $user->SLSLANG2 );
                }

                if ( $user->SLSLANG3 ) {
                    array_push( $language, $user->SLSLANG3 );
                }

                if ( $user->SLSLANG4 ) {
                    array_push( $language, $user->SLSLANG4 );
                }

                if ( $user->SLSLANG5 ) {
                    array_push( $language, $user->SLSLANG5 );
                }

                if ( $user->SLSLANG6 ) {
                    array_push( $language, $user->SLSLANG6 );
                }

                if( $user->SLSLANG7 ) {
                    array_push( $language, $user->SLSLANG7 );
                }

                if( $user->SLSLANG8 ) {
                    array_push( $language, $user->SLSLANG8 );
                }

                if( $user->SLSLANG9 ) {
                    array_push( $language, $user->SLSLANG9 );
                }

                if( $user->SLSLANG10 ) {
                    array_push( $language, $user->SLSLANG10 );
                }
            }

            return array_unique( $language );
        }

        private function get_reps() {

            $response = Arrow()->get( ArrowApiEmployee::ENDPOINT . '/' . strtolower($this->BRANCH) );

            $get_reps = [];

            foreach( $response as $user ){

                $rep = get_user_by_email( $user->SLSEMAIL );

                if( ! isset( $rep ) ){
                    continue;
                }

                $language = [];

                if ( $user->SLSLANG1 ) {

                    $language = [
                        'language1'     => $user->SLSLANG1,
                        'language2'     => $user->SLSLANG2,
                        'language3'     => $user->SLSLANG3,
                        'language4'     => $user->SLSLANG4,
                        'language5'     => $user->SLSLANG5,
                        'language6'     => $user->SLSLANG6,
                        'language7'     => $user->SLSLANG7,
                        'language8'     => $user->SLSLANG8,
                        'language9'     => $user->SLSLANG9,
                        'language10'    => $user->SLSLANG10
                    ];

                }

                $photo = "/{$user->SLSREPNO}/{$user->SLSREPNO}image1.jpg";

                $sales_rep = (object) [
                    'arrow_data' => $user,
                    'wp_data' => $rep,
                    'the_permalink' => ( isset( $rep->ID ) ) ? get_author_posts_url( $rep->ID ) : null,
                    'image' => ArrowLocation::sales_rep_photo_URL . $photo,
                    'languages' => $language
                ];

                array_push( $get_reps, $sales_rep );
            }

            return $get_reps;
        }

        private function featured_trucks() {

            $post_TYPE = 'll_inventory';

            $truck_post_IDS = get_posts( [
                'posts_per_page'    => -1,
                'post_type'         => $post_TYPE,
                'fields'            => 'ids',
                'meta_key'          => 'LOCATION',
                'meta_value'        => $this->BRANCH
            ] );

            $trucks = [];

            foreach( $truck_post_IDS as $truck_POST_ID ) {

                $truck_POST = get_post( $truck_POST_ID );

                $truck_META = jc_safe_decode( get_post_meta( $truck_POST_ID, 'arrow_data' )[0] );

                $truck = json_decode( $truck_META );

                $truck->LOCATION = $this->BRANCH;

                $truck->POST_DATA = get_post( $truck_POST_ID );

                $truck->PERMALINK = get_permalink( $truck_POST_ID );

                $truck->POST_META = get_post_meta( $truck_POST_ID );

                $trucks[] = $truck;

            }

            $featured_trucks = array_slice( $trucks, 0, (int) ArrowLocation::featured_trucks_LIMIT );

            // echo '<pre>'; print_r( $featured_trucks ); echo '</pre>';

            // $args = [
            //     'LOCATION' => $this->BRANCH
            // ];
            //
            // $featured_trucks_API = ArrowApiInventory::search( $args )->take( ArrowLocation::featured_trucks_LIMIT );

            return $featured_trucks;
        }

    }
