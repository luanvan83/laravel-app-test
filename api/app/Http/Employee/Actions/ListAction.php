<?php

namespace App\Http\Employee\Actions;

use App\Http\Controllers\ApiController;
use App\Http\Resources\EmployeeCollection;
use App\Http\Resources\EmployeeResource;
use App\Services\EmployeeService;
use Illuminate\Http\Request;

trait ListAction
{
    /**
     * @OA\Get(
     *     path="/api/employees",
     *     summary="Get list of employees",
     *     tags={"Employees"},
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
    public function listEmployees()
    {
        $lEmployees = $this->employeeService->listEmployees();
        return $this->sendSuccess(EmployeeResource::collection($lEmployees), '');
    }
}
