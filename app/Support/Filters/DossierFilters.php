<?php
//
//namespace App\Support\Filters;
//
//use Illuminate\Http\Request;
//use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
//use Illuminate\Database\Query\Builder as BaseBuilder;
//
//class DossierFilters
//{
//    /** Apply to Eloquent (Dossier::query()) */
//    public static function eloquent(EloquentBuilder $q, Request $r): EloquentBuilder
//    {
//        // --- Dossier column filters ---
//        $q->when($r->filled('status'), fn($q)=> $q->where('status', $r->status))
//            ->when($r->filled('type_dossier'), fn($q)=> $q->where('type_dossier', $r->type_dossier))
//            ->when($r->filled('approval_step'), fn($q)=> $q->where('approval_step', $r->approval_step))
//            ->when($r->filled('fournisseur_id'), fn($q)=> $q->where('fournisseur_id', $r->fournisseur_id))
//            ->when($r->filled('direction_id'), fn($q)=> $q->where('direction_id', $r->direction_id))
//            ->when($r->filled('condition_paiement'), fn($q)=> $q->where('condition_paiement', 'like', '%'.$r->condition_paiement.'%'))
//            ->when($r->filled('demande_number'), fn($q)=> $q->where('demande_number', 'like', '%'.$r->demande_number.'%'))
//            ->when($r->filled('periode'), fn($q)=> $q->where('periode', 'like', '%'.$r->periode.'%'))
//            ->when($r->filled('engagement_start'), fn($q)=> $q->whereDate('engagement_date', '>=', $r->engagement_start))
//            ->when($r->filled('engagement_end'), fn($q)=> $q->whereDate('engagement_date', '<=', $r->engagement_end))
//            ->when($r->filled('montant_ht_min'), fn($q)=> $q->where('montant_ht', '>=', $r->montant_ht_min))
//            ->when($r->filled('montant_ht_max'), fn($q)=> $q->where('montant_ht', '<=', $r->montant_ht_max))
//            ->when($r->filled('montant_ttc_min'), fn($q)=> $q->where('montant_ttc', '>=', $r->montant_ttc_min))
//            ->when($r->filled('montant_ttc_max'), fn($q)=> $q->where('montant_ttc', '<=', $r->montant_ttc_max))
//            ->when($r->filled('monnaie'), fn($q)=> $q->where('monnaie', $r->monnaie))
//            ->when($r->filled('taux_conv_min'), fn($q)=> $q->where('taux_conversion', '>=', $r->taux_conv_min))
//            ->when($r->filled('taux_conv_max'), fn($q)=> $q->where('taux_conversion', '<=', $r->taux_conv_max));
//
//        // --- Facture-specific filters with whereHas (no duplicates) ---
//        if ($r->filled('has_facture')) {
//            $r->has_facture
//                ? $q->whereHas('factures')
//                : $q->whereDoesntHave('factures');
//        }
//        $q->when($r->filled('facture_ref'), fn($q)=>
//        $q->whereHas('factures', fn($f)=> $f->where('facture_ref','like','%'.$r->facture_ref.'%'))
//        )->when($r->filled('facture_note'), fn($q)=>
//        $q->whereHas('factures', fn($f)=> $f->where('note','like','%'.$r->facture_note.'%'))
//        )->when($r->filled('facture_date_start'), fn($q)=>
//        $q->whereHas('factures', fn($f)=> $f->whereDate('date','>=',$r->facture_date_start))
//        )->when($r->filled('facture_date_end'), fn($q)=>
//        $q->whereHas('factures', fn($f)=> $f->whereDate('date','<=',$r->facture_date_end))
//        )->when($r->filled('facture_amount_min'), fn($q)=>
//        $q->whereHas('factures', fn($f)=> $f->where('amount','>=',$r->facture_amount_min))
//        )->when($r->filled('facture_amount_max'), fn($q)=>
//        $q->whereHas('factures', fn($f)=> $f->where('amount','<=',$r->facture_amount_max))
//        );
//
////        // --- Global search across dossier + facture ---
////        if ($r->filled('q')) {
////            $q->where(function($w) use ($r){
////                $needle = '%'.$r->q.'%';
////                $w->where('demande_number','like',$needle)
////                    ->orWhere('condition_paiement','like',$needle)
////                    ->orWhere('periode','like',$needle)
//////                    ->orWhere('observation','like',$needle)
////                    ->orWhereHas('fournisseur', fn($f)=> $f->where('name','like',$needle))
////                    ->orWhereHas('direction', fn($d)=> $d->where('name','like',$needle))
////                    ->orWhereHas('factures', fn($f)=> $f->where(function($ff) use ($needle){
////                        $ff->where('ref','like',$needle)
////                            ->orWhere('note','like',$needle);
////                    }));
////            });
////        }
//
//        // --- Global search across dossier + facture ---
//        if ($r->filled('q')) {
//            $q->where(function($w) use ($r){
//                $needle = '%'.$r->q.'%';
//
//                // numeric -> search by dossier id too
//                if (ctype_digit($r->q)) {
//                    $w->orWhere('id', (int)$r->q);
//                }
//
//                $w->orWhere('demande_number','like',$needle)
//                    ->orWhere('condition_paiement','like',$needle)
//                    ->orWhere('periode','like',$needle)
//                    // ->orWhere('observation','like',$needle) // <-- REMOVE this line
//                    ->orWhereHas('fournisseur', fn($f)=> $f->where('name','like',$needle))
//                    ->orWhereHas('direction', fn($d)=> $d->where('name','like',$needle))
//                    ->orWhereHas('factures', function ($f) use ($needle, $r) {
//                        if (ctype_digit($r->q)) {
//                            $f->orWhere('id', (int)$r->q);
//                        }
//                        $f->where(function($ff) use ($needle){
//                            $ff->where('facture_ref','like',$needle)
//                                ->orWhere('note','like',$needle);
//                        });
//                    });
//            });
//        }
//
//
//        // sorting whitelist
//        $sort = $r->get('sort','id');
//        $dir  = $r->get('dir','desc');
//        if (!in_array($sort, ['id','engagement_date','status','approval_step','montant_ttc'])) {
//            $sort = 'id';
//        }
//        if (!in_array($dir, ['asc','desc'])) $dir = 'desc';
//
//        return $q->orderBy($sort, $dir);
//    }
//
//    /** Apply to Query Builder with joins (for export rows) */
//    public static function base(BaseBuilder $q, Request $r): BaseBuilder
//    {
//        // Dossier filters (table alias d)
//        $q->when($r->filled('status'), fn($q)=> $q->where('d.status', $r->status))
//            ->when($r->filled('type_dossier'), fn($q)=> $q->where('d.type_dossier', $r->type_dossier))
//            ->when($r->filled('approval_step'), fn($q)=> $q->where('d.approval_step', $r->approval_step))
//            ->when($r->filled('fournisseur_id'), fn($q)=> $q->where('d.fournisseur_id', $r->fournisseur_id))
//            ->when($r->filled('direction_id'), fn($q)=> $q->where('d.direction_id', $r->direction_id))
//            ->when($r->filled('condition_paiement'), fn($q)=> $q->where('d.condition_paiement','like','%'.$r->condition_paiement.'%'))
//            ->when($r->filled('demande_number'), fn($q)=> $q->where('d.demande_number','like','%'.$r->demande_number.'%'))
//            ->when($r->filled('periode'), fn($q)=> $q->where('d.periode','like','%'.$r->periode.'%'))
//            ->when($r->filled('engagement_start'), fn($q)=> $q->whereDate('d.engagement_date','>=',$r->engagement_start))
//            ->when($r->filled('engagement_end'), fn($q)=> $q->whereDate('d.engagement_date','<=',$r->engagement_end))
//            ->when($r->filled('montant_ht_min'), fn($q)=> $q->where('d.montant_ht','>=',$r->montant_ht_min))
//            ->when($r->filled('montant_ht_max'), fn($q)=> $q->where('d.montant_ht','<=',$r->montant_ht_max))
//            ->when($r->filled('montant_ttc_min'), fn($q)=> $q->where('d.montant_ttc','>=',$r->montant_ttc_min))
//            ->when($r->filled('montant_ttc_max'), fn($q)=> $q->where('d.montant_ttc','<=',$r->montant_ttc_max))
//            ->when($r->filled('monnaie'), fn($q)=> $q->where('d.monnaie', $r->monnaie))
//            ->when($r->filled('taux_conv_min'), fn($q)=> $q->where('d.taux_conversion','>=',$r->taux_conv_min))
//            ->when($r->filled('taux_conv_max'), fn($q)=> $q->where('d.taux_conversion','<=',$r->taux_conv_max));
//
//        // Facture filters (table alias f) using whereExists to avoid duplicates side-effects
//        if ($r->filled('has_facture')) {
//            if ($r->has_facture) {
//                $q->whereExists(fn($sq)=>
//                $sq->from('factures as fx')->whereColumn('fx.dossier_id','d.id')
//                );
//            } else {
//                $q->whereNotExists(fn($sq)=>
//                $sq->from('factures as fx')->whereColumn('fx.dossier_id','d.id')
//                );
//            }
//        }
//        $factureFilter = ($r->filled('facture_ref') || $r->filled('facture_note') ||
//            $r->filled('facture_date_start') || $r->filled('facture_date_end') ||
//            $r->filled('facture_amount_min') || $r->filled('facture_amount_max'));
//        if ($factureFilter) {
//            $q->whereExists(function($sq) use ($r){
//                $sq->from('factures as fx')->whereColumn('fx.dossier_id','d.id');
//                $r->filled('facture_ref')       && $sq->where('fx.facture_ref','like','%'.$r->facture_ref.'%');
//                $r->filled('facture_note')      && $sq->where('fx.note','like','%'.$r->facture_note.'%');
//                $r->filled('facture_date_start')&& $sq->whereDate('fx.date','>=',$r->facture_date_start);
//                $r->filled('facture_date_end')  && $sq->whereDate('fx.date','<=',$r->facture_date_end);
//                $r->filled('facture_amount_min')&& $sq->where('fx.amount','>=',$r->facture_amount_min);
//                $r->filled('facture_amount_max')&& $sq->where('fx.amount','<=',$r->facture_amount_max);
//            });
//        }
//
////        // Global search across dossier + joins + facture exists
////        if ($r->filled('q')) {
////            $needle = '%'.$r->q.'%';
////            $q->where(function($w) use ($needle){
////                $w->where('d.demande_number','like',$needle)
////                    ->orWhere('d.condition_paiement','like',$needle)
////                    ->orWhere('d.periode','like',$needle)
////                    ->orWhere('d.observation','like',$needle)
////                    ->orWhere('s.name','like',$needle)
////                    ->orWhere('dir.name','like',$needle)
////                    ->orWhereExists(function($sq) use ($needle){
////                        $sq->from('factures as fx')
////                            ->whereColumn('fx.dossier_id','d.id')
////                            ->where(function($ff) use ($needle){
////                                $ff->where('fx.ref','like',$needle)
////                                    ->orWhere('fx.note','like',$needle);
////                            });
////                    });
////            });
////        }
//
//        // Global search across dossier + joins + facture exists
//        if ($r->filled('q')) {
//            $needle = '%'.$r->q.'%';
//
//            $q->where(function($w) use ($needle, $r){
//                // numeric -> dossier.id and facture.id
//                if (ctype_digit($r->q)) {
//                    $w->orWhere('d.id', (int)$r->q)
//                        ->orWhereExists(function($sq) use ($r){
//                            $sq->from('factures as fx')
//                                ->whereColumn('fx.dossier_id','d.id')
//                                ->where('fx.id', (int)$r->q);
//                        });
//                }
//
//                $w->orWhere('d.demande_number','like',$needle)
//                    ->orWhere('d.condition_paiement','like',$needle)
//                    ->orWhere('d.periode','like',$needle)
//                    // ->orWhere('d.observation','like',$needle) // <-- REMOVE this line
//                    ->orWhere('s.name','like',$needle)
//                    ->orWhere('dir.name','like',$needle)
//                    ->orWhereExists(function($sq) use ($needle){
//                        $sq->from('factures as fx')
//                            ->whereColumn('fx.dossier_id','d.id')
//                            ->where(function($ff) use ($needle){
//                                $ff->where('fx.facture_ref','like',$needle)
//                                    ->orWhere('fx.note','like',$needle);
//                            });
//                    });
//            });
//        }
//
//
//        return $q;
//    }
//}


