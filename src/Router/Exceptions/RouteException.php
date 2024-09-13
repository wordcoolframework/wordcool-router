<?php

namespace Router\Exceptions;

use Exception;
use Throwable;

class RouteException extends Exception {

    public function __construct(
        $message, $code, Throwable $previous = null
    ){
        parent::__construct($message, $code, $previous);
    }

}