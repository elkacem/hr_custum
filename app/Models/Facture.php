<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{

    protected $fillable = [
        'dossier_id',
        'type_dossier',
        'ref_facture',
        'date_facture',
        'bon_commande',
        'numero_contrat',
        'periode',
        'objet',
        'direction_id',
        'compte_id',
        'montant_ht',
        'taux_tva',
        'montant_ttc',
        'taux_conversion',
        'monnaie',
        'montant_devise',
        'observation',
    ];

    protected $casts = [
        'date_facture' => 'date',
        'montant_ht' => 'decimal:6',
        'taux_tva' => 'decimal:2',
        'custom_tva' => 'decimal:2',
        'remise_percent' => 'decimal:2',
        'taxe_percent' => 'decimal:2',
        'timbre_percent' => 'decimal:2',
        'montant_ttc' => 'decimal:6',
        'taux_conversion' => 'decimal:6',
        'montant_devise' => 'decimal:6',
        'ibs_percent' => 'decimal:2',
        'ibs_devise' => 'decimal:6',
        'montant_devise_net' => 'decimal:6',
        'montant_ttc_local' => 'decimal:6',
        'montant_ht_local' => 'decimal:6',
    ];

    public function dossier(){ return $this->belongsTo(Dossier::class); }
    public function direction(){ return $this->belongsTo(Direction::class); }
    public function compte(){ return $this->belongsTo(Compte::class); }

}
