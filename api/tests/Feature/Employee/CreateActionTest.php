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

class CreateActionTest extends FeatureTestCase
{
    public function test_guest_access(): void
    {
        $response = $this->post('/api/employees');
        $response->assertStatus(401);
    }

    public function test_validation_error_all(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $newEmployeeData = [];
        $response = $this->post('/api/employees', $newEmployeeData);
        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
        $response->assertJsonValidationErrorFor('firstname', 'data');
        $response->assertJsonValidationErrorFor('lastname', 'data');
        $response->assertJsonValidationErrorFor('email', 'data');
        $response->assertJsonValidationErrorFor('restaurant_ids', 'data');
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

    public function test_validation_error_unique_email(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $restaurant = Restaurant::factory()->create();

        $email = fake()->email();
        $newEmployeeData = [
            'firstname' => fake()->firstName(),
            'lastname' => fake()->lastName(),
            'email' => $email,
            'restaurant_ids' => [ $restaurant->id ]
        ];
        $response = $this->post('/api/employees', $newEmployeeData);
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        // Try to submit again
        $response = $this->post('/api/employees', $newEmployeeData);
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

    public function test_create_success(): void
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
        $response->assertStatus(200);
        unset($newEmployeeData['restaurant_ids']);
        $newEmployeeData['note'] = null;
        $newEmployeeData['max_restaurant'] = Employee::MAX_RESTAURANT;
        $expectJson = [
            'success' => true,
            'data' => $newEmployeeData
        ];
        $response->assertJson($expectJson);
    }

    protected function addEmployeeDataToRestaurant($restaurantId)
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $email = fake()->email();
        $employeeData = [
            'firstname' => fake()->firstName(),
            'lastname' => fake()->lastName(),
            'email' => $email,
            'restaurant_ids' => [ $restaurantId ]
        ];
        $response = $this->post('/api/employees', $employeeData);
        $json = json_decode($response->getContent());
        $employeeData['id'] = $json->data->id;
        return $employeeData;
    }

    public function test_validation_error_restaurant_can_not_hire_more(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $limit = Restaurant::MAX_EMPLOYEE;
        $restaurant = Restaurant::factory()->create();
        for ($i=0; $i < $limit; $i++) { 
            $this->addEmployeeDataToRestaurant($restaurant->id);
        }
        // Add more user
        $email = fake()->email();
        $newEmployeeData = [
            'firstname' => 'More employ',
            'lastname' => fake()->lastName(),
            'email' => $email,
            'restaurant_ids' => [$restaurant->id]
        ];
        $response = $this->post('/api/employees', $newEmployeeData);
        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
        $expectError = trans('Restaurant :id can not hire more people', ['id' => $restaurant->id]);
        $response->assertJsonValidationErrors(['restaurant_ids' => $expectError], 'data');
    }

}
