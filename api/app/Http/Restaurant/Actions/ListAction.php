<?php

namespace App\Http\Restaurant\Actions;

use App\Http\Controllers\ApiController;
use App\Http\Resources\RestaurantCollection;
use App\Http\Resources\RestaurantResource;
use App\Services\RestaurantService;
use Illuminate\Http\Request;

trait ListAction
{
    /**
     * @OA\Get(
     *     path="/api/restaurants",
     *     summary="Get list of restaurants",
     *     tags={"Restaurants"},
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
    public function listRestaurants()
    {
        $lRestaurants = $this->restaurantService->listRestaurants();
        return $this->sendSuccess(RestaurantResource::collection($lRestaurants), '');
    }
}
