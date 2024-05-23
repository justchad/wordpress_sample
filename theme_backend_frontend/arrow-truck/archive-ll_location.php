<?php
    $locations = [];

    $pins  = [];

    $build      = new ArrowBuildEmployee();

    $refresh_languages = ARROW_REFRESH_LANGUAGE_LIST;

    if ( class_exists( 'ACF' ) ) {
        $refresh_languages = get_field( 'employee_refresh_language_list', 'option' );
    }

    $languages = $build->get_language_list( $refresh_languages );

    // LOCATIONS
    $args = [
        'post_type'       => ARROW_LOCATION_POST_TYPE,
        'numberposts'     => -1
    ];

    $wp_posts = get_posts( $args );

    foreach( $wp_posts as $key => $post ){

        if ( ! $post ) {
            continue;
        }

        $meta = ll_safe_decode( get_post_meta( $post->ID, 'DATA')[0] );

        $locations[ $meta->ID ] = $meta;

        $geo = [
            'address'       => $meta->address,
            'coordinates'   => $meta->address->geo->lat_long,
        ];

        $pins[ $meta->ID ] = $geo;
    }

?>

<div class="locations-page" data-component="locations">
    <div class="location-search-form-languages-container">
        <div class="container">
            <div class="row justify-start">
                <div class="col w-full language-submit-wrapper flex pt-8">
                    <h2 class="hdg-0 md:text-l mb-8">
                        To find an Arrow representative that speaks a specific language, select from the drop-down below.
                    </h2>
                    <form class="w-full location-search-form-languages flex items-stretch justify-between">
                        <label for="zip" class="sr-only">Language</label>
                        <select id="location-language-select" type="text" class="border border-gray-200 opacity-100 w-full rounded py-2 px-4 mr-2" name="language" placeholder="Language">
                            <option value="None" selected>Select Language</option>

                            <?php foreach ( $languages as $language ) : ?>

                                <option value="<?php echo $language[ 'name' ]; ?>"><?php echo $language[ 'name' ]; ?></option>

                            <?php endforeach; ?>

                        </select>

                        <script type="text/javascript">
                            $('#location-language-select').on('change', function() {
                                if(this.value != 'None'){
                                    window.location = '/all-employees/?language=' + this.value;
                                }
                            });
                        </script>

                    </form> <!-- /.location-search-form -->
                </div>
            </div> <!-- /.row -->
        </div> <!-- /.container -->
    </div>
    <div class="container pt-8 pb-24 locations-area">
        <div class="row justify-center">
            <div class="col w-full xl:w-10/12">
                <div class="row locations-row">
                    <?php foreach ( $locations as $location ) : ?>
                        <?php include(locate_template( 'templates/partials/location-card.php' )); ?>
                    <?php endforeach; ?>
                </div> <!-- /.row -->
            </div> <!-- /.col -->
        </div> <!-- /.row -->
    </div> <!-- /.container -->
</div> <!-- /.locations-page -->
