<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DossierRejection extends Model
{
    protected $fillable = [
        'dossier_id','admin_id','role','event','reason','step',
        'date_envoi','date_retour','resubmitted_by','resubmit_note',
    ];

    protected $casts = [
        'date_envoi'  => 'date',
        'date_retour' => 'date',
    ];

    public function dossier(){ return $this->belongsTo(Dossier::class); }
    public function admin(){ return $this->belongsTo(Admin::class, 'admin_id'); }
    public function resubmitter(){ return $this->belongsTo(Admin::class, 'resubmitted_by'); }
}
