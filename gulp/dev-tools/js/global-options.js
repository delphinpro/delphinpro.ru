/*!
 * gulp-starter
 * dev-tools
 * GlobalOptions mixin
 * (c) 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

const prefix = 'gs.debugger';

export const LS_SHOW_PANEL   = `${prefix}.LS_SHOW_PANEL`;
export const LS_PIXEL_SHOW   = `${prefix}.LS_PIXEL_SHOW`;
export const LS_PIXEL_INVERT = `${prefix}.LS_PIXEL_INVERT`;
export const LS_SHOW_GRID    = `${prefix}.LS_SHOW_GRID`;
export const LS_SHOW_RHYTHM  = `${prefix}.LS_SHOW_RHYTHM`;
export const LS_SHOW_OUTLINE = `${prefix}.LS_SHOW_OUTLINE`;

/** @namespace this.$root */
/** @namespace this.$root.$data */
export const GlobalOptions = {
    computed: {
        showPanel: {
            get() { return this.$root.$data.showPanel; },
            set(val) {
                this.$root.$data.showPanel = val;
                localStorage.setItem(LS_SHOW_PANEL, +this.$root.$data.showPanel);
            },
        },

        showGrid: {
            get() { return this.$root.$data.showGrid; },
            set(val) {
                this.$root.$data.showGrid = val;
                localStorage.setItem(LS_SHOW_GRID, +this.$root.$data.showGrid);
                document.documentElement.dataset.grid = +this.$root.$data.showGrid;
            },
        },

        pixelShow: {
            get() { return this.$root.$data.pixelShow; },
            set(val) {
                this.$root.$data.pixelShow = val;
                localStorage.setItem(LS_PIXEL_SHOW, +this.$root.$data.pixelShow);
                document.documentElement.dataset.on = +this.$root.$data.pixelShow;
            },
        },

        pixelInvert: {
            get() { return this.$root.$data.pixelInvert; },
            set(val) {
                this.$root.$data.pixelInvert = val;
                localStorage.setItem(LS_PIXEL_INVERT, +this.$root.$data.pixelInvert);
                document.documentElement.dataset.invert = +this.$root.$data.pixelInvert;
            },
        },

        showRhythm: {
            get() { return this.$root.$data.showRhythm; },
            set(val) {
                this.$root.$data.showRhythm = val;
                localStorage.setItem(LS_SHOW_RHYTHM, +this.$root.$data.showRhythm);
                document.documentElement.dataset.rhythm = +this.$root.$data.showRhythm;
            },
        },

        showOutline: {
            get() { return this.$root.$data.showOutline; },
            set(val) {
                this.$root.$data.showOutline = val;
                localStorage.setItem(LS_SHOW_OUTLINE, +this.$root.$data.showOutline);
                document.documentElement.dataset.outline = +this.$root.$data.showOutline;
            },
        },
    },
};
