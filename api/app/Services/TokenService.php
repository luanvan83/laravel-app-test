<?php

namespace App\Services;

use Illuminate\Http\Request;

class TokenService
{
    protected $tokenName = 'demo-app';

    /**
     * @return \Laravel\Sanctum\NewAccessToken
     */
    public function createTokenFor(\App\Models\User $user)
    {
        return $user->createToken($this->tokenName);
    }

    public function destroyTokenFor(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }
}