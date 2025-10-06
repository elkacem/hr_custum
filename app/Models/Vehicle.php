<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use GlobalStatus;

    protected $casts = [
        'images' => 'object',
        'specifications' => 'object',
    ];


    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function seater()
    {
        return $this->belongsTo(Seater::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class)->with('user');
    }

//    public function rents()
//    {
//        return $this->hasMany(RentLog::class, 'vehicle_id');
//    }

    public function confirmedRents()
    {
        return $this->hasMany(RentLog::class, 'vehicle_id')
            ->whereIn('id', function ($query) {
                $query->select('rent_log_id')
                    ->from('deposits')
                    ->where('status', 1);
            });
    }

    public function booked()
    {
        return $this->rents()->where('drop_time', '>', now())->where('status', Status::ENABLE)->exists();
    }

    public function getAvailableVehicleInModel($pickTime, $dropTime)
    {
        return self::where('model', $this->model)
            ->where('id', '!=', $this->id) // exclude current dossier
            ->where('status', Status::ENABLE)
            ->whereDoesntHave('confirmedRents', function ($q) use ($pickTime, $dropTime) {
                $q->where('status', Status::ENABLE)
                    ->where(function ($query) use ($pickTime, $dropTime) {
                        $query
                            // Case: existing booking overlaps new booking
                            ->where('pick_time', '<', $dropTime)   // starts before new end
                            ->where('drop_time', '>', $pickTime);  // ends after new start
                    });
            })->first();
    }


    public static function getBookedDateRangesForModel($model)
    {
        return self::where('model', $model)
            ->with(['rents' => function ($q) {
                $q->where('status', Status::ENABLE);
            }])
            ->get()
            ->flatMap(function ($vehicle) {
                return $vehicle->rents->map(function ($rent) {
                    return [
                        'start' => Carbon::parse($rent->pick_time)->format('Y-m-d'),
                        'end'   => Carbon::parse($rent->drop_time)->format('Y-m-d'),
                    ];
                });
            })
            ->values()
            ->all();
    }

    public function rentLogs()
    {
        return $this->hasMany(RentLog::class, 'vehicle_id');
    }



}
