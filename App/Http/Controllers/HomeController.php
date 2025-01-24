<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Router\Route;

class HomeController {

    public function index(int $id) : void {
        echo $id;
    }

    public function redirect(){
        $url = Route::route('test', ['id' => 1]);
        header("Location: $url");
        exit();
    }

    public function test($params){
        var_dump($params);
    }

}