let mix = require('laravel-mix');
const postcssImport = require( 'postcss-import' );
const precss = require( 'precss' );
const postcssExtendRule = require( 'postcss-extend-rule' );
const postcssNested = require( 'postcss-nested' );
// const postCSShexrgba = require( 'postcss-hexrgba' );
const autoprefixer = require( 'autoprefixer' );
const globby = require( 'globby' );
require('laravel-mix-postcss-config');

mix.options({
  processCssUrls: false
});

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application, as well as bundling up your JS files.
 |
 */

/*
 |--------------------------------------------------------------------------
 | Scripts
 |--------------------------------------------------------------------------
 |
 | Array of paths to all of the different script files. For now, we include
 | them manually here. For some reason the linter won't watch them if we include
 | them using one of the combine mix methods such as scripts or babel.
 | Unfortunately, the mix.js() method doesn't accept wildcards to include entire
 | directories.
 |
 */
mix.webpackConfig({
    externals: {
        "jquery": "jQuery"
    },
    module: {
      rules: [ {
        test: /\.css?$/,
        loader: 'postcss-loader'
      } ]
    }
});

mix.setPublicPath( 'assets/' );

/*
 * Front End Pipeline
 */

if ( !process.argv.includes('--watch-admin') ) {
  $ll_scripts = globby.sync( ['resources/js/app.js','resources/js/_*.js', 'components/*/*.js'] );
  $vendor_scripts = [
    'resources/js/vendor/easy-toggle-state.js',
    'node_modules/magnific-popup/dist/jquery.magnific-popup.js',
    'node_modules/slick-carousel/slick/slick.min.js',
    'resources/js/plugins/smoothscroll.js',
  ];

  mix.postCss('resources/css/app.css', 'css/main.min.css', [
    postcssImport,
    precss,
    postcssExtendRule,
    require('tailwindcss')('resources/css/tailwind.config.js'),
    autoprefixer,
    postcssNested,
    // postCSShexrgba
  ]).postCssConfig().sourceMaps();

  mix.js($ll_scripts, 'js/scripts.min.js').sourceMaps();
  mix.js($vendor_scripts, 'js/ll_vendor.min.js');
}

/*
 * Admin / Editor Pipeline
 */
if ( !process.argv.includes('--watch-front') ) {
  mix.postCss('resources/admin/admin.css', 'css/admin.min.css', [
    postcssImport,
    precss,
    postcssExtendRule,
    autoprefixer,
    postcssNested,
    // postCSShexrgba
  ]).postCssConfig();

  mix.postCss('resources/admin/editor-style.css', 'css/editor-style.css', [
    postcssImport,
    precss,
    postcssExtendRule,
    require('tailwindcss')('resources/css/tailwind.config.js'),
    autoprefixer,
    postcssNested,
    // postCSShexrgba
  ]).postCssConfig().sourceMaps();

  mix.js('resources/admin/admin.js', 'js/admin.min.js').sourceMaps();
}

mix.version();

mix.webpackConfig({
  module: {
    rules: [{
      enforce: 'pre',
      test: /\.js$/,
      loader: 'eslint-loader',
      exclude: /node_modules/,
      options: {fix: true}
    }]
  },
  resolve: {
    alias: {
        "gsap": path.resolve('node_modules', 'gsap'),
        "@ryangjchandler/spruce": path.resolve('node_modules', '@ryangjchandler/spruce'),
        "alpinejs": path.resolve('node_modules','alpinejs')
    },
  },
});
