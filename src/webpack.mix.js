const mix = require('laravel-mix');


mix.webpackConfig({

});

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.setPublicPath('../').js('resources/js/app.js', 'js')
    .vue()
    .sass('resources/sass/app.scss', 'css');
