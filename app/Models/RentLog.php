<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\GlobalStatus;

class RentLog extends Model
{
    use GlobalStatus;

    protected $casts = [
        'extra_data' => 'array',
        'pick_time' => 'datetime',
        'drop_time' => 'datetime',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function deposit()
    {
        return $this->hasOne(Deposit::class, 'rent_log_id');
    }

    public function pickUpLocation()
    {
        return $this->belongsTo(Location::class, 'pick_location','id');
    }

    public function dropLocation()
    {
        return $this->belongsTo(Location::class, 'drop_location');
    }


    public function dropUpLocation()
    {
        return $this->belongsTo(Location::class, 'drop_location');
    }


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

    public function drivers()
    {
        return $this->hasMany(Driver::class);
    }

}
