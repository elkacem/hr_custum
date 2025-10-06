<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class Seater extends Model
{
    use GlobalStatus;
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'seater_id')->where('status', Status::ENABLE);
    }
}
