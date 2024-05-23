<?php

    class ArrowBuildEmployee
    {
        function __construct()
        {

        }

        public function get_templates()
        {
            return [
                'languages'         => 'templates/partials/employee/employee-languages.php',
                'contact'           => 'templates/partials/employee/employee-contact.php',
                'financing'         => 'templates/partials/employee/employee-financing.php',
                'financing-dynamic' => 'templates/partials/employee/employee-dynamic-financing.php',
            ];
        }

        public function get_language_list( $refresh = ARROW_REFRESH_LANGUAGE_LIST )
        {
            $languages = null;

            if ( $refresh == true ) {
                $preflight = Arrow()->check();

                $language_list = Arrow()->get_language_list();

                $languages = [];

                foreach( $language_list as $key => $language ){
                    if( ! $language ){
                        continue;
                    }

                    $languages[] = [ 'name' => $language->LANG ];
                }

                update_field( 'employee_language_list', $languages, 'option' );

                update_field( 'employee_refresh_language_list', false, 'option' );

                return $languages;
            }

            if ( class_exists( 'ACF' ) ) {
                $languages = get_field( 'employee_language_list', 'option' );
                return $languages;
            }

            return ARROW_LANGUAGE_LIST;
        }

        public function set_language_list( $language_list )
        {
            if ( ! $language_list ) {
                return false;
            }

            $languages = [];
            if ( is_array( $language_list ) && count( $language_list ) >= 1 ) {
                foreach( $language_list as $key => $language ) {
                    $languages[] = strtolower( $language->name );
                }
            }

            return $languages;
        }

        public function get_inventory( $location )
        {
            if ( ! $location ) {
                return false;
            }

            $args = [
                'meta_key'        => 'BRANCH_ID',
                'meta_value'      => $location->ID,
                'post_type'       => ARROW_INVENTORY_POST_TYPE,
            ];

            $wp_posts   = get_posts( $args );

            $inventory  = [];

            if ($wp_posts ) {

            }

            foreach( $wp_posts as $key => $truck ) {
                if ( ! $truck ) {
                    continue;
                }

                $meta = ll_safe_decode( get_post_meta( $truck->ID, 'DATA')[0] );

                if ( ! $meta ) {
                    continue;
                }

                $hide_trailers = ARROW_EXCLUDE_TRAILER_FROM_FEATURED;

                if ( class_exists( 'ACF' ) ) {
                    $hide_trailers = get_field( 'inventory_exclude_trailers', 'option' );
                }

                if ( $hide_trailers && $meta->type != 'trailer' ) {
                    $inventory[ $meta->ID ] = $meta->card;
                }
            }

            return $inventory;
        }

        public function get_featured( $trucks, $limit )
        {
            if ( ! $trucks ) {
                return false;
            }

            if ( class_exists( 'ACF' ) ) {
                $limit = get_field( 'inventory_number_of_featured_trucks', 'option' );
            }

            return array_intersect_key( $trucks, array_flip( array_rand( $trucks, $limit ) ) );
        }

        public function get_employee( $user )
        {
            if ( ! $user ) {
                return false;
            }

            $employee = ll_safe_decode( get_user_meta( $user->ID , 'DATA', true ) );

            return $employee;
        }

        public function get_location( $employee, $branch = null )
        {
            if ( ! $employee ) {
                return false;
            }

            $branch_ID = ( $branch ) ? $branch : $employee->branch_ID;

            if ( ! $branch_ID ) {
                return false;
            }

            $args = [
                'meta_key'    => 'BRANCH_ID',
                'meta_value'  => $branch_ID,
                'post_type'   => ARROW_LOCATION_POST_TYPE,
                'numberposts' => 1
            ];

            $wp_post  = get_posts( $args );

            $location = ll_safe_decode( get_post_meta( $wp_post[0]->ID, 'DATA')[0] );

            $location->inventory_link = "/search-inventory/?location={$location->ID}";

            $location->promotion      = null;

            return $location;
        }

    }
