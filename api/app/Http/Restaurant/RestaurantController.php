<?php

namespace App\Http\Restaurant;

use App\Http\Controllers\ApiController;
use App\Http\Restaurant\Actions\ListAction;
use App\Services\RestaurantService;
use Illuminate\Http\Request;

class RestaurantController extends ApiController
{
    use ListAction;
    protected $restaurantService;

    public function __construct(RestaurantService $restaurantService)
    {
        $this->restaurantService = $restaurantService;
    }
}
