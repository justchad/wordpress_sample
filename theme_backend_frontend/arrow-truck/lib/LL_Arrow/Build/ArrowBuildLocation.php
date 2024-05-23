<?php

class ArrowBuildLocation
    {
        function __construct()
        {

        }

        public function get_templates()
        {
            return [
                'disabled'  => 'templates/partials/location/location-disabled.php',
                'rep'       => 'templates/partials/location/location-rep.php',
                'manager'   => 'templates/partials/location/location-manager.php',
                'admin'     => 'templates/partials/location/location-admin.php',
                'title'     => 'templates/partials/location/location-title.php',
                'languages' => 'templates/partials/location/location-languages.php',
                'hours'     => 'templates/partials/location/location-hours.php'
            ];
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

        public function get_rep_card( $user, $location, $branch = null )
        {
            if ( ! $user ) {
                return  false;
            }

            $B = new ArrowBuildLocation();

            $user->card->languages = [];

            if ( $user->languages ) {
                $user->card->languages = $B->set_language_list( $user->languages ) ?? [];
            }

            if ( $user->v_card_href ) {
                $user->card->v_card = $user->v_card_href;
            }

            $user->card->member_of = $user->branch_ID;

            if ( $user->multi_manager && is_array( $user->multi_manager ) ) {
                $user->card->member_of = $user->multi_manager;
            }

            if ( $location->contact->toll_free_1) {
                $user->card->phone = ( $user->card->phone ) ? format_phone( $user->card->phone ) : format_phone( $location->contact->toll_free_1 );
            }

            if ( $branch ) {
                $user->card->branch_ID = $branch;
            }

            return $user->card;
        }

        public function query_reps( $location, $role )
        {
            if ( ! $location ) {
                return false;
            }

            $B = new ArrowBuildLocation();

            $args = [
            	'role__in' => $role
            ];

            $query = new WP_User_Query( $args );

            $users = $query->get_results();

            $reps = [];

            foreach( $users as $key => $user ) {

                if ( ! $user ){
                    continue;
                }

                $meta = get_user_meta( $user->ID );

                if ( ! $meta ) {
                    continue;
                }

                $user_branch = $meta['BRANCH_ID'][0];

                if ( ! $user_branch ) {
                    continue;
                }

                if ( is_serialized( $user_branch ) ) {
                    $user_branch = unserialize( $user_branch );
                    if ( is_array( $user_branch ) ) {
                        if ( in_array( $location->ID, $user_branch ) ) {
                            $user_branch = $location->ID;
                        }
                    }
                }

                if ( is_array( $user_branch ) ) {
                    continue;
                }

                if ( $user_branch == $location->ID ) {

                    $user_data = ll_safe_decode( $meta['DATA'][0] );

                    if ( ! $user_data ) {
                        continue;
                    }

                    $reps[] = $B->get_rep_card( $user_data, $location, $user_branch );
                }
            }

            return $reps;
        }

        public function get_inventory( $id )
        {
            if ( ! $id ) {
                return false;
            }

            $args = [
                'meta_key'      => 'BRANCH_ID',
                'meta_value'    => $id,
                'post_type'     => ARROW_INVENTORY_POST_TYPE,
            ];

            $wp_inventory = get_posts( $args );

            $trucks = [];

            foreach( $wp_inventory as $key => $inventory ){
                $meta = ll_safe_decode( get_post_meta( $inventory->ID, 'DATA')[0] );

                $hide_trailers = ARROW_EXCLUDE_TRAILER_FROM_FEATURED;

                if ( class_exists( 'ACF' ) ) {
                    $hide_trailers = get_field( 'inventory_exclude_trailers', 'option' );
                }

                if ( $hide_trailers && $meta->type != 'trailer' ) {
                    $trucks[ $meta->ID ] = $meta->card;
                }
            }

            return $trucks;
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

        public function order_reps( $reps, $order_by )
        {
            if ( ! $reps ) {
                return null;
            }

            $sorted = [];

            foreach( $reps as $key => $rep ) {

                if ( ! $rep ) {
                    continue;
                }

                if ( $rep->title == 'Branch Manager' || $rep->title == 'Finance and Insurance Manager' ) {
                    continue;
                }

                if ( $order_by != 'title' ) {
                    continue;
                }

                $order = 0;

                if ( $order_by == 'title' ) {

                    // Asst. Branch Manager
                    // Sales Manager
                    // Lead Sales Associate
                    // Retail Sales Consultant
                    // Admin. Asst.
                    // Lot Coordinator

                    switch ( $rep->title ) {
                        case "Assistant Branch Manager": // Asst. Branch Manager
                            $order = 6;
                            break;
                        case "Sales and Purchasing Manager": // Sales Manager
                            $order = 5;
                            break;
                        case "Sales Manager": // Sales Manager
                            $order = 5;
                            break;
                        case "Lead Sales Associate": // Lead Sales Associate
                            $order = 4;
                            break;
                        case "Retail Sales Consultant": // Retail Sales Consultant
                            $order = 3;
                            break;
                        case "Administrative Assistant": // Admin. Asst.
                            $order = 2;
                            break;
                        case "Lot Coordinator": // Lot Coordinator
                            $order = 1;
                            break;
                        case "Inventory Coordinator": // Lot Coordinator
                            $order = 1;
                            break;
                    }
                }

                $sorted[ $rep->ID ] = $rep;
                $sorted[ $rep->ID ]->order = $order;
            }

            $keys = array_keys( $sorted );

            array_multisort(
                array_column( $sorted, 'order'), SORT_DESC, SORT_NUMERIC, $sorted, $keys
            );

            return array_combine( $keys, $sorted );
        }

        public function get_active_status( $id )
        {
            if ( ! $id ) {
                return false;
            }

            $active = true;

            if ( class_exists( 'ACF' ) ) {

                $admin_disabled = get_field( 'location_disabled_global', 'option' );

                $disabled_locations = [];

                foreach( $admin_disabled as $disabled ){
                    $branch_id = get_post_meta( $disabled->ID, 'BRANCH_ID', true );
                    $disabled_locations[] = $branch_id;
                }

                if ( in_array( $id, $disabled_locations )) {
                    $active = false;
                }
            }

            return $active;
        }

        public function unset_location( $location )
        {
            if ( ! $location ) {
                return false;
            }

        }

        public function set_location_values( $location )
        {
            if ( ! $location ) {
                return false;
            }

            $location->image = ARROW_LOCATION_FALLBACK_IMAGE;
            if ( class_exists( 'ACF' ) ) {
                $location->image = get_field( 'location_image_global', 'option' ) ?? ARROW_LOCATION_FALLBACK_IMAGE;
            }

            $location->inventory_link = "/search-inventory/?location={$location->ID}";



            return $location;
        }

        public function get_content( $location, $post_id )
        {
            if ( ! $location ) {
                return false;
            }

            $acf = null;

            if ( class_exists( 'ACF' ) ) {
                $acf = get_field( 'location_about_seo', $post_id );
            }

            $copy = ll_safe_decode( $location->about_and_seo );

            $content = (object) [
                'title'     => $acf[ 'title'] ?? $copy->title,
                'about_1'   => $acf[ 'about_section_1'] ?? $copy->about_1,
                'about_2'   => $acf[ 'about_section_2'] ?? $copy->about_2,
                'about_3'   => $acf[ 'about_section_3'] ?? $copy->about_3,
                'about_4'   => $acf[ 'about_section_4'] ?? $copy->about_4,
                'source'    => ( $acf ) ? "ACF" : "DATA"
            ];

            return $content;
        }
    }
