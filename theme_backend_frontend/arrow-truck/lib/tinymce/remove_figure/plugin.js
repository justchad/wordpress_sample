jQuery(function($) {
  tinymce.PluginManager.add( 'remove_figure', function( editor, url ) {
    editor.on('NodeChange', function (e) { // check everytime a node is changed
      var text = editor.getBody(); // grab editor content
      var $images = $(text).find('figure'); // grab all figure elements (images)

      $images.each(function() { // loop through each found figure (image)
        if(!$('img', this).length) { // check if the figure is empty (an image was removed)
          $(this).remove(); // remove it!
        }
      });
    });
  });
});