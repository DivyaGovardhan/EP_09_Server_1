<?php

namespace Providers;

use Src\Provider\AbstractProvider;
use Src\Route;

class RouteProvider extends AbstractProvider
{

   public function register(): void
   {
   }

   public function boot(): void
   {
       $this->app->bind('route', Route::single()->setPrefix($this->app->settings->getRootPath()));

       if ($this->checkPrefix('/api')) {
           //Если префикс адреса api то удаляем не нужные middleware
           $this->app->settings->removeAppMiddleware('csrf');
           $this->app->settings->removeAppMiddleware('specialChars');

           //Загружаем маршруты из файла для апи
           Route::group('/api', function () {
               require_once __DIR__ . '/../../' . $this->app->settings->getRoutePath() . '/api.php';
           });
           return;
       }

       //Удаляем обработку json данных
       $this->app->settings->removeAppMiddleware('json');
       //Загружаем маршруты из стандартного файла
       require_once __DIR__ . '/../..' . $this->app->settings->getRoutePath() . '/web.php';
   }

   private function getUri(): string
   {
       //Возвращает адрес без пути до директории
       return substr($_SERVER['REQUEST_URI'], strlen($this->app->settings->getRootPath()));
   }

   private function checkPrefix(string $prefix): bool
   {
       $uri = $this->getUri();
       // Добавляем проверку на точное совпадение или начало с префикса + '/'
       return $uri === $prefix || strpos($uri, $prefix . '/') === 0;
   }
}