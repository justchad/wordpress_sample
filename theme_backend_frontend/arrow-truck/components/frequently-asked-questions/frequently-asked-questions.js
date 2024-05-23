/**
* Frequently Asked Questions JS
* -----------------------------------------------------------------------------
*
* All the JS for the Frequently Asked Questions component.
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

    className: 'faq',
    selector: function() {
      return '.' + this.className;
    },
    // Fires after common.init, before .finalize and common.finalize
    init: function() {
      $( '.faq__item-status, .faq__item-title' ).click( function() {
        if ( $( this ).closest( '.faq__item' ).hasClass( 'is-open' ) ) {
          $( this ).closest( '.faq__item' ).find( '.faq__item-answer' ).slideUp();
          $( this ).closest( '.faq__item' ).removeClass( 'is-open' );
          $( this ).closest( '.faq__item .accordion-trigger' ).attr( 'aria-expanded', false );
        } else {
          $( '.faq__item.is-open .faq__item-answer' ).slideUp();
          $( '.faq__item' ).removeClass( 'is-open' ).not( $( this ).closest( '.faq__item' ) ).find( '.accordion-trigger' ).attr( 'aria-expanded', false );
          $( this ).closest( '.faq__item' ).find( '.faq__item-answer' ).slideDown();
          $( this ).closest( '.faq__item' ).find( '.accordion-trigger' ).attr( 'aria-expanded', true );
          $( this ).closest( '.faq__item' ).addClass( 'is-open' );
        }
      } );

      // Bind keyboard behaviors on the main accordion container
      $( document ).on( 'keydown', '.faq', function( event ) {
        const accordion = this;
        const triggers = Array.prototype.slice.call( accordion.querySelectorAll( '.accordion-trigger' ) );
        const target = event.target;
        const key = event.which.toString();

        const isExpanded = target.getAttribute( 'aria-expanded' ) == 'true';

        // 33 = Page Up, 34 = Page Down
        const ctrlModifier = ( event.ctrlKey && key.match( /33|34/ ) );

        // Is this coming from an accordion header?
        if ( target.classList.contains( 'accordion-trigger' ) ) {
          // Up/ Down arrow and Control + Page Up/ Page Down keyboard operations
          // 38 = Up, 40 = Down
          if ( key.match( /38|40/ ) || ctrlModifier ) {
            const index = triggers.indexOf( target );
            const direction = ( key.match( /34|40/ ) ) ? 1 : -1;
            const length = triggers.length;
            const newIndex = ( index + length + direction ) % length;

            triggers[newIndex].focus();

            event.preventDefault();
          } else if ( key.match( /35|36/ ) ) {
            // 35 = End, 36 = Home keyboard operations
            switch ( key ) {
              // Go to first accordion
              case '36':
                triggers[0].focus();
                break;
                // Go to last accordion
              case '35':
                triggers[triggers.length - 1].focus();
                break;
            }
            event.preventDefault();
          }
        }
      } );
    },
    finalize: function() {
    },
  };

  // Hooks the component into the app
  app.registerComponent( 'faq', COMPONENT );
} )( app );
