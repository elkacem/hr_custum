<?php
//////
//////namespace App\Http\Controllers\Admin;
//////
//////use App\Http\Controllers\Controller;
//////use App\Models\Compte;
//////use App\Models\Direction;
//////use App\Models\Dossier;
//////use App\Models\Facture;
//////use Illuminate\Http\Request;
//////use Illuminate\Support\Facades\Storage;
//////use Illuminate\Validation\Rule;
//////
//////class FactureController extends Controller
//////{
//////    public function index(Dossier $dossier)
//////    {
//////        $factures = $dossier->factures()->with(['direction', 'compte'])->latest()->paginate(10);
//////        return view('admin.factures.index', compact('factures', 'dossier'));
//////    }
//////
//////    public function create(Dossier $dossier)
//////    {
//////        $directions = Direction::orderBy('name')->get();
//////        $comptes = Compte::orderBy('name')->get();
//////        $pageTitle = 'Créer une Facture';
//////        return view('admin.dossier.facture.create', compact('dossier', 'pageTitle', 'directions', 'comptes'));
//////    }
//////
//////    public function store(Request $request, Dossier $dossier)
//////    {
//////        // Normalize TVA (handle "custom")
//////        $tauxTvaInput = $request->input('taux_tva');
//////        $tauxTva = $tauxTvaInput === 'custom'
//////            ? $request->input('custom_tva')
//////            : $tauxTvaInput;
//////
//////        $validated = $request->validate([
//////            // Type + core fields
//////            'type_dossier' => ['required', Rule::in(['national', 'international'])],
//////            'ref_facture' => 'required|string|max:255',
//////            'date_facture' => 'nullable|date',
//////            'bon_commande' => 'nullable|string|max:255',
//////            'numero_contrat' => 'nullable|string|max:255',
//////            'periode' => 'nullable|string|max:255',
//////            'objet' => 'nullable|string|max:255',
//////            'direction_id' => 'nullable|exists:directions,id',
//////            'compte_id' => 'nullable|exists:comptes,id',
//////            'observation' => 'nullable|string',
//////
//////            // Amounts
//////            'montant_ht' => 'nullable|numeric|min:0',
//////            'taux_tva' => ['nullable'], // validated after normalization
//////            'custom_tva' => 'nullable|numeric|min:0',
//////            'montant_ttc' => 'nullable|numeric|min:0',
//////
//////            // International (only if international)
//////            'taux_conversion' => 'nullable|numeric|min:0',
//////            'monnaie' => ['nullable', Rule::in(['USD', 'EUR'])],
//////            'montant_devise' => 'nullable|numeric|min:0',
//////
//////            // Files
//////            'attachments' => 'nullable|array|max:5',
//////            'attachments.*' => 'file|mimes:jpeg,jpg,png,pdf,doc,docx|max:' . (int)(1024 * 10), // ~10MB per file
//////        ]);
//////
//////        // Final TVA rate number
//////        $tvaRate = is_numeric($tauxTva) ? (float)$tauxTva : 0.0;
//////
//////        // Compute TTC (authoritative on server)
//////        $ht = (float)($validated['montant_ht'] ?? 0);
//////        $ttc = $ht > 0 ? round($ht * (1 + $tvaRate / 100), 2) : 0.0;
//////
//////        // International auto compute (if not manually provided)
//////        $isInternational = $validated['type_dossier'] === 'international';
//////        $taux = (float)($validated['taux_conversion'] ?? 0);
//////        $dev = $validated['montant_devise'] ?? null;
//////
//////        if ($isInternational) {
//////            if (($dev === null || $dev === '') && $taux > 0) {
//////                $validated['montant_devise'] = round($ttc / $taux, 2);
//////            }
//////        } else {
//////            // wipe international fields if national
//////            $validated['taux_conversion'] = null;
//////            $validated['monnaie'] = null;
//////            $validated['montant_devise'] = null;
//////        }
//////
//////        // Persist authoritative numbers
//////        $validated['taux_tva'] = $tvaRate;
//////        $validated['montant_ttc'] = $ttc;
//////
//////        // Linkage
//////        $validated['dossier_id'] = $dossier->id;
//////
//////        $facture = $dossier->factures()->create($validated);
//////
//////        return redirect()->route('admin.dossiers.details', $dossier->id)
//////            ->with('success', 'Facture ajoutée avec succès.');
//////    }
//////
//////    public function edit(Dossier $dossier, Facture $facture)
//////    {
//////        $directions = Direction::orderBy('name')->get();
//////        $comptes = Compte::orderBy('name')->get();
//////        $pageTitle = 'Modifier une Facture';
//////
//////        return view('admin.dossier.facture.edit', compact('facture', 'dossier', 'directions', 'comptes', 'pageTitle'));
//////    }
//////
//////    public function update(Request $request, Dossier $dossier, Facture $facture)
//////    {
//////        $tauxTvaInput = $request->input('taux_tva');
//////        $tauxTva = $tauxTvaInput === 'custom'
//////            ? $request->input('custom_tva')
//////            : $tauxTvaInput;
//////
//////        $validated = $request->validate([
//////            'type_dossier' => ['required', Rule::in(['national', 'international'])],
//////            'ref_facture' => 'required|string|max:255',
//////            'date_facture' => 'nullable|date',
//////            'bon_commande' => 'nullable|string|max:255',
//////            'periode' => 'nullable|string|max:255',
//////            'objet' => 'nullable|string|max:255',
//////            'direction_id' => 'nullable|exists:directions,id',
//////            'compte_id' => 'nullable|exists:comptes,id',
//////            'observation' => 'nullable|string',
//////
//////            'montant_ht' => 'nullable|numeric|min:0',
//////            'taux_tva' => ['nullable'],
//////            'custom_tva' => 'nullable|numeric|min:0',
//////
//////            'taux_conversion' => 'nullable|numeric|min:0',
//////            'monnaie' => ['nullable', Rule::in(['USD', 'EUR'])],
//////            'montant_devise' => 'nullable|numeric|min:0',
//////
//////            'attachments' => 'nullable|array|max:5',
//////            'attachments.*' => 'file|mimes:jpeg,jpg,png,pdf,doc,docx|max:' . (int)(1024 * 10),
//////        ]);
//////
//////        $tvaRate = is_numeric($tauxTva) ? (float)$tauxTva : 0.0;
//////
//////        $ht = (float)($validated['montant_ht'] ?? $facture->montant_ht ?? 0);
//////        $ttc = $ht > 0 ? round($ht * (1 + $tvaRate / 100), 2) : 0.0;
//////
//////        $isInternational = $validated['type_dossier'] === 'international';
//////        $taux = (float)($validated['taux_conversion'] ?? 0);
//////        $dev = $validated['montant_devise'] ?? null;
//////
//////        if ($isInternational) {
//////            if (($dev === null || $dev === '') && $taux > 0) {
//////                $validated['montant_devise'] = round($ttc / $taux, 2);
//////            }
//////        } else {
//////            $validated['taux_conversion'] = null;
//////            $validated['monnaie'] = null;
//////            $validated['montant_devise'] = null;
//////        }
//////
//////        $validated['taux_tva'] = $tvaRate;
//////        $validated['montant_ttc'] = $ttc;
//////
//////        $facture->update($validated);
//////
//////        return redirect()->route('dossiers.factures.index', $dossier->id)
//////            ->with('success', 'Facture mise à jour avec succès.');
//////    }
//////
//////    public function destroy(Dossier $dossier, Facture $facture)
//////    {
//////        // delete files from disk + db
//////        foreach ($facture->files as $file) {
//////            if ($file->path && Storage::disk('public')->exists($file->path)) {
//////                Storage::disk('public')->delete($file->path);
//////            }
//////            $file->delete();
//////        }
//////
//////        $facture->delete();
//////        return back()->with('success', 'Facture supprimée.');
//////    }
//////}
////
////
////namespace App\Http\Controllers\Admin;
////
////use App\Http\Controllers\Controller;
////use App\Models\Compte;
////use App\Models\Direction;
////use App\Models\Dossier;
////use App\Models\Facture;
////use Illuminate\Http\Request;
////use Illuminate\Support\Facades\Storage;
////use Illuminate\Validation\Rule;
////
////class FactureController extends Controller
////{
////    public function index(Dossier $dossier)
////    {
////        $factures = $dossier->factures()->with(['direction', 'compte'])->latest()->paginate(10);
////        return view('admin.factures.index', compact('factures', 'dossier'));
////    }
////
////    public function create(Dossier $dossier)
////    {
////        $directions = Direction::orderBy('name')->get();
////        $comptes = Compte::orderBy('name')->get();
////        $pageTitle = 'Créer une Facture';
////        return view('admin.dossier.facture.create', compact('dossier', 'pageTitle', 'directions', 'comptes'));
////    }
////
////    public function store(Request $request, Dossier $dossier)
////    {
//////        dd($request->all());
////        // Handle "custom" TVA selection
////        $tauxTvaInput = $request->input('taux_tva');
////        $tauxTva = $tauxTvaInput === 'custom'
////            ? $request->input('custom_tva')
////            : $tauxTvaInput;
////
////        $validated = $request->validate([
////            // Type + meta
////            'type_dossier' => ['required', Rule::in(['national', 'international'])],
////            'ref_facture' => 'required|string|max:255',
////            'date_facture' => 'nullable|date',
////            'bon_commande' => 'nullable|string|max:255',
////            'numero_contrat' => 'nullable|string|max:255',
////            'periode' => 'nullable|string|max:255',
////            'objet' => 'nullable|string|max:255',
////            'direction_id' => 'nullable|exists:directions,id',
////            'compte_id' => 'nullable|exists:comptes,id',
////            'observation' => 'nullable|string',
////
////            // NATIONAL inputs (all % on HT)
////            'montant_ht' => 'nullable|numeric|min:0',
////            'taux_tva' => ['nullable'],             // normalized below
////            'custom_tva' => 'nullable|numeric|min:0',
////            'remise_percent' => 'nullable|numeric|min:0',
////            'taxe_percent' => 'nullable|numeric|min:0',
////            'timbre_percent' => 'nullable|numeric|min:0',
////            'montant_ttc' => 'nullable|numeric|min:0', // will be recomputed
////
////            // INTERNATIONAL inputs (source = devise)
////            'taux_conversion' => 'nullable|numeric|min:0',
////            'monnaie' => ['nullable', Rule::in(['USD', 'EUR'])],
////            'montant_devise' => 'nullable|numeric|min:0',
////            'ibs_percent' => 'nullable|numeric|min:0',
////
////            // Files
////            'attachments' => 'nullable|array|max:5',
////            'attachments.*' => 'file|mimes:jpeg,jpg,png,pdf,doc,docx|max:' . (int)(1024 * 10),
////        ]);
////
////        // Final numeric TVA
////        $tvaRate = is_numeric($tauxTva) ? (float)$tauxTva : 0.0;
////
////        // Prepare authoritative fields
////        $validated['dossier_id'] = $dossier->id;
////
////        if ($validated['type_dossier'] === 'national') {
////            $ht = (float)($validated['montant_ht'] ?? 0);
////            $remise = (float)($validated['remise_percent'] ?? 0);
////            $taxe = (float)($validated['taxe_percent'] ?? 0);
////            $timbre = (float)($validated['timbre_percent'] ?? 0);
////
////            // Every component on HT
////            $remiseAmt = $ht * ($remise / 100);
////            $tvaAmt = $ht * ($tvaRate / 100);
////            $taxeAmt = $ht * ($taxe / 100);
////            $timbAmt = $ht * ($timbre / 100);
////
////            $ttc = ($ht - $remiseAmt) + $tvaAmt + $taxeAmt + $timbAmt;
////
////            $validated['taux_tva'] = $tvaRate;
////            $validated['montant_ttc'] = round($ttc, 2);
////
////            // Clean international-only fields
////            $validated['taux_conversion'] = null;
////            $validated['monnaie'] = null;
////            $validated['montant_devise'] = null;
////            $validated['ibs_percent'] = null;
////        } else {
////            // INTERNATIONAL: source = devise; IBS% on devise; DZD derived
////            $taux = (float)($validated['taux_conversion'] ?? 0);
////            $dev = (float)($validated['montant_devise'] ?? 0);
////            $ibsPct = (float)($validated['ibs_percent'] ?? 0);
////
////            $ibsDev = $dev * ($ibsPct / 100);
////            $devNet = max(0, $dev - $ibsDev);
////            $ttcDzd = $dev * $taux;      // TTC derived
////            $htDzd = $devNet * $taux;   // HT after IBS derived
////
////            $validated['montant_ttc'] = $taux > 0 ? round($ttcDzd, 2) : 0.0;
////            $validated['montant_ht'] = $taux > 0 ? round($htDzd, 2) : 0.0;
////
////            // National-only fields cleared
////            $validated['taux_tva'] = null;
////            $validated['remise_percent'] = null;
////            $validated['taxe_percent'] = null;
////            $validated['timbre_percent'] = null;
////        }
////
////        $facture = $dossier->factures()->create($validated);
////
////        // Optional: handle attachments relation if present on Facture model (e.g., $facture->files())
////        if ($request->hasFile('attachments') && method_exists($facture, 'files')) {
////            foreach ($request->file('attachments') as $file) {
////                $path = $file->store("factures/{$facture->id}", 'public');
////                $facture->files()->create(['path' => $path]);
////            }
////        }
////
////        return redirect()->route('admin.dossiers.details', $dossier->id)
////            ->with('success', 'Facture ajoutée avec succès.');
////    }
////
////    public function edit(Dossier $dossier, Facture $facture)
////    {
////        $directions = Direction::orderBy('name')->get();
////        $comptes = Compte::orderBy('name')->get();
////        $pageTitle = 'Modifier une Facture';
////        return view('admin.dossier.facture.edit', compact('facture', 'dossier', 'directions', 'comptes', 'pageTitle'));
////    }
////
////    public function update(Request $request, Dossier $dossier, Facture $facture)
////    {
////        // Handle "custom" TVA selection
////        $tauxTvaInput = $request->input('taux_tva');
////        $tauxTva = $tauxTvaInput === 'custom'
////            ? $request->input('custom_tva')
////            : $tauxTvaInput;
////
////        $validated = $request->validate([
////            'type_dossier' => ['required', Rule::in(['national', 'international'])],
////            'ref_facture' => 'required|string|max:255',
////            'date_facture' => 'nullable|date',
////            'bon_commande' => 'nullable|string|max:255',
////            'numero_contrat' => 'nullable|string|max:255',
////            'periode' => 'nullable|string|max:255',
////            'objet' => 'nullable|string|max:255',
////            'direction_id' => 'nullable|exists:directions,id',
////            'compte_id' => 'nullable|exists:comptes,id',
////            'observation' => 'nullable|string',
////
////            // NATIONAL inputs
////            'montant_ht' => 'nullable|numeric|min:0',
////            'taux_tva' => ['nullable'],
////            'custom_tva' => 'nullable|numeric|min:0',
////            'remise_percent' => 'nullable|numeric|min:0',
////            'taxe_percent' => 'nullable|numeric|min:0',
////            'timbre_percent' => 'nullable|numeric|min:0',
////            'montant_ttc' => 'nullable|numeric|min:0',
////
////            // INTERNATIONAL inputs
////            'taux_conversion' => 'nullable|numeric|min:0',
////            'monnaie' => ['nullable', Rule::in(['USD', 'EUR'])],
////            'montant_devise' => 'nullable|numeric|min:0',
////            'ibs_percent' => 'nullable|numeric|min:0',
////
////            // Files
////            'attachments' => 'nullable|array|max:5',
////            'attachments.*' => 'file|mimes:jpeg,jpg,png,pdf,doc,docx|max:' . (int)(1024 * 10),
////        ]);
////
////        $tvaRate = is_numeric($tauxTva) ? (float)$tauxTva : 0.0;
////
////        if ($validated['type_dossier'] === 'national') {
////            $ht = (float)($validated['montant_ht'] ?? $facture->montant_ht ?? 0);
////            $remise = (float)($validated['remise_percent'] ?? 0);
////            $taxe = (float)($validated['taxe_percent'] ?? 0);
////            $timbre = (float)($validated['timbre_percent'] ?? 0);
////
////            $remiseAmt = $ht * ($remise / 100);
////            $tvaAmt = $ht * ($tvaRate / 100);
////            $taxeAmt = $ht * ($taxe / 100);
////            $timbAmt = $ht * ($timbre / 100);
////
////            $ttc = ($ht - $remiseAmt) + $tvaAmt + $taxeAmt + $timbAmt;
////
////            $validated['taux_tva'] = $tvaRate;
////            $validated['montant_ttc'] = round($ttc, 2);
////
////            // Clean intl-only fields
////            $validated['taux_conversion'] = null;
////            $validated['monnaie'] = null;
////            $validated['montant_devise'] = null;
////            $validated['ibs_percent'] = null;
////        } else {
////            $taux = (float)($validated['taux_conversion'] ?? 0);
////            $dev = (float)($validated['montant_devise'] ?? 0);
////            $ibsPct = (float)($validated['ibs_percent'] ?? 0);
////
////            $ibsDev = $dev * ($ibsPct / 100);
////            $devNet = max(0, $dev - $ibsDev);
////            $ttcDzd = $dev * $taux;
////            $htDzd = $devNet * $taux;
////
////            $validated['montant_ttc'] = $taux > 0 ? round($ttcDzd, 2) : 0.0;
////            $validated['montant_ht'] = $taux > 0 ? round($htDzd, 2) : 0.0;
////
////            // Clean national-only
////            $validated['taux_tva'] = null;
////            $validated['remise_percent'] = null;
////            $validated['taxe_percent'] = null;
////            $validated['timbre_percent'] = null;
////        }
////
////        $facture->update($validated);
////
////        // Optional: replace attachments if provided
////        if ($request->hasFile('attachments') && method_exists($facture, 'files')) {
////            foreach ($facture->files as $old) {
////                if ($old->path && Storage::disk('public')->exists($old->path)) {
////                    Storage::disk('public')->delete($old->path);
////                }
////                $old->delete();
////            }
////            foreach ($request->file('attachments') as $file) {
////                $path = $file->store("factures/{$facture->id}", 'public');
////                $facture->files()->create(['path' => $path]);
////            }
////        }
////
////        return redirect()->route('dossiers.factures.index', $dossier->id)
////            ->with('success', 'Facture mise à jour avec succès.');
////    }
////
////    public function destroy(Dossier $dossier, Facture $facture)
////    {
////        // delete files from disk + db (if relation exists)
////        if (method_exists($facture, 'files')) {
////            foreach ($facture->files as $file) {
////                if ($file->path && Storage::disk('public')->exists($file->path)) {
////                    Storage::disk('public')->delete($file->path);
////                }
////                $file->delete();
////            }
////        }
////
////        $facture->delete();
////        return back()->with('success', 'Facture supprimée.');
////    }
////}
//
//
//namespace App\Http\Controllers\Admin;
//
//use App\Http\Controllers\Controller;
//use App\Models\Compte;
//use App\Models\Direction;
//use App\Models\Dossier;
//use App\Models\Facture;
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Storage;
//use Illuminate\Validation\Rule;
//
//class FactureController extends Controller
//{
//    public function index(Dossier $dossier)
//    {
//        $factures = $dossier->factures()->with(['direction', 'compte'])->latest()->paginate(10);
//        return view('admin.factures.index', compact('factures', 'dossier'));
//    }
//
//    public function create(Dossier $dossier)
//    {
//        $directions = Direction::orderBy('name')->get();
//        $comptes = Compte::orderBy('name')->get();
//        $pageTitle = 'Créer une Facture';
//
//        return view('admin.dossier.facture.create', compact('dossier', 'pageTitle', 'directions', 'comptes'));
//    }
//
//    public function store(Request $request, Dossier $dossier)
//    {
//        // Handle "custom" TVA selection
//        $tauxTvaInput = $request->input('taux_tva');
//        $finalTva = $tauxTvaInput === 'custom' ? $request->input('custom_tva') : $tauxTvaInput;
//
//        $data = $request->validate([
//            // Core/meta
//            'type_dossier' => ['required', Rule::in(['national', 'international'])],
//            'ref_facture' => 'nullable|string|max:255',
//            'date_facture' => 'nullable|date',
//            'bon_commande' => 'nullable|string|max:255',
//            'numero_contrat' => 'nullable|string|max:255',
//            'periode' => 'nullable|string|max:255',
//            'objet' => 'nullable|string|max:255',
//            'direction_id' => 'nullable|exists:directions,id',
//            'compte_id' => 'nullable|exists:comptes,id',
//            'observation' => 'nullable|string',
//
//            // National
//            'montant_ht' => 'nullable|numeric|min:0',
//            'taux_tva' => 'nullable',
//            'custom_tva' => 'nullable|numeric|min:0',
//            'remise_percent' => 'nullable|numeric|min:0',
//            'taxe_percent' => 'nullable|numeric|min:0',
//            'timbre_percent' => 'nullable|numeric|min:0',
//
//            // International
//            'monnaie' => ['nullable', Rule::in(['USD', 'EUR'])],
//            'taux_conversion' => 'nullable|numeric|min:0',
//            'montant_devise' => 'nullable|numeric|min:0',
//            'ibs_percent' => 'nullable|numeric|min:0',
//
//            // Files (optional if you have a relation)
//            'attachments' => 'nullable|array|max:5',
//            'attachments.*' => 'file|mimes:jpeg,jpg,png,pdf,doc,docx|max:' . (int)(1024 * 10),
//        ]);
//
//        $data['dossier_id'] = $dossier->id;
//
//        if ($data['type_dossier'] === 'national') {
//            // Normalize inputs
//            $ht = (float)($data['montant_ht'] ?? 0);
//            $tva = is_numeric($finalTva) ? (float)$finalTva : 0.0;
//            $remise = (float)($data['remise_percent'] ?? 0);
//            $taxe = (float)($data['taxe_percent'] ?? 0);
//            $timbre = (float)($data['timbre_percent'] ?? 0);
//
//            // All % on HT
//            $remiseAmt = $ht * ($remise / 100);
//            $tvaAmt = $ht * ($tva / 100);
//            $taxeAmt = $ht * ($taxe / 100);
//            $timbAmt = $ht * ($timbre / 100);
//
//            $ttc = ($ht - $remiseAmt) + $tvaAmt + $taxeAmt + $timbAmt;
//
//            // Write nationals
//            $data['taux_tva'] = $tva;
//            $data['custom_tva'] = ($tauxTvaInput === 'custom') ? (float)($data['custom_tva'] ?? 0) : null;
//            $data['montant_ttc'] = round($ttc, 6);
//
//            // Clear internationals
//            $data['monnaie'] = null;
//            $data['taux_conversion'] = null;
//            $data['montant_devise'] = null;
//            $data['ibs_percent'] = 0;
//            $data['ibs_devise'] = null;
//            $data['montant_devise_net'] = null;
//            $data['montant_ttc_local'] = null;
//            $data['montant_ht_local'] = null;
//        } else {
//            // International: source = devise; IBS% on devise; DZD derived
//            $taux = (float)($data['taux_conversion'] ?? 0);
//            $dev = (float)($data['montant_devise'] ?? 0);
//            $ibsPct = (float)($data['ibs_percent'] ?? 0);
//
//            $ibsDev = $dev * ($ibsPct / 100);
//            $devNet = max(0, $dev - $ibsDev);
//            $ttcDzd = $dev * $taux;
//            $htDzd = $devNet * $taux;
//
//            // Store internationals
//            $data['ibs_devise'] = round($ibsDev, 6);
//            $data['montant_devise_net'] = round($devNet, 6);
//            $data['montant_ttc_local'] = $taux > 0 ? round($ttcDzd, 6) : null;
//            $data['montant_ht_local'] = $taux > 0 ? round($htDzd, 6) : null;
//
//            // Mirror in generic TTC for listings
//            $data['montant_ttc'] = $data['montant_ttc_local'];
//
//            // Clear nationals
//            $data['montant_ht'] = null;
//            $data['taux_tva'] = null;
//            $data['custom_tva'] = null;
//            $data['remise_percent'] = 0;
//            $data['taxe_percent'] = 0;
//            $data['timbre_percent'] = 0;
//        }
//
//        $facture = $dossier->factures()->create($data);
//
//        // Optional attachments relation (e.g. $facture->files())
//        if ($request->hasFile('attachments') && method_exists($facture, 'files')) {
//            foreach ($request->file('attachments') as $file) {
//                $path = $file->store("factures/{$facture->id}", 'public');
//                $facture->files()->create(['path' => $path]);
//            }
//        }
//
//        return redirect()->route('admin.dossiers.details', $dossier->id)
//            ->with('success', 'Facture ajoutée avec succès.');
//    }
//
//    public function edit(Dossier $dossier, Facture $facture)
//    {
//        $directions = Direction::orderBy('name')->get();
//        $comptes = Compte::orderBy('name')->get();
//        $pageTitle = 'Modifier une Facture';
//
//        return view('admin.dossier.facture.edit', compact('facture', 'dossier', 'directions', 'comptes', 'pageTitle'));
//    }
//
//    public function update(Request $request, Dossier $dossier, Facture $facture)
//    {
//        // Handle "custom" TVA selection
//        $tauxTvaInput = $request->input('taux_tva');
//        $finalTva = $tauxTvaInput === 'custom' ? $request->input('custom_tva') : $tauxTvaInput;
//
//        $data = $request->validate([
//            // Core/meta
//            'type_dossier' => ['required', Rule::in(['national', 'international'])],
//            'ref_facture' => 'nullable|string|max:255',
//            'date_facture' => 'nullable|date',
//            'bon_commande' => 'nullable|string|max:255',
//            'periode' => 'nullable|string|max:255',
//            'objet' => 'nullable|string|max:255',
//            'direction_id' => 'nullable|exists:directions,id',
//            'compte_id' => 'nullable|exists:comptes,id',
//            'observation' => 'nullable|string',
//
//            // National
//            'montant_ht' => 'nullable|numeric|min:0',
//            'taux_tva' => 'nullable',
//            'custom_tva' => 'nullable|numeric|min:0',
//            'remise_percent' => 'nullable|numeric|min:0',
//            'taxe_percent' => 'nullable|numeric|min:0',
//            'timbre_percent' => 'nullable|numeric|min:0',
//
//            // International
//            'monnaie' => ['nullable', Rule::in(['USD', 'EUR'])],
//            'taux_conversion' => 'nullable|numeric|min:0',
//            'montant_devise' => 'nullable|numeric|min:0',
//            'ibs_percent' => 'nullable|numeric|min:0',
//
//            // Files
//            'attachments' => 'nullable|array|max:5',
//            'attachments.*' => 'file|mimes:jpeg,jpg,png,pdf,doc,docx|max:' . (int)(1024 * 10),
//        ]);
//
//        if ($data['type_dossier'] === 'national') {
//            $ht = (float)($data['montant_ht'] ?? $facture->montant_ht ?? 0);
//            $tva = is_numeric($finalTva) ? (float)$finalTva : 0.0;
//            $remise = (float)($data['remise_percent'] ?? 0);
//            $taxe = (float)($data['taxe_percent'] ?? 0);
//            $timbre = (float)($data['timbre_percent'] ?? 0);
//
//            $remiseAmt = $ht * ($remise / 100);
//            $tvaAmt = $ht * ($tva / 100);
//            $taxeAmt = $ht * ($taxe / 100);
//            $timbAmt = $ht * ($timbre / 100);
//
//            $ttc = ($ht - $remiseAmt) + $tvaAmt + $taxeAmt + $timbAmt;
//
//            $data['taux_tva'] = $tva;
//            $data['custom_tva'] = ($tauxTvaInput === 'custom') ? (float)($data['custom_tva'] ?? 0) : null;
//            $data['montant_ttc'] = round($ttc, 6);
//
//            // Clear internationals
//            $data['monnaie'] = null;
//            $data['taux_conversion'] = null;
//            $data['montant_devise'] = null;
//            $data['ibs_percent'] = 0;
//            $data['ibs_devise'] = null;
//            $data['montant_devise_net'] = null;
//            $data['montant_ttc_local'] = null;
//            $data['montant_ht_local'] = null;
//        } else {
//            $taux = (float)($data['taux_conversion'] ?? 0);
//            $dev = (float)($data['montant_devise'] ?? 0);
//            $ibsPct = (float)($data['ibs_percent'] ?? 0);
//
//            $ibsDev = $dev * ($ibsPct / 100);
//            $devNet = max(0, $dev - $ibsDev);
//            $ttcDzd = $dev * $taux;
//            $htDzd = $devNet * $taux;
//
//            $data['ibs_devise'] = round($ibsDev, 6);
//            $data['montant_devise_net'] = round($devNet, 6);
//            $data['montant_ttc_local'] = $taux > 0 ? round($ttcDzd, 6) : null;
//            $data['montant_ht_local'] = $taux > 0 ? round($htDzd, 6) : null;
//
//            // Mirror TTC to generic column
//            $data['montant_ttc'] = $data['montant_ttc_local'];
//
//            // Clear nationals
//            $data['montant_ht'] = null;
//            $data['taux_tva'] = null;
//            $data['custom_tva'] = null;
//            $data['remise_percent'] = 0;
//            $data['taxe_percent'] = 0;
//            $data['timbre_percent'] = 0;
//        }
//
//        $facture->update($data);
//
//        // Optional: replace attachments if relation exists
//        if ($request->hasFile('attachments') && method_exists($facture, 'files')) {
//            foreach ($facture->files as $old) {
//                if ($old->path && Storage::disk('public')->exists($old->path)) {
//                    Storage::disk('public')->delete($old->path);
//                }
//                $old->delete();
//            }
//            foreach ($request->file('attachments') as $file) {
//                $path = $file->store("factures/{$facture->id}", 'public');
//                $facture->files()->create(['path' => $path]);
//            }
//        }
//
//        return redirect()->route('dossiers.factures.index', $dossier->id)
//            ->with('success', 'Facture mise à jour avec succès.');
//    }
//
//    public function destroy(Dossier $dossier, Facture $facture)
//    {
//        if (method_exists($facture, 'files')) {
//            foreach ($facture->files as $file) {
//                if ($file->path && Storage::disk('public')->exists($file->path)) {
//                    Storage::disk('public')->delete($file->path);
//                }
//                $file->delete();
//            }
//        }
//
//        $facture->delete();
//        return back()->with('success', 'Facture supprimée.');
//    }
//}


