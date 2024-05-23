/* ========================================================================
 * DOM-based Routing
 * Based on http://goo.gl/EUTi53 by Paul Irish
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 *
 * .noConflict()
 * The routing is enclosed within an anonymous function so that you can
 * always reference jQuery with $, even when in .noConflict() mode.
 * ======================================================================== */

( function( app ) {
  const COMPONENT = {
    init: function() {
      const _this = this;
      $( '.location-search-form' ).submit( function( e ) {

        e.preventDefault();

        $( this ).parent().toggleClass('hidden');
        $( this ).parent().parent().find('.search-location-spinner-wrapper').toggleClass('hidden');

        const zip = $( this ).find( 'input' ).val();
        let zipo_return_lat;
        let zipo_return_long;

        const client = new XMLHttpRequest();
        client.open( 'GET', 'https://api.zippopotam.us/us/' + zip + '', true );
        client.onreadystatechange = function() {

          if ( client.readyState == 4 ) {

            zipo_return_lat = $.parseJSON(client.responseText).places[0].latitude;
            zipo_return_long = $.parseJSON(client.responseText).places[0].longitude;

            $.post(
                site_info.ajax_url,
                {
                  action: 'll_run_function',
                  function: 'll_filter_locations',
                  token: site_info.ajax_nonce,
                  params: {
                    lat: zipo_return_lat,
                    long: zipo_return_long,
                  },
                },
                function( data, textStatus, xhr ) {

                  data = $.parseJSON( data );
                  if ( data.status === 'success' ) {

                    $( '.locations-row' ).html( data.response.locations );

                    const target = $( '.locations-row' );
                    let wpadminBarHeight = 0;
                    if ( $( '#wpadminbar' ).length > 0 ) {
                      wpadminBarHeight = $( '#wpadminbar' ).outerHeight();
                    }
                    const headerHeight = $( 'header.navbar' ).outerHeight();

                    if ( target.length ) {
                      $( 'html, body' ).animate( {
                        scrollTop: target.offset().top - ( headerHeight + wpadminBarHeight ),
                      }, 1000 );
                      // return false;
                    }


                    const mapData = {
                      lon: zipo_return_lat,
                      lat: zipo_return_long,
                      cen: 7,
                    };

                    app.components.map.init(mapData);

                    $( '.locations-area' ).focus();

                    $( this ).parent().parent().find('.search-location-spinner-wrapper').toggleClass('hidden');

                  }

                }
            );

        	}
        };

        client.send();
      } );
    },

    finalize: function() {
    },
  };

  app.registerComponent( 'locations', COMPONENT );
} )( app );
