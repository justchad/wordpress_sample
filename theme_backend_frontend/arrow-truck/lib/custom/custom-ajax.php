<?php
  // if you use this file, make sure to uncomment it in /lib/custom/main.php
  // get all locations, geocode the zip coed to get the coord lat long,
  // loop through the locations, geocode those zip codes,
  // pass into haversign formala, that will give distances
  // need to do a site option, or just do the closes one.
  // if there is 2 within 50 miles of each other,
  // zipapodamus send zips to, curl call and get the response.
  // haversign formula.

function haversineGreatCircleDistance( $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 3958.8 ) {
  // convert from degrees to radians
  $latFrom = deg2rad($latitudeFrom);
  $lonFrom = deg2rad($longitudeFrom);
  $latTo = deg2rad($latitudeTo);
  $lonTo = deg2rad($longitudeTo);

  $latDelta = $latTo - $latFrom;
  $lonDelta = $lonTo - $lonFrom;

  $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
    cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
  return $angle * $earthRadius;
}

function ll_filter_locations( $lat, $long ) {
  $args = array(
    'post_type'       => 'll_location',
    'post_status'     => 'publish',
    'orderby'         => 'name',
    'order'           => 'ASC',
    'posts_per_page'  => -1,
  );

  $locations = get_posts( $args );

  $filtered_locations = '';

  $closestLocation = false;
  $closestDistance = false;

  foreach ( $locations as $key => $location ) {

    $location = new ArrowLocation( $location, false );
    $distance = haversineGreatCircleDistance($lat, $long, $location->coordinates['lat'], $location->coordinates['long']);

    if(!$closestDistance){
      $closestDistance = $distance;
      $closestLocation = $location;
    }else if($distance < $closestDistance){
      $closestDistance = $distance;
      $closestLocation = $location;
    }
    // var_error_log($closestLocation);
  }

  if($closestLocation){
    $location = $closestLocation;
    ob_start();
    include(locate_template( 'templates/partials/location-card.php' ));
    $filtered_locations .= ob_get_clean();
    ob_flush();
  }

  $return['locations'] = $filtered_locations;

  return $return;
}

function ll_get_favorite_truck_list( $trucks=array() ) {
  $html = '';

  if ( !is_user_logged_in() || !$trucks || ll_empty( $trucks ) )
    return $return;

  foreach( $trucks as $truck ) {
    $truck = ll_safe_decode( $truck );
    if ( $truck ) {
      ob_start();
      include(locate_template( 'templates/partials/favorite-truck-card.php' ));
      $html .= ob_get_clean();
      ob_flush();
    }
  }

  $return['html'] = $html;
  return $html;
}
