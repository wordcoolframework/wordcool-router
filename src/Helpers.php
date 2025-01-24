<?php

use CoolView\CoolEngine;

require_once getcwd() . "/src/CoolView/Filters/filters.php";

if (!function_exists('req')){

    function req(): \Illuminate\Http\Request{

        return \Illuminate\Http\Request::capture();

    }
}

if (!function_exists('resJson')) {

    function resJson(?array $data, $status_code = 200, $headers = []) {

        http_response_code($status_code);

        foreach ($headers as $key => $value) {
            header("$key: $value");
        }

        header("Content-Type: application/json");

        echo json_encode($data, JSON_THROW_ON_ERROR | true); exit();

    }

}

if(!function_exists('root')){

    function root(): string {

        return dirname(__DIR__, 2);

    }
}

if(!function_exists('getRoutesFile')){

    function getRoutesFile(string $path) : string{

        return getcwd() . "/$path.php";

    }

}

if (!function_exists('__')){

    function __(string $key) : string {

        $currentLang    = \Router\Route::getCurrentLocalized();

        $translations = require getcwd() . "/lang/translate.php";

        if (isset($translations[$currentLang][$key])) {

            return $translations[$currentLang][$key];

        }

        return $key;
    }
}

if(!function_exists('cool')){

    function cool() : CoolEngine{
        return new CoolEngine(getcwd() . '/template/views', getcwd() . '/template/caches');
    }

}


if(!function_exists('route')){

    function route($routeName, ?array $params = []){

        return \Router\Route::route($routeName, $params);

    }

}


if (!function_exists('csrfValidate')){

    function csrfValidate(string $token) : bool {

        return CoolView\CSRFService\CSRFService::validateToken($token);

    }

}