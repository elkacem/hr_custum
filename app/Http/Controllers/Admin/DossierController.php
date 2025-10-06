<?php
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
//////        return view('admin.dossier.index', compact('dossier'));
////        return view('admin.dossier.index', compact('pageTitle', 'dossiers'));
////    }
////
////    public function create()
////    {
////        $pageTitle = 'All Vehicles';
////        $fournisseurs = Fournisseur::orderBy('name')->get();
////
////
////        return view('admin.dossier.create', compact('pageTitle', 'fournisseurs'));
////    }
////
////    public function store(Request $request)
////    {
////        $validated = $request->validate([
////            'engagement_date'    => 'required|date',
////            'fournisseur'        => 'required|exists:fournisseurs,id',
////            'ref_facture'        => 'nullable|string|max:255',
////            'date_facture'       => 'nullable|date',
////            'bon_commande'       => 'nullable|string|max:255',
////            'periode'            => 'nullable|string|max:20',
////
////            'montant_ht'         => 'nullable|numeric',
////            'taux_tva'           => 'nullable|numeric',
////            'montant_ttc'        => 'nullable|numeric',
////            'observation'        => 'nullable|string',
////
////            'ordonnancement_date'=> 'nullable|date',
////            'demande_number'     => 'nullable|string|max:255',
////            'dossier_rejete'     => 'nullable|boolean',
////            'be_number'          => 'nullable|string|max:255',
////            'date_envoi'         => 'nullable|date',
////            'date_retour'        => 'nullable|date',
////
////            'direction_emettrice'=> 'nullable|string|max:255',
////            'condition_paiement' => 'nullable|string|max:255',
////            'echeancier'         => 'nullable|string|max:255',
////            'observation_paiement'=> 'nullable|string',
////        ]);
////
////        // Calcul automatique TVA
////        if (!empty($validated['montant_ht']) && !empty($validated['taux_tva'])) {
////            $validated['montant_tva'] = $validated['montant_ht'] * ($validated['taux_tva'] / 100);
////        } else {
////            $validated['montant_tva'] = null;
////        }
////
////        Dossier::create($validated);
////
////        return redirect()->route('admin.dossiers.index')
////            ->with('success', 'Dossier créé avec succès.');
////    }
////
////
////    public function approve(Dossier $dossier)
////    {
////        $user = auth()->user();
////
////        // Vérifier le rôle de l'utilisateur
////        if ($user->hasRole('SERVICE_CHIEF') && $dossier->approval_step === 0) {
////            $dossier->approval_step = 1;
////        } elseif ($user->hasRole('DEPARTEMENT_CHIEF') && $dossier->approval_step === 1) {
////            $dossier->approval_step = 2;
////        } elseif ($user->hasRole('STRUCTURE_HEAD') && $dossier->approval_step === 2) {
////            $dossier->approval_step = 3;
////            $dossier->status = 'APPROVED';
////        } else {
////            return back()->withErrors('Vous ne pouvez pas approuver ce dossier.');
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
////        $dossier->status = 'REJECTED';
////        $dossier->rejected_by = auth()->id();
////        $dossier->rejection_reason = $request->reason;
////        $dossier->save();
////
////        return back()->with('error', 'Dossier rejeté.');
////    }
////
////
////    public function details($id)
////    {
////        $dossier = Dossier::findOrFail($id);
////        $pageTitle = "Détails du Dossier #{$dossier->id}";
////
////        return view('admin.dossier.details', compact('pageTitle', 'dossier'));
////    }
////
////
////}
//
//
//namespace App\Http\Controllers\Admin;
//
//use App\Http\Controllers\Controller;
//use App\Models\Dossier;
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
//
//        return view('admin.dossier.create', compact('pageTitle', 'fournisseurs'));
//    }
//
//    public function store(Request $request)
//    {
//        $validated = $request->validate([
//            'engagement_date' => 'required|date',
//            'fournisseur' => 'required|string|max:255',
//            'ref_facture' => 'nullable|string|max:255',
//            'date_facture' => 'nullable|date',
//            'montant_ht' => 'nullable|numeric',
//            'taux_tva' => 'nullable|numeric',
//            'montant_ttc' => 'nullable|numeric',
//            'observation' => 'nullable|string',
//        ]);
//
//        // Calcul automatique TVA
//        if (!empty($validated['montant_ht']) && !empty($validated['taux_tva'])) {
//            $validated['montant_tva'] = $validated['montant_ht'] * ($validated['taux_tva'] / 100);
//        } else {
//            $validated['montant_tva'] = null;
//        }
//
//        // init workflow
//        $validated['approval_step'] = 0;
//        $validated['status'] = 'PENDING';
//
//        Dossier::create($validated);
//
//        return redirect()->route('dossiers.index')
//            ->with('success', 'Dossier créé avec succès.');
//    }
//
//    public function approve(Dossier $dossier)
//    {
//        $user = auth()->user();
//
//        // workflow mapping
//        $steps = [
//            0 => 'SERVICE_CHIEF',
//            1 => 'DEPARTEMENT_CHIEF',
//            2 => 'STRUCTURE_HEAD',
//        ];
//
//        // security check
//        if (!isset($steps[$dossier->approval_step]) || !$user->hasRole($steps[$dossier->approval_step])) {
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
//        $request->validate(['reason' => 'required|string|max:255']);
//
//        if ($dossier->status !== 'PENDING') {
//            return back()->withErrors('Ce dossier est déjà finalisé.');
//        }
//
//        $dossier->status = 'REJECTED';
//        $dossier->rejected_by = auth()->id();
//        $dossier->rejection_reason = $request->reason;
//        $dossier->save();
//
//        return back()->with('error', 'Dossier rejeté.');
//    }
//
//    public function details($id)
//    {
//        $dossier = Dossier::findOrFail($id);
//        $pageTitle = "Détails du Dossier #{$dossier->id}";
//
//        return view('admin.dossier.details', compact('pageTitle', 'dossier'));
//    }
//}


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Condition;
use App\Models\Direction;
use App\Models\Dossier;
use App\Models\DossierAttachment;
use App\Models\DossierRejection;
use App\Models\Fournisseur;
use Illuminate\Http\Request;

