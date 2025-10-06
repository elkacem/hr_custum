<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Constants\Status;

use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::searchable(['name'])->orderBy('name')->paginate(getPaginate());
        $pageTitle = 'All Brands';
        return view('admin.brand.index', compact('pageTitle','brands'));
    }
    public function store(Request $request, $id = 0)
    {
        $request->validate([
            'name'        => 'required|unique:brands,name,' . $id,

        ]);

        if ($id) {
            $brand           = Brand::findOrFail($id);
            $notification       = 'Brand updated successfully';
        } else {
            $brand           = new Brand();
            $notification       = 'Brand added successfully';
        }
        $brand->name = $request->name;
        $brand->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return Brand::changeStatus($id);
    }
}
