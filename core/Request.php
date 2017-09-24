<?php
namespace App\Core;

class Request
{
    public static function uri()
    {
        return trim($_SERVER['PATH_INFO'], '/');
    }

    public static function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function email()
    {
        return $_POST['email'];
    }

    public static function password()
    {
        return $_POST['password'];
    }

    public static function name()
    {
        return $_POST['name'];
    }

    public static function date()
    {
        return $_POST['date'];
    }

    public static function file()
    {
        return $_POST['file'];
    }

    public static function project()
    {
        return $_POST['project'];
    }

    /**
     * Возвращает ID пользователя, для которого запущена текущая сессия.
     *
     * @return integer
     */
    public static function user()
    {
        return $_SESSION['user'] ?? null;
    }

    public static function logged()
    {
        return static::user() !== null;
    }
}