class DossierController extends Controller
{
    public function index(Request $request)
    {
        $query = Dossier::query();

        if ($request->filled('fournisseur')) {
            $query->where('fournisseur', 'like', "%{$request->fournisseur}%");
        }

        if ($request->filled('date')) {
            $query->whereDate('engagement_date', $request->date);
        }

        $dossiers = $query->latest()->paginate(10);
        $pageTitle = 'Tous Dossiers';

        return view('admin.dossier.index', compact('pageTitle', 'dossiers'));
    }

    public function create()
    {
        $pageTitle = 'Créer un Dossier';
        $fournisseurs = Fournisseur::orderBy('name')->get();
        $directions = Direction::orderBy('id')->get();
        $conditions = Condition::all();

        return view('admin.dossier.create', compact('pageTitle', 'fournisseurs', 'directions', 'conditions'));
    }

    public function store(Request $request)
    {
//        dd($request->rejection_reason);
        $validated = $request->validate([
            'type_dossier'       => 'required|in:national,international',
            'engagement_date'    => 'required|date',
            'demande_number'     => 'nullable|string|max:255',
            'fournisseur_id'        => 'required|exists:fournisseurs,id',
            'direction_id'       => 'nullable|exists:directions,id',
            'condition_paiement' => 'nullable|string|max:255',

            'montant_ht'         => 'nullable|numeric',
            'taux_tva'           => 'nullable',
            'custom_tva'         => 'nullable|numeric',
            'montant_ttc'        => 'nullable|numeric',

            'montant_devise'     => 'nullable|numeric',
            'monnaie'            => 'nullable|string|max:10',
            'taux_conversion'    => 'nullable|numeric',
            'montant_ttc_local'  => 'nullable|numeric',

            'periode'            => 'nullable|string|max:255',
            'dossier_rejete'     => 'nullable|boolean',
            'date_envoi'         => 'nullable|date',
        ]);

        // --- TVA ---
        if ($validated['taux_tva'] === 'custom') {
            $validated['taux_tva'] = $validated['custom_tva'] ?? null;
        }
        unset($validated['custom_tva']);

        if (!empty($validated['montant_ht']) && !empty($validated['taux_tva'])) {
            $validated['montant_ttc'] = $validated['montant_ht'] + ($validated['montant_ht'] * $validated['taux_tva'] / 100);
        }

        if ($validated['type_dossier'] === 'international' && !empty($validated['montant_ttc']) && !empty($validated['taux_conversion'])) {
            $validated['montant_ttc_local'] = $validated['montant_ttc'] * $validated['taux_conversion'];
        }

        // --- Workflow & Rejection ---
        if (!empty($validated['dossier_rejete']) && $validated['dossier_rejete']) {
            $validated['status'] = 'REJECTED';
            $validated['approval_step'] = 0;
        } else {
            $validated['status'] = 'PENDING';
            $validated['approval_step'] = 0;
            $validated['rejection_reason'] = null;
        }

        // Save dossier
        $dossier = Dossier::create($validated);

//        dd($dossier->status);

        // --- Log rejection if needed ---
        if ($dossier->status === 'REJECTED') {
            $user = auth()->guard('admin')->user();

            DossierRejection::create([
                'dossier_id' => $dossier->id,
                'admin_id'   => $user->id,
                'role'       => $user->role,
                'reason'     => $request->rejection_reason,
                'step'       => $dossier->approval_step,
            ]);
        }

        // --- Attachments ---
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store("dossiers/{$dossier->id}", 'private');
                $dossier->attachments()->create([
                    'file_path' => $path,
                ]);
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
        return view('admin.dossier.edit', compact('pageTitle', 'dossier', 'fournisseurs', 'directions'));
    }

//    public function update(Request $request, $id)
//    {
//        $dossier = Dossier::findOrFail($id);
//
//        $validated = $request->validate([
//            'type_dossier'      => 'required|in:national,international',
//            'engagement_date' => 'required|date',
//            'fournisseur' => 'required|string|max:255',
//            'ref_facture' => 'nullable|string|max:255',
//            'date_facture' => 'nullable|date',
//            'montant_ht' => 'nullable|numeric',
//            'taux_tva' => 'nullable|numeric',
//            'montant_ttc' => 'nullable|numeric',
//            'observation' => 'nullable|string',
//        ]);
//
//        // recalcul TVA if needed
//        if (!empty($validated['montant_ht']) && !empty($validated['taux_tva'])) {
//            $validated['montant_tva'] = $validated['montant_ht'] * ($validated['taux_tva'] / 100);
//        } else {
//            $validated['montant_tva'] = null;
//        }
//
//        $dossier->update($validated);
//
//        // If new files are uploaded → delete old ones first
//        if ($request->hasFile('attachments')) {
//            foreach ($dossier->attachments as $oldAttachment) {
//                if (\Storage::disk('private')->exists($oldAttachment->file_path)) {
//                    \Storage::disk('private')->delete($oldAttachment->file_path);
//                }
//                $oldAttachment->delete();
//            }
//
//            // Save new files
//            foreach ($request->file('attachments') as $file) {
//                $path = $file->store("dossiers/{$dossier->id}", 'private');
//                $dossier->attachments()->create([
//                    'file_path' => $path,
//                ]);
//            }
//        }
//
//
//        return redirect()->route('admin.dossiers.index')
//            ->with('success', "Dossier #{$dossier->id} mis à jour avec succès.");
//    }

