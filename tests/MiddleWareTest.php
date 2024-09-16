<?php

use PHPUnit\Framework\TestCase;

class MiddleWareTest extends TestCase {

    public function SetUp() :void{
        $_SERVER['REQUEST_URI'] = '/test';

        \Router\Route::addMiddleware('Auth');
    }

    public function testMiddlewareExecution(){
        $middleware = new class {
            public function handle(){
                return true;
            }
        };

        \Router\Route::middleware('Auth', function (){
            \Router\Route::get('test', function (){
                echo "Passeed";
            });
        });

        $this->assertTrue($middleware->handle());
    }

}