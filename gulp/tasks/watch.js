/*!
 * gulp-starter
 * Task. Watching changed in source files
 * (c) 2016-2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

const bs     = require('browser-sync');
const gulp   = require('gulp');
const tools  = require('../lib/tools');
const config = require('../../gulp.config');

module.exports = function () {
    return function (done) {

        config.watchableTasks.forEach(taskName => {
            let taskDef = config[taskName];

            if (taskDef) {

                if (typeof taskDef.src === 'string') {

                    let glob = (`${config.root.src}/${taskDef.src}/${tools.mask(taskDef.extensions)}`);
                    tools.info(`watch [${taskName}]: ${glob}`);
                    gulp.watch(glob, gulp.series(taskName));

                } else {
                    tools.danger(`Watch [${taskName}] taskDef: invalid src type`);
                }
                // else if (typeof taskDef.src === 'object') {
                //     for (let i = 0; i < taskDef.src.length; i++) {
                //         let glob;
                //         if (taskDef.extensions) {
                //             glob = path.join(config.root.src, taskDef.src[i], tools.mask(taskDef.extensions));
                //         } else {
                //             glob = path.join(config.root.src, taskDef.src[i]);
                //         }
                //         gulp.watch(glob, [taskName]);
                //     }
                // }
                //
                //         if (taskName === 'twig') {
                //             gulp.watch(path.join(config.root.src, taskDef.dataFile), [taskName]);
                //         }
            }
        });

        config.watchCustom.forEach(item => {
            tools.info(`watch custom: ${item}`);
            gulp.watch(item, done => {
                if (bs.has(config.browserSync.instanceName)) {
                    bs.get(config.browserSync.instanceName).reload();
                }
                done();
            });
        });
        //
        // gulp.watch('gulp/frontend-tools/**/*.scss', ['frontend-tools:js']);
        // gulp.watch('gulp/frontend-tools/**/*.js', ['frontend-tools:js']);
        // gulp.watch('gulp/frontend-tools/*.*', ['frontend-tools:misc']);
        // gulp.watch('gulp/frontend-tools/static/**', ['frontend-tools:misc']);
        // gulp.watch('gulp/frontend-tools/classes/**', ['frontend-tools:misc']);
        // gulp.watch('README.md', ['docs']);
        // gulp.watch('docs/**', ['docs']);
        // gulp.watch('source/sprites/**', ['sprite']);

        done();
    };
};
