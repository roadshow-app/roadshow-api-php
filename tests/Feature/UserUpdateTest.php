<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\Helpers\Helper as Helper;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserUpdateTest extends TestCase {
    use DatabaseMigrations;

    public function testUpdateName() {
        $helper = new Helper();
        $loginResponse = $helper->registerAndLogin();

        $currentUserState = $helper->getUserResource();
        $randomName = Str::random(5);

        $response = $this->json(
            'PATCH',
            'user',
            [
                'name' => $randomName,
            ],
            [
                'authorization' => 'Bearer ' . $loginResponse->access_token
            ]
        );

        $this->assertNotEquals($currentUserState->name, $response->original->name);
        $response
            ->assertStatus(200)
            ->assertExactJson([
                'id' => $currentUserState->id,
                'name' => $randomName,
                'email' => $currentUserState->email
            ]);
    }

    public function testUpdatePassword() {
        $helper = new Helper();

        $curretUserPassword = Str::random(8);
        $loginResponse = $helper->registerAndLogin($curretUserPassword);
        $currentUserState = $helper->getUserResource();

        $randomPassword = Str::random(8);

        $response = $this->json(
            'PATCH',
            'user',
            [
                'password' => $randomPassword,
            ],
            [
                'authorization' => 'Bearer ' . $loginResponse->access_token
            ]
        );

        $response
            ->assertStatus(200)
            ->assertExactJson([
                'id' => $currentUserState->id,
                'name' => $currentUserState->name,
                'email' => $currentUserState->email,
            ]);

        $response = $this->json(
            'POST',
            'login',
            [
                'email' => $currentUserState->email,
                'password' => Str::random(8),
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
