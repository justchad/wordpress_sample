<?php
global $stock_num;
?>

<?php while (have_posts()) : the_post(); ?>
  <?php
    global $post, $truck;
    $arrow_inv_api = new ArrowApiInventory;
    $truck = $arrow_inv_api->getTruck( $stock_num )->all();
    $truck = new ArrowTruck( $truck );
    $truck->wp_post = $post;

    if ( !ll_get_raw_referer() || ll_get_raw_referer() == get_the_permalink() ) {
      $url = arrow_page_url( 'search_inventory' );
    } else {
      $url = ll_get_raw_referer();
    }

    // print_r($truck);
  ?>

  <section class="single-truck-page" data-component="single-truck">
    <div class="hidden max-w-screen-xl mx-auto wishlist-title xl:px-gutter sticky top-nav z-10 bg-white">
      <div class="px-gutter xl:px-4 flex justify-between items-start border-b border-gray-200 py-2">
        <h2 class="text-sm font-bold text-gray-400"><?php echo $truck->name ?></h2>

        <button class="add-to-wishlist" data-truck="<?php echo ll_safe_encode( $truck ); ?>" title="Add <?php echo $truck->name; ?> to Wishlist"><svg class="icon icon-heart text-2xl text-gray-200 pointer-events-none"><use xlink:href="#icon-heart"></use></svg></button>
      </div>
    </div> <!-- /.wishlist-title -->

    <!-- <div class="col w-full single-inventory-search-wrapper p-4 px-8">
      <div class="flex flex-wrap">
        <input type="text" name="" value="" placeholder="Search by Make, Model, or keyword"/>
      </div>
    </div> -->

    <div class="col w-full single-inventory-back p-4 px-8">
      <div class="flex flex-wrap">
        <a href="#"><- Back</a>
      </div>
    </div>

    <?php lll($truck) ?>


    <div class="col w-full single-inventory-item mx-0 px-0">
      <div class="flex flex-wrap">
        <div class="flex-initial w-full xl:w-2/3 single-inventory-left xl:pl-8">

          <div class="image-wrapper relative aspect-4/3 overflow-hidden single-hero-image">
            <?php
              ll_include_component(
                'fit-image',
                [
                  'image_id' => '/wp-content/themes/arrow-truck/assets/img/image01.png'
                ]
              );
            ?>
          </div> <!-- /.single-hero-image -->

          <div class="col w-full single-extra-features mx-0 px-0">
            <!-- extra features -->

          </div> <!-- /.single-extra-features -->

          <div class="col w-full mx-0 px-0 single-image-gallery mt-2">

            <div class="flex flew-wrap -mx-1 my-2 -mt-1">

              <div class="flex-initial px-1 w-full xl:w-1/2">
                <div class="image-wrapper relative aspect-4/3 overflow-hidden single-image-gallery-each">
                  <?php
                    ll_include_component(
                      'fit-image',
                      [
                        'image_id' => '/wp-content/themes/arrow-truck/assets/img/image02.png'
                      ]
                    );
                  ?>
                </div>
              </div>

              <div class="flex-initial px-1 w-full xl:w-1/2">
                <div class="image-wrapper relative aspect-4/3 overflow-hidden single-image-gallery-each">
                  <?php
                    ll_include_component(
                      'fit-image',
                      [
                        'image_id' => '/wp-content/themes/arrow-truck/assets/img/image03.png'
                      ]
                    );
                  ?>
                </div>
              </div>

            </div>

            <div class="flex flew-wrap -mx-1 my-2 -mt-1">

              <div class="flex-initial px-1 w-full xl:w-1/2">
                <div class="image-wrapper relative aspect-4/3 overflow-hidden single-image-gallery-each">
                  <?php
                    ll_include_component(
                      'fit-image',
                      [
                        'image_id' => '/wp-content/themes/arrow-truck/assets/img/image04.png'
                      ]
                    );
                  ?>
                </div>
              </div>

              <div class="flex-initial px-1 w-full xl:w-1/2">
                <div class="image-wrapper relative aspect-4/3 overflow-hidden single-image-gallery-each">
                  <?php
                    ll_include_component(
                      'fit-image',
                      [
                        'image_id' => '/wp-content/themes/arrow-truck/assets/img/image05.png'
                      ]
                    );
                  ?>
                </div>
              </div>

            </div>

            <div class="flex flew-wrap -mx-1 my-2 -mt-1">

              <div class="flex-initial px-1 w-full xl:w-1/2">
                <div class="image-wrapper relative aspect-4/3 overflow-hidden single-image-gallery-each">
                  <?php
                    ll_include_component(
                      'fit-image',
                      [
                        'image_id' => '/wp-content/themes/arrow-truck/assets/img/image06.png'
                      ]
                    );
                  ?>
                </div>
              </div>

              <div class="flex-initial px-1 w-full xl:w-1/2">
                <div class="image-wrapper relative aspect-4/3 overflow-hidden single-image-gallery-each">
                  <?php
                    ll_include_component(
                      'fit-image',
                      [
                        'image_id' => '/wp-content/themes/arrow-truck/assets/img/image07.png'
                      ]
                    );
                  ?>
                </div>
              </div>

            </div>

            <div class="flex flew-wrap -mx-1 my-2 -mt-1">

              <div class="flex-initial px-1 w-full xl:w-1/2">
                <div class="image-wrapper relative aspect-4/3 overflow-hidden single-image-gallery-each">
                  <?php
                    ll_include_component(
                      'fit-image',
                      [
                        'image_id' => '/wp-content/themes/arrow-truck/assets/img/image08.png'
                      ]
                    );
                  ?>
                </div>
              </div>

              <div class="flex-initial px-1 w-full xl:w-1/2">
                <div class="image-wrapper relative aspect-4/3 overflow-hidden single-image-gallery-each">
                  <?php
                    ll_include_component(
                      'fit-image',
                      [
                        'image_id' => '/wp-content/themes/arrow-truck/assets/img/image09.png'
                      ]
                    );
                  ?>
                </div>
              </div>

            </div>

            <div class="flex flew-wrap -mx-1 my-2 -mt-1">

              <div class="flex-initial px-1 w-full xl:w-1/2">
                <div class="image-wrapper relative aspect-4/3 overflow-hidden single-image-gallery-each">
                  <?php
                    ll_include_component(
                      'fit-image',
                      [
                        'image_id' => '/wp-content/themes/arrow-truck/assets/img/image10.png'
                      ]
                    );
                  ?>
                </div>
              </div>

              <div class="flex-initial px-1 w-full xl:w-1/2">
                <div class="image-wrapper relative aspect-4/3 overflow-hidden single-image-gallery-each">
                  <?php
                    ll_include_component(
                      'fit-image',
                      [
                        'image_id' => '/wp-content/themes/arrow-truck/assets/img/image11.png'
                      ]
                    );
                  ?>
                </div>
              </div>

            </div>

          </div> <!-- /.single-image-gallery -->

        </div>
        <div class="flex-initial w-full xl:w-1/3 single-inventory-right p-4 xl:p-16">

          <div class="single-get-started">

            <div class="single-info-pane">
              <!-- start info pane -->
              <h1>2016 VNL 670</h1>
              <h2 class="mb-6">Volvo</h2>
              <div class="single-price-miles overflow-auto flex mb-4">
                <h3 class="spm-red">$51,950.00</h3>
                <h3 class="spm-gray">509,448 Miles</h3>
              </div>
              <div class="single-vehicle-list-details flex flex-wrap">

                <div class="flex-initial w-full xl:w-1/2">
                  <span class="single-vehicle-details-icon sv-engine"></span>
                  <p>Engine</p>
                </div>

                <div class="flex-initial w-full xl:w-1/2">
                  <span class="single-vehicle-details-icon sv-horse-power"></span>
                  <p>Horse Power</p>
                </div>

                <div class="flex-initial w-full xl:w-1/2">
                  <span class="single-vehicle-details-icon sv-location"></span>
                  <p>Location</p>
                </div>

                <div class="flex-initial w-full xl:w-1/2">
                  <span class="single-vehicle-details-icon sv-stock-number"></span>
                  <p>Stock Number</p>
                </div>

              </div>
              <div class="single-truck-desription">
                <h2 class="my-4 mt-8">Truck Description</h2>
                <p>HARD TO FIND 670 ISX MANUAL TRANS. Isx 400hp, manual 10 speed transmission, adaptive cruise control with time gap, collision mitigation system and new 14/32 recaps on drives.</p>
              </div>
            </div> <!-- /.single-info-pane -->


            <div class="single-get-started-buttons flex flex-wrap">

              <h2 class="py-8">Get Started</h2>

              <div class="flex-initial w-full single-get-started-buttons-each my-1">
                <button type="button" name="button" class="w-full p-4 text-left rounded single-get-started-button">
                  <span class="single-get-started-button-icon sv-buynow"></span>
                  Buy Now
                </button>
              </div>

              <div class="flex-initial w-full single-get-started-buttons-each my-1">
                <button type="button" name="button" class="w-full p-4 text-left rounded single-get-started-button">
                  <span class="single-get-started-button-icon sv-downpaymentestimator"></span>
                  Down Payment Estimator
                </button>
              </div>

              <div class="flex-initial w-full single-get-started-buttons-each my-1">
                <button type="button" name="button" class="w-full p-4 text-left rounded single-get-started-button">
                  <span class="single-get-started-button-icon sv-prequalifyforcredit"></span>
                  Pre-Qualify for Credit
                </button>
              </div>

              <div class="flex-initial w-full single-get-started-buttons-each my-1">
                <button type="button" name="button" class="w-full p-4 text-left rounded single-get-started-button">
                  <span class="single-get-started-button-icon sv-creditapplication"></span>
                  Credit Application
                </button>
              </div>

              <div class="flex-initial w-full single-get-started-buttons-each my-1">
                <button type="button" name="button" class="w-full p-4 text-left rounded single-get-started-button">
                  <span class="single-get-started-button-icon sv-contactusaboutthistruck"></span>
                  Contact Us About This Truck
                </button>
              </div>

            </div> <!-- /.single-get-started-buttons -->


            <div class="single-drop-down-wrapper flex flex-wrap py-8">

              <div class="flex-initial w-full single-each-drop-down sv-featuresandspecs">
                <div class="single-each-drop-down-top w-full relative py-4 flex justify-between items-center border-t border-gray-300">
                  <h2 class="">Features & Specs</h2>
                  <span class="block single-drop-down-icon sv-chevron-down"></span>
                  <span class="hidden single-drop-down-icon sv-chevron-up"></span>
                </div>
                <div class="hidden single-each-drop-down-bottom p-4">
                  <!-- stuff goes here -->
                  Features and Specs drop down stuff.
                </div>
              </div>

              <div class="flex-initial w-full single-each-drop-down sv-warranties">
                <div class="single-each-drop-down-top w-full relative py-4 flex justify-between items-center border-t border-gray-300">
                  <h2 class="">Warranties</h2>
                  <span class="block single-drop-down-icon sv-chevron-down"></span>
                  <span class="hidden single-drop-down-icon sv-chevron-up"></span>
                </div>
                <div class="hidden single-each-drop-down-bottom p-4">
                  <!-- stuff goes here -->
                  Warranties drop down stuff.
                </div>
              </div>

              <div class="flex-initial w-full single-each-drop-down sv-caniaffordit">
                <div class="single-each-drop-down-top w-full relative py-4 flex justify-between items-center border-t border-b border-gray-300">
                  <h2 class="">Can I afford it?</h2>
                  <span class="block single-drop-down-icon sv-chevron-down"></span>
                  <span class="hidden single-drop-down-icon sv-chevron-up"></span>
                </div>
                <div class="single-each-drop-down-bottom p-4 bg-gray-100">
                  <!-- stuff goes here -->
                  Can I afford it drop down stuff.
                </div>
              </div>

            </div> <!-- /.single-drop-down-wrapper -->


            <div class="single-summary flex flex-wrap bg-gray-100 p-4">

              <h2 class="pb-4">Summary</h2>

              <div class="single-price-list flex-initial w-full sv-truckprice pb-4">
                <div class="w-full relative flex justify-between items-center single-price-list-each">
                  <span class="single-price-list-line-title">
                    Truck Price
                  </span>
                  <span class="single-price-list-line-price">
                    $51,950
                  </span>
                </div>
              </div>

              <div class="single-price-list flex-initial w-full sv-downpayment pb-4">
                <div class="w-full relative flex justify-between items-center single-price-list-each">
                  <span class="single-price-list-line-title">
                    Down Payment
                    <a href="#">(Get a trade-in offer)</a>
                  </span>
                  <span class="single-price-list-line-price">
                    -$2,000
                  </span>
                </div>
              </div>

              <div class="single-price-list flex-initial w-full sv-totalfinanceamount pb-4">
                <div class="w-full relative flex justify-between items-center single-price-list-each">
                  <span class="single-price-list-line-title single-price-list-bold">
                    Total Finance Amount
                  </span>
                  <span class="single-price-list-line-price single-price-list-bold">
                    $55,550
                  </span>
                </div>
              </div>



              <div class="single-estimated-monthly flex-initial w-full">

                <div class="single-estimated-monthly-title">
                  Estimated Monthly Payment
                </div>
                <div class="single-estimated-monthly-price">
                  $531 - $595
                </div>

                <button type="button" name="button">
                  Get Pre Approved for Credit
                </button>

                <p>Save time, apply in a few simple steps!</p>

              </div>

            </div> <!-- /.single-summary -->

          </div> <!-- /.single-get-started -->

          <div class="single-roadside-assistance flex flex-wrap py-8">
            <div class="single-roadside-wrapper flex-initial w-full relative p-8">
              <div class="flex justify-start single-roadside-inner">
                <div class="single-roadside-left">
                  <img src="/wp-content/themes/arrow-truck/assets/img/247.png" alt="">
                </div>
                <div class="single-roadside-right">
                  <h1>24/7 Roadside Assistance</h1>
                  <ul>
                    <li>
                      <span class="single-vehicle-checkmark"></span>
                      Towing Assistance
                    </li>
                    <li>
                      <span class="single-vehicle-checkmark"></span>
                      Mobile Mechanic Service
                    </li>
                    <li>
                      <span class="single-vehicle-checkmark"></span>
                      Tire Delivery Service
                    </li>
                    <li>
                      <span class="single-vehicle-checkmark"></span>
                      Free Delivery Service
                    </li>
                  </ul>
                  <button type="button" name="button">
                    Learn More ->
                  </button>
                </div>
              </div>
            </div>
            <div class="single-roadside-wrapper flex-initial w-full relative p-8 pt-4">
              <div class="flex justify-start single-roadside-inner">
                <div class="single-roadside-left">
                  <img src="/wp-content/themes/arrow-truck/assets/img/247.png" alt="">
                </div>
                <div class="single-roadside-right">
                  <h1>24/7 Roadside Assistance</h1>
                  <ul>
                    <li>
                      <span class="single-vehicle-checkmark"></span>
                      Towing Assistance
                    </li>
                    <li>
                      <span class="single-vehicle-checkmark"></span>
                      Mobile Mechanic Service
                    </li>
                    <li>
                      <span class="single-vehicle-checkmark"></span>
                      Tire Delivery Service
                    </li>
                    <li>
                      <span class="single-vehicle-checkmark"></span>
                      Free Delivery Service
                    </li>
                  </ul>
                  <button type="button" name="button">
                    Learn More ->
                  </button>
                </div>
              </div>
            </div>
          </div> <!-- /.single-roadside-assistance -->

        </div>
      </div>

    </div>



  </section> <!-- /.single-truck-page -->
<?php endwhile; ?>