// app/Support/Filters/DossierFilters.php
namespace App\Support\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as BaseBuilder;

class DossierFilters
{
    public static function eloquent(EloquentBuilder $q, Request $r): EloquentBuilder
    {
        // Dossier fields
        $q->when($r->filled('status'), fn($q)=> $q->where('status', $r->status))
            ->when($r->filled('type_dossier'), fn($q)=> $q->where('type_dossier', $r->type_dossier))
            ->when($r->filled('approval_step'), fn($q)=> $q->where('approval_step', $r->approval_step))
            ->when($r->filled('fournisseur_id'), fn($q)=> $q->where('fournisseur_id', $r->fournisseur_id))
            ->when($r->filled('direction_id'), fn($q)=> $q->where('direction_id', $r->direction_id))
            ->when($r->filled('condition_paiement'), fn($q)=> $q->where('condition_paiement','like','%'.$r->condition_paiement.'%'))
            ->when($r->filled('demande_number'), fn($q)=> $q->where('demande_number','like','%'.$r->demande_number.'%'))
            ->when($r->filled('periode'), fn($q)=> $q->where('periode','like','%'.$r->periode.'%'))
            ->when($r->filled('engagement_start'), fn($q)=> $q->whereDate('engagement_date','>=',$r->engagement_start))
            ->when($r->filled('engagement_end'), fn($q)=> $q->whereDate('engagement_date','<=',$r->engagement_end))
            ->when($r->filled('montant_ht_min'), fn($q)=> $q->where('montant_ht','>=',$r->montant_ht_min))
            ->when($r->filled('montant_ht_max'), fn($q)=> $q->where('montant_ht','<=',$r->montant_ht_max))
            ->when($r->filled('montant_ttc_min'), fn($q)=> $q->where('montant_ttc','>=',$r->montant_ttc_min))
            ->when($r->filled('montant_ttc_max'), fn($q)=> $q->where('montant_ttc','<=',$r->montant_ttc_max))
            ->when($r->filled('monnaie'), fn($q)=> $q->where('monnaie', $r->monnaie))
            ->when($r->filled('taux_conv_min'), fn($q)=> $q->where('taux_conversion','>=',$r->taux_conv_min))
            ->when($r->filled('taux_conv_max'), fn($q)=> $q->where('taux_conversion','<=',$r->taux_conv_max));

        // Facture presence
        if ($r->filled('has_facture')) {
            $r->boolean('has_facture') ? $q->whereHas('factures') : $q->whereDoesntHave('factures');
        }

        // Facture field filters (match migration)
        $q->when($r->filled('facture_ref'), fn($q)=>
        $q->whereHas('factures', fn($f)=> $f->where('ref_facture','like','%'.$r->facture_ref.'%'))
        )->when($r->filled('facture_note'), fn($q)=>
        $q->whereHas('factures', fn($f)=> $f->where('observation','like','%'.$r->facture_note.'%'))
        )->when($r->filled('facture_date_start'), fn($q)=>
        $q->whereHas('factures', fn($f)=> $f->whereDate('date_facture','>=',$r->facture_date_start))
        )->when($r->filled('facture_date_end'), fn($q)=>
        $q->whereHas('factures', fn($f)=> $f->whereDate('date_facture','<=',$r->facture_date_end))
        )->when($r->filled('facture_amount_min'), fn($q)=>
        $q->whereHas('factures', fn($f)=> $f->where('montant_ttc','>=',$r->facture_amount_min))
        )->when($r->filled('facture_amount_max'), fn($q)=>
        $q->whereHas('factures', fn($f)=> $f->where('montant_ttc','<=',$r->facture_amount_max))
        );

        // Global search
        if ($r->filled('q')) {
            $q->where(function($w) use ($r){
                $needle = '%'.$r->q.'%';

                // numeric → search by IDs
                if (ctype_digit($r->q)) {
                    $w->orWhere('id', (int)$r->q)
                        ->orWhereHas('factures', fn($f)=> $f->where('id', (int)$r->q));
                }

                $w->orWhere('demande_number','like',$needle)
                    ->orWhere('condition_paiement','like',$needle)
                    ->orWhere('periode','like',$needle)
                    ->orWhereHas('fournisseur', fn($f)=> $f->where('name','like',$needle))
                    ->orWhereHas('direction', fn($d)=> $d->where('name','like',$needle))
                    ->orWhereHas('factures', function ($f) use ($needle) {
                        $f->where(function($ff) use ($needle){
                            $ff->where('ref_facture','like',$needle)
                                ->orWhere('bon_commande','like',$needle)
                                ->orWhere('objet','like',$needle)
                                ->orWhere('observation','like',$needle);
                        });
                    });
            });
        }

        // sorting
        $sort = $r->get('sort','id');
        $dir  = $r->get('dir','desc');
        if (!in_array($sort, ['id','engagement_date','status','approval_step','montant_ttc'])) $sort = 'id';
        if (!in_array($dir, ['asc','desc'])) $dir = 'desc';

        return $q->orderBy($sort, $dir);
    }
    /** Apply to Query Builder with joins (for export rows) */
    public static function base(BaseBuilder $q, Request $r): BaseBuilder
    {
        // Dossier filters (alias d)
        $q->when($r->filled('status'), fn($q)=> $q->where('d.status', $r->status))
            ->when($r->filled('type_dossier'), fn($q)=> $q->where('d.type_dossier', $r->type_dossier))
            ->when($r->filled('approval_step'), fn($q)=> $q->where('d.approval_step', $r->approval_step))
            ->when($r->filled('fournisseur_id'), fn($q)=> $q->where('d.fournisseur_id', $r->fournisseur_id))
            ->when($r->filled('direction_id'), fn($q)=> $q->where('d.direction_id', $r->direction_id))
            ->when($r->filled('condition_paiement'), fn($q)=> $q->where('d.condition_paiement','like','%'.$r->condition_paiement.'%'))
            ->when($r->filled('demande_number'), fn($q)=> $q->where('d.demande_number','like','%'.$r->demande_number.'%'))
            ->when($r->filled('periode'), fn($q)=> $q->where('d.periode','like','%'.$r->periode.'%'))
            ->when($r->filled('engagement_start'), fn($q)=> $q->whereDate('d.engagement_date','>=',$r->engagement_start))
            ->when($r->filled('engagement_end'), fn($q)=> $q->whereDate('d.engagement_date','<=',$r->engagement_end))
            ->when($r->filled('montant_ht_min'), fn($q)=> $q->where('d.montant_ht','>=',$r->montant_ht_min))
            ->when($r->filled('montant_ht_max'), fn($q)=> $q->where('d.montant_ht','<=',$r->montant_ht_max))
            ->when($r->filled('montant_ttc_min'), fn($q)=> $q->where('d.montant_ttc','>=',$r->montant_ttc_min))
            ->when($r->filled('montant_ttc_max'), fn($q)=> $q->where('d.montant_ttc','<=',$r->montant_ttc_max))
            ->when($r->filled('monnaie'), fn($q)=> $q->where('d.monnaie', $r->monnaie))
            ->when($r->filled('taux_conv_min'), fn($q)=> $q->where('d.taux_conversion','>=',$r->taux_conv_min))
            ->when($r->filled('taux_conv_max'), fn($q)=> $q->where('d.taux_conversion','<=',$r->taux_conv_max));

        // Facture presence
        if ($r->filled('has_facture')) {
            $r->boolean('has_facture')
                ? $q->whereExists(fn($sq)=> $sq->from('factures as fx')->whereColumn('fx.dossier_id','d.id'))
                : $q->whereNotExists(fn($sq)=> $sq->from('factures as fx')->whereColumn('fx.dossier_id','d.id'));
        }

        // Facture field filters
        if ($r->filled('facture_ref') || $r->filled('facture_note') ||
            $r->filled('facture_date_start') || $r->filled('facture_date_end') ||
            $r->filled('facture_amount_min') || $r->filled('facture_amount_max')) {

            $q->whereExists(function($sq) use ($r){
                $sq->from('factures as fx')->whereColumn('fx.dossier_id','d.id');
                $r->filled('facture_ref')        && $sq->where('fx.ref_facture','like','%'.$r->facture_ref.'%');
                $r->filled('facture_note')       && $sq->where('fx.observation','like','%'.$r->facture_note.'%');
                $r->filled('facture_date_start') && $sq->whereDate('fx.date_facture','>=',$r->facture_date_start);
                $r->filled('facture_date_end')   && $sq->whereDate('fx.date_facture','<=',$r->facture_date_end);
                $r->filled('facture_amount_min') && $sq->where('fx.montant_ttc','>=',$r->facture_amount_min);
                $r->filled('facture_amount_max') && $sq->where('fx.montant_ttc','<=',$r->facture_amount_max);
            });
        }

        // Global search
        if ($r->filled('q')) {
            $needle = '%'.$r->q.'%';

            $q->where(function($w) use ($needle, $r){
                // numeric IDs
                if (ctype_digit($r->q)) {
                    $w->orWhere('d.id', (int)$r->q)
                        ->orWhereExists(function($sq) use ($r){
                            $sq->from('factures as fx')
                                ->whereColumn('fx.dossier_id','d.id')
                                ->where('fx.id', (int)$r->q);
                        });
                }

                $w->orWhere('d.demande_number','like',$needle)
                    ->orWhere('d.condition_paiement','like',$needle)
                    ->orWhere('d.periode','like',$needle)
                    ->orWhere('s.name','like',$needle)   // fournisseurs alias 's' when you join
                    ->orWhere('dir.name','like',$needle) // directions alias 'dir' when you join
                    ->orWhereExists(function($sq) use ($needle){
                        $sq->from('factures as fx')
                            ->whereColumn('fx.dossier_id','d.id')
                            ->where(function($ff) use ($needle){
                                $ff->where('fx.ref_facture','like',$needle)
                                    ->orWhere('fx.bon_commande','like',$needle)
                                    ->orWhere('fx.objet','like',$needle)
                                    ->orWhere('fx.observation','like',$needle);
                            });
                    });
            });
        }

        return $q;
    }
}
