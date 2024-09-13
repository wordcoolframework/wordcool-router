<?php

require_once 'vendor/autoload.php';
use \Router\Route;

Route::addMiddleware('Auth');

Route::get("/", function (){
   echo "Arash";
}, 'GET', 'Auth');

Route::get("/param/:name", function ($name){
    echo "param is {$name}";
});

Route::get(
    '/home/:id',
    'HomeController@index',
    'GET',
    'Auth'
);

Route::dispatch();