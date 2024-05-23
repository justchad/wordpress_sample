/**
* LL JS
* -----------------------------------------------------------------------------
*
* This is the core of the LLJS system. It's a combination of a couple things,
* DOM-based routing, module-export pattern, and component-driven development.
*
* The goal is to allow component JS to exist within the component's folder
* and only firing if that component is being used on the page.
*/
window.debounce = require( 'lodash/debounce' );
window.throttle = require( 'lodash/throttle' );
window.favCount = 0;
window.createCookie = function( name, value, days ) {
  let expires = '';
  if ( days ) {
    const date = new Date();
    date.setTime( date.getTime() + ( days * 24 * 60 * 60 * 1000 ) );
    expires = '; expires=' + date.toUTCString();
  }

  document.cookie = name + '=' + value + expires + '; path=/';
};

window.getCookie = function( name ) {
  const match = document.cookie.match( new RegExp( '(^| )' + name + '=([^;]+)' ) );
  if ( match ) {
    return match[2];
  }

  return false;
};

( function( $ ) {
  const arrayUnique = function( a ) {
    return a.reduce( function( p, c ) {
      if ( p.indexOf( c ) < 0 ) {
        p.push( c );
      }
      return p;
    }, [] );
  };

  /**
   * The main app.
   *
   * @type {Object}
   */
  const app = {

    components: {},

    registerComponent: function( componentName, component ) {
      this.components[componentName] = component;
    },
  };

  window.app = app;
  window.isMobile = ! window.matchMedia( '(min-width: 768px)' ).matches;
  window.previouslyMobile = window.isMobile;

  $( window ).on( 'resize', debounce( function() {
    window.isMobile = ! window.matchMedia( '(min-width: 768px)' ).matches;
    if ( window.isMobile !== window.previouslyMobile ) {
      window.previouslyMobile = window.isMobile;
      $( document ).trigger( 'updateMediaQuery' );
    }
  }, 100 ) );

  window.toggleGridOverlay = function() {
    const template = `<div id="gridOverlay" class="fixed inset-0 opacity-25 pointer-events-none" style="z-index:9999;">
        <div class="container h-full">
          <div class="row h-full items-stretch">
            <div class="w-1/12 col">
              <div class="h-full" style="background-color: #fc8181;"></div>
            </div>
            <div class="w-1/12 col">
              <div class="h-full" style="background-color: #fc8181;"></div>
            </div>
            <div class="w-1/12 col">
              <div class="h-full" style="background-color: #fc8181;"></div>
            </div>
            <div class="w-1/12 col">
              <div class="h-full" style="background-color: #fc8181;"></div>
            </div>
            <div class="w-1/12 col">
              <div class="h-full" style="background-color: #fc8181;"></div>
            </div>
            <div class="w-1/12 col">
              <div class="h-full" style="background-color: #fc8181;"></div>
            </div>
            <div class="w-1/12 col">
              <div class="h-full" style="background-color: #fc8181;"></div>
            </div>
            <div class="w-1/12 col">
              <div class="h-full" style="background-color: #fc8181;"></div>
            </div>
            <div class="w-1/12 col">
              <div class="h-full" style="background-color: #fc8181;"></div>
            </div>
            <div class="w-1/12 col">
              <div class="h-full" style="background-color: #fc8181;"></div>
            </div>
            <div class="w-1/12 col">
              <div class="h-full" style="background-color: #fc8181;"></div>
            </div>
            <div class="w-1/12 col">
              <div class="h-full" style="background-color: #fc8181;"></div>
            </div>
          </div>
        </div>
      </div>`;
    if ( document.getElementById( 'gridOverlay' ) ) {
      document.getElementById( 'gridOverlay' ).remove();
    } else {
      document.body.insertAdjacentHTML( 'beforeend', template );
    }
  };

  // The routing fires all common scripts, followed by the component-specific
  // scripts. Add additional events for more control over
  // timing e.g. a finalize event
  const UTIL = {
    fire: function( func, funcname, args ) {
      let fire;
      const namespace = app.components;
      funcname = ( funcname === undefined ) ? 'init' : funcname;
      fire = func !== '';
      fire = fire && namespace[func];
      fire = fire && typeof namespace[func][funcname] === 'function';

      if ( fire ) {
        namespace[func][funcname]( args );
      }
    },
    loadEvents: function() {
      // Fire common init JS
      UTIL.fire( 'common' );

      let components = [];

      $( '[data-component]' ).each( function( index, el ) {
        components.push( $( this ).attr( 'data-component' ) );
      } );

      components = arrayUnique( components );

      // Fire component-specific init JS, and then finalize JS
      $.each( components, function( i, classnm ) {
        UTIL.fire( classnm );
        UTIL.fire( classnm, 'finalize' );
      } );

      // Fire common finalize JS
      UTIL.fire( 'common', 'finalize' );
    },
  };

  // Load Events
  $( document ).ready( UTIL.loadEvents );
} )( jQuery );
