<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends BaseResource
{
    //protected $withoutFields = ['updated_at', 'deleted_at'];

    public function toArray(Request $request)
    {
        $lData = $this->resource->toArray();
        return $this->filterFields($lData);
    }
}
