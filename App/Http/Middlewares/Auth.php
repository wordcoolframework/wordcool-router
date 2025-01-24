<?php

namespace App\Http\Middlewares;

class Auth{

    public function handle() : bool{
        if(1 === 1){
            return true;
        }
        return false;
    }

}