    public function update(Request $request, $id)
    {
        $dossier = Dossier::findOrFail($id);

        $validated = $request->validate([
            'type_dossier'    => 'required|in:national,international',
            'engagement_date' => 'required|date',
            'demande_number'  => 'nullable|string|max:255',
            'fournisseur_id'  => 'required|exists:fournisseurs,id',
            'direction_id'    => 'nullable|exists:directions,id',
            'condition_paiement' => 'nullable|string|max:255',
            'montant_ht'      => 'nullable|numeric',
            'taux_tva'        => 'nullable|numeric',
            'montant_ttc'     => 'nullable|numeric',
            'periode'         => 'nullable|string|max:255',
            'observation'     => 'nullable|string',
        ]);

        // recalcul TVA
        if (!empty($validated['montant_ht']) && !empty($validated['taux_tva'])) {
            $validated['montant_tva'] = $validated['montant_ht'] * ($validated['taux_tva'] / 100);
        } else {
            $validated['montant_tva'] = null;
        }

        $dossier->update($validated);

        // Gérer les fichiers uploadés
        if ($request->hasFile('attachments')) {
            foreach ($dossier->attachments as $oldAttachment) {
                if (\Storage::disk('private')->exists($oldAttachment->file_path)) {
                    \Storage::disk('private')->delete($oldAttachment->file_path);
                }
                $oldAttachment->delete();
            }

            foreach ($request->file('attachments') as $file) {
                $path = $file->store("dossiers/{$dossier->id}", 'private');
                $dossier->attachments()->create([
                    'file_path' => $path,
                ]);
            }
        }

        return redirect()->route('admin.dossiers.index')
            ->with('success', "Dossier #{$dossier->id} mis à jour avec succès.");
    }



