<?php

namespace App\Providers;

use App\Events\TaskCompleted;
use App\Listeners\TaskCompletedListener;
use App\Listeners\TaskCompletedRankCalculator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Event::listen(
            TaskCompleted::class,
            TaskCompletedListener::class
        );

        //Event::listen(
        //    TaskCompleted::class,
        //    TaskCompletedRankCalculator::class
        //);
        
    }
}
