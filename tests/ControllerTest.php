<?php

use PHPUnit\Framework\TestCase;

class ControllerTest extends TestCase {

    public function setUp() :void{
        $_SERVER['REQUEST_URI'] = '/controller';
        $_SERVER['REQUEST_METHOD'] = 'GET';
    }

    public function testControllerAction(){
        \Router\Route::get('/controller', 'SimpleController@index');

        $this->expectOutputString("index page simple controller");
        \Router\Route::dispatch();
    }

}