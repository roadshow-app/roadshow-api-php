<?php

namespace Tests\Unit\Http\Controllers;

use App\Contracts\Repositories\TestResponseRepository;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;

use Mockery;


class ControllerTest extends TestCase {
    use RefreshDatabase;

    public function testHello() {
        $controller = new Controller(new TestResponseRepository());
        $request = Request::create('/', 'get',[
            'title'     =>     'foo',
            'text'     =>     'bar',
        ]);

        $response = $controller->hello();

        $this->assertEquals([
            'data' => 'hoi :D',
            'status' => 200
        ], $response);



        $abc = $controller->callAction('hello', [$request]);
//        $controller::hello($request);

//        $this->assertEquals(302, $response->getStatusCode());


    }
}
