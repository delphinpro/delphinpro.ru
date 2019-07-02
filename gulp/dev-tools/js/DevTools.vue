<script>/*!
 * gulp-starter
 * dev-tools
 * DevTools component
 * (c) 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import { GlobalOptions } from './global-options';
import GridGuides        from './GridGuides';
import MainButton        from './MainButton';
import MainPanel         from './MainPanel';


export default {
    components: {
        GridGuides,
        MainButton,
        MainPanel,
    },

    mixins: [GlobalOptions],

    data: () => ({
        columnCount: new Array(12),
    }),

    mounted() {
        // document.addEventListener('click', () => this.showPanel = false);
    },
};
</script>

<template>
    <div class="gs-debugger" @click.stop role="document">
        <GridGuides
            v-if="showGrid"
            :column-count="columnCount"
        ></GridGuides>

        <MainPanel v-if="showPanel"></MainPanel>

        <MainButton
            :class="{showPanel}"
            @click="showPanel = !showPanel"
        ></MainButton>
    </div>
</template>

<style lang="scss">

    [data-rhythm="1"] body {
        .app {
            position: relative;

            &::after {
                z-index: $MAX_INT32 - 10;
                pointer-events: none;
                content: '';
                position: absolute;
                left: 0;
                top: 0;
                right: 0;
                bottom: 0;
                width: 100%;
                height: 100%;
                // @include debug-vertical-alignment($dev-grid-guides-color);
            }
        }
    }

</style>
