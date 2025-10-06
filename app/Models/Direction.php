<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Direction extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
    ];

    public function dossiers()
    {
        return $this->hasMany(Dossier::class, 'direction_id');
    }

}
