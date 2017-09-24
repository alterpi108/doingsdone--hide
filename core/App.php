<?php
namespace App\Core;

use App\Controllers\ActionController;
use App\Controllers\PagesController;
use App\Core\Database\Database;
use App\Core\Request;

class App
{
    public static $config;
    public static $userId;
    public static $userName;

    public static function auth()
    {
        static::$userId = Request::user();

        if (static::$userId !== null) {
            static::$userName = Database::getUserNameById(static::$userId);
        }
    }

    public static function date()
    {
        return date('d.m.Y');
    }

    public static function logged()
    {
        return static::$userId !== null;
    }

    public static function error($message)
    {
        (new PagesController())->error($message);
        die();
    }

    public static function loggedOnly()
    {
        if (! static::logged()) {
            static::error('Сначала зарегистрируйтесь');
        }
    }

    public static function error404()
    {
        static::error('Неверный путь');
    }

    public static function loginByEmail($email)
    {
        $user = Database::getUserIdByEmail($email);
        $_SESSION['user'] = $user;
    }

    public static function logout()
    {
        $_SESSION = [];
        session_destroy();
    }

    public static function redirect($location)
    {
        header("Location: $location/");
    }

    public static function redirectIndex()
    {
        header('Location: /');
    }
}
