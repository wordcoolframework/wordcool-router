<?php

require_once 'vendor/autoload.php';
use Router\Route;

Route::addMiddleware('Auth');

Route::get("/", static function (){
   echo \Configuration\Config::get('app.platform');
}, 'GET', 'Auth');

Route::get("/test/:id", static function ($id){
    echo "param is $id";
});

Route::get(
    '/home/:id',
    'HomeController@index',
);

Route::get(
    '/redirect',
    'HomeController@redirect',
);

Route::get('/test/:id','HomeController@test')
    ->name('test');

Route::middleware('Auth', static function (){
    Route::get('/inside-middleware', static function (){
       echo "inside middleware when return true";
    });
});

Route::fallback(static function (){
   echo "404 - not found";
});

Route::dispatch();