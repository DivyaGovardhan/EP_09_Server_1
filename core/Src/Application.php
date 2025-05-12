<?php

namespace Src;

use Error;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Database\Capsule\Manager as Capsule;
use Src\Auth\Auth;
use Src\Auth\IdentityInterface; // Добавлено

class Application
{
    private Settings $settings;
    private Route $route;
    private Capsule $dbManager;
    private Auth $auth;
    private ?IdentityInterface $user = null; // Добавлено: свойство user и инициализация

    public function __construct(Settings $settings)
    {
        //Привязываем класс со всеми настройками приложения
        $this->settings = $settings;
        //Привязываем класс маршрутизации с установкой префикса
        $this->route = Route::single()->setPrefix($this->settings->getRootPath());
        //Создаем класс менеджера для базы данных
        $this->dbManager = new Capsule();
        //Создаем класс для аутентификации на основе настроек приложения
        $this->auth = new $this->settings->app['auth'];

        //Настройка для работы с базой данных
        $this->dbRun();
        //Инициализация класса пользователя на основе настроек приложения
        $identityClass = $this->settings->app['identity']; // Получаем имя класса
        $this->user = new $identityClass(); // Создаем экземпляр класса пользователя
        $this->auth::init($this->user); // Передаем экземпляр в Auth::init()
    }

    public function __get($key)
    {
        switch ($key) {
            case 'settings':
                return $this->settings;
            case 'route':
                return $this->route;
            case 'auth':
                return $this->auth;
            case 'user':  // Добавлено: обработка запроса к свойству user
                return $this->user;
        }
        throw new Error('Accessing a non-existent property');
    }

    private function dbRun()
    {
        $this->dbManager->addConnection($this->settings->getDbSetting());
        $this->dbManager->setEventDispatcher(new Dispatcher(new Container));
        $this->dbManager->setAsGlobal();
        $this->dbManager->bootEloquent();
    }

    public function run(): void
    {
        //Запуск маршрутизации
        $this->route->start();
    }
}