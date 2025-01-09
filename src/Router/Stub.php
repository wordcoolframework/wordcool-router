<?php

namespace Router;

use Configuration\Config;
use Exception;

class Stub {

    /**
     * @throws Exception
     */
    public static function get(string $stub_name) : string {

        $stubDirectory = getcwd() . Config::get('StubsDirectory', '/src/Router/stubs');
        $stubPath = $stubDirectory . "/$stub_name.stub";

        if (!$stubPath){
            throw new \RuntimeException("Stub File $stub_name not found");

        }

        return $stubPath;

    }

}