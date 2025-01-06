<?php

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
        echo json_encode($data, true); exit();

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