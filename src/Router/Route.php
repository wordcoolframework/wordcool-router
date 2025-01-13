<?php

namespace Router;

use Configuration\Config;
use Exception;
use Router\Concerns\CallsControllers;
use Router\Concerns\HandlesMiddleware;
use Router\Concerns\MatchesRoutes;
use Router\Contracts\RouteContract;
use Router\Exceptions\RouteException;

class Route implements RouteContract{

    use MatchesRoutes, CallsControllers, HandlesMiddleware;

    private static array $routes = [];
    private static array $middlewares = [];
    private static $lastAddedRoute;
    private static $fallback;
    private static ?string $currentPrefix = null;
    private static ?array $currentRoute = null;

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

        $url = rtrim(self::$currentPrefix . '/' . trim($url, '/'), '/');

        self::$routes[] = array(
            'url'               => $url,
            'handler'           => $handler,
            'method'            => $method,
            'request_method'    => $requestMethod,
            'middleware'        => $middleware,
            'name'              => null,
            'validators'        => [],
        );

        self::$lastAddedRoute = &self::$routes[count(self::$routes) - 1];

        return new self;
    }

    public static function all() : ? array{
        return self::$routes;
    }

    public static function localized($url, $handler) : self{

        $languages = Config::get('localize.lang');

        foreach ($languages as $lang) {
            self::get("/$lang" . $url, $handler);
        }

        return new self;
    }

    public static function getCurrentLocalized() : ? string {

        $uri = $_SERVER['REQUEST_URI'];

        $languages = Config::get('localize.lang');

        foreach ($languages as $lang) {
            if (str_starts_with($uri, "/$lang")) {
                return $lang;
            }
        }

        return Config::get('localize.lang')[0];
    }

    public static function name($routeName) : self {
        if (isset(self::$lastAddedRoute) && is_array(self::$lastAddedRoute)) {
            self::$lastAddedRoute['name'] = $routeName;
        } else {
            throw new \RuntimeException("No route available to name.");
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
        throw new Exception('Route not found.');
    }

    public static function middleware(string $name, callable $callback) : void{
        $previousRoutes = self::$routes;
        $callback();

        $newRoutes = array_slice(self::$routes, count($previousRoutes));

        foreach ($newRoutes as &$route) {
            $middlewareClass = "App\Http\Middlewares\\" . $name;
            if(!class_exists($middlewareClass)){
                throw new \RuntimeException("middleware $name not exist");
            }

            $middlewareObj = new $middlewareClass();
            $route['middleware'] = $name;
        }
    }

    public function closureMiddleware(callable $middleware) : self {
        if (!isset(self::$lastAddedRoute)) {
            throw new \RuntimeException("No route available to attach middleware.");
        }

        if (!isset(self::$lastAddedRoute['middleware'])) {
            self::$lastAddedRoute['middleware'] = [];
        }

        self::$lastAddedRoute['middleware'][] = $middleware;

        return $this;
    }

    public static function fallback(callable $handler) :void{
        self::$fallback = $handler;
    }

    public static function addMiddleware($middleware) :void{
        self::$middlewares[] = $middleware;
    }

    public static function prefix(string $prefix, callable $callback): void {
        $previousPrefix = self::$currentPrefix;

        self::$currentPrefix = rtrim($previousPrefix . '/' . trim($prefix, '/'), '/');

        $callback();

        self::$currentPrefix = $previousPrefix;
    }

    public function limiter(int $limit, int $seconds) : self{
        self::$lastAddedRoute['rate_limit'] = [
            'limit' => $limit,
            'seconds' => $seconds
        ];

        return $this;
    }

    public function validate(array $rules): self {
        if (!isset(self::$lastAddedRoute)) {
            throw new Exception("No route available to add validation.");
        }
        self::$lastAddedRoute['validators'] = $rules;
        return $this;
    }

    public function activeBetween(string $startTime, string $endTime): self {
        if (!isset(self::$lastAddedRoute)) {
            throw new \RuntimeException("No route available to set active time range.");
        }
        self::$lastAddedRoute['active_between'] = [
            'start' => $startTime,
            'end' => $endTime,
        ];
        return $this;
    }

    public static function current() : ? array {
        return self::$currentRoute['route'] ?? null;
    }

    public static function currentRouteName() : ? string {
        return self::$currentRoute['route']['name'] ?? null;
    }

    public static function currentRouteAction(): ?string {
        $handler = self::$currentRoute['route']['handler'] ?? null;

        if (is_callable($handler) && $handler instanceof \Closure) {
            return 'closure';
        }

        return is_string($handler) ? $handler : null;
    }

    private static function validateParameters(array $params, array $rules): bool|string {
        $namedParams = [];
        foreach ($rules as $key => $type) {
            $namedParams[$key] = $params[array_search($key, array_keys($rules))];
        }
        foreach ($rules as $key => $type) {
            if (!isset($namedParams[$key])) {
                return "Missing parameter: $key";
            }

            $value = $namedParams[$key];
            switch ($type) {
                case 'int':
                    if (!is_numeric($value)) {
                        return "Parameter '$key' must be an integer.";
                    }
                    break;
                case 'string':
                    if (!is_string($value) || ctype_digit($value)) {
                        return "Parameter '$key' must be a string.";
                    }
                    break;
                default:
                    return "Unsupported validation type: $type";
            }
        }
        return true;
    }

    public static function dispatch() :bool{

        if (PHP_SAPI === 'cli') {
            return false;
        }

        $uri    = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        if(Config::get('app.Platform') === 'wordpress'){
            if (
                str_contains($uri, 'wp-login')
                ||
                str_contains($uri, 'wp-admin')
            ) {
                return false;
            }
        }

        try {
            $route = self::matchRoute($uri, $method, $matches);

            if (!$route) {
                if (isset(self::$fallback)) {
                    call_user_func(self::$fallback);
                }
                return false;
            }

            self::$currentRoute = [
                'route' => $route,
                'matches' => $matches,
            ];

            if (isset($route['rate_limit'])) {
                $rateLimit = $route['rate_limit'];
                $rateLimitKey = $method . $uri;
                if (!RateLimiter::hit($rateLimitKey, $rateLimit['limit'], $rateLimit['seconds'])) {
                    http_response_code(429);
                    echo "Too many requests. Please try again later.";
                    return false;
                }
            }

            if ($route['middleware']) {
                if (is_callable($route['middleware'])) {
                    // اگر middleware یک Closure باشد
                    $middlewareClosure = $route['middleware'];
                    $middlewareResponse = $middlewareClosure($_SERVER, function ($request) {
                        return true;
                    });

                    if ($middlewareResponse !== true) {
                        return false;
                    }
                } elseif (is_array($route['middleware'])) {
                    // اگر middleware یک آرایه باشد
                    foreach ($route['middleware'] as $middleware) {
                        if (is_callable($middleware)) {
                            $middlewareResponse = $middleware($_SERVER, function ($request) {
                                return true;
                            });

                            if ($middlewareResponse !== true) {
                                return false;
                            }
                        } else {
                            $middlewareClass = $middleware;
                            $pathMiddleware = 'App\Http\Middlewares\\' . $middlewareClass;
                            if (!class_exists($pathMiddleware)) {
                                throw new \RuntimeException("Middleware class '$middlewareClass' not found.");
                            }
                            $middlewareObj = new $pathMiddleware();
                            if ($middlewareObj->handle() !== true) {
                                return false;
                            }
                        }
                    }
                } elseif (is_string($route['middleware'])) {
                    // اگر middleware یک رشته باشد
                    $middlewares = explode(',', $route['middleware']);
                    foreach ($middlewares as $middleware) {
                        $middlewareClass = trim($middleware);
                        $pathMiddleware = 'App\Http\Middlewares\\' . $middlewareClass;
                        if (!class_exists($pathMiddleware)) {
                            throw new \RuntimeException("Middleware class '$middlewareClass' not found.");
                        }
                        $middlewareObj = new $pathMiddleware();
                        if ($middlewareObj->handle() !== true) {
                            return false;
                        }
                    }
                } else {
                    throw new \RuntimeException("Middleware must be a callable, an array of callables, or a comma-separated string.");
                }
            }


            if (isset($route['active_between'])) {
                $currentTime = date('Y-m-d H:i:s');
                $startTime = $route['active_between']['start'];
                $endTime = $route['active_between']['end'];

                if ($currentTime < $startTime || $currentTime > $endTime) {
                    http_response_code(404);
                    echo "Route is inactive.";
                    return false;
                }
            }

            $validationResult = self::validateParameters($matches, $route['validators']);
            if ($validationResult !== true) {
                http_response_code(400);
                echo $validationResult;
                return false;
            }

            if (is_callable($route['handler'])) {
                call_user_func_array($route['handler'], $matches);
            } else {
                self::callControllerMethod($route['handler'], $matches);
            }
            return true;
        }catch (Exception){
            return false;
        }
    }

}