<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::get('/users/{user?}', function (User $user) {
    return View::make('welcome', ['user' => $user]);
});
