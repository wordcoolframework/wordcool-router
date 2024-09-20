<?php

namespace Configuration;

final class Config {

    protected static array $config = [];

    public static function get(string $key, string|int $default = null){

        $parts = explode('.', $key);

//        example : app.platform  => app is file | platform in key config
        if (count($parts) === 2) {

            $file = $parts[0];
            $key  = $parts[1];

            if (!isset(self::$config[$file])) {

                $path = $_SERVER['DOCUMENT_ROOT'] . "/src/Router/config/{$file}.php";

                if (file_exists($path)) {
                    self::$config[$file] = include $path;
                } else {
                    return $default;
                }
            }
            return self::$config[$file][$key] ?? $default;
        }
        return $default;
    }

}