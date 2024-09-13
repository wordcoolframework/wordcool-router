<?php

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Router\Exceptions\RouteException;
use Router\Route;

class RouteTest extends TestCase{

    protected function setUp() : void{
        $_SERVER['REQUEST_URI'] = '/test';
        Route::addMiddleware('Auth');
    }

    public function testGetRouteDispatch(){

        $_SERVER['REQUEST_METHOD'] = 'GET';

        Route::get('/test', function(){
            echo "test";
        });

        ob_start();

        Route::dispatch();

        $output = ob_get_clean();

        $this->assertEquals("test", $output);
    }

    public function testPostRouteDispatch(){

        $_SERVER['REQUEST_METHOD'] = 'POST';

        Route::post('/test', function(){
            echo "post url";
        });

        ob_start();

        Route::dispatch();

        $output = ob_get_clean();

        $this->assertEquals('post url', $output);
    }

    public function testRouteNotFound(){

        $this->expectException(RouteException::class);

        $this->expectExceptionMessage('Route Not Found');

        $_SERVER['REQUEST_URI'] = '/not-found';

        Route::dispatch();
    }

    public function testRouteWithParameter(){
        $_SERVER['REQUEST_URI'] = "/user/12";

        Route::get("/user/:id", function ($id) {
            echo $id;
        });

        ob_start();
        Route::dispatch();
        $output = ob_get_clean();

        $this->assertEquals("12", $output);
    }
    
}