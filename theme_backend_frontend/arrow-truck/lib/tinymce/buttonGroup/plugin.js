tinymce.PluginManager.add( 'buttonGroup', function( editor, url ) {
  let buttonGroupCommand = editor.settings.style_formats.find( function( styleFormatGroup ) {
    return styleFormatGroup.title === 'Buttons & Links';
  } );

  if ( buttonGroupCommand ) {
    buttonGroupCommand = buttonGroupCommand.items.find( function( styleFormat ) {
      return styleFormat.title === 'Button Group';
    } );
  }

  if ( buttonGroupCommand && buttonGroupCommand.name ) {
    editor.on( 'ExecCommand', function( e ) {
      if ( e.command === 'mceToggleFormat' && e.value === buttonGroupCommand.name ) {
        const buttonGroups = editor.getBody().querySelectorAll( '.grid.grid.grid-flow-col.gap-5' );
        buttonGroups.forEach( function( item ) {
          const links = item.querySelectorAll( 'a' );
          let markup = '';
          links.forEach( function( link ) {
            markup = markup.concat( link.outerHTML );
          } );

          item.innerHTML = markup.trim();
        } );
      }
    } );
  }
} );
