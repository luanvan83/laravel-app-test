<?php

namespace Tests\Feature\Employee;

use App\Models\Employee;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\FeatureTestCase;

class DetailActionTest extends FeatureTestCase
{
    public function test_guest_access(): void
    {
        $response = $this->post('/api/employees');
        $response->assertStatus(401);
    }

    public function test_error(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $fakeId = 9999;
        $response = $this->get('/api/employees/'.$fakeId);
        $response->assertStatus(404);
        $response->assertJson(['success' => false]);
    }

    public function test_success(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $limit = Employee::MAX_RESTAURANT - 1;
        $lRestaurants = Restaurant::factory($limit)->create();

        $email = fake()->email();
        $newEmployeeData = [
            'firstname' => fake()->firstName(),
            'lastname' => fake()->lastName(),
            'email' => $email,
            'restaurant_ids' => $lRestaurants->map(function ($restaurant) {
                return $restaurant->id;
            })->toArray()
        ];
        $response = $this->post('/api/employees', $newEmployeeData);

        $json = json_decode($response->getContent());
        $id = $json->data->id;
        $responseDetail = $this->get('/api/employees/'.$id);
        $responseDetail->assertStatus(200);
        $responseDetail->assertJson(['success' => true]);
    }
}
