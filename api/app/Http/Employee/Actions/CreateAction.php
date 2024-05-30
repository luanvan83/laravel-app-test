<?php

namespace App\Http\Employee\Actions;

use App\Http\Controllers\ApiController;
use App\Http\Employee\Requests\EmployeeCreateRequest;
use App\Http\Resources\EmployeeCollection;
use App\Http\Resources\EmployeeResource;
use App\Services\EmployeeService;
use Illuminate\Http\Request;

trait CreateAction
{
    /**
     * @OA\Post(
     *     path="/api/employees",
     *     summary="Add employee",
     *     tags={"Employees"},
     *     @OA\Parameter(
     *         name="firstname",
     *         in="query",
     *         description="Employee's firstname",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="lastname",
     *         in="query",
     *         description="Employee's lastname",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="Employee's email",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="note",
     *         in="query",
     *         description="Note",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="restaurant_ids[]",
     *         in="query",
     *         description="Where the employee is working.",
     *         required=true,
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(type="number")
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="X-Requested-With",
     *         in="header",
     *         description="Laravel Ajax",
     *         required=true,
     *         @OA\Schema(type="string", default="XMLHttpRequest")
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="422", description="Validation errors"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function create(EmployeeCreateRequest $request)
    {
        $newEmployee = $this->employeeService->addEmployee($request);
        return $this->sendSuccess(new EmployeeResource($newEmployee), 'Employee has been added');
    }
}
