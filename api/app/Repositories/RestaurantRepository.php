<?php

namespace App\Repositories;

use App\Models\ArticleCategory;
use App\Models\Restaurant;
use App\Repositories\Contracts\IRestaurantRepository;
use Illuminate\Database\Eloquent\Collection;

class RestaurantRepository extends BaseRepository implements IRestaurantRepository {
    public function __construct(Restaurant $model)
    {
        $this->model = $model;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function listRestaurants()
    {
        return $this->model->orderBy('name', 'asc')->get();
    }

    /**
     * Check if the restaurant can hire more people
     * @param \App\Models\Restaurant $restaurant
     * @param int $ignoreEmployeeId
     */
    public function canHireNewEmployee($restaurant, $ignoreEmployeeId = null)
    {
        $query = $restaurant->employees();
        if ($ignoreEmployeeId != null) {
            $query->wherePivot('employee_id', '<>', $ignoreEmployeeId);
        }
        $employeeCount = $query->count();
        return $employeeCount <= Restaurant::MAX_EMPLOYEE;
    }
}