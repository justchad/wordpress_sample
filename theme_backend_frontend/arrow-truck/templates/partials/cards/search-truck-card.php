<?php


    $defaults = [
        'image'             => "image01.jpg",
        'image_FALLBACK'    => "https://www.arrowtruckhost.com/images/NoImage2.jpg",
        'list'              => ( isset( $component_data['list'] ) ) ? true : false,
        'featured'          => true,
        'all'               => ( isset( $component_data['all_trucks'] ) ) ? true : false,
        'post_type'         => 'll_inventory',
        'meta_key'          => 'stock_number'
    ];

    if ( isset( $truck ) ) {

        // if ( ! isset( $truck->POST_DATA ) ) {
        //
        //     $post_id = get_posts( [
        //         'posts_per_page'    => -1,
        //         'post_type'         => $defaults[ 'post_type' ],
        //         'fields'            => 'ids',
        //         'meta_key'          => $defaults[ 'meta_key' ],
        //         'meta_value'        => $truck->STOCKNUM
        //     ] );
        //
        //     if( isset( $post_id[0] ) ){
        //         $truck->POST_DATA = get_post( $post_id[0] ) ?? null;
        //     }
        // }
        //
        // $old_price = ( isset( $truck->OLDPRICE ) && $truck->OLDPRICE !== "" && $truck->OLDPRICE !== null && $truck->OLDPRICE !== 0 && $truck->OLDPRICE !== "0" ) ? $truck->OLDPRICE : 0;

        // $card = ( object ) [
        //     'href'      => $truck->POST_DATA->guid ?? null,
        //     'origin'    => $truck->CONTEXT->origin ?? null,
        //     'year'      => $truck->YEAR,
        //     'name'      => ( $truck->POST_DATA->post_title ) ?? null,
        //     'image'     => ( $truck->PHOTOPATH ) ? $truck->PHOTOPATH . $defaults[ 'image' ] : $defaults[ 'image_FALLBACK' ],
        //     'price'     => ( object ) [
        //         'changed'           => ( $truck->PRICECHANGED ) ? $truck->PRICECHANGED : 'Y',
        //         'current'           => $truck->PRICE,
        //         'current_FORMAT'    => number_format( $truck->PRICE, 2 ),
        //         'current_STRING'    => "$" . number_format( $truck->PRICE, 2 ),
        //         'old'               => $old_price,
        //         'old_FORMAT'        => number_format( (int) $old_price, 2 ),
        //         'old_STRING'        => "$" . number_format( (int) $old_price, 2 )
        //     ],
        //     'mileage'   => ( object ) [
        //         'current'           => $truck->MILEAGE,
        //         'current_FORMAT'    => number_format( $truck->MILEAGE),
        //         'current_STRING'    => number_format( $truck->MILEAGE) . " Miles",
        //         'ecm'               => $truck->ECMMILEAGE,
        //         'ecm_FORMAT'        => number_format( $truck->ECMMILEAGE),
        //         'ecm_STRING'        => number_format( $truck->ECMMILEAGE)
        //     ],
        //     'location'  => ( object ) [
        //         'id'                => $truck->BRANCHID,
        //         'city'              => $truck->LOCATION,
        //         'city_FORMAT'       => str_replace( ' ', '-', $truck->LOCATION ),
        //         'state'             => $truck->STATE,
        //         'zip'               => null,
        //         'city_state'        => set_city_state( $truck->BRANCHID ),
        //     ],
        //     'truck'     => ( object ) [
        //         'stocknum'          => $truck->STOCKNUM,
        //         'year'              => $truck->YEAR,
        //         'make'              => $truck->MANUFACTURER,
        //         'model'             => $truck->MODEL,
        //         'mileage'           => $truck->MILEAGE,
        //         'mileage_FORMAT'    => number_format( $truck->MILEAGE ),
        //         'fleet_num'         => $truck->FNUM
        //     ],
        //     'promotion' => ( isset( $truck->CONTEXT->promotion->title ) ) ? $truck->CONTEXT->promotion : null
        // ];

        $title_array = explode( ' ', $truck->title );

        $truck->year = $title_array[0];

        $truck->make = $title_array[1];

        $truck->model = $title_array[2];

    }

    $card = $truck;

    // SEE( $card );
?>

<a href="<?php echo $card->href; ?>"
    class="LOCATION-TRUCK-CARD LOCATION-ID LOCATION-STATE relative block overflow-hidden truck-card bg-gray-100"
    data-truckyear="<?php echo $card->year; ?>"
    data-truckmileage="<?php echo $card->mileage; ?>"
    data-location="<?php echo $card->city_state; ?>"
    data-stocknumber="<?php echo $card->inventory_ID; ?>">
    <div class="image-wrapper relative aspect-4/3">

        <?php
            ll_include_component(
                'fit-image',
                [
                    'image_id'          => $card->image,
                    'thumbnail_size'    => 'full'
                ]
            );
        ?>

        <?php if ( ! $defaults[ 'list' ] ) : ?>
            <div class="tag-wrapper hidden">
                <span>Featured</span>
            </div> <!-- /.tag-wrapper -->
        <?php endif; ?>

    </div> <!-- /.image-wrapper -->
    <div class="card-details bg-gray-100">

        <?php if ( ! str_contains( $card->old_price, '0.00') ) : ?>
            <h3 class="truck-title <?php echo $defaults[ 'all' ] ? 'text-sm md:text-lg' : 'text-sm'; ?> font-bold text-gray-400 mb-1/2 lg:mb-2">
                <?php echo $card->title; ?>
                <span class="text-sm text-brand-primary inline-block hidden lg:block" style="text-decoration: line-through;"><?php echo $card->old_price; ?></span>
                <span class="text-sm text-brand-primary inline-block hidden lg:block" style="font-weight: 100;">Incentives available. Call for current pricing.</span>
            </h3>
        <?php else : ?>
            <h3 class="truck-title <?php echo $defaults[ 'all' ] ? 'text-sm md:text-lg' : 'text-sm'; ?> font-bold text-gray-400 mb-1/2 lg:mb-2">
                <?php echo $card->title; ?>
                <span class="text-sm text-brand-primary inline-block hidden lg:block">
                    <?php echo $card->price; ?>
                </span>
            </h3>
        <?php endif; ?>


        <p class="<?php echo $defaults[ 'all' ] ? 'text-sm md:text-base' : 'text-sm'; ?> mb-1/2 lg:mb-3 font-medium">
            <span class="text-brand-primary inline-block lg:hidden">
                <?php echo $card->price; ?>
            </span>

            <span class="inline-block mx-1 lg:hidden"></span>
            <span class="text-gray-300 inline-block">
                <?php echo $card->mileage; ?>
            </span>

        </p>
        <div class="card-meta text-gray-300 <?php echo $defaults[ 'all' ] ? 'text-xs md:text-base' : 'text-xs'; ?>">
            <p>
                <svg class="icon icon-pin svg-align text-lg mr-1">
                    <use xlink:href="#icon-pin"></use>
                </svg>
                <?php echo $card->city_state ?>
            </p>
        </div> <!-- /.flex -->
    </div> <!-- /.bg-gray-100 -->
</a> <!-- /.relative -->
