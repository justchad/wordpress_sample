<?php

    global $post;

    $truck_check = false;

    $identifier = 'featured-trucks-grid';

    $assignment = ( isset( $post->post_title ) ) ? string_to_variable( $post->post_title ) : null;

    $etype      = 'component';

    $defaults = [
        'hdg'               => [],
        'origin'            => null,
        'mobile_slider'     => false,
        'list'              => false,
        'all_trucks'        => false,
        'single'            => false,
        'location'          => null,
        'address'           => null,
        'num'               => null,
        'view_all'          => false,
        'trucks'            => [],
        'cards'             => [
            'default'           => 'templates/partials/cards/truck-card.php',
            'search'            => 'templates/partials/cards/search-truck-card.php',
            'location'          => 'templates/partials/cards/location-truck-card.php',
            'rep'               => 'templates/partials/cards/rep-truck-card.php',
            'featured'          => 'templates/partials/cards/featured-truck-card.php'
        ],
        'grid'              => 'default'
    ];

    $component_data = ll_parse_args( $component_data, $defaults );

    $classes = ( isset( $component_args[ 'classes' ] ) ? $component_args[ 'classes' ] : [] );

    $id   = ( isset( $component_args[ 'id' ] ) ? $component_args[ 'id' ] : false );

    $t = '24';

    $b = '24';

    if ( ( isset( $component_data[ 'all_trucks' ] ) && $component_data[ 'all_trucks' ] === false ) ) {
        $t = '0';
    }

    if ( ( isset( $component_data[ 'single' ] ) && $component_data[ 'single' ] === false ) ) {
        $t = '0';
    }

    $component_data[ 'context' ] = ( object ) [
        'origin'                => $component_data[ 'origin' ] ?? null,
        'current_location_id'   => $component_data[ 'location' ] ?? null,
        'address'               => $component_data[ 'address' ] ?? null,
        'promotion'             => $component_data[ 'promotion' ] ?? null
    ];

    if ( $component_data[ 'grid' ] === "search" ) {
        $args = [];

        $trucks             = [];
        $truck_transient    = get_transient( 'inventory_transient' )[ 'items' ];

        foreach( $truck_transient as $truck_key => $truck_value ) {

            $truck          = json_decode( $truck_value );
            $truck->card    = json_decode( $truck->card );

            if( $truck->model != 'Trailer' ) {
                $trucks[$truck->ID] = $truck->card;
            }

        }

        $component_data[ 'trucks' ] = $trucks;
        // $component_data[ 'trucks' ] = ArrowApiInventory::search_inventory( $args );
    }

    if( $component_data[ 'grid' ] === "location" ) {
        // $component_data[ 'default_grid' ] = false;
    }

    if( $component_data[ 'grid' ] === "rep" ) {
        // $component_data[ 'default_grid' ] = false;
    }

    if( $component_data[ 'grid' ] === "featured" ) {
        // $component_data[ 'default_grid' ] = false;
    }

    // SEE( $component_data );
    // SEE( $trucks );

?>

<?php if ( ll_empty( $component_data ) ) return; ?>

    <section
        id="<?php echo strtolower( $assignment ) . '-' . $identifier . '-' . $etype; ?>"
        class="<?php echo $identifier; ?> <?php echo ll_format_bg( 'bg-white', $t, $b ) ?> <?php echo implode( " ", $classes ); ?>" <?php echo ( $id ? 'id="' . $id . '"' : '' ) ?>
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

                    <div id=""
                        class="row <?php echo isset( $component_data[ 'mobile_slider' ] ) && $component_data[ 'mobile_slider' ] ? 'flex-slider' : ''; ?>"
                        data-slides-to-show="sm:1 md:2"
                        data-arrows="false"
                        data-dots="true">

                        <!-- Check for trucks array, if found determine correct grid -->
                        <?php if ( isset( $component_data[ 'trucks' ] ) && count( (array) $component_data['trucks'] ) > 0 ) : ?>

                            <!-- This is the search page truck grid -->
                            <?php if ( $component_data['grid'] === "search" ) : ?>

                                <?php foreach ( $component_data['trucks'] as $key => $truck ) : ?>

                                    <?php if ( $truck ) : ?>

                                        <?php $truck->CONTEXT = $component_data['context']; ?>

                                        <div class="relative col w-full truck-card-wrapper <?php echo ( isset( $component_data['list'] ) && $component_data['list'] ? 'list-view' : 'grid-view' ) ?>">
                                            <div class="featured-truck-flag absolute hidden">
                                                <h3>Featured</h3>
                                            </div>
                                            <?php include( locate_template( $component_data['cards']['search'] ) ); ?>
                                        </div> <!-- /.col -->

                                    <?php endif; ?>

                                <?php endforeach; ?>

                            <?php endif; ?>

                            <!-- This is the location truck grid -->
                            <?php if ( $component_data['grid'] === "location" ) : ?>

                                <?php foreach ( $component_data['trucks'] as $key => $truck ) : ?>

                                    <?php if ( $truck ) : ?>

                                        <?php $truck->CONTEXT = $component_data['context']; ?>

                                        <div class="relative col w-full truck-card-wrapper <?php echo ( isset( $component_data['list'] ) && $component_data['list'] ? 'list-view' : 'grid-view' ) ?>">
                                            <div class="featured-truck-flag absolute hidden">
                                                <h3>Featured</h3>
                                            </div>
                                            <?php include( locate_template( $component_data['cards']['location'] ) ); ?>
                                        </div> <!-- /.col -->

                                    <?php endif; ?>

                                <?php endforeach; ?>

                            <?php endif; ?>

                            <!-- This is the rep truck grid -->
                            <?php if ( $component_data['grid'] === "rep" ) : ?>

                                <?php foreach ( $component_data['trucks'] as $key => $truck ) : ?>

                                    <?php if ( $truck ) : ?>

                                        <?php $truck->CONTEXT = $component_data['context']; ?>

                                        <div class="relative col w-full truck-card-wrapper <?php echo ( isset( $component_data['list'] ) && $component_data['list'] ? 'list-view' : 'grid-view' ) ?>">
                                            <div class="featured-truck-flag absolute hidden">
                                                <h3>Featured</h3>
                                            </div>
                                            <?php include( locate_template( $component_data['cards']['rep'] ) ); ?>
                                        </div> <!-- /.col -->

                                    <?php endif; ?>

                                <?php endforeach; ?>

                            <?php endif; ?>

                            <!-- This is the default truck grid -->
                            <?php if ( $component_data['grid'] === "default" ) : ?>

                                <?php foreach ( $component_data['trucks'] as $key => $truck ) : ?>

                                    <?php if ( $truck ) : ?>

                                        <?php $truck->CONTEXT = $component_data['context']; ?>

                                        <div class="relative col w-full truck-card-wrapper <?php echo ( isset( $component_data['list'] ) && $component_data['list'] ? 'list-view' : 'grid-view' ) ?>">
                                            <div class="featured-truck-flag absolute hidden">
                                                <h3>Featured</h3>
                                            </div>
                                            <?php include( locate_template( $component_data['cards']['default'] ) ); ?>
                                        </div> <!-- /.col -->

                                    <?php endif; ?>

                                <?php endforeach; ?>

                            <?php endif; ?>

                        <?php endif; ?>

                    </div> <!-- /.row -->
                </div> <!-- /.col -->
            </div> <!-- /.row -->
        </div> <!-- /.container -->
    </section> <!-- /.featured-trucks-grid -->
