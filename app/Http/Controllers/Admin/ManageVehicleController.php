<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\RentLog;
use App\Models\Seater;
use App\Models\Vehicle;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class ManageVehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::searchable(['model', 'name', 'doors', 'fuel_type', 'transmission', 'brand:name', 'seater:number'])->with(['brand', 'seater'])->latest()->paginate(getPaginate());
        $pageTitle = 'All Vehicles';
        return view('admin.vehicle.index', compact('pageTitle','vehicles'));
    }

    public function status($id)
    {
        return Vehicle::changeStatus($id);
    }

    public function add()
    {
        $pageTitle = 'Add dossier';
        $brands = Brand::active()->orderBy('name')->get();
        $seaters = Seater::active()->orderBy('number')->get();
        return view('admin.vehicle.add', compact('pageTitle', 'brands', 'seaters'));
    }

    public function store(Request $request, $id = 0)
    {

        $isRequired = $id ? 'nullable' : 'required';

        $request->validate([
            'name'         => 'required|string',
            'brand_id'     => 'required|integer|gt:0',
            'seater_id'    => 'required|integer|gt:0',
            'price'        => 'required|numeric|gt:0',
            'details'      => 'required|string',
            'model'        => 'required|string',
            'matriculation' => 'required|string|max:50|unique:vehicles,matriculation',
            'doors'        => 'required|integer|gt:0',
            'transmission' => 'required|string',
            'fuel_type'    => 'required|string',
            'image'        => "$isRequired|array",
            'image.*'      => ["required", new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'icon'         => 'required|array',
            'icon.*'       => 'required|string',
            'label'        => 'required|array',
            'label.*'      => 'required|string',
            'value'        => 'required|array',
            'value.*'      => 'required|string',
        ]);

        if ($id) {
            $vehicle = Vehicle::findOrFail($id);
            $notification = 'Vehicle updated Successfully!';
        } else {
            $vehicle = new Vehicle();
            $notification = 'Vehicle Added Successfully!';
        }

        $vehicle->name         = $request->name;
        $vehicle->brand_id     = $request->brand_id;
        $vehicle->seater_id    = $request->seater_id;
        $vehicle->price        = $request->price;
        $vehicle->details      = $request->details;
        $vehicle->model        = $request->model;
        $vehicle->matriculation  = $request->matriculation;
        $vehicle->doors        = $request->doors;
        $vehicle->transmission = $request->transmission;
        $vehicle->fuel_type    = $request->fuel_type;

        foreach ($request->label as $key => $item) {
            $specifications[$item] = [
                $request->icon[$key],
                $request->label[$key],
                $request->value[$key]
            ];
        }
        $vehicle->specifications = $specifications;

        // Upload image
        if ($request->hasFile('image')) {
            foreach ($request->image as $image) {
                try {
                    $imageCollection[]  = fileUploader($image, getFilePath('dossier'), getFileSize('dossier'), '');
                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Couldn\'t upload card image'];
                    return back()->withNotify($notify);
                }
            }

            if (empty($vehicle->images)) {
                $vehicle->images = $imageCollection;
            } else {
                $vehicle->images = array_merge($vehicle->images, $imageCollection);
            }
        }


        $vehicle->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function edit($id)
    {
        $vehicle   = Vehicle::findOrFail($id);
        $pageTitle = 'Edit Vehicle';
        $brands    = Brand::active()->orderBy('name')->get();
        $seaters   = Seater::active()->orderBy('number')->get();
        return view('admin.vehicle.edit', compact('pageTitle', 'brands', 'seaters', 'vehicle'));
    }

    public function removeImage($id, $image)
    {
        $vehicle = Vehicle::findOrFail($id);
        $images = $vehicle->images;
        $imageIndex = array_search($image, $images);

        if ($imageIndex !== false) {
            if (count($images) > 1) {
                $path = getFilePath('dossier') . '/' . $image;
                @unlink($path);
                unset($images[$imageIndex]);
                $images = array_values($images);
                $vehicle->images = $images;
                $vehicle->save();
                $notify[] = ['success', "Vehicle image removed successfully"];
            } else {
                $notify[] = ['error', "Cannot remove the last image"];
            }
        } else {
            $notify[] = ['error', "Something went wrong!"];
        }
        return back()->withNotify($notify);
    }

    //Booking Log
    public function bookingLog()
    {
        $pageTitle = 'Vehicle Booking Log';
        $bookingLogs = RentLog::active()->searchable(['dossier:name','user:username', 'trx', 'price'])->with(['dossier', 'user', 'pickUpLocation', 'dropUpLocation'])->latest()->paginate(getPaginate());
        return view('admin.vehicle.booking_log', compact('pageTitle','bookingLogs'));
    }
    public function upcomingBookingLog()
    {
        $bookingLogs = RentLog::active()->searchable(['dossier:name','user:username', 'trx', 'price'])->upcoming()->with(['dossier', 'user', 'pickUpLocation', 'dropUpLocation'])->latest()->paginate(getPaginate());
        $pageTitle = 'Vehicle Upcoming Booking Log';
        return view('admin.vehicle.booking_log', compact('pageTitle','bookingLogs'));
    }
    public function runningBookingLog()
    {
        $bookingLogs = RentLog::active()->searchable(['dossier:name','user:username', 'trx', 'price'])->running()->with(['dossier', 'user', 'pickUpLocation', 'dropUpLocation'])->latest()->paginate(getPaginate());
        $pageTitle = 'Vehicle Running Booking Log';
        return view('admin.vehicle.booking_log', compact('pageTitle','bookingLogs'));
    }
    public function completedBookingLog()
    {
        $pageTitle = 'Vehicle Completed Booking Log';
        $bookingLogs = RentLog::active()->searchable(['dossier:name','user:username', 'trx', 'price'])->completed()->with(['dossier', 'user', 'pickUpLocation', 'dropUpLocation'])->latest()->paginate(getPaginate());
        return view('admin.vehicle.booking_log', compact('pageTitle','bookingLogs'));
    }

    public function userBookingLog($id)
    {
        $bookingLogs = RentLog::active()->searchable(['dossier:name','user:username', 'trx', 'price'])->where('user_id', $id)->with(['dossier', 'user', 'pickUpLocation', 'dropUpLocation'])->latest()->paginate(getPaginate());
        $pageTitle = 'User Vehicle Booking Log';
        return view('admin.vehicle.booking_log', compact('pageTitle','bookingLogs'));
    }
    public function userUpcomingBookingLog($id)
    {
        $bookingLogs = RentLog::active()->searchable(['dossier:name','user:username', 'trx', 'price'])->where('user_id', $id)->upcoming()->with(['dossier', 'user', 'pickUpLocation', 'dropUpLocation'])->latest()->paginate(getPaginate());
        $pageTitle = 'User Vehicle Upcoming Booking Log';
        return view('admin.vehicle.booking_log', compact('pageTitle','bookingLogs'));
    }
    public function userRunningBookingLog($id)
    {
        $bookingLogs = RentLog::active()->searchable(['dossier:name','user:username', 'trx', 'price'])->where('user_id', $id)->running()->with(['dossier', 'user', 'pickUpLocation', 'dropUpLocation'])->latest()->paginate(getPaginate());
        $pageTitle = 'User Vehicle Running Booking Log';
        return view('admin.vehicle.booking_log', compact('pageTitle','bookingLogs'));
    }
    public function userCompletedBookingLog($id)
    {
        $bookingLogs = RentLog::active()->searchable(['dossier:name','user:username', 'trx', 'price'])->where('user_id', $id)->completed()->with(['dossier', 'user', 'pickUpLocation', 'dropUpLocation'])->latest()->paginate(getPaginate());
        $pageTitle = 'User Vehicle Completed Booking Log';
        return view('admin.vehicle.booking_log', compact('pageTitle','bookingLogs'));
    }
}
