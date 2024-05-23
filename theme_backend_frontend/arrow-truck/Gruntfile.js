'use strict';

module.exports = function(grunt) {

  function slugify(text, useHyphens) {

    if ( typeof useHyphens === 'undefined' ) {
      useHyphens = true;
    }

    var replacement = '-';

    if ( ! useHyphens ) {
      replacement = '_';
    }

    text = text.toString().toLowerCase().trim()
      .replace(/\s+/g, replacement)                      // Replace spaces with -
      .replace(/&/g, replacement + 'and' + replacement)  // Replace & with 'and'
      .replace(/[^\w\-]+/g, '')                          // Remove all non-word chars
      .replace(/\-\-+/g, replacement);                   // Replace multiple - with single -

    if ( useHyphens ) {
      text = text.replace(/\_/g, '-');                          // Replace _ with -
    }

    return text;
  }
  function insertBeforeLastOccurrence(strToSearch, strToFind, strToInsert) {
    var n = strToSearch.lastIndexOf(strToFind);
    if ( strToFind !== 'eof' ) {
      if (n < 0) {
        return strToSearch;
      }
    }
    else {
      var matches = strToSearch.match(/\r?\n$/);

      if ( matches ) {
        n = matches.index;
      }
      else {
        return strToSearch;
      }
    }

    return strToSearch.substring(0,n) + strToInsert + strToSearch.substring(n);
  }

  // Load all tasks
  require('load-grunt-tasks')(grunt);

  // Show elapsed time
  //require('time-grunt')(grunt);

  grunt.initConfig({
    /**
     * generate new files
     */
    generate: {
      /**
       * Component generator
       */
      component: {
        expand: true,
        src: 'generate/component/*',
        rename: function( dest, src ) {
          var slug = slugify( grunt.task.current.args[0] );
          return 'components/' + slug + '/' + src.replace( 'component', slug );
        },
        flatten: true,
        nonull: true,
        options: {
          process: function( content, srcpath ) {
            var data = {
              name: grunt.task.current.args[0],
              slug: slugify( grunt.task.current.args[0] )
            };

            var mainCSS = grunt.file.read('resources/css/app.css');

            if ( mainCSS.indexOf('@import \'../../components/' + data.slug + '/' + data.slug) === -1 ) {
              mainCSS += '@import \'../../components/' + data.slug + '/' + data.slug + '\';\r\n';
            }

            grunt.file.write('resources/css/app.css', mainCSS);
            return grunt.template.process( content, { data: data } );
          }
        }
      },

      /**
       * CSS generators
       */
      cssHelper: {
        expand: true,
        src: 'generate/helper.css',
        rename: function() {
          var slug = slugify(grunt.task.current.args[0]);
          return 'resources/css/helpers/_' + slug + '.css';
        },
        flatten: true,
        filter: 'isFile',
        nonull: true,
        options: {
          process: function(content, srcpath) {
            var data = {
              name: grunt.task.current.args[0],
              slug: slugify(grunt.task.current.args[0])
            };

            var mainCSS = grunt.file.read('resources/css/main.css');

            mainCSS = insertBeforeLastOccurrence(mainCSS, '\n// Vendors', '@import "helpers/_' + data.slug + '";\r\n');

            grunt.file.write('resources/css/main.css', mainCSS);
            return grunt.template.process(content, {data: data});
          }
        }
      },
      cssBase: {
        expand: true,
        src: 'generate/base.css',
        rename: function() {
          var slug = slugify(grunt.task.current.args[0]);
          return 'resources/css/base/_' + slug + '.css';
        },
        flatten: true,
        filter: 'isFile',
        nonull: true,
        options: {
          process: function(content, srcpath) {
            var data = {
              name: grunt.task.current.args[0],
              slug: slugify(grunt.task.current.args[0])
            };

            var mainCSS = grunt.file.read('resources/css/main.css');

            mainCSS = insertBeforeLastOccurrence(mainCSS, '\n// Layout', '@import "base/_' + data.slug + '";\r\n');

            grunt.file.write('resources/css/main.css', mainCSS);
            return grunt.template.process(content, {data: data});
          }
        }
      },
      cssLayout: {
        expand: true,
        src: 'generate/layout.css',
        rename: function() {
          var slug = slugify(grunt.task.current.args[0]);
          return 'resources/css/layout/_' + slug + '.css';
        },
        flatten: true,
        filter: 'isFile',
        nonull: true,
        options: {
          process: function(content, srcpath) {
            var data = {
              name: grunt.task.current.args[0],
              slug: slugify(grunt.task.current.args[0])
            };

            var mainCSS = grunt.file.read('resources/css/main.css');

            mainCSS = insertBeforeLastOccurrence(mainCSS, '\n// Partials', '@import "layout/_' + data.slug + '";\r\n');

            grunt.file.write('resources/css/main.css', mainCSS);
            return grunt.template.process(content, {data: data});
          }
        }
      },
      cssPartial: {
        expand: true,
        src: 'generate/partial.css',
        rename: function() {
          var slug = slugify(grunt.task.current.args[0]);
          return 'resources/css/partials/_' + slug + '.css';
        },
        flatten: true,
        filter: 'isFile',
        nonull: true,
        options: {
          process: function(content, srcpath) {
            var data = {
              name: grunt.task.current.args[0],
              slug: slugify(grunt.task.current.args[0])
            };

            var mainCSS = grunt.file.read('resources/css/main.css');

            mainCSS = insertBeforeLastOccurrence(mainCSS, '\n// Pages', '@import "partials/_' + data.slug + '";\r\n');

            grunt.file.write('resources/css/main.css', mainCSS);
            return grunt.template.process(content, {data: data});
          }
        }
      },
      cssPage: {
        expand: true,
        src: 'generate/page.css',
        rename: function() {
          var slug = slugify(grunt.task.current.args[0]);
          return 'resources/css/pages/_' + slug + '.css';
        },
        flatten: true,
        filter: 'isFile',
        nonull: true,
        options: {
          process: function(content, srcpath) {
            var data = {
              name: grunt.task.current.args[0],
              slug: slugify(grunt.task.current.args[0])
            };

            var mainCSS = grunt.file.read('resources/css/main.css');

            mainCSS = insertBeforeLastOccurrence(mainCSS, 'eof', '\r\n@import "pages/_' + data.slug + '";');

            grunt.file.write('resources/css/main.css', mainCSS);
            return grunt.template.process(content, {data: data});
          }
        }
      }
    }
  });

  // Register tasks
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.task.renameTask('copy', 'generate');
};
