<?php
require 'core/bootstrap.php';

use App\Core\Router;
use App\Core\Request;

//===========================================
// Информация о приложении
//===========================================
// Директория `public` отдаётся веб-сервером напрямую, в обход роутера.
//
// Прикрепленные файлы сохраняются в директорию `public/userfiles`.
//
// Чтобы нициализировать базу данных, выполните все команды из `schema.sql`.
//
// Можете запустить штатный веб-сервер, если из директории с проектом выполните команду `php -S localhost:8000`
//===========================================

date_default_timezone_set('Europe/Moscow');
session_start();

Router::load('app/routes.php')
    ->direct(Request::uri(), Request::method());
