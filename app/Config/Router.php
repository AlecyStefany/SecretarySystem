<?php

namespace app\Config;

class Router
{
    private static $routes = [];

    public static function get($path, $handler)
    {
        self::addRoute('GET', $path, $handler);
    }

    public static function post($path, $handler)
    {
        self::addRoute('POST', $path, $handler);
    }

    public static function put($path, $handler)
    {
        self::addRoute('PUT', $path, $handler);
    }

    public static function delete($path, $handler)
    {
        self::addRoute('DELETE', $path, $handler);
    }

    private static function addRoute($method, $path, $handler)
    {
        self::$routes[$method][$path] = $handler;
    }

    private static function matchRoute($method, $uri)
    {
        if (!isset(self::$routes[$method])) {
            return [null, null];
        }

        foreach (self::$routes[$method] as $route => $handler) {
            preg_match_all('/\{([a-zA-Z0-9_]+)\}/', $route, $paramNames);
            $paramNames = $paramNames[1];

            $pattern = '@^' . preg_replace('/\{[a-zA-Z0-9_]+\}/', '([^/]+)', $route) . '$@';

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);

                $params = [];
                foreach ($paramNames as $index => $name) {
                    $params[$name] = $matches[$index] ?? null;
                }

                return [$handler, $params];
            }
        }

        return [null, null];
    }

    public static function run($dependencies = [])
    {
        $uri = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        $method = $_SERVER['REQUEST_METHOD'];

        list($handler, $params) = self::matchRoute($method, $uri);

        if ($handler) {
            if (is_array($handler)) {
                [$class, $method] = $handler;
                if (!class_exists($class)) {
                    http_response_code(500);
                    echo "Classe $class não encontrada";
                    exit;
                }
                $service = $dependencies[$class] ?? null;
                $controller = $service ? new $class($service) : new $class();
                call_user_func_array([$controller, $method], $params);
            } elseif (is_callable($handler)) {
                call_user_func_array($handler, $params);
            }
        } else {
            http_response_code(404);
            echo json_encode(['ERROR' => 'Rota não encontrada']);
        }
    }
}
