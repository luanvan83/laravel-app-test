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
        if (!is_array($value)) {
            $fail(trans('Invalid input'));
        }
        $uniqueValue = array_unique($value);
        if (count($uniqueValue) > Employee::MAX_RESTAURANT) {
            $fail(trans(
                    'Employee can not work at more than :max restaurants',
                    ['max' => Employee::MAX_RESTAURANT]
                )
            );
        }
        $restaurantRepo = app(IRestaurantRepository::class);
        foreach ($uniqueValue as $id) {
            $foundRestaurant = $restaurantRepo->getModel()->find($id);
            if (!$foundRestaurant) {
                $fail(trans('Restaurant :id not found', ['id' => $id]));
                return;
            }
            if (!$restaurantRepo->canHireNewEmployee($foundRestaurant, $this->ignoreEmployeeId)) {
                $fail(trans('Restaurant :id can not hire more people', ['id' => $id]));
                return;
            }
        }
    }
}
