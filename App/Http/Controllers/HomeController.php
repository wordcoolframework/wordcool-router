<?php

namespace App\Http\Controllers;

use Router\Route;

class HomeController{

    public function index(int $id){
        echo "index page {$id}";
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