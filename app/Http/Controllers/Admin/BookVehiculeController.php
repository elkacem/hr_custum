<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Deposit;
use App\Models\Location;
use App\Models\Seater;
use App\Models\Vehicle;
use App\Models\RentLog;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BookVehiculeController extends Controller
{
    public function vehicule()
    {
        $pageTitle = 'Book Vehicle';

        $brands = Brand::orderBy('name')->get(['id', 'name']);
        $seaters = Seater::orderBy('number')->get(['id', 'number']);
        $transmissions = Vehicle::whereNotNull('transmission')
            ->select('transmission')->distinct()->orderBy('transmission')->pluck('transmission');
        $fuelTypes = Vehicle::whereNotNull('fuel_type')
            ->select('fuel_type')->distinct()->orderBy('fuel_type')->pluck('fuel_type');

        // Initial list (all vehicles)
        $vehicles = Vehicle::active()->with(['brand', 'seater'])->orderBy('name')->get();
        $locations = Location::all();
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        // Build resources server-side (no Blade bracket headaches)
        $initialResources = $vehicles->map(function ($v) {
            return [
                'id' => (string)$v->id,
                'title' => trim(($v->brand->name ?? '') . ' ' . $v->name . ($v->model ? ' (' . $v->model . ')' : '')),
                'extendedProps' => [
                    'seats' => optional($v->seater)->number,
                    'trans' => $v->transmission,
                    'fuel' => $v->fuel_type,
                    'price' => $v->price,
                ],
            ];
        })->values()->all();

        return view('admin.booking.book', compact(
            'pageTitle', 'vehicles', 'brands', 'seaters', 'transmissions', 'fuelTypes', 'initialResources', 'countries', 'locations'));
    }

    public function agendaResources(Request $request)
    {
        $vehicles = Vehicle::active()->query()
            ->when($request->vehicle_model, fn($q) => $q->where('model', $request->vehicle_model))
            ->with(['brand', 'seater'])
            ->orderBy('name')
            ->get();

        return $vehicles->map(fn($v) => [
            'id' => (string)$v->id,
            'title' => "{$v->brand->name} {$v->name} ({$v->model})",
            'extendedProps' => [
                'seats' => optional($v->seater)->number,
                'transmission' => $v->transmission,
                'fuel' => $v->fuel_type,
            ],
        ]);
    }

    private function colorForVehicle($id)
    {
        $colors = [
            '#ef4444', '#3b82f6', '#10b981',
            '#f59e0b', '#8b5cf6', '#ec4899', '#14b8a6',
        ];
        return $colors[$id % count($colors)];
    }

    public function agendaEvents(Request $request)
    {
//        dd($request->all());
        $tz = config('app.timezone', 'UTC');

        $start = $request->query('start')
            ? Carbon::parse($request->query('start'))->timezone($tz)
            : now($tz)->startOfDay();

        $end = $request->query('end')
            ? Carbon::parse($request->query('end'))->timezone($tz)
            : now($tz)->addDay()->endOfDay();

        // 🟢 CASE 1: Single Vehicle
        if ($request->vehicle_id) {
            $vehicles = Vehicle::active()->where('id', $request->vehicle_id)->get();
            $vehicleIds = $vehicles->pluck('id');
            $totalVehicles = 1;

            $deposits = Deposit::where('status', 1)
                ->whereHas('rentLog', function ($q) use ($vehicleIds, $start, $end) {
                    $q->whereIn('vehicle_id', $vehicleIds)
                        ->where('pick_time', '<', $end)
                        ->where('drop_time', '>', $start);
                })
                ->with(['rentLog.dossier', 'rentLog.user'])
                ->get();

            $logs = $deposits->pluck('rentLog');

            // collect time points
            $timePoints = [];
            foreach ($logs as $log) {
                $timePoints[] = ['time' => $log->pick_time, 'type' => 'start'];
                $timePoints[] = ['time' => $log->drop_time, 'type' => 'end'];
            }
            usort($timePoints, fn($a, $b) => strtotime($a['time']) <=> strtotime($b['time']));

            $events = [];
            $active = 0;
            $last = null;

            foreach ($timePoints as $tp) {
                $time = Carbon::parse($tp['time']);

                if ($last !== null && $time->gt($last)) {
                    $booked = $logs->filter(fn($l) => max(strtotime($l->pick_time), $last->timestamp) <
                        min(strtotime($l->drop_time), $time->timestamp)
                    );

                    $bookings = $booked->map(fn($l) => [
                        'plate' => $l->vehicle->matriculation ?? 'No Plate',
                        'brand' => $l->vehicle->brand->name ?? '',
                        'model' => $l->vehicle->model,
                        'pickup' => Carbon::parse($l->pick_time)->format('d/m/Y H:i'),
                        'drop' => Carbon::parse($l->drop_time)->format('d/m/Y H:i'),
                        'customer' => $l->user->firstname . ' ' . $l->user->lastname,
                    ])->values();

                    if ($active === 0) {
                        // free
                        $events[] = [
                            'start' => $last->format('Y-m-d\TH:i:s'),
                            'end' => $time->format('Y-m-d\TH:i:s'),
                            'display' => 'background',
                            'backgroundColor' => '#10b981',
                            'classNames' => ['slot-free'],
                            'extendedProps' => ['bookings' => [], 'free' => []],
                        ];
                    } else {
                        // fully booked (since only 1 dossier)
                        $events[] = [
                            'start' => $last->format('Y-m-d\TH:i:s'),
                            'end' => $time->format('Y-m-d\TH:i:s'),
                            'display' => 'background',
                            'backgroundColor' => '#ef4444',
                            'classNames' => ['slot-full'],
                            'extendedProps' => ['bookings' => $bookings, 'free' => []],
                        ];
                        // add click layer for details
                        $events[] = [
                            'start' => $last->format('Y-m-d\TH:i:s'),
                            'end' => $time->format('Y-m-d\TH:i:s'),
                            'display' => 'block',
                            'backgroundColor' => 'transparent',
                            'borderColor' => 'transparent',
                            'classNames' => ['click-layer', 'slot-full'],
                            'extendedProps' => ['bookings' => $bookings, 'free' => []],
                        ];
                    }
                }

                $active += $tp['type'] === 'start' ? 1 : -1;
                $last = $time;
            }

            return response()->json($events);
        }


        // 🟠 CASE 2: Model View
        if ($request->vehicle_model) {
            $vehicles = Vehicle::active()->where('model', $request->vehicle_model)->get();
            $vehicleIds = $vehicles->pluck('id');
            $totalVehicles = $vehicles->count();

            if ($totalVehicles === 0) {
                return response()->json([]);
            }

            $deposits = Deposit::where('status', 1)
                ->whereHas('rentLog', function ($q) use ($vehicleIds, $start, $end) {
                    $q->whereIn('vehicle_id', $vehicleIds)
                        ->where(function ($q2) use ($start, $end) {
                            $q2->whereBetween('pick_time', [$start, $end])
                                ->orWhereBetween('drop_time', [$start, $end])
                                ->orWhere(fn($q3) => $q3->where('pick_time', '<=', $start)->where('drop_time', '>=', $end));
                        });
                })
                ->with('rentLog.dossier')
                ->get();

            $logs = $deposits->pluck('rentLog');

            // collect all time points
            $timePoints = [];
            foreach ($logs as $log) {
                $timePoints[] = ['time' => $log->pick_time, 'type' => 'start'];
                $timePoints[] = ['time' => $log->drop_time, 'type' => 'end'];
            }
            usort($timePoints, fn($a, $b) => strtotime($a['time']) <=> strtotime($b['time']));

            $events = [];
            $active = 0;
            $last = null;

            foreach ($timePoints as $tp) {
                $time = Carbon::parse($tp['time']);

                if ($last !== null && $time->gt($last)) {
                    $booked = $logs->filter(fn($l) => max(strtotime($l->pick_time), $last->timestamp) <
                        min(strtotime($l->drop_time), $time->timestamp)
                    );

                    $bookings = $booked->map(fn($l) => [
                        'plate' => $l->vehicle->matriculation ?? 'No Plate',
                        'brand' => $l->vehicle->brand->name ?? '',
                        'model' => $l->vehicle->model,
                        'pickup' => Carbon::parse($l->pick_time)->format('d/m/Y H:i'),
                        'drop' => Carbon::parse($l->drop_time)->format('d/m/Y H:i'),
                        'customer' => $l->user->firstname . ' ' . $l->user->lastname,
                    ])->values();

                    $freeVehicles = $vehicles
                        ->whereNotIn('id', $booked->pluck('vehicle_id'))
                        ->map(fn($v) => [
                            'plate' => $v->matriculation ?? 'No Plate',
                            'brand' => $v->brand->name ?? '',
                            'model' => $v->model,
                        ])->values();

                    if ($active === 0) {
                        // 🟢 FREE
                        $events[] = [
                            'start' => $last->format('Y-m-d\TH:i:s'),
                            'end' => $time->format('Y-m-d\TH:i:s'),
                            'display' => 'background',
                            'backgroundColor' => '#10b981',
                            'title' => "$totalVehicles free",
                            'classNames' => ['slot-free'],
                            'extendedProps' => [
                                'bookings' => [], // 👈 always there
                                'free' => $freeVehicles,
                            ]
                        ];
                    } elseif ($active >= $totalVehicles) {
                        // 🔴 FULLY BOOKED
                        $events[] = [
                            'start' => $last->format('Y-m-d\TH:i:s'),
                            'end' => $time->format('Y-m-d\TH:i:s'),
                            'display' => 'background',
                            'backgroundColor' => '#ef4444',
                            'title' => "All $totalVehicles booked",
                            'classNames' => ['slot-full'],
                            'extendedProps' => [
                                'bookings' => $bookings,
                                'free' => [], // 👈 always there
                            ],
                        ];
                        $events[] = [ // overlay
                            'start' => $last->format('Y-m-d\TH:i:s'),
                            'end' => $time->format('Y-m-d\TH:i:s'),
                            'display' => 'block',
                            'backgroundColor' => 'transparent',
                            'borderColor' => 'transparent',
                            'classNames' => ['click-layer', 'slot-full'],
                            'extendedProps' => [
                                'bookings' => $bookings,
                                'free' => [],
                            ]
                        ];
                    } else {

                        // 🟠 PARTIAL (orange shading only, no overlay click event)
                        $events[] = [
                            'start' => $last->format('Y-m-d\TH:i:s'),
                            'end' => $time->format('Y-m-d\TH:i:s'),
                            'display' => 'background',
                            'backgroundColor' => '#f59e0b',
                            'title' => "$active booked, " . ($totalVehicles - $active) . " free",
                            'classNames' => ['slot-partial-bg'],
                            'extendedProps' => [
                                'bookings' => $bookings,
                                'free' => $freeVehicles,
                            ]
                        ];


                    }
                }

                // update active count
                $active += $tp['type'] === 'start' ? 1 : -1;
                $last = $time;
            }

            return response()->json($events);
        }
    }


    public function book(Request $request)
    {
//        dd($request->all());
        $trx = getTrx();

        $request->validate([
            'pickup_date' => 'required|date_format:Y-m-d H:i',
            'return_date' => 'required|date_format:Y-m-d H:i|after:pickup_date',
            'email' => 'nullable|email',
            'insurance_type' => 'nullable|boolean',
            'baby_seat' => 'nullable|integer|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'carburant_fee' => 'nullable|numeric|min:0',   // ✅ add this
        ]);

        $pickup = Carbon::createFromFormat('Y-m-d H:i', $request->pickup_date);
        $return = Carbon::createFromFormat('Y-m-d H:i', $request->return_date);

        // ----------------------------------------
        // 1️⃣ VEHICLE IS SELECTED DIRECTLY
        // ----------------------------------------
        if ($request->vehicle_id) {
            $vehicleId = $request->vehicle_id;
        }

        // ----------------------------------------
        // 2️⃣ VEHICLE SELECTED BY MODEL
        // ----------------------------------------
        elseif ($request->vehicle_model) {
//             Get busy vehicles in that model for the interval
            $busyVehicleIds = RentLog::whereHas('deposit', fn($q) => $q->where('status', 1))
                ->whereHas('dossier', fn($q) => $q->where('model', $request->vehicle_model))
                ->where(function ($q) use ($pickup, $return) {
                    // strict overlap (borders allowed)
                    $q->where('pick_time', '<', $return)
                        ->where('drop_time', '>', $pickup);
                })
                ->pluck('vehicle_id');

            $busyVehicleIds = RentLog::whereHas('deposit', function ($q) {
                $q->where('status', 1);
            })
                ->whereHas('dossier', fn($q) => $q->where('model', $request->vehicle_model))
                ->where(function ($q) use ($pickup, $return) {
                    // ✅ Strict overlap (borders allowed)
                    $q->where('pick_time', '<', $return)
                        ->where('drop_time', '>', $pickup);
                })
                ->pluck('vehicle_id');

            // Pick first free dossier
            $vehicle = Vehicle::where('model', $request->vehicle_model)
                ->where('status', Status::ENABLE)
                ->whereNotIn('id', $busyVehicleIds)
                ->first();

            $vehicleId = $vehicle?->id;
        }

        // ----------------------------------------
        // 3️⃣ NO VEHICLE FOUND
        // ----------------------------------------
        if (empty($vehicleId)) {
            return back()->withErrors(['booking' => 'No available dossier found for the selected model or dates.']);
        }

        // Always resolve the chosen dossier
        $vehicle = Vehicle::findOrFail($vehicleId);

        // ----------------------------------------
        // 4️⃣ FINAL OVERLAP CHECK (STRICT, ALLOWS BORDERS)
        // ----------------------------------------
        $overlap = Deposit::where('status', 1)
            ->whereHas('rentLog', function ($q) use ($vehicleId, $pickup, $return) {
                $q->where('vehicle_id', $vehicleId)
                    ->where(function ($q2) use ($pickup, $return) {
                        $q2->where('pick_time', '<', $return)
                            ->where('drop_time', '>', $pickup);
                    });
            })
            ->exists();

        if ($overlap) {
            return back()->withErrors(['booking' => 'This dossier is already reserved in that interval.']);
        }

        // ----------------------------------------
// 5️⃣ USER (find, update, or create)`
// ----------------------------------------
        $user = User::where('email', $request->email)
            ->orWhere('mobile', $request->mobile)
            ->first();

        if ($user) {
            // ✅ User already exists
            if ($request->guest_type == 0) { // Walk-in selected
                // Suggest to switch to existing (frontend alert will handle UX)
                // but still update the user if they continue
                $user->firstname = $request->firstname ?? $user->firstname;
                $user->lastname = $request->lastname ?? $user->lastname;
                $user->username = $request->username ?? $user->username;
                $user->dial_code = $request->mobile_code ?? $user->dial_code;
                $user->country_code = $request->country_code ?? $user->country_code;
                $user->country_name = $request->country ?? $user->country_name;
                $user->mobile = $request->mobile ?? $user->mobile;
                $user->address = $request->address ?? $user->address;
                $user->state = $request->state ?? $user->state;
                $user->zip = $request->zip ?? $user->zip;
                $user->city = $request->city ?? $user->city;
                $user->save();
            }
        } else {
            // 🆕 No user found → create new
            $randomPassword = Str::random(10);

            $user = new User();
            $user->firstname = $request->firstname;
            $user->lastname = $request->lastname;
            $user->email = $request->email;
            $user->username = $request->username;
            $user->password = Hash::make($randomPassword);
            $user->dial_code = $request->mobile_code;
            $user->country_code = $request->country_code;
            $user->country_name = $request->country;
            $user->mobile = $request->mobile;
            $user->address = $request->address;
            $user->state = $request->state;
            $user->zip = $request->zip;
            $user->city = $request->city;
            $user->status = Status::ENABLE;
            $user->profile_complete = Status::YES;
            $user->ev = gs('ev') ? Status::NO : Status::YES;
            $user->sv = gs('sv') ? Status::NO : Status::YES;
            $user->ts = Status::DISABLE;
            $user->tv = Status::ENABLE;
            $user->ref_by = session('reference') ? User::where('username', session('reference'))->value('id') ?? 0 : 0;
            $user->save();
        }

        // ----------------------------------------
// 6️⃣ PRICE CALCULATION
// ----------------------------------------
        $days = max(1, ceil($pickup->diffInHours($return) / 24));

// insurance
        $insurance_price = $request->insurance_type ? 6000 : 0;
        $insurance_total = $insurance_price * $days;

// carburant/cleaning
        $carburant_fee = $request->carburant_fee ?? 3000;

// base rental
        $subtotal = $days * $vehicle->price;

// tax (use global setting)
        $taxPercent = gs()->tax ?? 0;
        $tax = ($subtotal + $insurance_total + $carburant_fee) * $taxPercent / 100;

// grand total
        $amount = $subtotal + $insurance_total + $carburant_fee + $tax;

// amount already paid
        $paid = min($request->paid_amount ?? 0, $amount);

// reste à payer
        $remaining = max(0, $amount - $paid);

        // ----------------------------------------
        // 7️⃣ CREATE RENT LOG
        // ----------------------------------------
        $rent = new RentLog();
        $rent->user_id = $user->id;
        $rent->vehicle_id = $vehicle->id;
        $rent->pick_location = $request->pick_location;
        $rent->drop_location = $request->drop_location;
        $rent->pick_time = $pickup;
        $rent->drop_time = $return;
        $rent->trx = $trx;
        $rent->baby_seat = $request->baby_seat;
        $rent->insurance = (bool)$request->insurance_type;
        $rent->insurance_price = $insurance_total;
        $rent->caution = $request->caution_amount;
        $rent->price = $subtotal;
        $rent->status = Status::ENABLE;
        $rent->save();

        // 🔹 Ajout des conducteurs supplémentaires
        if ($request->has('drivers')) {
            foreach ($request->drivers as $driverData) {
                if (!empty($driverData['name']) && !empty($driverData['license_number'])) {
                    $rent->drivers()->create([
                        'name'           => $driverData['name'],
                        'license_number' => $driverData['license_number'],
                        'license_date'   => $driverData['license_date'] ?? null,
                        'license_place'  => $driverData['license_place'] ?? null,
                    ]);
                }
            }
        }

        // ----------------------------------------
        // 8️⃣ CREATE DEPOSIT
        // ----------------------------------------
        $deposit = new Deposit();
        $deposit->user_id = $user->id;
        $deposit->rent_log_id = $rent->id;
        $deposit->method_code = 0;
        $deposit->amount = $amount;
        $deposit->method_currency = gs()->cur_text ?? 'USD';
        $deposit->charge = 0;
        $deposit->rate = 1;
        $deposit->final_amount = $paid;
        $deposit->rest_amount = $remaining;
        $deposit->trx = $trx;
        $deposit->status = 1;

        $filename = 'invoice_' . $trx . '.pdf';
        $relativePath = 'invoices/' . $filename;

//        dd($deposit);

        $pdf = Pdf::loadView('invoice.facture', [
            'deposit' => $deposit,
            'user' => $deposit->user,
            'description' => 'Payment to ' . gs()->site_name,
        ]);

        $pdfContent = $pdf->output();
        Storage::disk('private')->put($relativePath, $pdfContent);

        $deposit->invoice = $relativePath;
        $deposit->save();

        $rent->load(['pickUpLocation', 'dropLocation']);

        // 9️⃣ Génération du contrat
        $contractFilename = 'contract_' . $trx . '.pdf';
        $contractPath = 'contracts/' . $contractFilename;

        $admin = Auth::guard('admin')->user();


        $contractPdf = Pdf::loadView('invoice.contract', [
            'deposit' => $deposit,
            'rent' => $rent,
            'user' => $user,
            'dossier' => $vehicle,
            'subtotal' => $subtotal,
            'insurance' => $insurance_total,
            'carburant' => $carburant_fee,
            'tax' => $tax,
            'amount' => $amount,
            'paid' => $paid,
            'remaining' => $remaining,
            'admin' => $admin, // 👈 on envoie l’admin
            'baby_seat' => $rent->baby_seat,   // ✅ add this

        ]);


        Storage::disk('private')->put($contractPath, $contractPdf->output());

        // Sauvegarde du chemin
        $deposit->contract = $contractPath;
        $deposit->save();

        $user = $deposit->user;
        notify($user, 'DEPOSIT_COMPLETE', [
            'method_name' => $deposit->methodName(),
            'method_currency' => $deposit->method_currency,
            'method_amount' => showAmount($deposit->final_amount, currencyFormat: false),
            'amount' => showAmount($deposit->amount, currencyFormat: false),
            'charge' => showAmount($deposit->charge, currencyFormat: false),
            'rate' => showAmount($deposit->rate, currencyFormat: false),
            'trx' => $deposit->trx,
            'post_balance' => showAmount($user->balance),
        ], ['email'], true, null, [
            [
                'data' => $pdfContent,
                'name' => $filename,
                'mime' => 'application/pdf',
            ]
        ]);

        return back()->with('success', 'Booking created successfully for ' . $user->email);
    }

    public function check(Request $request)
    {
        $request->validate([
            'email' => 'nullable|email',
            'mobile' => 'nullable'
        ]);

        $guest = User::query()
            ->when($request->email, fn($q) => $q->where('email', $request->email))
            ->when($request->mobile, fn($q) => $q->orWhere('mobile', $request->mobile))
            ->first();

        if ($guest) {
            return response()->json([
                'exists' => true,
                'data' => $guest
            ]);
        }

        return response()->json(['exists' => false]);
    }

    public function edit($id)
    {
        $pageTitle = 'Edit Book';

        $deposit = Deposit::with(['rentLog.dossier.brand', 'rentLog.dossier.seater', 'user'])->findOrFail($id);

        $brands = Brand::orderBy('name')->get();
        $seaters = Seater::orderBy('number')->get();
        $vehicles = Vehicle::active()->with(['brand', 'seater'])->orderBy('name')->get();
        $locations = Location::all();
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        $confirmedBookings = Deposit::where('status', 1)
            ->whereHas('rentLog', fn($q) => $q->where('vehicle_id', $deposit->rentLog->vehicle_id))
            ->with(['rentLog.dossier', 'user'])
            ->get()
            ->map(fn($d) => [
                'id' => 'booking-' . $d->id,
                'start' => $d->rentLog->pick_time,
                'end' => $d->rentLog->drop_time,
                'title' => $d->user->firstname . ' ' . $d->user->lastname,
                'classNames' => $d->id == $deposit->id ? ['selected-slot'] : ['slot-full'],
            ]);

        $currentBooking = [
            'id' => 'current-booking',
            'start' => $deposit->rentLog->pick_time,
            'end' => $deposit->rentLog->drop_time,
            'title' => 'Current Booking',
            'classNames' => ['selected-slot'],
        ];

        return view('admin.booking.edit', compact('pageTitle',
            'deposit', 'vehicles', 'brands', 'seaters', 'locations', 'countries',
            'confirmedBookings', 'currentBooking'
        ));
    }

    public function update(Request $request, $id)
    {
//        dd($request->all());
        $deposit = Deposit::with(['rentLog', 'user'])->findOrFail($id);
        $rent = $deposit->rentLog;
        $vehicle = Vehicle::findOrFail($rent->vehicle_id);

        $request->validate([
            'pickup_date' => 'required|date_format:Y-m-d H:i',
            'return_date' => 'required|date_format:Y-m-d H:i|after:pickup_date',
            'insurance_type' => 'nullable|boolean',
            'baby_seat' => 'nullable|integer|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'carburant_fee' => 'nullable|numeric|min:0',
            'caution_amount' => 'nullable|numeric|min:0',
        ]);

        $pickup = Carbon::createFromFormat('Y-m-d H:i', $request->pickup_date);
        $return = Carbon::createFromFormat('Y-m-d H:i', $request->return_date);

        // 🔒 Overlap check
        $overlap = Deposit::where('status', Status::ENABLE)
            ->where('id', '!=', $deposit->id)
            ->whereHas('rentLog', function ($q) use ($vehicle, $pickup, $return) {
                $q->where('vehicle_id', $vehicle->id)
                    ->where(function ($q2) use ($pickup, $return) {
                        $q2->where('pick_time', '<', $return)
                            ->where('drop_time', '>', $pickup);
                    });
            })->exists();

        if ($overlap) {
            return back()->withErrors(['booking' => '⚠️ Ce véhicule est déjà réservé sur cette période.']);
        }

        // 🧮 Recalcul
        $days = max(1, ceil($pickup->diffInHours($return) / 24));
        $insurance_price = $request->insurance_type ? 6000 : 0;
        $insurance_total = $insurance_price * $days;
        $carburant_fee = $request->carburant_fee ?? 3000;
        $subtotal = $days * $vehicle->price;
        $taxPercent = gs()->tax ?? 0;
        $tax = ($subtotal + $insurance_total + $carburant_fee) * $taxPercent / 100;
        $amount = $subtotal + $insurance_total + $carburant_fee + $tax;
        $paid = min($request->paid_amount ?? 0, $amount);
        $remaining = max(0, $amount - $paid);

        // 🔄 Update RentLog
        $rent->pick_time = $pickup;
        $rent->drop_time = $return;
        $rent->pick_location = $request->pick_location;
        $rent->drop_location = $request->drop_location;
        $rent->baby_seat = $request->baby_seat;
        $rent->insurance = (bool)$request->insurance_type;
        $rent->insurance_price = $insurance_total;
        $rent->caution = $request->caution_amount;
        $rent->price = $subtotal;
        $rent->save();

        // 🔄 Update Drivers
        $rent->drivers()->delete(); // on supprime les anciens
        if ($request->has('drivers')) {
            foreach ($request->drivers as $driverData) {
                if (!empty($driverData['name']) && !empty($driverData['license_number'])) {
                    $rent->drivers()->create([
                        'name'           => $driverData['name'],
                        'license_number' => $driverData['license_number'],
                        'license_date'   => $driverData['license_date'] ?? null,
                        'license_place'  => $driverData['license_place'] ?? null,
                    ]);
                }
            }
        }

        // 🔄 Update Deposit
        $deposit->amount = $amount;
        $deposit->final_amount = $paid;
        $deposit->rest_amount = $remaining;

        // 🗑️ Supprimer ancienne facture si existe
        if ($deposit->invoice && Storage::disk('private')->exists($deposit->invoice)) {
            Storage::disk('private')->delete($deposit->invoice);
        }
        if ($deposit->contract && Storage::disk('private')->exists($deposit->contract)) {
            Storage::disk('private')->delete($deposit->contract);
        }

        // 📄 Générer nouvelle facture
        $filename = 'invoice_' . $deposit->trx . '.pdf';
        $relativePath = 'invoices/' . $filename;
        $pdf = Pdf::loadView('invoice.facture', [
            'deposit' => $deposit,
            'user' => $deposit->user,
            'description' => 'Payment to ' . gs()->site_name,
        ]);
        Storage::disk('private')->put($relativePath, $pdf->output());
        $deposit->invoice = $relativePath;

        // 📄 Générer nouveau contrat
        $contractFilename = 'contract_' . $deposit->trx . '.pdf';
        $contractPath = 'contracts/' . $contractFilename;
        $admin = Auth::guard('admin')->user();
        $contractPdf = Pdf::loadView('invoice.contract', [
            'deposit' => $deposit,
            'rent' => $rent,
            'user' => $deposit->user,
            'dossier' => $vehicle,
            'subtotal' => $subtotal,
            'insurance' => $insurance_total,
            'carburant' => $carburant_fee,
            'tax' => $tax,
            'amount' => $amount,
            'paid' => $paid,
            'remaining' => $remaining,
            'admin' => $admin,
            'baby_seat' => $rent->baby_seat,   // ✅ add this

        ]);
        Storage::disk('private')->put($contractPath, $contractPdf->output());
        $deposit->contract = $contractPath;

        $deposit->save();

        return redirect()->route('admin.deposit.list')->with('success', 'Réservation mise à jour ✅');
    }

    public function delete($id)
    {
        $deposit = Deposit::with('rentLog')->findOrFail($id);

        // 🗑️ Supprimer fichiers liés (facture + contrat)
        if ($deposit->invoice && Storage::disk('private')->exists($deposit->invoice)) {
            Storage::disk('private')->delete($deposit->invoice);
        }
        if ($deposit->contract && Storage::disk('private')->exists($deposit->contract)) {
            Storage::disk('private')->delete($deposit->contract);
        }

        // 🗑️ Supprimer RentLog associé
        if ($deposit->rentLog) {
            $deposit->rentLog->delete();
        }

        // 🗑️ Supprimer le dépôt
        $deposit->delete();

        return redirect()->route('admin.deposit.list')
            ->with('success', 'Réservation supprimée avec succès ✅');
    }


}
