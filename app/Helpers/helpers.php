<?php

declare(strict_types=1);

use App\Core\View\BladeFactory;

if (!function_exists('env')) {
    /**
     * Lê uma variável de ambiente, com valor padrão opcional.
     */
    function env(string $key, mixed $default = null): mixed
    {
        $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key) ?? null;

        if ($value === false || $value === null) {
            return $default;
        }

        switch (strtolower((string) $value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }

        return $value;
    }
}

if (!function_exists('blade')) {
    /**
     * Retorna uma instância compartilhada do BladeOne.
     */
    function blade(): \eftec\bladeone\BladeOne
    {
        static $instance = null;

        if ($instance === null) {
            $instance = BladeFactory::make();
        }

        return $instance;
    }
}

if (!function_exists('view')) {
    /**
     * Renderiza uma view Blade e devolve o HTML como string.
     */
    function view(string $name, array $data = []): string
    {
        return blade()->run($name, $data);
    }
}

if (!function_exists('session')) {
    /**
     * Helper para acesso à sessão.
     */
    function session(): \App\Core\Session\Session
    {
        global $container;
        return $container->make(\App\Core\Session\Session::class);
    }
}


if (!function_exists('flash')) {
    /**
     * Helper para mensagens flash.
     */
    function flash(): \App\Application\Services\FlashService
    {
        global $container;
        return $container->make(\App\Application\Services\FlashService::class);
    }
}

if (!function_exists('old')) {
    /**
     * Recupera valor antigo de input (após erro de validação/login).
     */
    function old(string $key, mixed $default = null): mixed
    {
        return flash()->getOldInput($key, $default);
    }
}

if (!function_exists('redirect')) {
    /**
     * Cria uma resposta de redirecionamento.
     */
    function redirect(string $path): \Illuminate\Http\RedirectResponse
    {
        return new \Illuminate\Http\RedirectResponse($path);
    }
}


