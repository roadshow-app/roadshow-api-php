<?php

namespace App\Contracts\Repositories;

use App\Contracts\Interfaces\ResponseInterface;

class TestResponseRepository implements ResponseInterface {

    public function handleResponse($data = [], $status = 200) {
        return [
            'data' => $data,
            'status' => $status
        ];
    }
}
