/**
* Logos Slider JS
* -----------------------------------------------------------------------------
*
* All the JS for the Logos Slider component.
*/

/*
 * Example of importing modules if needed. Like for scroll magic / gsap
 */

// import ScrollMagic from 'ScrollMagic';
// import animationGSAP from 'animation.gsap';
// import addIndicators from 'debug.addIndicators';
// import TweenMax from 'TweenMax';
// import TimelineMax from 'TimelineMax';
( function( app ) {
  const COMPONENT = {

    className: 'logos-slider',
    selector: function() {
      return '.' + this.className;
    },
    // Fires after common.init, before .finalize and common.finalize
    init: function() {
      $( '.logos-slider .logos' ).slick( {
        arrows: true,
        dots: false,
        // slidesToShow: 5,
        slidesPerRow: 5,
        rows: 3,
        responsive: [
          {
            breakpoint: 1024,
            settings: {
              slidesToShow: 4,
            },
          },
          {
            breakpoint: 600,
            settings: {
              slidesToShow: 3,
            },
          },
          {
            breakpoint: 480,
            settings: {
              slidesToShow: 2,
            },
          },
        ],
      } );
    },
    finalize: function() {
    },
  };

  // Hooks the component into the app
  app.registerComponent( 'logos-slider', COMPONENT );
} )( app );
