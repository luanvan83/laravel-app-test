<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Employee extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    public const MAX_RESTAURANT = 3;

    protected $fillable = [
        'firstname',
        'lastname', 
        'email',
        'note',
        'max_restaurant',
        'created_at',
        'updated_at',
    ];

    public function restaurants() : BelongsToMany
    {
        return $this->belongsToMany(Restaurant::class, 'restaurants_employees');
    }
}
