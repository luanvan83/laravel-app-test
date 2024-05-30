<?php

namespace App\Repositories;

use App\Models\Employee;
use App\Repositories\Contracts\IEmployeeRepository;
use Illuminate\Support\Facades\DB;

class EmployeeRepository extends BaseRepository implements IEmployeeRepository {
    public function __construct(Employee $model)
    {
        $this->model = $model;
    }

    /**
     * @param  $cid Number
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginateEmployees($cid = null)
    {
        $query = $this->model->orderBy('firstname', 'asc');
        $query->with('restaurants:id,name');
        return $query->paginate();
    }

    public function employeeDetail($id) {
        return $this->findById($id, ['*'], ['restaurants:id,name']);
    }

    /**
     * @return \App\Models\Employee
     */
    public function createEmployee($payload, $lRestaurantIds = [])
    {
        DB::beginTransaction();
        try {
            $newEmployee = $this->create($payload);
            if (is_array($lRestaurantIds) && count($lRestaurantIds) > 0) {
                $newEmployee->restaurants()->attach($lRestaurantIds);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        DB::commit();
        return $newEmployee;
    }

    /**
     * @return \App\Models\Employee
     */
    public function updateEmployee($id, $payload, $lRestaurantIds = [])
    {
        DB::beginTransaction();
        try {
            $result = $this->update($id, $payload);
            $employee = $this->findById($id);
            if (is_array($lRestaurantIds) && count($lRestaurantIds) > 0) {
                $employee->restaurants()->sync($lRestaurantIds);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        } 
        DB::commit();
        return $employee;
    }
}