<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023.
 */

use Nwidart\Modules\Activators\FileActivator;
use Nwidart\Modules\Commands;

return [

    /*
    |--------------------------------------------------------------------------
    | Module Namespace
    |--------------------------------------------------------------------------
    | Default module namespace.
    */

    'namespace' => 'Modules',

    /*
    |--------------------------------------------------------------------------
    | Module Stubs
    |--------------------------------------------------------------------------
    | Default module stubs.
    */

    'stubs' => [
        'enabled'      => false,
        'path'         => base_path('stubs/nwidart-stubs'),
        'files'        => [
            'routes/web'      => 'Routes/web.php',
            'routes/admin'    => 'Routes/admin.php',
            'routes/api'      => 'Routes/api.php',
            'views/index'     => 'Resources/views/index.blade.php',
            'views/master'    => 'Resources/views/layouts/master.blade.php',
            'scaffold/config' => 'Config/config.php',
            // 'composer'        => 'composer.json',
            // 'assets/js/app'   => 'Resources/assets/js/app.js',
            // 'assets/sass/app' => 'Resources/assets/sass/app.scss',
            // 'vite'            => 'vite.config.js',
            // 'package'         => 'package.json',
        ],
        'replacements' => [
            'routes/web'      => ['LOWER_NAME', 'STUDLY_NAME'],
            'routes/admin'    => ['LOWER_NAME', 'STUDLY_NAME'],
            'routes/api'      => ['LOWER_NAME'],
            'vite'            => ['LOWER_NAME'],
            'json'            => ['LOWER_NAME', 'STUDLY_NAME', 'MODULE_NAMESPACE', 'PROVIDER_NAMESPACE'],
            'views/index'     => ['LOWER_NAME'],
            'views/master'    => ['LOWER_NAME', 'STUDLY_NAME'],
            'scaffold/config' => ['STUDLY_NAME'],
            'composer'        => [
                'LOWER_NAME',
                'STUDLY_NAME',
                'VENDOR',
                'AUTHOR_NAME',
                'AUTHOR_EMAIL',
                'MODULE_NAMESPACE',
                'PROVIDER_NAMESPACE',
            ],
        ],
        'gitkeep'      => false,
    ],
    'paths' => [

        /*
        |--------------------------------------------------------------------------
        | Modules path
        |--------------------------------------------------------------------------
        | This path used for save the generated module. This path also will be added
        | automatically to list of scanned folders.
        */

        'modules' => base_path('Modules'),

        /*
        |--------------------------------------------------------------------------
        | Modules assets path
        |--------------------------------------------------------------------------
        | Here you may update the modules assets path.
        */

        'assets' => public_path('modules'),

        /*
        |--------------------------------------------------------------------------
        | The migrations path
        |--------------------------------------------------------------------------
        | Where you run 'module:publish-migration' command, where do you publish the
        | the migration files?
        */

        'migration' => base_path('database/migrations'),

        /*
        |--------------------------------------------------------------------------
        | Generator path
        |--------------------------------------------------------------------------
        | Customise the paths where the folders will be generated.
        | Set the generate key to false to not generate that folder
        */

        'generator' => [
            'config'          => ['generate' => true, 'path' => 'Config'],
            'command'         => ['generate' => false, 'path' => 'Console'],
            'migration'       => ['generate' => true, 'path' => 'Database/Migrations'],
            'seeder'          => ['generate' => true, 'path' => 'Database/Seeders'],
            'factory'         => ['generate' => true, 'path' => 'Database/factories'],
            'model'           => ['generate' => false, 'path' => 'Entities'],
            'routes'          => ['generate' => true, 'path' => 'Routes'],
            'controller'      => ['generate' => true, 'path' => 'Http/Controllers'],
            'filter'          => ['generate' => false, 'path' => 'Http/Middleware'],
            'request'         => ['generate' => true, 'path' => 'Http/Requests'],
            'provider'        => ['generate' => true, 'path' => 'Providers'],
            'assets'          => ['generate' => false, 'path' => 'Resources/assets'],
            'lang'            => ['generate' => true, 'path' => 'Resources/lang'],
            'views'           => ['generate' => true, 'path' => 'Resources/views'],
            'test'            => ['generate' => false, 'path' => 'Tests/Unit'],
            'test-feature'    => ['generate' => false, 'path' => 'Tests/Feature'],
            'repository'      => ['generate' => false, 'path' => 'Repositories'],
            'event'           => ['generate' => false, 'path' => 'Events'],
            'listener'        => ['generate' => false, 'path' => 'Listeners'],
            'policies'        => ['generate' => false, 'path' => 'Policies'],
            'rules'           => ['generate' => false, 'path' => 'Rules'],
            'jobs'            => ['generate' => false, 'path' => 'Jobs'],
            'emails'          => ['generate' => false, 'path' => 'Emails'],
            'notifications'   => ['generate' => false, 'path' => 'Notifications'],
            'resource'        => ['generate' => false, 'path' => 'Transformers'],
            'component-view'  => ['generate' => false, 'path' => 'Resources/views/components'],
            'component-class' => ['generate' => false, 'path' => 'View/Components'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Package commands
    |--------------------------------------------------------------------------
    | Here you can define which commands will be visible and used in your
    | application. If for example you don't use some of the commands provided
    | you can simply comment them out.
    */

    'commands' => [
        // Commands\CommandMakeCommand::class,
        // Commands\ComponentClassMakeCommand::class,
        // Commands\ComponentViewMakeCommand::class,
        Commands\ControllerMakeCommand::class,
        // Commands\DisableCommand::class,
        // Commands\DumpCommand::class,
        // Commands\EnableCommand::class,
        // Commands\EventMakeCommand::class,
        // Commands\FactoryMakeCommand::class,
        // Commands\InstallCommand::class,
        // Commands\JobMakeCommand::class,
        // Commands\LaravelModulesV6Migrator::class,
        // Commands\ListCommand::class,
        // Commands\ListenerMakeCommand::class,
        // Commands\MailMakeCommand::class,
        // Commands\MiddlewareMakeCommand::class,
        // Commands\MigrateCommand::class,
        // Commands\MigrateFreshCommand::class,
        // Commands\MigrateRefreshCommand::class,
        // Commands\MigrateResetCommand::class,
        // Commands\MigrateRollbackCommand::class,
        // Commands\MigrateStatusCommand::class,
        // Commands\MigrationMakeCommand::class,
        // Commands\ModelMakeCommand::class,
        // Commands\ModuleDeleteCommand::class,
        Commands\ModuleMakeCommand::class,
        // Commands\NotificationMakeCommand::class,
        // Commands\PolicyMakeCommand::class,
        Commands\ProviderMakeCommand::class,
        // Commands\PublishCommand::class,
        // Commands\PublishConfigurationCommand::class,
        // Commands\PublishMigrationCommand::class,
        // Commands\PublishTranslationCommand::class,
        // Commands\RequestMakeCommand::class,
        // Commands\ResourceMakeCommand::class,
        Commands\RouteProviderMakeCommand::class,
        // Commands\RuleMakeCommand::class,
        // Commands\SeedCommand::class,
        Commands\SeedMakeCommand::class,
        // Commands\SetupCommand::class,
        // Commands\TestMakeCommand::class,
        // Commands\UnUseCommand::class,
        // Commands\UpdateCommand::class,
        // Commands\UseCommand::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Scan Path
    |--------------------------------------------------------------------------
    | Here you define which folder will be scanned. By default will scan vendor
    | directory. This is useful if you host the package in packagist website.
    */

    'scan' => [
        'enabled' => false,
        'paths'   => [
            base_path('vendor/*/*'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Composer File Template
    |--------------------------------------------------------------------------
    | Here is the config for composer.json file, generated by this package
    */

    'composer' => [
        'vendor'          => 'nwidart',
        'author'          => [
            'name'  => 'Nicolas Widart',
            'email' => 'n.widart@gmail.com',
        ],
        'composer-output' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Caching
    |--------------------------------------------------------------------------
    | Here is the config for setting up caching feature.
    */

    'cache' => [
        'enabled'  => false,
        'driver'   => 'file',
        'key'      => 'laravel-modules',
        'lifetime' => 60,
    ],

    /*
    |--------------------------------------------------------------------------
    | Choose what laravel-modules will register as custom namespaces.
    | Setting one to false will require you to register that part
    | in your own Service Provider class.
    |--------------------------------------------------------------------------
    */

    'register' => [
        'translations' => true,
        /**
         * Load files on boot or register method
         *
         * Note: boot not compatible with asgardcms
         *
         * @example boot|register
         */
        'files'        => 'register',
    ],

    /*
    |--------------------------------------------------------------------------
    | Activators
    |--------------------------------------------------------------------------
    | You can define new types of activators here, file, database etc. The only
    | required parameter is 'class'.
    | The file activator will store the activation status in storage/installed_modules
    */

    'activators' => [
        'file' => [
            'class'          => FileActivator::class,
            'statuses-file'  => base_path('modules_statuses.json'),
            'cache-key'      => 'activator.installed',
            'cache-lifetime' => 604800,
        ],
    ],

    'activator' => 'file',
];
