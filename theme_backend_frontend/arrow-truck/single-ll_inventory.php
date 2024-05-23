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

    if($truck->MODEL == 'Trailer'){
        $isTrailer = true;
    }else{
        $isTrailer = false;
    }


    // Promo stuff...

    // $promo_no_get_array = array();
    // $promo_no_get_text_array = array();
    // $promo_args = array(
    //     'numberposts'	=> -1,
    //     'post_type'		=> 'll_promotion',
    //     'meta_key'		=> 'promotion_enable_fleet_code',
    //     'meta_value'	=> '1'
    // );
    //
    // $promo_query = new WP_Query( $promo_args );
    //
    // $promo_temp_array = array();
    // $promo_temp_text_array = array();
    //
    // if(sizeof($promo_query->posts) >= 1){
    //     foreach ($promo_query->posts as $promo) {
    //         $promo_fleet_code_string = get_field( 'promotion_fleet_code', $promo->ID );
    //         $promo_temp_array[] = explode(",", $promo_fleet_code_string);
    //         $promo_each_temp = explode(",", $promo_fleet_code_string);
    //         foreach($promo_each_temp as $promotext){
    //             $promo_temp_text_array[$promotext] = $promo->post_title;
    //         }
    //     }
    // }
    //
    // $promo_no_get_array = array_merge(...$promo_temp_array);
    // $promo_no_get_text_array = $promo_temp_text_array;


    // ->>----------{> Activity Tracking <}----------<<-

    // yes, this should be turned into a class
    // no, there is no time to do this and it is not in the budget.
    // maybe we should do that later, but right now it iw working and the fact here is that working is a miracle.
    // not going to try and double down on miraculous at this point. anyway..

    // enable in site options, this is basically just a global on and off switch for this entire thing
    // admin/site-options/[site-options tab]/[CRPY Activity Tracking TRUE<>FALSE]
    $site_option_crpy_enabled = get_field( 'crpy_activity_tracking', 'option' );
    $crpy = $site_option_crpy_enabled;

    // this function delivers the dynamic object to Arrows, API endpoint.
    function deliverCookie($params){

        // explicitly encode array to send to activity api.
        $data = json_encode(
            array(
                'stock' => $params['stock'],
                'id' => $params['id'],
                'type' => $params['type'],
                'source' => $params['source']
            )
        );

        // send to arror activity api
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://arrowtruckservices.com/arrowapi2/api/Activity',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_POSTFIELDS => $data,
          CURLOPT_HTTPHEADER => array(
            'Api-Token: ab3c8c16708986299980187b990b3aa07362008d61d6ce1e8c9982ed34d721cc41dd610d',
            'Content-Type: application/json'
          ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
    }

    // this function rebuilds the url, if matching GET vars are set and/or if cookie is set and valid.
    function rebuildUrl($params){
        // pulling in known values and explicity setting the 'set' argument to avoid reload loop.
        $arg = $params;
        $name = $arg['name'];
        $url = '?id=' . $arg['id'] . '&type=' . $arg['type'] . '&source=' . $arg['source'] . '&stock=' . $arg['stock'] . '&set=' . $name;
        // we are setting 'set' implicitly, so the process knows that the url has been rebuilt. if the 'set' variable is set, we do nothing if not redirect from rebuilt url.
        if(!isset($_GET['set'])){
            header("Location: {$url}");
            exit;
        }
    }

    // http://arrowtruck.local/inventory/251806/?id=657890&type=lead&source=sfaInventorySearch

    // this funciton handleds the cookie in the case that the cookie is either A not set or B needs to be reset
    function bakeCookie($params){
        // cookie will continually be reset as long as the user is 'shopping' on the site, after 24 a day or two however and they haven't shopped on the site the cookie will expire and require instantiated again.
        $arg = $params;
        $name = $arg['name'];
        $transmission_object = array(
            'stock' => $arg['stock'],
            'id' => $arg['id'],
            'type' => $arg['type'],
            'source' => $arg['source']
        );
        $cookie_object = json_encode($transmission_object);
        setcookie($name, $cookie_object, time() + 604800, "/", NULL);
        deliverCookie($transmission_object);
    }

    // this is only chekcing the value here set in site options.
    if($crpy == true){
        $stocknumber = $truck->STOCKNUM;
        $source = 'sfaInventorySearch';
        $cookie_name = 'crpy';
        $param_arr = array();

        $url_stocknumber = ($stocknumber) ? $stocknumber : NULL;
        $url_id = NULL;
        $url_type = NULL;

        $crpy_gate_url = false;
        $crpy_gate_cookie = false;

        if(isset($_COOKIE[$cookie_name])){
            $cookie = json_decode(stripslashes($_COOKIE[$cookie_name]));
            if(isset($_GET['id']) && isset($_GET['type'])){
                $param_arr['stock'] = $stocknumber;
                $param_arr['id'] = $_GET['id'];
                $param_arr['type'] = $_GET['type'];
                $param_arr['source'] = $source;
                $param_arr['name'] = $cookie_name;

                if(isset($_GET['set'])){
                    bakeCookie($param_arr);
                }else{
                    rebuildUrl($param_arr);
                }

            }else{
                $param_arr['stock'] = $stocknumber;
                $param_arr['id'] = $cookie->id;
                $param_arr['type'] = $cookie->type;
                $param_arr['source'] = $source;
                $param_arr['name'] = $cookie_name;
                rebuildUrl($param_arr);
            }
        }else if(isset($_GET['id']) && isset($_GET['type'])){
            $param_arr['stock'] = $stocknumber;
            $param_arr['id'] = $_GET['id'];
            $param_arr['type'] = $_GET['type'];
            $param_arr['source'] = $source;
            $param_arr['name'] = $cookie_name;

            if(isset($_GET['set'])){
                bakeCookie($param_arr);
            }else{
                rebuildUrl($param_arr);
            }

        }else{
            // nothing to set stop processing.
        }
    }

    // $sync_args = [ 'delete' => true, 'insert' => true, 'preflight' => true, 'details' => true, 'encode' => true ];
    // ArrowApiLocation::sync_V2( $sync_args );
    // SEE( get_transient( 'location_transient_timestamp' ) );
    // SEE( get_transient( 'location_transient' ) );
?>



<!-- <h2>Truck Inquiry [ifurlparam param="truck_year"] for: <span class="beBold">[urlparam param="truck_year"][/ifurlparam] [ifurlparam param="truck_make"][urlparam param="truck_make"][/ifurlparam] [ifurlparam param="truck_model"][urlparam param="truck_model"][/ifurlparam] [ifurlparam param="truck_stock"]([urlparam param="truck_stock"])</span>[/ifurlparam].</h2> -->
<!-- <h2>Truck Inquiry<strong>[ifurlparam param="truck_year"][urlparam param="truck_year"][/ifurlparam]{truck_year} {truck_make}  {truck_model}  ({truck_stock})</strong></h2> -->

<section class="single-truck-page" data-component="single-truck">
  <div class="hidden max-w-screen-xl mx-auto wishlist-title lg:px-gutter sticky top-nav z-10 bg-white">
    <div class="px-gutter lg:px-4 flex justify-between items-start border-b border-gray-200 py-2">
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
      <button onclick="history.back()"><- Back</button>
    </div>
  </div>

  <div class="col w-full single-inventory-item mx-0 px-0">

    <div class="single-info-pane block lg:hidden p-8 pb-0 on-single-ll-inventory-one">

      <!-- start info pane -->
      <h2 style="font-size: 2rem;" class="border-b border-solid border-gray-300 text-xxl py-4 single-info-pane-headline"><?php echo $truck->YEAR . ' ' . $truck->MANUFACTURER . ' ' . $truck->MODEL; ?></h2>
      <div class="single-price-miles overflow-auto mb-4">

          <?php if($truck->PRICECHANGED != 'Y') : ?>
              <h3 class="spm-red">$<?php echo number_format($truck->PRICE); ?></h3>
          <?php else : ?>
              <h3 class="spm-red" style="font-weight: normal;"><span style="text-decoration: line-through;display: inline-block;margin-right: 1rem;font-weight: bold;">$<?php echo number_format($truck->OLDPRICE); ?></span>Incentives available.</h3>
              <span class="spm-red" style="font-size: 1.25rem;margin-bottom: 1rem;display: block;">Call for current pricing.</span>
          <?php endif; ?>



          <?php if($truck->PROMO) : ?>
              <?php foreach( $truck->PROMO as $key => $value ) : ?>
                  <?php
                    $promo_array = explode('|', $value);
                  ?>
                  <a class="truck-card-promo-link" href="<?php echo $promo_array[1]; ?>">
                      <span class="truck-card-promo-text text-brand-primary inline-block hidden lg:block" style="font-weight: bold;display:block;"><?php echo $promo_array[0]; ?></span>
                  </a>
              <?php endforeach; ?>
          <?php endif; ?>

        <?php if($truck->MODEL != 'Trailer') : ?>
            <h3 class="spm-gray"><?php echo number_format($truck->MILEAGE); ?> Miles</h3>
            <h3 class="spm-gray" style="font-size: 1rem;opacity: 0.7;">ECM Mileage: <?php echo number_format($truck->ECMMILEAGE); ?></h3>
        <?php endif; ?>
      </div>
      <div class="single-vehicle-list-details flex flex-wrap" style="margin-bottom:2rem;">

        <div class="flex flex-initial w-full">
          <svg class="icon icon-semi-truck mr-2 text-xl svg-align"><use xlink:href="#icon-semi-truck"></use></svg>
          <p><?php echo $truck->ENGINEMAKE; ?> <?php echo $truck->ENGINEMODEL; ?></p>
        </div>

        <div class="flex flex-initial w-full">
          <svg class="icon icon-speedometer mr-2 text-xl svg-align"><use xlink:href="#icon-speedometer"></use></svg>
          <p>HP: <?php echo $truck->HORSEPOWER; ?></p>
        </div>

        <div class="flex flex-initial w-full">
          <svg class="icon icon-pin mr-2 text-xl svg-align"><use xlink:href="#icon-pin"></use></svg>
          <?php

            $singleLocation = $truck->LOCATION;
            $singleState = $truck->STATE;

            if($singleLocation == 'St. Louis'){
                $singleState = 'MO';
            }

          ?>
          <p><?php echo $singleLocation; ?>, <?php echo $singleState; ?></p>
        </div>

        <div class="flex flex-initial w-full">
          <!-- <span class="single-vehicle-details-icon sv-stock-text">Stock #</span> -->
          <p class="mt-2">Stock # <?php echo $truck->STOCKNUM; ?></p>
        </div>

      </div>

    </div> <!-- /.single-info-pane -->

    <div class="block lg:hidden">
      <?php if ( $truck->images->count() > 1 ) : ?>
        <div class="p-8 main-image-wrapper">
          <?php foreach( $truck->images as $key => $image ) : ?>

              <?php

                  $photoPath = $truck->PHOTOPATH;


                  if($key == 0){
                      $imageName = 'image01.jpg';
                  }elseif($key == 1){
                      $imageName = 'image01.jpg';
                  }elseif($key == 2){
                      $imageName = 'image02.jpg';
                  }elseif($key == 3){
                      $imageName = 'image03.jpg';
                  }elseif($key == 4){
                      $imageName = 'image04.jpg';
                  }elseif($key == 5){
                      $imageName = 'image05.jpg';
                  }elseif($key == 6){
                      $imageName = 'image06.jpg';
                  }elseif($key == 7){
                      $imageName = 'image07.jpg';
                  }elseif($key == 8){
                      $imageName = 'image08.jpg';
                  }elseif($key == 9){
                      $imageName = 'image09.jpg';
                  }else{
                      $imageName = 'image' . $key . '.jpg';
                  }

              ?>



            <div class="image-wrapper relative aspect-4/3 overflow-hidden">
                <?php

                    // var_dump($imageName);

                    if($imageName != null ){
                        ll_include_component(
                          'fit-image',
                          [
                            'image_id' => $photoPath . $imageName,
							'default_asset_url' => 'https://www.arrowtruckhost.com/images/NoImage2.jpg'
                          ]
                        );
                    }

                ?>
              <a href="<?php echo $image; ?>" target="_blank" style="position: absolute;bottom: 0;font-size: .75rem;font-weight: bold;bottom: 0;right: 5px;color: rgba(255,255,255,0.5);">OPEN IN WINDOW</a>
            </div> <!-- /.image-wrapper -->
          <?php endforeach; ?>
        </div>

        <div class="images-gallery">
          <?php foreach( $truck->thumbnails as $key => $image ) : ?>

              <?php

                  $photoPath = $truck->PHOTOPATH;

                  if($key == 0){
                      $imageName = 'image01.tb.jpg';
                  }elseif($key == 1){
                      $imageName = 'image01.tb.jpg';
                  }elseif($key == 2){
                      $imageName = 'image02.tb.jpg';
                  }elseif($key == 3){
                      $imageName = 'image03.tb.jpg';
                  }elseif($key == 4){
                      $imageName = 'image04.tb.jpg';
                  }elseif($key == 5){
                      $imageName = 'image05.tb.jpg';
                  }elseif($key == 6){
                      $imageName = 'image06.tb.jpg';
                  }elseif($key == 7){
                      $imageName = 'image07.tb.jpg';
                  }elseif($key == 8){
                      $imageName = 'image08.tb.jpg';
                  }elseif($key == 9){
                      $imageName = 'image09.tb.jpg';
                  }else{
                      $imageName = 'image' . $key . '.tb.jpg';
                  }

              ?>


            <div class="image">

              <div class="image-wrapper relative aspect-4/3">

                <?php

                  ll_include_component(
                    'fit-image',
                    [
                      'image_id' => $photoPath . $imageName,
							'default_asset_url' => 'https://www.arrowtruckhost.com/images/NoImage2.jpg'
                    ]
                  );
                ?>

              </div> <!-- /.image-wrapper -->

            </div> <!-- /.image -->
          <?php endforeach; ?>

        </div> <!-- /.images-gallery -->


        <div class="video-wrapper">
            <?php if ( $truck->HASVIDEO == 'Y' ) : ?>

                <div class="mobile-is-a">

                  <div class="image-wrapper relative overflow-hidden single-image-gallery-each mobile-is-a-video-placeholder">
                      <a class="mobile-is-a-video-thumbnail js-init-video" href="<?php echo $truck->VIDEOPATH;  ?>video1.mp4">
                          <img class="mobile-is-a-video" src="/wp-content/themes/arrow-truck/assets/img/video_thumbnail.jpg" alt="">
                      </a>
                </div> <!-- /.video-wrapper -->

            </div> <!-- /.video -->

            <?php endif; ?>
        </div>

        <div class="single-truck-desription px-6">

            <?php

            if($truck->MODEL == 'Trailer'){
                $whatIam = 'Trailer';
            }else{
                $whatIam = 'Truck';
            }

            ?>

          <h2 class="my-4 mt-8"><?php echo $whatIam; ?> Description</h2>
          <h3><?php echo $truck->HEADLINE; ?></h3>
          <p><?php echo $truck->COMMENTS; ?></p>
        </div>
      <?php else : ?>
        <div class="image-wrapper relative aspect-4/3 overflow-hidden noimage">
          <?php

          if($truck->ISAPPRAISALPHOTO == 'Y'){

              //Handle fleet photos
              $photoPath = $truck->PHOTOPATH;
              $imageName = 'image01.jpg';

              ll_include_component(
                  'fit-image',
                  [
                      'image_id' => $photoPath . $imageName,
							'default_asset_url' => 'https://www.arrowtruckhost.com/images/NoImage2.jpg'
                  ]
              );

          }else{

              $photoPath = $truck->PHOTOPATH;
              $imageName = 'image01.jpg';

              ll_include_component(
                  'fit-image',
                  [
                      'image_id' => $photoPath . $imageName,
							'default_asset_url' => 'https://www.arrowtruckhost.com/images/NoImage2.jpg'
                  ]
              );
          }
          ?>
        </div> <!-- /.image-wrapper -->
      <?php endif; ?>
    </div>

    <div class="flex flex-wrap">
      <div class="flex-initial w-full lg:w-2/3 single-inventory-left lg:pl-8 hidden lg:block">

        <?php if ( $truck->images->count() > 1 ) : ?>


          <div class="image-wrapper relative aspect-4/3 overflow-hidden single-hero-image">
            <?php

                // Main Image

              if($truck->ISAPPRAISALPHOTO == 'Y'){

                  //Handle fleet photos
                  $photoPath = $truck->PHOTOPATH;
                  $imageName = 'image01.jpg';

                  ll_include_component(
                      'fit-image',
                      [
                          'image_id' => $photoPath . $imageName,
							'default_asset_url' => 'https://www.arrowtruckhost.com/images/NoImage2.jpg'
                      ]
                  );

              }else{

                  $photoPath = $truck->PHOTOPATH;
                  $imageName = 'image01.jpg';

                  ll_include_component(
                      'fit-image',
                      [
                          'image_id' => $photoPath . $imageName,
							'default_asset_url' => 'https://www.arrowtruckhost.com/images/NoImage2.jpg'
                      ]
                  );
              }

            ?>
          </div> <!-- /.single-hero-image -->

          <div class="col w-full single-extra-features mx-0 px-0">
            <!-- extra features -->

          </div> <!-- /.single-extra-features -->

          <div class="col w-full mx-0 px-0 single-image-gallery mb-16">



          <div class="w-full flex flex-wrap overflow-hidden">

            <?php foreach( $truck->images as $key => $image ) : ?>

                <?php

                    $photoPath = $truck->PHOTOPATH;

                    if($key == 0){
                        $imageName = 'image01.jpg';
                    }elseif($key == 1){
                        $imageName = 'image01.jpg';
                    }elseif($key == 2){
                        $imageName = 'image02.jpg';
                    }elseif($key == 3){
                        $imageName = 'image03.jpg';
                    }elseif($key == 4){
                        $imageName = 'image04.jpg';
                    }elseif($key == 5){
                        $imageName = 'image05.jpg';
                    }elseif($key == 6){
                        $imageName = 'image06.jpg';
                    }elseif($key == 7){
                        $imageName = 'image07.jpg';
                    }elseif($key == 8){
                        $imageName = 'image08.jpg';
                    }elseif($key == 9){
                        $imageName = 'image09.jpg';
                    }else{
                        $imageName = 'image' . $key . '.jpg';
                    }

                ?>

              <div class="<?php echo $key == 0 ? 'hidden' : '' ?> w-full sm:w-1/2 mt-2 <?php echo $key % 2 == 1 ? 'sm:mr-5/10 sm:-ml-5/10' : 'sm:ml-5/10 sm:-mr-5/10'; ?>">

                <div class="image-wrapper relative aspect-4/3 overflow-hidden single-image-gallery-each">


                  <?php
                    ll_include_component(
                      'fit-image',
                      [
                        'image_id' => $photoPath . $imageName,
							'default_asset_url' => 'https://www.arrowtruckhost.com/images/NoImage2.jpg'
                      ]
                    );
                  ?>

                </div> <!-- /.image-wrapper -->

              </div> <!-- /.image -->

            <?php endforeach; ?>


            <?php if ( $truck->HASVIDEO == 'Y' ) : ?>

                <div class="<?php echo $key == 0 ? 'hidden' : '' ?> w-full sm:w-1/2 mt-2">

                  <div class="image-wrapper relative overflow-hidden single-image-gallery-each is-a-video-placeholder">
                      <a class="is-a-video-thumbnail js-init-video" href="<?php echo $truck->VIDEOPATH;  ?>video1.mp4">
                          <img class="is-a-video" src="/wp-content/themes/arrow-truck/assets/img/video_thumbnail.jpg" alt="">
                      </a>
                </div> <!-- /.video-wrapper -->

            </div> <!-- /.video -->

            <?php endif; ?>



          </div>

        </div> <!-- /.single-image-gallery -->

        <?php else : ?>

          <div class="image-wrapper relative aspect-4/3 overflow-hidden single-hero-image">
              <?php

                if($truck->ISAPPRAISALPHOTO == 'Y'){

                    //Handle fleet photos
                    $photoPath = $truck->PHOTOPATH;
                    $imageName = 'image01.jpg';

                    ll_include_component(
                        'fit-image',
                        [
                            'image_id' => $photoPath . $imageName,
							'default_asset_url' => 'https://www.arrowtruckhost.com/images/NoImage2.jpg'
                        ]
                    );

                }else{

                    $photoPath = $truck->PHOTOPATH;
                    $imageName = 'image01.jpg';

                    ll_include_component(
                        'fit-image',
                        [
                            'image_id' => $photoPath . $imageName,
							'default_asset_url' => 'https://www.arrowtruckhost.com/images/NoImage2.jpg'
                        ]
                    );
                }

              ?>
          </div> <!-- /.single-hero-image -->

        <?php endif; ?>

      </div>
      <div class="flex-initial w-full lg:w-1/3 single-inventory-right p-4 lg:p-16">

        <div class="single-get-started">

          <div class="single-info-pane hidden lg:block on-single-ll-inventory">

            <!-- start info pane -->
            <h2 class="single-info-pane-headline"><?php echo $truck->YEAR . ' ' . $truck->MANUFACTURER . ' ' . $truck->MODEL; ?></h2>

            <div class="single-price-miles overflow-auto mb-4">

                <?php if($truck->PRICECHANGED != 'Y') : ?>
                    <h3 class="spm-red">$<?php echo number_format($truck->PRICE); ?></h3>
                <?php else : ?>
                    <h3 class="spm-red" style="font-weight: normal;"><span style="text-decoration: line-through;display: inline-block;margin-right: 1rem;font-weight: bold;">$<?php echo number_format($truck->OLDPRICE); ?></span>Incentives available.</h3>
                    <span class="spm-red" style="font-size: 1.25rem;margin-bottom: 1rem;display: block;">Call for current pricing.</span>
                <?php endif; ?>

              <?php if($truck->MODEL != 'Trailer') : ?>
                  <h3 class="spm-gray"><?php echo number_format($truck->MILEAGE); ?> Miles</h3>
                  <h3 class="spm-gray" style="font-size: 1rem;opacity: 0.7;">ECM Mileage: <?php echo number_format($truck->ECMMILEAGE); ?></h3>
              <?php endif; ?>
            </div>
            <div class="single-vehicle-list-details flex flex-wrap" style="margin-bottom:2rem;">

                <?php if($truck->MODEL != 'Trailer') : ?>
                  <div class="flex flex-initial w-full">
                    <svg class="icon icon-semi-truck mr-2 text-xl svg-align"><use xlink:href="#icon-semi-truck"></use></svg>
                    <p><?php echo $truck->ENGINEMAKE; ?> <?php echo $truck->ENGINEMODEL; ?></p>
                  </div>
                <?php endif; ?>

                <?php if($truck->MODEL != 'Trailer') : ?>
                  <div class="flex flex-initial w-full">
                    <svg class="icon icon-speedometer mr-2 text-xl svg-align"><use xlink:href="#icon-speedometer"></use></svg>
                    <p>HP: <?php echo $truck->HORSEPOWER; ?></p>
                  </div>
                <?php endif; ?>

              <div class="flex flex-initial w-full">
                <svg class="icon icon-pin mr-2 text-xl svg-align"><use xlink:href="#icon-pin"></use></svg>
                <?php

                  $singleLocation = $truck->LOCATION;
                  $singleState = $truck->STATE;

                  if($singleLocation == 'St. Louis'){
                      $singleState = 'MO';
                  }

                ?>
                <p><?php echo $singleLocation; ?>, <?php echo $singleState; ?></p>
              </div>

              <div class="flex flex-initial w-full">
                <!-- <span class="single-vehicle-details-icon sv-stock-text">Stock #</span> -->
                <p class="mt-2">Stock # <?php echo $truck->STOCKNUM; ?></p>
              </div>

            </div>

            <?php if($truck->PROMO) : ?>
                <?php foreach( $truck->PROMO as $key => $value ) : ?>
                    <?php
                      $promo_array = explode('|', $value);
                    ?>
                    <a class="truck-card-promo-link" href="<?php echo $promo_array[1]; ?>">
                        <span class="truck-card-promo-text text-brand-primary inline-block hidden lg:block" style="font-weight: bold;display:block;"><?php echo $promo_array[0]; ?></span>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>

            <div class="single-truck-desription">
              <h2 class="my-4 mt-8"><?php echo ( isset( $whatIam ) ) ? $whatIam : null; ?> Description</h2>
              <h3><?php echo $truck->HEADLINE; ?></h3>
              <p><?php echo $truck->COMMENTS; ?></p>
            </div>
          </div> <!-- /.single-info-pane -->


          <div class="single-drop-down-wrapper flex flex-wrap py-8">

            <div class="flex-initial w-full single-each-drop-down sv-featuresandspecs">
              <div id="features-and-specs-head" class="single-each-drop-down-top w-full relative py-4 flex justify-between items-center border-t border-gray-300">
                <h2 class="">Features & Specs</h2>
                <svg id="features-chevron-down" class="icon icon-chevron-down mr-2 text-xl text-brand-primary svg-align"><use xlink:href="#icon-chevron-down"></use></svg>
                <svg id="features-chevron-up" class="hidden icon icon-chevron-up mr-2 text-xl text-brand-primary svg-align"><use xlink:href="#icon-chevron-up"></use></svg>
              </div>
              <div id="features-and-specs-content" class="hidden single-each-drop-down-bottom p-4">
                <!-- stuff goes here -->

                <?php if($truck->MODEL != 'Trailer') : ?>

                    <div class="fs-section section-engine w-full">
                      <h3>Engine</h3>
                      <div class="<?php echo $truck->ENGINEMAKE ? 'flex' : 'hidden' ?> w-full justify-between align-center fs-data-item">
                        <p class="fs-title">Manfacturer</p>
                        <p class="fs-data"><?php echo $truck->ENGINEMAKE; ?></p>
                      </div>
                      <div class="<?php echo $truck->ENGINEMODEL ? 'flex' : 'hidden' ?> w-full justify-between align-center fs-data-item">
                        <p class="fs-title">Model</p>
                        <p class="fs-data"><?php echo $truck->ENGINEMODEL; ?></p>
                      </div>
                      <div class="<?php echo $truck->HORSEPOWER ? 'flex' : 'hidden' ?> w-full justify-between align-center fs-data-item">
                        <p class="fs-title">Horsepower</p>
                        <p class="fs-data"><?php echo $truck->HORSEPOWER; ?></p>
                      </div>
                    </div>

                    <div class="fs-section section-transmmission w-full">
                      <h3>Transmission</h3>
                      <div class="<?php echo $truck->TRANSPEED ? 'flex' : 'hidden' ?> w-full justify-between align-center fs-data-item">
                        <p class="fs-title">Speed</p>
                        <p class="fs-data"><?php echo $truck->TRANSPEED; ?></p>
                      </div>
                      <div class="<?php echo $truck->TRANSTYPE ? 'flex' : 'hidden' ?> w-full justify-between align-center fs-data-item">
                        <p class="fs-title">Type</p>
                        <p class="fs-data"><?php echo $truck->TRANSTYPE; ?></p>
                      </div>
                      <div class="<?php echo $truck->TRANSNAME ? 'flex' : 'hidden' ?> w-full justify-between align-center fs-data-item">
                        <p class="fs-title">Name</p>
                        <p class="fs-data"><?php echo $truck->TRANSNAME; ?></p>
                      </div>
                    </div>


                <div class="fs-section section-wheels-and-tires w-full">
                  <h3>Wheels and Tires</h3>
                  <div class="<?php echo $truck->AXLE ? 'flex' : 'hidden' ?> w-full justify-between align-center fs-data-item">
                    <p class="fs-title">Axle</p>
                    <p class="fs-data"><?php echo $truck->AXLE; ?></p>
                  </div>
                  <div class="<?php echo $truck->RATIO ? 'flex' : 'hidden' ?> w-full justify-between align-center fs-data-item">
                    <p class="fs-title">Ratio</p>
                    <p class="fs-data"><?php echo $truck->RATIO; ?></p>
                  </div>
                  <div class="<?php echo $truck->SUSPENSION ? 'flex' : 'hidden' ?> w-full justify-between align-center fs-data-item">
                    <p class="fs-title">Suspension</p>
                    <p class="fs-data"><?php echo $truck->SUSPENSION; ?></p>
                  </div>
                  <div class="<?php echo $truck->WHEELBASE ? 'flex' : 'hidden' ?> w-full justify-between align-center fs-data-item">
                    <p class="fs-title">Wheelbase</p>
                    <p class="fs-data"><?php echo $truck->WHEELBASE; ?></p>
                  </div>
                  <div class="<?php echo $truck->STEERWHEEL ? 'flex' : 'hidden' ?> w-full justify-between align-center fs-data-item">
                    <p class="fs-title">Steer Wheel Type</p>
                    <p class="fs-data"><?php echo $truck->STEERWHEEL; ?></p>
                  </div>
                  <div class="<?php echo $truck->REARWHEEL ? 'flex' : 'hidden' ?> w-full justify-between align-center fs-data-item">
                    <p class="fs-title">Rear Wheel Type</p>
                    <p class="fs-data"><?php echo $truck->REARWHEEL; ?></p>
                  </div>
                  <div class="<?php echo $truck->FRONTAXLE ? 'flex' : 'hidden' ?> w-full justify-between align-center fs-data-item">
                    <p class="fs-title">Front Axle (Lbs.)</p>
                    <p class="fs-data"><?php echo $truck->FRONTAXLE; ?></p>
                  </div>
                  <div class="<?php echo $truck->REARAXLE ? 'flex' : 'hidden' ?> w-full justify-between align-center fs-data-item">
                    <p class="fs-title">Rear Axle (Lbs.)</p>
                    <p class="fs-data"><?php echo $truck->REARAXLE; ?></p>
                  </div>
                  <div class="<?php echo $truck->TIRESIZE ? 'flex' : 'hidden' ?> w-full justify-between align-center fs-data-item">
                    <p class="fs-title">Tire Size</p>
                    <p class="fs-data"><?php echo $truck->TIRESIZE; ?></p>
                  </div>
                </div>

                <div class="fs-section section-details w-full">
                  <h3>Details</h3>
                  <div class="<?php echo $truck->SLEEPERTYPE ? 'flex' : 'hidden' ?> w-full justify-between align-center fs-data-item">
                    <p class="fs-title">Sleeper Type</p>
                    <p class="fs-data"><?php echo $truck->SLEEPERTYPE; ?></p>
                  </div>
                  <div class="<?php echo $truck->SLEEPERSIZE ? 'flex' : 'hidden' ?> w-full justify-between align-center fs-data-item">
                    <p class="fs-title">Sleeper Size</p>
                    <p class="fs-data"><?php echo $truck->SLEEPERSIZE; ?></p>
                  </div>
                  <div class="<?php echo $truck->BUNKSNUM ? 'flex' : 'hidden' ?> w-full justify-between align-center fs-data-item">
                    <p class="fs-title">No. of Bunks</p>
                    <p class="fs-data"><?php echo $truck->BUNKSNUM; ?></p>
                  </div>
                  <div class="<?php echo $truck->FAIRINGS ? 'flex' : 'hidden' ?> w-full justify-between align-center fs-data-item">
                    <p class="fs-title">Fairings</p>
                    <p class="fs-data"><?php echo $truck->FAIRINGS; ?></p>
                  </div>
                  <div class="<?php echo $truck->FCAM ? 'flex' : 'hidden' ?> w-full justify-between align-center fs-data-item">
                    <p class="fs-title">FCAM (Y/N)</p>
                    <p class="fs-data"><?php echo $truck->FCAM; ?></p>
                  </div>
                  <div class="<?php echo $truck->APU ? 'flex' : 'hidden' ?> w-full justify-between align-center fs-data-item">
                    <p class="fs-title">APU (Y/N)</p>
                    <p class="fs-data"><?php echo $truck->APU; ?></p>
                  </div>
                  <div class="<?php echo $truck->JAKEBRAKE ? 'flex' : 'hidden' ?> w-full justify-between align-center fs-data-item">
                    <p class="fs-title">Jake Brake (Y/N)</p>
                    <p class="fs-data"><?php echo $truck->JAKEBRAKE; ?></p>
                  </div>
                  <div class="<?php echo $truck->FIFTHWHEEL ? 'flex' : 'hidden' ?> w-full justify-between align-center fs-data-item">
                    <p class="fs-title">5th Wheel</p>
                    <p class="fs-data"><?php echo $truck->FIFTHWHEEL; ?></p>
                  </div>
                  <div class="<?php echo $truck->ECMMILEAGE ? 'flex' : 'hidden' ?> w-full justify-between align-center fs-data-item">
                    <p class="fs-title">ECM Mileage</p>
                    <p class="fs-data"><?php echo $truck->ECMMILEAGE; ?></p>
                  </div>
                  <div class="<?php echo $truck->BRAKES ? 'flex' : 'hidden' ?> w-full justify-between align-center fs-data-item">
                    <p class="fs-title">Brakes</p>
                    <p class="fs-data"><?php echo $truck->BRAKES; ?></p>
                  </div>
                  <div class="<?php echo $truck->TANKCAPACITY ? 'flex' : 'hidden' ?> w-full justify-between align-center fs-data-item">
                    <p class="fs-title">Fuel Tank Capacity</p>
                    <p class="fs-data"><?php echo $truck->TANKCAPACITY; ?></p>
                  </div>
                  <div class="<?php echo $truck->TANKQUANTITY ? 'flex' : 'hidden' ?> w-full justify-between align-center fs-data-item">
                    <p class="fs-title">Fuel Tank Quantity</p>
                    <p class="fs-data"><?php echo $truck->TANKQUANTITY; ?></p>
                  </div>

                </div>

            <?php else : ?>

                <div class="fs-section section-trailer w-full">
                  <div class="<?php echo $truck->TRAILERLENGTH ? 'flex' : 'hidden' ?> w-full justify-between align-center fs-data-item">
                    <p class="fs-title">Length</p>
                    <p class="fs-data"><?php echo $truck->TRAILERLENGTH; ?></p>
                  </div>
                  <div class="<?php echo $truck->TRAILERWIDTH ? 'flex' : 'hidden' ?> w-full justify-between align-center fs-data-item">
                    <p class="fs-title">Width</p>
                    <p class="fs-data"><?php echo $truck->TRAILERWIDTH; ?></p>
                  </div>
                  <div class="<?php echo $truck->SUSPENSION ? 'flex' : 'hidden' ?> w-full justify-between align-center fs-data-item">
                    <p class="fs-title">Suspension</p>
                    <p class="fs-data"><?php echo $truck->SUSPENSION; ?></p>
                  </div>
                  <div class="<?php echo $truck->AXLE ? 'flex' : 'hidden' ?> w-full justify-between align-center fs-data-item">
                    <p class="fs-title">Axle</p>
                    <p class="fs-data"><?php echo $truck->AXLE; ?></p>
                  </div>
                  <div class="<?php echo $truck->TRAILERREEFERMAKE ? 'flex' : 'hidden' ?> w-full justify-between align-center fs-data-item">
                    <p class="fs-title">Reefer Manufacturer</p>
                    <p class="fs-data"><?php echo $truck->TRAILERREEFERMAKE; ?></p>
                  </div>
                  <div class="<?php echo $truck->TRAILERREEFERMODEL ? 'flex' : 'hidden' ?> w-full justify-between align-center fs-data-item">
                    <p class="fs-title">Reefer Model</p>
                    <p class="fs-data"><?php echo $truck->TRAILERREEFERMODEL; ?></p>
                  </div>
                  <div class="<?php echo $truck->TRAILERREEFERHOURS ? 'flex' : 'hidden' ?> w-full justify-between align-center fs-data-item">
                    <p class="fs-title">Reefer Hours</p>
                    <p class="fs-data"><?php echo $truck->TRAILERREEFERHOURS; ?></p>
                  </div>
                  <div class="<?php echo $truck->TRAILERREEFERYEAR ? 'flex' : 'hidden' ?> w-full justify-between align-center fs-data-item">
                    <p class="fs-title">Reefer Year</p>
                    <p class="fs-data"><?php echo $truck->TRAILERREEFERYEAR; ?></p>
                  </div>
                  <div class="<?php echo $truck->TRAILERTIRESIZE ? 'flex' : 'hidden' ?> w-full justify-between align-center fs-data-item">
                    <p class="fs-title">Tire Size</p>
                    <p class="fs-data"><?php echo $truck->TRAILERTIRESIZE; ?></p>
                  </div>
                  <div class="<?php echo $truck->TRAILERSIDESKIRTS ? 'flex' : 'hidden' ?> w-full justify-between align-center fs-data-item">
                    <p class="fs-title">Side Skirts</p>
                    <p class="fs-data"><?php echo $truck->TRAILERSIDESKIRTS; ?></p>
                  </div>
                  <div class="<?php echo $truck->TRAILERTIEDOWNS ? 'flex' : 'hidden' ?> w-full justify-between align-center fs-data-item">
                    <p class="fs-title">Tie Downs</p>
                    <p class="fs-data"><?php echo $truck->TRAILERTIEDOWNS; ?></p>
                  </div>
                  <div class="<?php echo $truck->TRAILERCOMPOSITION ? 'flex' : 'hidden' ?> w-full justify-between align-center fs-data-item">
                    <p class="fs-title">Composition</p>
                    <p class="fs-data"><?php echo $truck->TRAILERCOMPOSITION; ?></p>
                  </div>
                </div>
            <? endif; ?>

              </div>
            </div>

            <div class="flex-initial w-full single-each-drop-down sv-warranties">
              <div id="warranties-head" class="single-each-drop-down-top w-full relative py-4 flex justify-between items-center border-t border-gray-300">
                <h2 class="">Warranties</h2>
                <svg id="warranties-chevron-down" class="icon icon-chevron-down mr-2 text-xl text-brand-primary svg-align"><use xlink:href="#icon-chevron-down"></use></svg>
                <svg id="warranties-chevron-up" class="hidden icon icon-chevron-up mr-2 text-xl text-brand-primary svg-align"><use xlink:href="#icon-chevron-up"></use></svg>
              </div>
              <div id="warranties-content" class="hidden single-each-drop-down-bottom p-4">
                <!-- stuff goes here -->

                <div class="warranties-wrapper">
                  <div class="warranties-header flex justify-start align-center">
                    <svg class="icon icon-checkmark-filled mr-2 text-xl text-brand-primary svg-align"><use xlink:href="#icon-checkmark-filled"></use></svg>
                    <h3>Extended Warranties</h3>
                  </div>
                  <div class="warranties-content">
                    <ul>
                      <li>Protection for up to 4 years / 400,000 miles</li>
                      <li>Pre-Purchase Inspection</li>
                      <li>One Deductible</li>
                      <li>Instant Claim Payment</li>
                    </ul>
                  </div>
                  <button class="warranties-button" onclick="window.location.href='/extended-semi-truck-warranties'" type="button" name="button">
                    Learn More ->
                  </button>
                </div>

                <div class="warranties-wrapper">
                  <div class="warranties-header flex justify-start align-center">
                    <svg class="icon icon-checkmark-filled mr-2 text-xl text-brand-primary svg-align"><use xlink:href="#icon-checkmark-filled"></use></svg>
                    <h3>Aftertreatment Warranty</h3>
                  </div>
                  <div class="warranties-content">
                    <ul>
                      <li>Coverage on 42 vital system components</li>
                      <li>Both medium and heavy duty diesel engines</li>
                      <li>Plans available from 6 to 48 months</li>
                    </ul>
                  </div>
                  <button class="warranties-button" onclick="window.location.href='/semi-truck-aftertreatment-warranty'" type="button" name="button">
                    Learn More ->
                  </button>
                </div>

                <div class="warranties-wrapper">
                  <div class="warranties-header flex justify-start align-center">
                    <svg class="icon icon-checkmark-filled mr-2 text-xl text-brand-primary svg-align"><use xlink:href="#icon-checkmark-filled"></use></svg>
                    <h3>ConfidencePLUS<em>&trade;</em> Comprehensive Coverage</h3>
                  </div>
                  <div class="warranties-content">
                    <ul>
                      <li>90 day / 25,000 mile coverage</li>
                      <li>Standard on virtually every late-model truck we sell at no extra cost to you</li>
                    </ul>
                  </div>
                  <button class="warranties-button" onclick="window.location.href='/90-day-semi-truck-warranty'" type="button" name="button">
                    Learn More ->
                  </button>
                </div>

              </div>
            </div>

          </div> <!-- /.single-drop-down-wrapper -->


          <div class="thickblackline">

          </div>


          <div class="single-get-started-buttons flex flex-wrap">

            <h2 class="py-8">Get Started</h2>

            <!-- <div class="flex-initial w-full single-get-started-buttons-each my-1">
              <button type="button" name="button" class="w-full p-4 text-left rounded single-get-started-button">
                <span class="single-get-started-button-icon sv-downpaymentestimator"></span>
                Buy Now
              </button>
            </div> -->

            <div class="flex-initial w-full single-get-started-buttons-each my-1">
              <button onclick="window.location.href='/down-payment-estimator/?price=<?php echo $truck->PRICE; ?>&stock=<?php echo $truck->STOCKNUM; ?>&mileage=<?php echo $truck->MILEAGE; ?>'" type="button" name="button" class="w-full p-4 text-left rounded single-get-started-button">
                <svg class="icon icon-estimate mr-2 text-xl text-brand-primary svg-align"><use xlink:href="#icon-estimate"></use></svg>
                Estimate Your Payments
              </button>
            </div>

            <div class="flex-initial w-full single-get-started-buttons-each my-1">
              <button onclick="window.location.href='/pre-qualify/?price=<?php echo $truck->PRICE; ?>&stock=<?php echo $truck->STOCKNUM; ?>&mileage=<?php echo $truck->MILEAGE; ?>'" type="button" name="button" class="w-full p-4 text-left rounded single-get-started-button">
                <svg class="icon icon-guarantee mr-2 text-xl text-brand-primary svg-align"><use xlink:href="#icon-guarantee"></use></svg>
                Pre-Qualify for Credit
              </button>
            </div>

            <div class="flex-initial w-full single-get-started-buttons-each my-1">
              <button onclick="window.location.href='/apply-for-credit'" type="button" name="button" class="w-full p-4 text-left rounded single-get-started-button">
                <svg class="icon icon-waiver mr-2 text-xl text-brand-primary svg-align"><use xlink:href="#icon-waiver"></use></svg>
                Credit Application
              </button>
            </div>

            <div class="flex-initial w-full single-get-started-buttons-each my-1">
              <button onclick="window.location.href='/truck-inquiry/?info_1=<?php echo $truck->YEAR; ?>&info_2=<?php echo $truck->MANUFACTURER; ?>&info_3=<?php echo $truck->MODEL; ?>&info_4=<?php echo $truck->STOCKNUM; ?>&price=<?php echo $truck->PRICE; ?>&mileage=<?php echo $truck->MILEAGE; ?>'" type="button" name="button" class="w-full p-4 text-left rounded single-get-started-button">
                <svg class="icon icon-mail mr-2 text-xl text-brand-primary svg-align"><use xlink:href="#icon-mail"></use></svg>
                Contact Us About This Truck
              </button>
            </div>

          </div> <!-- /.single-get-started-buttons -->


          <div class="single-summary flex flex-wrap bg-gray-100 p-4 hidden">

            <h2 class="pb-4">Summary</h2>

            <div class="single-price-list flex-initial w-full sv-truckprice pb-4">
              <div class="w-full relative flex justify-between items-center single-price-list-each">
                <span class="single-price-list-line-title">
                  Truck Price
                </span>
                <span class="single-price-list-line-price">
                  $<?php echo number_format($truck->PRICE); ?>
                </span>
              </div>
            </div>

            <div class="single-price-list flex-initial w-full sv-downpayment pb-4">
              <div class="w-full relative flex justify-between items-center single-price-list-each">
                <span class="single-price-list-line-title">
                  Down Payment
                  <a href="/sell-my-truck">(Get a trade-in offer)</a>
                </span>
                <span class="single-price-list-line-price">
                  -$<input id="downpayment-single-truck" type="number" value="" data-truckprice="<?php echo $truck->PRICE; ?>" placeholder="2,000"/>
                </span>
              </div>
            </div>

            <div class="single-price-list flex-initial w-full sv-totalfinanceamount pb-4">
              <div class="w-full relative flex justify-between items-center single-price-list-each">
                <span class="single-price-list-line-title single-price-list-bold">
                  Total Finance Amount
                </span>
                <span id="totalpayment-single-truck" class="single-price-list-line-price single-price-list-bold">

                </span>
              </div>
            </div>



            <div class="single-estimated-monthly flex-initial w-full">

              <div class="single-estimated-monthly-title">
                Estimated Monthly Payment
              </div>
              <div id="totalestimated-single-truck" class="single-estimated-monthly-price">

              </div>

              <button onclick="window.location.href='/pre-qualify/?price=<?php echo $truck->PRICE;?>'" type="button" name="button">
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
                <button onclick="window.location.href='/24-7-roadside-assistance'" type="button" name="button">
                  Learn More ->
                </button>
              </div>
            </div>
          </div>
          <div class="single-roadside-wrapper flex-initial w-full relative p-8 pt-4">
            <div class="flex justify-start single-roadside-inner">
              <div class="single-roadside-left">
                <img src="/wp-content/themes/arrow-truck/assets/img/protectionplan.png" alt="">
              </div>
              <div class="single-roadside-right">
                <h1>Protection Plans</h1>
                <ul>
                  <li>
                    <span class="single-vehicle-checkmark"></span>
                    Gap Protection
                  </li>
                  <li>
                    <span class="single-vehicle-checkmark"></span>
                    Debt Waiver
                  </li>
                  <li>
                    <span class="single-vehicle-checkmark"></span>
                    Physical Damage Insurance
                  </li>
                  <li>
                    <span class="single-vehicle-checkmark"></span>
                    Non-Trucking Liability Insurance
                  </li>
                </ul>
                <button onclick="window.location.href='/warranty-and-protection-products'" type="button" name="button">
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
