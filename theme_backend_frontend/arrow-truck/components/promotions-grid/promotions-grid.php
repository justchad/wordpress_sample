<?php
    /**
    * Promotions Grid
    * -----------------------------------------------------------------------------
    *
    * Promotions Grid component
    */

    $defaults = [
      'small_hdg'               => [],
      'hdg'                     => [],
      'promotions'              => [],
    ];

    $component_data = ll_parse_args( $component_data, $defaults );
?>

<?php
    /**
     * Any additional classes to apply to the main component container.
     *
     * @var array
     * @see args['classes']
     */
    $classes = ( isset( $component_args['classes'] ) ? $component_args['classes'] : array() );
    /**
     * ID to apply to the main component container.
     *
     * @var array
     * @see args['id']
     */
    $component_id   = ( isset( $component_args['id'] ) ? $component_args['id'] : false );

    $promoCount = count($component_data['promotions']);

    if($promoCount === 1){
        $dynClass = '';
    }elseif($promoCount === 2){
        $dynClass = 'md:w-1/2 lg:w-1/2';
    }else{
        $dynClass = 'md:w-1/2 lg:w-1/3';
    }
?>


<?php if ( ll_empty( $component_data ) ) return; ?>
<section class="promotions-grid <?php echo ll_format_bg( 'bg-white', '24', '6' ) ?> <?php echo implode( " ", $classes ); ?>" <?php echo ( $component_id ? 'id="'.$component_id.'"' : '' ) ?> data-component="promotions-grid">

  <div class="container">

    <div class="row justify-center">

      <div class="col w-full xl:w-10/12">

        <<?php echo $component_data['small_hdg']['tag']; ?> class="text-sm font-semibold text-brand-primary mb-10"><?php echo $component_data['small_hdg']['text']; ?></<?php echo $component_data['small_hdg']['tag']; ?>>

        <<?php echo $component_data['hdg']['tag']; ?> class="hdg-4 mb-8 with-bg block text-center sm:inline-block"><?php echo $component_data['hdg']['text']; ?></<?php echo $component_data['hdg']['tag']; ?>>

        <div class="row">


        <?php if ( ll_empty( $component_data['promotions'] ) ) : ?>

            <div class="col w-full">
                <h3 class="text-lg font-bold text-gray-400 my-4">No promotions running at this time.</h3>
            </div>

        <?php else : ?>

            <?php foreach ( $component_data['promotions'] as $key => $promotion ) : ?>

                           <?php if(get_post_status ( $promotion ) === 'publish' ) : ?>

                               <div class="col w-full <?php echo $dynClass; ?> mb-16">

                                 <div class="image-wrapper relative aspect-2/3">

                                   <?php
                                     ll_include_component(
                                       'fit-image',
                                       [
                                         'image_id'            => get_post_thumbnail_id( $promotion ) ,
                                         'thumbnail_size'      => 'full'
                                       ]
                                     );
                                   ?>

                                   <div class="tag-wrapper">

                                     <span>Promo</span>

                                   </div> <!-- /.tag-wrapper -->

                                 </div> <!-- /.image-wrapper -->

                                 <h3 class="text-lg font-bold text-gray-400 my-4"><?php echo get_the_title( $promotion ); ?></h3>

                                 <div class="wysiwyg">

                                   <?php echo get_the_content( '', '', $promotion ); ?>

                                 </div> <!-- /.wysiwyg -->

                                 <?php

                                     $p = get_field("promotions_builder", $promotion);

                                     $promo_array = [
                                         'invmake' => $p["promo_builder_make"]->name,
                                         'invmod' => $p["promo_builder_model"]->name,
                                         'invmilag_s' => $p["promo_builder_min_mileage"],
                                         'invmilag_e' => $p["promo_builder_mileage"],
                                         'invprice_s' => $p["promo_builder_min_price"],
                                         'invprice_e' => $p["promo_builder_price"],
                                         'fleet' => $p["promo_builder_fleet_code"],
                                         'invyear' => $p["promo_builder_min_year"],
                                         'invprice_s' => $p["promo_builder_max_year"],
                                     ];

                                     $button_array = [
                                         'promotionenabled' => get_field( "promotion_enable_promotion", $promotion ),
                                         'promoid' => $promotion,
                                         'description' => get_field( "promotion_description", $promotion ),
                                         'disclaimer' => get_field( "promotion_disclaimer", $promotion ),
                                     ];

                                     $query = http_build_query($promo_array);

                                 ?>

                                 <?php if ( $button_array['promotionenabled'] ) : ?>

                                     <div>
                                         <a class="btn" style="margin-top:2rem;" href="/search-inventory/?<?php echo $query; ?>" data-arrow-btn="true"><?php echo $button_array['description']; ?></a>
                                     </div>

                                 <?php else : ?>

                                     <div>
                                         <a class="btn" style="background-color:lightgray;margin-top:2rem;pointer-events: none;" href="javascript:void(0)"><?php echo $button_array['disclaimer']; ?></a>
                                     </div>

                                 <?php endif; ?>


                               </div> <!-- /.col -->

                           <?php endif ; ?>

                       <?php endforeach; ?>

        <?php endif; ?>



        </div> <!-- /.row -->

      </div> <!-- /.col -->

    </div> <!-- /.row -->

  </div> <!-- /.container -->

</section> <!-- /.promotions-grid -->
