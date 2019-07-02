/*!
 * gulp-starter
 * User functions for twig compiler
 * (c) 2016-2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

const fs          = require('fs');
const path        = require('path');
const config      = require('../../gulp.config.js');
const DEVELOPMENT = require('../lib/checkMode').isDevelopment();

// take from php.js library
function uniqueId(prefix, moreEntropy) {
    let retId;

    moreEntropy = !!moreEntropy;

    const formatSeed = function (seed, reqWidth) {
        seed = parseInt(seed, 10).toString(16);
        if (reqWidth < seed.length) {
            return seed.slice(seed.length - reqWidth);
        }
        if (reqWidth > seed.length) {
            return Array(1 + (reqWidth - seed.length)).join('0') + seed;
        }
        return seed;
    };

    if (!this.php_js) {
        this.php_js = {};
    }

    if (!this.php_js.uniqidSeed) {
        this.php_js.uniqidSeed = Math.floor(Math.random() * 0x75bcd15);
    }

    this.php_js.uniqidSeed++;

    retId = prefix || '';
    retId += formatSeed(parseInt(new Date().getTime() / 1000, 10), 8);
    retId += formatSeed(this.php_js.uniqidSeed, 5);

    if (moreEntropy) { /* *** */
        retId += (Math.random() * 10).toFixed(8).toString();
    }

    return retId;
}

function pixelPerfect(points) {
    if (typeof points !== 'object') {
        console.warn('Invalid param type for pixelPerfect()');
        return '';
    }

    let bp;
    let s = `\n<!-- Pixel Perfect. Удалить эти стили. Нужны только для верстальщика! -->\n<style>\n`;

    for (bp of Object.keys(points)) {
        if (bp === '_keys') continue;
        s += `@media(min-width:${bp}){
    html[data-on="1"]{background:url("${points[bp]}") no-repeat 50% 0;}
    html[data-on="1"] body{opacity:0.5}
    html[data-on="1"][data-invert="1"] body{filter:invert(100%)}
}\n`;
    }

    s += `</style><!-- // Pixel Perfect -->\n`;

    return s;
}

function includeStylesheet(filename, suffix = '.min') {
    filename = DEVELOPMENT ? filename : filename + suffix;
    return `<link href="/${config.scss.dest.replace(/\\/g,
        '/')}/${filename}.css?v=${uniqueId()}"  rel="stylesheet">`;
}

function includeJavascript(filename, suffix = '.min') {
    filename = DEVELOPMENT ? filename : filename + suffix;
    return `<script src="/${config.webpack.build.replace(/\\/g,
        '/')}/${filename}.js?v=${uniqueId()}"></script>`;
}

let nocacheValue = uniqueId();

function nocache(file) {
    return `${file}?v=${nocacheValue}`;
}

function nocachecss(file) {
    return `<link href="${nocache(file)}"  rel="stylesheet">`;
}

function nocachejs(file) {
    return `<script src="${nocache(file)}"></script>`;
}

function triplets(number) {
    // \u202f — неразрывный узкий пробел
    return number.toString().replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1\u202f');
}

function insertDevTools() {
    return `<!-- BEGIN: Только для режима разработки, удалить при интеграции в CMS -->
<script src="/${config.devTools.scriptDest}/${config.devTools.scriptName}"></script>
<!-- END -->
`;
}

function svgIcon(icon, customClass = '', baseClass = null) {
    const iconClass = baseClass || config.sprite.svg.iconClass || 'i';
    return `<svg class="${customClass} ${iconClass} ${iconClass}-${icon}">` +
        `<use xlink:href="${config.svgSpritePath}${config.svgSpriteName}#${icon}"></use>` +
        `</svg>`;
}

module.exports.functions = [
    {name: 'pixelPerfect', func: pixelPerfect},
    {name: 'uniqueId', func: uniqueId},
    {name: 'includeStylesheet', func: includeStylesheet},
    {name: 'includeJavascript', func: includeJavascript},
    {name: 'nocache', func: nocache},
    {name: 'nocachecss', func: nocachecss},
    {name: 'nocachejs', func: nocachejs},
    {name: 'triplets', func: triplets},
    {name: 'insertTools', func: insertDevTools},
    {name: 'svgIcon', func: svgIcon},
];

module.exports.loadData = function () {
    const dataPath = path.resolve(config.root.src, config.twig.dataFile);

    return {
        system: {
            development: DEVELOPMENT,
            options    : config,
            config    : config,
        },
        ...JSON.parse(fs.readFileSync(dataPath, 'utf8')),
    };
};

module.exports.extendFunction = function (Twig) {
    Twig.exports.extendTag({
        type : 'srccode',
        regex: /^srccode$/,
        next : ['endsrccode'],

        open: true,

        compile: function (token) {
            var expression = token.match[0];

            token.stack = Twig.expression.compile.apply(this, [
                {
                    type : Twig.expression.type[expression],
                    value: expression,
                },
            ]).stack;

            delete token.match;
            return token;
        },

        parse: function (token, context, chain) {
            //let level  = Twig.expression.parse.apply(this, [token.stack, context]);
            let output = '';

            let s   = token.output[0].value;
            let arr = s.split('\n')
                .map(item => item.replace(/\r/g, ''))
                .filter(item => item.trim());
            let len = 999;
            arr.forEach(item => {
                let l = item.length - item.trimLeft().length;
                if (l < len) len = l;
            });
            arr = arr.map(item => {
                let trimmed = item.trimLeft();
                let l       = item.length - trimmed.length;
                return trimmed.padStart(l - len + trimmed.length);
            });
            s   = arr.join('\r\n');

            token.output[0].value =
                '<pre class="demo-src-code">' +
                // token.output[0].value
                s
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                + '</pre>'
            ;
            // console.log('out', token.output);
            output                = Twig.parse.apply(this, [token.output, context]);

            return {
                chain : chain,
                output: output,
            };
        },
    });

    Twig.exports.extendTag({
        type : 'endsrccode',
        regex: /^endsrccode$/,
        next : [],
        open : false,
    });
};
