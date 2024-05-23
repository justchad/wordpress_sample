<?php
    $classes = 'grid-cols-5';

    if ( is_singular( 'll_inventory' ) ) {
        $classes = 'grid-cols-1 gap-x-3';
    } elseif ( get_field( 'page_bottom_nav' ) == 'buttons' ) {
        $classes = 'grid-cols-2 gap-x-3';
    }

    $templates = [
        'head'          => "templates/partials/head",
        'header'        => "templates/partials/header",
        'password_form' => "templates/partials/password-form",
        'footer'        => "templates/partials/footer"
    ];
?>

    <?php
        get_template_part( "templates/partials/head" );
    ?>

    <body <?php body_class(); ?> data-component="animations">

        <!-- Google Tag Manager (noscript) -->
        <noscript>
            <iframe src=https://www.googletagmanager.com/ns.html?id=GTM-PXHN8Q3 height="0" width="0" style="display:none;visibility:hidden"></iframe>
        </noscript>
        <!-- End Google Tag Manager (noscript) -->

        <?php //ll_output_analytics( $beginning_body_scripts ); ?>

        <?php include_once( 'assets/img/symbol-defs.svg' ); ?>

        <?php include_once( 'assets/img/symbol-defs-ui.svg' ); ?>

        <!--[if lt IE 8]>
        <div class="alert alert-warning">
        <?php _e('You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.', 'roots'); ?>
        </div>
        <![endif]-->

        <?php
            do_action( 'get_header' );
            get_template_part( "templates/partials/header" );
        ?>

        <div class="wrap" role="document">
            <main class="main" role="main">

                <?php if ( post_password_required() ) : ?>

                    <?php get_template_part( "templates/partials/password-form" ); ?>

                <?php else : ?>

                    <?php include roots_template_path(); ?>

                <?php endif; ?>

            </main><!-- /.main -->
        </div><!-- /.wrap -->


        <?php if ( get_field( 'page_bottom_nav' ) || is_post_type_archive( 'll_location' ) || is_singular( 'll_inventory' ) ) : ?>

            <?php if ( !is_archive() || get_field( 'page_bottom_nav' ) == 'none' ) : ?>

                <div id="bottom-nav" class="bottom-nav px-3 text-center grid lg:hidden <?php echo $classes; ?>">

                    <?php if ( get_field( 'page_bottom_nav' ) == 'icons' || is_post_type_archive( 'll_location' ) ) : ?>

                        <a href="<?php echo get_permalink( get_field( 'page_id_search_inventory', 'option' ) ); ?>" class="hover:underline">
                            <svg class="icon icon-search">
                                <use xlink:href="#icon-search"></use>
                            </svg>
                            Inventory
                        </a>

                        <a href="<?php echo get_permalink( get_field( 'page_id_estimate', 'option' ) ); ?>" class="hover:underline">
                            <svg class="icon icon-estimate">
                                <use xlink:href="#icon-estimate"></use>
                            </svg>
                            Estimate
                        </a>

                        <a href="tel:+1<?php echo strip_phone( get_field( 'contact_phone_number', 'option' ) );?>" class="hover:underline">
                            <svg class="icon icon-phone">
                                <use xlink:href="#icon-phone"></use>
                            </svg>
                            Call
                        </a>

                        <a href="<?php echo get_permalink( get_field( 'page_id_finance', 'option' ) ); ?>" class="hover:underline">
                            <svg class="icon icon-finance">
                                <use xlink:href="#icon-finance"></use>
                            </svg>
                            Finance
                        </a>

                        <a href="<?php echo get_permalink( get_field( 'page_id_locations', 'option' ) ); ?>" class="hover:underline">
                            <svg class="icon icon-pin">
                                <use xlink:href="#icon-pin"></use>
                            </svg>
                            Locations
                        </a>

                    <?php elseif ( is_singular( 'll_inventory' ) ) : ?>

                        <button class="btn is-plain" data-toggle-target="#get-started" data-toggle-class="is-open">
                            Get Started
                        </button>

                    <?php elseif ( get_field( 'page_bottom_nav' ) == 'buttons' ) : ?>

                        <a href="tel:+1<?php echo strip_phone( get_field( 'contact_phone_number', 'option' ) ); ?>" class="btn is-plain">
                            <svg class="icon icon-phone-alt text-lg svg-align mr-2">
                                <use xlink:href="#icon-phone-alt"></use>
                            </svg>
                            Call us
                        </a>

                        <a href="<?php echo get_permalink( get_field( 'page_id_quote', 'option' ) ); ?>" class="btn is-plain">
                            <svg class="icon icon-dollar text-xl svg-align mr-2">
                                <use xlink:href="#icon-dollar"></use>
                            </svg>
                            Get a Quote
                        </a>

                    <?php endif; ?>

                </div> <!-- /.bottom-nav -->

            <?php endif; ?>

        <?php endif; ?>

        <?php get_template_part( "templates/partials/footer" ); ?>

        <?php wp_footer(); ?>

    </body>
</html>
