@extends('admin.layouts.app')

@section('panel')
    {{-- Filters --}}

    <div class="card shadow-sm border-0">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="la la-search"></i> @lang('Find a Vehicle')</h5>
        </div>
        <div class="card-body">
            <form class="formVehicleSearch row g-3 align-items-end" method="GET" onsubmit="return false;">
                @csrf

                {{-- Pickup --}}
                <div class="col-md-6">
                    <label for="pick-point" class="form-label fw-semibold">
                        <i class="las la-map-marker-alt text-primary"></i> @lang('Pick Up Point')
                    </label>
                    <select name="pick_location" id="pick-point" class="form-select select2" required>
                        @foreach ($locations as $location)
                            <option value="{{ $location->id }}">{{ @$location->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Drop --}}
                <div class="col-md-6">
                    <label for="drop-point" class="form-label fw-semibold">
                        <i class="las la-map-marker text-danger"></i> @lang('Drop Off Point')
                    </label>
                    <select name="drop_location" id="drop-point" class="form-select select2" required>
                        @foreach ($locations as $location)
                            <option value="{{ $location->id }}">{{ @$location->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Vehicle Model --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        <i class="las la-car text-success"></i> @lang('Vehicle Model')
                    </label>
                    <select class="form-select select2-basic" name="vehicle_model">
                        <option value="">@lang('Any')</option>
                        @foreach($vehicles->groupBy('model') as $model => $list)
                            <option value="{{ $model }}" data-price="{{ $list->first()->price }}">
                                {{ $model }} ({{ $list->count() }} units)
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Specific Vehicle --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        <i class="las la-id-card text-warning"></i> @lang('Specific Vehicle')
                    </label>
                    <select class="form-select select2-basic" name="vehicle_id" id="vehicleSelect">
                        <option value="">@lang('Any')</option>
                        @foreach($vehicles as $v)
                            <option
                                value="{{ $v->id }}"
                                data-model="{{ $v->model }}"
                                data-price="{{ $v->price }}">
                                {{ $v->brand->name }} - {{ $v->model }} ({{ $v->matriculation ?? 'No Plate' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Pickup/Return Date --}}
                <div class="col-md-8">
                    <label class="form-label fw-semibold">
                        <i class="las la-calendar text-info"></i> @lang('Pickup - Return Date')
                    </label>
                    <input class="bookingDatePicker form-control" name="date" placeholder="@lang('Select Date')" required>
                    <input type="hidden" name="pickup_date">
                    <input type="hidden" name="return_date">
                </div>

                {{-- Search Button --}}
                <div class="col-md-4 d-grid">
                    <button class="btn btn-primary btn-lg search mt-3 mt-md-0" type="submit">
                        <i class="la la-search"></i> @lang('Search Vehicle')
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- Agenda --}}
    <div class="card mt-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <span class="legend-dot" style="background:#ef4444"></span> @lang('Reserved')
                <span class="legend-dot ms-3" style="background:#10b981"></span> @lang('Available')
                <span class="legend-dot ms-3" style="background:#3b82f6"></span> @lang('Selected')
            </div>
            <div class="btn-group">
                <button class="btn btn-sm btn-outline-secondary" id="zoom15">15m</button>
                <button class="btn btn-sm btn-outline-secondary active" id="zoom30">30m</button>
                <button class="btn btn-sm btn-outline-secondary" id="zoom60">60m</button>
            </div>
        </div>
        <div class="card-body">
            <div id="vehicleCalendar"></div>
        </div>
    </div>

    {{-- Booking Wrapper --}}
    <div class="row booking-wrapper mt-3">
        {{-- LEFT: Guest Info --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">@lang('Guest Information')</h5>
                </div>
                <div class="card-body">
                    <form class="booking-form" method="POST" action="{{ route('admin.booking.book') }}">
                        @csrf
                        <input type="hidden" name="pickup_date">
                        <input type="hidden" name="return_date">
                        <input type="hidden" name="pick_location">
                        <input type="hidden" name="drop_location">
                        <input type="hidden" name="vehicle_model">
                        <input type="hidden" name="caution_amount" class="cautionHidden">
                        <input type="hidden" name="paid_amount" class="paidHidden">
                        <input type="hidden" name="vehicle_id" class="vehicleIdHidden">
                        <input type="hidden" name="carburant_fee" class="carburantHidden">



                        <div class="row g-3">
                            {{-- Guest Type --}}
                            <div class="col-md-6">
                                <select class="form-control" name="guest_type" id="guestType">
                                    <option selected value="0">@lang('Walk-In Guest')</option>
                                    <option value="1">@lang('Existing Guest')</option>
                                </select>
                            </div>

                            {{-- Shared --}}
                            <div class="col-md-6">
                                <input type="email" class="form-control" placeholder="@lang('Email')" name="email" >
                            </div>

                            <div class="col-md-6 guestInputDiv">
                                <input type="text" name="firstname" class="form-control forGuest" placeholder="@lang('First Name')" value="{{ old('firstname') }}" required>
                            </div>
                            <div class="col-md-6 guestInputDiv">
                                <input type="text" name="lastname" class="form-control forGuest" placeholder="@lang('Last Name')" value="{{ old('lastname') }}" required>
                            </div>



                            <div class="col-md-6 ">
                                <label class="form--label">@lang('Country')</label>
                                <select class="form-control form--control select2" name="country" required>
                                    <option value="" disabled selected hidden>@lang('Select your country')</option>
                                    @foreach ($countries as $key => $country)
                                        <option data-mobile_code="{{ $country->dial_code }}" data-code="{{ $key }}" value="{{ $country->country }}">
                                            {{ __($country->country) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 ">
                                <label class="form--label">@lang('Mobile')</label>
                                <div class="input-group">
                                    <span class="input-group-text mobile-code"></span>
                                    <input name="mobile_code" type="hidden" value="{{ old('mobile_code') }}">
                                    <input name="country_code" type="hidden" value="{{ old('country_code') }}">
                                    <input class="form-control form--control" name="mobile" type="number" value="{{ old('mobile') }}" required>
                                </div>
                                <small class="text--danger mobileExist"></small>
                            </div>




{{--                            <div class="col-md-6 guestInputDiv">--}}
{{--                                <select class="form-control forGuest select2" name="country" required>--}}
{{--                                    <option value="" disabled selected hidden>@lang('Select your country')</option>--}}
{{--                                    @foreach ($countries as $key => $country)--}}
{{--                                        <option data-mobile_code="{{ $country->dial_code }}" data-code="{{ $key }}" value="{{ $country->country }}">--}}
{{--                                            {{ __($country->country) }}--}}
{{--                                        </option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                            <div class="col-md-6 guestInputDiv">--}}
{{--                                <div class="input-group">--}}
{{--                                    <span class="input-group-text mobile-code"></span>--}}
{{--                                    <input name="mobile_code" type="hidden" value="{{ old('mobile_code') }}">--}}
{{--                                    <input name="country_code" type="hidden" value="{{ old('country_code') }}">--}}
{{--                                    <input class="form-control forGuest" name="mobile" type="number" value="{{ old('mobile') }}" required>--}}
{{--                                </div>--}}
{{--                                <small class="text--danger mobileExist"></small>--}}
{{--                            </div>--}}
                            <div class="col-md-6 guestInputDiv">
                                <input type="text" name="username" class="form-control forGuest" placeholder="@lang('Username')" value="{{ old('username') }}" required>
                            </div>
                            <div class="col-md-6 guestInputDiv">
                                <input type="text" name="address" class="form-control forGuest" placeholder="@lang('Address')" value="{{ old('address') }}">
                            </div>
                            <div class="col-md-6 guestInputDiv">
                                <input type="text" name="city" class="form-control forGuest" placeholder="@lang('City')" value="{{ old('city') }}">
                            </div>
                            <div class="col-md-6 guestInputDiv">
                                <input type="text" name="state" class="form-control forGuest" placeholder="@lang('State')" value="{{ old('state') }}">
                            </div>
                            <div class="col-md-6 guestInputDiv">
                                <input type="text" name="zip" class="form-control forGuest" placeholder="@lang('Zip Code')" value="{{ old('zip') }}">
                            </div>

                            <div class="col-md-6">
                                <select class="form-control form--control select2-basic" name="insurance_type" required>
                                    <option value="" disabled selected hidden>@lang('Select your Insurance')</option>
                                    <option value="1">@lang('Complete')</option>
                                    <option value="0">@lang('Basic')</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <select class="form-control form--control select2-basic" name="baby_seat" required>
                                    <option value="0">@lang('Sans options')</option>
                                    <option value="1">0-3</option>
                                    <option value="2">3-5</option>
                                    <option value="3">6-10</option>
                                </select>
                            </div>
                        </div>

                        <div id="drivers-wrapper">
                            <h6>Conducteurs</h6>
                            <div class="driver-item row g-2 mb-2 border p-2 rounded align-items-center">
                                <div class="col-md-3">
                                    <input type="text" name="drivers[0][name]" class="form-control form-control-sm" placeholder="Nom & Prénom" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="drivers[0][license_number]" class="form-control form-control-sm" placeholder="Permis n°" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="date" name="drivers[0][license_date]" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="drivers[0][license_place]" class="form-control form-control-sm" placeholder="Par">
                                </div>
                                <div class="col-md-1 text-center">
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-driver">
                                        <i class="la la-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-driver">
                            <i class="la la-plus"></i> Ajouter un conducteur
                        </button>


                        {{-- Submit button (stays here, but summary controls booking) --}}
                        <div class="mt-4">
                            <button type="submit" class="btn btn--primary w-100 confirmBookingBtn" disabled>
                                <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                                <span class="btn-text text--white"><i class="la la-check-circle"></i> @lang('Book Now')</span>
                            </button>

                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mt-3 mt-lg-0">

            <div class="card-body">
                <ul class="list-group list-group-flush orderItem">
                    <li class="list-group-item bg-dark fw-bold">Booking Details</li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span><i class="las la-calendar"></i> Pickup</span>
                        <span class="pickupDate text-end">-</span>
                        <input type="hidden" name="pickup_date">
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span><i class="las la-calendar-check"></i> Drop-off</span>
                        <span class="dropoffDate text-end">-</span>
                        <input type="hidden" name="return_date">
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span><i class="las la-map-marker"></i> Pickup Location</span>
                        <span class="pickupLoc text-end">-</span>
                        <input type="hidden" name="pick_location">
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span><i class="las la-map-marker-alt"></i> Drop Location</span>
                        <span class="dropLoc text-end">-</span>
                        <input type="hidden" name="drop_location">
                    </li>
                </ul>

                <ul class="list-group list-group-flush mt-3">
                    <li class="list-group-item bg-dark fw-bold">Fare Summary</li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Vehicle</span>
                        <span class="vehicleName text-end">-</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Days</span>
                        <span class="daysCount">0</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Fare/Day</span>
                        <span class="farePerDay">0 {{ __(gs()->cur_text) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Subtotal</span>
                        <span class="totalFare">0 {{ __(gs()->cur_text) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Insurance</span>
                        <span class="insuranceCharge">0 {{ __(gs()->cur_text) }}</span>
                        <input type="hidden" name="insurance_charge">
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Carburant/Cleaning Fee</span>
                        <input type="number" class="form-control form-control-sm w-50 text-end"
                               name="carburant_fee" placeholder="3000">
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Amount Paid</span>
                        <input type="number" class="form-control form-control-sm w-50 text-end"
                               name="paid_amount" placeholder="0">
                    </li>

                    <li class="list-group-item d-flex justify-content-between fw-bold">
                        <span>Reste à Payer</span>
                        <span class="remainingAmount">0 {{ __(gs()->cur_text) }}</span>
                    </li>


                    <li class="list-group-item d-flex justify-content-between">
                        <span>{{ gs()->tax_name }} ({{ gs()->tax }}%)</span>
                        <span class="taxCharge">0</span> {{ __(gs()->cur_text) }}
                        <input type="hidden" name="tax_charge">
                    </li>
                    <li class="list-group-item d-flex justify-content-between fw-bold">
                        <span>Total Fare</span>
                        <span class="grandTotalFare">0 {{ __(gs()->cur_text) }}</span>
                        <input type="hidden" name="total_amount">
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Caution (Deposit)</span>
                        <input type="number" class="form-control form-control-sm w-50 text-end"
                               name="caution_amount" placeholder="0">
                    </li>
                </ul>
            </div>


        </div>
    </div>

    <div class="modal fade" id="bookingDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-dark text-white">
                    <h6 class="modal-title"><i class="la la-car"></i> Slot Details</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs mb-3" id="detailsTabs">
                        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#bookedTab">Booked</a></li>
                        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#freeTab">Free</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="bookedTab">
                            <ul class="list-group bookedList"></ul>
                        </div>
                        <div class="tab-pane fade" id="freeTab">
                            <ul class="list-group freeList"></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('style')
    <style>
        .legend-dot { display:inline-block;width:10px;height:10px;border-radius:50% }
        .fc-highlight { background: rgba(59,130,246,.28) !important; }
        .select2-container { width: 100% !important; }

        /* Selection highlight */
        .fc-event.selected-slot {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
            border: 2px solid #1e40af !important;
            opacity: 0.85 !important;
            border-radius: 6px;
        }

        /* Tooltip for live selection */
        .selection-tooltip {
            position: absolute;
            background: #111827;
            color: white;
            padding: 4px 8px;
            font-size: 12px;
            border-radius: 4px;
            pointer-events: none;
            z-index: 9999;
        }

        .fc-tooltip {
            background: #111827;
            color: white;
            padding: 6px 10px;
            font-size: 12px;
            border-radius: 4px;
            pointer-events: none;
            z-index: 9999;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
            display: none;
            max-width: 250px;
        }

        .fc-event.ghost-orange {
            cursor: pointer;
        }

        .fc-event.ghost-slot.partially-booked {
            pointer-events: auto; /* still clickable */
            background: transparent !important;
            border: none !important;
        }
        .fc-highlight {
            pointer-events: none; /* allow selection overlay to win */
        }

        .fc-event.ghost-slot {
            pointer-events: none !important; /* background does not block drag */
            background: transparent !important;
            border: none !important;
        }

        .fc-event.click-layer {
            background: transparent !important;
            border: none !important;
            cursor: pointer;
        }

        .fc-event.click-layer {
            background: transparent !important;
            border: none !important;
            cursor: pointer;
            pointer-events: auto !important;   /* allow click */
        }

        .fc-highlight { pointer-events: none !important; }  /* keep drag overlay on top */

        .popover.custom-popover {
            max-width: 350px;
            border-radius: 10px;
            border: none;
            box-shadow: 0 6px 20px rgba(0,0,0,0.25);
            z-index: 9999;
        }

        .popover.custom-popover .popover-header {
            background: #1f2937;
            color: #fff;
            font-weight: 600;
            font-size: 14px;
            padding: 8px 12px;
        }

        .popover.custom-popover .popover-body {
            padding: 10px 14px;
            font-size: 13px;
            max-height: 300px;
            overflow-y: auto;
        }

        .popover-content-wrapper ul {
            margin: 0;
            padding-left: 16px;
        }

        .popover-content-wrapper li {
            margin-bottom: 4px;
            line-height: 1.3;
        }

        .popover-content-wrapper .section {
            margin-bottom: 8px;
        }

        .popover-content-wrapper .section.booked b {
            color: #ef4444;
        }

        .popover-content-wrapper .section.free b {
            color: #10b981;
        }

        /* Orange shading but not blocking selection */
        .fc-event.slot-partial-bg {
            background: rgba(255, 165, 0, 0.5) !important;
            border: none !important;
            pointer-events: none !important; /* lets drag/selection work */
        }

        /* Transparent clickable layer */
        .fc-event.slot-partial-click {
            background: transparent !important;
            border: none !important;
            cursor: pointer;
            pointer-events: auto !important; /* only this handles clicks */
        }







        .custom-popover {
            max-width: 350px;
            border-radius: 10px;
            border: none;
            background: #fff;
            box-shadow: 0 6px 20px rgba(0,0,0,0.25);
            z-index: 9999;
        }

        .custom-popover .popover-header {
            background: #1f2937;
            color: #fff;
            font-weight: 600;
            font-size: 14px;
            padding: 8px 12px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .custom-popover .popover-body {
            padding: 10px 14px;
            font-size: 13px;
            max-height: 300px;
            overflow-y: auto;
        }

        .custom-popover ul {
            margin: 0;
            padding-left: 16px;
        }

        .custom-popover li {
            margin-bottom: 4px;
            line-height: 1.3;
        }


        /* Orange shading (partial availability) */
        .fc-event.slot-partial-bg {
            background: rgba(255, 165, 0, 0.5) !important;
            border: none !important;
            pointer-events: none !important; /* background does not block dragging */
        }



    </style>
@endpush

@push('style-lib')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
@endpush

@push('script-lib')
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endpush



@push('script')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const displayFormat = 'DD/MM/YYYY HH:mm';
        const backendFormat = 'YYYY-MM-DD HH:mm';

        const start = moment();
        const end   = moment().add(1, 'days');

        function setDates(el, s, e) {
            $(el).val(s.format(displayFormat) + ' - ' + e.format(displayFormat));
            document.querySelectorAll('input[name="pickup_date"]').forEach(x => x.value = s.format(backendFormat));
            document.querySelectorAll('input[name="return_date"]').forEach(x => x.value = e.format(backendFormat));
        }

        $('.bookingDatePicker').daterangepicker({
            // minDate: start,
            startDate: start,
            endDate: end,
            timePicker: true,
            timePickerIncrement: 15,
            autoUpdateInput: false,
            locale: { format: displayFormat }
        }, (s, e) => setDates('.bookingDatePicker', s, e));
        setDates('.bookingDatePicker', start, end);

        let slotDuration = '00:30:00';

        // --- helper to compute days ---
        function calculateDays(pickup, drop) {
            let hours = drop.diff(pickup, 'hours', true); // fractional hours
            let days = Math.ceil(hours / 24);
            return days > 0 ? days : 1;
        }

        const calendar = new FullCalendar.Calendar(document.getElementById('vehicleCalendar'), {
            timeZone: 'local',
            height: 'auto',
            initialView: 'timeGridWeek',
            headerToolbar: { left:'prev,next today', center:'title', right:'timeGridDay,timeGridWeek' },

            // 🔹 Force 24h format for agenda slots
            slotLabelFormat: {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            },

            dayHeaderContent: function(arg) {
                const diffDays = arg.view.currentEnd - arg.view.currentStart;
                return diffDays > 7
                    ? moment(arg.date).format("DD/MM")
                    : moment(arg.date).format("ddd DD/MM");
            },

            events: {
                url: '{{ route('admin.vehicule.agenda.events') }}',
                method: 'GET',
                extraParams: () => Object.fromEntries(new FormData(document.querySelector('.formVehicleSearch')))
            },

            selectable: true,
            eventDisplay: 'block',
            eventTextColor: '#fff',

            selectAllow: function(sel) {
                // Only block fully booked slots
                let blocking = calendar.getEvents().some(e =>
                    e.classNames.includes("slot-full") &&
                    Math.max(+e.start, +sel.start) < Math.min(+e.end, +sel.end)
                );
                return !blocking;
            },

            select: function (info) {
                let pickup = moment(info.start);
                let drop   = moment(info.end);
                let days   = calculateDays(pickup, drop);

                // ✅ store selected range globally
                selectedPickup = pickup;
                selectedDrop   = drop;

                // remove old selection
                calendar.getEvents().forEach(e => {
                    if (e.id === 'selected-slot') e.remove();
                });

                // add styled event for selection
                calendar.addEvent({
                    id: 'selected-slot',
                    start: info.start,
                    end: info.end,
                    classNames: ['selected-slot'],
                    title: pickup.format('HH:mm') + ' → ' + drop.format('HH:mm')
                });

                // 👉 get selected dossier or model for summary
                let vehicleSelect = $('#vehicleSelect option:selected');
                let modelSelect   = $('[name="vehicle_model"] option:selected');
                let vehicleName, price;

                if (vehicleSelect.val()) {
                    vehicleName = vehicleSelect.text();
                    price = parseFloat(vehicleSelect.data('price')) || 0;
                } else if (modelSelect.val()) {
                    vehicleName = modelSelect.text();
                    price = parseFloat(modelSelect.data('price')) || 0;
                } else {
                    vehicleName = "Any Vehicle";
                    price = 0;
                }

                // ✅ update booking summary
                updateSummary(vehicleName, days, price, pickup, drop);

                // update booking form hidden fields
                document.querySelector('.booking-form input[name="pickup_date"]').value =
                    pickup.format('YYYY-MM-DD HH:mm');
                document.querySelector('.booking-form input[name="return_date"]').value =
                    drop.format('YYYY-MM-DD HH:mm');

                document.querySelector('.confirmBookingBtn').disabled = false;
            }


        });

        calendar.setOption('selectAllow', function(sel) {
            // Block only red slots (fully booked)
            let blocking = calendar.getEvents().some(e =>
                e.classNames.includes("slot-full") &&
                Math.max(+e.start, +sel.start) < Math.min(+e.end, +sel.end)
            );
            return !blocking; // ✅ orange areas still selectable
        });



        // Create one tooltip element
        const globalTooltip = document.createElement('div');
        globalTooltip.className = 'fc-tooltip';
        globalTooltip.style.position = 'fixed';
        globalTooltip.style.display = 'none';
        document.body.appendChild(globalTooltip);

        calendar.on('eventDidMount', function(info) {
            if (info.event.classNames.includes('slot-partial')) {
                const data = info.event.extendedProps;

                info.el.addEventListener('mouseenter', e => {
                    let html = '';

                    if (data.bookings?.length) {
                        html += `<div><b style="color:#ef4444">Booked:</b><ul>`;
                        data.bookings.forEach(b => {
                            html += `<li>${b.plate} – ${b.model}<br><small>${b.pickup} → ${b.drop}</small></li>`;
                        });
                        html += `</ul></div>`;
                    }

                    if (data.free?.length) {
                        html += `<div><b style="color:#10b981">Free:</b><ul>`;
                        data.free.forEach(f => {
                            html += `<li>${f.plate} – ${f.model}</li>`;
                        });
                        html += `</ul></div>`;
                    }

                    globalTooltip.innerHTML = html || 'No data';
                    globalTooltip.style.display = 'block';
                    globalTooltip.style.left = e.clientX + 15 + 'px';
                    globalTooltip.style.top  = e.clientY + 15 + 'px';
                });

                info.el.addEventListener('mousemove', e => {
                    globalTooltip.style.left = e.clientX + 15 + 'px';
                    globalTooltip.style.top  = e.clientY + 15 + 'px';
                });

                info.el.addEventListener('mouseleave', () => {
                    globalTooltip.style.display = 'none';
                });
            }
        });

        // 🔹 Update summary when dossier model or specific dossier changes
        $('#vehicleSelect, [name="vehicle_model"]').on('change', function () {
            if (!selectedPickup || !selectedDrop) return; // only if a time range is already selected

            let vehicleSelect = $('#vehicleSelect option:selected');
            let modelSelect   = $('[name="vehicle_model"] option:selected');
            let vehicleName, price;

            if (vehicleSelect.val()) {
                vehicleName = vehicleSelect.text();
                price = parseFloat(vehicleSelect.data('price')) || 0;
            } else if (modelSelect.val()) {
                vehicleName = modelSelect.text();
                price = parseFloat(modelSelect.data('price')) || 0;
            } else {
                vehicleName = "Any Vehicle";
                price = 0;
            }

            let days = Math.ceil(selectedDrop.diff(selectedPickup, 'hours', true) / 24);
            if (days < 1) days = 1;

            updateSummary(vehicleName, days, price, selectedPickup, selectedDrop);
        });


        document.addEventListener("click", function(e) {
            const eventEl = e.target.closest(".fc-event.click-layer, .fc-event.slot-partial-click");
            if (!eventEl) return;

            if (clickTimer === null) {
                clickTimer = setTimeout(() => {
                    clickTimer = null;
                    document.querySelectorAll(".fc-event.selected-slot").forEach(el =>
                        el.classList.remove("selected-slot")
                    );
                    eventEl.classList.add("selected-slot");
                }, 250);
            } else {
                clearTimeout(clickTimer);
                clickTimer = null;
                showCard(eventEl);
            }
        });






        calendar.render();

        document.querySelector('.formVehicleSearch').addEventListener('submit', function (e) {
            e.preventDefault();

            const pickup = moment(document.querySelector('input[name="pickup_date"]').value, backendFormat);
            const drop   = moment(document.querySelector('input[name="return_date"]').value, backendFormat);

            if (pickup.isValid() && drop.isValid() && drop.isAfter(pickup)) {
                // force generic timeGrid
                calendar.changeView('timeGrid');

                // set visible range to exactly what was selected
                calendar.setOption('visibleRange', {
                    start: pickup.toISOString(),
                    end: drop.toISOString()
                });

                // jump to start of range
                calendar.gotoDate(pickup.toDate());
            }

            // reload events with new params
            calendar.refetchEvents();
        });





        // Filter vehicles by model
        document.querySelector('[name="vehicle_model"]').addEventListener('change', function () {
            const selectedModel = this.value;
            const vehicleSelect = document.getElementById('vehicleSelect');
            [...vehicleSelect.options].forEach(opt => {
                if (!opt.value) return;
                opt.style.display = (selectedModel === "" || opt.dataset.model === selectedModel) ? 'block' : 'none';
            });
            vehicleSelect.value = "";
        });

        // Quand on soumet le booking-form
        document.querySelector('.booking-form').addEventListener('submit', function () {
            let pick = document.querySelector('#pick-point').value;
            let drop = document.querySelector('#drop-point').value;
            let modelSelect = $('[name="vehicle_model"] option:selected');
            let vehicleSelect = $('#vehicleSelect option:selected');


            document.querySelector('.booking-form input[name="pick_location"]').value = pick;
            document.querySelector('.booking-form input[name="drop_location"]').value = drop;
            document.querySelector('.booking-form input[name="vehicle_model"]').value = modelSelect.val() || "";
            document.querySelector('.booking-form input[name="vehicle_id"]').value = vehicleSelect.val() || "";

        });


        // Zoom buttons
        document.getElementById('zoom15').onclick = () => { slotDuration = '00:15:00'; calendar.setOption('slotDuration', slotDuration); setActive('zoom15'); };
        document.getElementById('zoom30').onclick = () => { slotDuration = '00:30:00'; calendar.setOption('slotDuration', slotDuration); setActive('zoom30'); };
        document.getElementById('zoom60').onclick = () => { slotDuration = '01:00:00'; calendar.setOption('slotDuration', slotDuration); setActive('zoom60'); };

        function setActive(id){ ['zoom15','zoom30','zoom60'].forEach(x => document.getElementById(x).classList.toggle('active', x===id)); }

        document.getElementById('add-driver').addEventListener('click', function() {
            let wrapper = document.getElementById('drivers-wrapper');
            let count = wrapper.querySelectorAll('.driver-item').length;
            let clone = wrapper.querySelector('.driver-item').cloneNode(true);

            clone.querySelectorAll('input').forEach(input => {
                let name = input.getAttribute('name');
                name = name.replace(/\[\d+\]/, `[${count}]`);
                input.setAttribute('name', name);
                input.value = "";
            });

            wrapper.appendChild(clone);
        });

// 🚮 supprimer conducteur
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-driver')) {
                let item = e.target.closest('.driver-item');
                let wrapper = document.getElementById('drivers-wrapper');

                // Ne pas supprimer le premier conducteur (au moins 1 obligatoire)
                if (wrapper.querySelectorAll('.driver-item').length > 1) {
                    item.remove();
                } else {
                    alert("⚠️ Il doit y avoir au moins un conducteur.");
                }
            }
        });


        // Guest type toggle
        $('#guestType').on('change', function() {
            if ($(this).val() == 1) {
                $('.guestInputDiv').addClass('d-none');
                $('.forGuest').attr("required", false);
            } else {
                $('.guestInputDiv').removeClass('d-none');
                $('.forGuest').attr("required", true);
            }
        });
        // 🔹 Keep hidden caution field in sync
        $('input[name="caution_amount"]').on('input', function() {
            $('.booking-form input[name="caution_amount"]').val($(this).val());
        });
    });

    function updateSummary(vehicleName, days, farePerDay, pickupMoment = null, dropMoment = null) {
        let subtotal = days * farePerDay;

        // Insurance
        let insuranceType = $('[name="insurance_type"]').val();
        let insuranceRate = insuranceType == "1" ? 6000 : 0;
        let insuranceTotal = insuranceRate * days;

        // Carburant + Cleaning merged
        let carburantFee = parseFloat($('[name="carburant_fee"]').val()) || 3000;

        // Amount already paid
        let paidAmount = parseFloat($('[name="paid_amount"]').val()) || 0;


        let taxPercent = parseFloat($('.taxCharge').data('percent_charge')) || 0;
        let tax = (subtotal + insuranceTotal) * taxPercent / 100;

        let insuranceLabel = insuranceType == "1" ? "Complete" : "Basic";
        $('.insuranceCharge').text(`${insuranceLabel} – ${insuranceTotal.toFixed(2)} {{ __(gs()->cur_text) }}`);

        let total = subtotal + insuranceTotal + tax + carburantFee;

        // Remaining to pay
        let remaining = total - paidAmount;
        if (remaining < 0) remaining = 0; // no negative reste

        // Update fields
        $('.vehicleName').text(vehicleName);
        $('.daysCount').text(days);
        $('.farePerDay').text(farePerDay.toFixed(2) + " {{ __(gs()->cur_text) }}");
        $('.totalFare').text(subtotal.toFixed(2) + " {{ __(gs()->cur_text) }}");
        $('.taxCharge').text(tax.toFixed(2));
        $('.grandTotalFare').text(total.toFixed(2) + " {{ __(gs()->cur_text) }}");
        $('.remainingAmount').text(remaining.toFixed(2) + " {{ __(gs()->cur_text) }}");

        // Hidden inputs
        $('[name="insurance_charge"]').val(insuranceTotal.toFixed(2));
        $('[name="tax_charge"]').val(tax.toFixed(2));
        $('[name="total_amount"]').val(total.toFixed(2));
        $('[name="remaining_amount"]').val(remaining.toFixed(2));

        // ✅ Always use agenda selection (never fallback to filter inputs)
        $('.pickupDate').text(pickupMoment.format('DD/MM/YYYY HH:mm'));
        $('.dropoffDate').text(dropMoment.format('DD/MM/YYYY HH:mm'));


        let pickLoc = $('#pick-point option:selected').text();
        let dropLoc = $('#drop-point option:selected').text();
        $('.pickupLoc').text(pickLoc);
        $('.dropLoc').text(dropLoc);
    }

    // Recalculate summary when insurance is changed
    $('[name="insurance_type"]').on('change', function() {
        let vehicleName = $('.vehicleName').text();
        let days = parseInt($('.daysCount').text()) || 1;
        let farePerDay = parseFloat($('.farePerDay').text()) || 0;

        // ✅ use selectedPickup/selectedDrop if available
        let pickup = selectedPickup || moment($('input[name="pickup_date"]').val());
        let drop   = selectedDrop   || moment($('input[name="return_date"]').val());

        updateSummary(vehicleName, days, farePerDay, pickup, drop);

    });

    $('[name="carburant_fee"]').on('input', function() {
        // update hidden field in booking form
        $('.booking-form input[name="carburant_fee"]').val($(this).val());

        // also recalc summary
        let vehicleName = $('.vehicleName').text();
        let days = parseInt($('.daysCount').text()) || 1;
        let farePerDay = parseFloat($('.farePerDay').text()) || 0;
        // ✅ use selectedPickup/selectedDrop if available
        let pickup = selectedPickup || moment($('input[name="pickup_date"]').val());
        let drop   = selectedDrop   || moment($('input[name="return_date"]').val());

        updateSummary(vehicleName, days, farePerDay, pickup, drop);

    });


    $('[name="paid_amount"]').on('input', function() {
        $('.paidHidden').val($(this).val());

        // also recalc summary
        let vehicleName = $('.vehicleName').text();
        let days = parseInt($('.daysCount').text()) || 1;
        let farePerDay = parseFloat($('.farePerDay').text()) || 0;
// ✅ use selectedPickup/selectedDrop if available
        let pickup = selectedPickup || moment($('input[name="pickup_date"]').val());
        let drop   = selectedDrop   || moment($('input[name="return_date"]').val());

        updateSummary(vehicleName, days, farePerDay, pickup, drop);

    });

    $(document).ready(function () {
        $('.booking-form').on('submit', function (e) {
            e.preventDefault(); // stop default submit

            // Récupérer les infos du résumé
            let pickup   = $('.pickupDate').text();
            let dropoff  = $('.dropoffDate').text();
            let pickLoc  = $('.pickupLoc').text();
            let dropLoc  = $('.dropLoc').text();
            let vehicle  = $('.vehicleName').text();
            let days     = $('.daysCount').text();
            let total    = $('.grandTotalFare').text();
            let paid     = $('[name="paid_amount"]').val() || 0;
            let remaining= $('.remainingAmount').text();

            Swal.fire({
                title: 'Confirmer la réservation ?',
                html: `
                <div class="text-start">
                    <p><b>🚗 Véhicule :</b> ${vehicle}</p>
                    <p><b>📅 Pickup :</b> ${pickup}</p>
                    <p><b>📅 Drop-off :</b> ${dropoff}</p>
                    <p><b>📍 Lieux :</b> ${pickLoc} → ${dropLoc}</p>
                    <p><b>📊 Jours :</b> ${days}</p>
                    <p><b>💰 Total :</b> ${total}</p>
                    <p><b>💵 Payé :</b> ${paid}</p>
                    <p><b>⚖️ Reste à payer :</b> ${remaining}</p>
                </div>
            `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '✅ Confirmer',
                cancelButtonText: '❌ Annuler',
            }).then((result) => {

                if (result.isConfirmed) {
                    let btn = $('.confirmBookingBtn');
                    btn.prop('disabled', true);
                    btn.find('.spinner-border').removeClass('d-none'); // montre spinner
                    btn.find('.btn-text').text('Processing...');       // change texte

                    e.target.submit(); // soumettre réellement le formulaire
                }

            });
        });
    });


</script>


    <script>
        "use strict";

        let alreadyWarned = false;

        function checkGuest() {
            let email = $('input[name="email"]').val();
            let mobile = $('input[name="mobile"]').val();

            if (!email && !mobile) return;

            $.ajax({
                url: "{{ route('admin.guest.check') }}",
                type: "POST",
                dataType: "json",
                data: {
                    _token: "{{ csrf_token() }}",
                    email: email,
                    mobile: mobile
                },
                success: function (res) {
                    console.log("checkGuest response:", res);

                    if (res.exists) {
                        if ($('#guestType').val() == "0" && !alreadyWarned) {
                            alreadyWarned = true; // 🚫 prevent spamming
                            alert("⚠️ This user already exists. Please switch to 'Existing Guest'.\nIf you continue as Walk-In, their profile will be updated.");
                        }

                        // Autofill data
                        $('input[name="firstname"]').val(res.data.firstname);
                        $('input[name="lastname"]').val(res.data.lastname);
                        $('input[name="address"]').val(res.data.address);
                        $('input[name="city"]').val(res.data.city);
                        $('input[name="state"]').val(res.data.state);
                        $('input[name="zip"]').val(res.data.zip);
                        $('select[name="country"]').val(res.data.country_name);
                    }
                }
            });
        }

        $(function() {
            // ✅ trigger when leaving email or mobile
            $('input[name="email"], input[name="mobile"]').on('blur', checkGuest);

            $('select[name="country"]').on('change', function () {
                const selected = $(this).find(':selected');
                $('input[name="mobile_code"]').val(selected.data('mobile_code'));
                $('input[name="country_code"]').val(selected.data('code'));
                $('.mobile-code').text('+' + selected.data('mobile_code'));
            }).trigger('change'); // ✅ keep trigger, but no checkGuest()

        });
    </script>
@endpush
