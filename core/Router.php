<?php

class Router
{

    protected $routes = [];

    public function addRoute($method, $uri, $controller)
    {
        $this->routes[] = [
            'uri' => $uri,
            'controller' => $controller,
            'method' => $method
        ];
    }
    public function get($uri, $controller)
    {
        $this->addRoute('GET', $uri, $controller);
    }
    public function post($uri, $controller)
    {
        $this->addRoute("POST", $uri, $controller);
    }

    public function delete($uri, $controller)
    {
        $this->addRoute("DELETE", $uri, $controller);
    }

    public function route($uri, $method)
    {
        foreach ($this->routes as $route) {
            if ($route['uri'] === $uri && $route['method'] === strtoupper($method)) {
                // return 'hey there';
                return require '../controllers/' . $route['controller'];
            }
            $this->abort();
        }
    }

    public function abort($code = 404)
    {
        http_response_code($code);
        require '../views/404.php';
        die();
    }
}
