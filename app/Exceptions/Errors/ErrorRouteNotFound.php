<?php

namespace App\Exceptions\Errors;

use Exception;

class ErrorRouteNotFound extends Exception {

    public function __toString() {
        return "Route not found";
    }
}
