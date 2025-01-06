<?php

namespace Router;

use Exception;

class Stub {

    /**
     * @throws Exception
     */
    public static function get(string $stub_name) : string {

        $exist = getcwd() . "/src/Router/stubs/$stub_name.stub";

        if (!$exist){
            throw new \RuntimeException("Stub File $stub_name not found");

        }

        return $exist;

    }

}