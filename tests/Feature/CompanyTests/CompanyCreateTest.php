<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Str;
use Tests\Helpers\Helper as Helper;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CompanyCreateTest extends TestCase {
    use RefreshDatabase;

    private $helper;
    private $loginResponse;
    private $currentUserState;

    protected function setUp(): void {
        parent::setUp();
        $this->withoutMiddleware(
            ThrottleRequests::class
        );

        $this->helper = new Helper();
        $this->loginResponse = $this->helper->registerAndLogin();
        $this->currentUserState = $this->helper->getUserResource();

    }

    public function testCreateCompany() {
        $response = $this->json(
            'POST',
            'company',
            [
                'name' => Str::random(8),
                'description' => Str::random(8),
            ],
            [
                'authorization' => 'Bearer ' . $this->loginResponse->access_token
            ]
        );

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'name', 'photo', 'description'
            ]);
    }

    public function testCreateCompanyWithoutName() {
        $response = $this->json(
            'POST',
            'company',
            [
                'description' => Str::random(8),
            ],
            [
                'authorization' => 'Bearer ' . $this->loginResponse->access_token
            ]
        );

        $response
            ->assertStatus(400)
            ->assertExactJson([
                'status' => 'error',
                'message' => 'The name field is required.',
            ]);
    }
}
