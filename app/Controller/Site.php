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
        if (Auth::check()) {
            $this->redirectAfterLogin();
        }

        if ($request->method === 'GET') {
            echo(" form");
            return new View('site.login');
        }

        if ($request->method === 'POST') {
            echo(" post");

            if (Auth::attempt($request->all())) {
                $this->redirectAfterLogin();
            }
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

    public function saveUser(Request $request): void
    {
        header('Content-Type: application/json');

        // Отладочная информация
        error_log("Received data: " . print_r($request->all(), true));

        if (!Auth::check() || !Auth::user()->is_admin) {
            echo json_encode(['success' => false, 'message' => 'Доступ запрещен']);
            exit;
        }

        $data = $request->all();
        $id = $data['id'] ?? null;

        // Отладочная информация о проверяемых полях
        error_log("last_name: " . ($data['last_name'] ?? 'NOT SET'));
        error_log("first_name: " . ($data['first_name'] ?? 'NOT SET'));
        error_log("login: " . ($data['login'] ?? 'NOT SET'));

        // Проверяем обязательные поля
        if (empty($data['last_name']) || empty($data['first_name']) || empty($data['login'])) {
            echo json_encode(['success' => false, 'message' => 'Заполните обязательные поля']);
            exit;
        }

        // Если это создание нового пользователя, проверяем пароль
        if (!$id && empty($data['password'])) {
            echo json_encode(['success' => false, 'message' => 'Пароль обязателен для нового пользователя']);
            exit;
        }

        try {
            if ($id) {
                // Обновление существующего пользователя
                $user = User::find($id);
                if (!$user) {
                    echo json_encode(['success' => false, 'message' => 'Пользователь не найден']);
                    exit;
                }

                // Если пароль не указан, оставляем старый
                if (empty($data['password'])) {
                    unset($data['password']);
                }

                $user->fill($data);
                if ($user->save()) {
                    echo json_encode(['success' => true]);
                    exit;
                }
            } else {
                // Создание нового пользователя
                if (User::create($data)) {
                    echo json_encode(['success' => true]);
                    exit;
                }
            }
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Ошибка базы данных: ' . $e->getMessage()]);
            exit;
        }

        echo json_encode(['success' => false, 'message' => 'Ошибка при сохранении']);
        exit;
    }

    public function deleteUser(Request $request): void
    {
        // Устанавливаем заголовок для JSON ответа
        header('Content-Type: application/json');

        if (!Auth::check() || !Auth::user()->is_admin) {
            echo json_encode(['success' => false, 'message' => 'Доступ запрещен']);
            exit;
        }

        $id = $request->get('id');
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID пользователя не указан']);
            exit;
        }

        $user = User::find($id);
        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'Пользователь не найден']);
            exit;
        }

        // Не позволяем удалить самого себя
        if ($user->id === Auth::user()->id) {
            echo json_encode(['success' => false, 'message' => 'Нельзя удалить собственный аккаунт']);
            exit;
        }

        if ($user->delete()) {
            echo json_encode(['success' => true]);
            exit;
        }

        echo json_encode(['success' => false, 'message' => 'Ошибка при удалении']);
        exit;
    }
}