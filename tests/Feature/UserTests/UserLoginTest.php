<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserLoginTest extends TestCase {
    use RefreshDatabase;

    protected function setUp(): void {
        parent::setUp();
        $this->withoutMiddleware(
            ThrottleRequests::class
        );
    }

    public function testLogin(){
        $userEmail = Str::random(5) . '@email.com';
        $userPassword = Str::random(8);
        $this->json(
            'POST',
            'register',
            [
                'email' => $userEmail,
                'name' => Str::random(5),
                'password' => $userPassword,
            ]
        );

        $response = $this->json(
            'POST',
            'login',
            [
                'email' => $userEmail,
                'password' => $userPassword,
            ]
        );

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'access_token', 'token_type', 'expires_in'
            ]);
    }

    public function testLoginWithNoEmailShoudReturnError(){
        $response = $this->json(
            'POST',
            'login',
            []
        );

        $response
            ->assertStatus(400)
            ->assertExactJson([
                'status' => 'error',
                'message' => 'The email field is required.',
            ]);
    }

    public function testLoginWithInvalidEmailShoudReturnError(){
        $response = $this->json(
            'POST',
            'login',
            [
                'email' => Str::random(5),
            ]
        );

        $response
            ->assertStatus(400)
            ->assertExactJson([
                'status' => 'error',
                'message' => 'The email must be a valid email address.',
            ]);
    }

    public function testLoginWithNoPasswordShoudReturnError(){
        $response = $this->json(
            'POST',
            'login',
            [
                'email' => Str::random(5) . '@email.com',
            ]
        );

        $response
            ->assertStatus(400)
            ->assertExactJson([
                'status' => 'error',
                'message' => 'The password field is required.',
            ]);
    }

    public function testLoginWithLessThan8CharactersPasswordShoudReturnError(){
        $response = $this->json(
            'POST',
            'login',
            [
                'email' => Str::random(5) . '@email.com',
                'password' => Str::random(7),
            ]
        );

        $response
            ->assertStatus(400)
            ->assertExactJson([
                'status' => 'error',
                'message' => 'The password must be at least 8 characters.',
            ]);
    }

    public function testLoginWithInvalidCredentialsShoudReturnError() {

        $this->json(
            'POST',
            'register',
            [
                'email' => Str::random(5) . '@email.com',
                'name' => Str::random(5),
                'password' => Str::random(8),
            ]
        );

        $response = $this->json(
            'POST',
            'login',
            [
                'email' => Str::random(5) . '@email.com',
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
