<?php

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Router\Exceptions\RouteException;
use Router\Route;

class RouteTest extends TestCase{

    protected function setUp() : void{
        $_SERVER['REQUEST_URI'] = '/test';
    }

    public function testAddRouteMethodGet(){
        $_SERVER['REQUEST_METHOD'] = 'GET';
        Route::get('/test', function (){
           echo "test route";
        });

        $this->assertNotEmpty(Route::dispatch());
    }

    public function testAddRouteMethodPost(){
        $_SERVER['REQUEST_METHOD'] = 'POST';
        Route::post('/test', function (){
            echo "test route";
        });

        $this->assertNotEmpty(Route::dispatch());
    }
    
}