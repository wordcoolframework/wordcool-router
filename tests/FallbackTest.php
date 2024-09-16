<?php

use PHPUnit\Framework\TestCase;

class FallbackTest extends TestCase {

    public function setUp(): void{
        $_SERVER['REQUEST_URI'] = '/unknown';
        $_SERVER['REQUEST_METHOD'] = 'GET';
    }

    public function testFallbackRoute(){
        \Router\Route::fallback(function () {
            echo '404 - Page not found';
        });

        $this->expectOutputString('404 - Page not found');
        \Router\Route::dispatch();
    }

}