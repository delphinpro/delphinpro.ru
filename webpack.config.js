/*!
 * gulp-starter
 * Webpack configuration
 * (c) 2017-2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

const path                 = require('path');
const webpack              = require('webpack');
const VueLoaderPlugin      = require('vue-loader/lib/plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

const tools         = require('./gulp/lib/tools');
const isDevelopment = require('./gulp/lib/checkMode').isDevelopment;
const DIST          = require('./gulp/lib/checkMode').checkMode('dist');

const config = require('./gulp.config');
const root   = config.root;

const nodeEnv          = isDevelopment() ? 'development' : 'production';
const cssLoaderOptions = {url: false};

let outputPath = DIST
    ? path.join(root.main, tools.getTempDirectory())
    : path.join(root.main, root.build, root.static, config.webpack.dest);

tools.info(`Webpack output: ${outputPath}`);

let webpackConfig = {
    mode   : nodeEnv,
    devtool: nodeEnv === 'development' ? 'cheap-module-source-map' : undefined,
    // watch  : nodeEnv === 'development',

    entry: config.webpack.entry,

    output: {
        filename: '[name].bundle.js',
        path    : outputPath,
    },

    resolve: {
        modules   : ['node_modules'],
        extensions: ['.js', '.vue'],
        alias     : {'vue$': 'vue/dist/vue.esm.js'},
    },

    module: {
        rules: [
            {
                test   : /\.js$/,
                loader : 'babel-loader',
                exclude: function (file) {
                    return /node_modules/.test(file)
                        // && !/\.vue\.js/.test(file)
                        // && !/vue-js-modal/.test(file)
                        ;
                },
            },
            {
                test: /\.css$/,
                use : [
                    MiniCssExtractPlugin.loader,
                    {loader: 'css-loader', options: cssLoaderOptions},
                ],
            },
            {
                test  : /\.vue$/,
                loader: 'vue-loader',
            },
        ],
    },

    optimization: {
        splitChunks: {
            cacheGroups: {
                commons: {
                    test  : /[\\/]node_modules[\\/]/,
                    name  : 'vendor',
                    chunks: 'all',
                },
            },
        },
    },

    plugins: [
        new VueLoaderPlugin(),

        new MiniCssExtractPlugin({
            filename: '[name].bundle.css',
        }),

        new webpack.DefinePlugin({
            'process.env.NODE_ENV': JSON.stringify(nodeEnv),
        }),
    ],
};

module.exports = webpackConfig;
