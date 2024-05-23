/**
* map JS
* -----------------------------------------------------------------------------
*
* All the JS for the map component.
*/

/*
 * Example of importing modules if needed. Like for scroll magic / gsap
 */

// VARS that are set in map.php:
// mapLocations - array of objects [{address, city, state, zip, coordinates: {lat, lng}}]
// mapPin - url string of pin image
// mapStyle - url string of mapbox studio style link
// mapKey - string of mapbox api key
( function( app ) {
  const COMPONENT = {

    className: 'map',
    selector: function() {
      return '.' + this.className;
    },
    // Fires after common.init, before .finalize and common.finalize
    init: function( data ) {
      let mapVal;

      if ( data != undefined ) {
        mapVal = {
          lat: parseFloat( data.lat ),
          lon: parseFloat( data.lon ),
          cen: parseFloat( data.cen ),
        };
      } else {
        mapVal = {
          lat: -94.49495,
          lon: 39.04636,
          cen: 4.5,
        };
      }

      // mapKey variable is set in the PHP file
      mapboxgl.accessToken = ll_mapbox.token;
      $( '.map' ).each( function() {
        // Initialize the map
        const mapLocations = [];
        if ( document.querySelectorAll( '.location-card' ).length ) {
          document.querySelectorAll( '.location-card' ).forEach( ( element ) => {
            mapLocations.push( {
              'address': JSON.parse( element.dataset.address ),
              'coordinates': JSON.parse( element.dataset.coordinates ),
            } );
          } );
        }

        const mapId = $( this ).attr( 'id' );

        if ( mapLocations ) {
          const map = new mapboxgl.Map( {
            container: 'map', // mounts on element with id of "map"
            style: ll_mapbox.style, // stylesheet URL set in PHP file
            // this map is centered on the US, but you should change it your location
            center: [mapVal.lat, mapVal.lon],
            zoom: mapVal.cen,
          } );

          map.on( 'load', () => {
            // load the pin image (has to be png or jpg, [SVG DOESN'T WORK])
            // mapPin is set in PHP file
            map.loadImage( ll_mapbox.pin, ( err, img ) => {
              if ( err ) throw err;
              // Will rerference "pin" later
              map.addImage( 'll_pin', img );
              const features = [];
              const places = [];
              // for multi-location map, iterate over locations and add them to the array of features
              mapLocations.forEach( ( loc, index ) => {
                if ( !loc.coordinates ) {
                  return;
                }

                const feature = {
                  type: 'Feature',
                  properties: {
                    description:
                    `<address class="p-2 not-italic text-xs text-center">
                      <span class="block">${loc.address.street}</span>
                      <span class="block">${loc.address.city}, ${loc.address.state} ${loc.address.zip}</span>
                    </address>
                    <a class="block rounded-bl rounded-br p-2 text-center text-white leading-normal bg-black" href="https://www.google.com/maps/place/${loc.address.street}+${loc.address.city}+${loc.address.state}" target="_blank">Get Directions</a>`,
                  },
                  geometry: {
                    type: 'Point',
                    coordinates: [parseFloat( loc.coordinates.long ), parseFloat( loc.coordinates.lat )],
                  },
                };

                features.push( feature );
              } );

              // add this "geojson" object to the map, passing in the features array
              map.addSource( 'points', {
                type: 'geojson',
                data: {
                  type: 'FeatureCollection',
                  features,
                },
              } );

              // create a symbol layer using the "point" source that we just added
              map.addLayer( {
                id: 'points',
                type: 'symbol',
                source: 'points',
                layout: {
                  'icon-image': 'll_pin', // uses the "pin" image we added above
                  'icon-size': 1, // relative to the original image size (unfortunately)
                  'icon-anchor': 'bottom', // will put the bottom center of the pin on the location
                },
              } );

              // this handles the popups when the pin is clicked
              map.on( 'click', 'points', function( e ) {
                const coordinates = e.features[0].geometry.coordinates.slice();
                const description = e.features[0].properties.description;

                // Ensure that if the map is zoomed out such that multiple
                // copies of the feature are visible, the popup appears
                // over the copy being pointed to.
                while ( Math.abs( e.lngLat.lng - coordinates[0] ) > 180 ) {
                  coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
                }

                new mapboxgl.Popup( {offset: {'bottom': [0, -49]}} )
                    .setLngLat( coordinates )
                    .setHTML( description )
                    .addTo( map );
              } );

              // make the cursor a pointer when over the pin
              map.on( 'mouseenter', 'points', function() {
                map.getCanvas().style.cursor = 'pointer';
              } );
              map.on( 'mouseleave', 'points', function() {
                map.getCanvas().style.cursor = '';
              } );
            } );
          } );
        }
      } );
    },
    finalize: function() {
    },
  };

  // Hooks the component into the app
  app.registerComponent( 'map', COMPONENT );
} )( app );
