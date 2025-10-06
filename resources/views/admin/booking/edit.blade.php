@extends('admin.layouts.app')

@section('panel')
    {{-- Agenda --}}
    <div class="card mt-3">

        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <span class="legend-dot" style="background:#ef4444"></span> @lang('Reserved')
                <span class="legend-dot ms-3" style="background:#10b981"></span> @lang('Available')
                <span class="legend-dot ms-3" style="background:#3b82f6"></span> @lang('Selected')
            </div>
            <div class="d-flex align-items-center gap-2">
                {{-- 🔹 Agenda Date Range --}}
                <input type="text" id="agendaRangePicker"
                       class="form-control form-control-sm"
                       placeholder="@lang('Select Agenda Range')"
                       style="width:240px">

                <div class="btn-group ms-2">
                    <button class="btn btn-sm btn-outline-secondary" id="zoom15">15m</button>
                    <button class="btn btn-sm btn-outline-secondary active" id="zoom30">30m</button>
                    <button class="btn btn-sm btn-outline-secondary" id="zoom60">60m</button>
                </div>
            </div>
        </div>



        <div class="card-body">
            <!-- 🔹 Display visible period -->
            <div id="calendarRangeDisplay" class="mb-2 text-center fw-bold text-primary"></div>
            <div id="vehicleCalendar"></div>
        </div>

    </div>

    {{-- Booking Wrapper --}}
    <div class="row booking-wrapper mt-3">
        {{-- LEFT --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">@lang('Edit Booking') #{{ $deposit->trx }}</h5>
                </div>
                <div class="card-body">
                    <form class="booking-form" method="POST" action="{{ route('admin.booking.update', $deposit->id) }}">
                        @csrf

                        <input type="hidden" name="pickup_date" value="{{ $deposit->rentLog->pick_time }}">
                        <input type="hidden" name="return_date" value="{{ $deposit->rentLog->drop_time }}">
                        <input type="hidden" name="pick_location" value="{{ $deposit->rentLog->pick_location }}">
                        <input type="hidden" name="drop_location" value="{{ $deposit->rentLog->drop_location }}">
                        <input type="hidden" name="vehicle_id" value="{{ $deposit->rentLog->vehicle->id }}">
                        <input type="hidden" name="vehicle_model" value="{{ $deposit->rentLog->vehicle->model }}">
                        <input type="hidden" name="caution_amount" class="cautionHidden" value="{{ $deposit->rentLog->caution }}">
                        <input type="hidden" name="paid_amount" class="paidHidden" value="{{ $deposit->final_amount }}">
                        <input type="hidden" name="carburant_fee" class="carburantHidden" value="{{ $deposit->rentLog->carburant_fee ?? 3000 }}">


                        <div class="card mb-3 shadow-sm border-0">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="las la-user-circle"></i> @lang('Client Information')</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    {{-- Email --}}
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-muted">@lang('Email')</label>
                                        <div class="form-control bg-dark">{{ $deposit->user->email ?? '-' }}</div>
                                    </div>

                                    {{-- Firstname --}}
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-muted">@lang('First Name')</label>
                                        <div class="form-control bg-dark">{{ $deposit->user->firstname ?? '-' }}</div>
                                    </div>

                                    {{-- Lastname --}}
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-muted">@lang('Last Name')</label>
                                        <div class="form-control bg-dark">{{ $deposit->user->lastname ?? '-' }}</div>
                                    </div>

                                    {{-- Insurance (editable) --}}
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-muted">@lang('Insurance')</label>
                                        <select class="form-select" name="insurance_type">
                                            <option value="0" {{ !$deposit->rentLog->insurance ? 'selected' : '' }}>@lang('Basic')</option>
                                            <option value="1" {{ $deposit->rentLog->insurance ? 'selected' : '' }}>@lang('Complete')</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-muted">@lang('Baby Seat')</label>
                                        <select class="form-select" name="baby_seat">
                                            <option value="0" {{ $deposit->rentLog->baby_seat == 0 ? 'selected' : '' }}>@lang('Sans options')</option>
                                            <option value="1" {{ $deposit->rentLog->baby_seat == 1 ? 'selected' : '' }}>0-3</option>
                                            <option value="2" {{ $deposit->rentLog->baby_seat == 2 ? 'selected' : '' }}>3-5</option>
                                            <option value="3" {{ $deposit->rentLog->baby_seat == 3 ? 'selected' : '' }}>6-10</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="drivers-wrapper" class="mt-3">
                            <h6>Conducteurs</h6>

                            @forelse($deposit->rentLog->drivers as $i => $driver)
                                <div class="driver-item row g-2 mb-2 border p-2 rounded align-items-center">
                                    <div class="col-md-3">
                                        <input type="text" name="drivers[{{ $i }}][name]" value="{{ $driver->name }}"
                                               class="form-control form-control-sm" placeholder="Nom & Prénom" required>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="drivers[{{ $i }}][license_number]" value="{{ $driver->license_number }}"
                                               class="form-control form-control-sm" placeholder="Permis n°" required>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="date" name="drivers[{{ $i }}][license_date]" value="{{ $driver->license_date }}"
                                               class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" name="drivers[{{ $i }}][license_place]" value="{{ $driver->license_place }}"
                                               class="form-control form-control-sm" placeholder="Par">
                                    </div>
                                    <div class="col-md-1 text-center">
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-driver">
                                            <i class="la la-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @empty
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
                            @endforelse
                        </div>

                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-driver">
                            <i class="la la-plus"></i> Ajouter un conducteur
                        </button>



                        <div class="mt-4">
                            <button type="submit" class="btn btn--primary w-100 confirmBookingBtn">
                                <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                                <span class="btn-text text--white"><i class="la la-save"></i> @lang('Update Booking')</span>
                            </button>

                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- RIGHT: Fare Summary --}}
        <div class="col-lg-4 mt-3 mt-lg-0">
            <div class="card-body">
                <ul class="list-group list-group-flush orderItem">
                    <li class="list-group-item bg-dark fw-bold">Fare Summary</li>

                    <li class="list-group-item d-flex justify-content-between">
                        <span><i class="las la-calendar"></i> Pickup</span>
                        <span class="pickupDate text-end">
        {{ \Carbon\Carbon::parse($deposit->rentLog->pick_time)->format('d/m/Y H:i') }}
    </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span><i class="las la-calendar-check"></i> Drop-off</span>
                        <span class="dropoffDate text-end">
        {{ \Carbon\Carbon::parse($deposit->rentLog->drop_time)->format('d/m/Y H:i') }}
    </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span><i class="las la-map-marker"></i> Pickup Location</span>
                        <span class="pickupLoc text-end">{{ $deposit->rentLog->pickUpLocation->name ?? '-' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span><i class="las la-map-marker-alt"></i> Drop Location</span>
                        <span class="dropLoc text-end">{{ $deposit->rentLog->dropLocation->name ?? '-' }}</span>
                    </li>



                    <li class="list-group-item d-flex justify-content-between">
                        <span>Vehicle</span>
                        <span class="vehicleName text-end">
                            {{ $deposit->rentLog->vehicle->brand->name }} - {{ $deposit->rentLog->vehicle->model }}
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Days</span>
                        <span class="daysCount">
                            {{ ceil(\Carbon\Carbon::parse($deposit->rentLog->pick_time)->diffInHours(\Carbon\Carbon::parse($deposit->rentLog->drop_time)) / 24) }}
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Fare/Day</span>
                        <span class="farePerDay" data-fare="{{ $deposit->rentLog->vehicle->price }}">
                            {{ $deposit->rentLog->vehicle->price }} {{ __(gs()->cur_text) }}
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Carburant Fee</span>
                        <input type="number" class="carburantVisible form-control form-control-sm w-50 text-end"
                               value="{{ $deposit->rentLog->carburant_fee ?? 3000 }}">
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Paid</span>
                        <input type="number" name="paid_amount"
                               value="{{ $deposit->final_amount }}"
                               class="form-control form-control-sm w-50 text-end">
                    </li>
                    <li class="list-group-item d-flex justify-content-between fw-bold">
                        <span>Remaining</span>
                        <span class="remainingAmount">{{ $deposit->rest_amount }} {{ __(gs()->cur_text) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between fw-bold">
                        <span>Total</span>
                        <span class="grandTotalFare">{{ $deposit->amount }} {{ __(gs()->cur_text) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Caution</span>
                        <input type="number" name="caution_amount"
                               value="{{ $deposit->rentLog->caution }}"
                               class="form-control form-control-sm w-50 text-end">
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@push('script-lib')
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">

@endpush

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const backendFormat = 'YYYY-MM-DD HH:mm';
            const pickupInit = moment("{{ $deposit->rentLog->pick_time }}");
            const dropInit   = moment("{{ $deposit->rentLog->drop_time }}");

            const calendar = new FullCalendar.Calendar(document.getElementById('vehicleCalendar'), {
                timeZone: 'local',
                height: 'auto',
                initialView: 'timeGridWeek',
                initialDate: pickupInit.format("YYYY-MM-DD"), // ✅ démarre la semaine du booking
                headerToolbar: {
                    left:'prev,next today',
                    center:'title',
                    right:'timeGridDay,timeGridWeek'
                },
                views: {
                    customRange: {
                        type: 'timeGrid',   // ✅ timeGrid, not fixed to 7 days
                    }
                },

                // ✅ Add this
                dayHeaderFormat: { day: 'numeric', month: 'numeric' },

                events: {
                    url: '{{ route('admin.vehicule.agenda.events') }}',
                    method: 'GET',
                    extraParams: { vehicle_id: {{ $deposit->rentLog->vehicle_id }} }
                },

                selectable: true,
                selectMirror: true,
                editable: false,
                eventResizableFromStart: false,

                selectAllow: function(selection) {
                    let start = moment(selection.start);
                    let end   = moment(selection.end);

                    // block overlaps with other confirmed bookings
                    let blocking = calendar.getEvents().some(e => {
                        if (e.id === 'current-booking') return false;
                        let isReserved = e.classNames.includes("slot-full") ||
                            e.backgroundColor === '#ef4444' ||
                            e.extendedProps?.status === 'confirmed';
                        if (!isReserved) return false;

                        let eventStart = moment(e.start);
                        let eventEnd   = e.end ? moment(e.end) : eventStart.clone().add(1, 'days');

                        return Math.max(+eventStart, +start) < Math.min(+eventEnd, +end);
                    });
                    return !blocking;
                },

                // ✅ When admin reselects a new slot
                select: function (info) {
                    const pickup = moment(info.start);
                    const drop   = moment(info.end);

                    $('input[name="pickup_date"]').val(pickup.format(backendFormat));
                    $('input[name="return_date"]').val(drop.format(backendFormat));

                    let days = Math.max(1, Math.ceil(drop.diff(pickup, 'hours') / 24));
                    let fare = parseFloat($('.farePerDay').data('fare')) || 0;

                    updateSummary($('.vehicleName').text(), days, fare, pickup, drop);
                },

                eventOverlap: function(stillEvent, movingEvent) {
                    if (movingEvent.id === 'current-booking' && stillEvent.id !== 'current-booking') {
                        return false;
                    }
                    return true;
                },

                eventDidMount: function(info) {
                    // Replace the red copy of current booking with blue slot
                    if (
                        moment(info.event.start).isSame(pickupInit, 'minute') &&
                        moment(info.event.end).isSame(dropInit, 'minute')
                    ) {
                        info.event.remove();
                        calendar.select({
                            start: pickupInit.toDate(),
                            end: dropInit.toDate()
                        });
                    }
                }
            });

            calendar.render();

            $('#agendaRangePicker').daterangepicker({
                timePicker: true,
                timePickerIncrement: 30,
                autoUpdateInput: false,
                locale: { format: 'DD/MM/YYYY HH:mm' }
            }, function(start, end) {
                // show in the input field
                $('#agendaRangePicker').val(start.format('DD/MM/YYYY HH:mm') + ' → ' + end.format('DD/MM/YYYY HH:mm'));

                // ✅ use a custom view for flexible range
                calendar.changeView('customRange', start.toDate());

                // define custom range dynamically
                calendar.setOption('visibleRange', {
                    start: start.toISOString(),
                    end: end.toISOString()
                });

                // jump to start of range
                calendar.gotoDate(start.toDate());

                // update agenda display text
                document.getElementById('calendarRangeDisplay').innerText =
                    "📅 Showing: " + start.format("DD/MM/YYYY HH:mm") + " → " + end.format("DD/MM/YYYY HH:mm");
            });


            // ✅ Zoom controls
            function setActive(id) {
                ['zoom15','zoom30','zoom60'].forEach(x =>
                    document.getElementById(x).classList.toggle('active', x===id)
                );
            }
            document.getElementById('zoom15').onclick = () => { calendar.setOption('slotDuration','00:15:00'); setActive('zoom15'); };
            document.getElementById('zoom30').onclick = () => { calendar.setOption('slotDuration','00:30:00'); setActive('zoom30'); };
            document.getElementById('zoom60').onclick = () => { calendar.setOption('slotDuration','01:00:00'); setActive('zoom60'); };


            // Add new driver
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

                // remove hidden ID from clone
                let hidden = clone.querySelector('input[name*="[id]"]');
                if (hidden) hidden.remove();

                wrapper.appendChild(clone);
            });

// Remove driver
            document.addEventListener('click', function(e) {
                if (e.target.closest('.remove-driver')) {
                    let item = e.target.closest('.driver-item');
                    let wrapper = document.getElementById('drivers-wrapper');

                    if (wrapper.querySelectorAll('.driver-item').length > 1) {
                        item.remove();
                    } else {
                        alert("⚠️ Il doit y avoir au moins un conducteur.");
                    }
                }
            });


            // 🔹 Summary update function
            function updateSummary(vehicleName, days, farePerDay, pickupMoment = null, dropMoment = null) {
                let subtotal = days * farePerDay;

                // Insurance
                let insuranceType = $('[name="insurance_type"]').val();
                let insuranceRate = insuranceType == "1" ? 6000 : 0;
                let insuranceTotal = insuranceRate * days;

                let carburantFee = parseFloat($('[name="carburant_fee"]').val()) || 3000;
                let paidAmount = parseFloat($('[name="paid_amount"]').val()) || 0;

                let taxPercent = @json(gs()->tax ?? 0);
                let tax = (subtotal + insuranceTotal + carburantFee) * taxPercent / 100;

                let total = subtotal + insuranceTotal + carburantFee + tax;
                let remaining = Math.max(0, total - paidAmount);

                // Update UI
                $('.daysCount').text(days);
                $('.farePerDay').text(farePerDay.toFixed(2) + " {{ __(gs()->cur_text) }}");
                $('.grandTotalFare').text(total.toFixed(2) + " {{ __(gs()->cur_text) }}");
                $('.remainingAmount').text(remaining.toFixed(2) + " {{ __(gs()->cur_text) }}");

                // ✅ Update pickup/drop display
                if (pickupMoment && pickupMoment.isValid() && dropMoment && dropMoment.isValid()) {
                    $('.pickupDate').text(pickupMoment.format('DD/MM/YYYY HH:mm'));
                    $('.dropoffDate').text(dropMoment.format('DD/MM/YYYY HH:mm'));
                }
            }

            // 🔹 Live updates when form fields change
            $('[name="insurance_type"]').on('change', function() {
                let days = parseInt($('.daysCount').text()) || 1;
                let farePerDay = parseFloat($('.farePerDay').data('fare')) || 0;
                let pickup = $('input[name="pickup_date"]').val();
                let drop   = $('input[name="return_date"]').val();
                updateSummary($('.vehicleName').text(), days, farePerDay, moment(pickup), moment(drop));
            });

            // $('[name="carburant_fee"]').on('input', function() {
            //     let days = parseInt($('.daysCount').text()) || 1;
            //     let farePerDay = parseFloat($('.farePerDay').data('fare')) || 0;
            //     let pickup = $('input[name="pickup_date"]').val();
            //     let drop   = $('input[name="return_date"]').val();
            //     updateSummary($('.vehicleName').text(), days, farePerDay, moment(pickup), moment(drop));
            // });

            $('.carburantVisible').on('input', function() {
                $('.carburantHidden').val($(this).val()); // update hidden input
                let days = parseInt($('.daysCount').text()) || 1;
                let farePerDay = parseFloat($('.farePerDay').data('fare')) || 0;
                let pickup = $('input[name="pickup_date"]').val();
                let drop   = $('input[name="return_date"]').val();
                updateSummary($('.vehicleName').text(), days, farePerDay, moment(pickup), moment(drop));
            });



            $('[name="paid_amount"]').on('input', function() {
                $('.paidHidden').val($(this).val());
                let days = parseInt($('.daysCount').text()) || 1;
                let farePerDay = parseFloat($('.farePerDay').data('fare')) || 0;
                let pickup = $('input[name="pickup_date"]').val();
                let drop   = $('input[name="return_date"]').val();
                updateSummary($('.vehicleName').text(), days, farePerDay, moment(pickup), moment(drop));
            });
        });

        $(document).ready(function () {
            $('.booking-form').on('submit', function (e) {
                e.preventDefault();

                let form = this;

                // Récupérer infos pour résumé
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
                    title: 'Confirmer la mise à jour ?',
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
                        btn.find('.spinner-border').removeClass('d-none');
                        btn.find('.btn-text').text('Processing...');

                        // 👉 Soumission normale (page reload Laravel)
                        form.submit();
                    }
                });
            });
        });

    </script>
@endpush
