<?php

namespace App\Http\Controllers;

class HomeController
{
    public function index(int $id){
        echo "index Page {$id}";
    }
}