<?php

use \PHPUnit\Framework\TestCase;

class RouteParameterTest extends \PHPUnit\Framework\TestCase{

    public function setUp() : void{
        $_SERVER['REQUEST_URI'] = '/param/123';
        $_SERVER['REQUEST_METHOD'] = 'GET';
    }

    public function testRouteWithParameters(){

        \Router\Route::get('/param/:id', function ($id){
            echo "User ID: $id";
        });

        $this->expectOutputString("User ID: 123");

        \Router\Route::dispatch();
    }

}