<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('restaurants_employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained();
            $table->foreignId('employee_id')->constrained();
            $table->timestamps();
        });

        Artisan::call('db:seed', [
            '--class' => 'EmployeeSeeder',
            '--force' => true
        ]);

        Artisan::call('db:seed', [
            '--class' => 'RestaurantSeeder',
            '--force' => true
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurants_employees');
    }
};
