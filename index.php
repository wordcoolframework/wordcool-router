<?php
session_start();

require_once 'vendor/autoload.php';

use App\Http\Controllers\SimpleController;

use Router\Route;


Route::prefix('admin', static function () {

    Route::get('/', static function(){
       echo "index page in admin";
    });

    Route::get('users', 'AdminController@index');

    Route::post('create','AdminController@create');

});



//Route::get('/', static function () : \Illuminate\Contracts\Support\Jsonable{
//    return resJson([
//        'data' => req()->get('test')
//    ]200);
//});



Route::get('get-users', static function(){
   echo "get users";
})->limiter(3,60);


Route::get('/', static function(){
    (new SimpleController())->index();
})->name('home');

Route::get('post/:id/comments/:comment', static function (int $postId, string $commentId) {
    echo "Post id : $postId and Commend id: $commentId";
})->validate([
    'id'        => 'int',
    'comment'   => 'string'
]);

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