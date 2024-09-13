<?php

namespace Router\Concerns;

use Router\Exceptions\RouteException;

trait CallsControllers{
    public static function callControllerMethod($handler, $params)
    {
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