<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{

    protected $fillable = [
        'ref_facture', 'date_facture', 'montant_ht', 'taux_tva', 'montant_ttc',
        'objet', 'compte_id', 'direction_id', 'dossier_id'
    ];

    public function dossier()
    {
        return $this->belongsTo(Dossier::class);
    }

    public function direction()
    {
        return $this->belongsTo(Direction::class);
    }

}
