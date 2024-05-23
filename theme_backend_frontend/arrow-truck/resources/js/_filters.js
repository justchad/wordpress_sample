( function( app ) {
  const COMPONENT = {
    type: 'grid-view',
    promotions: [],
    getfilterdata: [],
    getsortdata: [],
    ignoredFilters: [
      'invmilag_s',
      'invmilag_e',
      'invprice_s',
      'invprice_e',
      'sort_factor',
      'sort_order',
    ],
    currentActiveFilters: [],
    debounce: function( callback, wait ) {
      let timeout;
      return ( ...args ) => {
          clearTimeout( timeout );
          timeout = setTimeout( function() {
           callback.apply( this, args );
          }, wait );
      };
    },
    urlVariables: function( value ) {
      const url = window.location.href;
      const arr = url.split( '?' );

      let varArr = false;

      if ( arr.length > 1 && arr[1] !== '' ) {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams( queryString );
        varArr = {
          [value]: urlParams.get( value ),
        };
      } else {
        varArr = false;
      }

      return varArr;
    },
    updatesearchheader: function( value, filters ) {
      const _this = this;

      const domHeaderMake = document.querySelector( '.search-inventory-page-header-text-make' );
      const domHeaderModel = document.querySelector( '.search-inventory-page-header-text-model' );
      const domHeaderIndustry = document.querySelector( '.search-inventory-page-header-text-industry' );
      const domHeaderType = document.querySelector( '.search-inventory-page-header-text-type' );
      const domHeaderLocation = document.querySelector( '.search-inventory-page-header-text-location' );
      const domHeaderTrailer = document.querySelector( '.search-inventory-page-header-text-truck' );

      let domHeaderMakeText = '';
      let domHeaderModelText = '';
      let domHeaderIndustryText = '';
      let domHeaderTypeText = '';
      let domHeaderLocationText = '';
      let domHeaderTrailerText = '';
      let checkMake = filters.hasOwnProperty( 'invmake' );
      let checkModel = filters.hasOwnProperty( 'invmodl' );
      let checkIndustry = filters.hasOwnProperty( 'industry' );
      let checkType = filters.hasOwnProperty( 'trucktype' );
      let checkLocation = filters.hasOwnProperty( 'location' );
      const checkTrailer = filters.hasOwnProperty( 'trailer' );

      if ( filters.length != 0 ) {
        if ( checkTrailer === true ) {
          if ( filters.trailer != undefined ) {
            domHeaderTrailerText = filters.trailer.replace( 'All ', '' );
            domHeaderTrailer.innerHTML = domHeaderTrailerText;

            checkMake = false;
            domHeaderMake.innerHTML = '';

            checkModel = false;
            domHeaderModel.innerHTML = '';

            checkIndustry = false;
            domHeaderIndustry.innerHTML = '';

            checkType = false;
            domHeaderType.innerHTML = '';

            checkLocation = false;
            domHeaderLocation.innerHTML = '';
          } else {
            domHeaderTrailer.innerHTML = 'trucks';
          }
        } else {
          domHeaderTrailer.innerHTML = 'trucks';
        }

        if ( checkMake === true ) {
          if ( filters.invmake != undefined ) {
            domHeaderMakeText = filters.invmake.toLowerCase();
            domHeaderMake.innerHTML = domHeaderMakeText + ' ';
          } else {
            domHeaderMake.innerHTML = '';
          }
        } else {
          domHeaderMake.innerHTML = '';
        }

        if ( checkModel === true ) {
          if ( filters.invmodl != undefined ) {
            domHeaderModelText = filters.invmodl.toLowerCase();
            domHeaderModel.innerHTML = '> ' + domHeaderModelText + ' ';
          } else {
            domHeaderModel.innerHTML = '';
          }
        } else {
          domHeaderModel.innerHTML = '';
        }

        if ( checkIndustry === true ) {
          if ( filters.industry != undefined ) {
            domHeaderIndustryText = filters.industry.toLowerCase();
            domHeaderIndustry.innerHTML = '> ' + domHeaderIndustryText + ' ';
          } else {
            domHeaderIndustry.innerHTML = '';
          }
        } else {
          domHeaderIndustry.innerHTML = '';
        }

        if ( checkType === true ) {
          if ( filters.trucktype != undefined ) {
            domHeaderTypeText = filters.trucktype.toLowerCase();
            domHeaderType.innerHTML = '> ' + domHeaderTypeText + ' ';
          } else {
            domHeaderType.innerHTML = '';
          }
        } else {
          domHeaderType.innerHTML = '';
        }

        if ( checkLocation === true ) {
          if ( filters.location != undefined ) {
            domHeaderLocationText = filters.location;
            domHeaderLocation.innerHTML = 'in ' + domHeaderLocationText + ' ';
          } else {
            domHeaderLocation.innerHTML = '';
          }
        } else {
          domHeaderLocation.innerHTML = '';
        }
      } else {
        domHeaderMake.innerHTML = '';
        domHeaderModel.innerHTML = '';
        domHeaderIndustry.innerHTML = '';
        domHeaderType.innerHTML = '';
        domHeaderLocation.innerHTML = '';
      }

      return false;
    },
    updateTextVariables: function( status ) {
      const sortType = document.getElementById( 'sort_factor' ).value.split( '&' );
      const sortDir = document.getElementById( 'sort_order' ).value.split( '&' );

      document.getElementById( 'la-minimum-price-text' ).innerText = '$' + parseInt( document.getElementById( 'v_invprice_s' ).value ).toLocaleString();
      document.getElementById( 'la-maximum-price-text' ).innerText = '$' + parseInt( document.getElementById( 'v_invprice_e' ).value ).toLocaleString();
      document.getElementById( 'la-minimum-miles-text' ).innerText = parseInt( document.getElementById( 'v_invmilag_s' ).value ).toLocaleString();
      document.getElementById( 'la-maximum-miles-text' ).innerText = parseInt( document.getElementById( 'v_invmilag_e' ).value ).toLocaleString();
      document.getElementById( 'la-sort-type-text' ).innerText = sortType[0];
      document.getElementById( 'la-sort-direction-text' ).innerText = sortDir[0];
    },
    clickToCopySearch: function( params ) {
      const path = window.location.protocol + '//' + window.location.hostname + window.location.pathname;
      const url = path + '?' + $.param( params );
      const copyButton = document.getElementById( 'search_result_share_button' );
      copyButton.dataset.url = url;
    },
    init: function() {
      const _this = this;
      const filters = {};
      const sorts = [];
      let count = 0;
      const docRoot = document.querySelector( 'body' );
      const filterCount = document.querySelector( '#filter-count .count' );
      const currentFilters = document.querySelector( '.current-filters' );
      const resultsCount = document.querySelector( '.results-total' );
      const paginationText = document.querySelector( '#pagination-text' );
      const searchQueryInput = document.querySelector( '.searchquery input' );
      let page = 1;
      let pages = 1;

      const copyToClipboard = ( function initClipboardText() {
        const textarea = document.createElement( 'textarea' );

        textarea.style.cssText = 'position: absolute; left: -99999em';

        textarea.setAttribute( 'readonly', true );

        document.body.appendChild( textarea );

        return function setClipboardText( text ) {
          textarea.value = text;

          const selected = document.getSelection().rangeCount > 0 ?
            document.getSelection().getRangeAt( 0 ) : false;

          if ( navigator.userAgent.match( /ipad|ipod|iphone/i ) ) {
            const editable = textarea.contentEditable;
            textarea.contentEditable = true;
            const range = document.createRange();
            range.selectNodeContents( textarea );
            const sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange( range );
            textarea.setSelectionRange( 0, 999999 );
            textarea.contentEditable = editable;
          } else {
            textarea.select();
          }

          try {
            const result = document.execCommand( 'copy' );

            // Restore previous selection.
            if ( selected ) {
              document.getSelection().removeAllRanges();
              document.getSelection().addRange( selected );
            }

            return result;
          } catch ( err ) {
            console.error( err );
            return false;
          }
        };
      } )();


      const copyButton = document.getElementById( 'search_result_share_button' );
      copyButton.addEventListener( 'click', ( e ) => {
        e.preventDefault;

        const toClipString = e.target.dataset.url;

        copyToClipboard( toClipString );
        $( '.search-result-share-button-wrapper' ).toggleClass( 'hidden-important' );
        $( '.copy-button-confirmation' ).toggleClass( 'hidden-important' );

        setTimeout( function() {
          $( '.search-result-share-button-wrapper' ).toggleClass( 'hidden-important' );
          $( '.copy-button-confirmation' ).toggleClass( 'hidden-important' );
        }, 2000 );
      } );


      const checkForMake = _this.urlVariables( 'invmake' );

      if ( checkForMake != null ) {
        const currentMake = 'invmake|' + checkForMake['invmake'];


        const assocations = document.querySelectorAll( `[data-association^="${currentMake}"]` );

        if ( assocations ) {
          assocations.forEach( ( item, index ) => {
            if ( item.dataset.association === currentMake ) {
              item.classList.remove( 'hidden' );
            } else {
              item.classList.add( 'hidden' );
            }

            item.querySelector( 'input' ).checked = false;
          } );
        }


        // console.log( 'make found: ', currentMake );
      } else {
        // console.log( 'make not in get array' );
      }


      const checkForModel = _this.urlVariables( 'invmodl' );

      if ( checkForModel != null ) {
        const currentModel = checkForModel['invmodl'];


        const assocations = document.querySelectorAll( `[name^="invmodl"]` );

        if ( assocations ) {
          assocations.forEach( ( item, index ) => {
            if ( item.value === currentModel + '&' + currentModel ) {
              // console.log( 'yahoo', currentModel );
              item.checked = true;
            } else {
              item.checked = false;
            }

            // item.querySelector( 'input' ).checked = false;
          } );
        }


        // console.log( 'model found: ', currentModel );
      } else {
        // console.log( 'model not in get array' );
      }

      $( '.checkbox-list.autosort' ).each( function() {
          $( this ).html( $( this ).children( 'li' ).sort( function( a, b ) {
              return ( String( $( b ).data( 'sortvalue' ) ) ) < ( String( $( a ).data( 'sortvalue' ) ) ) ? 1 : -1;
          } ) );
      } );

      $( '.button-list.autosort' ).each( function() {
          $( this ).html( $( this ).children( '.lisorter' ).sort( function( a, b ) {
              return ( String( $( b ).data( 'sortvalue' ) ) ) < ( String( $( a ).data( 'sortvalue' ) ) ) ? 1 : -1;
          } ) );
      } );


      document.querySelectorAll( '.filterbuttonsidebar' ).forEach( ( item ) => {
        item.addEventListener( 'click', ( event ) => {
          const target = event.target.dataset.dropfilter;
          document.getElementById( target ).classList.toggle( 'is-open' );
        } );
      } );

      const filterToggle = document.getElementById( 'current-filters-toggle' );
      filterToggle.addEventListener( 'click', ( event ) => {
        console.log( 'Toggle Attempted: ', event.target.dataset.toggledefault );

        const val = event.target.dataset.toggledefault;
        const tOpen = event.target.dataset.toggletextopen;
        const tClose = event.target.dataset.toggletextclosed;
        if ( val == 'show' ) {
          document.getElementById( 'top-o-the-filter' ).classList.add( 'hidden' );
          document.getElementById( 'mid-o-the-filter' ).classList.add( 'hidden' );
          document.getElementById( 'mid-sep-o-the-filter' ).classList.add( 'hidden' );
          // document.getElementById( 'end-o-the-filter' ).classList.add( 'hidden' );
          filterToggle.innerText = tClose;
          event.target.dataset.toggledefault = 'hide';
        } else if ( val == 'hide' ) {
          document.getElementById( 'top-o-the-filter' ).classList.remove( 'hidden' );
          document.getElementById( 'mid-o-the-filter' ).classList.remove( 'hidden' );
          document.getElementById( 'mid-sep-o-the-filter' ).classList.remove( 'hidden' );
          // document.getElementById( 'end-o-the-filter' ).classList.remove( 'hidden' );
          filterToggle.innerText = tOpen;
          event.target.dataset.toggledefault = 'show';
        }
      } );


      const filterbuttontoggleShow = document.getElementById( 'show-mobile-filter-button' );
      const filterbuttontoggleHide = document.getElementById( 'hide-mobile-filter-button' );

      filterbuttontoggleShow.addEventListener( 'click', ( event ) => {
        document.getElementById( 'hide-mobile-filter-button' ).classList.toggle( 'hidden' );
        document.getElementById( 'show-mobile-filter-button' ).classList.toggle( 'hidden' );
        document.getElementById( 'search-inventory-form-1' ).classList.toggle( 'hidden' );
        document.getElementById( 'search-inventory-form-2' ).classList.toggle( 'hidden' );
      } );
      filterbuttontoggleHide.addEventListener( 'click', ( event ) => {
        document.getElementById( 'hide-mobile-filter-button' ).classList.toggle( 'hidden' );
        document.getElementById( 'show-mobile-filter-button' ).classList.toggle( 'hidden' );
        document.getElementById( 'search-inventory-form-1' ).classList.toggle( 'hidden' );
        document.getElementById( 'search-inventory-form-2' ).classList.toggle( 'hidden' );
      } );


      filterSubmit();
      _this.updateTextVariables( true );

      function closeSort() {
        $( '#sort-dropdown' ).removeClass( 'is-open' );
        _this.updateTextVariables( true );
        filterSubmit();
      }

      function clearActiveSort() {
        sortTarget1.classList.remove( 'active-sort' );
        sortTarget2.classList.remove( 'active-sort' );
        sortTarget3.classList.remove( 'active-sort' );
        sortTarget4.classList.remove( 'active-sort' );
        sortTarget5.classList.remove( 'active-sort' );
        sortTarget6.classList.remove( 'active-sort' );
        sortTarget7.classList.remove( 'active-sort' );
        sortTarget8.classList.remove( 'active-sort' );
      }

      function setSortValue( factor, order, label ) {
        let orderLongValue = 'Descending';
        if ( order == 'DESC' ) {
          orderLongValue = 'Descending';
        } else if ( order == 'ASC' ) {
          orderLongValue = 'Ascending';
        }
        const factorString = factor + '&' + 'Sort: ' + label;
        const orderString = order + '&' + 'Sort: ' + orderLongValue;

        // console.log( 'FactorString: ', factorString );
        // console.log( 'OrderString: ', orderString );

        document.getElementById( 'sort_factor' ).value = factorString;
        document.getElementById( 'sort_order' ).value = orderString;
        closeSort();
      }

      const sortTarget1 = document.getElementById( 'sort-location-asc' );
      const sortTarget2 = document.getElementById( 'sort-location-desc' );
      const sortTarget3 = document.getElementById( 'sort-year-asc' );
      const sortTarget4 = document.getElementById( 'sort-year-desc' );
      const sortTarget5 = document.getElementById( 'sort-price-asc' );
      const sortTarget6 = document.getElementById( 'sort-price-desc' );
      const sortTarget7 = document.getElementById( 'sort-mileage-asc' );
      const sortTarget8 = document.getElementById( 'sort-mileage-desc' );

      let sortSet1a = false;
      let sortSet1b = false;
      let sortSet2a = false;
      let sortSet2b = false;
      let sortSet3a = false;
      let sortSet3b = false;
      let sortSet4a = false;
      let sortSet4b = false;

      sortTarget7.addEventListener( 'click', ( event ) => {
        event.preventDefault();
        clearActiveSort();

        const hasClassSort = event.target.classList.contains( 'active-sort' );
        if ( hasClassSort === false ) {
          let canUpdate = false;
          const checkValue = sortTarget8.dataset.sortactive;
          // before we turn this on we need to check with the group.
          // if the other group member value is active, de-activate it.
          setSortValue( 'MILEAGE', 'ASC', 'Mileage' );
          if ( checkValue == 'true' ) {
            document.getElementById( 'mileage_desc' ).value = 0;
            sortTarget8.classList.remove( 'active-sort' );
            sortTarget8.dataset.sortactive = false;
            canUpdate = true;
          } else {
            canUpdate = true;
          }
          if ( canUpdate === true ) {
            sortSet4a = true;
            document.getElementById( 'mileage_asc' ).value = 1;
            sortTarget7.classList.add( 'active-sort' );
            sortTarget7.dataset.sortactive = true;
            // closeSort();
            return;
          }
        }
        if ( hasClassSort === true ) {
          sortSet4a = false;
          document.getElementById( 'mileage_asc' ).value = 0;
          sortTarget7.classList.remove( 'active-sort' );
          sortTarget7.dataset.sortactive = false;
          // closeSort();
          return;
        }
      } );

      sortTarget8.addEventListener( 'click', ( event ) => {
        event.preventDefault();
        clearActiveSort();

        const hasClassSort = event.target.classList.contains( 'active-sort' );
        if ( hasClassSort === false ) {
          let canUpdate = false;
          const checkValue = sortTarget7.dataset.sortactive;
          // before we turn this on we need to check with the group.
          // if the other group member value is active, de-activate it.
          setSortValue( 'MILEAGE', 'DESC', 'Mileage' );
          if ( checkValue == 'true' ) {
            document.getElementById( 'mileage_asc' ).value = 0;
            sortTarget7.classList.remove( 'active-sort' );
            sortTarget7.dataset.sortactive = false;
            canUpdate = true;
          } else {
            canUpdate = true;
          }
          if ( canUpdate === true ) {
            sortSet4b = true;
            document.getElementById( 'mileage_desc' ).value = 1;
            sortTarget8.classList.add( 'active-sort' );
            sortTarget8.dataset.sortactive = true;
            // closeSort();
            return;
          }
        }
        if ( hasClassSort === true ) {
          sortSet4b = false;
          document.getElementById( 'mileage_desc' ).value = 0;
          sortTarget2.classList.remove( 'active-sort' );
          sortTarget2.dataset.sortactive = false;
          // closeSort();
          return;
        }
      } );


      sortTarget1.addEventListener( 'click', ( event ) => {
        event.preventDefault();
        clearActiveSort();
        const hasClassSort = event.target.classList.contains( 'active-sort' );
        if ( hasClassSort === false ) {
          let canUpdate = false;
          const checkValue = sortTarget2.dataset.sortactive;
          // before we turn this on we need to check with the group.
          // if the other group member value is active, de-activate it.
          setSortValue( 'LOCATION', 'ASC', 'Location' );
          if ( checkValue == 'true' ) {
            document.getElementById( 'location_desc' ).value = 0;
            sortTarget2.classList.remove( 'active-sort' );
            sortTarget2.dataset.sortactive = false;
            canUpdate = true;
          } else {
            canUpdate = true;
          }
          if ( canUpdate === true ) {
            sortSet1a = true;
            document.getElementById( 'location_asc' ).value = 1;
            sortTarget1.classList.add( 'active-sort' );
            sortTarget1.dataset.sortactive = true;
            // closeSort();
            return;
          }
        }
        if ( hasClassSort === true ) {
          sortSet1a = false;
          document.getElementById( 'location_asc' ).value = 0;
          sortTarget1.classList.remove( 'active-sort' );
          sortTarget1.dataset.sortactive = false;
          // closeSort();
          return;
        }
      } );

      sortTarget2.addEventListener( 'click', ( event ) => {
        event.preventDefault();
        clearActiveSort();
        const hasClassSort = event.target.classList.contains( 'active-sort' );
        if ( hasClassSort === false ) {
          let canUpdate = false;
          const checkValue = sortTarget1.dataset.sortactive;
          // before we turn this on we need to check with the group.
          // if the other group member value is active, de-activate it.
          setSortValue( 'LOCATION', 'DESC', 'Location' );
          if ( checkValue == 'true' ) {
            document.getElementById( 'location_asc' ).value = 0;
            sortTarget1.classList.remove( 'active-sort' );
            sortTarget1.dataset.sortactive = false;
            canUpdate = true;
          } else {
            canUpdate = true;
          }
          if ( canUpdate === true ) {
            sortSet1b = true;
            document.getElementById( 'location_desc' ).value = 1;
            sortTarget2.classList.add( 'active-sort' );
            sortTarget2.dataset.sortactive = true;
            // closeSort();
            return;
          }
        }
        if ( hasClassSort === true ) {
          sortSet1b = false;
          document.getElementById( 'location_desc' ).value = 0;
          sortTarget2.classList.remove( 'active-sort' );
          sortTarget2.dataset.sortactive = false;
          // closeSort();
          return;
        }
      } );

      sortTarget3.addEventListener( 'click', ( event ) => {
        event.preventDefault();
        clearActiveSort();
        const hasClassSort = event.target.classList.contains( 'active-sort' );
        if ( hasClassSort === false ) {
          let canUpdate = false;
          const checkValue = sortTarget4.dataset.sortactive;
          // before we turn this on we need to check with the group.
          // if the other group member value is active, de-activate it.
          setSortValue( 'YEAR', 'ASC', 'Year' );
          if ( checkValue == 'true' ) {
            document.getElementById( 'year_desc' ).value = 0;
            sortTarget4.classList.remove( 'active-sort' );
            sortTarget4.dataset.sortactive = false;
            canUpdate = true;
          } else {
            canUpdate = true;
          }
          if ( canUpdate === true ) {
            sortSet2a = true;
            document.getElementById( 'year_asc' ).value = 1;
            sortTarget3.classList.add( 'active-sort' );
            sortTarget3.dataset.sortactive = true;
            // closeSort();
            return;
          }
        }
        if ( hasClassSort === true ) {
          sortSet2a = false;
          document.getElementById( 'year_asc' ).value = 0;
          sortTarget3.classList.remove( 'active-sort' );
          sortTarget3.dataset.sortactive = false;
          // closeSort();
          return;
        }
      } );

      sortTarget4.addEventListener( 'click', ( event ) => {
        event.preventDefault();
        clearActiveSort();
        const hasClassSort = event.target.classList.contains( 'active-sort' );
        if ( hasClassSort === false ) {
          let canUpdate = false;
          const checkValue = sortTarget3.dataset.sortactive;
          // before we turn this on we need to check with the group.
          // if the other group member value is active, de-activate it.
          setSortValue( 'YEAR', 'DESC', 'Year' );
          if ( checkValue == 'true' ) {
            document.getElementById( 'year_asc' ).value = 0;
            sortTarget3.classList.remove( 'active-sort' );
            sortTarget3.dataset.sortactive = false;
            canUpdate = true;
          } else {
            canUpdate = true;
          }
          if ( canUpdate === true ) {
            sortSet2b = true;
            document.getElementById( 'year_desc' ).value = 1;
            sortTarget4.classList.add( 'active-sort' );
            sortTarget4.dataset.sortactive = true;
            // closeSort();
            return;
          }
        }
        if ( hasClassSort === true ) {
          sortSet2b = false;
          document.getElementById( 'year_desc' ).value = 0;
          sortTarget4.classList.remove( 'active-sort' );
          sortTarget4.dataset.sortactive = false;
          // closeSort();
          return;
        }
      } );

      sortTarget5.addEventListener( 'click', ( event ) => {
        event.preventDefault();
        clearActiveSort();
        const hasClassSort = event.target.classList.contains( 'active-sort' );
        if ( hasClassSort === false ) {
          let canUpdate = false;
          const checkValue = sortTarget6.dataset.sortactive;
          // before we turn this on we need to check with the group.
          // if the other group member value is active, de-activate it.
          setSortValue( 'PRICE', 'ASC', 'Price' );
          if ( checkValue == 'true' ) {
            document.getElementById( 'price_desc' ).value = 0;
            sortTarget6.classList.remove( 'active-sort' );
            sortTarget6.dataset.sortactive = false;
            canUpdate = true;
          } else {
            canUpdate = true;
          }
          if ( canUpdate === true ) {
            sortSet3a = true;
            document.getElementById( 'price_asc' ).value = 1;
            sortTarget5.classList.add( 'active-sort' );
            sortTarget5.dataset.sortactive = true;
            // closeSort();
            return;
          }
        }
        if ( hasClassSort === true ) {
          sortSet3a = false;
          document.getElementById( 'price_asc' ).value = 0;
          sortTarget5.classList.remove( 'active-sort' );
          sortTarget5.dataset.sortactive = false;
          // closeSort();
          return;
        }
      } );

      sortTarget6.addEventListener( 'click', ( event ) => {
        event.preventDefault();
        clearActiveSort();
        const hasClassSort = event.target.classList.contains( 'active-sort' );
        if ( hasClassSort === false ) {
          let canUpdate = false;
          const checkValue = sortTarget5.dataset.sortactive;
          // before we turn this on we need to check with the group.
          // if the other group member value is active, de-activate it.
          setSortValue( 'PRICE', 'DESC', 'Price' );
          if ( checkValue == 'true' ) {
            document.getElementById( 'price_asc' ).value = 0;
            sortTarget5.classList.remove( 'active-sort' );
            sortTarget5.dataset.sortactive = false;
            canUpdate = true;
          } else {
            canUpdate = true;
          }
          if ( canUpdate === true ) {
            sortSet3b = true;
            document.getElementById( 'price_desc' ).value = 1;
            sortTarget6.classList.add( 'active-sort' );
            sortTarget6.dataset.sortactive = true;
            // closeSort();
            return;
          }
        }
        if ( hasClassSort === true ) {
          sortSet3b = false;
          document.getElementById( 'price_desc' ).value = 0;
          sortTarget6.classList.remove( 'active-sort' );
          sortTarget6.dataset.sortactive = false;
          // closeSort();
          return;
        }
      } );

      document.addEventListener( 'change', ( event ) => {
        if ( !event.target.matches( '.filter-input' ) ) return;

        const value = event.target.value;
        const param = event.target.name;

        const val = value.split( '&' );

        const assocations = document.querySelectorAll( `[data-association^="${param}"]` );

        filters[param] = event.target.dataset.label;

        // this is it right here...Clara

        if ( assocations ) {
          assocations.forEach( ( item, index ) => {
            if ( item.dataset.association === `${param}|${val[0]}` ) {
              item.classList.remove( 'hidden' );
            } else {
              item.classList.add( 'hidden' );
            }

            item.querySelector( 'input' ).checked = false;
          } );
        }

        filterSubmit();
      } );

      function filterSubmit( ) {
        $( '#sort-dropdown' ).removeClass( 'is-open' );
        // ?invmilag_s=1000&invmilag_e=700000&invprice_s=1000&invprice_e=100000&invmake=VOLVO&location=CH&trucktype=1
        const formData = new FormData( document.querySelector( '#inventory-filter' ) );
        const params = {};
        const filters = {};
        _this.getfilterdata = params;

        formData.forEach( ( value, key ) => {
          const keyFlag = 'v_';
          const keyString = key;

          if ( !keyString.includes( keyFlag ) ) {
            const val = value.split( '&' );
            params[key] = val[0];
            filters[key] = val[1];
            // console.log( 'PARSING-PARAMS_____: ', key + ' : ' + val[0] );
            // console.log( 'PARSING-FILTERS_____: ', key + ' : ' + val[1] );
          }
        } );

        _this.clickToCopySearch( params );

        _this.currentActiveFilters = filters;

        Object.keys( filters ).forEach( ( key ) => {
          if ( filters[key] === '' ) {
            delete filters[key];
          }
        } );

        count = Object.values( filters ).filter( ( item ) => {
          return item;
        } ).length;

        const sortData = new FormData( document.querySelector( '#sort-filter ' ) );
        const sorts = {};
        _this.getsortdata = sorts;

        sortData.forEach( ( value, key ) => {
          sorts[key] = value;
        } );

        const allData = {
          params: params,
          sorts: sorts,
        };

        console.log( 'All Data: ', allData );

        // main search ajax call
        $.ajax( {
          type: 'POST',
          url: site_info.wpApiSettings.ll + 'inventory/filter'+window.location.search,
          data: allData,
          beforeSend: function( xhr ) {
            xhr.setRequestHeader( 'X-WP-Nonce', site_info.wpApiSettings.nonce );
            xhr.setRequestHeader( 'X-Requested-With', 'XMLHttpRequest' );
            $( '#spinner-wrap' ).removeClass( 'hidden' );

              $( '#truck-watch' ).addClass( 'hidden' );
            document.querySelector( '#trucks-list-view' ).innerHTML = '';
            // console.log( 'beforeSend: ', xhr );
          },
          success: function( data ) {
            // console.log( 'success: ', data );

            formData.forEach( ( value, key ) => {
              const targ = document.getElementById( 'll_inventory_' + key + '-dropdown' );
              if ( targ ) {
                targ.classList.remove( 'is-open' );
              }
            } );

            if ( data.link ) {
              window.location.href = data.link;
            } else {
              document.querySelector( '#trucks-list-view' ).outerHTML = data.response;
              document.querySelector( '#trucks-list-view' ).classList.add( 'is-active' );
              resultsCount.innerText = data.count;
              pages = data.pages;
              paginationText.innerText = data.results;
              if ( data.count === 0 ) {
                $( '#truck-watch' ).removeClass( 'hidden' );
                searchQueryInput.value = JSON.stringify( params );
              } else {
                $( '#truck-watch' ).addClass( 'hidden' );

                if ( data.cache ) {
                  sessionStorage.setItem( 'arrow_filters', data.cache );
                }

                document.querySelectorAll( '.truck-card-wrapper' ).forEach( ( element ) => {
                  element.classList.remove( 'list-view' );
                  element.classList.add( 'grid-view' );
                } );

                if ( _this.type !== 'grid-view' ) {
                  // document.querySelector( '#trucks-list-view' ).classList.remove( 'is-list' );
                  // document.querySelector( '#trucks-list-view' ).classList.add( 'is-list' );
                } else {
                  // document.querySelector( '#trucks-list-view' ).classList.remove( 'is-list' );
                }

                document.querySelectorAll( '.promo-card' ).forEach( ( element ) => {
                  element.remove();
                } );

                if ( _this.promotions && _this.promotions.length > 0 ) {
                  let offset = 0;
                  document.querySelectorAll( '.truck-card-wrapper' ).forEach( ( element, index ) => {
                    if ( ( index + 1 ) % 10 == 0 && index > 5 ) {
                      if ( !_this.promotions[offset] ) {
                        offset = 0;
                      }

                      $( element ).after( _this.promotionTemplate( _this.promotions[offset] ) );
                      if ( document.querySelector( '#trucks-list-view' ).classList.contains( 'is-list' ) ) {
                        $( element ).after( _this.promotionTemplate( _this.promotions[offset+1] ) );
                        offset = offset + 2;
                      } else {
                        offset++;
                      }
                    }
                  } );
                }
              }
            }
          },
          complete: function( jqXHR, status ) {
            _this.updatesearchheader( params, filters );

            _this.updateTextVariables( true );

            filterCount.innerText = count;
            if ( filters.hasOwnProperty( 'trailer' ) ) {
              _this.updateTextVariables( true );
              currentFilters.innerHTML = `
                ${Object.keys( filters ).map( function( filter ) {
                  if ( filter != 'trailer' ) {
                    return;
                  }
                  if ( !_this.ignoredFilters.includes( filter ) ) {
                    return `
                    ${filters[filter] ? `
                      <div class="button-area">
                        <button class="remove-filter filterfromtrailer" data-param="${filter}">${filters[filter]} <svg class="icon icon-close pointer-events-none"><use xlink:href="#icon-close"></use></svg></button>
                      </div>` : '' }`;
                  }
                } ).join( '' )}`;
            } else {
              _this.updateTextVariables( true );
              currentFilters.innerHTML = `
                ${Object.keys( filters ).map( function( filter ) {
                  if ( !_this.ignoredFilters.includes( filter ) ) {
                    return `
                    ${filters[filter] ? `
                      <div class="button-area">
                        <button class="remove-filter filteraftertrailer" data-param="${filter}">${filters[filter]} <svg class="icon icon-close pointer-events-none"><use xlink:href="#icon-close"></use></svg></button>
                      </div>` : '' }`;
                  }
                } ).join( '' )}`;
            }


            $( '#spinner-wrap' ).addClass( 'hidden' );


            if ( pages > 1 ) {
              $( '#results-pagination' ).removeClass( 'hidden' );
            } else {
              $( '#results-pagination' ).addClass( 'hidden' );
            }
          },
        } );
      }


      function sortSubmit() {
        console.log( 'Sort Submit' );
        const formData = new FormData( document.querySelector( '#inventory-filter' ) );
        const params = {};

        _this.getfilterdata = params;

        formData.forEach( ( value, key ) => {
          params[key] = value;
        } );

        count = Object.values( filters ).filter( ( item ) => {
          return item;
        } ).length;

        $.ajax( {
          type: 'POST',
          url: site_info.wpApiSettings.ll + 'inventory/sort'+window.location.search,
          data: params,
          beforeSend: function( xhr ) {
            xhr.setRequestHeader( 'X-WP-Nonce', site_info.wpApiSettings.nonce );
            xhr.setRequestHeader( 'X-Requested-With', 'XMLHttpRequest' );
            $( '#spinner-wrap' ).removeClass( 'hidden' );

              $( '#truck-watch' ).addClass( 'hidden' );
            document.querySelector( '#trucks-list-view' ).innerHTML = '';
            // console.log( 'beforeSend: ', xhr );
          },
          success: function( data ) {
            if ( data.link ) {
              window.location.href = data.link;
            } else {
              document.querySelector( '#trucks-list-view' ).outerHTML = data.response;
              document.querySelector( '#trucks-list-view' ).classList.add( 'is-active' );
              resultsCount.innerText = data.count;
              pages = data.pages;
              paginationText.innerText = data.results;
              if ( data.count === 0 ) {
                $( '#truck-watch' ).removeClass( 'hidden' );
                searchQueryInput.value = JSON.stringify( params );
              } else {
                $( '#truck-watch' ).addClass( 'hidden' );

                if ( data.cache ) {
                  sessionStorage.setItem( 'arrow_filters', data.cache );
                }

                document.querySelectorAll( '.truck-card-wrapper' ).forEach( ( element ) => {
                  element.classList.remove( 'list-view' );
                  element.classList.add( 'grid-view' );
                } );

                if ( _this.type !== 'grid-view' ) {
                  // document.querySelector( '#trucks-list-view' ).classList.remove( 'is-list' );
                  // document.querySelector( '#trucks-list-view' ).classList.add( 'is-list' );
                } else {
                  // document.querySelector( '#trucks-list-view' ).classList.remove( 'is-list' );
                }

                document.querySelectorAll( '.promo-card' ).forEach( ( element ) => {
                  element.remove();
                } );

                if ( _this.promotions && _this.promotions.length > 0 ) {
                  let offset = 0;
                  document.querySelectorAll( '.truck-card-wrapper' ).forEach( ( element, index ) => {
                    if ( ( index + 1 ) % 10 == 0 && index > 5 ) {
                      if ( !_this.promotions[offset] ) {
                        offset = 0;
                      }

                      $( element ).after( _this.promotionTemplate( _this.promotions[offset] ) );
                      if ( document.querySelector( '#trucks-list-view' ).classList.contains( 'is-list' ) ) {
                        $( element ).after( _this.promotionTemplate( _this.promotions[offset+1] ) );
                        offset = offset + 2;
                      } else {
                        offset++;
                      }
                    }
                  } );
                }
              }
            }
          },
          complete: function( jqXHR, status ) {
            _this.updatesearchheader( params, filters );

            filterCount.innerText = count;
            currentFilters.innerHTML = `
              ${Object.keys( filters ).map( function( filter ) {
                return `
                ${filters[filter] ? `
                  <div class="button-area">
                    <button class="remove-filter" data-param="${filter}">${filters[filter]} <svg class="icon icon-close pointer-events-none"><use xlink:href="#icon-close"></use></svg></button>
                  </div>` : '' }`;
              } ).join( '' )}`;

            $( '#spinner-wrap' ).addClass( 'hidden' );


            if ( pages > 1 ) {
              $( '#results-pagination' ).removeClass( 'hidden' );
            } else {
              $( '#results-pagination' ).addClass( 'hidden' );
            }
          },
        } );
      }

      function searchSubmit() {
        console.log( 'Search Submit' );
        const formData = new FormData( document.querySelector( '#inventory-filter-search' ) );
        const params = {};
        formData.forEach( ( value, key ) => {
          params[key] = value;
        } );

        count = Object.values( filters ).filter( ( item ) => {
          return item;
        } ).length;

        $.ajax( {
          type: 'POST',
          url: site_info.wpApiSettings.ll + 'inventory/filter'+window.location.search,
          data: params,
          beforeSend: function( xhr ) {
            xhr.setRequestHeader( 'X-WP-Nonce', site_info.wpApiSettings.nonce );
            xhr.setRequestHeader( 'X-Requested-With', 'XMLHttpRequest' );
              $( '#spinner-wrap' ).removeClass( 'hidden' );

              $( '#truck-watch' ).addClass( 'hidden' );

            document.querySelector( '#trucks-list-view' ).innerHTML = '';
          },
          success: function( data ) {
            // kevin
            if ( data.link ) {
              window.location.href = data.link;
            } else {
              document.querySelector( '#trucks-list-view' ).outerHTML = data.response;
              document.querySelector( '#trucks-list-view' ).classList.add( 'is-active' );
              resultsCount.innerText = data.count;
              pages = data.pages;
              paginationText.innerText = data.results;
              if ( data.count === 0 ) {
                $( '#truck-watch' ).removeClass( 'hidden' );
                searchQueryInput.value = JSON.stringify( params );
              } else {
                $( '#truck-watch' ).addClass( 'hidden' );

                if ( data.cache ) {
                  sessionStorage.setItem( 'arrow_filters', data.cache );
                }

                document.querySelectorAll( '.truck-card-wrapper' ).forEach( ( element ) => {
                  // element.classList.remove( 'list-view', 'grid-view' );
                  // element.classList.add( _this.type );
                  element.classList.remove( 'list-view' );
                  element.classList.add( 'grid-view' );
                } );

                if ( _this.type !== 'grid-view' ) {
                  // alert( 'something' );
                  // document.querySelector( '#trucks-list-view' ).classList.remove( 'is-list' );
                  // document.querySelector( '#trucks-list-view' ).classList.add( 'is-list' );
                } else {
                  // alert( 'something2' );
                  // document.querySelector( '#trucks-list-view' ).classList.remove( 'is-list' );
                }

                document.querySelectorAll( '.promo-card' ).forEach( ( element ) => {
                  element.remove();
                } );

                if ( _this.promotions && _this.promotions.length > 0 ) {
                  let offset = 0;
                  document.querySelectorAll( '.truck-card-wrapper' ).forEach( ( element, index ) => {
                    if ( ( index + 1 ) % 10 == 0 && index > 5 ) {
                      if ( !_this.promotions[offset] ) {
                        offset = 0;
                      }

                      $( element ).after( _this.promotionTemplate( _this.promotions[offset] ) );
                      if ( document.querySelector( '#trucks-list-view' ).classList.contains( 'is-list' ) ) {
                        $( element ).after( _this.promotionTemplate( _this.promotions[offset+1] ) );
                        offset = offset + 2;
                      } else {
                        offset++;
                      }
                    }
                  } );
                }
              }
            }
          },
          complete: function( jqXHR, status ) {
            filterCount.innerText = count;
            currentFilters.innerHTML = `
              ${Object.keys( filters ).map( function( filter ) {
                return `
                ${filters[filter] ? `
                  <div class="button-area">
                    <button class="remove-filter" data-param="${filter}">${filters[filter]} <svg class="icon icon-close pointer-events-none"><use xlink:href="#icon-close"></use></svg></button>
                  </div>` : '' }`;
              } ).join( '' )}
            `;

            $( '#spinner-wrap' ).addClass( 'hidden' );

            if ( pages > 1 ) {
              $( '#results-pagination' ).removeClass( 'hidden' );
            } else {
              $( '#results-pagination' ).addClass( 'hidden' );
            }
          },
        } );
      }

      document.addEventListener( 'click', ( event ) => {
        if ( !event.target.matches( '.remove-filter' ) ) return;


        filters[event.target.dataset.param] = null;

        const input = document.querySelector( '[name="' + event.target.dataset.param + '"]:checked' );
        const isnotRange = input.classList.contains( 'range-input' );
        const isnotSort = input.classList.contains( 'sort-input' );
        if ( !isnotSort ) {
          if ( !isnotRange ) {
            input.checked = false;

            const rawValue = input.value;
            const value = rawValue.split( '&' );
            const param = input.name;
            const assocations = document.querySelectorAll( `[data-association^="${param}"]` );
            // filters[param] = event.target.dataset.param;

            if ( assocations ) {
              assocations.forEach( ( item, index ) => {
                if ( item.dataset.association === `${param}|${value[0]}` ) {
                  item.classList.add( 'hidden' );
                }
              } );
            }
          }
        }

        filterSubmit();
      } );

      document.querySelector( '#inventory-filter' ).addEventListener( 'submit', ( event ) => {
        event.preventDefault();
      }, false );

      document.addEventListener( 'change', ( event ) => {
        if ( !event.target.matches( '.range-input' ) ) return;

        filterSubmit();
      } );

      document.addEventListener( 'change', ( event ) => {
        if ( !event.target.matches( '#stock-num' ) ) {
           // console.log( event.target );
        }
      } );

      const stockNum = document.getElementById( 'stock-num-button' );
      stockNum.addEventListener( 'click', function( e ) {
        const input = document.getElementById( 'stock-num' );

        const url = window.origin + '/inventory/' + input.value + '/';
        const http = new XMLHttpRequest();

        http.open( 'HEAD', url, false );
        http.send();
        const result = http.status==200;

        if ( result == true ) {
          window.open( url, '_self' );
        } else {
          const stockAlert = document.getElementById( 'stock-number-alert' );
          stockAlert.innerHTML = `<p>Item with the stock number ${input.value} is not available.</p>`;
          stockAlert.classList.toggle( 'hidden' );
        }

        input.value = '';
      }, false );

      document.addEventListener( 'change', ( event ) => {
        if ( !event.target.matches( '#vehicle-search' ) ) return;

        searchSubmit();
      } );

      const rangeArray = [];


      // slider section dorkus
      if ( document.querySelectorAll( '#inventory-filter .nouislider' ) ) {
        document.querySelectorAll( '#inventory-filter .nouislider' ).forEach( ( item, index ) => {
          const sV = {};
          sV['min'] = parseInt( item.dataset.min );
          sV['max'] = parseInt( item.dataset.max );
          sV['min_default'] = parseInt( item.dataset.mindefault );
          sV['max_default'] = parseInt( item.dataset.maxdefault );
          sV['min_label'] = item.dataset.minlabel;
          sV['max_label'] = item.dataset.maxlabel;
          sV['sliderid'] = item.dataset.sliderid;
          sV['slide'] = item.dataset.slide;
          sV['step'] = parseInt( item.dataset.step );

          // console.log( 'sV: ', sV );

          noUiSlider.create( item, {
            start: [sV['min'], sV['max']],
            connect: true,
            range: {
              'min': sV['min_default'],
              'max': sV['max_default'],
            },
            step: sV['step'],
          } );

          item.noUiSlider.on( 'update', ( values, handle, unencoded, tap, positions, noUiSlider ) => {
            // set the constants
            const slide = {};
            const parentGroup = item.closest( '.range' );

            // build the array of values
            slide['base'] = parentGroup.dataset.parent;
            slide['min_name'] = parentGroup.dataset.minarg;
            slide['max_name'] = parentGroup.dataset.maxarg;
            slide['min_label'] = parentGroup.dataset.minlabel;
            slide['max_label'] = parentGroup.dataset.maxlabel;
            slide['min_view'] = 'v_' + parentGroup.dataset.minarg;
            slide['max_view'] = 'v_' + parentGroup.dataset.maxarg;
            slide['min_text'] = 'text_min_' + parentGroup.dataset.minarg;
            slide['max_text'] = 'text_max_' + parentGroup.dataset.maxarg;
            slide['min'] = parseInt( values[0] );
            slide['max'] = parseInt( values[1] );
            slide['format'] = parentGroup.dataset.format;

            // set the input fields on change
            document.getElementById( slide['min_name'] ).value = slide['min'] + '&' + slide['min_label'];
            document.getElementById( slide['max_name'] ).value = slide['max'] + '&' + slide['max_label'];
            document.getElementById( slide['min_view'] ).value = slide['min'];
            document.getElementById( slide['max_view'] ).value = slide['max'];

            // set the display text on change in seperate formats
            if ( slide['format'] == 'currency' ) {
              document.getElementById( slide['min_text'] ).textContent = '$' + slide['min'].toLocaleString();
              document.getElementById( slide['max_text'] ).textContent = '$' + slide['max'].toLocaleString();
              parentGroup.dataset.minlabel = 'MN: $' + slide['min'].toLocaleString();
              parentGroup.dataset.maxlabel = 'MX: $' + slide['max'].toLocaleString();
            } else if ( slide['format'] == 'thousands' ) {
              document.getElementById( slide['min_text'] ).textContent = slide['min'].toLocaleString();
              document.getElementById( slide['max_text'] ).textContent = slide['max'].toLocaleString();
              parentGroup.dataset.minlabel = 'MN: ' + slide['min'].toLocaleString() + ' Miles';
              parentGroup.dataset.maxlabel = 'MX: ' + slide['max'].toLocaleString() + ' Miles';
            } else {
              document.getElementById( slide['min_text'] ).textContent = slide['min'];
              document.getElementById( slide['max_text'] ).textContent = slide['max'];
              parentGroup.dataset.minlabel = slide['min'];
              parentGroup.dataset.maxlabel = slide['max'];
            }

            // set the parent values to new data values
            parentGroup.dataset.min = values[0];
            parentGroup.dataset.max = values[1];

            // console.log( 'Slide Data: ', slide );
          } );

          item.noUiSlider.on( 'end', ( values, handle, unencoded, tap, positions, noUiSlider ) => {
            filterSubmit();
          } );
        } );
      }

      // dorkus


      document.addEventListener( 'keyup', _this.debounce( ( event ) => {
        console.log( 'Event Target: ', event );
          if ( event.target.classList.contains( 'range-input' ) ) {
            console.log( 'DataSet: ', event.target.dataset );
            const vparent = document.getElementById( 'range_' + event.target.dataset.slideparent );
            const hparentMin = document.getElementById( event.target.dataset.minarg );
            const hparentMax = document.getElementById( event.target.dataset.maxarg );
            const tparentMin = document.getElementById( 'text_min_' + event.target.dataset.minarg );
            const tparentMax = document.getElementById( 'text_max_' + event.target.dataset.maxarg );
            const targetSlide = document.getElementById( event.target.dataset.slide );
            const rangeType = event.target.dataset.rangetype;
            const newVal = parseInt( event.target.value );

            if ( rangeType == 'min' ) {
              vparent.dataset.min = newVal;
              if ( event.target.dataset.format == 'currency' ) {
                vparent.dataset.minlabel = 'MN: $' + newVal.toLocaleString();
                tparentMin.textContent = '$' + newVal.toLocaleString();
              } else if ( event.target.dataset.format == 'thousands' ) {
                vparent.dataset.minlabel = 'MN: ' + newVal.toLocaleString() + ' Miles';
                tparentMin.textContent = newVal.toLocaleString();
              } else {
                vparent.dataset.minlabel = newVal;
                tparentMin.textContent = newVal.toLocaleString();
              }

              // update the hidden input
              hparentMin.value = newVal + '&' + hparentMin.dataset.label;
              hparentMin.dataset.label = vparent.dataset.minlabel;
              targetSlide.noUiSlider.setHandle( 0, parseInt( newVal ) );
            }

            if ( rangeType == 'max' ) {
              vparent.dataset.max = newVal;
              if ( event.target.dataset.format == 'currency' ) {
                vparent.dataset.maxlabel = 'MN: $' + newVal.toLocaleString();
                tparentMax.textContent = '$' + newVal.toLocaleString();
              } else if ( event.target.dataset.format == 'thousands' ) {
                vparent.dataset.maxlabel = 'MX: ' + newVal.toLocaleString() + ' Miles';
                tparentMax.textContent = newVal.toLocaleString();
              } else {
                vparent.dataset.maxlabel = newVal;
                tparentMax.textContent = newVal.toLocaleString();
              }

              // update the hidden input
              hparentMax.value = newVal + '&' + hparentMax.dataset.label;
              hparentMax.dataset.label = vparent.dataset.maxlabel;
              targetSlide.noUiSlider.setHandle( 1, parseInt( newVal ) );
            }
          }
      }, 1000 ) );


      document.addEventListener( 'click', ( event ) => {
        if ( !event.target.matches( '.filter-pagination' ) ) return;

        event.preventDefault();

        if ( event.target.matches( '.filters-start' ) ) {
          page = 1;
        }

        if ( event.target.matches( '.filters-end' ) ) {
          page = pages;
        }

        if ( event.target.matches( '.filters-back' ) ) {
          page = page - 1;
        }

        if ( event.target.matches( '.filters-next' ) ) {
          page = page + 1;
        }

        $.ajax( {
          type: 'POST',
          url: site_info.wpApiSettings.ll + 'inventory/paginate',
          data: {
            cache: sessionStorage.getItem( 'arrow_filters' ),
            page: page,
          },
          beforeSend: function( xhr ) {
            xhr.setRequestHeader( 'X-WP-Nonce', site_info.wpApiSettings.nonce );
            xhr.setRequestHeader( 'X-Requested-With', 'XMLHttpRequest' );
          },
          success: function( data ) {
            document.querySelector( '#trucks-list-view' ).outerHTML = data.response;
            paginationText.innerText = data.results;
          },
          complete: function( jqXHR, status ) {
            $( '#sort-dropdown' ).removeClass( 'is-open' );
            document.querySelector( '#trucks-list-view' ).classList.remove( 'is-list' );
            document.documentElement.scrollTop = 0;
            document.getElementsByTagName( 'body' ).scrollTop = 0;

            if ( page >= pages ) {
              $( '.filters-next, .filters-end' ).addClass( 'hidden' );
            } else {
              $( '.filters-next, .filters-end' ).removeClass( 'hidden' );
            }

            if ( page == 1 ) {
              $( '.filters-start, .filters-back' ).addClass( 'hidden' );
            } else {
              $( '.filters-start, .filters-back' ).removeClass( 'hidden' );
            }

            $( '[data-view="'+_this.type+'"]' ).trigger( 'click' );
          },
        } );
      } );

      document.querySelector( '.view-button[data-view="grid-view"]' ).classList.add( 'is-active' );

      document.addEventListener( 'click', ( event ) => {
        if ( !event.target.matches( '.view-button' ) ) return;

        _this.type = event.target.dataset.view;

        document.querySelector( '.view-button.is-active' ).classList.remove( 'is-active' );
        document.querySelector( '#trucks-list-view' ).classList.remove( 'is-list' );
        event.target.classList.add( 'is-active' );

        document.querySelectorAll( '.truck-card-wrapper' ).forEach( ( element ) => {
          element.classList.remove( 'list-view', 'grid-view' );
          element.classList.add( _this.type );
        } );

        if ( _this.type === 'list-view' ) {
          document.querySelector( '#trucks-list-view' ).classList.add( 'is-list' );
        }
      } );
    },

    finalize: function() {
      const _this = this;

      $.ajax( {
        type: 'GET',
        url: site_info.wpApiSettings.root + 'wp/v2/ll_promotion?_embed',
        beforeSend: function( xhr ) {
          xhr.setRequestHeader( 'X-WP-Nonce', site_info.wpApiSettings.nonce );
          xhr.setRequestHeader( 'X-Requested-With', 'XMLHttpRequest' );
        },
        success: function( data ) {
          _this.promotions = data;
          if ( data && data.length > 0 ) {
            let offset = 0;
            document.querySelectorAll( '.truck-card-wrapper' ).forEach( ( element, index ) => {
              if ( ( index + 1 ) % 10 == 0 && index > 5 ) {
                if ( !data[offset] ) {
                  offset = 0;
                }

                $( element ).after( _this.promotionTemplate( data[offset] ) );
                if ( document.querySelector( '#trucks-list-view' ).classList.contains( 'is-list' ) ) {
                  $( element ).after( _this.promotionTemplate( data[offset+1] ) );
                  offset = offset + 2;
                } else {
                  offset++;
                }
              }
            } );
          }
        },
      } );

      document.addEventListener( 'click', ( event ) => {
        if ( !event.target.matches( '.view-button' ) ) return;

        _this.type = event.target.dataset.view;

        document.querySelector( '.view-button.is-active' ).classList.remove( 'is-active' );
        document.querySelector( '#trucks-list-view' ).classList.remove( 'is-list' );
        event.target.classList.add( 'is-active' );

        document.querySelectorAll( '.truck-card-wrapper' ).forEach( ( element ) => {
          element.classList.remove( 'list-view', 'grid-view' );
          element.classList.add( _this.type );
        } );

        if ( _this.type === 'list-view' ) {
          document.querySelector( '#trucks-list-view' ).classList.add( 'is-list' );
        }

        document.querySelectorAll( '.promo-card' ).forEach( ( element ) => {
          element.remove();
        } );

        if ( _this.promotions && _this.promotions.length > 0 ) {
          let offset = 0;
          document.querySelectorAll( '.truck-card-wrapper' ).forEach( ( element, index ) => {
            if ( ( index + 1 ) % 10 == 0 && index > 5 ) {
              if ( !_this.promotions[offset] ) {
                offset = 0;
              }

              $( element ).after( _this.promotionTemplate( _this.promotions[offset] ) );
              if ( document.querySelector( '#trucks-list-view' ).classList.contains( 'is-list' ) ) {
                $( element ).after( _this.promotionTemplate( _this.promotions[offset+1] ) );
                offset = offset + 2;
              } else {
                offset++;
              }
            }
          } );
        }
      } );
    },
    promotionTemplate: function( data ) {
      if ( data._embedded ) {
        return `
          <div class="col w-full md:w-1/2 lg:w-1/3 mt-10 promotion promo-card">
            <div class="relative overflow-hidden image-wrapper h-full">
              <a href="/commercial-truck-sales">
                <div class="fit-image object-cover object-center">
                  ${data._embedded['wp:featuredmedia'] && data._embedded['wp:featuredmedia'].length > 0 && data._embedded['wp:featuredmedia'][0].media_type === 'image' ? `
                  <img class="object-cover object-center" src="${data._embedded['wp:featuredmedia'][0].source_url}" alt="${data._embedded['wp:featuredmedia'][0].alt_text}" title="${data._embedded['wp:featuredmedia'][0].title.rendered}">
                  ` : ``}
                </div>
                <div class="flex items-end justify-between flex-col absolute top-1/2 left-0 w-full z-10 transform -translate-y-1/2">
                  <div class="tag-wrapper flex-0">
                    <span>Promo</span>
                  </div>

                  <div class="flex-0 text-center w-full px-10 mt-12">
                    <span class="icon-wrapper bg-white"><svg class="icon icon-estimate text-brand-primary"><use xlink:href="#icon-estimate"></use></svg></span>
                    <h3 class="hdg-5 mb-2 text-white">${data.title.rendered}</h3>
                    <p class="text-sm mb-2 text-white">${data.ACF.promotion_description}</p>
                    <p class="text-xs text-gray-200 mt-1">${data.ACF.promotion_disclaimer}</p>
                  </div>
                </div>
              </a>
            </div>
          </div>`;
      }
    },
  };

  app.registerComponent( 'filters', COMPONENT );
} )( app );
