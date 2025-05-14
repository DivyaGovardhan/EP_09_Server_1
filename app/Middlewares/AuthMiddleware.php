<?php

namespace Middlewares;

use Src\Auth\Auth;
use Src\Request;

class AuthMiddleware
{
    private array $publicRoutes = ['/login', '/logout'];
    private array $adminRoutes = ['/users', '/register'];
    private array $userRoutes = ['/books', '/readers', '/authors'];

    public function handle(Request $request)
    {
        // Разрешаем доступ к публичным маршрутам
        if (in_array($request->uri, $this->publicRoutes)) {
            return;
        }

        // Если пользователь не авторизован - перенаправляем на страницу входа
        if (!Auth::check()) {
            app()->route->redirect('/login');
            exit;
        }

        $user = Auth::user();

        // Если пользователь админ и пытается получить доступ к обычным маршрутам
        if ($user->is_admin && in_array($request->uri, $this->userRoutes)) {
            app()->route->redirect('/users');
            exit;
        }

        // Если обычный пользователь пытается получить доступ к админским маршрутам
        if (!$user->is_admin && in_array($request->uri, $this->adminRoutes)) {
            app()->route->redirect('/books');
            exit;
        }
    }
}