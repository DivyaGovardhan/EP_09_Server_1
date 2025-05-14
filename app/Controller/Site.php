<?php

namespace Controller;

use Model\Post;
use Src\View;
use Src\Request;
use Model\User;
use Model\Reader;
use Model\Book;
use Src\Auth\Auth;

class Site
{
    public function index(Request $request): string
    {
        $posts = Post::where('id', $request->id)->get();
        return (new View())->render('site.post', ['posts' => $posts]);
    }

    public function readers(): string
    {
        $readers = Reader::orderBy('last_name')->get();
        return (new View())->render('site.readers', ['readers' => $readers]);
    }

    public function updateReader(Request $request, $id): string
    {
        $reader = Reader::findIdentity($id);

        if (!$reader) {
            return (new View())->render('site.error', ['message' => 'Читатель не найден']);
        }

        $reader->last_name = $request->post('last-name');
        $reader->first_name = $request->post('first-name');
        $reader->patronym = $request->post('patronym');
        $reader->address = $request->post('address');
        $reader->phone_number = $request->post('phone-number');

        if ($reader->save()) {
            app()->route->redirect('/readers');
        }

        return (new View())->render('site.error', ['message' => 'Ошибка при обновлении читателя']);
    }

    public function books(): string
    {
        $books = Book::orderBy('title')->get();
        return (new View())->render('site.books', ['books' => $books]);
    }

    public function authors(): string
    {
        $authors = [];
        return (new View())->render('site.authors', ['authors' => $authors]);
    }

    public function register(Request $request): string
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            app()->route->redirect('/login');
        }

        if ($request->method === 'POST') {
            $data = $request->all();
            $data['is_admin'] = isset($data['is_admin']) ? 1 : 0;

            if (User::create($data)) {
                app()->route->redirect('/users');
            }

            return (new View())->render('site.register', ['message' => 'Ошибка при регистрации']);
        }

        return new View('site.register');
    }

    public function users(): string
    {
        $users = User::all();
        return (new View())->render('site.users', ['users' => $users]);
    }

    public function login(Request $request): string
    {
        // Если уже авторизован - перенаправляем сразу
        if (Auth::check()) {
            $this->redirectAfterLogin();
        }

        if ($request->method === 'GET') {
            return new View('site.login');
        }

        if (Auth::attempt($request->all())) {
            $this->redirectAfterLogin();
        }

        return new View('site.login', ['message' => 'Неправильные логин или пароль']);
    }

    private function redirectAfterLogin(): void
    {
        $user = Auth::user();
        if ($user->is_admin) {
            app()->route->redirect('/users');
        } else {
            app()->route->redirect('/books');
        }
        exit;
    }

    public function logout(): void
    {
        Auth::logout();
        app()->route->redirect('/login');
    }

    public function error(): string
    {
        return (new View())->render('site.error', ['message' => 'Доступ запрещен']);
    }
}