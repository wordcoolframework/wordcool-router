<?php

return [
    // if you use and This Router in WordPress when login in admin panel de Active routing system
    'Platform'                      => "wordpress", // or php-pure

    // Define the parameter format for routes
    'RouteParameterFormat'          => ':param', // Options: ":param" or "{param}"

    // set Controller Classes Path
    'ControllerPath'                => "App\\Http\\Controllers\\",

    // set Route Middleware Classes Path
    'MiddlewarePath'                => "App\Http\Middlewares\\",

    // if you use Routing With Call Controller you should Separation Method and Controller
    'ControllerMethodSeparation'    => "@",

    // Set Base Routes File
    'RoutesFile'                    => "index",

    // set Lang Path
    "LangPath"                      => '/lang/translate/',

    // Set Command Namespace for Register All Commands
    'CommandNamespace'              => "Router\\Commands",

    // Set Command Directory for Register All Commands
    'CommandDirectory'              => "/src/Router/Commands",

    // Set Stubs Directory
    'StubsDirectory'                => "/src/Router/stubs",

];
