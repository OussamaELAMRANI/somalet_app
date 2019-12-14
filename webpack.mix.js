const config = require('./webpack.config');
const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
    .webpackConfig({
        output: {chunkFilename: 'js/[name].js'},
    })
    .sass('resources/sass/app.scss', 'public/css');

mix.webpackConfig(config);

//
mix.browserSync('somalet.io');
