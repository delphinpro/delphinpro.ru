/*!
 * gulp-starter
 * Task. Build dev tools.
 * (c) 2017-2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

const fs              = require('fs');
const path            = require('path');
const gulp            = require('gulp');
const webpack         = require('webpack');
const VueLoaderPlugin = require('vue-loader/lib/plugin');

const config = require('../../gulp.config');
const tools  = require('../lib/tools');

const cssLoaderOptions  = { url: false };
const sassLoaderOptions = {
    outputStyle: 'compressed',
    data       : '@import "./config.scss";',
};

if (config.devTools.overrideScss && Array.isArray(config.devTools.overrideScss)) {
    config.devTools.overrideScss.forEach(item => {
        let p = path.join(config.root.main, item);
        if (fs.existsSync(p)) {
            sassLoaderOptions.data += '\n' + `@import "../../../${item}";`;
        } else {
            tools.warn(`File not exists: ${item}`)
        }
    });
}

tools.info('Additional sass data:');
console.log(sassLoaderOptions.data);

const webpackConfig = {
    mode   : 'production',
    devtool: undefined,
    watch  : false,
    entry  : {
        dev: './gulp/dev-tools/js/dev.js',
    },
    output : {
        filename: config.devTools.scriptName,
        path    : path.join(config.root.main, config.root.build, config.devTools.scriptDest),
    },
    resolve: {
        modules   : ['node_modules'],
        extensions: ['.js', '.vue'],
        // alias     : { 'vue$': 'vue/dist/vue.esm.js' },
    },
    module : {
        rules: [
            {
                test   : /\.js$/,
                loader : 'babel-loader',
                exclude: [/node_modules/, /3rd-party/],
            },
            {
                test: /\.css$/,
                use : ['style-loader', { loader: 'css-loader', options: cssLoaderOptions }],
            },
            {
                test: /\.scss$/,
                use : [
                    'style-loader',
                    { loader: 'css-loader', options: cssLoaderOptions },
                    { loader: 'sass-loader', options: sassLoaderOptions },
                ],
            },
            {
                test  : /\.vue$/,
                loader: 'vue-loader',
            },
        ],
    },
    plugins: [
        new VueLoaderPlugin(),
        new webpack.DefinePlugin({ 'process.env.NODE_ENV': JSON.stringify('production') }),
    ],
};

function copyFiles() {
    return new Promise((resolve, reject) => {

        if (config.devTools.srcMain
            && Array.isArray(config.devTools.srcMain)
            && config.devTools.srcMain.length
        ) {

            gulp.src(config.devTools.srcMain)
                .on('error', err => reject(err))
                .on('end', () => resolve())
                .pipe(gulp.dest(config.devTools.destMain));

        } else {
            resolve();
        }
    });
}

function buildScripts() {
    return new Promise((resolve, reject) => {

        webpack(webpackConfig).run((err, stats) => {

            if (err) {
                tools.danger('WEBPACK ERROR:');
                tools.danger(err.stack || err);
                if (err.details) {
                    tools.danger(err.details);
                }
                reject(err);
            }

            const info = stats.toJson();

            if (stats.hasErrors()) {
                tools.danger('WEBPACK ERRORS:');
                // let msg = info.errors.reduce((prev, el) => prev+el, '');
                // console.error(msg);
                // info.errors.map(msg => console.error(msg));
                tools.danger(info.errors[0]);
            }

            if (stats.hasWarnings()) {
                tools.warn('WEBPACK WARNINGS:');
                info.warnings.map(msg => tools.warn(msg));
            }

            // console.log(info);
            console.log(`Webpack output:`);
            const len1 = info.assets.reduce((prev, curr) => curr.name.length > prev ? curr.name.length : prev, 0);
            const len2 = info.assets.reduce((prev, curr) => curr.size.toString().length > prev
                ? curr.size.toString().length
                : prev, 0);
            info.assets.forEach(chunk => {
                console.log(`  ${chunk.name.toString().padEnd(len1)} :  ${chunk.size.toString().padStart(len2)} b`);
            });

            stats.hasErrors() ? reject(err) : resolve();

        });
    });
}

module.exports = function () {

    return function (done) {

        let promises = [];
        promises.push(buildScripts());
        promises.push(copyFiles());

        Promise
            .all(promises)
            .then(() => done())
            .catch(err => done(err));

    };
};
