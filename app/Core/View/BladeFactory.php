<?php

declare(strict_types=1);

namespace App\Core\View;

use eftec\bladeone\BladeOne;

class BladeFactory
{
    public static function make(): BladeOne
    {
        $views = __DIR__ . '/../../../resources/views';
        $cache = __DIR__ . '/../../../storage/framework/views';

        if (!is_dir($cache)) {
            mkdir($cache, 0777, true);
        }

        $blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);

        // Compartilha variáveis globais com todas as views
        $blade->share('auth', auth());
        $blade->share('session', session());
        $blade->share('flash', flash());
        $blade->share('errors', flash()->getError() ?: null);
        $blade->share('success', flash()->getSuccess() ?: null);

        return $blade;
    }
}

