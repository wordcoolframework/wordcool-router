<?php

namespace Router\Concerns;

trait MatchesRoutes {

    public static function matchRoute($uri, $method, &$matches){

        foreach (self::$routes as $route) {

            if (!self::isRequestMethodMatching($route, $method)) {
                continue;
            }

            $pattern = self::generateRoutePattern($route['url']);

            if (self::matchUriWithPattern($pattern, $uri, $matches)) {

                self::extractQueryParameters($uri, $matches);

                return $route;

            }
        }

        return null;
    }

    private static function isRequestMethodMatching($route, $method) : bool{
        return $route['request_method'] == $method;
    }

    private static function generateRoutePattern($url){
        $pattern = preg_replace('/:([a-zA-Z0-9_-]+)/', '([a-zA-Z0-9_-]+)', $url);
        return '#^' . $pattern . '$#';
    }

    private static function matchUriWithPattern($pattern, $uri, &$matches){
        return preg_match($pattern, $uri, $matches);
    }

    private static function extractQueryParameters($uri, &$matches){
        array_shift($matches); // Remove the full match

        $queryString = parse_url($uri, PHP_URL_QUERY);
        parse_str($queryString, $queryParams);
        $matches[] = $queryParams; // Add query parameters
    }

}