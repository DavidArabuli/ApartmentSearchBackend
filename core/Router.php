<?php

// require '../controllers/APIController.php';

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
                array_shift($matches); // Remove the full match
                return call_user_func_array($route['action'], $matches);
            }
        }

        $this->abort();
    }

    // public function route($uri, $method)
    // {
    //     foreach ($this->routes as $route) {
    //         if ($route['uri'] === $uri && $route['method'] === strtoupper($method))
    //         // if (strpos($uri, $route['uri']) === 0 && $route['method'] === strtoupper($method)) 
    //         {
    //             if (is_callable($route['action'])) {
    //                 return $route['action']();
    //             }

    //             return require '../controllers/' . $route['action'];
    //         }
    //     }
    //     $this->abort();
    // }

    public function abort($code = 404)
    {
        http_response_code($code);
        require '../views/404.php';
        die();
    }
}
