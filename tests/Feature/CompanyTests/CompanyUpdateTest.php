<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Str;
use Tests\Helpers\Helper as Helper;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CompanyUpdateTest extends TestCase {
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

    public function testUpdateCompanyName() {

        $randomName = Str::random(5);
        $response = $this->json(
            'PATCH',
            'company',
            [
                'name' => $randomName,
            ],
            [
                'authorization' => 'Bearer ' . $this->loginResponse->access_token
            ]
        );

        $this->assertNotEquals($this->currentCompanyState->name, $response->original->name);
        $response
            ->assertStatus(200)
            ->assertExactJson([
                'id' => $this->currentCompanyState->id,
                'name' => $randomName,
                'photo' => $this->currentCompanyState->photo,
                'description' => $this->currentCompanyState->description,
            ]);
    }

    public function testUpdateCompanyPhoto() {

        $randomPhoto = Str::random(5);
        $response = $this->json(
            'PATCH',
            'company',
            [
                'photo' => $randomPhoto,
            ],
            [
                'authorization' => 'Bearer ' . $this->loginResponse->access_token
            ]
        );

        $this->assertNotEquals($this->currentCompanyState->photo, $response->original->photo);
        $response
            ->assertStatus(200)
            ->assertExactJson([
                'id' => $this->currentCompanyState->id,
                'name' => $this->currentCompanyState->name,
                'photo' => $randomPhoto,
                'description' => $this->currentCompanyState->description,
            ]);
    }

    public function testUpdateCompanyDescription() {

        $randomDescription = Str::random(5);
        $response = $this->json(
            'PATCH',
            'company',
            [
                'description' => $randomDescription,
            ],
            [
                'authorization' => 'Bearer ' . $this->loginResponse->access_token
            ]
        );

        $this->assertNotEquals($this->currentCompanyState->description, $response->original->description);
        $response
            ->assertStatus(200)
            ->assertExactJson([
                'id' => $this->currentCompanyState->id,
                'name' => $this->currentCompanyState->name,
                'photo' => $this->currentCompanyState->photo,
                'description' => $randomDescription,
            ]);
    }
}
