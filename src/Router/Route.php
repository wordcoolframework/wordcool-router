<?php

namespace Router;

use Router\Concerns\CallsControllers;
use Router\Concerns\HandlesMiddleware;
use Router\Concerns\MatchesRoutes;
use Router\Contracts\RouteContract;

class Route implements RouteContract{

    use MatchesRoutes, CallsControllers, HandlesMiddleware;

    private static $routes = [];
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

    public static function dispatch(){
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        try {
            $route = self::matchRoute($uri, $method, $matches);

            if(!self::handleMiddleware($route)){
                return false;
            }

            if (is_callable($route['handler'])) {
                call_user_func_array($route['handler'], $matches);
            } else {
                self::callControllerMethod($route['handler'], $matches);
            }
            return true;
        }catch (\Exception $e){
            return false;
        }
    }

}