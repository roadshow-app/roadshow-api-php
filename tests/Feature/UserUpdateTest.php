<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\Helpers\Helpers;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserUpdateTest extends TestCase {
    use DatabaseMigrations;

    public function testUpdateName(){
        $helper = new Helpers();

        $loginResponse = $helper->registerAndLogin();

        $loginResponse = json_decode($loginResponse->getContent());

        dd($loginResponse);

        $response = $this->json(
            'PATCH',
            'user',
            [
                'email' => Str::random(5) . '@email.com',
                'name' => Str::random(5),
                'password' => Str::random(8),
            ],
            [
                'authorization' => 'Bearer ' . $loginResponse->access_token
            ]
        );

        /*$response
            ->assertStatus(200)
            ->assertJsonStructure([
                'access_token', 'token_type', 'expires_in'
            ]);*/
    }

    public function testUpdatePassword(){
        $response = $this->json(
            'POST',
            'register',
            [
                'email' => Str::random(5) . '@email.com',
                'name' => Str::random(5),
                'password' => Str::random(8),
            ]
        );

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'access_token', 'token_type', 'expires_in'
            ]);
    }
}
