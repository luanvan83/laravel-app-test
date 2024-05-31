<?php

namespace Tests\Feature\Employee;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\FeatureTestCase;
use Tests\TestCase;

class DeleteActionTest extends FeatureTestCase
{
    protected function addEmployeeData()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $restaurant = Restaurant::factory()->create();

        $email = fake()->email();
        $employeeData = [
            'firstname' => fake()->firstName(),
            'lastname' => fake()->lastName(),
            'email' => $email,
            'restaurant_ids' => [ $restaurant->id ]
        ];
        $response = $this->post('/api/employees', $employeeData);
        $json = json_decode($response->getContent());
        $employeeData['id'] = $json->data->id;
        return $employeeData;
    }

    public function test_error(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $fakeId = 9999;
        $response = $this->delete('/api/employees/'.$fakeId);
        $response->assertStatus(404);
        $response->assertJson(['success' => false]);
    }

    public function test_delete(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $employeeData = $this->addEmployeeData();
        $response = $this->delete('/api/employees/' . $employeeData['id']);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }
}
