/**
* Search Banner JS
* -----------------------------------------------------------------------------
*
* All the JS for the Search Banner component.
*/

/*
 * Example of importing modules if needed. Like for scroll magic / gsap
 */

import {gsap} from 'gsap';
import {ScrollTrigger} from 'gsap/ScrollTrigger.js';
( function( app ) {
  const COMPONENT = {

    className: 'search-banner',
    selector: function() {
      return '.' + this.className;
    },
    // Fires after common.init, before .finalize and common.finalize
    init: function() {
      const filters = {};
      $( '.search-banner .clear-filters' ).click( function() {
        $( '.search-banner input' ).val( '' );
        $( '.search-banner input[type="checkbox"], .search-banner input[type="radio"]' ).prop( 'checked', false );
      } );

      document.addEventListener( 'change', ( event ) => {
        if ( !event.target.matches( '.filter-input' ) ) return;

        const value = event.target.value;
        const param = event.target.name;
        const assocations = document.querySelectorAll( `[data-association^="${param}"]` );

        filters[param] = event.target.dataset.label;

        if ( assocations ) {
          assocations.forEach( ( item, index ) => {
            if ( item.dataset.association === `${param}|${value}` ) {
              item.classList.remove( 'hidden' );
            } else {
              item.classList.add( 'hidden' );
            }

            item.querySelector( 'input' ).checked = false;
          } );
        }
      } );
    },
    finalize: function() {
    },
  };

  // Hooks the component into the app
  app.registerComponent( 'search-banner', COMPONENT );
} )( app );
