<?php while ( have_posts() ) : the_post(); ?>

    <?php

        $id         = get_the_ID();
        $post       = get_post( $id );
        $meta       = get_post_meta( $id );
        $build      = new ArrowBuildLocation();
        $trans      = get_transient( 'location_transient_timestamp' );
        $pre        = null;
        $location   = null;
        $promotion  = null;
        $templates  = $build->get_templates();
        // SEE( $templates );
        // include( locate_template( $templates['disabled'] ) )


        // Check if data has expired
        if ( $trans === false ) {
            delete_transient( 'location_transient_timestamp' );

            delete_transient( 'location_transient' );

            $args = [
                'INIT'          => true,
                'PREFLIGHT'     => true,
                'REQUESTED'     => 'SYSTEM->SINGLE_LOCATIONS',
                'MODE'          => ARROW_API_ENVIRONMENT
            ];

            $sync = ArrowApiLocation::sync_V2( $args );
            var_error_log( json_encode( $sync ) );
        }

        $location_transient = get_transient( 'location_transient' )['items'];

        if ( $meta ) {
            $pre = ( object ) [
                'ID'            => strtolower( $meta[ 'BRANCH_ID' ][0] ),
                'data'          => ll_safe_decode( $location_transient[ $meta[ 'BRANCH_ID' ][0] ] ),
                'active'        => $build->get_active_status( $meta[ 'BRANCH_ID' ][0] ),
            ];
        }

        if ( $pre ) {
            $location           = $pre->data;
            $location->active   = $pre->active;
        }

        if ( $location ) {
            $location = $build->set_location_values( $location );
        }

        // REPS
        $manager    = null;
        $admin      = null;
        $staff      = null;

        if ( $location ) {

            // GET MANAGER
            if ( $location->manager ) {
                $manager = $build->query_reps( $location, [ ARROW_BRANCH_MANAGER_ROLE ] );
            }
            // SET MANAGER
            if ( $manager ) {
                $location->manager = $manager[0];
            }

            // GET ADMIN
            if ( $location->admin ) {
                $admin = $build->query_reps( $location, [ ARROW_BRANCH_ADMIN_ROLE ] );
            }
            // SET ADMIN
            if ( $admin ) {
                $location->admin = $admin[0];
            }

            // // GET REPS
            if ( $location ) {
                $staff = $build->query_reps( $location, ARROW_BRANCH_REP_ROLES );
            }
            // ORDER REPS
            if ( $staff ) {
                $location->reps = ( count( $staff ) > 0 ) ? $build->order_reps( $staff, 'title' ) : null;
            }
        }

        // TRUCKS
        $trucks = null;

        if ( $location ) {
            $trucks = $build->get_inventory( $location->ID );
            $location->featured_inventory = ( count( $trucks ) > 0 ) ? $build->get_featured( $trucks, ARROW_FEATURED_TRUCK_DISPLAY_LIMIT ) : null;
        }

        // CONTENT
        $content = $build->get_content( $location, $id );

        // SEE( $location );
        // SEE( $content );
    ?>

    <?php if ( $location->active === false ) : ?>

        <?php include( locate_template( $templates['disabled'] ) ) ?>

    <?php else : ?>

        <div class="single-location" data-component="locations">
            <div class="image-half-color-header">
                <div class="container">
                    <a href="<?php echo get_post_type_archive_link( 'll_location' ) ?>" class="location-back" title="Back to All Locations">
                        <svg class="icon icon-left-arrow"><use xlink:href="#icon-left-arrow"></use></svg>
                    </a>
                    <!-- THUMBNAIL -->
                    <div class="image-wrapper relative aspect-square">
                        <?php
                            ll_include_component(
                                'fit-image',
                                [
                                    'image_id'          => ( isset( $location->WP->thumbnail ) && $location->WP->thumbnail ) ? $location->WP->thumbnail : $location->image,
                                    'thumbnail_size'    => 'full'
                                ]
                            );
                        ?>
                    </div> <!-- /.image-wrapper -->
                </div> <!-- /.container -->
            </div> <!-- /.image-half-color-header -->
            <div class="container block md:grid md:grid-cols-8 lg:grid-cols-12 gap-x-13" data-component="set-code" data-code="<?php echo $location->ID ?>">
                <div class="md:col-span-8 lg:col-span-10 lg:col-start-2 text-center">
                    <div class="location-THE-CONTENT">

                        <!-- TITLE -->
                        <?php include( locate_template( $templates['title'] ) ) ?>

                        <!-- MULTILINGUAL -->
                        <?php include( locate_template( $templates['languages'] ) ) ?>

                        <!-- BRANCH HOURS -->
                        <?php include( locate_template( $templates['hours'] ) ) ?>

                        <!-- STAFF -->
                        <?php if ( $location->reps ) : ?>
                            <div class="custom-simple-slider-updated">
                                <h2 class="hdg-1 md:text-5xl px-gutter text-2xl text-center mb-12 mt-16">
                                    Meet Our <?php echo $location->name; ?> Team
                                </h2>
                                <div class="mx-auto max-4-col md:max-w-none" data-component="simple-slider">

                                    <!-- BRANCH MANAGER -->
                                    <?php if ( $location->manager ) : ?>
                                        <?php include( locate_template( $templates['manager'] ) ); ?>
                                    <?php endif; ?>
                                    <!-- ADMIN -->
                                    <?php if ( $location->admin ) : ?>
                                        <?php include( locate_template( $templates['admin'] ) ); ?>
                                    <?php endif; ?>

                                    <?php if ( count( $location->reps ) > 0 ) : ?>
                                        <?php foreach( $location->reps as $key => $rep ) : ?>
                                            <!-- REPS -->
                                            <?php include( locate_template( $templates['rep'] ) ); ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>

                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- TRUCKS -->
                        <h2 class="px-gutter hdg-1 text-2xl md:text-5xl text-center mt-16 mb-5 md:mb-16">
                            Trucks for Sale in <?php echo $location->name . ', ' . $location->address->state ?>
                        </h2>

                        <?php
                            ll_include_component(
                                'featured-trucks-grid',
                                [
                                    'hdg'               => [
                                        'text'              =>  $location->name . '\'s Featured Inventory',
                                        'tag'               => 'h3'
                                    ],
                                    'origin'            => 'single-ll_location',
                                    'mobile_slider'     => false,
                                    'list'              => false,
                                    'all_trucks'        => false,
                                    'single'            => true,
                                    'location'          => $location->ID,
                                    'address'           => $location->address,
                                    'view_all'          => $location->inventory_link,
                                    'grid'              => 'location',
                                    'trucks'            => $location->featured_inventory,
                                    'promotion'         => $promotion,
                                    'promotion_text'    => null,
                                    'promotion_array'   => []
                                ],
                                [
                                    'id'                => 'featured-trucks',
                                    'classes'           => [ 'text-left' ]
                                ]
                            );
                        ?>

                        <?php if ( $content ) : ?>
                            <h2 class="block container hdg-1 md:text-5xl px-gutter text-2xl text-center mb-8">
                                About Arrow
                            </h2>
                            <!-- ABOUT 1 -->
                            <div class="block container mb-8 text-left">
                                <?php echo $content->about_1; ?>
                            </div>
                            <!-- ABOUT 2 -->
                            <div class="block container mb-8 text-left">
                                <?php echo $content->about_2; ?>
                            </div>
                            <!-- ABOUT 3 -->
                            <div class="block container mb-8 text-left">
                                <?php echo $content->about_3; ?>
                            </div>
                            <!-- ABOUT 4 -->
                            <div class="block container mb-16 text-left">
                                <?php echo $content->about_4; ?>
                            </div>
                        <?php endif ; ?>
                    </div> <!-- END location-THE-CONTENT -->
                </div> <!-- /.col -->
            </div> <!-- /.container -->
        </div> <!-- /.single-location -->
    <?php endif ; ?>

<?php endwhile; ?>
