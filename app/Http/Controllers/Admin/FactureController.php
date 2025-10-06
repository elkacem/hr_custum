<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Compte;
use App\Models\Direction;
use App\Models\Dossier;
use App\Models\Facture;
use Illuminate\Http\Request;

class FactureController extends Controller
{
    public function index(Dossier $dossier)
    {
        $factures = $dossier->factures()->paginate(10);
        return view('admin.factures.index', compact('factures','dossier'));
    }

    public function create(Dossier $dossier)
    {
        $directions = Direction::orderBy('name')->get();
        $comptes = Compte::orderBy('name')->get();
        $pageTitle = 'Créer une Facture';
        return view('admin.dossier.facture.create', compact('dossier', 'pageTitle', 'directions', 'comptes'));
    }

    public function store(Request $request, Dossier $dossier)
    {
        $validated = $request->validate([
            'ref_facture' => 'required|string|max:255',
            'date_facture' => 'nullable|date',
            'montant_ht' => 'nullable|numeric',
            'taux_tva' => 'nullable|numeric',
        ]);

        if (!empty($validated['montant_ht']) && !empty($validated['taux_tva'])) {
            $validated['montant_ttc'] = $validated['montant_ht'] * (1 + $validated['taux_tva'] / 100);
        }

        $dossier->factures()->create($validated);

        return redirect()->route('admin.dossiers.details', $dossier->id)
            ->with('success', 'Facture ajoutée avec succès.');
    }

    public function edit(Dossier $dossier, Facture $facture)
    {
        $directions = Direction::orderBy('name')->get();
        $comptes = Compte::orderBy('name')->get();
        $pageTitle = 'Modifier une Facture';

        return view('admin.dossier.facture.edit', compact('facture','dossier','directions','comptes', 'pageTitle'));
    }

    public function update(Request $request, Dossier $dossier, Facture $facture)
    {
        $validated = $request->validate([
            'ref_facture' => 'required|string|max:255',
            'date_facture' => 'nullable|date',
            'montant_ht' => 'nullable|numeric',
            'taux_tva' => 'nullable|numeric',
            'objet' => 'nullable|string|max:255',
            'compte_id' => 'nullable|exists:comptes,id',
            'direction_id' => 'nullable|exists:directions,id',
        ]);


        if (!empty($validated['montant_ht']) && !empty($validated['taux_tva'])) {
            $validated['montant_ttc'] = $validated['montant_ht'] * (1 + $validated['taux_tva'] / 100);
        }

        $facture->update($validated);

        return redirect()->route('dossiers.factures.index', $dossier->id)
            ->with('success', 'Facture mise à jour avec succès.');
    }

    public function destroy(Dossier $dossier, Facture $facture)
    {
        $facture->delete();
        return back()->with('success', 'Facture supprimée.');
    }
}
