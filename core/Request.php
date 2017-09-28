<?php
namespace App\Core;

class Request
{
    /**
     * Get the current URI.
     *
     * @return string
     */
    public static function uri()
    {
        return trim($_SERVER['PATH_INFO'] ?? '', '/');
    }

    /**
     * Get the current method (post or get).
     *
     * @return string
     */
    public static function method()
    {
        return trim($_SERVER['REQUEST_METHOD'] ?? '', '/');
    }

    /**
     * Get the submitted email.
     *
     * @return string
     */
    public static function email()
    {
        return trim($_POST['email'] ?? '');
    }

    /**
     * Get the submitted password.
     *
     * @return string
     */
    public static function password()
    {
        return $_POST['password'] ?? '';
    }

    /**
     * Get the submitted name.
     *
     * @return string
     */
    public static function name()
    {
        return trim($_POST['name'] ?? '');
    }

    /**
     * Get the submitted project name.
     *
     * @return string
     */
    public static function projectName()
    {
        $projectName = trim($_POST['name'] ?? '');
        if (function_exists('mb_strtolower')) {
            $projectName = mb_strtolower($projectName);
        }
        return $projectName;
    }

    /**
     * Get the submitted project ID.
     *
     * @return integer
     */
    public static function project()
    {
        return (int) $_POST['project'] ?? 0;
    }

    /**
     * Get the submitted date.
     *
     * @return string
     */
    public static function date()
    {
        return trim($_POST['date'] ?? '');
    }

    /**
     * Get the user ID that owns the current session.
     *
     * @return integer
     */
    public static function user()
    {
        return $_SESSION['user'] ?? null;
    }
}
