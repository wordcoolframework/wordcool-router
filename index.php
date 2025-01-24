<?php
session_start();

require_once 'vendor/autoload.php';

use App\Http\Controllers\SimpleController;

use Router\Route;

Route::addMiddleware('Auth');

Route::localized('/about',
    'AboutController@index'
);
Route::post('en/handleForm', 'AboutController@handleForm')
    ->name('handleForm');

//Route::get('handle-middileware', static function (){
//    echo "Pass Middleware";
//})->closureMiddleware(
//    middleware: static function (
//        $request, $next
//    ){
//    if (1 !== 1) return $next($request);
//    return false;
//});

Route::get('pass-middleware-with-class', static function (){
   echo "pass Auth middleware with class";
}, middleware: 'Auth');


Route::prefix('admin', static function () {

    Route::get('/', static function(){
       echo "index page in admin";
    });

    Route::get('users', 'AdminController@index');

    Route::post('create','AdminController@create');

});



Route::get('/json', static function () {
    return resJson([
        'data' => [
            'name' => 'arash narimani'
        ]
    ], 200);
});



Route::get('get-users', static function(){
    echo "get users";
})->limiter(3,60);


Route::get('/', static function(){
    (new SimpleController())->index();
})
    ->name('home')
    ->activeBetween(
        '2023-01-01 00:00:00',
        '2023-01-10 23:59:59'
    );

Route::get('post/:id/comments/:comment', static function (int $postId, string $commentId) {
    echo "Post id : $postId and Commend id: $commentId";
})->validate([
    'id'        => 'int',
    'comment'   => 'string'
]);


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
    echo cool()->render('404');
});

Route::get('/all', static function(){
    dd(Route::all());
});

Route::dispatch();