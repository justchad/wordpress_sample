<?php

    class ArrowLocale
    {
        public $country;
        public $display_country;
        public $conversion_dollars;
        public $conversion_mileage;
        // MEDIA HOST
        public $cdn;
        // MEDIA DEFAULTS
        public $image_default;
        public $directory_default;
        public $video_default;
        public $doc_default;
        // MEDIA DIRECTORIES
        public $directory_image;
        public $directory_video;
        public $directory_docs;
        public $directory_fleet;
        // MEDIA FALLBACKS AND FIRSTS
        public $image_first;
        public $thumbnail_first;
        public $fallback_image;
        public $fallback_thumbnail;
        // MEDIA CONTROLS
        public $thumbnails_enabled;

        function __construct( )
        {
            $this->display_country      = false;
            $this->country              = ARROW_COUNTRY;
            $this->conversion_dollars   = ( $this->country === "USA" ) ? 1 : ARROW_CANADIAN_DOLLAR_CONVERSION_RATE;
            $this->conversion_mileage   = ( $this->country === "USA" ) ? 1 : ARROW_CANADIAN_MILEAGE_CONVERSION_RATE;

            $this->cdn                  = "https://www.arrowtruckhost.com";

            $this->image_default        = "NoImage2.jpg";
            $this->directory_default    = "images";
            $this->video_default        = "video1.mp4";
            $this->doc_default          = "doc1.pdf";

            $this->directory_image      = "/invImages";
            $this->directory_video      = "/invVideos";
            $this->directory_docs       = "/invDocs";
            $this->directory_fleet      = "/images/FleetPhotos";

            $this->image_first          = "image01.jpg";
            $this->thumbnail_first      = "image01.tb.jpg";

            $this->fallback_image       = "{$this->cdn}/{$this->directory_default}/{$this->image_default}";
            $this->fallback_thumbnail   = "{$this->cdn}/{$this->directory_default}/{$this->image_default}";

            $this->thumbnails_enabled   = false;
        }

        public function video( $path, $has_video )
        {
            if ( ! isset( $path ) ) {
                return false;
            }

            if ( ! isset( $has_video ) || $has_video !== 'Y' ) {
                return false;
            }

            if ( ! $this->video_default ) {
                return false;
            }

            $video = $path . $this->video_default;

            return $video;
        }

        public function doc( $path, $has_doc )
        {
            if ( ! isset( $path ) ) {
                return false;
            }

            if ( ! isset( $has_doc ) || $has_doc !== 'Y' ) {
                return false;
            }

            $doc = $path . $this->doc_default;

            return $doc;
        }

        public function image( $path, $count, $has_image, $is_appraisal )
        {
            if( !isset( $path ) ){
                throw new Exception( '[ERROR] > IMAGES CAN NOT BE MAPPED ::The $path is not set. [*] ' . __FUNCTION__ . '( $path, $count, $has_image, $is_appraisal ) [@] ' . $this->home_directory );
            }

            $processed      = false;

            $dir            = explode('/', $path);
            $url            = ( array_key_exists( 2, $dir ) ) ? $dir[2] : $this->cdn;
            $directory      = ( array_key_exists( 3, $dir ) ) ? $dir[3] : $this->directory_image;
            $sub_directory  = ( array_key_exists( 4, $dir ) ) ? $dir[4] : "";
            $id_directory   = ( array_key_exists( 5, $dir ) ) ? $dir[5] : "";
            $first_image    = $this->image_first;

            $fallback_image = $this->fallback_image;
            $fallback_thumbnail = $this->fallback_thumbnail;

            if ( class_exists( 'ACF' ) ) {
                $fallback_image = get_field( 'inventory_image_global', 'option' ) ?? $this->fallback_image;
                $fallback_thumbnail = get_field( 'inventory_image_global', 'option' ) ?? $this->fallback_thumbnail;
            }


            $image = ( object ) [
                'main'              => ( object ) [
                    'image'             => $fallback_image,
                    'thumbnail'         => $fallback_thumbnail
                ],
                'gallery'           => ( object ) [
                    'image'             => null,
                    'thumbnail'         => null
                ],
                'list'           => ( object ) [
                    'image'             => null,
                    'thumbnail'         => null
                ],
                'url'               => $url,
                'directory'         => $directory,
                'sub_directory'     => $sub_directory,
                'id_directory'      => $id_directory,
                'type'              => "inventory"
            ];

            if( isset( $is_appraisal ) && $is_appraisal === 'Y' ) {
                $image->main->image         = $path . $this->image_first;
                $image->main->thumbnail     = $path . $this->thumbnail_first;
                $image->type                = "fleet";
                $processed = true;
            }

            if( array_key_exists( 4, $dir ) && $dir[4] === 'FleetPhotos' ){
                $image->main->image         = $path . $this->image_first;
                $image->main->thumbnail     = $path . $this->thumbnail_first;
                $image->type                = "fleet";
                $processed = true;
            }

            if( $processed === false ){

                if( isset( $has_image ) && $has_image === 'Y' && $count >= 1 ) {

                    $images     = range( 1, $count );
                    $thumbnails = range( 1, $count );

                    foreach( $images as $k => $v ){

                        $img = sprintf( "%02d", $k + 1 );
                        $img_name = 'image' . $img . '.jpg';
                        $image_path = $path . $img_name;
                        $images[$k] = $image_path;

                        $thumb = sprintf( "%02d", $k + 1 );
                        $thumb_name = 'image' . $thumb . '.tb.jpg';
                        $thumb_path = $path . $thumb_name;
                        $thumbnails[$k] = $thumb_path;
                    }

                    $image->type                = "inventory";

                    $image->main->image         = $images[0];

                    $image->main->thumbnail     = $thumbnails[0];

                    $image->list->image         = $images;

                    $image->list->thumbnail     = $thumbnails;

                    unset( $images[0] );

                    unset( $thumbnails[0] );

                    $image->gallery->image      = $images;

                    $image->gallery->thumbnail  = $thumbnails;

                }
            }

            return $image;
        }

        private function assign_state( $city, $state )
        {
            if ( ! $city ) {
                return false;
            }

            $_city = strtoupper( $city );

            switch ( $_city ) {
                case "TROY":
                    $_state = "IL";
                    break;

                default:
                    $_state = $state;
            }

            return strtoupper( $_state );
        }

        public function city_state_from_branch( $branch_id )
        {
            switch ( $branch_id ) {
                case "SL":
                    $city_state = "St. Louis, MO";
                    break;

                case "CH":
                    $city_state = "Chicago, IL";
                    break;

                case "AT":
                    $city_state = "Atlanta, GA";
                    break;

                case "CN":
                    $city_state = "Cincinnati, OH";
                    break;

                case "DA":
                    $city_state = "Dallas, TX";
                    break;

                case "FT":
                    $city_state = "Fontana, CA";
                    break;

                case "FR":
                    $city_state = "Fresno, CA";
                    break;

                case "HS":
                    $city_state = "Houston, TX";
                    break;

                case "JX":
                    $city_state = "Jacksonville, FL";
                    break;

                case "KC":
                    $city_state = "Kansas City, MO";
                    break;

                case "NJ":
                    $city_state = "Newark, NJ";
                    break;

                case "PH":
                    $city_state = "Philadelphia, PA";
                    break;

                case "PX":
                    $city_state = "Phoenix, AZ";
                    break;

                case "SA":
                    $city_state = "San Antonio, TX";
                    break;

                case "SP":
                    $city_state = "Springfield, MO";
                    break;

                case "TA":
                    $city_state = "Tampa, FL";
                    break;

                case "OK":
                    $city_state = "Oklahoma City, OK";
                    break;

                case "ST":
                    $city_state = "Stockton, CA";
                    break;

                default:
                    $city_state = $branch_id;
            }

            return $city_state;
        }

        public function city_state( $city, $state )
        {
            if ( ! $city || ! $state ) {
                return false;
            }

            $_city = ucwords( strtolower( $city ) );

            $_state = $this->assign_state( $city, $state );

            return "{$_city}, {$_state}";
        }

        public function price( $price, $old_price )
        {
            if ( ! $price ) {
                return false;
            }

            $prefix = "$";
            $suffix = 'USD';

            if( $this->country !== 'USA' ){
                $prefix = "";
                $suffix = 'CAD';
            }

            $calculated_price     = number_format( (int) $price * $this->conversion_dollars, 2 );
            $old_calculated_price = number_format( (int) $old_price * $this->conversion_dollars, 2 );

            $price = ( object ) [
                'current'   => ( object ) [
                    'number'        => $calculated_price,
                    'format'        => "{$prefix}{$calculated_price} {$suffix}"
                ],
                'old'       => ( object ) [
                    'number'        => $old_calculated_price,
                    'format'        => "{$prefix}{$old_calculated_price} {$suffix}"
                ]
            ];

            return $price;
        }

        public function mileage( $mileage )
        {
            if ( ! $mileage ) {
                return false;
            }

            $suffix     = 'Miles';

            if( $this->country !== 'USA' ){
                $suffix     = 'KM';
            }

            $calculated = number_format( $mileage * $this->conversion_mileage );

            $mileage = ( object ) [
                'number'        => $calculated,
                'format'        => "{$calculated} {$suffix}",
            ];

            return $mileage;
        }

        public function ecm( $mileage )
        {
            if ( ! $mileage ) {
                return false;
            }

            $suffix     = 'Miles';

            if( $this->country !== 'USA' ){
                $suffix     = 'KM';
            }

            $calculated = number_format( $mileage * $this->conversion_mileage );

            $ecm = ( object ) [
                'number'        => $calculated,
                'format'        => "{$calculated} {$suffix}",
            ];

            return $ecm;
        }
    }
