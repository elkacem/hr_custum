<?php
//////
//////namespace App\Http\Controllers\Admin;
//////
//////use App\Http\Controllers\Controller;
//////use App\Models\Dossier;
//////use App\Models\Fournisseur;
//////use Illuminate\Http\Request;
//////
//////class DossierController extends Controller
//////{
//////    public function index(Request $request)
//////    {
//////        $query = Dossier::query();
//////
//////        if ($request->filled('fournisseur')) {
//////            $query->where('fournisseur', 'like', "%{$request->fournisseur}%");
//////        }
//////
//////        if ($request->filled('date')) {
//////            $query->whereDate('engagement_date', $request->date);
//////        }
//////
//////        $dossiers = $query->latest()->paginate(10);
//////        $pageTitle = 'Tous Dossiers';
//////
////////        return view('admin.dossier.index', compact('dossier'));
//////        return view('admin.dossier.index', compact('pageTitle', 'dossiers'));
//////    }
//////
//////    public function create()
//////    {
//////        $pageTitle = 'All Vehicles';
//////        $fournisseurs = Fournisseur::orderBy('name')->get();
//////
//////
//////        return view('admin.dossier.create', compact('pageTitle', 'fournisseurs'));
//////    }
//////
//////    public function store(Request $request)
//////    {
//////        $validated = $request->validate([
//////            'engagement_date'    => 'required|date',
//////            'fournisseur'        => 'required|exists:fournisseurs,id',
//////            'ref_facture'        => 'nullable|string|max:255',
//////            'date_facture'       => 'nullable|date',
//////            'bon_commande'       => 'nullable|string|max:255',
//////            'periode'            => 'nullable|string|max:20',
//////
//////            'montant_ht'         => 'nullable|numeric',
//////            'taux_tva'           => 'nullable|numeric',
//////            'montant_ttc'        => 'nullable|numeric',
//////            'observation'        => 'nullable|string',
//////
//////            'ordonnancement_date'=> 'nullable|date',
//////            'demande_number'     => 'nullable|string|max:255',
//////            'dossier_rejete'     => 'nullable|boolean',
//////            'be_number'          => 'nullable|string|max:255',
//////            'date_envoi'         => 'nullable|date',
//////            'date_retour'        => 'nullable|date',
//////
//////            'direction_emettrice'=> 'nullable|string|max:255',
//////            'condition_paiement' => 'nullable|string|max:255',
//////            'echeancier'         => 'nullable|string|max:255',
//////            'observation_paiement'=> 'nullable|string',
//////        ]);
//////
//////        // Calcul automatique TVA
//////        if (!empty($validated['montant_ht']) && !empty($validated['taux_tva'])) {
//////            $validated['montant_tva'] = $validated['montant_ht'] * ($validated['taux_tva'] / 100);
//////        } else {
//////            $validated['montant_tva'] = null;
//////        }
//////
//////        Dossier::create($validated);
//////
//////        return redirect()->route('admin.dossiers.index')
//////            ->with('success', 'Dossier créé avec succès.');
//////    }
//////
//////
//////    public function approve(Dossier $dossier)
//////    {
//////        $user = auth()->user();
//////
//////        // Vérifier le rôle de l'utilisateur
//////        if ($user->hasRole('SERVICE_CHIEF') && $dossier->approval_step === 0) {
//////            $dossier->approval_step = 1;
//////        } elseif ($user->hasRole('DEPARTEMENT_CHIEF') && $dossier->approval_step === 1) {
//////            $dossier->approval_step = 2;
//////        } elseif ($user->hasRole('STRUCTURE_HEAD') && $dossier->approval_step === 2) {
//////            $dossier->approval_step = 3;
//////            $dossier->status = 'APPROVED';
//////        } else {
//////            return back()->withErrors('Vous ne pouvez pas approuver ce dossier.');
//////        }
//////
//////        $dossier->save();
//////
//////        return back()->with('success', 'Dossier approuvé avec succès.');
//////    }
//////
//////    public function reject(Request $request, Dossier $dossier)
//////    {
//////        $request->validate(['reason' => 'required|string|max:255']);
//////
//////        $dossier->status = 'REJECTED';
//////        $dossier->rejected_by = auth()->id();
//////        $dossier->rejection_reason = $request->reason;
//////        $dossier->save();
//////
//////        return back()->with('error', 'Dossier rejeté.');
//////    }
//////
//////
//////    public function details($id)
//////    {
//////        $dossier = Dossier::findOrFail($id);
//////        $pageTitle = "Détails du Dossier #{$dossier->id}";
//////
//////        return view('admin.dossier.details', compact('pageTitle', 'dossier'));
//////    }
//////
//////
//////}
////
////
////namespace App\Http\Controllers\Admin;
////
////use App\Http\Controllers\Controller;
////use App\Models\Dossier;
////use App\Models\Fournisseur;
////use Illuminate\Http\Request;
////
////class DossierController extends Controller
////{
////    public function index(Request $request)
////    {
////        $query = Dossier::query();
////
////        if ($request->filled('fournisseur')) {
////            $query->where('fournisseur', 'like', "%{$request->fournisseur}%");
////        }
////
////        if ($request->filled('date')) {
////            $query->whereDate('engagement_date', $request->date);
////        }
////
////        $dossiers = $query->latest()->paginate(10);
////        $pageTitle = 'Tous Dossiers';
////
////        return view('admin.dossier.index', compact('pageTitle', 'dossiers'));
////    }
////
////    public function create()
////    {
////        $pageTitle = 'Créer un Dossier';
////        $fournisseurs = Fournisseur::orderBy('name')->get();
////
////        return view('admin.dossier.create', compact('pageTitle', 'fournisseurs'));
////    }
////
////    public function store(Request $request)
////    {
////        $validated = $request->validate([
////            'engagement_date' => 'required|date',
////            'fournisseur' => 'required|string|max:255',
////            'ref_facture' => 'nullable|string|max:255',
////            'date_facture' => 'nullable|date',
////            'montant_ht' => 'nullable|numeric',
////            'taux_tva' => 'nullable|numeric',
////            'montant_ttc' => 'nullable|numeric',
////            'observation' => 'nullable|string',
////        ]);
////
////        // Calcul automatique TVA
////        if (!empty($validated['montant_ht']) && !empty($validated['taux_tva'])) {
////            $validated['montant_tva'] = $validated['montant_ht'] * ($validated['taux_tva'] / 100);
////        } else {
////            $validated['montant_tva'] = null;
////        }
////
////        // init workflow
////        $validated['approval_step'] = 0;
////        $validated['status'] = 'PENDING';
////
////        Dossier::create($validated);
////
////        return redirect()->route('dossiers.index')
////            ->with('success', 'Dossier créé avec succès.');
////    }
////
////    public function approve(Dossier $dossier)
////    {
////        $user = auth()->user();
////
////        // workflow mapping
////        $steps = [
////            0 => 'SERVICE_CHIEF',
////            1 => 'DEPARTEMENT_CHIEF',
////            2 => 'STRUCTURE_HEAD',
////        ];
////
////        // security check
////        if (!isset($steps[$dossier->approval_step]) || !$user->hasRole($steps[$dossier->approval_step])) {
////            return back()->withErrors('Vous ne pouvez pas approuver ce dossier à cette étape.');
////        }
////
////        // advance step
////        $dossier->approval_step++;
////
////        // last step reached?
////        if ($dossier->approval_step >= count($steps)) {
////            $dossier->status = 'APPROVED';
////        }
////
////        $dossier->save();
////
////        return back()->with('success', 'Dossier approuvé avec succès.');
////    }
////
////    public function reject(Request $request, Dossier $dossier)
////    {
////        $request->validate(['reason' => 'required|string|max:255']);
////
////        if ($dossier->status !== 'PENDING') {
////            return back()->withErrors('Ce dossier est déjà finalisé.');
////        }
////
////        $dossier->status = 'REJECTED';
////        $dossier->rejected_by = auth()->id();
////        $dossier->rejection_reason = $request->reason;
////        $dossier->save();
////
////        return back()->with('error', 'Dossier rejeté.');
////    }
////
////    public function details($id)
////    {
////        $dossier = Dossier::findOrFail($id);
////        $pageTitle = "Détails du Dossier #{$dossier->id}";
////
////        return view('admin.dossier.details', compact('pageTitle', 'dossier'));
////    }
////}
//
//
//namespace App\Http\Controllers\Admin;
//
//use App\Http\Controllers\Controller;
//use App\Models\Condition;
//use App\Models\Direction;
//use App\Models\Dossier;
//use App\Models\DossierAttachment;
//use App\Models\DossierRejection;
//use App\Models\Fournisseur;
//use Illuminate\Http\Request;
//
//class DossierController extends Controller
//{
//    public function index(Request $request)
//    {
//        $query = Dossier::query();
//
//        if ($request->filled('fournisseur')) {
//            $query->where('fournisseur', 'like', "%{$request->fournisseur}%");
//        }
//
//        if ($request->filled('date')) {
//            $query->whereDate('engagement_date', $request->date);
//        }
//
//        $dossiers = $query->latest()->paginate(10);
//        $pageTitle = 'Tous Dossiers';
//
//        return view('admin.dossier.index', compact('pageTitle', 'dossiers'));
//    }
//
//    public function create()
//    {
//        $pageTitle = 'Créer un Dossier';
//        $fournisseurs = Fournisseur::orderBy('name')->get();
//        $directions = Direction::orderBy('id')->get();
//        $conditions = Condition::all();
//
//        return view('admin.dossier.create', compact('pageTitle', 'fournisseurs', 'directions', 'conditions'));
//    }
//
//    public function store(Request $request)
//    {
////        dd($request->rejection_reason);
//        $validated = $request->validate([
//            'type_dossier'       => 'required|in:national,international',
//            'engagement_date'    => 'required|date',
//            'demande_number'     => 'nullable|string|max:255',
//            'fournisseur_id'        => 'required|exists:fournisseurs,id',
//            'direction_id'       => 'nullable|exists:directions,id',
//            'condition_paiement' => 'nullable|string|max:255',
//
//            'montant_ht'         => 'nullable|numeric',
//            'taux_tva'           => 'nullable',
//            'custom_tva'         => 'nullable|numeric',
//            'montant_ttc'        => 'nullable|numeric',
//
//            'montant_devise'     => 'nullable|numeric',
//            'monnaie'            => 'nullable|string|max:10',
//            'taux_conversion'    => 'nullable|numeric',
//            'montant_ttc_local'  => 'nullable|numeric',
//
//            'periode'            => 'nullable|string|max:255',
//            'dossier_rejete'     => 'nullable|boolean',
//            'date_envoi'         => 'nullable|date',
//        ]);
//
//        // --- TVA ---
//        if ($validated['taux_tva'] === 'custom') {
//            $validated['taux_tva'] = $validated['custom_tva'] ?? null;
//        }
//        unset($validated['custom_tva']);
//
//        if (!empty($validated['montant_ht']) && !empty($validated['taux_tva'])) {
//            $validated['montant_ttc'] = $validated['montant_ht'] + ($validated['montant_ht'] * $validated['taux_tva'] / 100);
//        }
//
//        if ($validated['type_dossier'] === 'international' && !empty($validated['montant_ttc']) && !empty($validated['taux_conversion'])) {
//            $validated['montant_ttc_local'] = $validated['montant_ttc'] * $validated['taux_conversion'];
//        }
//
//        // --- Workflow & Rejection ---
//        if (!empty($validated['dossier_rejete']) && $validated['dossier_rejete']) {
//            $validated['status'] = 'REJECTED';
//            $validated['approval_step'] = 0;
//        } else {
//            $validated['status'] = 'PENDING';
//            $validated['approval_step'] = 0;
//            $validated['rejection_reason'] = null;
//        }
//
//        // Save dossier
//        $dossier = Dossier::create($validated);
//
////        dd($dossier->status);
//
//        // --- Log rejection if needed ---
//        if ($dossier->status === 'REJECTED') {
//            $user = auth()->guard('admin')->user();
//
//            DossierRejection::create([
//                'dossier_id' => $dossier->id,
//                'admin_id'   => $user->id,
//                'role'       => $user->role,
//                'reason'     => $request->rejection_reason,
//                'step'       => $dossier->approval_step,
//            ]);
//        }
//
//        // --- Attachments ---
//        if ($request->hasFile('attachments')) {
//            foreach ($request->file('attachments') as $file) {
//                $path = $file->store("dossiers/{$dossier->id}", 'private');
//                $dossier->attachments()->create([
//                    'file_path' => $path,
//                ]);
//            }
//        }
//
//        return redirect()->route('admin.dossiers.index')
//            ->with('success', "Dossier #{$dossier->id} créé avec succès.");
//    }
//
//
//
//    public function edit($id)
//    {
//        $dossier = Dossier::findOrFail($id);
//        $pageTitle = "Modifier le Dossier #{$dossier->id}";
//        $fournisseurs = Fournisseur::orderBy('name')->get();
//        $directions = Direction::orderBy('id')->get();
//        $conditions = Condition::all();
//        return view('admin.dossier.edit', compact('pageTitle', 'dossier', 'fournisseurs', 'directions', 'conditions'));
//    }
//
////    public function update(Request $request, $id)
////    {
////        $dossier = Dossier::findOrFail($id);
////
////        $validated = $request->validate([
////            'type_dossier'      => 'required|in:national,international',
////            'engagement_date' => 'required|date',
////            'fournisseur' => 'required|string|max:255',
////            'ref_facture' => 'nullable|string|max:255',
////            'date_facture' => 'nullable|date',
////            'montant_ht' => 'nullable|numeric',
////            'taux_tva' => 'nullable|numeric',
////            'montant_ttc' => 'nullable|numeric',
////            'observation' => 'nullable|string',
////        ]);
////
////        // recalcul TVA if needed
////        if (!empty($validated['montant_ht']) && !empty($validated['taux_tva'])) {
////            $validated['montant_tva'] = $validated['montant_ht'] * ($validated['taux_tva'] / 100);
////        } else {
////            $validated['montant_tva'] = null;
////        }
////
////        $dossier->update($validated);
////
////        // If new files are uploaded → delete old ones first
////        if ($request->hasFile('attachments')) {
////            foreach ($dossier->attachments as $oldAttachment) {
////                if (\Storage::disk('private')->exists($oldAttachment->file_path)) {
////                    \Storage::disk('private')->delete($oldAttachment->file_path);
////                }
////                $oldAttachment->delete();
////            }
////
////            // Save new files
////            foreach ($request->file('attachments') as $file) {
////                $path = $file->store("dossiers/{$dossier->id}", 'private');
////                $dossier->attachments()->create([
////                    'file_path' => $path,
////                ]);
////            }
////        }
////
////
////        return redirect()->route('admin.dossiers.index')
////            ->with('success', "Dossier #{$dossier->id} mis à jour avec succès.");
////    }
//
//    public function update(Request $request, $id)
//    {
//        $dossier = Dossier::findOrFail($id);
//
//        // normalize numbers like "12,50" -> 12.50
//        $asNum = function ($v) {
//            if ($v === null || $v === '') return null;
//            $v = str_replace([' ', ','], ['', '.'], $v);
//            return is_numeric($v) ? (float)$v : null;
//        };
//
//        $validated = $request->validate([
//            'type_dossier'       => 'required|in:national,international',
//            'engagement_date'    => 'required|date',
//            'demande_number'     => 'nullable|string|max:255',
//            'fournisseur_id'     => 'required|exists:fournisseurs,id',
//            'direction_id'       => 'nullable|exists:directions,id',
//            'condition_paiement' => 'nullable|string|max:255',
//
//            'montant_ht'         => 'nullable',
//            'taux_tva'           => 'nullable',           // may be "custom" or numeric
//            'custom_tva'         => 'nullable',
//            'montant_ttc'        => 'nullable',           // will be recomputed anyway
//
//            // international fields
//            'taux_conversion'    => 'nullable',
//            'monnaie'            => 'nullable|in:USD,EUR',
//            'montant_devise'     => 'nullable',
//
//            'periode'            => 'nullable|string|max:255',
//
//            // keep your existing pieces…
//            'observation'        => 'nullable|string',
//        ]);
//
//        // ---- Numbers (server-side sanitation) ----
//        $validated['montant_ht']      = $asNum($validated['montant_ht'] ?? null);
//        $validated['taux_conversion'] = $asNum($validated['taux_conversion'] ?? null);
//        $validated['montant_devise']  = $asNum($validated['montant_devise'] ?? null);
//
//        // ---- TVA (single source of truth) ----
//        // take custom value when "custom" selected
//        if (($validated['taux_tva'] ?? null) === 'custom') {
//            $validated['taux_tva'] = $asNum($validated['custom_tva'] ?? null);
//        } else {
//            $validated['taux_tva'] = $asNum($validated['taux_tva'] ?? null);
//        }
//        unset($validated['custom_tva']);
//
//        // Recompute TTC on server (authoritative)
//        $ht  = $validated['montant_ht'] ?? 0.0;
//        $tva = $validated['taux_tva']   ?? 0.0;
//        $validated['montant_ttc'] = ($ht && $tva !== null)
//            ? round($ht * (1 + ($tva / 100)), 2)
//            : null;
//
//        // ---- International: montant_devise from TTC / taux (but allow manual override) ----
//        if (($validated['type_dossier'] ?? 'national') === 'international') {
//            $taux = $validated['taux_conversion'] ?? 0.0;
//
//            // If sync flag present OR no user-entered value, derive from TTC
//            $syncRequested = $request->boolean('sync_devise'); // set this on ↻ button as hidden input
//            $userGaveDev   = ($request->filled('montant_devise'));
//
//            if (($syncRequested || !$userGaveDev) && $validated['montant_ttc'] !== null && $taux > 0) {
//                $validated['montant_devise'] = round($validated['montant_ttc'] / $taux, 2);
//            }
//            // else: keep user's edited montant_devise (already normalized above)
//        } else {
//            // if not international, clear intl fields
//            $validated['taux_conversion'] = null;
//            $validated['monnaie']         = null;
//            $validated['montant_devise']  = null;
//        }
//
//        // ---- Update the dossier ----
//        $dossier->update($validated);
//
//        // ---- Attachments (kept same behavior as your code) ----
//        if ($request->hasFile('attachments')) {
//            foreach ($dossier->attachments as $oldAttachment) {
//                if (\Storage::disk('private')->exists($oldAttachment->file_path)) {
//                    \Storage::disk('private')->delete($oldAttachment->file_path);
//                }
//                $oldAttachment->delete();
//            }
//            foreach ($request->file('attachments') as $file) {
//                $path = $file->store("dossiers/{$dossier->id}", 'private');
//                $dossier->attachments()->create(['file_path' => $path]);
//            }
//        }
//
//        return redirect()
//            ->route('admin.dossiers.index')
//            ->with('success', "Dossier #{$dossier->id} mis à jour avec succès.");
//    }
//
//
//
//    public function approve(Dossier $dossier)
//    {
//        $user = auth()->guard('admin')->user();
//
//        // workflow mapping with your real enum values
//        $steps = [
//            0 => 'payment_service',
//            1 => 'payment_department',
//            2 => 'other_structure',
//        ];
//
//        // security check
//        if (!isset($steps[$dossier->approval_step]) || $user->role !== $steps[$dossier->approval_step]) {
//            return back()->withErrors('Vous ne pouvez pas approuver ce dossier à cette étape.');
//        }
//
//        // advance step
//        $dossier->approval_step++;
//
//        // last step reached?
//        if ($dossier->approval_step >= count($steps)) {
//            $dossier->status = 'APPROVED';
//        }
//
//        $dossier->save();
//
//        return back()->with('success', 'Dossier approuvé avec succès.');
//    }
//
//    public function reject(Request $request, Dossier $dossier)
//    {
//        $request->validate([
//            'reason'     => 'required|string|max:255',
//            'date_envoi' => 'required|date',
//        ]);
//
//        if ($dossier->status !== 'PENDING') {
//            return back()->withErrors('Ce dossier est déjà finalisé.');
//        }
//
//        $user = auth()->guard('admin')->user();
//
//        \App\Models\DossierRejection::create([
//            'dossier_id' => $dossier->id,
//            'admin_id'   => $user->id,
//            'role'       => $user->role,
//            'event'      => 'REJECT',
//            'reason'     => $request->reason,
//            'step'       => $dossier->approval_step,
//            'date_envoi' => $request->date_envoi,
//        ]);
//
//        $dossier->update([
//            'status'           => 'REJECTED',
//            'rejected_by'      => $user->id,
//            'rejection_reason' => $request->reason,
//        ]);
//
//        return back()->with('error', 'Dossier rejeté et journal enregistré.');
//    }
//
//    public function resubmit(Dossier $dossier, Request $request)
//    {
//        $data = $request->validate([
//            'date_retour'   => 'required|date',
//            'resubmit_note' => 'nullable|string|max:500',
//        ]);
//
//        if ($dossier->status !== 'REJECTED') {
//            return back()->withErrors('Seuls les dossiers rejetés peuvent être ré-soumis.');
//        }
//
//        $user = auth()->guard('admin')->user();
//        $lastRejection = $dossier->rejections()->first(); // latest() in relation
//
//        \App\Models\DossierRejection::create([
//            'dossier_id'    => $dossier->id,
//            'admin_id'      => $user->id,
//            'role'          => $user->role,
//            'event'         => 'RESUBMIT',
//            'reason'        => optional($lastRejection)->reason,  // keep context (optional)
//            'step'          => optional($lastRejection)->step ?? $dossier->approval_step,
//            'date_envoi'    => optional($lastRejection)->date_envoi, // keep linkage (optional)
//            'date_retour'   => $data['date_retour'],
//            'resubmitted_by'=> $user->id,
//            'resubmit_note' => $data['resubmit_note'] ?? null,
//        ]);
//
//        // Reset workflow
//        $dossier->update([
//            'status'           => 'PENDING',
//            'approval_step'    => 0,
//            'rejected_by'      => null,
//            'rejection_reason' => null,
//        ]);
//
//        return back()->with('success', 'Dossier ré-soumis pour approbation.');
//    }
//
//    public function details($id)
//    {
//        $dossier = Dossier::findOrFail($id);
//        $pageTitle = "Détails du Dossier #{$dossier->id}";
//
//        return view('admin.dossier.details', compact('pageTitle', 'dossier'));
//    }
//
//    public function destroy($id)
//    {
//        $dossier = Dossier::findOrFail($id);
//
//        // Optional: Prevent deleting approved dossiers
//        if ($dossier->status === 'APPROVED') {
//            return back()->withErrors('Impossible de supprimer un dossier déjà approuvé.');
//        }
//
//        $dossier->delete();
//
//        return back()->with('success', "Dossier #{$id} supprimé avec succès.");
//    }
//
//
//    public function deleteAttachment($id)
//    {
//        dd($id);
//        $attachment = DossierAttachment::findOrFail($id);
//
//        if (\Storage::disk('private')->exists($attachment->file_path)) {
//            \Storage::disk('private')->delete($attachment->file_path);
//        }
//
//        $attachment->delete();
//
//        return back()->with('success', 'Fichier supprimé.');
//    }
//
//    public function downloadAttachment($id)
//    {
//        $attachment = DossierAttachment::findOrFail($id);
//
//        if (!\Storage::disk('private')->exists($attachment->file_path)) {
//            return back()->withErrors('Fichier introuvable.');
//        }
//
//        return \Storage::disk('private')->download($attachment->file_path);
//    }
//
//
//}


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Condition;
use App\Models\Direction;
use App\Models\Dossier;
use App\Models\DossierAttachment;
use App\Models\DossierRejection;
use App\Models\Fournisseur;
use App\Support\Filters\DossierFilters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DossierController extends Controller
{
    /** Roles map used in approvals */
    private const APPROVAL_STEPS = [
        0 => 'payment_service',
        1 => 'payment_department',
        2 => 'other_structure',
    ];

    public function index(Request $request)
    {
//        dd($request->all());
        $pageTitle = 'Tous Dossiers';

        // Base query with relations you show in the table
        $q = Dossier::with(['fournisseur','direction'])
            ->withCount('factures');

        // Apply all column filters + global search + sorting
        $q = DossierFilters::eloquent($q, $request);

        // Paginate and keep query string (so filters persist)
        $dossiers = $q->paginate(15)->appends($request->query());

        // Data for filter dropdowns
        $fournisseurs = Fournisseur::orderBy('name')->select('id','name')->get();
        $directions   = Direction::orderBy('name')->select('id','name')->get();
        $conditions   = Condition::orderBy('name')->select('id','name')->get();

        return view('admin.dossier.index', compact(
            'pageTitle','dossiers','fournisseurs','directions','conditions'
        ));
    }

    public function create()
    {
        $pageTitle = 'Créer un Dossier';
        $fournisseurs = Fournisseur::orderBy('name')->get();
        $directions = Direction::orderBy('id')->get();
        $conditions = Condition::all();

        return view('admin.dossier.create', compact('pageTitle', 'fournisseurs', 'directions', 'conditions'));
    }

//    public function store(Request $request)
//    {
//        $validated = $request->validate([
//            'type_dossier' => 'required|in:national,international',
//            'engagement_date' => 'required|date',
//            'demande_number' => 'nullable|string|max:255',
//            'fournisseur_id' => 'required|exists:fournisseurs,id',
//            'direction_id' => 'nullable|exists:directions,id',
//            'condition_paiement' => 'nullable|string|max:255',
//
//            'montant_ht' => 'nullable|numeric',
//            'taux_tva' => 'nullable',            // can be "custom" or numeric
//            'custom_tva' => 'nullable|numeric',
//            'montant_ttc' => 'nullable|numeric',
//
//            'montant_devise' => 'nullable|numeric',
//            'monnaie' => 'nullable|in:USD,EUR',
//            'taux_conversion' => 'nullable|numeric',
//            'montant_ttc_local' => 'nullable|numeric',
//
//            'periode' => 'nullable|string|max:255',
//            'dossier_rejete' => 'nullable|boolean',
//            'date_envoi' => 'nullable|date',
//            'rejection_reason' => 'nullable|string|max:255',
//            'observation' => 'nullable|string',
//        ]);
//
//        // --- TVA (server authoritative) ---
//        if (($validated['taux_tva'] ?? null) === 'custom') {
//            $validated['taux_tva'] = $validated['custom_tva'] ?? null;
//        }
//        unset($validated['custom_tva']);
//
//        if (!empty($validated['montant_ht']) && $validated['taux_tva'] !== null && $validated['taux_tva'] !== '') {
//            $validated['montant_ttc'] = round($validated['montant_ht'] * (1 + ($validated['taux_tva'] / 100)), 2);
//        }
//
//        if (
//            $validated['type_dossier'] === 'international'
//            && !empty($validated['montant_ttc'])
//            && !empty($validated['taux_conversion'])
//        ) {
//            $validated['montant_ttc_local'] = round($validated['montant_ttc'] * $validated['taux_conversion'], 2);
//        }
//
//        // --- Workflow init ---
//        $validated['approval_step'] = 0;
//
//        if (!empty($validated['dossier_rejete'])) {
//            $validated['status'] = 'REJECTED';
//        } else {
//            $validated['status'] = 'PENDING';
//            $validated['rejection_reason'] = null;
//        }
//
//        $dossier = Dossier::create($validated);
//
//        // If created as rejected, append a REJECT log row (append-only)
//        if ($dossier->status === 'REJECTED') {
//            $admin = auth()->guard('admin')->user();
//            DossierRejection::create([
//                'dossier_id' => $dossier->id,
//                'admin_id' => $admin->id,
//                'role' => $admin->role,
//                'event' => 'REJECT',
//                'reason' => $request->rejection_reason,         // may be null if not provided
//                'step' => $dossier->approval_step,
//                'date_envoi' => $request->date_envoi,               // optional at creation
//            ]);
//        }
//
//        // Attachments
//        if ($request->hasFile('attachments')) {
//            foreach ($request->file('attachments') as $file) {
//                $path = $file->store("dossiers/{$dossier->id}", 'private');
//                $dossier->attachments()->create(['file_path' => $path]);
//            }
//        }
//
//        return redirect()->route('admin.dossiers.index')
//            ->with('success', "Dossier #{$dossier->id} créé avec succès.");
//    }

    public function store(Request $request)
    {
//        dd($request->all());
        $validated = $request->validate([
            'type_dossier'       => 'required|in:national,international',
            'engagement_date'    => 'required|date',
            'demande_number'     => 'nullable|string|max:255',
            'fournisseur_id'     => 'required|exists:fournisseurs,id',
            'direction_id'       => 'nullable|exists:directions,id',
            'condition_paiement' => 'nullable|string|max:255',

            // NATIONAL inputs
            'montant_ht'         => 'nullable|numeric',
            'taux_tva'           => 'nullable',            // may be "custom" or numeric
            'custom_tva'         => 'nullable|numeric',
            'remise_percent'     => 'nullable|numeric',    // NEW (base = HT)
            'taxe_percent'       => 'nullable|numeric',    // NEW (base = HT)
            'timbre_percent'     => 'nullable|numeric',    // NEW (base = HT)

            // INTERNATIONAL inputs
            'montant_devise'     => 'nullable|numeric',
            'monnaie'            => 'nullable|in:USD,EUR',
            'taux_conversion'    => 'nullable|numeric',
            'ibs_percent'        => 'nullable|numeric',    // NEW (base = devise)

            'periode'            => 'nullable|string|max:255',
            'dossier_rejete'     => 'nullable|boolean',
            'date_envoi'         => 'nullable|date',
            'rejection_reason'   => 'nullable|string|max:255',
            'observation'        => 'nullable|string',
        ]);

        // Normalize TVA
        if (($validated['taux_tva'] ?? null) === 'custom') {
            $validated['taux_tva'] = $validated['custom_tva'] ?? null;
        }
        unset($validated['custom_tva']);

        // Initialize computed fields
        $validated['montant_ttc']       = null;
        $validated['montant_ttc_local'] = null;

        if ($validated['type_dossier'] === 'national') {
            $ht     = (float)($validated['montant_ht'] ?? 0);
            $tva    = (float)($validated['taux_tva'] ?? 0);
            $remise = (float)($validated['remise_percent'] ?? 0);
            $taxe   = (float)($validated['taxe_percent'] ?? 0);
            $timbre = (float)($validated['timbre_percent'] ?? 0);

            $remiseAmt = $ht * ($remise / 100);
            $tvaAmt    = $ht * ($tva    / 100);
            $taxeAmt   = $ht * ($taxe   / 100);
            $timbAmt   = $ht * ($timbre / 100);

            $validated['montant_ttc'] = round(($ht - $remiseAmt) + $tvaAmt + $taxeAmt + $timbAmt, 2);

            // clear intl fields BUT use safe defaults for NOT NULL columns
            $validated['montant_devise']     = null;
            $validated['taux_conversion']    = null;
            $validated['monnaie']            = null;

            // <- THIS WAS THE CRASH:
            $validated['ibs_percent']        = 0;     // use 0, not null
            $validated['ibs_devise']         = null;  // if nullable
            $validated['montant_devise_net'] = null;  // if nullable
            $validated['montant_ttc_local']  = null;  // if nullable
            $validated['montant_ht_local']   = null;  // if nullable
        } else { // international
            $dev   = (float)($validated['montant_devise'] ?? 0);
            $taux  = (float)($validated['taux_conversion'] ?? 0);
            $ibs   = (float)($validated['ibs_percent'] ?? 0);

            $ibsDevise = $dev * ($ibs / 100);
            $devNet    = max(0, $dev - $ibsDevise);

            // DZD derived
            $ttcDzd = $dev * $taux;      // TTC derived
            $htDzd  = $devNet * $taux;   // HT derived after IBS

            // store in the standard columns so lists & exports work
            $validated['montant_ttc'] = $taux > 0 ? round($ttcDzd, 2) : null;
            $validated['montant_ht']  = $taux > 0 ? round($htDzd , 2) : null;

            // keep national-only percents out
            $validated['taux_tva']       = 0;   // if NOT NULL in DB
            $validated['remise_percent'] = 0;   // if NOT NULL
            $validated['taxe_percent']   = 0;   // if NOT NULL
            $validated['timbre_percent'] = 0;   // if NOT NULL

            // optional: if you keep a local mirror column
            $validated['montant_ttc_local'] = $validated['montant_ttc'];
        }

        // Workflow
        $validated['approval_step'] = 0;
        if (!empty($validated['dossier_rejete'])) {
            $validated['status'] = 'REJECTED';
        } else {
            $validated['status'] = 'PENDING';
            $validated['rejection_reason'] = null;
        }

        $dossier = Dossier::create($validated);

        // Rejection log if created rejected
        if ($dossier->status === 'REJECTED') {
            $admin = auth()->guard('admin')->user();
            DossierRejection::create([
                'dossier_id' => $dossier->id,
                'admin_id'   => $admin->id,
                'role'       => $admin->role,
                'event'      => 'REJECT',
                'reason'     => $request->rejection_reason,
                'step'       => $dossier->approval_step,
                'date_envoi' => $request->date_envoi,
            ]);
        }

        // Attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store("dossiers/{$dossier->id}", 'private');
                $dossier->attachments()->create(['file_path' => $path]);
            }
        }

        return redirect()->route('admin.dossiers.index')
            ->with('success', "Dossier #{$dossier->id} créé avec succès.");
    }


    public function edit($id)
    {
        $dossier = Dossier::findOrFail($id);
        $pageTitle = "Modifier le Dossier #{$dossier->id}";
        $fournisseurs = Fournisseur::orderBy('name')->get();
        $directions = Direction::orderBy('id')->get();
        $conditions = Condition::all();

        return view('admin.dossier.edit', compact('pageTitle', 'dossier', 'fournisseurs', 'directions', 'conditions'));
    }

