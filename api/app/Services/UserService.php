<?php

namespace App\Services;

use App\Repositories\Contracts\IUserRepository;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * @var App\Repositories\UserRepository
     */
    protected $userRepo;

    public function __construct(IUserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
     * @param  $payload
     * @return App\Model\User
     */
    public function createUserFromRequest(Request $request)
    {
        $payload = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];

        $user = $this->userRepo->create($payload); 
        
        event(new Registered($user));
        return $user;
    }
}