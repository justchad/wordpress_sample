/**
* Column Photos JS
* -----------------------------------------------------------------------------
*
* All the JS for the Column Photos component.
*/

/*
 * Example of importing modules if needed. Like for scroll magic / gsap
 */

import {gsap} from 'gsap';
import {ScrollTrigger} from 'gsap/ScrollTrigger.js';
( function( app ) {
  const COMPONENT = {

    className: 'column-photos',
    selector: function() {
      return '.' + this.className;
    },
    // Fires after common.init, before .finalize and common.finalize
    init: function() {
      gsap.registerPlugin( ScrollTrigger );

      $( '.column-photos' ).each( function() {
        const image1 = $( this ).find( '.col:nth-child(1) .fit-image img' )[0];
        const image3 = $( this ).find( '.col:nth-child(3) .fit-image img' )[0];
        const height = $( this ).outerHeight();

        const tl = gsap.timeline( {
          scrollTrigger: {
            trigger: this,
            start: 'top 70%',
            end: 'bottom top',
            scrub: 0,
            // markers: true,
          },
        } );
        tl.to( image1, {y: '15%', ease: 'linear'}, 0 );
        tl.to( image3, {y: '15%', ease: 'linear'}, 0 );
      } );
    },
    finalize: function() {
    },
  };

  // Hooks the component into the app
  app.registerComponent( 'column-photos', COMPONENT );
} )( app );
