<?php

    global $wp;

    $user       = get_user_by( 'slug', get_query_var( 'author_name' ) );

    $id         = $user->ID;

    $build      = new ArrowBuildEmployee();

    $templates  = $build->get_templates();

    $employee   = $build->get_employee( $user );

    $location   = $build->get_location( $employee, $_GET[ 'branch' ] ?? null );

    $inventory = $build->get_inventory( $location );

    // EXTRA CONTENT
    $content = null;

    if ( class_exists( 'ACF' ) && $employee->role == 'arrow_fandi_manager' ) {
        $content = get_field( 'employee_financing_content', 'option' );
    }
?>


<section class="arrowsalesrepview container grid md:grid-cols-6 lg:grid-cols-12 gap-13 mt-10 mb-16" data-component="set-code" data-code="<?php echo $employee->ID ?>">
    <div class="col-span-6 lg:col-span-4 lg:col-start-2">
        <figure class="aspect-square relative overflow-hidden rounded-full">

            <?php
                ll_include_component(
                    'fit-image',
                    [
                        'image_id' => $employee->media,
                        'size' => 'full'
                    ]
                )
            ?>

        </figure>

        <?php if ( $employee->languages ) : ?>

            <?php include( locate_template( $templates['languages'] ) ); ?>

        <?php endif; ?>

    </div>
    <div class="col-span-6 md:col-span-5 md:col-start-7">

        <h1 class="hdg-2 mb-5">
            <?php echo $employee->name->full; ?>
        </h1>
        <p class="paragraph-large font-semibold mb-4">
            <?php echo $employee->title; ?>
        </p>

        <div class="mb-13 inline-flex items-center flex-wrap">

            <?php include( locate_template( $templates['contact'] ) ); ?>

        </div>

        <!-- BIO -->
        <?php if ( $employee->bio ) : ?>

            <div class="wysiwyg mb-16">
                <h2 class="paragraph-large font-bold mb-4 mt-8">
                    About Me
                </h2>
                <?php echo format_text( $employee->bio ); ?>
            </div>

        <?php endif; ?>

    </div>
</section>

<?php if ( $employee->role == 'arrow_fandi_manager' ) : ?>

    <?php if ( $content ) : ?>

        <?php include( locate_template( $templates['financing-dynamic'] ) ); ?>

    <?php else : ?>

        <?php include( locate_template( $templates['financing'] ) ); ?>

    <?php endif; ?>

<?php endif; ?>


<?php

    ll_include_component(
        'featured-trucks-grid',
        [
            'hdg'               => [
                'text'              =>  $location->name . '\'s Featured Inventory',
                'tag'               => 'h3'
            ],
            'origin'            => 'author',
            'mobile_slider'     => false,
            'list'              => false,
            'all_trucks'        => false,
            'single'            => true,
            'location'          => $location->ID,
            'address'           => $location->address,
            'view_all'          => $location->inventory_link,
            'grid'              => 'location',
            'trucks'            => $build->get_featured( $inventory, ARROW_FEATURED_TRUCK_DISPLAY_LIMIT ),
            'promotion'         => $location->promotion,
            'promotion_text'    => null,
            'promotion_array'   => []
        ],
        [
            'id'                => 'featured-trucks',
            'classes'           => [ 'text-left' ]
        ]
    );
?>
