<?php

namespace App\Providers;

use App\Repositories\EmployeeRepository;
use App\Repositories\Contracts\IEmployeeRepository;
use App\Repositories\RestaurantRepository;
use App\Repositories\Contracts\IRestaurantRepository;
use App\Repositories\UserRepository;
use App\Repositories\Contracts\IUserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(IEmployeeRepository::class, EmployeeRepository::class);
        $this->app->bind(IRestaurantRepository::class, RestaurantRepository::class);
        $this->app->bind(IUserRepository::class, UserRepository::class);
    }
}