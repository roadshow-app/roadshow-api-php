<?php

namespace App\Http\Controllers;

use App\Contracts\Interfaces\ResponseInterface;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *    title="Roadshow API",
 *    version="1.0.0",
 *    description="L5 Swagger OpenApi description",
 * )
 */

class Controller extends BaseController {
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /** @var ResponseInterface */
    private $responseInterface;

    public function __construct(ResponseInterface $responseInterface) {
        $this->responseInterface = $responseInterface;
    }

    /**
     * @OA\Get(
     *     path="/",
     *     description="Hello page",
     *     @OA\Response(response="200", description="Hello message :D")
     * )
     */
    public function hello() {
        return $this->responseInterface->handleResponse('hoi :D', 200);
    }

}
