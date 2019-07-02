/*!
 * gulp-starter
 * Task. Build SVG sprites
 * (c) 2017-2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

//const bs        = require('browser-sync').create();
const gulp      = require('gulp');
const svgMin    = require('gulp-svgmin');
const cheerio   = require('gulp-cheerio');
const replace   = require('gulp-replace');
const svgSprite = require('gulp-svg-sprites');

const config = require('../../gulp.config');
const notify = require('../lib/notifyError');
const tools  = require('../lib/tools');

const svgMinOptions = {
    js2svg: {pretty: true},
};

const cheerioOptions = {
    parserOptions: {xmlMode: true},
    run          : function ($) {
        $('title').remove();
        $('[stroke="#000"]').removeAttr('stroke');
        $('[stroke="#000000"]').removeAttr('stroke');
        $('[style]').removeAttr('style');
    },
};

module.exports = function () {
    return function (done) {

        const {source, build} = tools.makePaths(config.sprite.svg);

        let previewTemplate = require('fs').readFileSync(config.root.main + '/gulp/tmpl/svg-sprite-preview.html',
            "utf-8");

        gulp.src(source)
            .on('end', function () {
                done();
            })
            .pipe(svgMin(svgMinOptions)).on('error', notify)
            .pipe(cheerio(cheerioOptions)).on('error', notify)
        // cheerio plugin create unnecessary string '&gt;', so replace it.
            .pipe(replace('&gt;', '>'))
            .pipe(replace('&amp;gt;', '>'))
            .pipe(svgSprite({
                mode     : 'symbols',
                svg      : {
                    symbols: config.sprite.svg.dest,
                },
                preview  : {
                    symbols: config.sprite.svg.previewPath,
                },
                templates: {
                    previewSymbols: previewTemplate,
                },
            })).on('error', notify)
            .pipe(gulp.dest(build));
    };
};
