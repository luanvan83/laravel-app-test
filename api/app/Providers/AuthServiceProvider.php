<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider {
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        // Binding eloquent.api to our AdminEloquentUserProvider
        Auth::provider('eloquent.api', function($app, array $config) {
            return new AuthUserProvider($app['hash'], $config['model']);
        });
    }
}