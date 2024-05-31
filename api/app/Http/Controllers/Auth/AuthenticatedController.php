<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\TokenService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthenticatedController extends ApiController
{
    protected $tokenService;
    public function __construct(TokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Handle an incoming authentication request.",
     *     tags={"Auth"},
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="User's email",
     *         required=true,
     *         @OA\Schema(type="string", default="user1@test.app")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="User's password",
     *         required=true,
     *         @OA\Schema(type="string", default="Passw0rd1")
     *     ),
     *     @OA\Parameter(
     *         name="X-Requested-With",
     *         in="header",
     *         description="Laravel Ajax",
     *         required=true,
     *         @OA\Schema(type="string", default="XMLHttpRequest")
     *     ),
     *     @OA\Response(response="200", description="Login successful"),
     *     @OA\Response(response="401", description="Invalid credentials")
     * )
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        $user = Auth::user();
        $token = $this->tokenService->createTokenFor($user);

        $success['token'] =  $token->plainTextToken; 
        $success['name'] =  $user->name;

        return $this->sendSuccess($success, 'User login successfully.');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
        $this->tokenService->destroyTokenFor($request);
        return $this->sendSuccess([], 'User logout successfully.');
    }
}
