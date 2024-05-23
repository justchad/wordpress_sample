( function( $ ) {
  // custom helper field


  $( '#poststuff #acf-field_630ec830927d1' ).change( function() {
      const element = this;
      setTimeout( function() {
        const text = $( element ).val();
        populatePromo( text, '#poststuff #acf-field_630ec830927d1' );
      }, 100 );
  } );

  $( '#poststuff #acf-field_630ec830927d1' ).on( 'paste', function() {
    const element = this;
    setTimeout( function() {
      const text = $( element ).val();
      populatePromo( text, '#poststuff #acf-field_630ec830927d1' );
    }, 100 );
  } );

  $( '#poststuff #acf-field_6310d920f5cdb' ).change( function() {
      if ( $( this ).is( ':checked' ) ) {
        specifyPromo( 'fleet', true );
      } else {
        specifyPromo( 'fleet', false );
      }
  } );

  $( '#poststuff #acf-field_6310d952f5cdc' ).change( function() {
      if ( $( this ).is( ':checked' ) ) {
        specifyPromo( 'make-model', true );
      } else {
        specifyPromo( 'make-model', false );
      }
  } );

  const specifyPromo = ( value, flatten ) => {
    console.log( value );
    console.log( flatten );

    // http://arrowtruck.local/search-inventory/?stock-num=&invmake=PETE&invmodl=579&invmilag_s=300000&invmilag_e=310000&invprice_s=105000&invprice_e=110000&sort_factor=YEAR&sort_order=DESC&invfleet=

    if ( value === 'make-model' ) {
      if ( flatten === true ) {
        $( '#acf-field_630ec3975cca3-field_630ec555edc63' ).val( '0' ).css( 'background-color', 'lightgray' );
        $( '#acf-field_630ec3975cca3-field_630ec4155cca4 .select2-container .select2-selection' ).css( 'background-color', 'deepskyblue' );
        $( '#acf-field_630ec3975cca3-field_630ec4b37014e .select2-container .select2-selection' ).css( 'background-color', 'deepskyblue' );

        $( '#acf-field_630ec3975cca3-field_630ee3c5ba145' ).val( '0' ).css( 'background-color', 'lightgray' );
        $( '#acf-field_630ec3975cca3-field_630ec509edc61' ).val( '0' ).css( 'background-color', 'lightgray' );
        $( '#acf-field_630ec3975cca3-field_630ee3e8ba146' ).val( '0' ).css( 'background-color', 'lightgray' );
        $( '#acf-field_630ec3975cca3-field_630ec53bedc62' ).val( '0' ).css( 'background-color', 'lightgray' );
        $( '#acf-field_630ec3975cca3-field_630ec56fedc64' ).val( '0' ).css( 'background-color', 'lightgray' );
        $( '#acf-field_630ec3975cca3-field_630ec7e9cc43d' ).val( '0' ).css( 'background-color', 'lightgray' );
      } else {
        $( '#acf-field_630ec3975cca3-field_630ec555edc63' ).val( '0' ).css( 'background-color', 'white' );
        $( '#acf-field_630ec3975cca3-field_630ec4155cca4 .select2-container .select2-selection' ).css( 'background-color', 'white' );
        $( '#acf-field_630ec3975cca3-field_630ec4b37014e .select2-container .select2-selection' ).css( 'background-color', 'white' );

        $( '#acf-field_630ec3975cca3-field_630ee3c5ba145' ).val( '0' ).css( 'background-color', 'white' );
        $( '#acf-field_630ec3975cca3-field_630ec509edc61' ).val( '0' ).css( 'background-color', 'white' );
        $( '#acf-field_630ec3975cca3-field_630ee3e8ba146' ).val( '0' ).css( 'background-color', 'white' );
        $( '#acf-field_630ec3975cca3-field_630ec53bedc62' ).val( '0' ).css( 'background-color', 'white' );
        $( '#acf-field_630ec3975cca3-field_630ec56fedc64' ).val( '0' ).css( 'background-color', 'white' );
        $( '#acf-field_630ec3975cca3-field_630ec7e9cc43d' ).val( '0' ).css( 'background-color', 'white' );
      }
    }

    if ( value === 'fleet' ) {
      if ( flatten === true ) {
        $( '#acf-field_630ec3975cca3-field_630ec555edc63' ).css( 'background-color', 'deepskyblue' );
        $( '#acf-field_630ec3975cca3-field_630ec4155cca4 .select2-container .select2-selection' ).css( 'background-color', 'lightgray' );
        $( '#acf-field_630ec3975cca3-field_630ec4b37014e .select2-container .select2-selection' ).css( 'background-color', 'lightgray' );
        $( '#acf-field_630ec3975cca3-field_630ec4155cca4' ).val( '0' );
        $( '#acf-field_630ec3975cca3-field_630ec4b37014e' ).val( '0' );

        $( '#acf-field_630ec3975cca3-field_630ee3c5ba145' ).val( '0' ).css( 'background-color', 'lightgray' );
        $( '#acf-field_630ec3975cca3-field_630ec509edc61' ).val( '0' ).css( 'background-color', 'lightgray' );
        $( '#acf-field_630ec3975cca3-field_630ee3e8ba146' ).val( '0' ).css( 'background-color', 'lightgray' );
        $( '#acf-field_630ec3975cca3-field_630ec53bedc62' ).val( '0' ).css( 'background-color', 'lightgray' );
        $( '#acf-field_630ec3975cca3-field_630ec56fedc64' ).val( '0' ).css( 'background-color', 'lightgray' );
        $( '#acf-field_630ec3975cca3-field_630ec7e9cc43d' ).val( '0' ).css( 'background-color', 'lightgray' );
      } else {
        $( '#acf-field_630ec3975cca3-field_630ec555edc63' ).css( 'background-color', 'white' );
        $( '#acf-field_630ec3975cca3-field_630ec4155cca4 .select2-container .select2-selection' ).css( 'background-color', 'white' );
        $( '#acf-field_630ec3975cca3-field_630ec4b37014e .select2-container .select2-selection' ).css( 'background-color', 'white' );

        $( '#acf-field_630ec3975cca3-field_630ee3c5ba145' ).val( '0' ).css( 'background-color', 'white' );
        $( '#acf-field_630ec3975cca3-field_630ec509edc61' ).val( '0' ).css( 'background-color', 'white' );
        $( '#acf-field_630ec3975cca3-field_630ee3e8ba146' ).val( '0' ).css( 'background-color', 'white' );
        $( '#acf-field_630ec3975cca3-field_630ec53bedc62' ).val( '0' ).css( 'background-color', 'white' );
        $( '#acf-field_630ec3975cca3-field_630ec56fedc64' ).val( '0' ).css( 'background-color', 'white' );
        $( '#acf-field_630ec3975cca3-field_630ec7e9cc43d' ).val( '0' ).css( 'background-color', 'white' );
      }
    }
  };

  const getUrlVars = ( url ) => {
      const vars = []; let hash;
      const hashes = url.slice( url.indexOf( '?' ) + 1 ).split( '&' );
      for ( let i = 0; i < hashes.length; i++ ) {
          hash = hashes[i].split( '=' );
          vars.push( hash[0] );
          vars[hash[0]] = hash[1];
      }
      return vars;
  };

  const populatePromo = ( value, dom ) => {
    $( dom ).css( 'background-color', 'palegreen' );
    const url = getUrlVars( value );
    // console.log( url );
    // define element values
    const filters = {
      make: ( 'invmake' in url ) ? ( url['invmake'] === '0' || url['invmake'] === '' || url['invmake'] === null ) ? '0' : url['invmake'] : '0', // acf-field_630ec3975cca3-field_630ec4155cca4
      model: ( 'invmodl' in url ) ? ( url['invmodl'] === '0' || url['invmodl'] === '' || url['invmodl'] === null ) ? '0' : url['invmodl'] : '0', // acf-field_630ec3975cca3-field_630ec4b37014e
      minmileage: ( 'invmilag_s' in url ) ? ( url['invmilag_s'] === '0' || url['invmilag_s'] === '' || url['invmilag_s'] === null ) ? '0' : url['invmilag_s'] : '0', // acf-field_630ec3975cca3-field_630ee3c5ba145
      maxmileage: ( 'invmilag_e' in url ) ? ( url['invmilag_e'] === '0' || url['invmilag_e'] === '' || url['invmilag_e'] === null ) ? '0' : url['invmilag_e'] : '0', // acf-field_630ec3975cca3-field_630ec509edc61
      minprice: ( 'invprice_s' in url ) ? ( url['invprice_s'] === '0' || url['invprice_s'] === '' || url['invprice_s'] === null ) ? '0' : url['invprice_s'] : '0', // acf-field_630ec3975cca3-field_630ee3e8ba146
      maxprice: ( 'invprice_e' in url ) ? ( url['invprice_e'] === '0' || url['invprice_e'] === '' || url['invprice_e'] === null ) ? '0' : url['invprice_e'] : '0', // acf-field_630ec3975cca3-field_630ec53bedc62
      fleetcode: ( 'invfleet' in url ) ? ( url['invfleet'] === '0' || url['invfleet'] === '' || url['invfleet'] === null ) ? '0' : url['invfleet'] : '0', // acf-field_630ec3975cca3-field_630ec555edc63
      minyear: '0', // acf-field_630ec3975cca3-field_630ec56fedc64
      maxyear: '0', // acf-field_630ec3975cca3-field_630ec7e9cc43d
    };

    // $( '#acf-field_630ec3975cca3-field_630ec4155cca4' ).val( filters['make'] ).css( 'background-color', 'palegreen' );
    // $( '#acf-field_630ec3975cca3-field_630ec4b37014e' ).val( filters['model'] ).css( 'background-color', 'palegreen' );
    $( '#acf-field_630ec3975cca3-field_630ee3c5ba145' ).val( filters['minmileage'] ).css( 'background-color', 'palegreen' );
    $( '#acf-field_630ec3975cca3-field_630ec509edc61' ).val( filters['maxmileage'] ).css( 'background-color', 'palegreen' );
    $( '#acf-field_630ec3975cca3-field_630ee3e8ba146' ).val( filters['minprice'] ).css( 'background-color', 'palegreen' );
    $( '#acf-field_630ec3975cca3-field_630ec53bedc62' ).val( filters['maxprice'] ).css( 'background-color', 'palegreen' );
    $( '#acf-field_630ec3975cca3-field_630ec555edc63' ).val( filters['fleetcode'] ).css( 'background-color', 'palegreen' );
    $( '#acf-field_630ec3975cca3-field_630ec56fedc64' ).val( filters['minyear'] ).css( 'background-color', 'palegreen' );
    $( '#acf-field_630ec3975cca3-field_630ec7e9cc43d' ).val( filters['maxyear'] ).css( 'background-color', 'palegreen' );

    // console.log( filters );
  };

  const copyToClipboard = ( str ) => {
    const el = document.createElement( 'textarea' );
    el.value = str;
    document.body.appendChild( el );
    el.select();
    document.execCommand( 'copy' );
    document.body.removeChild( el );
  };

  /*
   * Custom group labels on load
   */
  const groupLabels = document.querySelectorAll( '.ll-group-label input' );
  if ( groupLabels.length > 0 ) {
    groupLabels.forEach( ( el, index ) => {
      el.closest( '.layout' ).querySelector( '.acf-fc-layout-handle' ).insertAdjacentHTML( 'beforebegin', `<span class="ll-group-title" contenteditable="true">${el.value}</span>` );
    } );
  }

  /*
   * Custom Anchor target and component preview
   */
  const groupNames = document.querySelectorAll( '.ll-target-name input' );
  if ( groupNames.length > 0 ) {
    let permalinkEl = document.getElementById( 'sample-permalink' );
    let permalink = permalinkEl.getAttribute( 'href' );
    if ( !permalink ) {
      permalinkEl = document.querySelectorAll( '#sample-permalink a' );
      permalink = permalinkEl[0].getAttribute( 'href' );
    }

    groupNames.forEach( ( el, index ) => {
      const groupKey = el.closest( '.layout' ).querySelector( '.component-key' ).dataset.key;
      const layoutHandle = el.closest( '.layout' ).querySelector( '.acf-fc-layout-handle' );
      const controls = el.closest( '.layout' ).querySelector( '.acf-fc-layout-controls' );

      layoutHandle.insertAdjacentHTML( 'beforebegin', `<span class="ll-target-title" contenteditable="true">${el.value}</span><span class="ll-target-link">${permalink}#<span>${el.value}</span></span>` );
      controls.innerHTML = `<a target="_blank" href="/component-preview?component=${groupKey}" class="acf-icon -preview small light acf-js-tooltip" href="#" data-name="preview-layout" title="Preview Component"></a>${controls.innerHTML}`;
    } );
  }

  /*
   * Sync custom group label to the hidden acf input
   *
   */
  document.addEventListener( 'input', ( event ) => {
    if ( !event.target.matches( '.ll-group-title' ) ) return;

    event.target.closest( '.layout' ).querySelector( '.ll-group-label input' ).value = event.target.innerText;
  } );

  /*
   * Sync text from custom target to popup text and hidden acf input
   *
   */
  document.addEventListener( 'input', ( event ) => {
    if ( !event.target.matches( '.ll-target-title' ) ) return;

    const el = event.target;
    const layout = el.closest( '.layout' );
    layout.querySelector( '.ll-target-name input' ).value = el.innerText;
    let str = el.innerText.replace( /^\s+|\s+$/g, '' );
    str = str.toLowerCase();

    // remove accents, swap ñ for n, etc
    const from = 'àáäâèéëêìíïîòóöôùúüûñç·/_,:;';
    const to = 'aaaaeeeeiiiioooouuuunc------';
    for ( let i=0, l=from.length; i<l; i++ ) {
      str = str.replace( new RegExp( from.charAt( i ), 'g' ), to.charAt( i ) );
    }

    str = str.replace( /[^a-z0-9 -]/g, '' ).replace( /\s+/g, '-' ).replace( /-+/g, '-' );
    layout.querySelector( '.ll-target-link' ).classList.remove( 'copied' );
    layout.querySelector( '.ll-target-link span' ).innerHTML = str;
  } );

  /*
   * When a components custom target input is focused,
   * hide any open ones and then make this one visible.
   */
  document.addEventListener( 'click', ( event ) => {
    if ( !event.target.matches( '.ll-target-title' ) ) return;

    const el = event.target;
    const visibleTargetLinks = document.querySelectorAll( '.ll-target-link.visible' );
    if ( visibleTargetLinks.length > 0 ) {
      visibleTargetLinks.forEach( ( el ) => {
        el.classList.remove( 'visible' );
      } );
    }

    el.closest( '.layout' ).querySelector( '.ll-target-link' ).classList.add( 'visible' );
  } );

  document.addEventListener( 'click', ( event ) => {
    if ( !event.target.matches( '.ll-target-link' ) ) return;

    const copyText = event.target.innerText;
    copyToClipboard( copyText );
    event.target.classList.add( 'visible', 'copied' );

    setTimeout( () => {
      event.target.classList.remove( 'copied' );
    }, 3000 );
  } );

  document.addEventListener( 'click', ( event ) => {
    if ( !event.target.matches( '.ll-target-title' ) && !event.target.matches( '.ll-target-link.visible' ) ) {
      const visibleLinks = document.querySelectorAll( '.ll-target-link.visible' );
      if ( visibleLinks.length > 0 ) {
        visibleLinks.forEach( ( el ) => {
          el.classList.remove( 'visible' );
        } );
      }
    }
  } );

  /*
   * on sales rep screen
   */
  const arrowScreens = ['users_page_arrow-sales-reps', 'll_location_page_arrow-locations', 'll_inventory_page_arrow-inventory'];

  if ( arrowScreens.includes( site_info.screen ) ) {
    let arrowSyncPage = 1;
    let doingAjax = false;
    const placeHolderBtn = document.getElementById( 'arrow-admin-sync-placeholder' );
    const arrowSyncBtn = document.getElementById( 'arrow-admin-sync' );

    const placeHolderPurgeBtn = document.getElementById( 'arrow-admin-purge-placeholder' );
    const arrowSyncPurgeBtn = document.getElementById( 'arrow-admin-purge' );

    document.addEventListener( 'click', ( event ) => {
      console.log( 'Sync Clicked.' );
      if ( !event.target.matches( '#arrow-admin-sync' ) ) return;

      event.target.classList.add( 'hidden' );
      placeHolderBtn.classList.remove( 'hidden' );

      arrowAdminSync( arrowSyncPage );
    } );

    document.addEventListener( 'click', ( event ) => {
      if ( !event.target.matches( '#arrow-admin-purge' ) ) return;

      event.target.classList.add( 'hidden' );
      placeHolderPurgeBtn.classList.remove( 'hidden' );

      arrowAdminSync( arrowSyncPage, true );
    } );

    const arrowAdminSync = function( page, purge, user ) {
      let url;
      if ( site_info.screen === 'users_page_arrow-sales-reps' ) {
        if ( purge == true ) {
          url = 'employee/purge';
        } else {
          url = 'employee/sync';
        }
      } else if ( site_info.screen === 'll_location_page_arrow-locations' ) {
        if ( purge == true ) {
          url = 'location/purge';
        } else {
          url = 'location/sync';
        }
      } else if ( site_info.screen === 'll_inventory_page_arrow-inventory' ) {
        if ( purge == true ) {
          url = 'inventory/purge';
        } else {
          url = 'inventory/sync';
        }
      }

      if ( !url ) return;

      $.ajax( {
        type: 'GET',
        url: site_info.wpApiSettings.ll + url,
        data: {
          page: arrowSyncPage,
          INIT: null,
          PREFLIGHT: null,
          REQUESTED: user,
          MODE: null,
        },
        beforeSend: function( xhr ) {
          xhr.setRequestHeader( 'X-WP-Nonce', site_info.wpApiSettings.nonce );
          xhr.setRequestHeader( 'X-Requested-With', 'XMLHttpRequest' );
          doingAjax = true;
          arrowSyncPage = arrowSyncPage + 1;
        },
        success: function( data ) {
          if ( arrowSyncPage <= data.totalPages ) {
            arrowAdminSync( arrowSyncPage );
          } else {
            arrowSyncPage = 1;
            if ( purge == true ) {
              placeHolderPurgeBtn.classList.add( 'hidden' );
              arrowSyncPurgeBtn.classList.remove( 'hidden' );
            } else {
              placeHolderBtn.classList.add( 'hidden' );
              arrowSyncBtn.classList.remove( 'hidden' );
            }
            document.getElementById( 'arrow-sync-count' ).innerText = data.totalPosts;
            if ( site_info.screen === 'users_page_arrow-sales-reps' ) {
              document.getElementById( 'arrow-sync-count-all' ).innerText = data.totalPosts;
              document.getElementById( 'arrow-sync-count-sales-reps' ).innerText = data.totalSalesReps;
              document.getElementById( 'arrow-sync-count-buyers' ).innerText = data.totalBuyers;
              document.getElementById( 'arrow-sync-count-finance-managers' ).innerText = data.totalManagers;
            }
          }
        },
        complete: function( jqXHR, status ) {
          doingAjax = false;
        },
      } );
    };
  }
} )( jQuery );
