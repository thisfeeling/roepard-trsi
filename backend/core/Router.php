<?php

class Router {
    private $routes = [];
    private $notFoundHandler;

    public function addRoute($method, $path, $handler) {
        $this->routes[$method][$path] = $handler;
    }

    public function post($path, $handler) {
        $this->addRoute('POST', $path, $handler);
    }

    public function get($path, $handler) {
        $this->addRoute('GET', $path, $handler);
    }

    public function setNotFoundHandler($handler) {
        $this->notFoundHandler = $handler;
    }

    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = $_SERVER['REQUEST_URI'];

        if (isset($this->routes[$method][$path])) {
            list($controller, $method) = explode('@', $this->routes[$method][$path]);
            (new $controller())->$method();
        } else {
            call_user_func($this->notFoundHandler);
        }
    }
}
?>
