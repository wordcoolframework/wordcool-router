<?php

namespace Router\Concerns;

use Configuration\Config;
use Router\Exceptions\RouteException;

trait HandlesMiddleware{

    public static function handleMiddleware($route){

        if (!isset($middleware) || empty($middleware)) {
            return true;
        }

        $middlewares = self::brokeMiddlewareWithSeperator(',', $route['middleware']);

        foreach ($middlewares as $middleware) {

            self::checkMiddleware($middleware);

            $middlewareObj = self::instantiateMiddleware(
                $middleware,
                Config::get('app.MiddlewarePath'),
            );

            if (!$middlewareObj->handle()) {
                return false;
            }
        }
        return true;
    }

    private static function brokeMiddlewareWithSeperator(
        string $separator, $middleware
    ) : array{
        return explode($separator,$middleware);
    }

    private static function checkMiddleware($middleware){
        if (!in_array($middleware, self::$middlewares)) {
            throw new RouteException("Middleware '$middleware' is not registered", 500);
        }
        return true;
    }

    private static function instantiateMiddleware($middleware, $path){
        $middlewareClass = $path . $middleware;
        return new $middlewareClass();
    }

}