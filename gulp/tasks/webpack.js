/*!
 * gulp-starter
 * Task. Bundling javascript source files with webpack
 * (c) 2017-2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

const path = require('path');

const bs      = require('browser-sync');
const webpack = require('webpack');

const config      = require('../../gulp.config');
const tools       = require('../lib/tools');
const DEVELOPMENT = require('../lib/checkMode').isDevelopment();

let webpackConfig = require(path.join(config.root.main, 'webpack.config.js'));

function showInfo(err, stats) {
    if (err) {
        tools.danger('WEBPACK ERROR:');
        tools.danger(err.stack || err);
        if (err.details) {
            tools.danger(err.details);
        }
        return;
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
    const len2 = info.assets.reduce((prev, curr) => curr.size.toString().length > prev ? curr.size.toString().length : prev,
        0);
    info.assets.forEach(chunk => {
        console.log(`  ${chunk.name.toString().padEnd(len1)} :  ${chunk.size.toString().padStart(len2)} b`);
    });
}

module.exports = function (options = {}) {

    options = {
        watch: false,
        ...options,
    };

    return function (done) {

        const compiler = webpack(webpackConfig);

        if (options.watch) {
            tools.info('Webpack watching...');

            compiler.watch({
                ignored         : /node_modules/,
                aggregateTimeout: 300,
            }, (err, stats) => {

                showInfo(err, stats);

                if (DEVELOPMENT && bs.has(config.browserSync.instanceName)) {
                    bs.get(config.browserSync.instanceName).reload();
                }
            });

        } else {

            compiler.run((err, stats) => {
                showInfo(err, stats);
                done();
            });

        }

    };
};
