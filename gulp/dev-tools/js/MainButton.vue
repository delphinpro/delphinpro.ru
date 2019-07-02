<script>/*!
 * gulp-starter
 * dev-tools
 * MainButton component
 * (c) 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

export default {};
</script>

<template>
    <button class="gs-debugger-main-button" @click="$emit('click', $event)">
        <span class="gs-debugger-main-button__content"></span>
    </button>
</template>

<style lang="scss">
    .gs-debugger-main-button {
        $bg: red;
        position: fixed;
        left: ($main-button-size / -2);
        bottom: ($main-button-size / -2);
        width: $main-button-size;
        height: $main-button-size;
        border: none;
        border-radius: 100%;
        transition: 0.3s ease;
        z-index: $z-main-button;
        cursor: pointer;
        background: rgba($bg, 0.1);

        &:hover {
            background: rgba($bg, 0.35);
        }

        &:focus {
            outline: none;
            box-shadow: 0 0 0 5px rgba(#7dadd9, 0.1);
        }

        &.showPanel {
            background: rgba($bg, 0.5);

            &:focus { box-shadow: 0 0 0 5px rgba(#7dadd9, 0.5); }
        }

        &__content {
            position: absolute;
            left: 50%;
            bottom: 50%;
            width: 50% * 4/5;
            height: 50% * 4/5;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;

            &::before {
                @if mixin_exists(media-breakpoint-up)
                 and function_exists(breakpoint-next)
                 and variable_exists(grid-breakpoints) {
                    content: 'xs';
                    @each $breakpoint, $value in $grid-breakpoints {
                        @include media-breakpoint-up($breakpoint) {
                            //$nextValue: map_get($grid-breakpoints, breakpoint-next($breakpoint));
                            content: '#{to_upper_case($breakpoint)}';
                            //content: '#{to_upper_case($breakpoint)}: #{$value}..#{$nextValue}';
                        }
                    }
                }
            }
        }
    }
</style>
