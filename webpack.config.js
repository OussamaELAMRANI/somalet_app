const path = require('path');
const webpack = require('webpack');

// function resolve(dir) {
//     return path.join(
//         __dirname,
//         '/resources/js',
//         dir
//     );
// }

module.exports = {
    module: {
        rules:[
            {
                test: /\.pug$/,
                loader: 'pug-plain-loader'
            }
        ],
    },
    resolve: {
        extensions: ['.js', '.vue', '.json'],
        alias: {
            vue$: 'vue/dist/vue.esm.js',
            '@': path.join(__dirname, '/resources/js'),
        },
    }

};
