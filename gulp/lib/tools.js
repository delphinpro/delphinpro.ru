/*!
 * gulp-starter
 * Utilities
 * (c) 2016-2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

const path  = require('path');
const chalk = require('chalk');
const bs    = require('browser-sync');

const pkg         = require('../../package');
const config      = require('../../gulp.config');
const DEVELOPMENT = require('./checkMode').isDevelopment();

const LINE_WIDTH = 30;

const LINE_H     = '─'; // 2500
const LINE_V     = '│'; // 2502
const LINE_TR    = '┐'; // 2510
const LINE_BR    = '┘'; // 2518
const LINE_TL    = '┌'; // 250C
const LINE_BL    = '└'; // 2514
const LINE_RIGHT = '┤'; // 2524
const LINE_LEFT  = '├'; // 251C

let welcomeMessage = ``;
welcomeMessage += `${LINE_TL.padEnd(LINE_WIDTH, LINE_H) + LINE_TR}` + '\n';
welcomeMessage += `${LINE_V} ${pkg.name} v${pkg.version}`.padEnd(LINE_WIDTH) + LINE_V + '\n';
welcomeMessage += `${LINE_V} Frontend build system`.padEnd(LINE_WIDTH) + LINE_V + '\n';
welcomeMessage += `${LINE_BL.padEnd(LINE_WIDTH, LINE_H) + LINE_BR}` + '\n';

const tools = {
    mask(extensions) {
        if (typeof extensions === 'undefined') return '';

        if (!Array.isArray(extensions)) {
            extensions = extensions.toString().split(',');
        }

        extensions = extensions.map(item => item.trim());

        return extensions.length > 1
            ? '**/*.{' + extensions + '}'
            : '**/*.' + extensions + '';
    },

    /**
     * Сформировать пути для таска
     *
     * @param {Object, Object} taskDef
     * @param {string} taskDef.src
     * @param {string} taskDef.build
     * @param {string} taskDef.extensions
     * @returns {{source: string}}
     */
    makePaths(taskDef) {
        const source = path.resolve(config.root.src, taskDef.src, tools.mask(taskDef.extensions));
        const build  = path.resolve(config.root.build, taskDef.build);

        return {
            source,
            build,
        };
    },

    welcomeMessage() {
        // noinspection JSUnresolvedFunction
        console.log(chalk.magentaBright(welcomeMessage));
    },

    info(msg) {
        // noinspection JSUnresolvedFunction
        Array.from(arguments).map(msg => console.log(chalk.blue(msg)));
    },

    warn(msg) {
        // noinspection JSUnresolvedFunction
        Array.from(arguments).map(msg => console.log(chalk.yellow(msg)));
    },

    success() {
        // noinspection JSUnresolvedFunction
        Array.from(arguments).map(msg => console.log(chalk.green(msg)));
    },

    danger() {
        // noinspection JSUnresolvedFunction
        Array.from(arguments).map(msg => console.log(chalk.red(msg)));
    },

    getTempDirectory() {
        return config.root.temp || '.tmp';
    },
};

function bsNotify(msg, timeout = 1000) {
    if (DEVELOPMENT && bs.has(config.browserSync.instanceName)) {
        bs.get(config.browserSync.instanceName).notify(msg, timeout);
    }
}

module.exports = tools;

module.exports.bsNotify = bsNotify;

module.exports.LINE_WIDTH = LINE_WIDTH;
module.exports.LINE_H     = LINE_H;
module.exports.LINE_V     = LINE_V;
module.exports.LINE_TR    = LINE_TR;
module.exports.LINE_BR    = LINE_BR;
module.exports.LINE_TL    = LINE_TL;
module.exports.LINE_BL    = LINE_BL;
module.exports.LINE_RIGHT = LINE_RIGHT;
module.exports.LINE_LEFT  = LINE_LEFT;
