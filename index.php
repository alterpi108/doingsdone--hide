<?php
require 'core/bootstrap.php';

use App\Core\Router;
use App\Core\Request;

// Директория /public отерыта для всех и отдаётся веб-сервером напрямую, в обход роутера.
// Можете запустить сервер из директории с проектом, если выполните команду `php -S localhost:8000`

date_default_timezone_set('Europe/Moscow');
session_start();

Router::load('app/routes.php')
    ->direct(Request::uri(), Request::method());
