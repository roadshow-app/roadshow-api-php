<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\Helpers\Helper as Helper;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserDeleteTest extends TestCase {
    use DatabaseMigrations;

    public function testDeleteUser() {
        $helper = new Helper();

        $curretUserPassword = Str::random(8);
        $loginResponse = $helper->registerAndLogin($curretUserPassword);
        $currentUserState = $helper->getUserResource();

        $response = $this->json(
            'DELETE',
            'user',
            [],
            [
                'authorization' => 'Bearer ' . $loginResponse->access_token
            ]
        );

        $response
            ->assertStatus(200)
            ->assertExactJson([
                'status' => 'success',
                'message' => "user removed"
            ]);

        $response = $this->json(
            'GET',
            'user',
            [],
            [
                'authorization' => 'Bearer ' . $loginResponse->access_token
            ]
        );

        $response
            ->assertStatus(401)
            ->assertExactJson([
                'status' => 'error',
                'message' => 'Unauthorized',
            ]);

        $response = $this->json(
            'POST',
            'login',
            [
                'email' => $currentUserState->email,
                'password' => $curretUserPassword,
            ]
        );

        $response
            ->assertStatus(401)
            ->assertExactJson([
                'status' => 'error',
                'message' => 'Unauthorized',
            ]);
    }
}
