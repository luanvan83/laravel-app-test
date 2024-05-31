<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\FeatureTestCase;
use Tests\TestCase;

class RegistrationTest extends FeatureTestCase
{
    public function test_new_users_can_register(): void
    {
        $response = $this->post('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $json = json_decode($response->getContent());
        $this->assertTrue($json->success);
        $this->assertEquals('Test User', $json->data->name);
    }
}
