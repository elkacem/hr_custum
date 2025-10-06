<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Direction;
use Illuminate\Http\Request;

class DirectionController extends Controller
{
    public function index()
    {
        $directions = Direction::latest()->paginate(10);
        $pageTitle = 'Toutes les Directions';

        return view('admin.direction.index', compact('directions', 'pageTitle'));
    }

    public function create()
    {
        $pageTitle = 'Ajouter une Direction';
        return view('admin.direction.create', compact('pageTitle'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id'   => 'nullable|exists:directions,id', // pour l'édition
            'name' => 'required|string|max:255|unique:directions,name,' . $request->id,
            'code' => 'nullable|string|max:50|unique:directions,code,' . $request->id,
        ]);

        if ($request->id) {
            // Update
            $direction = Direction::findOrFail($request->id);
            $direction->update($validated);
            $notify[] = ['success', 'Direction mise à jour avec succès.'];
        } else {
            // Create
            Direction::create($validated);
            $notify[] = ['success', 'Direction créée avec succès.'];
        }

        return back()->withNotify($notify);
    }

    public function edit(Direction $direction)
    {
        $pageTitle = 'Modifier la Direction';
        return view('admin.direction.edit', compact('direction', 'pageTitle'));
    }

    public function update(Request $request, Direction $direction)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:directions,name,' . $direction->id,
            'code' => 'nullable|string|max:50|unique:directions,code,' . $direction->id,
        ]);

        $direction->update($validated);

        return redirect()->route('admin.direction.index')
            ->with('success', 'Direction mise à jour avec succès.');
    }

    public function destroy(Direction $direction)
    {
        $direction->delete();

        return redirect()->route('admin.direction.index')
            ->with('success', 'Direction supprimée avec succès.');
    }

    public function status($id)
    {
        $direction = Direction::findOrFail($id);
        $direction->status = $direction->status ? 0 : 1;
        $direction->save();

        $notify[] = ['success', 'Statut de la direction mis à jour.'];
        return back()->withNotify($notify);
    }
}
