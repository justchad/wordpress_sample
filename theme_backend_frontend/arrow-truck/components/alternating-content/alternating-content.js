/**
* Alternating Content JS
* -----------------------------------------------------------------------------
*
* All the JS for the Alternating Content component.
*/

/*
 * Example of importing modules if needed. Like for scroll magic / gsap
 */

import {gsap} from 'gsap';
import {ScrollTrigger} from 'gsap/ScrollTrigger.js';
( function( app ) {
  const COMPONENT = {

    className: 'alternating-content',
    selector: function() {
      return '.' + this.className;
    },
    // Fires after common.init, before .finalize and common.finalize
    init: function() {
      gsap.registerPlugin( ScrollTrigger );

      $( '.alternating-content' ).each( function() {
        const image1 = $( this ).find( '.image-1 .fit-image img' )[0];
        const image2 = $( this ).find( '.image-2 .fit-image img' )[0];
        const imageWrapper2 = $( this ).find( '.image-2' )[0];

        if ( image1 && image2 ) {
          const tl = gsap.timeline( {
            scrollTrigger: {
              trigger: this,
              start: 'top 50%',
              end: 'bottom top',
              scrub: 0,
              // markers: true,
            },
          } );
          tl.to( image1, {y: '22%', ease: 'linear'}, 0 );
          tl.to( image2, {y: '22%', ease: 'linear'}, 0 );
          tl.to( imageWrapper2, {y: '50%', ease: 'linear'}, 0 );
        }
      } );
    },
    finalize: function() {
    },
  };

  // Hooks the component into the app
  app.registerComponent( 'alternating-content', COMPONENT );
} )( app );
