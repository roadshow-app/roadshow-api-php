<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Helpers\Helper as Helper;

class UserIndexTest extends TestCase {
    use RefreshDatabase;

    protected function setUp(): void {
        parent::setUp();
        $this->withoutMiddleware(
            ThrottleRequests::class
        );
    }

    public function testUserIndex() {
        $helper = new Helper();
        $loginResponse = $helper->registerAndLogin();

        $response = $this->json(
            'GET',
            'user',
            [],
            [
                'authorization' => 'Bearer ' . $loginResponse->access_token
            ]
        );

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'id', 'name', 'email'
            ]);
    }
}
