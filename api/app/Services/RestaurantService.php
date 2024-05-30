<?php

namespace App\Services;

use App\Repositories\Contracts\IRestaurantRepository;

class RestaurantService
{   
    /**
     * @var \App\Repositories\RestaurantRepository
     */
    protected $repo;

    public function __construct(IRestaurantRepository $repo) 
    {
        $this->repo = $repo;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function listRestaurants()
    {
        return $this->repo->listRestaurants();
    }
}