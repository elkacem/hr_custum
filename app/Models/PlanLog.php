<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\GlobalStatus;

class PlanLog extends Model
{
    use GlobalStatus;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function pickUpLocation()
    {
        return $this->belongsTo(Location::class, 'pick_location');
    }

    //Scopes

    public function scopeUpcoming($query)
    {
        return $query->where('pick_time', '>', now());
    }

    public function scopeRunning($query)
    {
        return $query->where('pick_time', '<', now())->where('drop_time', '>', now());
    }

    public function scopeCompleted($query)
    {
        return $query->where('drop_time', '<', now());
    }
}
