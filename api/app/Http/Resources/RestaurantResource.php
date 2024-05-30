<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;

class RestaurantResource extends BaseResource
{
    public function toArray(Request $request)
    {
        $lData = $this->resource->toArray();
        $lData['employees_count'] = $this->resource->employees()->count();
        return $this->filterFields($lData);
    }
}
