<?php

namespace App\Http\Employee;

use App\Http\Controllers\ApiController;
use App\Http\Employee\Actions\CreateAction;
use App\Http\Employee\Actions\DeleteAction;
use App\Http\Employee\Actions\DetailAction;
use App\Http\Employee\Actions\ListAction;
use App\Http\Employee\Actions\UpdateAction;
use Illuminate\Http\Request;
use App\Services\EmployeeService;

class EmployeeController extends ApiController
{
    use ListAction;
    use CreateAction;
    use UpdateAction;
    use DetailAction;
    use DeleteAction;

    protected $employeeService;

    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }
}
