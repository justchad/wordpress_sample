/**
* Hero Banner JS
* -----------------------------------------------------------------------------
*
* All the JS for the Hero Banner component.
*/

/*
 * Example of importing modules if needed. Like for scroll magic / gsap
 */
import {gsap} from 'gsap';
import {ScrollTrigger} from 'gsap/ScrollTrigger.js';
( function( app ) {
  const COMPONENT = {

    className: 'hero-banner',
    selector: function() {
      return '.' + this.className;
    },
    // Fires after common.init, before .finalize and common.finalize
    init: function() {
      gsap.registerPlugin( ScrollTrigger );

      $( '.hero-banner' ).each( function() {
        let image = $( this ).find( '.fit-image img' )[0];
        if ( $( this ).find( '.loop-video-container' ).length ) {
          image = $( this ).find( '.loop-video-container' )[0];
        }
        const height = $( this ).outerHeight();

        const tl = gsap.timeline( {
          scrollTrigger: {
            trigger: this,
            start: 'top top',
            end: 'bottom top',
            scrub: 0,
            // markers: true,
          },
        } );
        tl.to( image, {y: '10%', ease: 'linear'}, 0 );
      } );

      $( '.hero-banner' ).addClass( 'is-loaded' );
    },
    finalize: function() {
    },
  };

  // Hooks the component into the app
  app.registerComponent( 'hero-banner', COMPONENT );
} )( app );
