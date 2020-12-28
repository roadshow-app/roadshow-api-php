<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserRegisterTest extends TestCase {
    use RefreshDatabase;

    protected function setUp(): void {
        parent::setUp();
        $this->withoutMiddleware(
            ThrottleRequests::class
        );
    }

    public function testRegister(){
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

    public function testRegisterWithNoEmailShoudReturnError(){
        $response = $this->json(
            'POST',
            'register',
            []
        );

        $response
            ->assertStatus(400)
            ->assertExactJson([
                'status' => 'error',
                'message' => 'The email field is required.',
            ]);
    }

    public function testRegisterWithInvalidEmailShoudReturnError(){
        $response = $this->json(
            'POST',
            'register',
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

    public function testRegisterWithAlreadyUsedEmailShoudReturnError() {
        $randomEmail = Str::random(5) . '@email.com';
        $this->json(
            'POST',
            'register',
            [
                'email' => $randomEmail,
                'name' => Str::random(5),
                'password' => Str::random(8),
            ]
        );

        $response = $this->json(
            'POST',
            'register',
            [
                'email' => $randomEmail,
            ]
        );

        $response
            ->assertStatus(400)
            ->assertExactJson([
                'status' => 'error',
                'message' => 'The email has already been taken.',
            ]);
    }

    public function testRegisterWithNoNameShoudReturnError(){
        $response = $this->json(
            'POST',
            'register',
            [
                'email' => Str::random(5) . '@email.com',
            ]
        );

        $response
            ->assertStatus(400)
            ->assertExactJson([
                'status' => 'error',
                'message' => 'The name field is required.',
            ]);
    }

    public function testRegisterWithNoPasswordShoudReturnError(){
        $response = $this->json(
            'POST',
            'register',
            [
                'email' => Str::random(5) . '@email.com',
                'name' => Str::random(5),
            ]
        );

        $response
            ->assertStatus(400)
            ->assertExactJson([
                'status' => 'error',
                'message' => 'The password field is required.',
            ]);
    }

    public function testRegisterWithLessThan8CharactersPasswordShoudReturnError(){
        $response = $this->json(
            'POST',
            'register',
            [
                'email' => Str::random(5) . '@email.com',
                'name' => Str::random(5),
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
}
