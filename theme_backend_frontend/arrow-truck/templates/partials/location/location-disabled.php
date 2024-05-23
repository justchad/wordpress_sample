<div class="single-location location-not-active" data-component="locations">
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
                            'image_id'          => $location->image,
                            'thumbnail_size'    => 'full'
                        ]
                    );
                ?>
            </div> <!-- /.image-wrapper -->
        </div>
    </div>
    <div class="container block md:grid md:grid-cols-8 lg:grid-cols-12 gap-x-13" data-component="set-code" data-code="<?php echo $location->ID ?>">
        <div class="md:col-span-8 lg:col-span-10 lg:col-start-2 text-center">
            <div class="location-NO-CONTENT">
                <div class="mb-16">
                    <h2 class="block container hdg-1 md:text-5xl px-gutter text-2xl text-center mb-8">
                        <?php echo $content->title; ?>
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
                </div>

                <!-- ABOUT 4 -->
                <div class="block container mb-16 text-left">
                    <?php echo $content->about_4; ?>
                </div>
            </div>
        </div>
    </div>
</div>
