<?php

namespace App\Rules;

use App\Models\Employee;
use App\Repositories\Contracts\IRestaurantRepository;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EmployeeCanWorkAtRestaurant implements ValidationRule
{
    protected $ignoreEmployeeId = null;
    public function __construct($ignoreEmployeeId = null)
    {
        $this->ignoreEmployeeId = $ignoreEmployeeId;
    }
    
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_array($value) && count($value) > Employee::MAX_RESTAURANT) {
            $fail('Employee can not work at more than 3 restaurants');
        }
        $restaurantRepo = app(IRestaurantRepository::class);
        foreach ($value as $id) {
            $foundRestaurant = $restaurantRepo->getModel()->find($id);
            if (!$foundRestaurant) {
                $fail(sprintf('Restaurant %s not found', $id));
                return;
            }
            if (!$restaurantRepo->canHireNewEmployee($foundRestaurant, $this->ignoreEmployeeId)) {
                $fail(sprintf('Restaurant %s can not hire more people', $id));
                return;
            }
        }
    }
}
