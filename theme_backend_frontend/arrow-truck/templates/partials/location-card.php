<?php
    $address = "&street={$location->address->line_1}&city={$location->address->city}&state={$location->address->state}&zip={$location->address->zip}";
?>


<?php if( $location != null && $location->ID != 'TO' ) : ?>
    <div class="col w-full md:w-1/2 lg:w-1/3 mt-8">
        <div class="location-card branch-location-name-<?php echo $location->ID; ?> border border-gray-200 rounded p-4 h-full flex flex-col justify-between" data-address="<?php echo $address; ?>" data-coordinates="<?php echo  $location->address->geo->lat_long; ?>">
            <div class="flex-0">
                <div class="image-wrapper aspect-16/9 relative">
                    <?php
                        ll_include_component(
                            'fit-image',
                            [
                                'image_id'            => $location->image,
                                'thumbnail_size'      => 'medium_large'
                            ]
                        );
                    ?>
                    <a href="<?php echo $location->WP->href; ?>" class="overlay-link">
                        <span class="sr-only">
                            See Details about <?php echo get_the_title( $location->WP->ID ); ?>
                        </span>
                    </a>
                </div> <!-- /.image-wrapper -->
                <h2 class="hdg-4 mt-4">
                    <a href="<?php echo $location->WP->href; ?>" class="hover:underline">
                        <?php echo $location->name; ?> Branch
                    </a>
                </h2>
                <?php if ( $location->address ) : ?>
                    <a href="<?php echo $location->address->link; ?>" class="inline-block hover:underline" target="_blank">
                        <address class="not-italic font-bold mt-3 text-gray-400">
                            <span class="block">
                                <?php echo $location->address->line_1; ?>
                            </span>
                            <span class="block">
                                <?php echo $location->address->city; ?>, <?php echo $location->address->state; ?> <?php echo $location->address->zip; ?>
                            </span>
                        </address>
                    </a>
                <?php endif; ?>
                <p class="text-sm mt-1"><?php echo $location->address->exit; ?></p>
                <?php if ( $location->contact->toll_free_1 ) : ?>
                    <p class="mt-4">
                        <a href="tel:+1<?php echo $location->contact->toll_free_1; ?>" class="text-sm font-bold text-gray-300 hover:underline">
                            +1 <?php echo format_phone( $location->contact->toll_free_1 ) ?>
                        </a>
                    </p>
                <?php endif; ?>
            </div> <!-- /.flex-0 -->
            <p class="text-right mt-4 flex-0">
                <a href="<?php echo $location->WP->href; ?>" class="btn is-plain font-normal">
                    See Details <span class="sr-only">about <?php echo $location->name; ?></span>
                </a>
            </p>
        </div> <!-- /.location-card -->
    </div> <!-- /.col -->
<?php endif; ?>
