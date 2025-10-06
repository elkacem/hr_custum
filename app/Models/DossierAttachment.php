<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DossierAttachment extends Model
{
    protected $fillable = ['dossier_id', 'file_path'];

    public function dossier()
    {
        return $this->belongsTo(Dossier::class);
    }
}
