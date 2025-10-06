<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Compte;
use Illuminate\Http\Request;

class CompteController extends Controller
{
    public function index()
    {
        $comptes = Compte::latest()->paginate(10);
        $pageTitle = 'Toutes les Directions';

        return view('admin.compte.index', compact('comptes', 'pageTitle'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id'   => 'nullable|exists:comptes,id', // pour l'édition
            'name' => 'required|string|max:255|unique:comptes,name,' . $request->id,
            'code' => 'nullable|string|max:50|unique:comptes,code,' . $request->id,
        ]);

        if ($request->id) {
            // Update
            $direction = Compte::findOrFail($request->id);
            $direction->update($validated);
            $notify[] = ['success', 'Compte mise à jour avec succès.'];
        } else {
            // Create
            Compte::create($validated);
            $notify[] = ['success', 'Compte créée avec succès.'];
        }

        return back()->withNotify($notify);
    }

    public function destroy($id)
    {
        $compte = Compte::findOrFail($id);
        $compte->delete();

        return redirect()->route('admin.compte.index')
            ->with('success', 'Compte supprimée avec succès.');
    }

}
