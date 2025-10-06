<?php
//
//namespace App\Models;
//use Illuminate\Database\Eloquent\Factories\HasFactory;
//
//use Illuminate\Database\Eloquent\Model;
//
//class Dossier extends Model
//{
//    use HasFactory;
//
//    protected $fillable = [
//        'ordonnancement', 'engagement_number', 'engagement_date',
//        'fournisseur', 'ref_facture', 'date_facture', 'bon_commande',
//        'periode', 'montant_ht', 'taux_tva', 'montant_tva',
//        'montant_ttc', 'observation',
//        'ordonnancement_number', 'ordonnancement_date', 'demande_number',
//        'dossier_rejete', 'be_number', 'date_envoi', 'date_retour',
//        'direction_emettrice', 'condition_paiement', 'echeancier',
//        'observation_paiement',
//        'status', 'approval_step', 'rejected_by', 'rejection_reason'
//    ];
//
//    const APPROVAL_STEPS = [
//        0 => 'SERVICE_CHIEF',
//        1 => 'DEPARTEMENT_CHIEF',
//        2 => 'STRUCTURE_HEAD',
//    ];
//
//    public function getStatusBadgeAttribute()
//    {
//        return match($this->status) {
//            'APPROVED' => '<span class="badge badge--success">Approuvé</span>',
//            'REJECTED' => '<span class="badge badge--danger">Rejeté</span>',
//            default    => '<span class="badge badge--warning">En attente</span>',
//        };
//    }
//
//    public function rejectedByAdmin()
//    {
//        return $this->belongsTo(User::class, 'rejected_by');
//    }
//}


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Dossier extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_dossier',
        'engagement_date',
        'demande_number',
        'fournisseur',          // si ta colonne s’appelle bien "fournisseur"
        'fournisseur_id',       // si tu as migré vers clé étrangère
        'direction_id',
        'condition_paiement',

        'ref_facture',
        'date_facture',
        'bon_commande',
        'periode',

        'montant_ht',
        'taux_tva',
        'montant_tva',
        'montant_ttc',

        'montant_devise',
        'monnaie',
        'taux_conversion',
        'montant_ttc_local',

        'dossier_rejete',
        'date_envoi',
        'rejection_reason',

        'observation',

        'approval_step',
        'status',
    ];


    /**
     * Workflow approval steps
     * Matches your real roles from the "admins" table
     */
    const APPROVAL_STEPS = [
        0 => 'payment_service',      // Chef Service Paiement
        1 => 'payment_department',   // Chef Département Paiement
        2 => 'other_structure',      // Autre structure
    ];

    /**
     * Return status badge for UI
     */
    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'APPROVED' => '<span class="badge badge--success">Approuvé</span>',
            'REJECTED' => '<span class="badge badge--danger">Rejeté</span>',
            default => '<span class="badge badge--warning">En attente</span>',
        };
    }

    /**
     * Relation to the admin who rejected the dossier
     */
    public function rejectedByAdmin()
    {
        return $this->belongsTo(Admin::class, 'rejected_by'); // Use your Admin model, not User
    }

    public function attachments()
    {
        return $this->hasMany(DossierAttachment::class);
    }

    public function factures()
    {
        return $this->hasMany(Facture::class);
    }

    public function rejections()
    {
        return $this->hasMany(DossierRejection::class);
    }

    public function direction()
    {
        return $this->belongsTo(Direction::class, 'direction_id');
    }


}
