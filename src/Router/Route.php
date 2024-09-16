<?php

namespace Router;

use Router\Concerns\CallsControllers;
use Router\Concerns\HandlesMiddleware;
use Router\Concerns\MatchesRoutes;
use Router\Contracts\RouteContract;

class Route implements RouteContract{

    use MatchesRoutes, CallsControllers, HandlesMiddleware;

    private static array $routes = [];
    private static array $middlewares = [];
    private static $lastAddedRoute;
    private static $fallback ;

    public static function get($url, $handler, $method = 'GET', $middleware = null) :self {
        self::addRoute($url, $handler, $method, 'GET', $middleware);
        return new self;
    }
    
    public static function post($url, $handler, $method = 'POST', $middleware = null) :self {
        self::addRoute($url, $handler, $method, 'POST', $middleware);
        return new self;
    }

    public static function put($url, $handler, $method = 'PUT', $middleware = null) :self {
        self::addRoute($url, $handler, $method, 'PUT', $middleware);
        return new self;
    }

    public static function patch($url, $handler, $method = 'PATCH', $middleware = null) :self {
        self::addRoute($url, $handler, $method, 'PATCH', $middleware);
        return new self;
    }

    public static function delete($url, $handler, $method = 'DELETE', $middleware = null) :self {
        self::addRoute($url, $handler, $method, 'DELETE', $middleware);
        return new self;
    }

    public static function options($url, $handler, $method = 'OPTIONS', $middleware = null) :self{
        self::addRoute($url, $handler, $method, 'OPTIONS', $middleware);

        return new self;
    }
    
    public static function addRoute($url, $handler, $method, $requestMethod, $middleware = null) :self {
        self::$routes[] = array(
            'url'               => $url,
            'handler'           => $handler,
            'method'            => $method,
            'request_method'    => $requestMethod,
            'middleware'        => $middleware,
            'name'              => null,
        );

        self::$lastAddedRoute = &self::$routes[count(self::$routes) - 1];

        return new self;
    }

    public static function name($routeName) : self {
        if (isset(self::$lastAddedRoute) && is_array(self::$lastAddedRoute)) {
            self::$lastAddedRoute['name'] = $routeName;
        } else {
            throw new \Exception("No route available to name.");
        }
        return new self;
    }

    public static function route(string $name, array $params = []) {
        foreach (self::$routes as $route) {
            if ($route['name'] === $name) {
                $url = $route['url'];

                if (!empty($params)) {
                    foreach ($params as $key => $value) {
                        $url = preg_replace("/:$key/", $value, $url);
                    }
                }

                return $url;
            }
        }
        throw new \Exception('Route not found.');
    }

    public static function middleware(string $name, callable $callback) : void{
        $previousRoutes = self::$routes;
        $callback();

        $newRoutes = array_slice(self::$routes, count($previousRoutes));

        foreach ($newRoutes as &$route) {
            $middlewareClass = 'App\Http\Middlewares\\' . $name;
            if(!class_exists($middlewareClass)){
                throw new \Exception("middleware {$name} not exist");
            }
            $middlewareObj = new $middlewareClass();
            $route['middleware'] = $name;
        }
    }

    public static function fallback(callable $handler) :void{
        self::$fallback = $handler;
    }

    public static function addMiddleware($middleware) :void{
        self::$middlewares[] = $middleware;
    }

    public static function dispatch() :bool{

        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        try {
            $route = self::matchRoute($uri, $method, $matches);

            if (!$route) {
                if (isset(self::$fallback)) {
                    call_user_func(self::$fallback);
                }
                return false;
            }

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