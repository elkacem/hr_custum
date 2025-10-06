<?php

namespace App\Http\Controllers;

use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\Rule;
use App\Constants\Status;
use Carbon\Carbon;
use App\Models\RentLog;
use App\Models\Vehicle;
use App\Models\Location;
class PublicBookingController extends Controller
{
    public function showIndex($id)
    {
//        dd('Public Booking Index', $id);
        $vehicle   = Vehicle::active()->where('id', $id)->firstOrFail();
        $locations = Location::active()->orderBy('name')->get();
        $pageTitle = 'Check Availability';

        return view('template.vehicles.public_booking_book', compact('vehicle', 'locations', 'pageTitle'));
    }

    public function checkAvailability(Request $request, $id)
    {

//        dd('Check Availability', $request->all(), $id);
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
                $vehicle = $alternativeVehicle;
            } else {
                $notify[] = ['error', __('no_vehicle_available')];
                return back()->withNotify($notify);
            }
        }

        $locations = Location::active()->orderBy('name')->get();
        $info       = json_decode(json_encode(getIpInfo()), true);
        $mobileCode = @implode(',', $info['code']);
        $countries  = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        $pageTitle = 'Vehicle Booking';

        if (auth()->check()) {
            $pickTime = Carbon::parse($request->pick_time);
            $dropTime = Carbon::parse($request->drop_time);

            $totalDays = ceil($pickTime->diffInDays($dropTime, false));

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

            return to_route('user.dossier.insurance');
        }

//        dd($dossier->id, $dossier->name, $request->pick_time, $request->drop_time, $request->pick_location, $request->drop_location);

        return redirect()->route('booking.guest.info', [
            'id' => $vehicle->id,
            'pageTitle'=> $pageTitle,
            'pick'     => $request->pick_time,
            'drop'     => $request->drop_time,
            'pickup'   => $request->pick_location,
            'dropoff'  => $request->drop_location,
        ]);

    }

    public function showGuestForm(Request $request, $id)
    {
        $vehicle = Vehicle::active()->where('id', $id)->firstOrFail();
        $locations = Location::active()->orderBy('name')->get();
        $countries  = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        return view('template.vehicles.public_booking', [
            'dossier'  => $vehicle,
            'locations'=> $locations,
            'pageTitle'=> 'Vehicle Booking',
            'pick'     => $request->pick,
            'drop'     => $request->drop,
            'pickup'   => $request->pickup,
            'dropoff'  => $request->dropoff,
            'countries'=> $countries,
        ]);
    }



    public function guestVehicleBooking($id, $slug)
    {
        $info       = json_decode(json_encode(getIpInfo()), true);
        $mobileCode = @implode(',', $info['code']);
        $countries  = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        if (!auth()->check()) {
            $pageTitle = 'Vehicle Booking';
            $vehicle = Vehicle::active()->where('id', $id)->firstOrFail();
            $locations = Location::active()->orderBy('name')->get();
            return view('template.vehicles.public_booking_book', compact('vehicle', 'pageTitle', 'locations', 'mobileCode', 'countries'));
        }

        $pageTitle = 'Vehicle Booking';
        $vehicle = Vehicle::active()->where('id', $id)->firstOrFail();
        $locations = Location::active()->orderBy('name')->get();

        return view('template.vehicles.public_booking', compact('vehicle', 'pageTitle', 'locations'));
    }


