<?php

namespace App\Services;

use App\Repositories\Contracts\IEmployeeRepository;
use App\Repositories\Contracts\ITagRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmployeeService
{   
    /**
     * @var \App\Repositories\EmployeeRepository
     */
    protected $employeeRepo;

    public function __construct(
        IEmployeeRepository $employeeRepo
    ) {
        $this->employeeRepo = $employeeRepo;
    }

    /**
     * @param  $cid Number
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function listEmployees($cid = null)
    {
        if ($cid < 1 || !is_numeric($cid)) {
            $cid = null;
        }
        $lEmployees = $this->employeeRepo->paginateEmployees($cid);
        return $lEmployees;
    }

    /**
     * Create new Employee
     * return \App\Model\Employee
     */
    public function addEmployee(Request $request)
    {
        $payload = $request->only($this->employeeRepo->getModel()->getFillable());
        
        $lWorkAtRestaurants = array_unique($request->input('restaurant_ids'));

        Log::info('Before create employee', [$payload, $lWorkAtRestaurants]);
        $newEmployee = $this->employeeRepo->createEmployee($payload, $lWorkAtRestaurants);
        if ($newEmployee) {
            return $newEmployee;
        }
        return null;
    }

    /**
     * @return \App\Models\Employee
     * @throws ModelNotFoundException
     */
    public function updateEmployee(Request $request)
    {
        $id = $request->id;
        // Make sure the article existed
        $this->employeeRepo->findById($id);

        $payload = $request->only($this->employeeRepo->getModel()->getFillable());

        $lWorkAtRestaurants = array_unique($request->input('restaurant_ids'));
        
        Log::info('Before update article', [$payload, $lWorkAtRestaurants]);

        return $this->employeeRepo->updateEmployee($id, $payload, $lWorkAtRestaurants);
    }

    /**
     * @return \App\Models\Employee
     * @throws ModelNotFoundException
     */
    public function employeeDetail($id)
    {
        $id = (int) $id;
        return $this->employeeRepo->employeeDetail($id);
    }

    public function deleteEmployee($id)
    {
        $employee = $this->employeeDetail($id);
        return $employee->delete();
    }
}