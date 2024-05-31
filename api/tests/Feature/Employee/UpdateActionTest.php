<?php

namespace Tests\Feature\Employee;

use App\Models\Employee;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\FeatureTestCase;
use Tests\TestCase;

class UpdateActionTest extends FeatureTestCase
{
    public function test_guest_access(): void
    {
        $response = $this->post('/api/employees');
        $response->assertStatus(401);
    }

    public function test_validation_error_invalid_email(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $newEmployeeData = [
            'firstname' => fake()->firstName(),
            'last' => fake()->lastName(),
            'email' => 'Invalid email',
        ];
        $response = $this->post('/api/employees', $newEmployeeData);
        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
        $response->assertJsonValidationErrorFor('email', 'data');
        $response->assertJsonValidationErrorFor('restaurant_ids', 'data');
    }

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
    
    public function test_validation_error_unique_email(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $restaurant = Restaurant::factory()->create();

        $employeeData1 = $this->addEmployeeData();
        $email = $employeeData1['email'];

        $email2 = fake()->email();
        $employeeData2 = [
            'firstname' => fake()->firstName(),
            'lastname' => fake()->lastName(),
            'email' => $email2,
            'restaurant_ids' => [ $restaurant->id ]
        ];
        $response2 = $this->post('/api/employees', $employeeData2);
        
        $json = json_decode($response2->getContent());
        $employeeId2 = $json->data->id;
        // Now employeeData2 wants to use the email of employeeData1
        $employeeData2['id'] = $employeeId2;
        $employeeData2['email'] = $email;
        // Try to update
        $response = $this->put('/api/employees/' . $employeeId2, $employeeData2);
        $response->assertStatus(422);

        $response->assertJson(['success' => false]);
        $response->assertJsonValidationErrorFor('email', 'data');
    }

    public function test_validation_error_restaurants(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $limit = Employee::MAX_RESTAURANT + 1;
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
        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
        $expectError = trans(
            'Employee can not work at more than :max restaurants',
            ['max' => Employee::MAX_RESTAURANT]
        );
        $response->assertJsonValidationErrors(['restaurant_ids' => $expectError], 'data');

        // Fake restaurant
        $fakeId = 9999;
        $newEmployeeData = [
            'firstname' => fake()->firstName(),
            'lastname' => fake()->lastName(),
            'email' => $email,
            'restaurant_ids' => [$fakeId, $lRestaurants->first()->id]
        ];
        $response = $this->post('/api/employees', $newEmployeeData);
        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
        $expectError = trans('Restaurant :id not found', ['id' => $fakeId]);
        $response->assertJsonValidationErrors(['restaurant_ids' => $expectError], 'data');
    }

    public function test_update_success(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $newEmployeeData = $this->addEmployeeData();
        
        $newEmployeeData['note'] = 'Updated';
        // Try to update
        $response = $this->put('/api/employees/' . $newEmployeeData['id'], $newEmployeeData);
        unset($newEmployeeData['restaurant_ids']);
        $expectJson = [
            'success' => true,
            'data' => $newEmployeeData
        ];
        $response->assertJson($expectJson);
    }
}
