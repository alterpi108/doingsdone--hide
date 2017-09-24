<?php
namespace App\Core;

use App\Controllers\PagesController;
use Exception;

class Router
{
    public $routes = [
        'GET' => [],
        'POST' => []
    ];

    public static function load($file)
    {
        $router = new static;
        require($file);

        return $router;
    }

    public function get($uri, $controller)
    {
        $this->routes['GET'][$uri] = $controller;
    }

    public function post($uri, $controller)
    {
        $this->routes['POST'][$uri] = $controller;
    }

    public function direct($uri, $method)
    {
        // для конкретного проекта
        if (strpos($uri, '/') !== false) {
            $pieces = explode('/', $uri);

            (new PagesController())->project($pieces[1]);
        } else {
            // нормальное поведение
            if (!array_key_exists($uri, $this->routes[$method])) {
                App::error404();
            }

            return $this->callAction(
                ...(explode('@', $this->routes[$method][$uri]))
            );
        }

    }

    protected function callAction($controller, $action)
    {
        $controller = "\\App\\Controllers\\{$controller}";
        $controller = new $controller;
        if (! method_exists($controller, $action)) {
            throw new Exception("{$controller} controller does not respond to {$action} action.");
        }

        return $controller->$action();
    }
}