//    public function update(Request $request, $id)
//    {
//        $dossier = Dossier::findOrFail($id);
//
//        // 1) validate normal fields (same as you have)
//        $asNum = function ($v) {
//            if ($v === null || $v === '') return null;
//            $v = str_replace([' ', ','], ['', '.'], $v);
//            return is_numeric($v) ? (float)$v : null;
//        };
//
//        // Base rules
//        $rules = [
//            'type_dossier'       => 'required|in:national,international',
//            'engagement_date'    => 'required|date',
//            'demande_number'     => 'nullable|string|max:255',
//            'fournisseur_id'     => 'required|exists:fournisseurs,id',
//            'direction_id'       => 'nullable|exists:directions,id',
//            'condition_paiement' => 'nullable|string|max:255',
//            'montant_ht'         => 'nullable',
//            'taux_tva'           => 'nullable',
//            'custom_tva'         => 'nullable',
//            'montant_ttc'        => 'nullable',
//            'taux_conversion'    => 'nullable',
//            'monnaie'            => 'nullable|in:USD,EUR',
//            'montant_devise'     => 'nullable',
//            'periode'            => 'nullable|string|max:255',
//            'observation'        => 'nullable|string',
//
//            // Rejection toggle from edit:
//            'dossier_rejete'     => 'required|in:0,1',
//        ];
//
//        // If “reject” checkbox is ON, require reason + date_envoi
//        if ((int)$request->input('dossier_rejete') === 1) {
//            $rules['rejection_reason'] = 'required|string|max:255';
//            $rules['date_envoi']       = 'required|date';
//        } else {
//            $rules['rejection_reason'] = 'nullable|string|max:255';
//            $rules['date_envoi']       = 'nullable|date';
//        }
//
//        $validated = $request->validate($rules);
//
//        // 2) numeric normalization + TTC/devise (unchanged from your version)
//        $validated['montant_ht']      = $asNum($validated['montant_ht'] ?? null);
//        $validated['taux_conversion'] = $asNum($validated['taux_conversion'] ?? null);
//        $validated['montant_devise']  = $asNum($validated['montant_devise'] ?? null);
//
//        if (($validated['taux_tva'] ?? null) === 'custom') {
//            $validated['taux_tva'] = $asNum($validated['custom_tva'] ?? null);
//        } else {
//            $validated['taux_tva'] = $asNum($validated['taux_tva'] ?? null);
//        }
//        unset($validated['custom_tva']);
//
//        $ht  = $validated['montant_ht'] ?? 0.0;
//        $tva = $validated['taux_tva']   ?? 0.0;
//        $validated['montant_ttc'] = ($ht && $tva !== null)
//            ? round($ht * (1 + ($tva / 100)), 2)
//            : null;
//
//        if (($validated['type_dossier'] ?? 'national') === 'international') {
//            $taux = $validated['taux_conversion'] ?? 0.0;
//            $syncRequested = $request->boolean('sync_devise');
//            $userGaveDev   = $request->filled('montant_devise');
//
//            if (($syncRequested || !$userGaveDev) && $validated['montant_ttc'] !== null && $taux > 0) {
//                $validated['montant_devise'] = round($validated['montant_ttc'] / $taux, 2);
//            }
//        } else {
//            $validated['taux_conversion'] = null;
//            $validated['monnaie']         = null;
//            $validated['montant_devise']  = null;
//        }
//
//        // 3) Save the dossier fields (not status yet)
//        DB::transaction(function () use ($request, $dossier, $validated) {
//            $wasRejected = ($dossier->status === 'REJECTED');
//            $wantsReject = ((int)$request->input('dossier_rejete') === 1);
//
//            // Update main fields
//            $dossier->update($validated);
//
//            // Case A: user ticks "rejeté" now AND dossier wasn't rejected before -> REJECT now + log
//            if ($wantsReject && !$wasRejected) {
//                $admin = auth()->guard('admin')->user();
//
//                // Append log row (append-only)
//                DossierRejection::create([
//                    'dossier_id' => $dossier->id,
//                    'admin_id'   => $admin->id,
//                    'role'       => $admin->role,
//                    'event'      => 'REJECT',
//                    'reason'     => $request->input('rejection_reason'),
//                    'step'       => $dossier->approval_step,     // current step at edit time
//                    'date_envoi' => $request->input('date_envoi'),
//                ]);
//
//                // Flip dossier status to REJECTED
//                $dossier->update([
//                    'status'           => 'REJECTED',
//                    'rejected_by'      => $admin->id,
//                    'rejection_reason' => $request->input('rejection_reason'),
//                ]);
//            }
//        });
//
//        // 4) Attachments (unchanged)
//        if ($request->hasFile('attachments')) {
//            foreach ($dossier->attachments as $old) {
//                if (Storage::disk('private')->exists($old->file_path)) {
//                    Storage::disk('private')->delete($old->file_path);
//                }
//                $old->delete();
//            }
//            foreach ($request->file('attachments') as $file) {
//                $path = $file->store("dossiers/{$dossier->id}", 'private');
//                $dossier->attachments()->create(['file_path' => $path]);
//            }
//        }
//
//        return redirect()->route('admin.dossiers.index')
//            ->with('success', "Dossier #{$dossier->id} mis à jour avec succès.");
//    }

    public function update(Request $request, $id)
    {
        $dossier = Dossier::findOrFail($id);

        // helper: normalize numbers like "12,50" → 12.50
        $asNum = function ($v) {
            if ($v === null || $v === '') return null;
            $v = str_replace([' ', ','], ['', '.'], $v);
            return is_numeric($v) ? (float)$v : null;
        };

        // -------- 1) Validation --------
        $rules = [
            'type_dossier'       => 'required|in:national,international',
            'engagement_date'    => 'required|date',
            'demande_number'     => 'nullable|string|max:255',
            'fournisseur_id'     => 'required|exists:fournisseurs,id',
            'direction_id'       => 'nullable|exists:directions,id',
            'condition_paiement' => 'nullable|string|max:255',

            // NATIONAL inputs
            'montant_ht'         => 'nullable',
            'taux_tva'           => 'nullable',          // may be "custom" or numeric
            'custom_tva'         => 'nullable',
            'remise_percent'     => 'nullable',
            'taxe_percent'       => 'nullable',
            'timbre_percent'     => 'nullable',

            // INTERNATIONAL inputs
            'taux_conversion'    => 'nullable',
            'monnaie'            => 'nullable|in:USD,EUR',
            'montant_devise'     => 'nullable',
            'ibs_percent'        => 'nullable',

            'periode'            => 'nullable|string|max:255',
            'observation'        => 'nullable|string',

            // Reject toggle
            'dossier_rejete'     => 'required|in:0,1',
        ];

        // If “rejeté” checked, require reason + date_envoi
        if ((int)$request->input('dossier_rejete') === 1) {
            $rules['rejection_reason'] = 'required|string|max:255';
            $rules['date_envoi']       = 'required|date';
        } else {
            $rules['rejection_reason'] = 'nullable|string|max:255';
            $rules['date_envoi']       = 'nullable|date';
        }

        $validated = $request->validate($rules);

        // -------- 2) Sanitize numerics --------
        // National
        $validated['montant_ht']      = $asNum($validated['montant_ht'] ?? null);
        $validated['remise_percent']  = $asNum($validated['remise_percent'] ?? null);
        $validated['taxe_percent']    = $asNum($validated['taxe_percent'] ?? null);
        $validated['timbre_percent']  = $asNum($validated['timbre_percent'] ?? null);

        // TVA normalize (custom → numeric)
        if (($validated['taux_tva'] ?? null) === 'custom') {
            $validated['taux_tva'] = $asNum($validated['custom_tva'] ?? null);
        } else {
            $validated['taux_tva'] = $asNum($validated['taux_tva'] ?? null);
        }
        unset($validated['custom_tva']);

        // International
        $validated['taux_conversion'] = $asNum($validated['taux_conversion'] ?? null);
        $validated['montant_devise']  = $asNum($validated['montant_devise'] ?? null);
        $validated['ibs_percent']     = $asNum($validated['ibs_percent'] ?? null);

        // safety (no negatives)
        $validated['montant_ht']     = max(0, (float)($validated['montant_ht'] ?? 0));
        $validated['montant_devise'] = max(0, (float)($validated['montant_devise'] ?? 0));
        $validated['taux_conversion']= max(0, (float)($validated['taux_conversion'] ?? 0));

        // -------- 3) Compute amounts according to type --------
        $validated['montant_ttc']       = null;
        $validated['montant_ttc_local'] = null; // keep if you use it as mirror

        if (($validated['type_dossier'] ?? 'national') === 'national') {
            $ht     = (float)$validated['montant_ht'];
            $tva    = (float)($validated['taux_tva'] ?? 0);
            $remise = (float)($validated['remise_percent'] ?? 0);
            $taxe   = (float)($validated['taxe_percent'] ?? 0);
            $timbre = (float)($validated['timbre_percent'] ?? 0);

            // all components are based on original HT
            $remiseAmt = $ht * ($remise / 100);
            $tvaAmt    = $ht * ($tva    / 100);
            $taxeAmt   = $ht * ($taxe   / 100);
            $timbAmt   = $ht * ($timbre / 100);

            // TTC = (HT − remise) + TVA + Taxe + Timbre
            $validated['montant_ttc'] = round(($ht - $remiseAmt) + $tvaAmt + $taxeAmt + $timbAmt, 2);

            // clear international-only fields
            $validated['montant_devise']  = null;
            $validated['taux_conversion'] = null;
            $validated['monnaie']         = null;
            $validated['ibs_percent']     = null;
            $validated['montant_ttc_local'] = null;
        } else {
            // International: source of truth = devise
            $dev  = (float)$validated['montant_devise'];
            $taux = (float)$validated['taux_conversion'];
            $ibs  = (float)($validated['ibs_percent'] ?? 0);

            $ibsDevise = $dev * ($ibs / 100);       // IBS is on devise
            $devNet    = max(0, $dev - $ibsDevise); // after retention

            // DZD derived
            $ttcDzd = $dev    * $taux;  // TTC in DZD
            $htDzd  = $devNet * $taux;  // HT in DZD (after IBS)

            $validated['montant_ttc'] = $taux > 0 ? round($ttcDzd, 2) : null;
            $validated['montant_ht']  = $taux > 0 ? round($htDzd , 2) : null;

            // wipe national-only percentages
            $validated['taux_tva']       = null;
            $validated['remise_percent'] = null;
            $validated['taxe_percent']   = null;
            $validated['timbre_percent'] = null;

            // optional mirror
            $validated['montant_ttc_local'] = $validated['montant_ttc'];
        }

        // -------- 4) Persist + rejection logging (atomically) --------
        \DB::transaction(function () use ($request, $dossier, $validated) {
            $wasRejected = ($dossier->status === 'REJECTED');
            $wantsReject = ((int)$request->input('dossier_rejete') === 1);

            // Update core fields first
            $dossier->update($validated);

            // If switched to "rejeté" now and wasn't rejected before -> add log + flip status
            if ($wantsReject && !$wasRejected) {
                $admin = auth()->guard('admin')->user();

                DossierRejection::create([
                    'dossier_id' => $dossier->id,
                    'admin_id'   => $admin->id,
                    'role'       => $admin->role,
                    'event'      => 'REJECT',
                    'reason'     => $request->input('rejection_reason'),
                    'step'       => $dossier->approval_step,
                    'date_envoi' => $request->input('date_envoi'),
                ]);

                $dossier->update([
                    'status'           => 'REJECTED',
                    'rejected_by'      => $admin->id,
                    'rejection_reason' => $request->input('rejection_reason'),
                ]);
            }
        });

        // -------- 5) Attachments (same behavior) --------
        if ($request->hasFile('attachments')) {
            foreach ($dossier->attachments as $old) {
                if (Storage::disk('private')->exists($old->file_path)) {
                    Storage::disk('private')->delete($old->file_path);
                }
                $old->delete();
            }
            foreach ($request->file('attachments') as $file) {
                $path = $file->store("dossiers/{$dossier->id}", 'private');
                $dossier->attachments()->create(['file_path' => $path]);
            }
        }

        return redirect()->route('admin.dossiers.index')
            ->with('success', "Dossier #{$dossier->id} mis à jour avec succès.");
    }


    public function approve(Dossier $dossier)
    {
        $user = auth()->guard('admin')->user();

        // Step / role check
        $expectedRole = self::APPROVAL_STEPS[$dossier->approval_step] ?? null;
        if ($expectedRole === null || $user->role !== $expectedRole) {
            return back()->withErrors('Vous ne pouvez pas approuver ce dossier à cette étape.');
        }

        // Advance
        $dossier->approval_step++;

        // If final step reached
        if ($dossier->approval_step >= count(self::APPROVAL_STEPS)) {
            $dossier->status = 'APPROVED';
        }

        $dossier->save();

        return back()->with('success', 'Dossier approuvé avec succès.');
    }

    public function reject(Request $request, Dossier $dossier)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
            'date_envoi' => 'required|date',
        ]);

        if ($dossier->status !== 'PENDING') {
            return back()->withErrors('Ce dossier est déjà finalisé.');
        }

        $admin = auth()->guard('admin')->user();
