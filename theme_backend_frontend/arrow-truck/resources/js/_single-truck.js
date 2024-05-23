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
    init: function() {
      const _this = this;
      $( '.single-truck-page .images-gallery' ).slick( {
        arrows: true,
        dots: false,
        slidesToShow: 5,
        infinite: true,
        autoplay: false,
        prevArrow: '<button type="button" class="slick-prev"><svg class="icon icon-left-arrow"><use xlink:href="#icon-left-arrow"></use></svg></button>',
        nextArrow: '<button type="button" class="slick-next"><svg class="icon icon-right-arrow"><use xlink:href="#icon-right-arrow"></use></svg></button>',
        asNavFor: '.main-image-wrapper',
        focusOnSelect: true,
        responsive: [
          {
            breakpoint: 1024,
            settings: {
              slidesToShow: 4,
            },
          },
          {
            breakpoint: 600,
            settings: {
              slidesToShow: 3,
            },
          },
          {
            breakpoint: 480,
            settings: {
              slidesToShow: 2,
            },
          },
        ],
      } );

      $( '.single-truck-page .main-image-wrapper' ).slick( {
        arrows: true,
        dots: false,
        infinite: true,
        autoplay: false,
        slidesToShow: 1,
      } );

      document.addEventListener( 'click', ( event ) => {
        if ( !event.target.matches( '.add-to-wishlist' ) || event.target.matches( '.favorited' ) ) return;

        $.ajax( {
          type: 'POST',
          url: site_info.wpApiSettings.ll + 'user/favorites',
          data: {
            truck: event.target.dataset.truck,
          },
          beforeSend: function( xhr ) {
            xhr.setRequestHeader( 'X-WP-Nonce', site_info.wpApiSettings.nonce );
            xhr.setRequestHeader( 'X-Requested-With', 'XMLHttpRequest' );
          },
          success: function( data ) {
            sessionStorage.setItem( 'arrow_favorites', JSON.stringify( data.results ) );
            $( '.wishlist-count' ).text( data.results.length );
            event.target.classList.add( 'favorited' );
            favCount = data.results.length;

            if ( data.results.length == 0 ) {
              $( '#favorites-dropdown' ).addClass( 'empty' );
            } else {
              $( '#favorites-dropdown' ).removeClass( 'empty' );
            }
          },
          complete: function( jqXHR, status ) {
          },
        } );
      } );
    },

    finalize: function() {
      $( '[data-toggle-target="#get-started"]' ).on( 'toggleAfter', ( event ) => {
        if ( event.target.isToggleActive ) {
          event.target.innerText = 'Close';
          event.target.closest( '.bottom-nav' ).classList.add( 'shadow-none' );
        } else {
          event.target.innerText = 'Get Started';
          event.target.closest( '.bottom-nav' ).classList.remove( 'shadow-none' );
        }
      } );
    },
  };


  $( '#downpayment-single-truck' ).keyup( function() {
    const priceStart = $( this ).attr( 'data-truckprice' );
    const priceEnd = priceStart - $( this ).val();
    const totalAmountRegister = Math.round( priceEnd * 100 ) / 100;
    $( '#totalpayment-single-truck' ).text( '$' + totalAmountRegister.toLocaleString() );
    const estimated = priceEnd / 72;
    const estimatedPlus = ( estimated * .035 ) + estimated;
    const calculatedEstimate1 = Math.round( estimated * 100 ) / 100;
    const calculatedEstimate2 = Math.round( estimatedPlus * 100 ) / 100;
    $( '#totalestimated-single-truck' ).text( '$' + calculatedEstimate1.toLocaleString() + ' - $' + calculatedEstimate2.toLocaleString() );
  } );

  $( '#features-and-specs-head' ).click( function() {
    $( '#features-and-specs-content' ).toggleClass( 'hidden' );
    $( '#features-chevron-down' ).toggleClass( 'hidden' );
    $( '#features-chevron-up' ).toggleClass( 'hidden' );
  } );

  $( '#warranties-head' ).click( function() {
    $( '#warranties-content' ).toggleClass( 'hidden' );
    $( '#warranties-chevron-down' ).toggleClass( 'hidden' );
    $( '#warranties-chevron-up' ).toggleClass( 'hidden' );
  } );

  $( document ).ready( function() {
    // $( '.favorite-truck-link' ).prepend( '<svg class="favorite-heart-like icon icon-heart mr-2 text-xl svg-align"><use xlink:href="#icon-heart"></use></svg>' );
    // $( '.unfavorite-truck-link' ).prepend( '<svg class="favorite-heart-dislike icon icon-heart mr-2 text-xl svg-align"><use xlink:href="#icon-heart"></use></svg>' );
  } );

  $( '.wpfp-span a' ).click( function() {
    // console.log( 'favorite clicked' );
    // setTimeout(
    // function() {
    //   $( '.favorite-truck-link' ).prepend( '<svg class="favorite-heart-like icon icon-heart mr-2 text-xl svg-align"><use xlink:href="#icon-heart"></use></svg>' );
    //   $( '.unfavorite-truck-link' ).prepend( '<svg class="favorite-heart-dislike icon icon-heart mr-2 text-xl svg-align"><use xlink:href="#icon-heart"></use></svg>' );
    // }, 1000 );
  } );


  app.registerComponent( 'single-truck', COMPONENT );
} )( app );
