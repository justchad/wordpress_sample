<?php

    global $post;

    $identifier = 'search-trucks-grid';

    $assignment = ( isset( $post->post_title ) ) ? string_to_variable( $post->post_title ) : null;
    $etype      = 'component';

    $defaults = [
        'hdg'             => [],
        'trucks'          => false,
        'mobile_slider'   => false,
        'list'            => false,
        'promotext'       => false,
        'promoarray'      => false,
        'num'             => 6
    ];

    $includes = [
        'featured-truck-card'   => 'templates/partials/featured-truck-card.php',
        'truck-card'            => 'templates/partials/truck-card.php'
    ];

    $component_data = ll_parse_args( $component_data, $defaults );

    $classes = ( isset( $component_args['classes'] ) ? $component_args['classes'] : array() );

    $component_id   = ( isset( $component_args['id'] ) ? $component_args['id'] : false );

    $t = '24';

    $b = '24';

    if ( ( isset( $component_data['all_trucks'] ) && $component_data['all_trucks'] ) || isset( $component_data['single'] ) ) {
        $t = '0';
    }

    $truck_check    = false;
    $truck_data     = $component_data['trucks'];
    $truck_source   = 'API';

    if ( isset( $truck_data ) ){
        $truck_check = true;
        $component_data['featured-trucks'] = $truck_data;
    }

    $component_data[ 'context' ] = ( object ) [
        'origin'                => $component_data[ 'origin' ] ?? null,
        'current_location_id'   => $component_data[ 'location' ] ?? null,
        'address'               => $component_data[ 'address' ] ?? null,
        'promotion'             => $component_data[ 'promotion' ] ?? null
    ];

    // SEE( $component_data );

?>

<?php if ( ll_empty( $component_data ) ) return; ?>
    <section
        id="<?php echo strtolower( $assignment ) . '-' . $identifier . '-' . $etype; ?>"
        class="<?php echo $identifier; ?> <?php echo ll_format_bg( 'bg-white', $t, $b ) ?> <?php echo implode( " ", $classes ); ?>" <?php echo ( $component_id ? 'id="'.$component_id.'"' : '' ) ?>
        data-component="<?php echo $identifier; ?>">
        <div class="container">
            <div class="row justify-center">
                <div class="col w-full <?php echo isset( $component_data[ 'all_trucks' ] ) ? '' : 'xl:w-10/12'; ?>">

                    <?php if ( isset( $component_data[ 'hdg' ][ 'text' ] ) || isset( $component_data[ 'view_all' ] ) ) : ?>

                        <div class="flex justify-between items-center">

                            <?php if ( isset( $component_data[ 'hdg' ][ 'text' ] ) ) : ?>

                                <<?php echo $component_data[ 'hdg' ][ 'tag' ]; ?> class="hdg-2 text-lg md:text-3xl lg:text-4xl">

                                    <?php echo $component_data[ 'hdg' ][ 'text' ]; ?>

                                </<?php echo $component_data[ 'hdg' ][ 'tag' ]; ?>>

                            <?php endif; ?>

                            <?php if ( isset( $component_data['view_all'] ) ) : ?>

                                <?php if ( $component_data['view_all'] == true ) : ?>

                                    <a href="<?php echo $component_data['view_all']; ?>" class="flex-0 font-medium text-base text-gray-400 hover:text-brand-primary inline-flex items-center">
                                        <svg class="icon icon-search svg-align mr-1 text-brand-primary">
                                            <use xlink:href="#icon-search"></use>
                                        </svg>
                                        View All
                                    </a>


                                <?php else : ?>

                                    <a href="/search-inventory/" class="flex-0 font-medium text-base text-gray-400 hover:text-brand-primary inline-flex items-center">
                                        <svg class="icon icon-search svg-align mr-1 text-brand-primary">
                                            <use xlink:href="#icon-search"></use>
                                        </svg>
                                        View All
                                    </a>

                                <?php endif; ?>

                            <?php endif; ?>

                        </div> <!-- /.flex -->

                    <?php endif; ?>

                    <div class="row <?php echo isset( $component_data['mobile_slider'] ) && $component_data['mobile_slider'] ? 'flex-slider' : ''; ?>" data-slides-to-show="sm:1 md:2" data-arrows="false" data-dots="true">

                        <?php if ( isset( $component_data['featured-trucks'] ) && is_array( $component_data['featured-trucks'] ) ) : ?>

                            <?php foreach ( $component_data['featured-trucks'] as $key => $truck ) : ?>

                                <?php $truck->CONTEXT = $component_data['context']; ?>

                                <?php if ( isset( $component_data[ 'featured-trucks' ] ) && $truck_check == true ) : ?>

                                    <div class="relative col w-full truck-card-wrapper <?php echo ( isset( $component_data['list'] ) && $component_data['list'] ? 'list-view' : 'grid-view' ) ?>">

                                        <div class="featured-truck-flag absolute hidden">
                                            <h3>Featured</h3>
                                        </div>

                                        <?php if ( isset( $component_data['origin'] ) && $component_data['origin'] === 'single-ll_location' ) : ?>

                                            <?php include( locate_template( $includes[ 'featured-truck-card' ] ) ); ?>

                                        <?php else : ?>

                                            <?php include( locate_template( $includes[ 'truck-card' ] ) ); ?>

                                        <?php endif; ?>

                                    </div> <!-- /.col -->

                                <?php endif; ?>

                            <?php endforeach; ?>

                        <?php endif; ?>

                    </div> <!-- /.row -->
                </div> <!-- /.col -->
            </div> <!-- /.row -->
        </div> <!-- /.container -->
    </section> <!-- /.search-trucks-grid -->
