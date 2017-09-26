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
        $userId = Request::user();

        if ($userId !== null && Database::userWithIdExists($userId)) {
            static::$userId = $userId;
            static::$userName = Database::getUserNameById($userId);
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
        header("Location: $location");
    }

    public static function redirectIndex()
    {
        header('Location: /');
    }

    public static function saveFile()
    {
        $fileName = $_FILES['file']['name'];
        $src = $_FILES['file']['tmp_name'];
        $dst = __DIR__ . '/../../public/userfiles/' . $fileName;
        move_uploaded_file($src, $dst);

        return $fileName;
    }
}
