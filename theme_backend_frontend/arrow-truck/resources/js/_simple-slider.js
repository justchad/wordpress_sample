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
      $( '[data-component="simple-slider"]' ).slick( {
        arrows: true,
        dots: true,
        autoplay: false,
        inifite: false,
        slidesToShow: 3,
        slidesToScroll: 3,
        centerPadding: 0,
        responsive: [
          {
            breakpoint: 1024,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 2,
            },
          },
          {
            breakpoint: 600,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1,
            },
          },
        ],
      } );
    },

    finalize: function() {
    },
  };

  app.registerComponent( 'simple-slider', COMPONENT );
} )( app );
