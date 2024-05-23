/**
* Photo Divider JS
* -----------------------------------------------------------------------------
*
* All the JS for the Photo Divider component.
*/

/*
 * Example of importing modules if needed. Like for scroll magic / gsap
 */

import {gsap} from 'gsap';
import {ScrollTrigger} from 'gsap/ScrollTrigger.js';
( function( app ) {
  const COMPONENT = {

    className: 'photo-divider',
    selector: function() {
      return '.' + this.className;
    },
    // Fires after common.init, before .finalize and common.finalize
    init: function() {
      // gsap.registerPlugin( ScrollTrigger );

      // $( '.photo-divider' ).each( function() {
      //   const image = $( this ).find( '.fit-image img' )[0];

      //   const tl = gsap.timeline( {
      //     scrollTrigger: {
      //       trigger: this,
      //       pin: image,
      //       start: 'bottom bottom',
      //       end: 'bottom top',
      //       // markers: true,
      //     },
      //   } );
      // } );
    },
    finalize: function() {
    },
  };

  // Hooks the component into the app
  app.registerComponent( 'photo-divider', COMPONENT );
} )( app );
