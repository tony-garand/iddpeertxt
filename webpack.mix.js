const mix = require('laravel-mix');
const webpack = require('webpack');

// mix.webpackConfig({
// 	plugins: [
// 		new webpack.IgnorePlugin(/^\.\/locale$/, /moment$/)
// 	]
// });

// mix.disableNotifications();
mix.js([
    'resources/assets/js/app.js',
//		'resources/assets/js/moment.js',
    'node_modules/spectrum-colorpicker/spectrum.js',
    'resources/assets/js/datatable.js',
    'resources/assets/js/datatable-moment.js',
    'resources/assets/js/featherlight.js',
    'node_modules/lightbox2/dist/js/lightbox.js',
    'resources/assets/js/core.js',
    'node_modules/suggestags/js/jquery.amsify.suggestags.js',
  'node_modules/chosen-js/chosen.jquery.js'
], 'public/js/app.js')
    .copy('resources/assets/js/pages', 'public/js/pages', false)
	.copy('node_modules/suggestags/css/amsify.suggestags.css', 'public/css/')
  .copy('node_modules/chosen-js/chosen.css', 'public/css')
    .sass('resources/assets/sass/app.scss', 'public/css').version();