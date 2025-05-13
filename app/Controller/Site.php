<?php

namespace Controller;

use Model\Post;
use Src\View;
use Src\Request;
use Model\User;
use Model\Reader;
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
    public function updateReader(Request $request, $id): string  // Добавлен метод updateReader
    {
        $reader = Reader::findIdentity($id);

        if (!$reader) {
            // Обработка ошибки, читатель не найден
            return 'Reader not found'; // Или перенаправление на страницу ошибки
        }

        // Получаем данные из запроса
        $reader->last_name = $request->post('last-name');
        $reader->first_name = $request->post('first-name');
        $reader->patronym = $request->post('patronym');
        $reader->address = $request->post('address');
        $reader->phone_number = $request->post('phone-number');

        // Сохраняем изменения
        $reader->save();

        // Перенаправляем обратно на страницу читателей
        header('Location: /readers');
        exit();
    }

    public function books(): string
    {
        $books = Book::all();
        return (new View())->render('site.books', ['books' => $books]);
    }

    public function authors(): string
    {
        // Здесь должна быть логика для получения данных об авторах
        // Например, из модели Author
        // $authors = Author::all(); // или Author::orderBy(...)->get();

        // Пока что просто возвращаем пустой массив:
        $authors = [];
        return (new View())->render('site.authors', ['authors' => $authors]);
    }

    public function register(Request $request): string
    {
        if ($request->method === 'POST' && User::create($request->all())) {
            app()->route->redirect('/readers');
        }
        return new View('site.register');
    }
    public function login(Request $request): string
    {
        //Если просто обращение к странице, то отобразить форму
        if ($request->method === 'GET') {
            return new View('site.login');
        }
        //Если удалось аутентифицировать пользователя, то редирект
        if (Auth::attempt($request->all())) {
//            print("aboba");
//            die();
            app()->route->redirect('/readers');
        }
        //Если аутентификация не удалась, то сообщение об ошибке
        return new View('site.login', ['message' => 'Неправильные логин или пароль']);
    }

    public function logout(): void
    {
        Auth::logout();
        app()->route->redirect('/login');
    }
}