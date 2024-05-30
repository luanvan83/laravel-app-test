<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\IUserRepository;

class UserRepository extends BaseRepository implements IUserRepository
{
    protected $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }
}