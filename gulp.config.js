/*!
 * gulp-starter
 * Main configuration
 * (c) 2017-2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

'use strict';

/*==
 *== Main settings
 *== ===================================================================== ==*/

const _serverPort     = 3000;
const _useProxy       = true;
const _localDomain    = 'delphinpro.mysite';
const _startPath      = '/';
const _browsers       = ['chrome'];
const _reloadDebounce = 300;

const root = {
    main  : __dirname,
    src   : 'source',
    build : 'public',
    dist  : 'public/design/dist',
    static: 'design',
    temp  : '.tmp',
};

const dist = [];

let config = {
    root,
    dist,
    svgSpriteName: 'sprite.svg',
    svgSpritePath: '/design/',
    sprite       : {},
};

/*==
 *== Task lists
 *== ===================================================================== ==*/

config.watchableTasks = [
    // 'twig',
    'scss',
    // 'javascript',
    'images',
    'fonts',
    'copy',
];

/*==
 *== Custom watch
 *== ===================================================================== ==*/

config.watchCustom = [
    root.build + '/views/**/*.blade.php',
];

/*==
 *== Sass build settings
 *== ===================================================================== ==*/

config.scss = {
    src        : 'sass',
    dest       : 'css',
    extensions : ['scss'],
    sassOptions: {
        outputStyle: 'compressed', // In production mode. In development mode always is 'nested'
    },
    resolveUrl : {
        source     : '/source/',
        replacement: '../',
    },
};

/*==
 *== Autoprefixer settings
 *== ===================================================================== ==*/

// Moved to .browserslistrc

/*==
 *== Main javascript build settings
 *== ===================================================================== ==*/

config.javascript = {
    src       : 'js',
    files     : [
        'header.js',
        'helpers/*.js',
        'plugins/*.js',
        'components/*.js',
        'parts/*.js',
        'main.js',
    ],
    build     : `${root.static}/js`,
    extensions: ['js'],
    outputName: 'main.js',

    use: {
        vue   : false,
        jquery: true,
    },
};

/*==
 *== Javascript processing of webpack
 *== ===================================================================== ==*/

config.webpack = {
    src  : 'js',
    dest : 'js',
    entry: {
        main: './source/js/main.js',
    },
};

/*==
 *== Twig processing settings for templates
 *== ===================================================================== ==*/

config.twig = {
    src       : 'twig',
    build     : '',
    extensions: ['twig', 'html', 'json'],

    dataFile      : 'twig/layouts/data.json',
    excludeFolders: ['layouts', 'parts'],
    resolveUrl    : {
        source     : '/source/',
        replacement: '/design/',
    },
};

/*==
 *== Image optimization settings
 *== ===================================================================== ==*/

config.images = {
    src       : 'images',
    build     : `${root.static}/images`,
    extensions: ['jpg', 'png', 'svg', 'gif'],
};

/*==
 *== Sprite generation settings
 *== ===================================================================== ==*/

config.sprite.svg = {
    src        : 'sprites/svg',
    build      : root.static,
    extensions : 'svg',
    dest       : config.svgSpriteName,
    previewPath: '../../source/twig/parts/ui/_sprites.twig',
    iconClass  : 'i',
};

/*==
 *== Font copy settings
 *== ===================================================================== ==*/

config.fonts = {
    src       : 'fonts',
    build     : `${root.static}/fonts`,
    extensions: ['woff2', 'woff', 'eot', 'ttf', 'svg'],
};

/*==
 *== Cleaning settings
 *==
 *== root    Начальная (корневая) директория для удаления файлов и папок
 *==         По умолчанию равна config.root.build
 *== exclude Директории без оконечных слешей, слеш прямой, от root
 *==         Одиночные файлы
 *==
 *== Можно задавать массив объектов для очистки нескольких директорий
 *==         [{root:'/dir1/',exclude:[]}, {root:'/docs/',exclude:[]}]
 *== ===================================================================== ==*/

config.cleaning = [
    {
        root   : '/design/',
        exclude: [],
    },
];

/*==
 *== Copy settings
 *== ===================================================================== ==*/

config.copy = [
    { src: 'source/assets/**' },
];

/*==
 *== Frontend tools settings
 *== ===================================================================== ==*/

config.devTools = {
    srcMain: [
        // 'gulp/dev-tools/index.php',
        // 'gulp/dev-tools/robots.txt',
    ],

    destMain: 'public',

    scriptDest: `${root.static}/js`,
    scriptName: `dev.js`,

    overrideScss: [
        'source/sass/config/_bs-grid-config.scss',
        'node_modules/bs-grid-system/bs-grid.scss',
        'source/sass/config/_vrhythm.scss',
        'node_modules/vrhythm/source/mixins/_rhythm.scss',
    ],
};

/*==
 *== BrowserSync settings
 *== http://www.browsersync.io/docs/options/
 *== ===================================================================== ==*/

config.browserSync = {
    instanceName: 'delphinpro',
    options     : {
        browser        : _browsers,
        notify         : true,
        startPath      : _startPath,
        port           : _serverPort,
        reloadDebounce : _reloadDebounce,
        reloadOnRestart: true,
        ghostMode      : {
            clicks: false,
            forms : true,
            scroll: false,
        },
    },
};

if (_useProxy) {
    config.browserSync.options.proxy = _localDomain;
} else {
    config.browserSync.options.server = {
        baseDir  : root.build,
        directory: true,
        index    : _startPath,
    };
}

module.exports = config;
