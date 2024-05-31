<?php

namespace App\Http\Employee\Actions;

use App\Http\Controllers\ApiController;
use App\Http\Employee\Requests\EmployeeUpdateRequest;
use App\Http\Resources\EmployeeCollection;
use App\Http\Resources\EmployeeResource;
use App\Services\EmployeeService;
use Illuminate\Http\Request;

trait UpdateAction
{
    /**
     * @OA\Put(
     *     path="/api/employees/{id}",
     *     summary="Update employee",
     *     tags={"Employees"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Employee ID",
     *         required=true,
     *     ),
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
    public function update(EmployeeUpdateRequest $request)
    {
        $employee = $this->employeeService->updateEmployee($request);
        return $this->sendSuccess(new EmployeeResource($employee), 'Employee has been updated.');
    }
}
