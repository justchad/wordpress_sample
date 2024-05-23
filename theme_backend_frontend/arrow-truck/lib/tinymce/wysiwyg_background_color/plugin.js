tinymce.PluginManager.add( 'blockcolor', function( editor, url ) {
  const documentId = 'wp-' + editor.id + '-wrap';
  const layout = document.querySelector( '#' + documentId ).closest( '.layout' );

  if ( layout ) {
    const colorField = layout.querySelector( '[data-name*="_background_color"] label.selected input' );

    if ( colorField ) {
      editor.settings.body_class = colorField.value;

      const inputs = layout.querySelectorAll( '[data-name*="_background_color"] .acf-button-group input' );
      inputs.forEach( function( item ) {
        item.addEventListener( 'change', function( e ) {
          let classList = editor.contentDocument.body.classList.toString().split( ' ' );
          classList = classList.filter( function( classname ) {
            return classname.lastIndexOf( 'bg-' ) !== -1;
          } );

          if ( classList && classList[0] ) {
            editor.contentDocument.body.classList.remove( classList[0].trim() );
          }

          editor.contentDocument.body.classList.add( e.target.value );
        } );
      } );
    }
  }
} );
