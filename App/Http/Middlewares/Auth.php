<?php

namespace App\Http\Middlewares;

class Auth{

    public function handle(){
        if(1 == 1){
            return true;
        }
        header("location: param/arash");
    }

}