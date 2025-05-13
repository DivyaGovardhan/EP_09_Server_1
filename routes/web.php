<?php

use Src\Route;
use Controller\Site;

Route::add('GET', '/readers', [Controller\Site::class, 'readers'])
    ->middleware('auth');
Route::add('POST', '/readers/{id}', [Controller\Site::class, 'updateReader'])
    ->middleware('auth'); // Добавлен маршрут для обновления

Route::add('GET', '/books', [Site::class, 'books'])
    ->middleware('auth');

Route::add('GET', '/authors', [Site::class, 'authors'])
    ->middleware('auth');

Route::add(['GET', 'POST'], '/register', [Controller\Site::class, 'register']);
Route::add(['GET', 'POST'], '/login', [Controller\Site::class, 'login']);
Route::add('GET', '/logout', [Controller\Site::class, 'logout']);