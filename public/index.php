<?php

declare(strict_types=1);

error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Routing\UrlGenerator;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\HttpFoundation\Response;

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

/** @var Container $container */
$container = require __DIR__.'/../bootstrap/app.php';

/** @var Router $router */
$router = $container->make('router');

$request = Request::capture();
$container->instance('request', $request);
$container->instance(Request::class, $request);

/** @var UrlGenerator $url */
$url = $container->make('url');
$url->setRequest($request);

$response = $router->dispatch($request);

if ($response instanceof Response) {
    $response->send();

    return;
}

/** @var PsrHttpFactory $psrHttpFactory */
$psrHttpFactory = $container->make(PsrHttpFactory::class);
/** @var Psr17Factory $psr17Factory */
$psr17Factory = $container->make(Psr17Factory::class);

if ($response instanceof ResponseInterface) {
    $symfonyResponse = (new HttpFoundationFactory)
        ->createResponse($response);
    $symfonyResponse->send();

    return;
}

echo (string) $response;
