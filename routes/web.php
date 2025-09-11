<?php

use Src\Route;
use Controller\Site;

// Route::add('POST', '/readers/{id}', [Controller\Site::class, 'updateReader'])->middleware('auth'); // Добавлен маршрут для обновления

// Публичные маршруты
Route::add(['GET', 'POST'], '/login', [Site::class, 'login']);
Route::add('GET', '/logout', [Site::class, 'logout']);

// Маршруты для обычных пользователей
Route::add('GET', '/books', [Site::class, 'books'])->middleware('auth');
Route::add('GET', '/readers', [Site::class, 'readers'])->middleware('auth');
Route::add('GET', '/authors', [Site::class, 'authors'])->middleware('auth');

// Маршруты только для администраторов
Route::add('GET', '/users', [Site::class, 'users'])->middleware('auth');
Route::add(['GET', 'POST'], '/register', [Site::class, 'register'])->middleware('auth');
Route::add('POST', '/save-user', [Site::class, 'saveUser'])->middleware('auth');
Route::add('POST', '/delete-user', [Site::class, 'deleteUser'])->middleware('auth');