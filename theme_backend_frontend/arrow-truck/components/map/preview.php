<?php
  $locations = array(
    array(
      'address' => array(
        'street' => '10261 W 87th St',
        'street_2' => '',
        'city'   => 'Overland Park',
        'state'  => 'KS',
        'zip'    => '66212'
      ),
      'phone' => '(816) 298-7018',
      'coordinates' => array(
        'lat' => '38.9705643',
        'long' => '-94.7044442',
      )
    )
  );
?>
<section class="map py-12" id="map-preview" data-component="map" data-locations="<?php echo ll_esc_json( json_encode( $locations ) ); ?>">
  <div class="container">
    <div class="row mb-8 justify-center js-fade-group">
      <div class="col w-5/12">
        <h2 class="hdg-3">Lifted Logic</h2>
      </div>

      <div class="col w-5/12">
        <div class="wysiwyg">
          <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Itaque voluptate eos excepturi ullam nulla quos qui sint corporis exercitationem officia molestias reprehenderit molestiae dicta voluptas explicabo adipisci deleniti enim, officiis!</p>
        </div>
      </div>
    </div>

    <div class="row js-reveal">
      <div class="col w-full lg:w-10/12 mx-auto">
        <div class="aspect-16/9 relative">
          <div class="map-box absolute top-0 left-0 w-full h-full" id="map" style="position:absolute!important;"></div>
        </div>
      </div>
    </div>
  </div>
</section>
