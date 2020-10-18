<?php

namespace Tests\Helpers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class Helpers extends TestCase {
    use DatabaseMigrations;

    function __construct() {
        parent::setUp();
    }

    public function registerAndLogin() {
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

        return $response;
    }

}
