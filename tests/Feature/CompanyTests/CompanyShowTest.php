<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Str;
use Tests\Helpers\Helper as Helper;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CompanyShowTest extends TestCase {
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

    public function testShowCompanyById() {

        $response = $this->json(
            'GET',
            "company/{$this->currentCompanyState->id}",
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
            ->assertExactJson([
                'id' => $this->currentCompanyState->id,
                'name' => $this->currentCompanyState->name,
                'photo' => $this->currentCompanyState->photo,
                'description' => $this->currentCompanyState->description,
            ]);
    }

    public function testShowCompanyByIdWithInvalidId() {

        $id = Str::uuid();
        $response = $this->json(
            'GET',
            "company/$id",
            [
                'name' => Str::random(8),
                'description' => Str::random(8),
            ],
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
