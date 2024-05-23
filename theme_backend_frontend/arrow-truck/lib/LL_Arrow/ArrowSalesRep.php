<?php

/**
 * Class ArrowSalesRep
 * Builds a ArrowSalesRep from a WP_User
 *
 * @package ArrowApi
 */
class ArrowSalesRep
{
    private const SRC_URL        = 'https://www.arrowtruckhost.com/images/slsphotos';
    private const DEFAULT_IMAGE  = 'https://www.arrowtruck.com/images/NoImageHead2.jpg';

    public function __construct( WP_User $wp_user, $include_location = false )
    {
        $data = ll_safe_decode( get_field( 'arrow_data', $wp_user ) );

        if ( is_object( $data ) ) {
            $data = get_object_vars( ll_safe_decode( get_user_meta( $wp_user->ID, 'arrow_data', true ) ) );

            array_map( function( $key, $value ) {
                $this->$key = $value;

            }, array_keys( $data ), $data );
        }

        $this->wp_user      = $wp_user;

        $this->image        = $this->getImage();

        $this->bio          = $this->getBio();

        $this->languages    = $this->getLanguages();

        $this->name         = LL_StringUtil::sentance_case( $this->SLSFNAME ) . ' ' . LL_StringUtil::sentance_case( $this->SLSLNAME );

        $this->job          = $this->getTitle( $this->USRJOBTL );

        $this->card         = get_field( 'contact_card_file', $this->wp_user );

        $this->permalink    = get_author_posts_url( $this->wp_user->ID );

        $this->last_initial = $this->wp_user->last_name[0];

        $this->first_name   = LL_StringUtil::sentance_case( $this->SLSFNAME );

        $this->last_name    = LL_StringUtil::sentance_case( $this->SLSLNAME );

        $this->repnumber    = $this->SLSREPNO;

        if ( $include_location ) {
            $this->location = $this->getLocation();
        }

        // $this->site->user   = $this->arrowUser();
    }

    private function getImage()
    {
        $image = get_field( 'profile_photo', $this->wp_user );

        if ( $image ) {
            return $image;
        }

        $filename = ArrowSalesRep::SRC_URL . "/{$this->SLSREPNO}/{$this->SLSREPNO}image1.jpg";

        $file_headers = @get_headers($filename);

        if ( $file_headers[0] !== 'HTTP/1.1 404 Not Found' && $file_headers[0] !== 'HTTP/1.0 302 Found' && $file_headers[7] !== 'HTTP/1.0 404 Not Found' ) {

            return ArrowSalesRep::SRC_URL . "/{$this->SLSREPNO}/{$this->SLSREPNO}image1.jpg";

        } else {

            return ArrowSalesRep::DEFAULT_IMAGE;

        }
    }

    private function getBio()
    {
        $bio = get_field( 'biography', $this->wp_user );

        if ( $bio ) {
            return $bio;
        }

        return $this->SLSBIO;
    }

    private function getLanguages()
    {
        $rep_languages = [];

        for ( $i = 1; $i < 11; $i++ ) {

            $key = 'SLSLANG' . $i;

            $this->$key = LL_StringUtil::sentance_case( $this->$key );

            if ( !$this->$key )
                continue;

            $rep_languages[] = $this->$key;

        }

        return collect( $rep_languages );
    }

    private function getLocation()
    {

        return new ArrowLocation( reset( get_posts( [
            'post_type'         => 'll_location',
            'posts_per_page'    => 1,
            'meta_key'          => 'BRNBRNID',
            'meta_value'        => $this->SLSBRANCH
        ]
        ) ), false );

    }

    private function getTitle( $title_string = null )
    {
        $rep_title = ( $title_string ) ? strtoupper( $title_string ) : strtoupper( $this->USRJOBTL ) ;

        if ( ! $rep_title ) {
            return false;
        }

        switch ( $rep_title ) {
            case "BUYERS":
                $title  = "Buyer";
                $sort   = 1;
                break;

            case "MGR":
                $sort   = 2;
            case "SATMGR":
                $title  = "Branch Manager";
                $sort   = 3;
                break;

            case "FIMGR/ADMIN":
                $title  = "Finance and Insurance Manager";
                $sort   = 4;
                break;

            case "ASTMGR":
                $title  = "Assistant Branch Manager";
                $sort   = 5;
                break;

            case "SALESMGR":
                $sort   = 6;
            case "SALES MANAGER":
                $title  = "Sales Manager";
                $sort   = 7;
                break;

            case "SALES/PURCH MGR":
                $title  = "Sales and Purchasing Manager";
                $sort   = 8;
                break;

            case "LEAD SALE ASSC":
                $title  = "Lead Sales Associate";
                $sort   = 9;
                break;

            case "SALES":
                $title  = "Retail Sales Consultant";
                $sort   = 10;
                break;

            case "ADMIN":
                $sort   = 11;
            case "ADMIN ASSIST":
                $title  = "Administrative Assistant";
                $sort   = 12;
                break;

            case "SHOP":
                $sort   = 13;
            case "SHOP2":
                $title  = "Inventory Coordinator";
                $sort   = 14;
                break;

            default:
                $title  = $title;
                $sort   = 0;
        }

        if ( $this->SLSREPNO ) {
            switch ( $this->SLSREPNO ) {
                case "2828":
                    $title  = "Lead Sales Associate";
                    $sort   = 9;
                    break;

                case "1363":
                    $title  = "Sales Manager";
                    $sort   = 7;
                    break;
            }
        }

        $this->sort_order = $sort;

        return $title;
    }

    private function url()
    {
        return home_url() . '/staff/'. sanitize_title( $this->name );
    }

    public function card()
    {
        $card = "BEGIN:VCARD
        VERSION:3.0
        FN;CHARSET=UTF-8:{$this->first_name} {$this->first_name}
        N;CHARSET=UTF-8:{$this->first_name};{$this->first_name};;;
        EMAIL;CHARSET=UTF-8;type=HOME,INTERNET:hellojustchad@gmail.com
        EMAIL;CHARSET=UTF-8;type=WORK,INTERNET:3333333333
        LOGO;ENCODING=b;TYPE=PNG:IMAGEDATA..
        PHOTO;ENCODING=b;TYPE=JPEG:IMAGEDATA..
        TEL;TYPE=CELL:1111111111
        TEL;TYPE=HOME,VOICE:2222222222
        TEL;TYPE=WORK,FAX:4444444444
        LABEL;CHARSET=UTF-8;TYPE=HOME:Home
        ADR;CHARSET=UTF-8;TYPE=HOME:;;10117 W. 54th St.;Merriam;KS ;66203;United States
        LABEL;CHARSET=UTF-8;TYPE=WORK:Arrow Truck Sales
        ADR;CHARSET=UTF-8;TYPE=WORK:;;3200 Manchester Trafficway;Kansas City;MO;64129;United States
        TITLE;CHARSET=UTF-8:Hamster Pants
        ORG;CHARSET=UTF-8:Arrow Trucks
        URL;type=WORK;CHARSET=UTF-8:https://www.arrowtruck.com
        REV:2024-01-15T01:33:00.789Z
        END:VCARD";

        return utf8_encode($card);
    }

}
