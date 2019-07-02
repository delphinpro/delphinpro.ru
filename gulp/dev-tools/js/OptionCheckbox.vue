<script>/*!
 * gulp-starter
 * dev-tools
 * OptionCheckbox component
 * (c) 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

export default {
    props: {
        value    : { type: Boolean, default: false },
        disabled : { type: Boolean, default: false },
        labelText: { type: String, default: 'labelText' },
    },
};

</script>

<template>
    <div class="gs-debugger-checkbox">
        <label :class="{disabled}">
            <!--suppress JSUnresolvedFunction, JSUnresolvedVariable -->
            <input
                type="checkbox"
                :checked="value"
                :disabled="disabled"
                @change="$emit('input', $event.target.checked)"
            >
            <i></i><span>{{ labelText }}</span>
        </label>
    </div>
</template>

<style lang="scss">
    .gs-debugger-checkbox {
        $color: white;
        $color-checked: lime;
        color: $color;
        display: flex;

        label {
            display: inline-flex;
            align-items: flex-start;
            vertical-align: top;
            position: relative;
            overflow: hidden;
            cursor: pointer;

            &.disabled {
                cursor: default;
            }
        }

        input {
            position: absolute;
            left: -9999px;

            &:disabled ~ * {
                opacity: 0.5;
            }
        }

        i {
            flex-shrink: 0;
            width: 14px;
            height: 14px;
            border: 1px solid $color;
            padding: 2px;
            background: transparent;
            background-clip: content-box;
            transition: 0.25s ease;
        }

        input:checked + i {
            border-color: $color-checked;
            background-color: $color-checked;
        }

        span {
            padding-left: 0.5em;
            line-height: 12px;
        }
    }
</style>
