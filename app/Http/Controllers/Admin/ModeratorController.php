<?php
//
//namespace App\Http\Controllers\Admin;
//
//use App\Http\Controllers\Controller;
//use App\Models\Admin;
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Hash;
//
//class ModeratorController extends Controller
//{
//    public function index()
//    {
//        $admins = Admin::latest()->paginate(getPaginate());
//        $pageTitle = "Manage Users";
//        $emptyMessage = "No users found";
//        return view('admin.moderator.index', compact('admins', 'pageTitle', 'emptyMessage'));
//    }
//
//    public function store(Request $request, $id = null)
//    {
//        $request->validate([
//            'name' => 'required|string|max:40',
//            'email' => 'required|string|email|max:40|unique:admins,email,' . $id,
//            'username' => 'required|string|max:40|unique:admins,username,' . $id,
//            'role' => 'required|in:admin,moderator,payment_service,payment_department,other_structure',
//            'password' => $id ? 'nullable|string|min:6' : 'required|string|min:6',
//        ]);
//
//        $admin = $id ? Admin::findOrFail($id) : new Admin();
//
//        $admin->name = $request->name;
//        $admin->email = $request->email;
//        $admin->username = $request->username;
//        $admin->role = $request->role;
//
//        if ($request->password) {
//            $admin->password = Hash::make($request->password);
//        }
//
//        $admin->save();
//
//        $notify[] = ['success', 'User saved successfully'];
//        return back()->withNotify($notify);
//    }
//
//    public function status($id)
//    {
//        $admin = Admin::findOrFail($id);
//        $admin->status = $admin->status ? 0 : 1;
//        $admin->save();
//
//        $notify[] = ['success', 'User status updated successfully'];
//        return back()->withNotify($notify);
//    }
//}


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ModeratorController extends Controller
{
    public function index()
    {
        $admins = Admin::latest()->paginate(getPaginate());
        $pageTitle = "Manage Users";
        $emptyMessage = "No users found";
        return view('admin.moderator.index', compact('admins', 'pageTitle', 'emptyMessage'));
    }

    public function store(Request $request, $id = null)
    {
        $request->validate([
            'name' => 'required|string|max:40',
            'email' => 'required|string|email|max:40|unique:admins,email,' . $id,
            'username' => 'required|string|max:40|unique:admins,username,' . $id,
            'role' => 'required|in:admin,agent,payment_service,payment_department,other_structure',
            'password' => $id ? 'nullable|string|min:6' : 'required|string|min:6',
        ]);

        $admin = $id ? Admin::findOrFail($id) : new Admin();

        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->username = $request->username;
        $admin->role = $request->role;
        $admin->status = 1; // Set status to active when creating or updating

        if ($request->password) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        $notify[] = ['success', 'User saved successfully'];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        $admin = Admin::findOrFail($id);
        $admin->status = $admin->status ? 0 : 1;
        $admin->save();


        $notify[] = ['success', 'User status updated successfully'];
        return back()->withNotify($notify);
    }
}

