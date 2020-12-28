<?php

namespace Tests\Helpers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class Helper extends TestCase {
    use DatabaseMigrations;

    private $loginResponseJson;

    function __construct() {
        parent::setUp();
    }

    public function registerAndLogin($userPassword = "12345678") {
        $userEmail = Str::random(5) . '@email.com';

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

        $this->loginResponseJson = json_decode($response->getContent());
        return $this->loginResponseJson;
    }

    public function createAndGetCompany() {
        $response = $this->json(
            'POST',
            'company',
            [
                'name' => Str::random(8),
                'description' => Str::random(8),
            ],
            [
                'authorization' => 'Bearer ' . $this->loginResponseJson->access_token
            ]
        );

        return json_decode($response->getContent());

    }

    public function getUserResource() {
        $response = $this->json(
            'GET',
            'user',
            [],
            [
                'authorization' => 'Bearer ' . $this->loginResponseJson->access_token
            ]
        );

        return json_decode($response->getContent());
    }

}
