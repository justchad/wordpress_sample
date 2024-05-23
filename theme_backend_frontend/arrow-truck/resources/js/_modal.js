/* ========================================================================
 * DOM-based Routing
 * Based on http://goo.gl/EUTi53 by Paul Irish
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 *
 * .noConflict()
 * The routing is enclosed within an anonymous function so that you can
 * always reference jQuery with $, even when in .noConflict() mode.
 * ======================================================================== */

( function( app ) {
  const COMPONENT = {

    loadPopup: function( popupId ) {
      $.magnificPopup.open( {
        items: {
          src: popupId,
        },
        type: 'inline',
        fixedContentPos: true,
        fixedBgPos: true,
        overflowY: 'auto',
        closeBtnInside: true,
        preloader: false,
        midClick: true,
        removalDelay: 300,
      }, 0 );
    },

    init: function() {
      const _this = this;
      $( document ).on( 'click', '.js-init-popup', function( e ) {
        e.preventDefault();
        const popupId = $( this ).data( 'modal' );

        if ( popupId !== '' ) {
          _this.loadPopup( popupId );
        }
      } );
    },

    finalize: function() {
    },
  };

  app.registerComponent( 'modal', COMPONENT );
} )( app );
