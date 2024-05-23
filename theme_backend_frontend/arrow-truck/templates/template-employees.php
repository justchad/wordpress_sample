<?php
/*
Template Name: Employees

*/

    global $wp;

    $build = new ArrowBuildEmployee();

    $get_var = strtolower( $_GET['language'] ) ?? null;

    $reps   = [];

    $roles  = ARROW_USER_ROLES;

    $user_query = get_users( [ 'role__in' => $roles ] );

    foreach( $user_query as $user_key => $user ){

        if ( ! $user ) {
            continue;
        }

        $meta = ll_safe_decode( get_user_meta( $user->ID, 'DATA')[0] );

        if ( ! $meta ) {
            continue;
        }

        $languages = $build->set_language_list( $meta->languages );

        if ( ! is_array( $languages ) ) {
            continue;
        }

        $meta->card->languages = $languages;

        if ( ! in_array( $get_var, $meta->card->languages ) ) {
            continue;
        }

        $location_args = [
            'meta_key'      => 'BRANCH_ID',
            'meta_value'    => $meta->card->branch_ID,
            'post_type'     => ARROW_LOCATION_POST_TYPE,
            'numberposts'   => 1
        ];

        $location = get_posts( $location_args )[0];

        $branch = ll_safe_decode( get_post_meta( $location->ID, 'DATA')[0] );

        $meta->card->branch = ( object ) [
            'ID'            => $branch->ID,
            'name'          => $branch->name,
            'href'          => $branch->WP->href,
            'phone'         => format_phone( $branch->contact->toll_free_1 ),
            'city_state'    => $branch->address->city_state
        ];

        $meta->card->phone = format_phone( $branch->contact->toll_free_1 );

        $reps[ $meta->ID ] = $meta->card;
    }

    // SEE( $reps );

?>

<?php while ( have_posts() ) : the_post(); ?>

    <?php
        ll_include_component(
          'hero-banner',
          array(
            'hdg' => array(
              'tag'  => 'h2',
              'text' => count( $reps ) . ' ' . ucfirst( strtolower( $get_var ?? "" ) ) . ' Speaking Representatives'
            ),
            'scroll_hdg'     => 'Sales Reps',
            'image_id'       => '642',
            'image_focus'    => 'object-center',

          )
        );
    ?>

    <section class="container">
        <div class="row justify-center">
            <?php if ( $reps ) : ?>

                <div class="all-employees-wrapper w-full md:w-10/12 flex flex-wrap justify-start">

                    <?php foreach ( $reps as $rep ) : ?>

                        <div class="all-employees-card w-full sm:w-6/12 md:w-6/12 lg:w-4/12 flex flex-col">

                            <div class="all-employees-card-inner">
                                <div class="all-employees-card-image w-full" data-employee-repno="<?php echo $rep->ID; ?>" style="background-image:url(<?php echo $rep->profile; ?>)">

                                </div>
                                <div class="all-employees-card-info w-full flex flex-col">
                                    <h2><?php echo $rep->name->full; ?></h2>
                                    <h3><?php echo $rep->title; ?></h3>
                                    <div class="employee-card-info-branch w-full flex flex-row">
                                        <span>Branch: </span>
                                        <span>
                                            <a href="<?php echo $rep->branch->href; ?>">
                                                <?php echo $rep->branch->name; ?>
                                            </a>
                                        </span>
                                    </div>

                                    <div class="employee-card-coms-wrapper">
                                        <div class="employee-card-info-branch-phone w-full">
                                            <?php if( $rep->branch->phone ) : ?>
                                                <a href="tel:<?php echo $rep->branch->phone; ?>">
                                                    <?php echo format_phone( $rep->branch->phone ); ?>
                                                </a>
                                            <?php endif ; ?>
                                        </div>
                                        <div class="employee-card-info-branch-email w-full">
                                            <?php if( $rep->email ) : ?>
                                                <a href="mailto:<?php echo $rep->email; ?>">
                                                    Email
                                                </a>
                                            <?php endif ; ?>
                                        </div>
                                    </div>


                                    <h2 class="employee-card-header-small">Languages</h2>
                                    <div class="all-employee-card-language-wrapper">

                                        <?php foreach ( $rep->languages as $lang ) : ?>

                                            <span><?php echo ucfirst( $lang ); ?></span>

                                        <?php endforeach; ?>

                                    </div>

                                    <div class="all-employee-card-bio">

                                        <?php if ( ! empty( $rep->bio ) ) : ?>

                                            <button type="button" class="all-employee-card-bio-button is-open-trigger">Learn More</button>
                                            <div class="all-employee-card-bio-content is-open-container hidden">

                                                <?php echo $rep->bio; ?>

                                            </div>

                                        <?php endif; ?>

                                    </div>
                                </div>
                            </div>

                        </div>

                    <?php endforeach; ?>
                </div>

            <?php endif; ?>
        </div>
    </section>

    <script type="text/javascript">
        $( '.all-employee-card-bio-button' ).click( function() {
            $(this)
            .parent()
            .find('.all-employee-card-bio-content')
            .toggleClass('hidden');
        } );
    </script>

<?php endwhile; ?>
