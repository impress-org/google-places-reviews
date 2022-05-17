const mix = require('laravel-mix');
const { default: ImageminPlugin } = require('imagemin-webpack-plugin');
require('@tinypixelco/laravel-mix-wp-blocks');

mix.block('assets/src/js/block.js', 'js');

mix.setPublicPath('assets/dist')
    .sourceMaps(false)
    // Admin
    .js('assets/src/js/admin-main.js', 'js/admin-main.js')
    .sass('assets/src/css/admin-main.scss', 'css/admin-main.css')
    // Public
    .sass('assets/src/css/public-main.scss', 'css/public-main.css')
    // Images
    .copy('assets/src/images', 'assets/dist/images')
    .options({
        processCssUrls: false,
    });
