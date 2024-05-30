<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Restaurant extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    public const MAX_EMPLOYEE = 5;

    public function employees() : BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'restaurants_employees');
    }
}