//        dd($admin, $dossier);

        // Append REJECT log (append-only)
        DossierRejection::create([
            'dossier_id' => $dossier->id,
            'admin_id' => $admin->id,
            'role' => $admin->role,
            'event' => 'REJECT',
            'reason' => $request->reason,
            'step' => $dossier->approval_step,
            'date_envoi' => $request->date_envoi,
        ]);

        // Flip status on dossier
        $dossier->update([
            'status' => 'REJECTED',
//            'rejected_by' => $admin->id,
            'rejection_reason' => $request->reason,
        ]);

//        dd($admin, $dossier);

        return back()->with('error', 'Dossier rejeté et journal enregistré.');
    }

    public function resubmit(Dossier $dossier, Request $request)
    {
        $data = $request->validate([
            'date_retour' => 'required|date',
            'resubmit_note' => 'nullable|string|max:500',
        ]);

        if ($dossier->status !== 'REJECTED') {
            return back()->withErrors('Seuls les dossiers rejetés peuvent être ré-soumis.');
        }

        $admin = auth()->guard('admin')->user();

        // Get latest rejection row for context (DON'T update it)
        $last = $dossier->rejections()->latest()->first();

        // Append RESUBMIT log row
        DossierRejection::create([
            'dossier_id' => $dossier->id,
            'admin_id' => $admin->id,             // who is performing the action now
            'role' => $admin->role,
            'event' => 'RESUBMIT',
            'reason' => optional($last)->reason, // keep previous reason for context (optional)
            'step' => optional($last)->step ?? $dossier->approval_step,
            'date_envoi' => optional($last)->date_envoi, // keep linkage (optional)
            'date_retour' => $data['date_retour'],
            'resubmitted_by' => $admin->id,
            'resubmit_note' => $data['resubmit_note'] ?? null,
        ]);

        // Reset dossier workflow
        $dossier->update([
            'status' => 'PENDING',
            'approval_step' => 0,
            'rejected_by' => null,
            'rejection_reason' => null,
        ]);

        return back()->with('success', 'Dossier ré-soumis pour approbation.');
    }

    public function details($id)
    {
        $dossier = Dossier::with(['rejections.admin', 'rejections.resubmitter', 'fournisseur'])->findOrFail($id);
        $pageTitle = "Détails du Dossier #{$dossier->id}";

        return view('admin.dossier.details', compact('pageTitle', 'dossier'));
    }

    public function destroy($id)
    {
        $dossier = Dossier::findOrFail($id);

        // Optional policy: block deleting approved dossiers
        if ($dossier->status === 'APPROVED') {
            return back()->withErrors('Impossible de supprimer un dossier déjà approuvé.');
        }

        // Delete attachments files
        foreach ($dossier->attachments as $att) {
            if ($att->file_path && Storage::disk('private')->exists($att->file_path)) {
                Storage::disk('private')->delete($att->file_path);
            }
            $att->delete();
        }

        $dossier->delete();

        return back()->with('success', "Dossier #{$id} supprimé avec succès.");
    }

    public function deleteAttachment($id)
    {
        $attachment = DossierAttachment::findOrFail($id);

        if (Storage::disk('private')->exists($attachment->file_path)) {
            Storage::disk('private')->delete($attachment->file_path);
        }

        $attachment->delete();

        return back()->with('success', 'Fichier supprimé.');
    }

    public function downloadAttachment($id)
    {
        $attachment = DossierAttachment::findOrFail($id);

        if (!Storage::disk('private')->exists($attachment->file_path)) {
            return back()->withErrors('Fichier introuvable.');
        }

        return Storage::disk('private')->download($attachment->file_path);
    }
}
