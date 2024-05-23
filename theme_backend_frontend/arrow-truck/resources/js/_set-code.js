( function( app ) {
  const COMPONENT = {
    init: function() {
      const _this = this;
      const code = document.querySelector( '[data-code]' ).dataset.code;
      createCookie( 'wordpress_salesmanNumber', code, 14 );
    },

    finalize: function() {
    },
  };

  app.registerComponent( 'set-code', COMPONENT );
} )( app );
