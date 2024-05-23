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
      const root = document.documentElement;
      const adminBar = document.querySelector( '#wpadminbar' );
      const bottomNav = document.querySelector( '#bottom-nav' );
      let adminbarHeight = ( adminBar ? adminBar.offsetHeight : 0 );
      let bottomNavHeight = ( bottomNav ? bottomNav.offsetHeight : 0 );
      root.style.setProperty( '--navbarHeight', `${document.querySelector( '.navbar' ).offsetHeight}px` );
      root.style.setProperty( '--adminbarHeight', `${adminbarHeight}px` );
      root.style.setProperty( '--topnavHeight', `${document.querySelector( '#action-nav' ).offsetHeight}px` );
      root.style.setProperty( '--bottomNavHeight', `${bottomNavHeight}px` );


      const getCookies = function() {
        const pairs = document.cookie.split( ';' );
        const cookies = {};
        for ( let i=0; i<pairs.length; i++ ) {
          const pair = pairs[i].split( '=' );
          cookies[( pair[0]+'' ).trim()] = unescape( pair.slice( 1 ).join( '=' ) );
        }
        return cookies;
      };


      if ( getCookies().fromcanada ) {
        // .iscanadaphone
        const classExists = document.getElementsByClassName(
                     '.iscanadaphone'
                    ).length > 0;

          if ( classExists ) {
            classExists.textContent = '(855-313-9099)';
            classExists.setAttribute( 'href', 'tel:8553139099' );
          }
      }


      getUrlParameter = function getUrlParameter( sParam ) {
        const sPageURL = window.location.search.substring( 1 );
            const sURLVariables = sPageURL.split( '&' );
            let sParameterName;
            let i;

        for ( i = 0; i < sURLVariables.length; i++ ) {
            sParameterName = sURLVariables[i].split( '=' );

            if ( sParameterName[0] === sParam ) {
                return sParameterName[1] === undefined ? true : decodeURIComponent( sParameterName[1] );
            }
        }
        return false;
      };


      getCookie = function getCookie( cname ) {
        const name = cname + '=';
        const decodedCookie = decodeURIComponent( document.cookie );
        const ca = decodedCookie.split( ';' );
        for ( let i = 0; i <ca.length; i++ ) {
          let c = ca[i];
          while ( c.charAt( 0 ) == ' ' ) {
            c = c.substring( 1 );
          }
          if ( c.indexOf( name ) == 0 ) {
            return c.substring( name.length, c.length );
          }
        }
        return '';
      };

      // if ( $( '.truck-inquiry-form' ).length >= 1 ) {
      //     let comm;
      //
      //     if ( getUrlParameter( 'info_1' ).length >= 1 ) {
      //       comm += 'Year: ' + getUrlParameter( 'info_1' ) + ', \n';
      //     }
      //
      //     if ( getUrlParameter( 'info_2' ).length >= 1 ) {
      //       comm += 'Make: ' + getUrlParameter( 'info_2' ) + ', \n';
      //     }
      //
      //     if ( getUrlParameter( 'info_3' ).length >= 1 ) {
      //       comm += 'Model: ' + getUrlParameter( 'info_3' ) + ', \n';
      //     }
      //
      //     if ( getUrlParameter( 'info_4' ).length >= 1 ) {
      //       comm += 'Stock: ' + getUrlParameter( 'info_4' ) + ', \n';
      //     }
      //
      //     if ( getUrlParameter( 'price' ).length >= 1 ) {
      //       comm += 'Price: ' + getUrlParameter( 'price' ) + ', \n';
      //     }
      //
      //     if ( getUrlParameter( 'mileage' ).length >= 1 ) {
      //       comm += 'Mileage: ' + getUrlParameter( 'mileage' );
      //     }
      //
      //     if ( comm != undefined ) {
      //       $( '.truck-watch-comments textarea' ).val( comm );
      //     }
      // }

      $( '#main-nav-toggle' ).on( 'toggleAfter', ( event ) => {
        if ( event.target.isToggleActive ) {
          $( 'body' ).addClass( 'overflow-hidden' );
          $( '.primary-nav' ).removeClass( 'hidden' );
          $( '.primary-nav' ).slideDown();
        } else {
          $( '.primary-nav' ).addClass( 'hidden' );
          $( 'body' ).removeClass( 'overflow-hidden' );
          $( '.primary-nav' ).slideUp();
        }
      } );

      $( '#location-action' ).on( 'toggleAfter', ( event ) => {
        if ( event.target.isToggleActive ) {
          $( 'body' ).addClass( 'overflow-hidden' );
        } else {
          $( 'body' ).removeClass( 'overflow-hidden' );
        }
      } );

      $( '[data-toggle-target*="#advanced-search"]' ).on( 'toggleAfter', ( event ) => {
        if ( event.target.isToggleActive ) {
          $( 'body' ).addClass( 'search-open' );
          $( 'body' ).addClass( 'overflow-hidden' );
          // console.log( '1' );
        } else {
          $( 'body' ).removeClass( 'search-open' );
          $( 'body' ).removeClass( 'overflow-hidden' );
          // console.log( '2' );
        }
      } );


      // also need to check to see if cookie set..


      // transfer rep no to credit application
      if ( getUrlParameter( 'rep' ).length >= 1 ) {
        const oldRef = $( '#credit-application-button-ref' ).attr( 'href' );
        const newRef = oldRef + '?sls=' + getUrlParameter( 'rep' );
        $( '#credit-application-button-ref' ).attr( 'href', newRef );
      } else {
        if ( getCookie( 'rep_no_raw' ) ) {
          const oldRef = $( '#credit-application-button-ref' ).attr( 'href' );
          const newRef = oldRef + '?sls=' + getCookie( 'rep_no_raw' );
          $( '#credit-application-button-ref' ).attr( 'href', newRef );
        }
      }


      $( '.js-init-video' ).magnificPopup( {
        type: 'iframe',
        mainClass: 'mfp-fade',
        removalDelay: 160,
        preloader: false,
        fixedContentPos: false,
        callbacks: {
          open: function() {
            $( 'video' ).trigger( 'pause' );
          },
          close: function() {
            $( 'video' ).trigger( 'play' );
          },
        },
      } );

      $( document ).ready( function() {
        $( '.page-semi-truck-dealers-near-you .logos' ).each( function( index, el ) {
            $( this ).find( 'a' ).attr( 'target', '_blank' );
        } );
      } );

      $( document ).on( 'updateMediaQuery', ( event ) => {
        /*
         * Remove any inline display values when the screen changes
         * between mobile and desktop state. This allows the default
         * stylings to kick in and prevent any weird "half mobile half desktop"
         * nav display states that sometimes occur while resizing the browser
         * Also remove any active is-open classes from the toggle and nav to reset
         * its state when switching between screen sizes
         */
        adminbarHeight = ( adminBar ? adminBar.offsetHeight : 0 );
        bottomNavHeight = ( bottomNav ? bottomNav.offsetHeight : 0 );
        root.style.setProperty( '--navbarHeight', `${document.querySelector( '.navbar' ).offsetHeight}px` );
        root.style.setProperty( '--adminbarHeight', `${adminbarHeight}px` );
        root.style.setProperty( '--topnavHeight', `${document.querySelector( '#action-nav' ).offsetHeight}px` );
        root.style.setProperty( '--bottomNavHeight', `${bottomNavHeight}px` );

        $( '.primary-nav' ).get( 0 ).style.removeProperty( 'display' );
        $( '#main-nav-toggle, .primary-nav' ).removeClass( 'is-open' );
      } );

      $( '.form-buttons button, [aria-haspopup="true"]' ).on( 'toggleAfter', ( event ) => {
        $( this ).attr( 'aria-expanded', function( index, attr ) {
          return attr == false ? true : false;
        } );
      } );

      $( document ).on( 'click', '.form-buttons button', function( e ) {
        const top = $( this )[0].offsetTop + $( this ).outerHeight() + 5;
        let left = $( this )[0].offsetLeft;
        const arrowLeft = ( left + ( $( this ).width() / 2 ) );

        if ( window.innerWidth > 1024 ) {
          if ( left > ( ( window.innerWidth / 2 ) - 5 ) ) {
            $( this ).next().addClass( 'is-reversed' );
            left = ( ( left + $( this ).width() ) - $( this ).next().width() ) + 18;
            $( this ).next().css( {
              'top': top,
              'left': left,
            } );
            $( this ).next().find( '.arrow' ).css( 'right', $( this ).width() / 2 );
          } else {
            $( this ).next().css( {
              'top': top,
              'left': left,
            } );
            $( this ).next().find( '.arrow' ).css( 'left', $( this ).width() / 2 );
          }
        } else {
          $( this ).next().css( {
            'top': top,
            'left': 0,
          } );
          $( this ).next().find( '.arrow' ).css( 'left', arrowLeft );
        }
      } );

      $( '.flex-slider' ).each( function() {
        const arrows = $( this ).attr( 'data-arrows' ) == 'true' ? true : false;
        const dots = $( this ).attr( 'data-dots' ) == 'true' ? true : false;
        const sizes = $( this ).attr( 'data-slides-to-show' ).split( ' ' );
        let startSlides = 0;
        let startSlick = 0;
        const responsive = [];

        $.each( sizes, function( key, size ) {
          const sizeSlides = size.substr( 3 );
          const trimSize = size.substr( 0, 2 );
          let width;
          switch ( trimSize ) {
            case 'xs':
              width = 640;
              break;
            case 'sm':
              width = 768;
              break;
            case 'md':
              width = 1024;
              break;
            case 'lg':
              width = 1270;
              break;
          }

          const sizeSettings = {
            breakpoint: width,
            settings: {
              slidesToShow: parseInt( sizeSlides ),
            },
          };
          responsive.push( sizeSettings );
          startSlick = width;
          startSlides = sizeSlides;
        } );

        if ( window.innerWidth < startSlick ) {
          $( this ).slick( {
            slidesToShow: startSlides,
            arrows: arrows,
            dots: dots,
            responsive: responsive,
          } );
        }
      } );

      if ( $( '.gform_wrapper' ).length > 0 && typeof( gform ) !== 'undefined' ) {
        gform.addAction( 'gform_input_change', function( elem, formId, fieldId ) {
          if ( $( '#input_' + formId + '_' + fieldId ).val() ) {
            const path = $( '#input_' + formId + '_' + fieldId ).val().replace( 'C:\\fakepath\\', '' );
            $( '#input_' + formId + '_' + fieldId ).closest( '.ginput_container_fileupload' ).find( '.file-upload-label' ).text( path );
            $( '#input_' + formId + '_' + fieldId ).closest( '.ginput_container_fileupload' ).addClass( 'has-file' );
          } else {
            $( '#input_' + formId + '_' + fieldId ).closest( '.ginput_container_fileupload' ).find( '.file-upload-label' ).text( 'No file selected' );
            $( '#input_' + formId + '_' + fieldId ).closest( '.ginput_container_fileupload' ).removeClass( 'has-file' );
          }
        }, 10, 3 );
      }
    },
    finalize: function() {
      const _this = this;
      const salescontact = localStorage.getItem( 'salescontact' );

      let referer = site_info.referer.split( '/' );
      if ( referer.length > 0 ) {
        referer = referer[0] + '//' + referer[2];
      }

      if ( document.querySelector( '#location-action-2' ) ) {
        // console.log( 'salescontact: ', salescontact );
        // console.log( 'data-component: ', document.querySelectorAll( '[data-component="set-code"]' ) );
        // console.log( 'referer: ', referer );

        const bypass = true;


        // if ( ( !salescontact || document.querySelectorAll( '[data-component="set-code"]' ).length > 0 ) && referer !== window.location.origin ) {
        if ( bypass == true ) {
          // console.log( 'Step 1' );

          $.ajax( {
            type: 'GET',
            url: site_info.wpApiSettings.ll + 'user/data',
            data: {},
            beforeSend: function( xhr ) {
              xhr.setRequestHeader( 'X-WP-Nonce', site_info.wpApiSettings.nonce );
              xhr.setRequestHeader( 'X-Requested-With', 'XMLHttpRequest' );
            },
            success: function( data ) {
              // console.log( data );
              if ( !data.location ) {
                return;
              }

              let headerText = '';
              let valueText = '';
              let newObj = {};


              switch ( data.type ) {
                case 'rep':
                  data.location.wp_user = null;
                  headerText = 'Sales Contact: ';
                  valueText = data.location.first_name + ' ' + data.location.last_initial;
                  newObj = data;
                  break;
                case 'location':
                  headerText = 'Location: ';
                  valueText = data.location.wp_post.post_title;
                  newObj.location = {};
                  newObj.location.location = data.location;
                  break;
                default:
              }

              // console.log( headerText );
              // console.log( valueText );
              // console.log( newObj );

              document.querySelector( '#location-header-2' ).innerText = headerText;
              document.querySelector( '#location-value-2' ).innerText = valueText;
              document.querySelector( '#location-action-2' ).classList.remove( 'invisible' );
              document.querySelector( '#action-nav' ).classList.remove( 'hidden' );
              localStorage.setItem( 'salescontact', JSON.stringify( data ) );
              _this.buildLocation( newObj );
            },
          } );
        } else {
          // console.log( 'Step 2' );

          data = JSON.parse( salescontact );
          let headerText = '';
          let valueText = '';
          let newObj = {};

          switch ( data.type ) {
            case 'rep':
              headerText = 'Sales Contact: ';
              valueText = data.location.first_name + ' ' + data.location.last_initial;
              newObj = data;
              break;
            case 'location':
              headerText = 'Location: ';
              valueText = data.location.wp_post.post_title;
              newObj.location = {};
              newObj.location.location = data.location;
              break;
            default:
          }

          document.querySelector( '#location-header' ).innerText = headerText;
          document.querySelector( '#location-value' ).innerText = valueText;
          document.querySelector( '#location-action' ).classList.remove( 'invisible' );
          document.querySelector( '#action-nav' ).classList.remove( 'hidden' );
          localStorage.setItem( 'salescontact', JSON.stringify( data ) );
          _this.buildLocation( newObj );
        }
      }

      $( '.wishlist-link' ).on( 'toggleAfter', ( event ) => {
        if ( event.target.isToggleActive ) {
          if ( favCount > 0 ) {
            const favorites = JSON.parse( sessionStorage.getItem( 'arrow_favorites' ) );
            $( 'body' ).addClass( 'overflow-hidden' );

            $.post(
                site_info.ajax_url,
                {
                  action: 'll_run_function',
                  function: 'll_get_favorite_truck_list',
                  token: site_info.ajax_nonce,
                  params: {
                    trucks: favorites,
                  },
                },
                function( data, textStatus, xhr ) {
                  data = $.parseJSON( data );
                  $( '#favorites-list' ).html( data.response );
                }
            );
          } else {
            $( '#favorites-dropdown' ).addClass( 'empty' );
          }
        } else {
          $( 'body' ).removeClass( 'overflow-hidden' );
          document.querySelector( '.favorites-tooltip' ).classList.add( 'hidden' );
        }
      } );

      /*
       * Get count
       */
      const wishLink = document.querySelector( '.wishlist-link' );
      const favoritesTip = document.querySelector( '.favorites-tooltip' );

      if ( wishLink != null ) {
        favoritesTip.style.left = `${wishLink.offsetLeft + wishLink.offsetWidth + 25}px`;
      }

      $.ajax( {
        type: 'GET',
        url: site_info.wpApiSettings.ll + 'user/favorites',
        beforeSend: function( xhr ) {
          xhr.setRequestHeader( 'X-WP-Nonce', site_info.wpApiSettings.nonce );
          xhr.setRequestHeader( 'X-Requested-With', 'XMLHttpRequest' );
        },
        success: function( data ) {
          sessionStorage.setItem( 'arrow_favorites', JSON.stringify( data.results ) );
          if ( !data.results ) {
            favCount = 0;
          } else {
            favCount = data.results.length;
          }

          $( '.wishlist-count' ).text( favCount );

          if ( favCount == 0 ) {
            $( '#favorites-dropdown' ).addClass( 'empty' );
          }

          if ( $( '.add-to-wishlist' ).length ) {
            const currentTruck = $( '.add-to-wishlist' ).data( 'truck' );
            if ( data.results && data.results.includes( currentTruck ) ) {
              $( '.add-to-wishlist' ).addClass( 'favorited' );
            }
          }
        },
        complete: function( jqXHR, status ) {
        },
      } );

      document.addEventListener( 'click', ( event ) => {
        if ( !event.target.matches( '.favorite-remove' ) ) return;

        const truck = event.target.dataset.truck;
        event.target.closest( '.truck-card' ).remove();

        $.ajax( {
          type: 'DELETE',
          url: site_info.wpApiSettings.ll + 'user/favorites',
          data: {
            truck: truck,
          },
          beforeSend: function( xhr ) {
            xhr.setRequestHeader( 'X-WP-Nonce', site_info.wpApiSettings.nonce );
            xhr.setRequestHeader( 'X-Requested-With', 'XMLHttpRequest' );
          },
          success: function( data ) {
            if ( data && data.results.length > 0 ) {
              sessionStorage.setItem( 'arrow_favorites', JSON.stringify( data.results ) );
            } else {
              sessionStorage.setItem( 'arrow_favorites', '{}' );
            }

            $( '.wishlist-count' ).text( data.results.length );
            favCount = data.results.length;
            if ( favCount == 0 ) {
              $( '#favorites-dropdown' ).addClass( 'empty' );
            } else {
              $( '#favorites-dropdown' ).removeClass( 'empty' );
            }
          },
          complete: function( jqXHR, status ) {
          },
        } );
      } );

      $( document ).on( 'gform_page_loaded', function( event, formId, currentPage ) {
        const params = new URLSearchParams( window.location.search );
        const formPage = parseInt( params.get( 'form_page' ) );

        if ( currentPage == 1 && formPage == 2 && params.get( 'first_name' ) ) {
          document.querySelector( '.gform_page .gform_next_button' ).click();
        }
      } );
    },
    buildLocation: function( data ) {
      const capitalize = ( s ) => {
        if ( typeof s !== 'string' ) return '';
        return s.charAt( 0 ).toUpperCase() + s.slice( 1 );
      };


      const template = `
        <div class="md:col-span-8 lg:col-span-10 lg:col-start-2 text-center pt-8 pb-16">

          <h2 class="hdg-3">${data.location.location.title} Branch</h2>

          <a href="${data.location.location.addressLink}" class="inline-block hover:underline" target="_blank">
            <address class="not-italic font-bold mt-3 text-gray-400">
              <span class="block">${data.location.location.address.street}</span>
              <span class="block">${data.location.location.address.city}, ${data.location.location.address.state} ${data.location.location.address.zip}</span>
            </address>
          </a>

          <p class="text-sm mt-2">${data.location.location.directions}</p>

          <div class="flex items-center justify-center mt-10">
            <a href="${data.location.location.inventoryLink}" class="mx-1 lg:mx-6 font-medium hover:text-brand-primary inline-flex items-center">
              <svg class="icon icon-search text-brand-primary mr-2 text-lg svg-align"><use xlink:href="#icon-search"></use></svg>
              Inventory
            </a>

            <a href="tel:${data.location.location.phone}" class="mx-1 lg:mx-6 font-medium hover:text-brand-primary inline-flex items-center">
              <svg class="icon icon-phone text-brand-primary mr-2 text-lg svg-align"><use xlink:href="#icon-phone"></use></svg>
              Call
            </a>

            <a href="${data.location.location.addressLink}" class="mx-1 lg:mx-6 font-medium hover:text-brand-primary inline-flex items-center" target="_blank">
              <svg class="icon icon-pin text-brand-primary mr-2 text-lg svg-align"><use xlink:href="#icon-pin"></use></svg>
              Directions
            </a>

          </div>


          ${data.location.location.languages.length > 0 ? `
          <div class="my-10 text-center mt-12">
            <h2 class="hdg-2 text-lg md:text-3xl lg:text-4xl font-bold mb-3 md:mb-10">Multilingual</h2>
              <div class="max-4-col mx-auto bg-brand-light-gray grid gap-4 py-0 px-6 text-center rounded-md p-6 rounded-md">
                <ul class="language-list text-center -mx-3 my-5 flex flex-wrap justify-center">
                  ${data.location.location.languages.map( function( language ) {
                    return `
                    <li class="text-sm text-gray-300 text-center mx-3 my-2">
                      <div class="flag-circle border-4 border-white w-6 h-6">
                        <img src="${site_info.asset_url}/img/flags/${capitalize( language.toLowerCase() )}.svg" alt="flag of language">
                      </div>
                      <div class="font-medium">${capitalize( language.toLowerCase() )}</div>
                    </li>`;
                  } ).join( '' )}
                </ul>
              </div>
          </div>` : '' }


          ${data.location.location.hours.length > 0 ? `
            <div class="mt-12 mb-10">
              <h2 class="hdg-2 text-lg md:text-3xl lg:text-4xl font-bold mb-3 md:mb-10 text-center">Branch Hours</h2>
              <ul class="schema-hours max-4-col mx-auto">
                ${data.location.location.hours.map( function( range ) {
                  return `
                  <li class="flex justify-between items-center tab-row rounded py-2 px-6 bg-gray-100 mb-2">
                    <span class="font-bold w-6/12 text-left">${range.days}</span>
                    <span class="w-6/12 text-left">${range.hours}</span>
                  </li>`;
                } ).join( '' )}
              </ul>
            </div>` : ''}

          <a class="btn is-plain w-full max-4-col mt-10 mb-16 mx-auto" href="${data.location.location.permalink}">View Location</a>
        </div>
      `;


      document.querySelector( '#location-information-dropdown' ).innerHTML = template;

      const root = document.documentElement;
      const adminBar = document.querySelector( '#wpadminbar' );
      const bottomNav = document.querySelector( '#bottom-nav' );
      const adminbarHeight = ( adminBar ? adminBar.offsetHeight : 0 );
      const bottomNavHeight = ( bottomNav ? bottomNav.offsetHeight : 0 );
      root.style.setProperty( '--navbarHeight', `${document.querySelector( '.navbar' ).offsetHeight}px` );
      root.style.setProperty( '--adminbarHeight', `${adminbarHeight}px` );
      root.style.setProperty( '--topnavHeight', `${document.querySelector( '#action-nav' ).offsetHeight}px` );
      root.style.setProperty( '--bottomNavHeight', `${bottomNavHeight}px` );

      if ( document.querySelectorAll( '.accordion-nav .nouislider' ) ) {
        document.querySelectorAll( '.accordion-nav .nouislider' ).forEach( ( item, index ) => {
          const min = item.dataset.min;
          const max = item.dataset.max;
          const difference = item.dataset.step;
          noUiSlider.create( item, {
            start: [min, ( max + difference )],
            connect: true,
            range: {
              'min': parseInt( min ),
              'max': parseInt( max ),
            },
          } );

          item.noUiSlider.on( 'update', ( values, handle, unencoded, tap, positions, noUiSlider ) => {
            const parentGroup = item.closest( '.range' );
            const minInput = document.querySelector( `[name="${parentGroup.dataset.param}_S"]` );
            const maxInput = document.querySelector( `[name="${parentGroup.dataset.param}_E"]` );

            if ( minInput != null ) {
              minInput.value = parseInt( values[0] );
            }

            if ( maxInput != null ) {
              maxInput.value = parseInt( values[1] );
            }
          } );
        } );
      }
    },
  };

  app.registerComponent( 'common', COMPONENT );
} )( app );
