<?php

return [
    // if you use and This Router in WordPress when login in admin panel deActive routing system
    'platform'                      => "wordpress", // or php-pure

    // set Controller Classes Path
    'ControllerPath'                => "App\\Http\\Controllers\\",

    // set Route Middleware Classes Path
    'MiddlewarePath'                => "App\Http\Middlewares\\",

    // if you use Routing With Call Controller you should Separation Method and Controller
    'ControllerMethodSeparation'    => '@'
];
