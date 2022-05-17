const mix = require('laravel-mix');
const wpPot = require('wp-pot');
const { default: ImageminPlugin } = require('imagemin-webpack-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');

mix.setPublicPath('assets/dist')
    .sourceMaps(false)
    // Admin
    .js('assets/src/js/admin-main.js', 'js/admin-main.js')
    .js('assets/src/js/block.js', 'js/block.js')
    .sass('assets/src/css/admin-main.scss', 'css/admin-main.css')
    // Public
    .sass('assets/src/css/public-main.scss', 'css/public-main.css')
    // Images
    .copy('assets/src/images', 'assets/dist/images')
    .options({
        processCssUrls: false,
    });

mix.webpackConfig({
    plugins: [
        new CleanWebpackPlugin(),
        new ImageminPlugin({
            test: /\.(jpe?g|png|gif|svg)$/i,
            disable: !mix.inProduction(),
        }),
    ],
});
