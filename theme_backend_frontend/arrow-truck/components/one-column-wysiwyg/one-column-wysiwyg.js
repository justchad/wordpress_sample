/**
* One Column WYSIWYG JS
* -----------------------------------------------------------------------------
*
* All the JS for the One Column WYSIWYG component.
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

    className: 'one-column-wysiwyg',
    selector: function() {
      return '.' + this.className;
    },
    // Fires after common.init, before .finalize and common.finalize
    init: function() {

    },
    finalize: function() {
    },
  };

  // Hooks the component into the app
  app.registerComponent( 'one-column-wysiwyg', COMPONENT );
} )( app );
