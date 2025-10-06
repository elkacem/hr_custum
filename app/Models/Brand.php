<?php

namespace App\Models;
use App\Constants\Status;
use App\Traits\GlobalStatus;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use GlobalStatus;

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class)->where('status', Status::ENABLE);
    }
}
