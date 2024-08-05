<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \App\Events\TaskCompleted;

class Task extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'user_id',
        'description'
    ];

    public function markCompleted()
    {
        $this->status = 'COMPLETED';
        // Store DB
        $this->save();

        // Trigger Event
        TaskCompleted::dispatch($this);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
