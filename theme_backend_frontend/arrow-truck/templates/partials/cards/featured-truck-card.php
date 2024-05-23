<?php

    $defaults = [
        'image'             => "image01.jpg",
        'image_FALLBACK'    => "https://www.arrowtruckhost.com/images/NoImage2.jpg",
        'list'              => ( isset( $component_data['list'] ) ) ? true : false,
        'featured'          => true,
        'all'               => ( isset( $component_data['all_trucks'] ) ) ? true : false,
    ];

    $old_price = ( isset( $truck->OLDPRICE ) && $truck->OLDPRICE !== "" && $truck->OLDPRICE !== null && $truck->OLDPRICE !== 0 && $truck->OLDPRICE !== "0" ) ? $truck->OLDPRICE : 0;

    $card = ( object ) [
        'href'      => $truck->PERMALINK,
        'origin'    => $truck->CONTEXT->origin,
        'name'      => $truck->POST_DATA->post_title,
        'image'     => ( $truck->PHOTOPATH ) ? $truck->PHOTOPATH . $defaults[ 'image' ] : $defaults[ 'image_FALLBACK' ],
        'price'     => ( object ) [
            'changed'           => ( $truck->PRICECHANGED ) ? $truck->PRICECHANGED : 'Y',
            'current'           => $truck->PRICE,
            'current_FORMAT'    => number_format( $truck->PRICE, 2 ),
            'current_STRING'    => "$" . number_format( $truck->PRICE, 2 ),
            'old'               => $old_price,
            'old_FORMAT'        => number_format( (int) $old_price, 2 ),
            'old_STRING'        => "$" . number_format( (int) $old_price, 2 )
        ],
        'mileage'   => ( object ) [
            'current'           => $truck->MILEAGE,
            'current_FORMAT'    => number_format( $truck->MILEAGE),
            'current_STRING'    => number_format( $truck->MILEAGE) . " Miles",
            'ecm'               => $truck->ECMMILEAGE,
            'ecm_FORMAT'        => number_format( $truck->ECMMILEAGE),
            'ecm_STRING'        => number_format( $truck->ECMMILEAGE)
        ],
        'location'  => ( object ) [
            'id'                => $truck->CONTEXT->current_location_id,
            'city'              => $truck->CONTEXT->address->city,
            'city_FORMAT'       => str_replace( ' ', '-', $truck->CONTEXT->address->city ),
            'state'             => $truck->CONTEXT->address->state,
            'zip'               => $truck->CONTEXT->address->zip,
        ],
        'truck'     => ( object ) [
            'stocknum'          => $truck->STOCKNUM,
            'year'              => $truck->YEAR,
            'make'              => $truck->MANUFACTURER,
            'model'             => $truck->MODEL,
            'mileage'           => $truck->MILEAGE,
            'mileage_FORMAT'    => number_format( $truck->MILEAGE ),
            'fleet_num'         => $truck->FNUM
        ],
        'promotion' => ( $truck->CONTEXT->promotion->title ) ? $truck->CONTEXT->promotion : null
    ];

    $all = false;

    if ( isset( $component_data['all_trucks'] ) && $component_data['all_trucks'] ) {

        $all = true;

    }

?>

    <a
        href="<?php echo $card->href; ?>"
        class="FEATURED-TRUCK-CARD LOCATION-ID-<?php echo $card->location->id ?> LOCATION-CITY-<?php echo $card->location->city_FORMAT ?> LOCATION-STATE-<?php echo $card->location->state ?> relative block overflow-hidden truck-card bg-gray-100"
        data-pricechanged="<?php echo $card->price->changed; ?>"
        data-truckyear="<?php echo $card->truck->year; ?>"
        data-truckmileage="<?php echo $truck->MILEAGE; ?>"
        data-location="<?php echo $card->location->city . ', ' . $card->location->state; ?>"
        data-stocknumber="<?php echo $card->truck->stocknum; ?>"
        data-fleetnumber="<?php echo $card->truck->fleet_num; ?>">

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

            <?php if ( ! $defaults[ 'list' ] && $truck->INVSPCD === 'Y' ) : ?>

                <div class="tag-wrapper hidden">
                    <span>Featured</span>
                </div> <!-- /.tag-wrapper -->

            <?php endif; ?>

        </div> <!-- /.image-wrapper -->

        <div class="card-details bg-gray-100">

            <?php if ( $card->price->changed == 'Y' && $card->price->old !== 0 && $defaults[ 'featured' ] !== true ) : ?>

                <h3 class="truck-title <?php echo $defaults[ 'all' ] ? 'text-sm md:text-lg' : 'text-sm'; ?> font-bold text-gray-400 mb-1/2 lg:mb-2">
                    <?php echo $card->name; ?>
                    <span class="text-sm text-brand-primary inline-block hidden lg:block" style="text-decoration: line-through;">
                        <?php echo $card->price->old_STRING; ?>
                    </span>
                    <span class="text-sm text-brand-primary inline-block hidden lg:block" style="font-weight: 100;">
                        Incentives available. Call for current pricing.
                    </span>
                </h3>

            <?php else : ?>

                <h3 class="truck-title <?php echo $defaults[ 'all' ] ? 'text-sm md:text-lg' : 'text-sm'; ?> font-bold text-gray-400 mb-1/2 lg:mb-2">
                    <?php echo $card->name; ?>
                    <span class="text-sm text-brand-primary inline-block hidden lg:block">
                        <?php echo $card->price->current_STRING; ?>
                    </span>
                </h3>

            <?php endif; ?>

            <?php if( $card->promotion !== null ) : ?>

                <a class="truck-card-promo-link" href="">
                    <span class="truck-card-promo-text text-brand-primary inline-block hidden lg:block" style="font-weight: bold;display:block;">
                        <?php echo $card->promotion->title; ?>
                    </span>
                </a>

            <?php endif; ?>

            <p class="<?php echo $defaults[ 'all' ] ? 'text-sm md:text-base' : 'text-sm'; ?> mb-1/2 lg:mb-3 font-medium">
                <span class="inline-block mx-1 lg:hidden">
                    <?php echo $card->mileage->current_FORMAT; ?>
                </span>
                <span class="text-gray-300 inline-block">
                    <?php echo $card->mileage->current_STRING; ?>
                </span>
            </p>
            <div class="card-meta text-gray-300 <?php echo $defaults[ 'all' ] ? 'text-xs md:text-base' : 'text-xs'; ?>">

                <?php

                    $city_state = ucwords( strtolower( $card->location->city ) ) . ", {$card->location->state}";

                    if ( $card->location->city_FORMAT == 'CHICAGO' ) {

                        $city_state = 'Chicago, IL';

                    }

                    if ( $card->location->city_FORMAT == 'ST-LOUIS' ) {

                        $city_state = 'St. Louis, MO';

                    }

                ?>

                <p>
                    <svg class="icon icon-pin svg-align text-lg mr-1"><use xlink:href="#icon-pin"></use></svg>
                    <?php echo $city_state ?>
                </p>
            </div> <!-- /.flex -->
        </div> <!-- /.bg-gray-100 -->
    </a> <!-- /.relative -->
