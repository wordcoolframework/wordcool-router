<?php

namespace Router\Concerns;

use Router\Exceptions\RouteException;

trait HandlesMiddleware{

    public static function handleMiddleware($route){
        if ($route['middleware']) {
            $middlewares = explode(',', $route['middleware']);

            foreach ($middlewares as $middleware) {
                if (!in_array($middleware, self::$middlewares)) {
                    throw new RouteException("Middleware '$middleware' is not registered", 500);
                }

                $middlewareClass = 'App\Http\Middlewares\\' . $middleware;
                $middlewareObj = new $middlewareClass();

                if (!$middlewareObj->handle()) {
                    return false;
                }
            }
        }
        return true;
    }

}