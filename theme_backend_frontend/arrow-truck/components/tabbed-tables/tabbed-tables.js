/**
* Tabbed Tables JS
* -----------------------------------------------------------------------------
*
* All the JS for the Tabbed Tables component.
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

    className: 'tabbed-tables',
    selector: function() {
      return '.' + this.className;
    },
    // Fires after common.init, before .finalize and common.finalize
    init: function() {
      let tabItemsWidth = 0;
      $( '.tabs-list .tab-item' ).each( function() {
        tabItemsWidth += $( this ).outerWidth();
      } );

      setTimeout( function() {
        $( '.tabs-list .selector' ).css( {
          'left': $( '.tabs-list .tab-item' ).first().offsetLeft,
          'width': $( '.tabs-list .tab-item' ).first().outerWidth(),
        } );
      } );

      $( '.tabs-list' ).width( tabItemsWidth );

      function activateTab( tab, setFocus ) {
        setFocus = setFocus || true;
        // Deactivate all other tabs
        deactivateTabs();

        // Remove tabindex attribute
        $( tab ).removeAttr( 'tabindex' );

        // Set the tab as selected
        $( tab ).attr( 'aria-selected', 'true' );

        // Get the value of aria-controls (which is an ID)
        const controls = $( tab ).attr( 'aria-controls' );

        // Remove hidden attribute from tab panel to make it visible
        $( '#' + controls ).removeAttr( 'hidden' );

        $( '.tabs-list .selector' ).css( {
          'left': $( tab ).parent()[0].offsetLeft,
          'width': $( tab ).parent().outerWidth(),
        } );

        // Set focus when required
        if ( setFocus ) {
          $( tab ).focus();
        }
      }

      // // Deactivate all tabs and tab panels
      function deactivateTabs() {
        for ( t = 0; t < tabs.length; t++ ) {
          tabs[t].setAttribute( 'tabindex', '-1' );
          tabs[t].setAttribute( 'aria-selected', 'false' );
        }

        for ( p = 0; p < panels.length; p++ ) {
          panels[p].setAttribute( 'hidden', 'hidden' );
        }
      }

      // ########### ACCESSIBILITY ###########

      const tablist = document.querySelectorAll( '[role="tablist"]' )[0];
      let tabs;
      let panels;

      generateArrays();

      function generateArrays() {
        tabs = document.querySelectorAll( '[role="tab"]' );
        panels = document.querySelectorAll( '[role="tabpanel"]' );
      }

      // For easy reference
      const keys = {
        end: 35,
        home: 36,
        left: 37,
        up: 38,
        right: 39,
        down: 40,
        delete: 46,
        enter: 13,
        space: 32,
      };

      // Add or substract depenign on key pressed
      const direction = {
        37: -1,
        38: -1,
        39: 1,
        40: 1,
      };

      // Bind listeners
      for ( i = 0; i < tabs.length; ++i ) {
        addListeners( i );
      }

      function addListeners( index ) {
        tabs[index].addEventListener( 'click', clickEventListener );
        tabs[index].addEventListener( 'keydown', keydownEventListener );
        tabs[index].addEventListener( 'keyup', keyupEventListener );

        // Build an array with all tabs (<button>s) in it
        tabs[index].index = index;
      }

      // When a tab is clicked, activateTab is fired to activate it
      function clickEventListener( event ) {
        const tab = event.target;
        activateTab( tab, false );
      }

      // Handle keydown on tabs
      function keydownEventListener( event ) {
        const key = event.keyCode;

        switch ( key ) {
          case keys.end:
            event.preventDefault();
            // Activate last tab
            focusLastTab();
            break;
          case keys.home:
            event.preventDefault();
            // Activate first tab
            focusFirstTab();
            break;

          // Up and down are in keydown
          // because we need to prevent page scroll >:)
          case keys.up:
          case keys.down:
            determineOrientation( event );
            break;
        }
      }

      // Handle keyup on tabs
      function keyupEventListener( event ) {
        const key = event.keyCode;

        switch ( key ) {
          case keys.left:
          case keys.right:
            determineOrientation( event );
            break;
          case keys.delete:
            determineDeletable( event );
            break;
          case keys.enter:
          case keys.space:
            activateTab( event.target );
            break;
        }
      }

      // When a tablistâ€™s aria-orientation is set to vertical,
      // only up and down arrow should function.
      // In all other cases only left and right arrow function.
      function determineOrientation( event ) {
        const key = event.keyCode;
        const vertical = tablist.getAttribute( 'aria-orientation' ) == 'vertical';
        let proceed = false;

        if ( vertical ) {
          if ( key === keys.up || key === keys.down ) {
            event.preventDefault();
            proceed = true;
          }
        } else {
          if ( key === keys.left || key === keys.right ) {
            proceed = true;
          }
        }

        if ( proceed ) {
          switchTabOnArrowPress( event );
        }
      }

      // Either focus the next, previous, first, or last tab
      // depening on key pressed
      function switchTabOnArrowPress( event ) {
        const pressed = event.keyCode;

        if ( direction[pressed] ) {
          const target = event.target;
          if ( target.index !== undefined ) {
            if ( tabs[target.index + direction[pressed]] ) {
              tabs[target.index + direction[pressed]].focus();
            } else if ( pressed === keys.left || pressed === keys.up ) {
              focusLastTab();
            } else if ( pressed === keys.right || pressed == keys.down ) {
              focusFirstTab();
            }
          }
        }
      }

      // Make a guess
      function focusFirstTab() {
        tabs[0].focus();
      }

      // Make a guess
      function focusLastTab() {
        tabs[tabs.length - 1].focus();
      }
    },
    finalize: function() {
    },
  };

  // Hooks the component into the app
  app.registerComponent( 'tabbed-tables', COMPONENT );
} )( app );
