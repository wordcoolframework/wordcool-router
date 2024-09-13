<?php

require_once 'vendor/autoload.php';

\Router\Route::addMiddleware('Auth');

\Router\Route::get("/", function (){
   echo "Arash";
}, 'GET', 'Auth');

\Router\Route::get("/param/:name", function ($name){
    echo "param is {$name}";
});

\Router\Route::get('/home/:id', 'HomeController@index', 'GET', 'Auth');

\Router\Route::dispatch();