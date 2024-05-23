/**
* Content Accordion JS
* -----------------------------------------------------------------------------
*
* All the JS for the Content Accordion component.
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

    className: 'content-accordion',
    selector: function() {
      return '.' + this.className;
    },
    // Fires after common.init, before .finalize and common.finalize
    init: function() {
      $( '.content-accordion__item-status, .content-accordion__item-title' ).click( function() {
        if ( $( this ).closest( '.content-accordion__item' ).hasClass( 'is-open' ) ) {
          $( this ).closest( '.content-accordion__item' ).find( '.content-accordion__item-answer' ).slideUp();
          $( this ).closest( '.content-accordion__item' ).removeClass( 'is-open' );
          $( this ).closest( '.content-accordion__item .accordion-trigger' ).attr( 'aria-expanded', false );
        } else {
          $( '.content-accordion__item.is-open .content-accordion__item-answer' ).slideUp();
          $( '.content-accordion__item' ).removeClass( 'is-open' ).not( $( this ).closest( '.content-accordion__item' ) ).find( '.accordion-trigger' ).attr( 'aria-expanded', false );
          $( this ).closest( '.content-accordion__item' ).find( '.content-accordion__item-answer' ).slideDown();
          $( this ).closest( '.content-accordion__item' ).find( '.accordion-trigger' ).attr( 'aria-expanded', true );
          $( this ).closest( '.content-accordion__item' ).addClass( 'is-open' );
        }
      } );

      // Bind keyboard behaviors on the main accordion container
      $( document ).on( 'keydown', '.content-accordion', function( event ) {
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
  app.registerComponent( 'content-accordion', COMPONENT );
} )( app );
