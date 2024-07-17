<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Please wait</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet">
    <style>
        /*! normalize.css v8.0.1 | MIT License | github.com/necolas/normalize.css */
        html {
            line-height: 1.15;
            -webkit-text-size-adjust: 100%
        }

        body {
            margin: 0
        }

        a {
            background-color: transparent
        }

        code {
            font-family: monospace, monospace;
            font-size: 1em
        }

        [hidden] {
            display: none
        }

        html {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica Neue, Arial, Noto Sans, sans-serif, Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol, Noto Color Emoji;
            line-height: 1.5
        }

        *, :after, :before {
            box-sizing: border-box;
            border: 0 solid #e2e8f0
        }

        a {
            color: inherit;
            text-decoration: inherit
        }

        code {
            font-family: Menlo, Monaco, Consolas, Liberation Mono, Courier New, monospace
        }

        svg, video {
            display: block;
            vertical-align: middle
        }

        video {
            max-width: 100%;
            height: auto
        }

        .bg-gray-100 {
            background-color: #f7fafc;
        }

        .border-gray-400 {
            border-color: #cbd5e0;
        }

        .border-r {
            border-right-width: 1px
        }

        .flex {
            display: flex
        }

        .items-center {
            align-items: center
        }

        .justify-center {
            justify-content: center
        }

        .text-lg {
            font-size: 1.125rem
        }

        .mx-auto {
            margin-left: auto;
            margin-right: auto
        }

        .ml-4 {
            margin-left: 1rem
        }

        .max-w-xl {
            max-width: 36rem
        }

        .min-h-screen {
            min-height: 100vh
        }

        .px-4 {
            padding-left: 1rem;
            padding-right: 1rem
        }

        .pt-8 {
            padding-top: 2rem
        }

        .relative {
            position: relative
        }

        .text-gray-500 {
            color: rgba(160, 174, 192, 1)
        }

        .antialiased {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale
        }

        .tracking-wider {
            letter-spacing: .05em
        }

        .mono {
            font-family: monospace;
            font-size: 1.4em;
        }

        @-webkit-keyframes spin {
            0% {
                transform: rotate(0deg)
            }
            to {
                transform: rotate(1turn)
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg)
            }
            to {
                transform: rotate(1turn)
            }
        }

        @-webkit-keyframes ping {
            0% {
                transform: scale(1);
                opacity: 1
            }
            75%, to {
                transform: scale(2);
                opacity: 0
            }
        }

        @keyframes ping {
            0% {
                transform: scale(1);
                opacity: 1
            }
            75%, to {
                transform: scale(2);
                opacity: 0
            }
        }

        @-webkit-keyframes pulse {
            0%, to {
                opacity: 1
            }
            50% {
                opacity: .5
            }
        }

        @keyframes pulse {
            0%, to {
                opacity: 1
            }
            50% {
                opacity: .5
            }
        }

        @-webkit-keyframes bounce {
            0%, to {
                transform: translateY(-25%);
                -webkit-animation-timing-function: cubic-bezier(.8, 0, 1, 1);
                animation-timing-function: cubic-bezier(.8, 0, 1, 1)
            }
            50% {
                transform: translateY(0);
                -webkit-animation-timing-function: cubic-bezier(0, 0, .2, 1);
                animation-timing-function: cubic-bezier(0, 0, .2, 1)
            }
        }

        @keyframes bounce {
            0%, to {
                transform: translateY(-25%);
                -webkit-animation-timing-function: cubic-bezier(.8, 0, 1, 1);
                animation-timing-function: cubic-bezier(.8, 0, 1, 1)
            }
            50% {
                transform: translateY(0);
                -webkit-animation-timing-function: cubic-bezier(0, 0, .2, 1);
                animation-timing-function: cubic-bezier(0, 0, .2, 1)
            }
        }

        @media (min-width: 576px) {
            .sm\:items-center {
                align-items: center
            }

            .sm\:justify-start {
                justify-content: flex-start
            }

            .sm\:px-6 {
                padding-left: 1.5rem;
                padding-right: 1.5rem
            }

            .sm\:pt-0 {
                padding-top: 0
            }
        }

        @media (min-width: 992px) {
            .lg\:px-8 {
                padding-left: 2rem;
                padding-right: 2rem
            }
        }

        @media (prefers-color-scheme: dark) {
            .dark\:bg-gray-900 {
                background-color: rgba(26, 32, 44, 1)
            }
        }

        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
    <style>
        .radial-progress {
            position: relative;
            margin: 3em auto 0;
            border-radius: 50%;
            width: 90px;
            height: 90px;
            overflow: hidden;
            box-shadow: 0 0 3px rgba(0, 0, 0, 0.1);
        }

        .radial-progress * {
            transition: none !important;
        }

        .radial-progress .circle-block {
            position: relative;
            background: #e3e3fd;
            background: #18bd18;
            border-radius: 100%;
        }

        .radial-progress .circle-block .fill,
        .radial-progress .circle-block .mask {
            position: absolute;
            border-radius: 50%;
        }

        .radial-progress .circle-block .fill,
        .radial-progress .circle-block .mask {
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            border-radius: 50%;
            transition-property: transform;
            transition-duration: 0.25s;
            transition-timing-function: cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .radial-progress .circle-block .mask {
            clip: rect(0, 90px, 90px, 45px);
        }

        .radial-progress .circle-block .mask .fill {
            clip: rect(0, 45px, 90px, 0);
            background-color: #3c4a65;
        }

        .radial-progress .inset {
            position: absolute;
            background-color: #353535;
            border-radius: 50%;
            top: 0;
        }

        .radial-progress[data-progress="0"] .circle-block .fill,
        .radial-progress[data-progress="0"] .circle-block .mask.full {
            transform: rotate(0deg);
        }

        .radial-progress[data-progress="0"] .circle-block .fill.fix {
            transform: rotate(0deg);
        }

        .radial-progress[data-progress="1"] .circle-block .fill,
        .radial-progress[data-progress="1"] .circle-block .mask.full {
            transform: rotate(3deg);
        }

        .radial-progress[data-progress="1"] .circle-block .fill.fix {
            transform: rotate(6deg);
        }

        .radial-progress[data-progress="2"] .circle-block .fill,
        .radial-progress[data-progress="2"] .circle-block .mask.full {
            transform: rotate(6deg);
        }

        .radial-progress[data-progress="2"] .circle-block .fill.fix {
            transform: rotate(12deg);
        }

        .radial-progress[data-progress="3"] .circle-block .fill,
        .radial-progress[data-progress="3"] .circle-block .mask.full {
            transform: rotate(9deg);
        }

        .radial-progress[data-progress="3"] .circle-block .fill.fix {
            transform: rotate(18deg);
        }

        .radial-progress[data-progress="4"] .circle-block .fill,
        .radial-progress[data-progress="4"] .circle-block .mask.full {
            transform: rotate(12deg);
        }

        .radial-progress[data-progress="4"] .circle-block .fill.fix {
            transform: rotate(24deg);
        }

        .radial-progress[data-progress="5"] .circle-block .fill,
        .radial-progress[data-progress="5"] .circle-block .mask.full {
            transform: rotate(15deg);
        }

        .radial-progress[data-progress="5"] .circle-block .fill.fix {
            transform: rotate(30deg);
        }

        .radial-progress[data-progress="6"] .circle-block .fill,
        .radial-progress[data-progress="6"] .circle-block .mask.full {
            transform: rotate(18deg);
        }

        .radial-progress[data-progress="6"] .circle-block .fill.fix {
            transform: rotate(36deg);
        }

        .radial-progress[data-progress="7"] .circle-block .fill,
        .radial-progress[data-progress="7"] .circle-block .mask.full {
            transform: rotate(21deg);
        }

        .radial-progress[data-progress="7"] .circle-block .fill.fix {
            transform: rotate(42deg);
        }

        .radial-progress[data-progress="8"] .circle-block .fill,
        .radial-progress[data-progress="8"] .circle-block .mask.full {
            transform: rotate(24deg);
        }

        .radial-progress[data-progress="8"] .circle-block .fill.fix {
            transform: rotate(48deg);
        }

        .radial-progress[data-progress="9"] .circle-block .fill,
        .radial-progress[data-progress="9"] .circle-block .mask.full {
            transform: rotate(27deg);
        }

        .radial-progress[data-progress="9"] .circle-block .fill.fix {
            transform: rotate(54deg);
        }

        .radial-progress[data-progress="10"] .circle-block .fill,
        .radial-progress[data-progress="10"] .circle-block .mask.full {
            transform: rotate(30deg);
        }

        .radial-progress[data-progress="10"] .circle-block .fill.fix {
            transform: rotate(60deg);
        }

        .radial-progress[data-progress="11"] .circle-block .fill,
        .radial-progress[data-progress="11"] .circle-block .mask.full {
            transform: rotate(33deg);
        }

        .radial-progress[data-progress="11"] .circle-block .fill.fix {
            transform: rotate(66deg);
        }

        .radial-progress[data-progress="12"] .circle-block .fill,
        .radial-progress[data-progress="12"] .circle-block .mask.full {
            transform: rotate(36deg);
        }

        .radial-progress[data-progress="12"] .circle-block .fill.fix {
            transform: rotate(72deg);
        }

        .radial-progress[data-progress="13"] .circle-block .fill,
        .radial-progress[data-progress="13"] .circle-block .mask.full {
            transform: rotate(39deg);
        }

        .radial-progress[data-progress="13"] .circle-block .fill.fix {
            transform: rotate(78deg);
        }

        .radial-progress[data-progress="14"] .circle-block .fill,
        .radial-progress[data-progress="14"] .circle-block .mask.full {
            transform: rotate(42deg);
        }

        .radial-progress[data-progress="14"] .circle-block .fill.fix {
            transform: rotate(84deg);
        }

        .radial-progress[data-progress="15"] .circle-block .fill,
        .radial-progress[data-progress="15"] .circle-block .mask.full {
            transform: rotate(45deg);
        }

        .radial-progress[data-progress="15"] .circle-block .fill.fix {
            transform: rotate(90deg);
        }

        .radial-progress[data-progress="16"] .circle-block .fill,
        .radial-progress[data-progress="16"] .circle-block .mask.full {
            transform: rotate(48deg);
        }

        .radial-progress[data-progress="16"] .circle-block .fill.fix {
            transform: rotate(96deg);
        }

        .radial-progress[data-progress="17"] .circle-block .fill,
        .radial-progress[data-progress="17"] .circle-block .mask.full {
            transform: rotate(51deg);
        }

        .radial-progress[data-progress="17"] .circle-block .fill.fix {
            transform: rotate(102deg);
        }

        .radial-progress[data-progress="18"] .circle-block .fill,
        .radial-progress[data-progress="18"] .circle-block .mask.full {
            transform: rotate(54deg);
        }

        .radial-progress[data-progress="18"] .circle-block .fill.fix {
            transform: rotate(108deg);
        }

        .radial-progress[data-progress="19"] .circle-block .fill,
        .radial-progress[data-progress="19"] .circle-block .mask.full {
            transform: rotate(57deg);
        }

        .radial-progress[data-progress="19"] .circle-block .fill.fix {
            transform: rotate(114deg);
        }

        .radial-progress[data-progress="20"] .circle-block .fill,
        .radial-progress[data-progress="20"] .circle-block .mask.full {
            transform: rotate(60deg);
        }

        .radial-progress[data-progress="20"] .circle-block .fill.fix {
            transform: rotate(120deg);
        }

        .radial-progress[data-progress="21"] .circle-block .fill,
        .radial-progress[data-progress="21"] .circle-block .mask.full {
            transform: rotate(63deg);
        }

        .radial-progress[data-progress="21"] .circle-block .fill.fix {
            transform: rotate(126deg);
        }

        .radial-progress[data-progress="22"] .circle-block .fill,
        .radial-progress[data-progress="22"] .circle-block .mask.full {
            transform: rotate(66deg);
        }

        .radial-progress[data-progress="22"] .circle-block .fill.fix {
            transform: rotate(132deg);
        }

        .radial-progress[data-progress="23"] .circle-block .fill,
        .radial-progress[data-progress="23"] .circle-block .mask.full {
            transform: rotate(69deg);
        }

        .radial-progress[data-progress="23"] .circle-block .fill.fix {
            transform: rotate(138deg);
        }

        .radial-progress[data-progress="24"] .circle-block .fill,
        .radial-progress[data-progress="24"] .circle-block .mask.full {
            transform: rotate(72deg);
        }

        .radial-progress[data-progress="24"] .circle-block .fill.fix {
            transform: rotate(144deg);
        }

        .radial-progress[data-progress="25"] .circle-block .fill,
        .radial-progress[data-progress="25"] .circle-block .mask.full {
            transform: rotate(75deg);
        }

        .radial-progress[data-progress="25"] .circle-block .fill.fix {
            transform: rotate(150deg);
        }

        .radial-progress[data-progress="26"] .circle-block .fill,
        .radial-progress[data-progress="26"] .circle-block .mask.full {
            transform: rotate(78deg);
        }

        .radial-progress[data-progress="26"] .circle-block .fill.fix {
            transform: rotate(156deg);
        }

        .radial-progress[data-progress="27"] .circle-block .fill,
        .radial-progress[data-progress="27"] .circle-block .mask.full {
            transform: rotate(81deg);
        }

        .radial-progress[data-progress="27"] .circle-block .fill.fix {
            transform: rotate(162deg);
        }

        .radial-progress[data-progress="28"] .circle-block .fill,
        .radial-progress[data-progress="28"] .circle-block .mask.full {
            transform: rotate(84deg);
        }

        .radial-progress[data-progress="28"] .circle-block .fill.fix {
            transform: rotate(168deg);
        }

        .radial-progress[data-progress="29"] .circle-block .fill,
        .radial-progress[data-progress="29"] .circle-block .mask.full {
            transform: rotate(87deg);
        }

        .radial-progress[data-progress="29"] .circle-block .fill.fix {
            transform: rotate(174deg);
        }

        .radial-progress[data-progress="30"] .circle-block .fill,
        .radial-progress[data-progress="30"] .circle-block .mask.full {
            transform: rotate(90deg);
        }

        .radial-progress[data-progress="30"] .circle-block .fill.fix {
            transform: rotate(180deg);
        }

        .radial-progress[data-progress="31"] .circle-block .fill,
        .radial-progress[data-progress="31"] .circle-block .mask.full {
            transform: rotate(93deg);
        }

        .radial-progress[data-progress="31"] .circle-block .fill.fix {
            transform: rotate(186deg);
        }

        .radial-progress[data-progress="32"] .circle-block .fill,
        .radial-progress[data-progress="32"] .circle-block .mask.full {
            transform: rotate(96deg);
        }

        .radial-progress[data-progress="32"] .circle-block .fill.fix {
            transform: rotate(192deg);
        }

        .radial-progress[data-progress="33"] .circle-block .fill,
        .radial-progress[data-progress="33"] .circle-block .mask.full {
            transform: rotate(99deg);
        }

        .radial-progress[data-progress="33"] .circle-block .fill.fix {
            transform: rotate(198deg);
        }

        .radial-progress[data-progress="34"] .circle-block .fill,
        .radial-progress[data-progress="34"] .circle-block .mask.full {
            transform: rotate(102deg);
        }

        .radial-progress[data-progress="34"] .circle-block .fill.fix {
            transform: rotate(204deg);
        }

        .radial-progress[data-progress="35"] .circle-block .fill,
        .radial-progress[data-progress="35"] .circle-block .mask.full {
            transform: rotate(105deg);
        }

        .radial-progress[data-progress="35"] .circle-block .fill.fix {
            transform: rotate(210deg);
        }

        .radial-progress[data-progress="36"] .circle-block .fill,
        .radial-progress[data-progress="36"] .circle-block .mask.full {
            transform: rotate(108deg);
        }

        .radial-progress[data-progress="36"] .circle-block .fill.fix {
            transform: rotate(216deg);
        }

        .radial-progress[data-progress="37"] .circle-block .fill,
        .radial-progress[data-progress="37"] .circle-block .mask.full {
            transform: rotate(111deg);
        }

        .radial-progress[data-progress="37"] .circle-block .fill.fix {
            transform: rotate(222deg);
        }

        .radial-progress[data-progress="38"] .circle-block .fill,
        .radial-progress[data-progress="38"] .circle-block .mask.full {
            transform: rotate(114deg);
        }

        .radial-progress[data-progress="38"] .circle-block .fill.fix {
            transform: rotate(228deg);
        }

        .radial-progress[data-progress="39"] .circle-block .fill,
        .radial-progress[data-progress="39"] .circle-block .mask.full {
            transform: rotate(117deg);
        }

        .radial-progress[data-progress="39"] .circle-block .fill.fix {
            transform: rotate(234deg);
        }

        .radial-progress[data-progress="40"] .circle-block .fill,
        .radial-progress[data-progress="40"] .circle-block .mask.full {
            transform: rotate(120deg);
        }

        .radial-progress[data-progress="40"] .circle-block .fill.fix {
            transform: rotate(240deg);
        }

        .radial-progress[data-progress="41"] .circle-block .fill,
        .radial-progress[data-progress="41"] .circle-block .mask.full {
            transform: rotate(123deg);
        }

        .radial-progress[data-progress="41"] .circle-block .fill.fix {
            transform: rotate(246deg);
        }

        .radial-progress[data-progress="42"] .circle-block .fill,
        .radial-progress[data-progress="42"] .circle-block .mask.full {
            transform: rotate(126deg);
        }

        .radial-progress[data-progress="42"] .circle-block .fill.fix {
            transform: rotate(252deg);
        }

        .radial-progress[data-progress="43"] .circle-block .fill,
        .radial-progress[data-progress="43"] .circle-block .mask.full {
            transform: rotate(129deg);
        }

        .radial-progress[data-progress="43"] .circle-block .fill.fix {
            transform: rotate(258deg);
        }

        .radial-progress[data-progress="44"] .circle-block .fill,
        .radial-progress[data-progress="44"] .circle-block .mask.full {
            transform: rotate(132deg);
        }

        .radial-progress[data-progress="44"] .circle-block .fill.fix {
            transform: rotate(264deg);
        }

        .radial-progress[data-progress="45"] .circle-block .fill,
        .radial-progress[data-progress="45"] .circle-block .mask.full {
            transform: rotate(135deg);
        }

        .radial-progress[data-progress="45"] .circle-block .fill.fix {
            transform: rotate(270deg);
        }

        .radial-progress[data-progress="46"] .circle-block .fill,
        .radial-progress[data-progress="46"] .circle-block .mask.full {
            transform: rotate(138deg);
        }

        .radial-progress[data-progress="46"] .circle-block .fill.fix {
            transform: rotate(276deg);
        }

        .radial-progress[data-progress="47"] .circle-block .fill,
        .radial-progress[data-progress="47"] .circle-block .mask.full {
            transform: rotate(141deg);
        }

        .radial-progress[data-progress="47"] .circle-block .fill.fix {
            transform: rotate(282deg);
        }

        .radial-progress[data-progress="48"] .circle-block .fill,
        .radial-progress[data-progress="48"] .circle-block .mask.full {
            transform: rotate(144deg);
        }

        .radial-progress[data-progress="48"] .circle-block .fill.fix {
            transform: rotate(288deg);
        }

        .radial-progress[data-progress="49"] .circle-block .fill,
        .radial-progress[data-progress="49"] .circle-block .mask.full {
            transform: rotate(147deg);
        }

        .radial-progress[data-progress="49"] .circle-block .fill.fix {
            transform: rotate(294deg);
        }

        .radial-progress[data-progress="50"] .circle-block .fill,
        .radial-progress[data-progress="50"] .circle-block .mask.full {
            transform: rotate(150deg);
        }

        .radial-progress[data-progress="50"] .circle-block .fill.fix {
            transform: rotate(300deg);
        }

        .radial-progress[data-progress="51"] .circle-block .fill,
        .radial-progress[data-progress="51"] .circle-block .mask.full {
            transform: rotate(153deg);
        }

        .radial-progress[data-progress="51"] .circle-block .fill.fix {
            transform: rotate(306deg);
        }

        .radial-progress[data-progress="52"] .circle-block .fill,
        .radial-progress[data-progress="52"] .circle-block .mask.full {
            transform: rotate(156deg);
        }

        .radial-progress[data-progress="52"] .circle-block .fill.fix {
            transform: rotate(312deg);
        }

        .radial-progress[data-progress="53"] .circle-block .fill,
        .radial-progress[data-progress="53"] .circle-block .mask.full {
            transform: rotate(159deg);
        }

        .radial-progress[data-progress="53"] .circle-block .fill.fix {
            transform: rotate(318deg);
        }

        .radial-progress[data-progress="54"] .circle-block .fill,
        .radial-progress[data-progress="54"] .circle-block .mask.full {
            transform: rotate(162deg);
        }

        .radial-progress[data-progress="54"] .circle-block .fill.fix {
            transform: rotate(324deg);
        }

        .radial-progress[data-progress="55"] .circle-block .fill,
        .radial-progress[data-progress="55"] .circle-block .mask.full {
            transform: rotate(165deg);
        }

        .radial-progress[data-progress="55"] .circle-block .fill.fix {
            transform: rotate(330deg);
        }

        .radial-progress[data-progress="56"] .circle-block .fill,
        .radial-progress[data-progress="56"] .circle-block .mask.full {
            transform: rotate(168deg);
        }

        .radial-progress[data-progress="56"] .circle-block .fill.fix {
            transform: rotate(336deg);
        }

        .radial-progress[data-progress="57"] .circle-block .fill,
        .radial-progress[data-progress="57"] .circle-block .mask.full {
            transform: rotate(171deg);
        }

        .radial-progress[data-progress="57"] .circle-block .fill.fix {
            transform: rotate(342deg);
        }

        .radial-progress[data-progress="58"] .circle-block .fill,
        .radial-progress[data-progress="58"] .circle-block .mask.full {
            transform: rotate(174deg);
        }

        .radial-progress[data-progress="58"] .circle-block .fill.fix {
            transform: rotate(348deg);
        }

        .radial-progress[data-progress="59"] .circle-block .fill,
        .radial-progress[data-progress="59"] .circle-block .mask.full {
            transform: rotate(177deg);
        }

        .radial-progress[data-progress="59"] .circle-block .fill.fix {
            transform: rotate(354deg);
        }

        .radial-progress[data-progress="60"] .circle-block .fill,
        .radial-progress[data-progress="60"] .circle-block .mask.full {
            transform: rotate(180deg);
        }

        .radial-progress[data-progress="60"] .circle-block .fill.fix {
            transform: rotate(360deg);
        }

        .radial-progress,
        .radial-progress .circle-block,
        .radial-progress .circle-block .fill,
        .radial-progress .circle-block .mask {
            width: 90px;
            height: 90px;
        }

        .radial-progress .circle-block .mask {
            clip: rect(0, 90px, 90px, 45px);
        }

        .radial-progress .circle-block .mask .fill {
            clip: rect(0, 45px, 90px, 0);
        }

        .radial-progress .inset {
            width: 66px;
            height: 66px;
            margin-left: 12px;
            margin-top: 12px;
            background: #fff;
            background: rgba(160, 174, 192, 1);
            box-shadow: 1px 1px 5px rgba(0, 0, 0, 0.5);
            font-size: 26px;
            color: #1A202C;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            line-height: 1;
        }

        .radial-progress .inset span {
            display: block;
            font-size: 10px;
        }
    </style>
</head>
<body class="antialiased">
<div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
    <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
        <div class="flex items-start pt-8 sm:justify-start sm:pt-0">
            <div class="px-4 text-lg text-gray-500 border-r border-gray-400 tracking-wider mono">
                503
            </div>
            <div class="ml-4 text-lg text-gray-500 -uppercase tracking-wider">
                Привет! Прямо сейчас мы обновляем сайт.<br>
                Обычно это занимает не более минуты.
            </div>
        </div>

        <div class="flex items-center pt-8 sm:justify-start sm:pt-0">
            <div data-progress="0" class="radial-progress progress" id="progress">
                <div class="circle-block">
                    <div class="mask full">
                        <div class="fill"></div>
                    </div>
                    <div class="mask half">
                        <div class="fill"></div>
                        <div class="fill fix"></div>
                    </div>
                </div>
                <div class="inset" id="digit">0</div>
            </div>
        </div>
    </div>
</div>

<script>
    const progress = document.getElementById('progress');
    const digit = document.getElementById('digit');
    let timer = 60;

    function counter() {
        progress.setAttribute('data-progress', timer.toString());
        digit.innerText = timer.toString();
        timer--;
        if (timer < 0) {
            timer = 0;
            location.reload();
        }
    }

    counter();

    setInterval(counter, 40 / 60 * 1000);
</script>
</body>
</html>
