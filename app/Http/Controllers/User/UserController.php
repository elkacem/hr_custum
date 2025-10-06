<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\PlanLog;
use App\Models\RentLog;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function home() {
        $pageTitle = 'Dashboard';

        $user = auth()->user();
        //Vehicle booking
        $data['total_vehicle_booking']     = RentLog::active()->where('user_id', $user->id)->count();
        $data['upcoming_vehicle_booking']  = RentLog::active()->where('user_id', $user->id)->upcoming()->count();
        $data['running_vehicle_booking']   = RentLog::active()->where('user_id', $user->id)->running()->count();
        $data['completed_vehicle_booking'] = RentLog::active()->where('user_id', $user->id)->completed()->count();

//        dd($data);

        $deposits = auth()->user()->deposits()->with(['gateway', 'rentLog', 'planLog'])->orderBy('id', 'desc')->take(10)->get();

        return view('template.user.dashboard', compact('pageTitle', 'user', 'data', 'deposits'));
    }

    public function userData() {
        $user = auth()->user();

        if ($user->profile_complete == Status::YES) {
            return to_route('user.home');
        }

        $pageTitle  = 'User Data';
        $info       = json_decode(json_encode(getIpInfo()), true);
        $mobileCode = @implode(',', $info['code']);
        $countries  = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        return view('template.user.user_data', compact('pageTitle', 'user', 'countries', 'mobileCode'));
    }

    public function userDataSubmit(Request $request) {

        $user = auth()->user();

        if ($user->profile_complete == Status::YES) {
            return to_route('user.home');
        }

        $countryData  = (array) json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryCodes = implode(',', array_keys($countryData));
        $mobileCodes  = implode(',', array_column($countryData, 'dial_code'));
        $countries    = implode(',', array_column($countryData, 'country'));

        $request->validate([
            'country_code' => 'required|in:' . $countryCodes,
            'country'      => 'required|in:' . $countries,
            'mobile_code'  => 'required|in:' . $mobileCodes,
            'username'     => 'required|unique:users|min:6',
            'mobile'       => ['required', 'regex:/^([0-9]*)$/', Rule::unique('users')->where('dial_code', $request->mobile_code)],
        ]);

        if (preg_match("/[^a-z0-9_]/", trim($request->username))) {
            $notify[] = ['info', __('auth.username_info')];
            $notify[] = ['error', __('auth.username_error')];
            return back()->withNotify($notify)->withInput($request->all());
        }

        $user->country_code = $request->country_code;
        $user->mobile       = $request->mobile;
        $user->username     = $request->username;

        $user->address      = $request->address;
        $user->city         = $request->city;
        $user->state        = $request->state;
        $user->zip          = $request->zip;
        $user->country_name = @$request->country;
        $user->dial_code    = $request->mobile_code;

        $user->profile_complete = Status::YES;
        $user->save();

        return to_route('user.home');
    }

    public function vehicleBooking($id, $slug) {
        if (!auth()->check()) {
            $notify[] = ['error', 'Please login to continue!'];
            return back()->withNotify($notify);
        }
        $pageTitle = 'Vehicle Booking';
        $vehicle   = Vehicle::active()->where('id', $id)->firstOrFail();
        $locations = Location::active()->orderBy('name')->get();
        return view('template.vehicles.booking', compact('vehicle', 'pageTitle', 'locations'));
    }

    public function bookingConfirm(Request $request, $id) {
        $request->validate([
            'pick_location' => 'required|integer', Rule::exists('locations', 'id')->where(function ($location) {
                $location->where('status', Status::ENABLE);
            }),

            'drop_location' => 'required|integer', Rule::exists('locations', 'id')->where(function ($location) {
                $location->where('status', Status::ENABLE);
            }) . 'not_in:' . $request->pick_location,

            'pick_time'     => 'required|date_format:Y-m-d h:i A|after_or_equal:today',
            'drop_time'     => 'required|date_format:Y-m-d h:i A|after_or_equal:' . $request->pick_time,
        ], [
            'drop_location.not_in' => 'Please choose different location!',
        ]);

        $vehicle = Vehicle::active()->where('id', $id)->firstOrFail();
//
//        if ($dossier->booked()) {
//            $notify[] = ['error', 'Already dossier is booked!'];
//            return back()->withNotify($notify);
//        }

        // Check if this dossier is available
        $pickTime = Carbon::parse($request->pick_time);
        $dropTime = Carbon::parse($request->drop_time);

//        $isBooked = $dossier->confirmedRents()
//            ->where(function ($query) use ($pickTime, $dropTime) {
//                $query->whereBetween('pick_time', [$pickTime, $dropTime])
//                    ->orWhereBetween('drop_time', [$pickTime, $dropTime])
//                    ->orWhere(function ($query) use ($pickTime, $dropTime) {
//                        $query->where('pick_time', '<', $pickTime)
//                            ->where('drop_time', '>', $dropTime);
//                    });
//            })
//            ->exists();

        $isBooked = $vehicle->confirmedRents()
            ->where(function ($query) use ($pickTime, $dropTime) {
                $query
                    // Case 1: booking starts before drop_time and ends after pick_time (overlap)
                    ->where('pick_time', '<', $dropTime)
                    ->where('drop_time', '>', $pickTime);
            })
            ->exists();

        if ($isBooked) {
            $alternativeVehicle = $vehicle->getAvailableVehicleInModel($pickTime, $dropTime);

            if ($alternativeVehicle) {
                $vehicle = $alternativeVehicle; // dynamically switch to available dossier of same model
            } else {
                $notify[] = ['error', 'No available vehicles in this model for the selected time, choose another time slot!'];
                return back()->withNotify($notify);
            }
        }


        $pickTime = Carbon::parse($request->pick_time);
        $dropTime = Carbon::parse($request->drop_time);

//        $totalDays  = $pickTime->diffInDays($dropTime);
        $totalDays = max(1, ceil($pickTime->diffInDays($dropTime, false)));
        $totalPrice = $vehicle->price * $totalDays;

        $rent                = new RentLog();
        $rent->user_id       = auth()->id();
        $rent->vehicle_id    = $vehicle->id;
        $rent->pick_location = $request->pick_location;
        $rent->drop_location = $request->drop_location;
        $rent->pick_time     = $pickTime;
        $rent->drop_time     = $dropTime;
        $rent->price         = getAmount($totalPrice);
        $rent->status  = Status::ENABLE;
        $rent->baby_seat = Status::DISABLE;
        $rent->insurance = Status::DISABLE;

        $rent->save();

        session(['rent_id' => $rent->id]);

//        return to_route('user.deposit.index');
        return to_route('user.dossier.insurance')->with([
            'success' => 'Booking confirmed successfully! Please choose an insurance plan.'
        ]);
    }

    public function depositHistory(Request $request) {
        $pageTitle = 'Payment History';
        $deposits  = auth()->user()->deposits()->searchable(['trx', 'planLog.plan:name', 'rentLog.dossier:name'])->with(['gateway', 'rentLog', 'planLog'])->orderBy('id', 'desc')->paginate(getPaginate());
//        dd($deposits);
//        dd($deposits);
        return view('template.user.deposit_history', compact('pageTitle', 'deposits'));
    }

    public function vehicleBookingLog() {
        $pageTitle   = 'Vehicle Booking Log';
        $bookingLogs = RentLog::active()->where('user_id', auth()->id())->with(['dossier', 'user', 'pickUpLocation', 'dropUpLocation'])->latest()->paginate(getPaginate());
//        dd($bookingLogs);
        return view('template.user.vehicle_booking_log', compact('pageTitle', 'bookingLogs'));
    }


    private function createRentLog(Vehicle $vehicle, Request $request)
    {
        $pickTime = Carbon::parse($request->pick_time);
        $dropTime = Carbon::parse($request->drop_time);

        $totalDays  = $pickTime->diffInDays($dropTime);
        $totalPrice = $vehicle->price * $totalDays;

        $rent = new RentLog();
        $rent->user_id = auth()->id();
        $rent->vehicle_id = $vehicle->id;
        $rent->pick_location = $request->pick_location;
        $rent->drop_location = $request->drop_location;
        $rent->pick_time = $pickTime;
        $rent->drop_time = $dropTime;
        $rent->price = getAmount($totalPrice);
        $rent->status = Status::ENABLE;
        $rent->save();

        session(['rent_id' => $rent->id]);

        return to_route('user.deposit.index');
    }

    public function showForm()
    {
        $pageTitle = 'Choisir une Assurance';

        // Optional: fetch rent from session
        $rent = session('rent_id') ? \App\Models\RentLog::find(session('rent_id')) : null;

        return view('template.vehicles.insurance', compact('pageTitle', 'rent'));
    }

    public function submitForm(Request $request)
    {
        $request->validate([
            'baby_seat' => 'required',
            'insurance' => 'required|boolean',
            'insurance_price' => 'required|numeric|min:0',
            'caution' => 'required|numeric|min:0',
        ]);

        $rent = session('rent_id') ? \App\Models\RentLog::find(session('rent_id')) : null;

        if (!$rent) {
            return back()->withErrors(['error' => 'No active booking found.']);
        }

        $rent->insurance = $request->insurance;
        $rent->insurance_price = $request->insurance_price;
        $rent->baby_seat = $request->baby_seat;
        $rent->caution = $request->caution;
        $rent->save();

        return to_route('user.deposit.index')->with('success', 'Insurance and baby seat options updated successfully!');
    }





}
