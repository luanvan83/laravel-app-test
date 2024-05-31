<?php

namespace App\Http\Employee\Actions;

use App\Http\Controllers\ApiController;
use App\Http\Resources\EmployeeCollection;
use App\Http\Resources\EmployeeResource;
use App\Services\EmployeeService;
use Illuminate\Http\Request;

trait DetailAction
{
    /**
     * @OA\Get(
     *     path="/api/employees/{id}",
     *     summary="Get employee detail",
     *     tags={"Employees"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Employee ID",
     *         required=true,
     *     ),
     *     @OA\Parameter(
     *         name="X-Requested-With",
     *         in="header",
     *         description="Laravel Ajax",
     *         required=true,
     *         @OA\Schema(type="string", default="XMLHttpRequest")
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function detail($id)
    {
        $employee = $this->employeeService->employeeDetail($id);
        return $this->sendSuccess(new EmployeeResource($employee), '');
    }
}
