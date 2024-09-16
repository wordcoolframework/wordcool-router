<?php

require_once 'vendor/autoload.php';
use \Router\Route;

Route::addMiddleware('Auth');

Route::get("/", function (){
   echo "Arash";
}, 'GET', 'Auth');

Route::get("/test/:id", function ($id){
    echo "param is {$id}";
});

Route::get(
    '/home/:id',
    'HomeController@index',
    'GET',
);

Route::get(
    '/redirect',
    'HomeController@redirect',
);

Route::get('/test/:id','HomeController@test')
    ->name('test');

Route::middleware('Auth', function (){
    Route::get('/inside-middleware', function (){
       echo "inside middleware when return true";
    });
});

Route::fallback(function (){
   echo "404 - not found";
});

Route::dispatch();