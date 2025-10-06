<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fournisseur;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    public function index()
    {
        $fournisseurs = Fournisseur::latest()->paginate(10);
        $pageTitle = 'Tous les Fournisseurs';

        return view('admin.fournisseur.index', compact('fournisseurs', 'pageTitle'));
    }

    public function create()
    {
        $pageTitle = 'Ajouter un Fournisseur';
        return view('admin.fournisseur.create', compact('pageTitle'));
    }


//    public function store(Request $request)
//    {
//        $validated = $request->validate([
//            'id' => 'nullable|exists:fournisseurs,id', // for edit
//            'name' => 'required|string|max:255',
//            'email' => 'nullable|email',
//            'phone' => 'nullable|string|max:20',
//            'address' => 'nullable|string|max:255',
//        ]);
//
//        if ($request->id) {
//            // Update existing fournisseur
//            $fournisseur = Fournisseur::findOrFail($request->id);
//            $fournisseur->update($validated);
//            $notify[] = ['success', 'Fournisseur mis à jour avec succès.'];
//        } else {
//            // Create new
//            Fournisseur::create($validated);
//            $notify[] = ['success', 'Fournisseur créé avec succès.'];
//        }
//
//        return back()->withNotify($notify);
//    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id' => 'nullable|exists:fournisseurs,id', // pour l'édition
            'name' => 'required|string|max:255|unique:fournisseurs,name,' . $request->id,
            'email' => 'nullable|email|unique:fournisseurs,email,' . $request->id,
            'phone' => 'nullable|string|max:20|unique:fournisseurs,phone,' . $request->id,
            'address' => 'nullable|string|max:255',
        ]);

        if ($request->id) {
            // Update
            $fournisseur = Fournisseur::findOrFail($request->id);
            $fournisseur->update($validated);
            $notify[] = ['success', 'Fournisseur mis à jour avec succès.'];
        } else {
            // Create
            Fournisseur::create($validated);
            $notify[] = ['success', 'Fournisseur créé avec succès.'];
        }

        return back()->withNotify($notify);
    }


    public function status($id)
    {
        $fournisseur = Fournisseur::findOrFail($id);
        $fournisseur->status = $fournisseur->status ? 0 : 1;
        $fournisseur->save();

        $notify[] = ['success', 'Statut du fournisseur mis à jour.'];
        return back()->withNotify($notify);
    }

    public function edit(Fournisseur $fournisseur)
    {
        $pageTitle = 'Modifier le Fournisseur';
        return view('admin.fournisseur.edit', compact('fournisseur', 'pageTitle'));
    }

    public function update(Request $request, Fournisseur $fournisseur)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $fournisseur->update($validated);

        return redirect()->route('admin.supplier.index')
            ->with('success', 'Fournisseur mis à jour avec succès.');
    }

    public function destroy(Fournisseur $fournisseur)
    {
        $fournisseur->delete();

        return redirect()->route('admin.supplier.index')
            ->with('success', 'Fournisseur supprimé avec succès.');
    }
}
