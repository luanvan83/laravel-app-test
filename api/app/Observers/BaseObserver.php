<?php

namespace App\Observers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class BaseObserver
{    
    public function creating(Model $model)
    {
        $model->created_at = $model->freshTimestamp();
    }

    public function saving(Model $model)
    {
        $model->created_at = $model->freshTimestamp();
    }
}
