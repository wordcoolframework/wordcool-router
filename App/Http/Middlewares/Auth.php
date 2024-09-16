<?php

namespace App\Http\Middlewares;

use Router\Route;

class Auth{

    public function handle(){
        if(1 == 1){
            return true;
        }
        return false;
    }

}