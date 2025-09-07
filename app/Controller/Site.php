<?php

namespace Controller;

use Model\Post;
use Src\View;
use Src\Request;
use Model\User;
use Src\Auth\Auth;
use Src\Validator\Validator;

class Site
{
    public function start(): void
    {
       // Fetch method and URI from somewhere
       $httpMethod = $_SERVER['REQUEST_METHOD'];
       $uri = $_SERVER['REQUEST_URI'];

       // Strip query string (?foo=bar) and decode URI
       if (false !== $pos = strpos($uri, '?')) {
           $uri = substr($uri, 0, $pos);
       }
       $uri = rawurldecode($uri);
       $uri = substr($uri, strlen($this->prefix));

       $dispatcher = new Dispatcher($this->routeCollector->getData());

       $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
       switch ($routeInfo[0]) {
           case Dispatcher::NOT_FOUND:
               throw new Error('NOT_FOUND');
           case Dispatcher::METHOD_NOT_ALLOWED:
               throw new Error('METHOD_NOT_ALLOWED');
           case Dispatcher::FOUND:
               $handler = $routeInfo[1];
               $vars = array_values($routeInfo[2]);
    //Вызываем обработку всех Middleware
               $vars[] = Middleware::single()->go($httpMethod, $uri, new Request());
               $class = $handler[0];
               $action = $handler[1];
               call_user_func([new $class, $action], ...$vars);
               break;
       }
    }

    public function index(Request $request): string
    {
        $posts = Post::where('id', $request->id)->get();
        return (new View())->render('site.post', ['posts' => $posts]);
    }

    public function hello(): string
    {
        return new View('site.hello', ['message' => 'hello working']);
    }

    public function signup(Request $request): string
    {
       if ($request->method === 'POST') {

           $validator = new Validator($request->all(), [
               'name' => ['required'],
               'login' => ['required', 'unique:users,login'],
               'password' => ['required']
           ], [
               'required' => 'Поле :field пусто',
               'unique' => 'Поле :field должно быть уникально'
           ]);

           if($validator->fails()){
               return new View('site.signup',
                   ['message' => json_encode($validator->errors(), JSON_UNESCAPED_UNICODE)]);
           }

           if (User::create($request->all())) {
               app()->route->redirect('/login');
           }
       }
       return new View('site.signup');
    }

    public function login(Request $request): string
    {
        //Если просто обращение к странице, то отобразить форму
        if ($request->method === 'GET') {
            return new View('site.login');
        }
        //Если удалось аутентифицировать пользователя, то редирект
        if (Auth::attempt($request->all())) {
            app()->route->redirect('/hello');
        }
        //Если аутентификация не удалась, то сообщение об ошибке
        return new View('site.login', ['message' => 'Неправильные логин или пароль']);
    }

    public function logout(): void
    {
        Auth::logout();
        app()->route->redirect('/hello');
    }
}