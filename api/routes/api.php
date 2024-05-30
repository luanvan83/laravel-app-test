<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::group(['prefix' => 'employees'], function () {
        Route::get('/', ['as' => 'employees.list', 'uses' => 'App\Http\Employee\EmployeeController@listEmployees']);
        Route::get('/{id}', ['as' => 'employees.detail', 'uses' => 'App\Http\Employee\EmployeeController@detail']);
        Route::post('/', ['as' => 'employees.create', 'uses' => 'App\Http\Employee\EmployeeController@create']);
        Route::put('/{id}', ['as' => 'employees.update', 'uses' => 'App\Http\Employee\EmployeeController@update']);
        Route::delete('/{id}', ['as' => 'employees.delete', 'uses' => 'App\Http\Employee\EmployeeController@destroy']);
    });

    Route::group(['prefix' => 'restaurants'], function () {
        Route::get('/', ['as' => 'restaurants.list', 'uses' => 'App\Http\Restaurant\RestaurantController@listRestaurants']);
    });
});

require __DIR__.'/auth.php';