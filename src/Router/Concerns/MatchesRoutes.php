<?php

namespace Router\Concerns;

trait MatchesRoutes {

    public static function matchRoute($uri, $method, &$matches){

        foreach (self::$routes as $route) {
            if ($route['request_method'] != $method) {
                continue;
            }

            $pattern = '#^' . $route['url'] . '$#';
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Remove the full match

                $queryString = parse_url($uri, PHP_URL_QUERY);
                parse_str($queryString, $queryParams);
                $matches[] = $queryParams; // Add query parameters

                return $route;
            }
        }

        throw new RouteException('Route Not Found', 404);
    }

}