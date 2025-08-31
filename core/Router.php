<?php

class Router
{

    protected $routes = [];

    public function addRoute($method, $uri, $action)
    {
        $this->routes[] = [
            'uri' => $uri,
            'action' => $action,
            'method' => $method
        ];
    }
    public function get($uri, $action)
    {
        $this->addRoute('GET', $uri, $action);
    }
    public function post($uri, $action)
    {
        $this->addRoute("POST", $uri, $action);
    }

    public function delete($uri, $action)
    {
        $this->addRoute("DELETE", $uri, $action);
    }
    public function route($requestUri, $method)
    {
        foreach ($this->routes as $route) {
            $pattern = preg_replace('#:([\w]+)#', '([\w-]+)', $route['uri']);
            $pattern = "#^" . $pattern . "$#";

            if (preg_match($pattern, $requestUri, $matches) && strtoupper($method) === $route['method']) {
                array_shift($matches);

                if (is_callable($route['action'])) {
                    return call_user_func_array($route['action'], $matches);
                }


                if (is_string($route['action']) && file_exists(__DIR__ . '/../views' . $route['action'])) {
                    return require __DIR__ . '/../views' . $route['action'];
                }

                throw new InvalidArgumentException("Invalid route action: " . $route['action']);
            }
        }

        $this->abort();
    }


    public function abort($code = 404)
    {
        http_response_code($code);
        require '../views/404.php';
        die();
    }
}
