import {gsap} from 'gsap';
import {ScrollTrigger} from 'gsap/ScrollTrigger.js';
( function( app ) {
  const COMPONENT = {
    init: function() {
      const _this = this;
      /*
       * Fade on scroll
       */
      gsap.registerPlugin( ScrollTrigger );
      $( '.js-fade:not(.js-ignore), .js-fade-group > *:not(.js-ignore)' ).each( function() {
        gsap.registerPlugin( ScrollTrigger );
        const tl = gsap.timeline( {
          scrollTrigger: {
            trigger: this,
            start: 'top 90%',
            scrub: 0.15,
            onRefresh: ( self ) => {
              if ( self.progress > 0 ) {
                $( this ).addClass( 'js-animated' );
              }
            },
            onEnter: ( {progress, direction, isActive} ) => $( this ).addClass( 'js-animated' ),
          },
        } );
      } );

      /*
       * Reveal on scroll
       */
      gsap.registerPlugin( ScrollTrigger );
      $( '.js-reveal:not(.js-ignore)' ).each( function() {
        const tl = gsap.timeline( {
          scrollTrigger: {
            trigger: this,
            start: 'top 90%',
            scrub: false,
          },
        } );
        tl.to( this, {clipPath: 'polygon(0 0, 100% 0, 100% 100%, 0 100%)', duration: 1.5, ease: 'power4.inOut'} );
      } );
    },
    finalize: function() {
    },
  };
  app.registerComponent( 'animations', COMPONENT );
} )( app );
