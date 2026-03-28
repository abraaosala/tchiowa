<?php

declare(strict_types=1);

use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Routing\Router;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Routing\CallableDispatcher;
use Illuminate\Routing\Contracts\CallableDispatcher as CallableDispatcherContract;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Nyholm\Psr7\Factory\Psr17Factory;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

// Carrega variáveis de ambiente
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Whoops Error Handler
if (env('APP_DEBUG', true)) {
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}

$container = new Container();
Container::setInstance($container);

// Config
$configPath = __DIR__ . '/../config';
$configFiles = glob($configPath . '/*.php') ?: [];
$configData = [];

foreach ($configFiles as $file) {
    $name = basename($file, '.php');
    $configData[$name] = require $file;
}

$config = new ConfigRepository($configData);
$container->instance('config', $config);

// Eventos
$events = new Dispatcher($container);
$container->instance('events', $events);

// Filesystem
$filesystem = new Filesystem();
$container->instance('files', $filesystem);

// Database / Eloquent
$capsule = new Capsule($container);

// Tenta obter a conexão padrão, fallback para 'mysql'
$defaultConnection = $config->get('database.default') ?: env('DB_CONNECTION', 'mysql');
$dbConfig = $config->get("database.connections.{$defaultConnection}");

// Se a conexão não existe mas temos algo em 'mysql', usamos mysql como última tentativa
if (empty($dbConfig) && $defaultConnection !== 'mysql') {
    $defaultConnection = 'mysql';
    $dbConfig = $config->get("database.connections.mysql");
}

if (empty($dbConfig)) {
    throw new \RuntimeException("Erro fatal: Configuração de base de dados para '{$defaultConnection}' não foi encontrada no arquivo config/database.php.");
}

$capsule->addConnection($dbConfig);
$capsule->setEventDispatcher($events);
$capsule->setAsGlobal();
$capsule->bootEloquent();

// Garante que o resolver está definido globalmente no Model
\Illuminate\Database\Eloquent\Model::setConnectionResolver($capsule->getDatabaseManager());

$container->instance('db', $capsule->getDatabaseManager());
$container->instance(Capsule::class, $capsule);

// Repositories & Services
$container->singleton(\App\Core\Session\Session::class, function () {
    return new \App\Core\Session\Session();
});

$container->singleton(\App\Infrastructure\Persistence\Repositories\UserRepository::class, function () {
    return new \App\Infrastructure\Persistence\Repositories\UserRepository();
});

$container->singleton(\App\Application\Services\AuthService::class, function ($container) {
    return new \App\Application\Services\AuthService(
        $container->make(\App\Infrastructure\Persistence\Repositories\UserRepository::class),
        $container->make(\App\Core\Session\Session::class)
    );
});

$container->singleton(\App\Application\Services\FlashService::class, function ($container) {
    return new \App\Application\Services\FlashService(
        $container->make(\App\Core\Session\Session::class)
    );
});

// Router
$router = new Router($events, $container);
$container->instance('router', $router);

// Dispatcher para Controllers (necessário para injeção de Request e outros em métodos)
$container->singleton(\Illuminate\Routing\Contracts\ControllerDispatcher::class, function ($container) {
    return new \Illuminate\Routing\ControllerDispatcher($container);
});

// Dispatcher para closures e callables em rotas
$container->bind(CallableDispatcherContract::class, function ($container) {
    return new CallableDispatcher($container);
});

// URL Generator (inicializado sem request, index.php fornecerá a capturada)
$routes = $router->getRoutes();
$url = new UrlGenerator($routes, new Illuminate\Http\Request());
$container->instance('url', $url);

// PSR-7 / HTTP bridge
$psr17Factory = new Psr17Factory();
$psrHttpFactory = new PsrHttpFactory(
    $psr17Factory,
    $psr17Factory,
    $psr17Factory,
    $psr17Factory
);

$container->instance(PsrHttpFactory::class, $psrHttpFactory);
$container->instance(Psr17Factory::class, $psr17Factory);

// Registrar rotas
if (file_exists(__DIR__ . '/../routes/web.php')) {
    require __DIR__ . '/../routes/web.php';
}

return $container;

