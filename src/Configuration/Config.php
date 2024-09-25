<?php

namespace Configuration;

final class Config {

    protected static array $config = [];

    public static function get(string $key, string|int $default = null){

        $parts = self::separationFileAndKey(".", $key);

//        example : app.platform  => app is file | platform in key config
        if (self::countSeparationPartsIsTwo($parts)) {

            $file = self::getPart('file', $parts);
            $key  = self::getPart('key', $parts);

            if (!isset(self::$config[$file])) {

                $path = self::getConfigPath($file);

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


    private static function separationFileAndKey(
        string $character = ".", string $key
    ) : ? array {
        return explode($character, $key);
    }

    private static function countSeparationPartsIsTwo(?array $parts){
        return count($parts) === 2;
    }

    private static function getPart(
        string $partName, $part
    ) : ? string{
        if($partName === 'file'){
            $part = $part[0];
        }
        if($partName === 'key'){
            $part = $part[1];
        }

        return $part;
    }

    private static function getConfigPath(string $file) : ? string{
        return $_SERVER['DOCUMENT_ROOT'] . "/src/Router/config/$file.php";
    }

}