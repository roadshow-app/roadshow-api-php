<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;


class BaseRoutesTest extends TestCase {

    use RefreshDatabase;

    public function testBaseEndPoint() {
        $response = $this->json('GET', '/');

        $response
            ->assertStatus(200)
            ->assertExactJson([
                "hoi :D"
            ]);
    }

    public function testRandomEndPoint() {
        $randomPath = Str::random(40);
        $response = $this->json('GET', "$randomPath");

        $response
            ->assertStatus(404)
            ->assertExactJson([
                "status" => "error",
                "message" => "Route not found"
            ]);
    }
}
