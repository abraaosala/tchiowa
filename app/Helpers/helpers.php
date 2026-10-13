<?php

declare(strict_types=1);

use App\Application\Services\FlashService;
use App\Core\Session\Session;
use App\Core\View\BladeFactory;
use eftec\bladeone\BladeOne;
use Illuminate\Http\RedirectResponse;

if (! function_exists('env')) {
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

if (! function_exists('blade')) {
    /**
     * Retorna uma instância compartilhada do BladeOne.
     */
    function blade(): BladeOne
    {
        static $instance = null;

        if ($instance === null) {
            $instance = BladeFactory::make();
        }

        return $instance;
    }
}

if (! function_exists('view')) {
    /**
     * Renderiza uma view Blade e devolve o HTML como string.
     */
    function view(string $name, array $data = []): string
    {
        return blade()->run($name, $data);
    }
}

if (! function_exists('session')) {
    /**
     * Helper para acesso à sessão.
     */
    function session(): Session
    {
        global $container;

        return $container->make(Session::class);
    }
}

if (! function_exists('flash')) {
    /**
     * Helper para mensagens flash.
     */
    function flash(): FlashService
    {
        global $container;

        return $container->make(FlashService::class);
    }
}

if (! function_exists('old')) {
    /**
     * Recupera valor antigo de input (após erro de validação/login).
     */
    function old(string $key, mixed $default = null): mixed
    {
        return flash()->getOldInput($key, $default);
    }
}

if (! function_exists('config')) {
    /**
     * Obtém um valor da configuração, ou o repositório inteiro.
     */
    function config(?string $key = null, mixed $default = null): mixed
    {
        global $container;

        if ($key === null) {
            return $container->make('config');
        }

        return $container->make('config')->get($key, $default);
    }
}

if (! function_exists('url')) {
    /**
     * Gera uma URL absoluta para o caminho informado.
     */
    function url(string $path = ''): string
    {
        global $container;

        return $container->make('url')->to($path);
    }
}

if (! function_exists('redirect')) {
    /**
     * Cria uma resposta de redirecionamento.
     */
    function redirect(string $path): RedirectResponse
    {
        return new RedirectResponse($path);
    }
}
