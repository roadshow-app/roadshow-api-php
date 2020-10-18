<?php

namespace App\Contracts\Repositories;

use App\Contracts\Interfaces\ResponseInterface;

class JsonResponseRepository implements ResponseInterface {

    public function handleResponse($data = [], $status = 200) {
        return response()->json($data, $status);
    }
}
