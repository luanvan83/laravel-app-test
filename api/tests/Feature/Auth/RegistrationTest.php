<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_users_can_register(): void
    {
        $ajaxHeaders = [
            'X-Requested-With' => 'XMLHttpRequest',
            'Content-Type' => 'application/josn'
        ];
        $response = $this->post('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ], $ajaxHeaders);

        $json = json_decode($response->getContent());
        $this->assertTrue($json->success);
        $this->assertEquals('Test User', $json->data->name);
    }
}
