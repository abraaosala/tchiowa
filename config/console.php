<?php

declare(strict_types=1);

return [
    'paths' => [
        'controllers' => 'app/Http/Controllers',
        'models' => 'app/Infrastructure/Persistence/Eloquent',
        'repositories' => 'app/Infrastructure/Persistence/Repositories',
        'services' => 'app/Application/Services',
        'middlewares' => 'app/Http/Middleware',
        'views' => 'resources/views',
        'migrations' => 'database/migrations',
        'seeds' => 'database/seeds',
        'stubs' => 'stubs',
    ],
    'namespaces' => [
        'controllers' => 'App\\Http\\Controllers',
        'models' => 'App\\Infrastructure\\Persistence\\Eloquent',
        'repositories' => 'App\\Infrastructure\\Persistence\\Repositories',
        'services' => 'App\\Application\\Services',
        'middlewares' => 'App\\Http\\Middleware',
    ],
    'suffixes' => [
        'controller' => 'Controller',
        'model' => '',
        'service' => 'Service',
        'middleware' => 'Middleware',
        'repository' => 'Repository',
    ],
    'tinker' => [
        'startup' => null,
        'includes' => [],
        'variables' => [],
        'history' => 'storage/psysh',
        'casters' => true,
    ],
];
