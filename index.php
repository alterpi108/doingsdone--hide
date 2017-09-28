<?php
require 'core/bootstrap.php';

use App\Core\Router;
use App\Core\Request;

//===========================================
// Информация о приложении
//===========================================
// Чтобы нициализировать базу данных, выполните все команды из `schema.sql`.
//
// Директория `public` должна отдаваться веб-сервером напрямую, в обход роутера.
//
// Прикрепленные файлы сохраняются в директорию `public/userfiles`.
//
// Можете запустить штатный веб-сервер, если из директории с проектом выполните команду `php -S localhost:8000`
//===========================================

// PHP and MySQL should be set to the same time zone
//date_default_timezone_set('Europe/Moscow');


//print(trim(null) === '');
//
//die();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

Router::load('app/routes.php')
    ->direct(Request::uri(), Request::method());
