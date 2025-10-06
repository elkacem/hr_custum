<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seater;
use Illuminate\Http\Request;

class SeaterController extends Controller
{
    public function index()
    {
        $seaters = Seater::searchable(['number'])->paginate(getPaginate());
        $pageTitle = 'All Seaters';
        return view('admin.seater.index', compact('pageTitle','seaters'));
    }
    public function store(Request $request, $id = 0)
    {
        $request->validate([
            'number'        => 'required|unique:seaters,number,' . $id,

        ]);

        if ($id) {
            $seater           = Seater::findOrFail($id);
            $notification       = 'Seater updated successfully';
        } else {
            $seater           = new Seater();
            $notification       = 'Seater added successfully';
        }
        $seater->number = $request->number;
        $seater->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return Seater::changeStatus($id);
    }
}
