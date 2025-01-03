<?php

namespace Router\Commands;

use CommandStyle\CommandStyle;
use Router\Route;

final class RouteTableMiddlewareCommand extends CommandStyle {

    public string $commandName = 'route:table-middleware';

    public static function handle() : void {

        $routes = Route::all();

        if (empty($routes)) {
            echo "No routes found.\n";
            return;
        }

        $headers = ["Request Method", "Url", "Handler", "Middleware", "Name"];
        $routeRow = [];

        foreach ($routes as $route) {
            if(!empty($route['middleware'])){
                $routeRow[] = [
                    $route['request_method'],
                    $route['url'] !== "" ? $route['url'] : '/',
                    is_string($route['handler']) ? $route['handler'] : 'Closure',
                    $route['middleware'],
                    $route['name'] ?? '-'
                ];
            }
        }

        self::printTable($headers, $routeRow);

    }

}