<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Helpers\Helper as Helper;

class UserIndexTest extends TestCase {
    use DatabaseMigrations;

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
