<?php
namespace App\Core;

use App\Controllers\PagesController;
use App\Core\Database\Database;

class App
{
    public static $config;
    public static $userId;
    public static $userName;

    /**
     * Authorize a user.
     *
     * @return void
     */
    public static function auth()
    {
        $userId = Request::user();

        if ($userId !== null && Database::userWithIdExists($userId)) {
            static::$userId = $userId;
            static::$userName = Database::getUserNameById($userId);
        }
    }

    /**
     * Return the current date in the format 'dd.mm.yyyy'.
     *
     * @return string
     */
    public static function date()
    {
        return date('d.m.Y');
    }

    /**
     * Check if the user is logged.
     *
     * @return boolean
     */
    public static function logged()
    {
        return static::$userId !== null;
    }

    /**
     * Show an error message and finish.
     *
     * @param string
     *
     * @return void
     */
    public static function error($message)
    {
        (new PagesController())->error($message);
        die();
    }

    /**
     * Check if a user is logged and if not, show an error and finish.
     *
     * @return void
     */
    public static function loggedOnly()
    {
        if (! static::logged()) {
            static::error('Сначала зарегистрируйтесь');
        }
    }

    /**
     * Show a 404 error.
     *
     * @return void
     */
    public static function error404()
    {
        static::error('Неверный путь');
    }

    /**
     * Login a current user by email.
     *
     * @param string
     *
     * @return void
     */
    public static function loginByEmail($email)
    {
        $user = Database::getUserIdByEmail($email);
        $_SESSION['user'] = $user;
    }

    /**
     * Logiut the current user.
     *
     * @return void
     */
    public static function logout()
    {
        $_SESSION = [];
        session_destroy();
    }

    /**
     * Redirect the current user to a given address.
     *
     * @param string
     *
     * @return void
     */
    public static function redirect($location)
    {
        header("Location: $location");
    }

    /**
     * Redirect the current user to the index page.
     *
     * @return void
     */
    public static function redirectIndex()
    {
        header('Location: /');
    }

    /**
     * Move the attached file to the appropriate directory.
     *
     * This method is called after the task adding form has been submitted with a file.
     *
     * @return string
     */
    public static function saveFile()
    {
        if (! array_key_exists('file', $_FILES) ||
            ! array_key_exists('name', $_FILES['file']) ||
            ! array_key_exists('tmp_name', $_FILES['file'])) {
            static::error("Неправильно отправлен файл");
        }

        $fileName = $_FILES['file']['name'];
        $src = $_FILES['file']['tmp_name'];
        $dst = __DIR__ . '/../public/userfiles/' . $fileName;
        move_uploaded_file($src, $dst);

        return $fileName;
    }
}
