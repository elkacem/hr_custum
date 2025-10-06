<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DossierRejection extends Model
{
    protected $fillable = [
        'dossier_id',
        'admin_id',
        'role',
        'reason',
        'step',
    ];

    public function dossier()
    {
        return $this->belongsTo(Dossier::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
