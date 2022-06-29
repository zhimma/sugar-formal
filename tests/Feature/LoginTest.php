<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function setUp():void
    {
        parent::setUp();
    }

    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen()
    { 
        $response = $this->postJson('/login', ['email' => 'TESTmaleVIP@test.com', 'password'=>'123123']);

        $this->assertAuthenticated();
    }

    public function test_users_can_not_authenticate_with_invalid_password()
    {
        $response = $this->postJson('/login', ['email' => 'TESTmaleVIP@test.com', 'password'=>'123123']);

        $this->assertGuest();
    }
}
