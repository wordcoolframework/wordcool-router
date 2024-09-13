<?php

namespace Router;

use Router\Contracts\RouteContract;
use Router\Exceptions\RouteException;

class Route implements RouteContract{

    private static $routes = array();
    private static array $middlewares = [];

    public static function get($url, $handler, $method = 'GET', $middleware = null) {
        self::addRoute($url, $handler, $method, 'GET', $middleware);
    }
    
    public static function post($url, $handler, $method = 'POST', $middleware = null) {
        self::addRoute($url, $handler, $method, 'POST', $middleware);
    }

    public static function put($url, $handler, $method = 'PUT', $middleware = null) {
        self::addRoute($url, $handler, $method, 'PUT', $middleware);
    }

    public static function patch($url, $handler, $method = 'PATCH', $middleware = null) {
        self::addRoute($url, $handler, $method, 'PATCH', $middleware);
    }

    public static function delete($url, $handler, $method = 'DELETE', $middleware = null) {
        self::addRoute($url, $handler, $method, 'DELETE', $middleware);
    }

    public static function options($url, $handler, $method = 'OPTIONS', $middleware = null) {
        self::addRoute($url, $handler, $method, 'OPTIONS', $middleware);
    }
    
    public static function addRoute($url, $handler, $method, $requestMethod, $middleware = null) {
        $url = preg_replace('/:([a-zA-Z]+)/', '([a-zA-Z0-9_-]+)', $url);
        self::$routes[] = array(
            'url'               => $url,
            'handler'           => $handler,
            'method'            => $method,
            'request_method'    => $requestMethod,
            'middleware'       => $middleware
        );
    }
    
    public static function addMiddleware($middleware){
        self::$middlewares[] = $middleware;
    }

    public static function dispatch()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];
        $found = false;

        if (strpos($uri, 'wp-login') !== false || strpos($uri, 'wp-admin') !== false) {
            return false;
        }
        
        foreach (self::$routes as $route) {
            if ($route['request_method'] != $method) {
                continue;
            }

            // $pattern = '#^' . dirname($_SERVER['SCRIPT_NAME']) . $route['url'] . '$#';
            $pattern = '#^' . $route['url'] . '$#';
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);

                $queryString = parse_url($uri, PHP_URL_QUERY);
                parse_str($queryString, $queryParams);
                $matches[] = $queryParams;

                if ($route['middleware']) {
                    $middlewares = explode(',', $route['middleware']);

                    foreach ($middlewares as $middleware) {
                        if (!in_array($middleware, self::$middlewares)) {
                            throw new RouteException("Middleware '$middleware' is not registered", 500);
                        }

                        $middlewareClass = self::$middlewares[] = $middleware;
                        $pathMiddleware = 'App\Http\Middlewares\\' . $middlewareClass;
                        $middlewareObj = new $pathMiddleware();
                        $middlewareObj->handle();
                        
                        if($middlewareObj->handle() != true){
                            return false;
                        }

                        // if ($middlewareObj->shouldAbort()) {
                        //     return false;
                        // }
                    }
                }

                if (is_callable($route['handler'])) {
                    call_user_func_array($route['handler'], $matches);
                } 
                else{
                    self::callControllerMethod($route['handler'], $matches);
                }
                
                $found = true;
                return true;
            }
        }
        if (!$found) {
            throw new RouteException('Route Not Found', 404);
        }

        return false;
    }

    public static function callControllerMethod($handler, $params){
        $handlerParts = explode('@', $handler);

        if (count($handlerParts) != 2) {
            throw new RouteException('Invalid handler format', 500);
        }

        $controllerName = 'App\Http\Controllers\\' . str_replace('/', '\\', $handlerParts[0]);
        $methodName = $handlerParts[1];

        if (!class_exists($controllerName)) {
            throw new RouteException('Controller class not found', 500);
        }

        $controller = new $controllerName();

        if (!method_exists($controller, $methodName)) {
            throw new RouteException('Controller method not found', 500);
        }

        $controller->$methodName(...$params);
    }    

}