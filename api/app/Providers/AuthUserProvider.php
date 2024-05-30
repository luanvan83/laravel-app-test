<?php

namespace App\Providers;

use Illuminate\Auth\EloquentUserProvider;

class AuthUserProvider extends EloquentUserProvider
{
    public function retrieveByCredentials(array $credentials)
    {
        // to customize here
        return parent::retrieveByCredentials($credentials);
    }
}