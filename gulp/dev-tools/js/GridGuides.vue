<script>/*!
 * gulp-starter
 * dev-tools
 * GridGuides component
 * (c) 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

export default {
    props: {
        columnCount   : { type: Number, default: 12 },
        containerClass: { type: String, default: 'container' },
        rowClass      : { type: String, default: 'row' },
        colClass      : { type: String, default: '' },
    },
};
</script>

<template>
    <div class="gs-debugger-grid">
        <div class="gs-debugger-grid__container" :class="containerClass">
            <div class="gs-debugger-grid__row" :class="rowClass">
                <div
                    v-for="i in columnCount"
                    class="gs-debugger-grid__col"
                    :class="colClass"
                ></div>
            </div>
        </div>
    </div>
</template>

<style lang="scss">
    .gs-debugger-grid {
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        overflow: hidden;
        z-index: $z-grid-guides;

        &__container,
        &__row,
        &__col {
            height: 100%;
        }

        &__container { }

        &__row { }

        &__col {
            @if (mixin_exists(make-col) and mixin_exists(make-col-ready)) {
                @include make-col-ready();
                @include make-col(1);
            }

            //border-right: 1px solid $dev-grid-guides-color;

            //&:first-child { border-left: 1px solid $dev-grid-guides-color; }

            &::before {
                content: '';
                display: block;
                width: calc(100% + 2px);
                margin-left: -1px;
                height: 100%;
                border-right: 1px solid $dev-grid-guides-color;
                border-left: 1px solid $dev-grid-guides-color;
            }


            @if (mixin_exists(media-breakpoint-down)) {
                $next: breakpoint-next(xs);

                @include media-breakpoint-down($next) {
                    @include make-col(12);
                    &:not(:first-child) { display: none; }
                }
            }
        }
    }
</style>
