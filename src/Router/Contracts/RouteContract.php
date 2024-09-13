<?php

namespace Router\Contracts;

interface RouteContract{

    public static function get($url, $handler, $method = 'GET', $middleware = null);

    public static function post($url, $handler, $method = 'POST', $middleware = null);

    public static function put($url, $handler, $method = 'PUT', $middleware = null);

    public static function patch($url, $handler, $method = 'PATCH', $middleware = null);

    public static function delete($url, $handler, $method = 'DELETE', $middleware = null);

    public static function options($url, $handler, $method = 'OPTIONS', $middleware = null);
    
    public static function dispatch();
    
}

