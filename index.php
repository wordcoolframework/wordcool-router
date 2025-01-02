<?php

require_once 'vendor/autoload.php';
use App\Http\Controllers\SimpleController;

use Router\Route;

//Route::get('/', static function () : \Illuminate\Contracts\Support\Jsonable{
//    return resJson([
//        'data' => req()->get('test')
//    ]200);
//});

Route::get('/', static function(){
    (new SimpleController())->index();
})->name('home');

Route::get('/post/:id/comments/:comment', static function (int $postId, string $commentId) {
    echo "Post id : $postId and Commend id: $commentId";
});

Route::addMiddleware('Auth');

Route::get("/asd", static function (){
   echo "asdfaf";
}, 'GET', 'Auth');

Route::get("/test/:id", static function (int $id){
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

Route::get('/all', static function(){
    dd(Route::all());
});

Route::dispatch();