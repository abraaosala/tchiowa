<?php

declare(strict_types=1);

use App\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
