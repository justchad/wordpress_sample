/**
* Content Box JS
* -----------------------------------------------------------------------------
*
* All the JS for the Content Box component.
*/

/*
 * Example of importing modules if needed. Like for scroll magic / gsap
 */

import {gsap} from 'gsap';
import {ScrollTrigger} from 'gsap/ScrollTrigger.js';
( function( app ) {
  const COMPONENT = {

    className: 'content-box',
    selector: function() {
      return '.' + this.className;
    },
    // Fires after common.init, before .finalize and common.finalize
    init: function() {
      gsap.registerPlugin( ScrollTrigger );

      $( '.content-box' ).each( function() {
        const image = $( this ).find( '.fit-image img' )[0];

        const tl = gsap.timeline( {
          scrollTrigger: {
            trigger: this,
            start: 'top 50%',
            end: 'bottom top',
            scrub: 0,
            // markers: true,
          },
        } );
        tl.to( image, {y: '10%', ease: 'linear'}, 0 );
      } );
    },
    finalize: function() {
    },
  };

  // Hooks the component into the app
  app.registerComponent( 'content-box', COMPONENT );
} )( app );