//namespace App\Http\Controllers\Admin;
//
//use App\Http\Controllers\Controller;
//use App\Models\Compte;
//use App\Models\Direction;
//use App\Models\Dossier;
//use App\Models\Facture;
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Storage;
//use Illuminate\Validation\Rule;
//
//class FactureController extends Controller
//{
//    /** Convert "12,50" => 12.50 and return float|null */
//    private function asNum($v): ?float
//    {
//        if ($v === null || $v === '') return null;
//        $v = str_replace([' ', ','], ['', '.'], (string)$v);
//        return is_numeric($v) ? (float)$v : null;
//    }
//
//    /** Round to 6 decimals to match schema */
//    private function r6($n): ?float
//    {
//        if ($n === null) return null;
//        return round((float)$n, 6);
//    }
//
//    public function index(Dossier $dossier)
//    {
//        $factures = $dossier->factures()
//            ->with(['direction', 'compte'])
//            ->latest()
//            ->paginate(10);
//
//        return view('admin.factures.index', compact('factures', 'dossier'));
//    }
//
//    public function create(Dossier $dossier)
//    {
//        $directions = Direction::orderBy('name')->get();
//        $comptes = Compte::orderBy('name')->get();
//        $pageTitle = 'Créer une Facture';
//
//        return view('admin.dossier.facture.create', compact('dossier', 'pageTitle', 'directions', 'comptes'));
//    }
//
//    public function store(Request $request, Dossier $dossier)
//    {
//        dd($request->all());
//
//        // Normalize TVA selection (custom vs fixed)
//        $tauxTvaInput = $request->input('taux_tva');
//        $finalTva = $tauxTvaInput === 'custom' ? $request->input('custom_tva') : $tauxTvaInput;
//
//        $data = $request->validate([
//            // Core/meta
//            'type_dossier' => ['required', Rule::in(['national', 'international'])],
//            'ref_facture' => 'nullable|string|max:255',
//            'date_facture' => 'nullable|date',
//            'bon_commande' => 'nullable|string|max:255',
//            'numero_contrat' => 'nullable|string|max:255',
//            'periode' => 'nullable|string|max:255',
//            'objet' => 'nullable|string|max:255',
//            'direction_id' => 'nullable|exists:directions,id',
//            'compte_id' => 'nullable|exists:comptes,id',
//            'observation' => 'nullable|string',
//
//            // NATIONAL (all % on HT)
//            'montant_ht' => 'nullable',
//            'taux_tva' => 'nullable',
//            'custom_tva' => 'nullable',
//            'remise_percent' => 'nullable',
//            'taxe_percent' => 'nullable',
//            'timbre_percent' => 'nullable',
//
//            // INTERNATIONAL (source = devise)
//            'monnaie' => ['nullable', Rule::in(['USD', 'EUR'])],
//            'taux_conversion' => 'nullable',
//            'montant_devise' => 'nullable',
//            'ibs_percent' => 'nullable',
//
//            // Files (optional if you have a relation)
//            'attachments' => 'nullable|array|max:5',
//            'attachments.*' => 'file|mimes:jpeg,jpg,png,pdf,doc,docx|max:' . (int)(1024 * 10),
//        ]);
//
//        $data['dossier_id'] = $dossier->id;
//
//        if ($data['type_dossier'] === 'national') {
//            // ---- normalize numbers ----
//            $ht = $this->asNum($data['montant_ht'] ?? null) ?? 0.0;
//            $tva = $this->asNum($finalTva) ?? 0.0;
//            $remise = $this->asNum($data['remise_percent'] ?? 0) ?? 0.0;
//            $taxe = $this->asNum($data['taxe_percent'] ?? 0) ?? 0.0;
//            $timbre = $this->asNum($data['timbre_percent'] ?? 0) ?? 0.0;
//
//            // ---- compute ----
//            $remiseAmt = $ht * ($remise / 100);
//            $tvaAmt = $ht * ($tva / 100);
//            $taxeAmt = $ht * ($taxe / 100);
//            $timbAmt = $ht * ($timbre / 100);
//
//            $ttc = ($ht - $remiseAmt) + $tvaAmt + $taxeAmt + $timbAmt;
//
//            // ---- write national columns ----
//            $data['montant_ht'] = $this->r6($ht);
//            $data['taux_tva'] = $this->r6($tva);
//            $data['custom_tva'] = ($tauxTvaInput === 'custom') ? $this->r6($data['custom_tva'] ?? null) : null;
//            $data['remise_percent'] = $this->r6($remise);
//            $data['taxe_percent'] = $this->r6($taxe);
//            $data['timbre_percent'] = $this->r6($timbre);
//            $data['montant_ttc'] = $this->r6($ttc);
//
//            // ---- clear international columns ----
//            $data['monnaie'] = null;
//            $data['taux_conversion'] = null;
//            $data['montant_devise'] = null;
//            $data['ibs_percent'] = 0;
//            $data['ibs_devise'] = null;
//            $data['montant_devise_net'] = null;
//            $data['montant_ttc_local'] = null;
//            $data['montant_ht_local'] = null;
//        } else {
//            // ---- INTERNATIONAL: source = devise; IBS% on devise; DZD derived ----
//            $taux = $this->asNum($data['taux_conversion'] ?? null) ?? 0.0;    // DZD per 1 unit
//            $dev = $this->asNum($data['montant_devise'] ?? null) ?? 0.0;    // TTC logique in devise
//            $ibsPct = $this->asNum($data['ibs_percent'] ?? null) ?? 0.0;
//
//            $ibsDev = $dev * ($ibsPct / 100);
//            $devNet = max(0, $dev - $ibsDev);
//            $ttcDzd = $dev * $taux;
//            $htDzd = $devNet * $taux;
//
//            // ---- write international columns ----
//            $data['monnaie'] = $data['monnaie'] ?? null;
//            $data['taux_conversion'] = $this->r6($taux);
//            $data['montant_devise'] = $this->r6($dev);
//            $data['ibs_percent'] = $this->r6($ibsPct);
//            $data['ibs_devise'] = $this->r6($ibsDev);
//            $data['montant_devise_net'] = $this->r6($devNet);
//            $data['montant_ttc_local'] = $taux > 0 ? $this->r6($ttcDzd) : null;
//            $data['montant_ht_local'] = $taux > 0 ? $this->r6($htDzd) : null;
//
//            // Mirror generic TTC for listings/exports
//            $data['montant_ttc'] = $data['montant_ttc_local'];
//
//            // ---- clear national columns ----
//            $data['montant_ht'] = null;
//            $data['taux_tva'] = null;
//            $data['custom_tva'] = null;
//            $data['remise_percent'] = 0;
//            $data['taxe_percent'] = 0;
//            $data['timbre_percent'] = 0;
//        }
//
//        $facture = $dossier->factures()->create($data);
//
//        // Optional attachments relation (if exists: $facture->files())
//        if ($request->hasFile('attachments') && method_exists($facture, 'files')) {
//            foreach ($request->file('attachments') as $file) {
//                $path = $file->store("factures/{$facture->id}", 'public');
//                $facture->files()->create(['path' => $path]);
//            }
//        }
//
//        return redirect()->route('admin.dossiers.details', $dossier->id)
//            ->with('success', 'Facture ajoutée avec succès.');
//    }
//
//    public function edit(Dossier $dossier, Facture $facture)
//    {
//        $directions = Direction::orderBy('name')->get();
//        $comptes = Compte::orderBy('name')->get();
//        $pageTitle = 'Modifier une Facture';
//
//        return view('admin.dossier.facture.edit', compact('facture', 'dossier', 'directions', 'comptes', 'pageTitle'));
//    }
//
//    public function update(Request $request, Dossier $dossier, Facture $facture)
//    {
//        $tauxTvaInput = $request->input('taux_tva');
//        $finalTva = $tauxTvaInput === 'custom' ? $request->input('custom_tva') : $tauxTvaInput;
//
//        $data = $request->validate([
//            // Core/meta
//            'type_dossier' => ['required', Rule::in(['national', 'international'])],
//            'ref_facture' => 'nullable|string|max:255',
//            'date_facture' => 'nullable|date',
//            'bon_commande' => 'nullable|string|max:255',
//            'numero_contrat' => 'nullable|string|max:255',
//            'periode' => 'nullable|string|max:255',
//            'objet' => 'nullable|string|max:255',
//            'direction_id' => 'nullable|exists:directions,id',
//            'compte_id' => 'nullable|exists:comptes,id',
//            'observation' => 'nullable|string',
//
//            // NATIONAL
//            'montant_ht' => 'nullable',
//            'taux_tva' => 'nullable',
//            'custom_tva' => 'nullable',
//            'remise_percent' => 'nullable',
//            'taxe_percent' => 'nullable',
//            'timbre_percent' => 'nullable',
//
//            // INTERNATIONAL
//            'monnaie' => ['nullable', Rule::in(['USD', 'EUR'])],
//            'taux_conversion' => 'nullable',
//            'montant_devise' => 'nullable',
//            'ibs_percent' => 'nullable',
//
//            // Files
//            'attachments' => 'nullable|array|max:5',
//            'attachments.*' => 'file|mimes:jpeg,jpg,png,pdf,doc,docx|max:' . (int)(1024 * 10),
//        ]);
//
//        if ($data['type_dossier'] === 'national') {
//            $ht = $this->asNum($data['montant_ht'] ?? $facture->montant_ht ?? null) ?? 0.0;
//            $tva = $this->asNum($finalTva) ?? 0.0;
//            $remise = $this->asNum($data['remise_percent'] ?? $facture->remise_percent ?? 0) ?? 0.0;
//            $taxe = $this->asNum($data['taxe_percent'] ?? $facture->taxe_percent ?? 0) ?? 0.0;
//            $timbre = $this->asNum($data['timbre_percent'] ?? $facture->timbre_percent ?? 0) ?? 0.0;
//
//            $remiseAmt = $ht * ($remise / 100);
//            $tvaAmt = $ht * ($tva / 100);
//            $taxeAmt = $ht * ($taxe / 100);
//            $timbAmt = $ht * ($timbre / 100);
//
//            $ttc = ($ht - $remiseAmt) + $tvaAmt + $taxeAmt + $timbAmt;
//
//            $data['montant_ht'] = $this->r6($ht);
//            $data['taux_tva'] = $this->r6($tva);
//            $data['custom_tva'] = ($tauxTvaInput === 'custom') ? $this->r6($data['custom_tva'] ?? null) : null;
//            $data['remise_percent'] = $this->r6($remise);
//            $data['taxe_percent'] = $this->r6($taxe);
//            $data['timbre_percent'] = $this->r6($timbre);
//            $data['montant_ttc'] = $this->r6($ttc);
//
//            // clear international
//            $data['monnaie'] = null;
//            $data['taux_conversion'] = null;
//            $data['montant_devise'] = null;
//            $data['ibs_percent'] = 0;
//            $data['ibs_devise'] = null;
//            $data['montant_devise_net'] = null;
//            $data['montant_ttc_local'] = null;
//            $data['montant_ht_local'] = null;
//        } else {
//            $taux = $this->asNum($data['taux_conversion'] ?? $facture->taux_conversion ?? null) ?? 0.0;
//            $dev = $this->asNum($data['montant_devise'] ?? $facture->montant_devise ?? null) ?? 0.0;
//            $ibsPct = $this->asNum($data['ibs_percent'] ?? $facture->ibs_percent ?? null) ?? 0.0;
//
//            $ibsDev = $dev * ($ibsPct / 100);
//            $devNet = max(0, $dev - $ibsDev);
//            $ttcDzd = $dev * $taux;
//            $htDzd = $devNet * $taux;
//
//            $data['monnaie'] = $data['monnaie'] ?? $facture->monnaie ?? null;
//            $data['taux_conversion'] = $this->r6($taux);
//            $data['montant_devise'] = $this->r6($dev);
//            $data['ibs_percent'] = $this->r6($ibsPct);
//            $data['ibs_devise'] = $this->r6($ibsDev);
//            $data['montant_devise_net'] = $this->r6($devNet);
//            $data['montant_ttc_local'] = $taux > 0 ? $this->r6($ttcDzd) : null;
//            $data['montant_ht_local'] = $taux > 0 ? $this->r6($htDzd) : null;
//
//            // Mirror TTC generic
//            $data['montant_ttc'] = $data['montant_ttc_local'];
//
//            // clear national
//            $data['montant_ht'] = null;
//            $data['taux_tva'] = null;
//            $data['custom_tva'] = null;
//            $data['remise_percent'] = 0;
//            $data['taxe_percent'] = 0;
//            $data['timbre_percent'] = 0;
//        }
//
//        $facture->update($data);
//
//        // Replace attachments (if relation exists)
//        if ($request->hasFile('attachments') && method_exists($facture, 'files')) {
//            foreach ($facture->files as $old) {
//                if ($old->path && Storage::disk('public')->exists($old->path)) {
//                    Storage::disk('public')->delete($old->path);
//                }
//                $old->delete();
//            }
//            foreach ($request->file('attachments') as $file) {
//                $path = $file->store("factures/{$facture->id}", 'public');
//                $facture->files()->create(['path' => $path]);
//            }
//        }
//
//        return redirect()->route('admin.dossiers.factures.index', $dossier->id)
//            ->with('success', 'Facture mise à jour avec succès.');
//    }
//
//    public function destroy(Dossier $dossier, Facture $facture)
//    {
//        if (method_exists($facture, 'files')) {
//            foreach ($facture->files as $file) {
//                if ($file->path && Storage::disk('public')->exists($file->path)) {
//                    Storage::disk('public')->delete($file->path);
//                }
//                $file->delete();
//            }
//        }
//
//        $facture->delete();
//        return back()->with('success', 'Facture supprimée.');
//    }
//}


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Compte;
use App\Models\Direction;
use App\Models\Dossier;
use App\Models\Facture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class FactureController extends Controller
{
    /** Optional: turn "12,50" into 12.50, return float|null */
    private function asNum($v): ?float
    {
        if ($v === null || $v === '') return null;
        $v = str_replace([' ', ','], ['', '.'], (string)$v);
        return is_numeric($v) ? (float)$v : null;
    }

    /** Optional: round to 6 decimals (fits your schema) */
    private function r6($n): ?float
    {
        return $n === null ? null : round((float)$n, 6);
    }

    /** ===== INDEX: factures for one dossier ===== */
    public function index(Dossier $dossier)
    {
        $factures = $dossier->factures()
            ->latest()
            ->paginate(10);

        return view('admin.dossier.facture.index', compact('dossier', 'factures'));
    }

    /** ===== CREATE form (scoped) ===== */
    public function create(Dossier $dossier)
    {
        // keep if your Blade shows these selects; otherwise remove both lines
        $directions = Direction::orderBy('name')->get();
        $comptes = Compte::orderBy('name')->get();

        return view('admin.dossier.facture.create', [
            'dossier' => $dossier,
            'pageTitle' => 'Créer une Facture',
            'directions' => $directions,
            'comptes' => $comptes,
        ]);
    }

    /** ===== STORE (strictly creates a facture under this dossier) ===== */
    public function store(Request $request, Dossier $dossier)
    {
        $data = $request->validate([
            // Core/meta
            'type_dossier' => ['required', Rule::in(['national', 'international'])],
            'ref_facture' => 'nullable|string|max:255',
            'date_facture' => 'nullable|date',
            'bon_commande' => 'nullable|string|max:255',
            'numero_contrat' => 'nullable|string|max:255',
            'periode' => 'nullable|string|max:255',
            'objet' => 'nullable|string|max:255',
            'direction_id' => 'nullable|exists:directions,id',
            'compte_id' => 'nullable|exists:comptes,id',
            'observation' => 'nullable|string',
            // National
            'montant_ht' => 'nullable',
            'taux_tva' => 'nullable',
            'custom_tva' => 'nullable',
            'remise_percent' => 'nullable',
            'taxe_percent' => 'nullable',
            'timbre_percent' => 'nullable',
            'montant_ttc' => 'nullable',
            // International
            'monnaie' => ['nullable', Rule::in(['USD', 'EUR'])],
            'taux_conversion' => 'nullable',
            'montant_devise' => 'nullable',
            'ibs_percent' => 'nullable',
            'ibs_devise' => 'nullable',
            'montant_devise_net' => 'nullable',
            'montant_ttc_local' => 'nullable',
            'montant_ht_local' => 'nullable',
            // Files (if you have a files() relation on Facture)
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|mimes:jpeg,jpg,png,pdf,doc,docx|max:10240',
        ]);

        // Link ONLY by id – this is the sole “dossier touch”
        $data['dossier_id'] = $dossier->id;

        // Optional small normalization (keeps your decimals sane; remove if unwanted)
        foreach ([
                     'montant_ht', 'taux_tva', 'custom_tva', 'remise_percent', 'taxe_percent', 'timbre_percent',
                     'montant_ttc', 'taux_conversion', 'montant_devise', 'ibs_percent', 'ibs_devise',
                     'montant_devise_net', 'montant_ttc_local', 'montant_ht_local'
                 ] as $k) {
            if (array_key_exists($k, $data)) {
                $data[$k] = $this->r6($this->asNum($data[$k]));
            }
        }

        $facture = $dossier->factures()->create($data);

        if ($request->hasFile('attachments') && method_exists($facture, 'files')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store("factures/{$facture->id}", 'public');
                $facture->files()->create(['path' => $path]);
            }
        }


        return redirect()->route('admin.dossiers.details', $dossier->id)
            ->with('success', 'Facture ajoutée avec succès.');
    }

    /** ===== EDIT (guard ownership) ===== */
    public function edit(Dossier $dossier, Facture $facture)
    {
        abort_if($facture->dossier_id !== $dossier->id, 404);

        // keep if your Blade uses them
        $directions = Direction::orderBy('name')->get();
        $comptes = Compte::orderBy('name')->get();
        $pageTitle = 'Modifier une Facture';

        return view('admin.dossier.facture.edit', compact('dossier', 'facture', 'directions', 'comptes', 'pageTitle'));
    }

    /** ===== UPDATE (still only this facture) ===== */
    public function update(Request $request, Dossier $dossier, Facture $facture)
    {
        abort_if($facture->dossier_id !== $dossier->id, 404);

        $data = $request->validate([
            'type_dossier' => ['required', Rule::in(['national', 'international'])],
            'ref_facture' => 'nullable|string|max:255',
            'date_facture' => 'nullable|date',
            'bon_commande' => 'nullable|string|max:255',
            'numero_contrat' => 'nullable|string|max:255',
            'periode' => 'nullable|string|max:255',
            'objet' => 'nullable|string|max:255',
            'direction_id' => 'nullable|exists:directions,id',
            'compte_id' => 'nullable|exists:comptes,id',
            'observation' => 'nullable|string',
            // National
            'montant_ht' => 'nullable',
            'taux_tva' => 'nullable',
            'custom_tva' => 'nullable',
            'remise_percent' => 'nullable',
            'taxe_percent' => 'nullable',
            'timbre_percent' => 'nullable',
            'montant_ttc' => 'nullable',
            // International
            'monnaie' => ['nullable', Rule::in(['USD', 'EUR'])],
            'taux_conversion' => 'nullable',
            'montant_devise' => 'nullable',
            'ibs_percent' => 'nullable',
            'ibs_devise' => 'nullable',
            'montant_devise_net' => 'nullable',
            'montant_ttc_local' => 'nullable',
            'montant_ht_local' => 'nullable',
            // Files
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|mimes:jpeg,jpg,png,pdf,doc,docx|max:10240',
        ]);

        // Optional decimal normalization
        foreach ([
                     'montant_ht', 'taux_tva', 'custom_tva', 'remise_percent', 'taxe_percent', 'timbre_percent',
                     'montant_ttc', 'taux_conversion', 'montant_devise', 'ibs_percent', 'ibs_devise',
                     'montant_devise_net', 'montant_ttc_local', 'montant_ht_local'
                 ] as $k) {
            if (array_key_exists($k, $data)) {
                $data[$k] = $this->r6($this->asNum($data[$k]));
            }
        }

        $facture->update($data);

        // Optional: replace attachments
        if ($request->hasFile('attachments') && method_exists($facture, 'files')) {
            foreach ($facture->files as $old) {
                if ($old->path && Storage::disk('public')->exists($old->path)) {
                    Storage::disk('public')->delete($old->path);
                }
                $old->delete();
            }
            foreach ($request->file('attachments') as $file) {
                $path = $file->store("factures/{$facture->id}", 'public');
                $facture->files()->create(['path' => $path]);
            }
        }

        return redirect()->route('admin.dossiers.factures.index', $dossier->id)
            ->with('success', 'Facture mise à jour avec succès.');
    }

    /** ===== DESTROY (guard ownership) ===== */
    public function destroy(Dossier $dossier, Facture $facture)
    {
        abort_if($facture->dossier_id !== $dossier->id, 404);

        if (method_exists($facture, 'files')) {
            foreach ($facture->files as $file) {
                if ($file->path && Storage::disk('public')->exists($file->path)) {
                    Storage::disk('public')->delete($file->path);
                }
                $file->delete();
            }
        }

        $facture->delete();

        return back()->with('success', 'Facture supprimée.');
    }
}
