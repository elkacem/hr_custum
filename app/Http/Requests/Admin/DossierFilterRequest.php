<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class DossierFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->guard('admin')->check();
    }

    public function rules(): array
    {
        return [
            // global search
            'q'               => ['nullable','string','max:200'],

            // dossier fields
            'status'          => ['nullable','in:PENDING,APPROVED,REJECTED'],
            'type_dossier'    => ['nullable','in:national,international'],
            'approval_step'   => ['nullable','integer','min:0','max:50'],
            'fournisseur_id'  => ['nullable','integer','exists:fournisseurs,id'],
            'direction_id'    => ['nullable','integer','exists:directions,id'],
            'condition_paiement'=>['nullable','string','max:255'],
            'demande_number'  => ['nullable','string','max:255'],
            'periode'         => ['nullable','string','max:255'],
            'engagement_start'=> ['nullable','date'],
            'engagement_end'  => ['nullable','date','after_or_equal:engagement_start'],
            'montant_ht_min'  => ['nullable','numeric'],
            'montant_ht_max'  => ['nullable','numeric'],
            'montant_ttc_min' => ['nullable','numeric'],
            'montant_ttc_max' => ['nullable','numeric'],
            'monnaie'         => ['nullable','in:USD,EUR'],
            'taux_conv_min'   => ['nullable','numeric'],
            'taux_conv_max'   => ['nullable','numeric'],

            // factures filters
            'has_facture'     => ['nullable','in:0,1'],
            'facture_ref'     => ['nullable','string','max:255'],
            'facture_note'    => ['nullable','string','max:255'],
            'facture_date_start'=>['nullable','date'],
            'facture_date_end'  =>['nullable','date','after_or_equal:facture_date_start'],
            'facture_amount_min'=>['nullable','numeric'],
            'facture_amount_max'=>['nullable','numeric'],

            // sorting
            'sort'            => ['nullable','in:id,engagement_date,status,approval_step,montant_ttc'],
            'dir'             => ['nullable','in:asc,desc'],
        ];
    }
}
