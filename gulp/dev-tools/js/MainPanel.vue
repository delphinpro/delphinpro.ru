<script>/*!
 * gulp-starter
 * dev-tools
 * MainPanel component
 * (c) 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import { GlobalOptions } from './global-options';
import OptionCheckbox    from './OptionCheckbox';


export default {
    components: {
        OptionCheckbox,
    },

    mixins: [GlobalOptions],

    props: {
        showPixel  : Boolean,
        invertPixel: Boolean,
        showGrid   : Boolean,
        showRhythm : Boolean,
        showOutline: Boolean,
    },

    data: () => ({}),
};
</script>

<template>
    <div class="gs-debugger-panel">
        <div class="gs-debugger-panel__group">
            <OptionCheckbox v-model="pixelShow" label-text="Наложение PixelPerfect"></OptionCheckbox>
            <div class="gs-debugger-panel__group">
                <OptionCheckbox
                    v-model="pixelInvert"
                    :disabled="!pixelShow"
                    label-text="Инвертировать наложение"
                ></OptionCheckbox>
            </div>
        </div>
        <div class="gs-debugger-panel__group">
            <OptionCheckbox v-model="showGrid" label-text="Показать сетку"></OptionCheckbox>
        </div>
        <div class="gs-debugger-panel__group">
            <OptionCheckbox v-model="showRhythm" label-text="Показать ритм"></OptionCheckbox>
        </div>
        <div class="gs-debugger-panel__group">
            <OptionCheckbox v-model="showOutline" label-text="Показать элементы"></OptionCheckbox>
        </div>
    </div>
</template>

<style lang="scss">
    .gs-debugger-panel {
        user-select: none;
        position: fixed;
        background: rgba(6, 11, 17, 0.95);
        color: lime;
        padding: 1rem 1rem 1rem 50px;
        z-index: $z-debugger-panel;
        min-height: 140px;
        max-height: 80vh;
        overflow: auto;
        transition: opacity 0.15s ease;
        left: 0;
        bottom: 0;
        width: 100%;

        &__group {
            padding: 0 3px;
            margin: 5px 0;
        }

        &__group &__group {
            margin-left: 14px;
        }

        &, * { box-sizing: border-box; }
    }
</style>
