<?php

declare(strict_types=1);

use App\Application\Services\FlashService;
use App\Core\Session\Session;
use App\Support\Facades\Route;
use Dotenv\Dotenv;
use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Routing\CallableDispatcher;
use Illuminate\Routing\Contracts\CallableDispatcher as CallableDispatcherContract;
use Illuminate\Routing\ControllerDispatcher;
use Illuminate\Routing\RouteCollectionInterface;
use Illuminate\Routing\Router;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Facade;
use Nyholm\Psr7\Factory\Psr17Factory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

require __DIR__.'/../vendor/autoload.php';

// Carrega variáveis de ambiente
$dotenv = Dotenv::createImmutable(__DIR__.'/..');
$dotenv->load();

// Whoops Error Handler
if (env('APP_DEBUG', true)) {
    $whoops = new Run;
    $whoops->pushHandler(new PrettyPageHandler);
    $whoops->register();
}

$container = new Container;
Container::setInstance($container);
Facade::setFacadeApplication($container);

// Config
$configPath = __DIR__.'/../config';
$configFiles = glob($configPath.'/*.php') ?: [];
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
$filesystem = new Filesystem;
$container->instance('files', $filesystem);

// Database / Eloquent
$capsule = new Capsule($container);

$defaultConnection = $config->get('database.default') ?: env('DB_CONNECTION', 'mysql');
$dbConfig = $config->get("database.connections.{$defaultConnection}");

if (empty($dbConfig) && $defaultConnection !== 'mysql') {
    $defaultConnection = 'mysql';
    $dbConfig = $config->get('database.connections.mysql');
}

if (empty($dbConfig)) {
    throw new RuntimeException("Erro fatal: Configuração de base de dados para '{$defaultConnection}' não foi encontrada no arquivo config/database.php.");
}

$capsule->addConnection($dbConfig);
$capsule->setEventDispatcher($events);
$capsule->setAsGlobal();
$capsule->bootEloquent();

Model::setConnectionResolver($capsule->getDatabaseManager());

$container->instance('db', $capsule->getDatabaseManager());
$container->instance(Capsule::class, $capsule);

// Repositories & Services
$container->singleton(Session::class, function () {
    return new Session;
});

$container->singleton(FlashService::class, function ($container) {
    return new FlashService(
        $container->make(Session::class)
    );
});

// Router
$router = new Router($events, $container);
$container->instance('router', $router);

$container->instance(RouteCollectionInterface::class, $router->getRoutes());

$container->singleton(Illuminate\Routing\Contracts\ControllerDispatcher::class, function ($container) {
    return new ControllerDispatcher($container);
});

$container->bind(CallableDispatcherContract::class, function ($container) {
    return new CallableDispatcher($container);
});

$routes = $router->getRoutes();

$url = new UrlGenerator($routes, new Request);
$container->instance('url', $url);

// PSR-7 / HTTP bridge
$psr17Factory = new Psr17Factory;
$psrHttpFactory = new PsrHttpFactory(
    $psr17Factory,
    $psr17Factory,
    $psr17Factory,
    $psr17Factory
);

$container->instance(PsrHttpFactory::class, $psrHttpFactory);
$container->instance(Psr17Factory::class, $psr17Factory);

if (! class_exists('Route')) {
    class_alias(Route::class, 'Route');
}

// Registrar rotas
if (file_exists(__DIR__.'/../routes/web.php')) {
    require __DIR__.'/../routes/web.php';
}

return $container;
