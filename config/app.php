<?php

return [
    // if you use and This Router in WordPress when login in admin panel de Active routing system
    'Platform'                      => "wordpress", // or php-pure

    // set Controller Classes Path
    'ControllerPath'                => "App\\Http\\Controllers\\",

    // set Route Middleware Classes Path
    'MiddlewarePath'                => "App\Http\Middlewares\\",

    // if you use Routing With Call Controller you should Separation Method and Controller
    'ControllerMethodSeparation'    => "@",

    // Set Base Routes File
    'RoutesFile'                    => "index",

    // Set Command Namespace for Register All Commands
    'CommandNamespace'              => "Router\\Commands",

    // Set Command Directory for Register All Commands
    'CommandDirectory'              => "/src/Router/Commands",
];
