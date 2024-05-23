/**
* Promotions Slider JS
* -----------------------------------------------------------------------------
*
* All the JS for the Promotions Slider component.
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

    className: 'promotions-slider',
    selector: function() {
      return '.' + this.className;
    },
    // Fires after common.init, before .finalize and common.finalize
    init: function() {
      const promoEach = document.getElementById( 'promotions-slider-wrapper' ).dataset.promocount;

      let slideCount = 3;

      if ( promoEach <= 2 ) {
        slideCount = promoEach;
      }

      $( '.promotions-slider .promotions' ).slick( {
        arrows: false,
        dots: true,
        autoplay: false,
        inifite: true,
        slidesToShow: promoEach,
        slidesToScroll: 1,
        centerMode: true,
        centerPadding: 0,
        responsive: [
          {
            breakpoint: 1024,
            settings: {
              slidesToShow: 2,
            },
          },
          {
            breakpoint: 600,
            settings: {
              slidesToShow: 1,
            },
          },
        ],
      } );
    },
    finalize: function() {
    },
  };

  // Hooks the component into the app
  app.registerComponent( 'promotions-slider', COMPONENT );
} )( app );
