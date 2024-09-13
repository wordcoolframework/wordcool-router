<?php

namespace Router\Contracts;

interface RouteContract{

    public static function get($url, $handler, $method = 'GET', $middleware = null) :self;

    public static function post($url, $handler, $method = 'POST', $middleware = null) :self;

    public static function put($url, $handler, $method = 'PUT', $middleware = null) :self;

    public static function patch($url, $handler, $method = 'PATCH', $middleware = null) :self;

    public static function delete($url, $handler, $method = 'DELETE', $middleware = null) :self;

    public static function options($url, $handler, $method = 'OPTIONS', $middleware = null) :self;
    
    public static function dispatch();
    
}