    public function approve(Dossier $dossier)
    {
        $user = auth()->guard('admin')->user();

        // workflow mapping with your real enum values
        $steps = [
            0 => 'payment_service',
            1 => 'payment_department',
            2 => 'other_structure',
        ];

        // security check
        if (!isset($steps[$dossier->approval_step]) || $user->role !== $steps[$dossier->approval_step]) {
            return back()->withErrors('Vous ne pouvez pas approuver ce dossier à cette étape.');
        }

        // advance step
        $dossier->approval_step++;

        // last step reached?
        if ($dossier->approval_step >= count($steps)) {
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

        $user = auth()->guard('admin')->user();

        if ($dossier->status !== 'PENDING') {
            return back()->withErrors('Ce dossier est déjà finalisé.');
        }

        // Sauvegarder le log de rejet
        DossierRejection::create([
            'dossier_id' => $dossier->id,
            'admin_id'   => $user->id,
            'role'       => $user->role,
            'reason'     => $request->reason,
            'step'       => $dossier->approval_step,
            'date_envoi' => $request->date_envoi,
        ]);

        // Mettre à jour le dossier
        $dossier->status = 'REJECTED';
        $dossier->rejected_by = $user->id;
        $dossier->rejection_reason = $request->reason;
        $dossier->save();

        return back()->with('error', 'Dossier rejeté et journal enregistré.');
    }

    public function resubmit(Dossier $dossier, Request $request)
    {
        $request->validate([
            'date_retour' => 'required|date',
        ]);

        if ($dossier->status !== 'REJECTED') {
            return back()->withErrors('Seuls les dossiers rejetés peuvent être ré-soumis.');
        }

        // Mettre à jour le dernier log de rejet
        $lastRejection = $dossier->rejections()->latest()->first();
        if ($lastRejection) {
            $lastRejection->update([
                'date_retour' => $request->date_retour,
            ]);
        }

        // Réinitialiser le workflow
        $dossier->status = 'PENDING';
        $dossier->approval_step = 0;
        $dossier->rejected_by = null;
        $dossier->rejection_reason = null;
        $dossier->save();

        return back()->with('success', 'Dossier ré-soumis pour approbation.');
    }


    public function details($id)
    {
        $dossier = Dossier::findOrFail($id);
        $pageTitle = "Détails du Dossier #{$dossier->id}";

        return view('admin.dossier.details', compact('pageTitle', 'dossier'));
    }

    public function destroy($id)
    {
        $dossier = Dossier::findOrFail($id);

        // Optional: Prevent deleting approved dossiers
        if ($dossier->status === 'APPROVED') {
            return back()->withErrors('Impossible de supprimer un dossier déjà approuvé.');
        }

        $dossier->delete();

        return back()->with('success', "Dossier #{$id} supprimé avec succès.");
    }


    public function deleteAttachment($id)
    {
        dd($id);
        $attachment = DossierAttachment::findOrFail($id);

        if (\Storage::disk('private')->exists($attachment->file_path)) {
            \Storage::disk('private')->delete($attachment->file_path);
        }

        $attachment->delete();

        return back()->with('success', 'Fichier supprimé.');
    }

    public function downloadAttachment($id)
    {
        $attachment = DossierAttachment::findOrFail($id);

        if (!\Storage::disk('private')->exists($attachment->file_path)) {
            return back()->withErrors('Fichier introuvable.');
        }

        return \Storage::disk('private')->download($attachment->file_path);
    }


}
