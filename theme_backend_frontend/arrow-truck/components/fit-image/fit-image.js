/**
* Fit Image JS
* -----------------------------------------------------------------------------
*
**/
( function() {
  /*
     simple object-fit polyfill
    should only run on browsers that don't support object-fit
    this will apply the images as background images to the
    .fit-image container instead.
  */
  const fitImages = $( '.fit-image' );
  // stole this if check from modernizr 🤓
  // it checks for browser support on objectFit
  if ( 'objectFit' in document.documentElement.style === false ) {
    $( fitImages ).each( function() {
      const $container = $( this );
      const imgUrl = $container.find( 'img' ).prop( 'src' );
      if ( imgUrl ) {
        $container
            .css( {
              'backgroundImage': 'url(' + imgUrl + ')',
            } )
            .addClass( 'compat-object-fit' );
      }
    } );
  }
} )();
