<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023.
 */

return [
    'enabled'       => env('TRACY_ENABLED', false),
    'showBar'       => env('TRACY_BAR', env('TRACY_ENABLED', false)),
    'showException' => env('TRACY_EXCEPTION', false),
    'route'         => [
        'prefix' => 'tracy',
        'as'     => 'tracy.',
    ],
    'accepts'       => [
        'text/html',
    ],
    'appendTo'      => 'body',
    'editor'        => env('TRACY_EDITOR', 'subl://open?url=file://%file&line=%line'),
    'maxDepth'      => env('TRACY_MAX_DEPTH', 4),
    'maxLength'     => env('TRACY_MAX_LENGTH', 1000),
    'scream'        => env('TRACY_SCREAM', true),
    'showLocation'  => env('TRACY_SHOW_LOCATION', true),
    'strictMode'    => env('TRACY_STRICT_MODE', true),
    'editorMapping' => [],
    'panels'        => [
        'routing'        => env('TRACY_PANEL_ROUTING', true),
        'database'       => env('TRACY_PANEL_DATABASE', true),
        'view'           => env('TRACY_PANEL_VIEW', true),
        'event'          => env('TRACY_PANEL_EVENT', false),
        'session'        => env('TRACY_PANEL_SESSION', true),
        'request'        => env('TRACY_PANEL_REQUEST', true),
        'auth'           => env('TRACY_PANEL_AUTH', true),
        'html-validator' => env('TRACY_PANEL_HTML_VALIDATOR', false),
        'terminal'       => env('TRACY_PANEL_TERMINAL', false),
    ],
];
