<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = [
        'rent_log_id', 'name', 'license_number', 'license_date', 'license_place'
    ];

    public function rentLog()
    {
        return $this->belongsTo(RentLog::class);
    }

}
