<?php

namespace Router\Commands;

use CommandStyle\CommandStyle;
use Router\Route;

final class RouteListCommand extends CommandStyle{

    public static function handle() : void{
        $routes = Route::all();

        if (empty($routes)) {
            echo "No routes found.\n";
            return;
        }

        self::br(2);
        self::hr(135);

        echo self::paddedColor("Method", ['bright_red'], 15)
            . self::paddedColor("Request Method", ['bright_red'], 20)
            . self::paddedColor("URL", ['bright_red'], 30)
            . self::paddedColor("Handler", ['bright_red'], 30)
            . self::paddedColor("Middleware", ['bright_red'], 30)
            . self::paddedColor("Name", ['bright_red'], 20);

        echo self::RepeatChar('-',  135) . PHP_EOL;

        foreach ($routes as $route) {
            echo self::paddedColor($route['method'],['bright_green', 'bold'], 15);
            echo self::paddedColor($route['request_method'], ['bright_yellow', 'bold'],20);
            echo self::paddedColor($route['url'],['bright_white','bold'],30);
            echo str_pad(is_string($route['handler']) ? $route['handler'] : 'Closure', 30);
            echo str_pad($route['middleware'] ?? 'None', 20);
            echo str_pad($route['name'] ?? 'None', 20);
            echo PHP_EOL;
        }

    }

}