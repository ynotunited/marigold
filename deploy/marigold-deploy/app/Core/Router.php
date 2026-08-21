<?php

namespace App\Core;

class Router
{
    protected array $routes = [];
    protected array $params = [];

    public function get(string $route, $handler): void
    {
        $this->addRoute('GET', $route, $handler);
    }

    public function post(string $route, $handler): void
    {
        $this->addRoute('POST', $route, $handler);
    }

    public function put(string $route, $handler): void
    {
        $this->addRoute('PUT', $route, $handler);
    }

    public function delete(string $route, $handler): void
    {
        $this->addRoute('DELETE', $route, $handler);
    }

    protected function addRoute(string $method, string $route, $handler): void
    {
        // Convert route to regex: escape forward slashes
        $route = preg_replace('/\//', '\\/', $route);
        // Convert variables e.g. {id} to (?P<id>[a-zA-Z0-9_-]+)
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-zA-Z0-9_-]+)', $route);
        // Add start and end delimiters, and case insensitive flag
        $route = '/^' . $route . '$/i';

        $this->routes[$method][$route] = $handler;
    }

    public function dispatch(string $url, string $requestMethod)
    {
        $url = $this->removeQueryStringVariables($url);

        if (isset($this->routes[$requestMethod])) {
            foreach ($this->routes[$requestMethod] as $route => $handler) {
                if (preg_match($route, $url, $matches)) {
                    // Get named capture group values
                    foreach ($matches as $key => $match) {
                        if (is_string($key)) {
                            $this->params[$key] = $match;
                        }
                    }

                    if (is_callable($handler)) {
                        return call_user_func_array($handler, $this->params);
                    }

                    if (is_array($handler)) {
                        [$controller, $method] = $handler;
                        if (class_exists($controller)) {
                            $controllerObject = new $controller();
                            if (method_exists($controllerObject, $method)) {
                                return call_user_func_array([$controllerObject, $method], $this->params);
                            } else {
                                throw new \Exception("Method $method not found in controller $controller");
                            }
                        } else {
                            throw new \Exception("Controller class $controller not found");
                        }
                    }
                }
            }
        }

        // 404 Not Found
        throw new \Exception("Route not found", 404);
    }

    protected function removeQueryStringVariables(string $url): string
    {
        if ($url != '') {
            $parts = explode('?', $url, 2);
            if (strpos($parts[0], '=') === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }
        }
        return $url;
    }
}
