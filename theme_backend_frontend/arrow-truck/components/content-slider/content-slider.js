/**
* Content Slider JS
* -----------------------------------------------------------------------------
*
* All the JS for the Content Slider component.
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

    className: 'content-slider',
    selector: function() {
      return '.' + this.className;
    },
    // Fires after common.init, before .finalize and common.finalize
    init: function() {
      $( '.content-slider .slides' ).slick( {
        arrows: false,
        dots: true,
        slidesToShow: 1,
        autoplay: false,
        infinite: false,
      } );

      $( '.content-slider .next-slide' ).click( function() {
        $( this ).closest( '.content-slider' ).find( '.slides' ).slick( 'slickNext' );
      } );

      $( '.content-slider .previous-slide' ).click( function() {
        $( this ).closest( '.content-slider' ).find( '.slides' ).slick( 'slickPrev' );
      } );

      $( '.content-slider .slides' ).on( 'afterChange', function( event, slick, currentSlide ) {
        if ( currentSlide + 1 > 1 ) {
          $( '.content-slider .previous-slide' ).attr( 'disabled', false );
        } else {
          $( '.content-slider .previous-slide' ).attr( 'disabled', true );
        }

        if ( currentSlide + 1 === $( '.content-slider .slide' ).length ) {
          $( '.content-slider .next-slide' ).attr( 'disabled', true );
        } else {
          $( '.content-slider .next-slide' ).attr( 'disabled', false );
        }
      } );
    },
    finalize: function() {
    },
  };

  // Hooks the component into the app
  app.registerComponent( 'content-slider', COMPONENT );
} )( app );
