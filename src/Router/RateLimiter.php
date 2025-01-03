<?php

namespace Router;

class RateLimiter
{
    private static int $limit = 5;
    private static int $seconds = 60;

    public static function hit(string $key, int $limit = 5, int $seconds = 60): bool
    {
        $currentTime = time();

        self::$limit = $limit;
        self::$seconds = $seconds;

        if (!isset($_SESSION['requests'][$key])) {
            $_SESSION['requests'][$key] = [];
        }

        $_SESSION['requests'][$key] = array_filter($_SESSION['requests'][$key], function ($timestamp) use ($currentTime) {
            return ($currentTime - $timestamp) <= self::$seconds;
        });

        $_SESSION['requests'][$key][] = $currentTime;

        if (count($_SESSION['requests'][$key]) > self::$limit) {
            return false;
        }

        return true;
    }
}