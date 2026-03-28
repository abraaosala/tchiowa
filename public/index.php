<?php

declare(strict_types=1);

// Suprime avisos deprecatados de libs de terceiros
//error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Nyholm\Psr7\Factory\Psr17Factory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;

// Sessão para autenticação
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

/** @var \Illuminate\Container\Container $container */
$container = require __DIR__ . '/../bootstrap/app.php';

/** @var Router $router */
$router = $container->make('router');

// Captura a Request HTTP do Illuminate e atualiza o gerador de URLs
$request = Request::capture();
$container->instance('request', $request);
$container->instance(Illuminate\Http\Request::class, $request);

/** @var \Illuminate\Routing\UrlGenerator $url */
$url = $container->make('url');
$url->setRequest($request);

// Despacha a rota e obtém uma Response Symfony/Illuminate
$response = $router->dispatch($request);

// Se a Response já for uma Instância Symfony, apenas envia.
if ($response instanceof \Symfony\Component\HttpFoundation\Response) {
    $response->send();
    return;
}

// Se for um PSR-7 Response, convertemos para Symfony usando o bridge
/** @var PsrHttpFactory $psrHttpFactory */
$psrHttpFactory = $container->make(PsrHttpFactory::class);
/** @var Psr17Factory $psr17Factory */
$psr17Factory = $container->make(Psr17Factory::class);

if ($response instanceof \Psr\Http\Message\ResponseInterface) {
    $symfonyResponse = (new Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory())
        ->createResponse($response);
    $symfonyResponse->send();
    return;
}

// Fallback simples: imprimir string
echo (string) $response;
