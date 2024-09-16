<?php

namespace Router\Concerns;

use Router\Exceptions\RouteException;

trait CallsControllers{

    public static function callControllerMethod(string $handler, $params) :void{

        $handlerParts = self::brokeControllerAndMehod('@', $handler);

        self::validateHanlderFormat($handlerParts);

        $controllerName = self::setControllerPath('App\Http\Controllers\\', $handlerParts[0]);
        $methodName = $handlerParts[1];

        self::controllerNotExist($controllerName);

        $controller = new $controllerName();

        self::methodNotExist($controller, $methodName);

        $controller->$methodName(...$params);
    }

    private static function brokeControllerAndMehod(
        string $explodeChar, string $handler
    ) : ? array{
        return explode($explodeChar, $handler);
    }

    private static function validateHanlderFormat($handlerParts) {
        if (count($handlerParts) > 2) {
            throw new Exception('Invalid handler format', 500);
        }
    }

    private static function setControllerPath($path, $controllerName) : string{
        return $path . str_replace('/', '\\', $controllerName);
    }

    private static function controllerNotExist($controllerName){
        if (!class_exists($controllerName)) {
            throw new RouteException('Controller class not found', 500);
        }
    }

    private static function methodNotExist($controller, $methodName){
        if (!method_exists($controller, $methodName)) {
            throw new RouteException('Controller method not found', 500);
        }
    }
}