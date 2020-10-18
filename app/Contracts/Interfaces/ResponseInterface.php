<?php

namespace App\Contracts\Interfaces;

interface ResponseInterface {
    public function handleResponse($data = [], $status = 200);
}
