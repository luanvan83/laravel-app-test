<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Restaurant;
use App\Models\RestaurantEmployee;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lRestaurants = [
            [
                'name' => 'MeatChefs',
            ],
            [
                'name' => 'VegeChefs'
            ],
            [
                'name' => 'BurgerChefs'
            ],
        ];
        $lDbRestaurants = [];
        foreach ($lRestaurants as $restaurant) {
            $lDbRestaurants[] = Restaurant::create($restaurant);
        }

        $lEmployees = Employee::all();
        foreach ($lEmployees as $employee) {
            $dbRestaurant = $this->pickARestaurant($lDbRestaurants);
            if ($dbRestaurant !== null) {
                RestaurantEmployee::create([
                    'employee_id' => $employee->id,
                    'restaurant_id' => $dbRestaurant->id,
                ]);
            }
        }
    }

    protected function pickARestaurant($lDbRestaurants)
    {
        $maxLoop = 10;
        $loop = 1;
        while ($loop < $maxLoop) {
            $dbRestaurant = fake()->randomElement($lDbRestaurants);
            if ($dbRestaurant->employees->count() <= Restaurant::MAX_EMPLOYEE) {
                return $dbRestaurant;
            }
            $loop ++;
        }
        return null;
    }
}
