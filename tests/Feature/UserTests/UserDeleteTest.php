<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Str;
use Tests\Helpers\Helper as Helper;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserDeleteTest extends TestCase {
    use RefreshDatabase;

    private $helper;
    private $loginResponse;
    private $currentUserState;
    private $currentCompanyState;

    protected function setUp(): void {
        parent::setUp();
        $this->withoutMiddleware(
            ThrottleRequests::class
        );

        $this->helper = new Helper();
        $this->loginResponse = $this->helper->registerAndLogin();
        $this->currentUserState = $this->helper->getUserResource();
        $this->currentCompanyState = $this->helper->createAndGetCompany();

    }

    public function testDeleteUser() {

        $curretUserPassword = Str::random(8);

        $response = $this->json(
            'DELETE',
            'user',
            [],
            [
                'authorization' => 'Bearer ' . $this->loginResponse->access_token
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
                'authorization' => 'Bearer ' . $this->loginResponse->access_token
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
                'email' => $this->currentUserState->email,
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

    public function testDeleteUserWithCompany() {

        $response = $this->json(
            'DELETE',
            'user',
            [],
            [
                'authorization' => 'Bearer ' . $this->loginResponse->access_token
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
            "company/{$this->currentCompanyState->id}",
            [],
            [
                'authorization' => 'Bearer ' . $this->loginResponse->access_token
            ]
        );

        $response
            ->assertStatus(404)
            ->assertExactJson([
                'status' => 'error',
                'message' => 'Company not found',
            ]);
    }
}
