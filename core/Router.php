<?php
namespace App\Core;

use App\Controllers\PagesController;

class Router
{
    public $routes = [
        'GET' => [],
        'POST' => []
    ];

    /**
     * Load routing data from a file.
     *
     * @return self
     */
    public static function load($file)
    {
        $router = new static;
        require($file);
        return $router;
    }

    /**
     * Set routing for a get request.
     *
     * @param string
     * @param object
     *
     * @return void
     */
    public function get($uri, $controller)
    {
        $this->routes['GET'][$uri] = $controller;
    }

    /**
     * Set routing for a post request.
     *
     * @param string
     * @param object
     *
     * @return void
     */
    public function post($uri, $controller)
    {
        $this->routes['POST'][$uri] = $controller;
    }

    /**
     * Route a request.
     *
     * @param string
     * @param string
     *
     * @return void
     */
    public function direct($uri, $method)
    {
        // project is specified
        if (strpos($uri, '/') !== false) {
            $pieces = explode('/', $uri);

            (new PagesController())->project($pieces[1]);
        } else {
            // project not specified
            if (!array_key_exists($uri, $this->routes[$method])) {
                App::error404();
            }

            $this->callAction(
                ...(explode('@', $this->routes[$method][$uri]))
            );
        }
    }

    /**
     * Route a request.
     *
     * @param string
     * @param string
     *
     * @return void
     */
    protected function callAction($controller, $action)
    {
        $controller = "\\App\\Controllers\\{$controller}";
        $controller = new $controller;
        if (! method_exists($controller, $action)) {
            App::error("Контроллер `{$controller}` не имеет действия `{$action}`.");
        }

        $controller->$action();
    }
}