//    public function guestSubmit(Request $request, $id)
//    {
//        \Log::info('checkAvailability HIT', [
//            'method' => $request->method(),
//            'full_url' => $request->fullUrl(),
//            'inputs' => $request->all(),
//        ]);
//        if (!gs('registration')) {
//            return back()->withNotify([['error', 'Registration is currently disabled']]);
//        }
//
//        $agree = gs('agree') ? 'required' : 'nullable';
//
//        // Validation
//        $request->validate([
//            // Booking
//            'pick_location' => [
//                'required', 'integer',
//                Rule::exists('locations', 'id')->where(fn($query) => $query->where('status', Status::ENABLE))
//            ],
//            'drop_location' => [
//                'required', 'integer',
//                Rule::exists('locations', 'id')->where(fn($query) => $query->where('status', Status::ENABLE)),
//
//            ],
//            'pick_time' => 'required|date_format:Y-m-d h:i A|after_or_equal:today',
//            'drop_time' => 'required|date_format:Y-m-d h:i A|after_or_equal:' . $request->pick_time,
//
//            // User info
//            'firstname' => 'required|string|max:100',
//            'lastname' => 'required|string|max:100',
//            'email' => 'required|email|max:255|unique:users,email',
//            'username' => 'required|string|max:100|unique:users,username',
//            'password' => 'required|string|min:6|confirmed',
//            'country' => 'required|string|max:100',
//            'mobile' => 'required|string|max:30|unique:users,mobile',
//            'address' => 'nullable|string|max:255',
//            'state' => 'nullable|string|max:100',
//            'zip' => 'nullable|string|max:20',
//            'city' => 'nullable|string|max:100',
//            'agree' => $agree,
//        ], [
//            'drop_location.not_in' => 'Please choose a different drop-off location!',
//        ]);
//
//        // Parse dates
//        $pickTime = Carbon::parse($request->pick_time);
//        $dropTime = Carbon::parse($request->drop_time);
//
//        // Retrieve dossier
//        $dossier = Vehicle::active()->where('id', $id)->firstOrFail();
//
//        // Create guest user
//        $user = new User();
//        $user->firstname = $request->firstnamee;
//        $user->lastname = $request->lastname;
//        $user->email = $request->email;
//        $user->username = $request->username;
//        $user->password = Hash::make($request->password);
//        $user->dial_code = $request->mobile_code;
//        $user->country_code = $request->country_code;
//        $user->country_name = $request->country;
//        $user->mobile = $request->mobile;
//        $user->address = $request->address;
//        $user->state = $request->state;
//        $user->zip = $request->zip;
//        $user->city = $request->city;
//        $user->status = Status::ENABLE;
//        $user->profile_complete = Status::YES;
//        $user->ev = gs('ev') ? Status::NO : Status::YES;
//        $user->sv = gs('sv') ? Status::NO : Status::YES;
//        $user->ts = Status::DISABLE;
//        $user->tv = Status::ENABLE;
//        $user->ref_by = session('reference') ? User::where('username', session('reference'))->value('id') ?? 0 : 0;
//        $user->save();
//
//        Auth::login($user);
//
//
//        // Save booking
//        $totalDays = $pickTime->diffInDays($dropTime);
//        $totalPrice = $dossier->price * $totalDays;
//
//
//        $rent = new RentLog();
//        $rent->user_id = $user->id;
//        $rent->vehicle_id = $dossier->id;
//        $rent->pick_location = $request->pick_location;
//        $rent->drop_location = $request->drop_location;
//        $rent->pick_time = $pickTime;
//        $rent->drop_time = $dropTime;
//        $rent->price = getAmount($totalPrice);
//        $rent->status = Status::ENABLE;
//        $rent->save();
//
//        session(['rent_id' => $rent->id]);
//
////        return to_route('user.deposit.index');
//        return to_route('user.dossier.insurance');
//    }


    public function guestSubmit(Request $request, $id)
    {
        \Log::info('Guest Booking Submission', [
            'method' => $request->method(),
            'full_url' => $request->fullUrl(),
            'inputs' => $request->all(),
        ]);

        if (!gs('registration')) {
            return back()->withNotify([['error', 'Registration is currently disabled']]);
        }

        $agree = gs('agree') ? 'required' : 'nullable';

        $user = User::where('email', $request->email)->first();


        // Validate input
        $request->validate([
            'pick_location' => [
                'required', 'integer',
                Rule::exists('locations', 'id')->where(fn($q) => $q->where('status', Status::ENABLE))
            ],
            'drop_location' => [
                'required', 'integer',
                Rule::exists('locations', 'id')->where(fn($q) => $q->where('status', Status::ENABLE)),
            ],
            'pick_time' => 'required|date_format:Y-m-d h:i A|after_or_equal:today',
            'drop_time' => 'required|date_format:Y-m-d h:i A|after_or_equal:' . $request->pick_time,

            'firstname' => 'required|string|max:100',
            'lastname' => 'required|string|max:100',
            'email' => 'required|email|max:255',
//            'username' => 'required|string|max:100|unique:users,username',
            'username' => [
                'required', 'string', 'max:100',
                Rule::unique('users', 'username')->ignore(optional($user)->id),
            ],
            'password' => 'required|string|min:6|confirmed',
            'country' => 'required|string|max:100',
            'mobile' => 'required|string|max:30',
            'address' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:100',
            'zip' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'agree' => $agree,
        ]);

        // Parse dates
        $pickTime = Carbon::parse($request->pick_time);
        $dropTime = Carbon::parse($request->drop_time);

        // Get dossier
        $vehicle = Vehicle::active()->where('id', $id)->firstOrFail();


        if ($user) {
            // Update existing user
            $user->firstname     = $request->firstname;
            $user->lastname      = $request->lastname;
            $user->username      = $request->username; // Consider allowing change only if unique
            $user->password      = Hash::make($request->password);
            $user->dial_code     = $request->mobile_code;
            $user->country_code  = $request->country_code;
            $user->country_name  = $request->country;
            $user->mobile        = $request->mobile;
            $user->address       = $request->address;
            $user->state         = $request->state;
            $user->zip           = $request->zip;
            $user->city          = $request->city;
            $user->profile_complete = Status::YES;
            $user->save();
        } else {
            // Create new user
            $user = new User();
            $user->firstname     = $request->firstname;
            $user->lastname      = $request->lastname;
            $user->email         = $request->email;
            $user->username      = $request->username;
            $user->password      = Hash::make($request->password);
            $user->dial_code     = $request->mobile_code;
            $user->country_code  = $request->country_code;
            $user->country_name  = $request->country;
            $user->mobile        = $request->mobile;
            $user->address       = $request->address;
            $user->state         = $request->state;
            $user->zip           = $request->zip;
            $user->city          = $request->city;
            $user->status        = Status::ENABLE;
            $user->profile_complete = Status::YES;
            $user->ev            = gs('ev') ? Status::NO : Status::YES;
            $user->sv            = gs('sv') ? Status::NO : Status::YES;
            $user->ts            = Status::DISABLE;
            $user->tv            = Status::ENABLE;
            $user->ref_by        = session('reference') ? User::where('username', session('reference'))->value('id') ?? 0 : 0;
            $user->save();
        }

        // Log in the user
        Auth::login($user);

        // Calculate price
//        $totalDays = $pickTime->diffInDays($dropTime);
        $totalDays = max(1, ceil($pickTime->diffInDays($dropTime, false)));
        $totalPrice = getAmount($vehicle->price * $totalDays);

        // Save booking
        $rent = new RentLog();
        $rent->user_id       = $user->id;
        $rent->vehicle_id    = $vehicle->id;
        $rent->pick_location = $request->pick_location;
        $rent->drop_location = $request->drop_location;
        $rent->pick_time     = $pickTime;
        $rent->drop_time     = $dropTime;
        $rent->price         = $totalPrice;
        $rent->status        = Status::ENABLE;

        // Optional flight info
        if ($request->filled('flight_number') || $request->filled('provenance') || $request->filled('airline_company')) {
            $rent->extra_data = [
                'flight_number' => $request->flight_number,
                'provenance' => $request->provenance,
                'airline_company' => $request->airline_company,
            ];
        }

        $rent->save();
        session(['rent_id' => $rent->id]);

        return to_route('user.dossier.insurance');
    }


}
