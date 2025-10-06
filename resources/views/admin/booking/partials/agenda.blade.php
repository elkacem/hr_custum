{{-- resources/views/admin/booking/agenda.blade.php --}}
{{--@extends('admin.layouts.app')--}}

{{--@push('style-lib')--}}
{{--    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">--}}
{{--    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/resource-timeline@6.1.11/index.global.min.css" rel="stylesheet">--}}
{{--@endpush--}}

{{--@section('panel')--}}
{{--    --}}{{----}}{{-- your existing filters form (brand/seater/transmission/fuel + daterange) --}}
{{--    --}}{{----}}{{-- On submit, we’ll refetch calendar events with these params --}}

{{--    <div class="card mt-3">--}}
{{--        <div class="card-header d-flex justify-content-between align-items-center">--}}
{{--            <div>--}}
{{--                <span class="legend-dot" style="background:#ef4444"></span> @lang('Reserved')--}}
{{--                <span class="legend-dot ms-3" style="background:#10b981"></span> @lang('Available')--}}
{{--                <span class="legend-dot ms-3" style="background:#3b82f6"></span> @lang('Selected')--}}
{{--            </div>--}}
{{--            <div class="btn-group">--}}
{{--                <button class="btn btn-sm btn-outline-secondary" id="zoom15">15m</button>--}}
{{--                <button class="btn btn-sm btn-outline-secondary active" id="zoom30">30m</button>--}}
{{--                <button class="btn btn-sm btn-outline-secondary" id="zoom60">60m</button>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="card-body">--}}
{{--            <div id="vehicleCalendar"></div>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    --}}{{----}}{{-- your existing booking form lives on the right; keep hidden fields: --}}
{{--    --}}{{----}}{{-- <input type="hidden" name="vehicle_id"> --}}
{{--    --}}{{----}}{{-- <input type="hidden" name="pickup_date"> --}}
{{--    --}}{{----}}{{-- <input type="hidden" name="return_date"> --}}

{{--@endsection--}}

{{--@push('style')--}}
{{--    <style>--}}
{{--        .legend-dot{display:inline-block;width:10px;height:10px;border-radius:50%}--}}

{{--        /* make selection show in blue */--}}
{{--        .fc-highlight { background: rgba(59,130,246,.28) !important; }--}}
{{--        /* booked event color already red via backgroundColor in JSON */--}}
{{--    </style>--}}
{{--@endpush--}}

{{--@push('script-lib')--}}
{{--    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>--}}
{{--    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/resource-timeline@6.1.11/index.global.min.js"></script>--}}
{{--@endpush--}}

{{--@push('script')--}}
{{--    <script>--}}
{{--        (function(){--}}
{{--            const vehicles = @json(--}}
{{--    $vehicles->map(fn($v)=>[--}}
{{--      'id'    => (string)$v->id,--}}
{{--      'title' => trim((($v->brand->name ?? '') . ' ' . $v->name . ($v->model ? ' ('.$v->model.')' : ''))),--}}
{{--      'extendedProps' => [--}}
{{--        'seats' => optional($v->seater)->number,--}}
{{--        'trans' => $v->transmission,--}}
{{--        'fuel'  => $v->fuel_type,--}}
{{--        'price' => $v->price,--}}
{{--      ]--}}
{{--    ])--}}
{{--  );--}}

{{--            function pad(n){ return n.toString().padStart(2,'0'); }--}}
{{--            function toBackend(dt){ // local → "YYYY-MM-DD hh:mm A"--}}
{{--                const d = new Date(dt);--}}
{{--                let h = d.getHours(), ampm = 'AM';--}}
{{--                if (h === 0) h = 12; else if (h >= 12){ ampm='PM'; if (h>12) h-=12; }--}}
{{--                return d.getFullYear()+'-'+pad(d.getMonth()+1)+'-'+pad(d.getDate())+' '+pad(h)+':'+pad(d.getMinutes())+' '+ampm;--}}
{{--            }--}}

{{--            // read filters when fetching events--}}
{{--            function extraParams(){--}}
{{--                const form = document.querySelector('.formVehicleSearch');--}}
{{--                const fd = new FormData(form);--}}
{{--                const o = {};--}}
{{--                for (const [k,v] of fd.entries()) if (v !== '') o[k]=v;--}}
{{--                return o;--}}
{{--            }--}}

{{--            let slotDuration = '00:30:00';--}}

{{--            const calendarEl = document.getElementById('vehicleCalendar');--}}
{{--            const calendar = new FullCalendar.Calendar(calendarEl, {--}}
{{--                schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',--}}
{{--                timeZone: 'local',--}}
{{--                height: 'auto',--}}
{{--                stickyHeaderDates: true,--}}

{{--                initialView: 'resourceTimelineDay',        // try Day/Week--}}
{{--                headerToolbar: { left: 'prev,next today', center: 'title', right: 'resourceTimelineDay,resourceTimelineWeek' },--}}
{{--                resourceAreaHeaderContent: '{{ __("Vehicle") }}',--}}
{{--                resourceAreaWidth: '28%',--}}
{{--                resources: vehicles,--}}

{{--                slotDuration,--}}
{{--                slotMinWidth: 40,--}}
{{--                slotLabelFormat: [{ hour: 'numeric', minute: '2-digit', hour12: true }],--}}

{{--                selectable: true,--}}
{{--                selectMirror: true,--}}
{{--                selectOverlap: false,--}}
{{--                eventOverlap: false,--}}

{{--                // prevent selecting over red (booked) events--}}
{{--                selectAllow: function(sel){--}}
{{--                    const evts = calendar.getEvents().filter(e =>--}}
{{--                        e.getResources().some(r => r.id === sel.resource.id) &&--}}
{{--                        e.extendedProps && e.backgroundColor === '#ef4444' &&--}}
{{--                        // overlap test--}}
{{--                        Math.max(+e.start, +sel.start) < Math.min(+e.end, +sel.end)--}}
{{--                    );--}}
{{--                    return evts.length === 0;--}}
{{--                },--}}

{{--                // when user drags to select on a dossier row--}}
{{--                select: function(info){--}}
{{--                    // blue highlight appears automatically--}}
{{--                    const vehicleId = info.resource.id;--}}
{{--                    document.querySelector('[name="vehicle_id"]').value   = vehicleId;--}}
{{--                    document.querySelector('[name="pickup_date"]').value  = toBackend(info.start);--}}
{{--                    document.querySelector('[name="return_date"]').value  = toBackend(info.end);--}}
{{--                    if (window.notify) notify('success', 'Selected '+toBackend(info.start)+' → '+toBackend(info.end));--}}
{{--                    document.querySelector('.confirmBookingBtn')?.removeAttribute('disabled');--}}
{{--                },--}}

{{--                eventSources: [{--}}
{{--                    url: '{{ route('admin.vehicule.agenda.events') }}',--}}
{{--                    method: 'GET',--}}
{{--                    extraParams,               // include filters--}}
{{--                    failure: () => window.notify && notify('error','Failed to load availability'),--}}
{{--                }],--}}

{{--                // nicer resource label--}}
{{--                resourceLabelContent: function(arg){--}}
{{--                    const p = arg.resource.extendedProps || {};--}}
{{--                    const t = document.createElement('div');--}}
{{--                    t.innerHTML = `<div class="fw-semibold">${arg.resource.title}</div>--}}
{{--                     <div class="small text-muted">--}}
{{--                       {{ __("Seats") }}: ${p.seats ?? '-'} • {{ __("Trans") }}: ${p.trans ?? '-'} • {{ __("Fuel") }}: ${p.fuel ?? '-'}--}}
{{--                     </div>`;--}}
{{--                    return { domNodes: [t] };--}}
{{--                },--}}
{{--            });--}}

{{--            calendar.render();--}}

{{--            // Filters: refetch events (keeps current date/zoom)--}}
{{--            document.querySelector('.formVehicleSearch')?.addEventListener('submit', function(e){--}}
{{--                e.preventDefault();--}}
{{--                calendar.refetchEvents();--}}
{{--            });--}}

{{--            // Zoom buttons--}}
{{--            document.getElementById('zoom15').onclick = ()=>{ slotDuration='00:15:00'; calendar.setOption('slotDuration', slotDuration); setActive('zoom15'); };--}}
{{--            document.getElementById('zoom30').onclick = ()=>{ slotDuration='00:30:00'; calendar.setOption('slotDuration', slotDuration); setActive('zoom30'); };--}}
{{--            document.getElementById('zoom60').onclick = ()=>{ slotDuration='01:00:00'; calendar.setOption('slotDuration', slotDuration); setActive('zoom60'); };--}}
{{--            function setActive(id){--}}
{{--                ['zoom15','zoom30','zoom60'].forEach(x=>document.getElementById(x).classList.toggle('active', x===id));--}}
{{--            }--}}
{{--        })();--}}
{{--    </script>--}}
{{--@endpush--}}

{{--@extends('admin.layouts.app')--}}

{{--@php--}}
{{--    $initialResources = $vehicles->map(function ($v) {--}}
{{--        return [--}}
{{--            'id'    => (string) $v->id,--}}
{{--            'title' => trim(--}}
{{--                ($v->brand->name ?? '') . ' ' . $v->name . ($v->model ? ' (' . $v->model . ')' : '')--}}
{{--            ),--}}
{{--            'extendedProps' => [--}}
{{--                'seats' => optional($v->seater)->number,--}}
{{--                'trans' => $v->transmission,--}}
{{--                'fuel'  => $v->fuel_type,--}}
{{--                'price' => $v->price--}}
{{--            ]--}}
{{--        ];--}}
{{--    })->values()->all();--}}
{{--@endphp--}}

{{--@push('style-lib')--}}
{{--    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">--}}
{{--    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/resource-timeline@6.1.11/index.global.min.css" rel="stylesheet">--}}
{{--    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">--}}
{{--@endpush--}}

{{--@section('panel')--}}
{{--    --}}{{-- === Filters === --}}
{{--    <div class="card">--}}
{{--        <div class="card-body">--}}
{{--            <form class="formVehicleSearch">--}}
{{--                @csrf--}}
{{--                <div class="d-flex gap-3 flex-wrap">--}}
{{--                    <select class="form-control select2-basic" name="brand_id">--}}
{{--                        <option value="">@lang('Any Brand')</option>--}}
{{--                        @foreach($brands as $brand)--}}
{{--                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}

{{--                    <select class="form-control select2-basic" name="seater_id">--}}
{{--                        <option value="">@lang('Any Seater')</option>--}}
{{--                        @foreach($seaters as $s)--}}
{{--                            <option value="{{ $s->id }}">{{ $s->number }}</option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}

{{--                    <select class="form-control select2-basic" name="transmission">--}}
{{--                        <option value="">@lang('Any Transmission')</option>--}}
{{--                        @foreach($transmissions as $t)--}}
{{--                            <option value="{{ $t }}">{{ ucfirst($t) }}</option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}

{{--                    <select class="form-control select2-basic" name="fuel_type">--}}
{{--                        <option value="">@lang('Any Fuel')</option>--}}
{{--                        @foreach($fuelTypes as $f)--}}
{{--                            <option value="{{ $f }}">{{ ucfirst($f) }}</option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}

{{--                    <input class="bookingDatePicker form-control bg--white" name="date" placeholder="@lang('Select Date')" required>--}}
{{--                    <input type="hidden" name="pickup_date">--}}
{{--                    <input type="hidden" name="return_date">--}}

{{--                    <button class="btn btn--primary search" type="submit">--}}
{{--                        <i class="la la-search"></i> @lang('Search')--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--            </form>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    --}}{{-- === Agenda === --}}
{{--    <div class="card mt-3">--}}
{{--        <div class="card-header d-flex justify-content-between align-items-center">--}}
{{--            <div>--}}
{{--                <span class="legend-dot" style="background:#ef4444"></span> @lang('Reserved')--}}
{{--                <span class="legend-dot ms-3" style="background:#10b981"></span> @lang('Available')--}}
{{--                <span class="legend-dot ms-3" style="background:#3b82f6"></span> @lang('Selected')--}}
{{--            </div>--}}
{{--            <div class="btn-group">--}}
{{--                <button class="btn btn-sm btn-outline-secondary" id="zoom15">15m</button>--}}
{{--                <button class="btn btn-sm btn-outline-secondary active" id="zoom30">30m</button>--}}
{{--                <button class="btn btn-sm btn-outline-secondary" id="zoom60">60m</button>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="card-body">--}}
{{--            <div id="vehicleCalendar"></div>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    --}}{{-- === Booking form (right side or below; keep your markup) === --}}
{{--    --}}{{-- Replace this include with your own partial if you already have it --}}
{{--    <div class="card mt-3">--}}
{{--        <div class="card-header"><h5 class="mb-0">@lang('Book Vehicle')</h5></div>--}}
{{--        <div class="card-body">--}}
{{--            <form class="booking-form" method="POST" action="{{ route('admin.booking.book') }}">--}}
{{--                @csrf--}}
{{--                <input type="hidden" name="vehicle_id">--}}
{{--                <input type="hidden" name="pickup_date">--}}
{{--                <input type="hidden" name="return_date">--}}

{{--                <div class="row g-3">--}}
{{--                    <div class="col-md-6">--}}
{{--                        <label class="form-label">@lang('Guest Name')</label>--}}
{{--                        <input class="form-control" name="guest_name" required>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-6">--}}
{{--                        <label class="form-label">@lang('Email')</label>--}}
{{--                        <input type="email" class="form-control" name="email" required>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-6">--}}
{{--                        <label class="form-label">@lang('Phone')</label>--}}
{{--                        <input class="form-control" name="mobile" required>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-6">--}}
{{--                        <label class="form-label">@lang('Address')</label>--}}
{{--                        <input class="form-control" name="address" required>--}}
{{--                    </div>--}}
{{--                    <div class="col-12">--}}
{{--                        <button type="submit" class="btn btn--primary confirmBookingBtn" disabled>--}}
{{--                            <i class="la la-check-circle"></i> @lang('Book Now')--}}
{{--                        </button>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </form>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--@endsection--}}

{{--@push('style')--}}
{{--    <style>--}}
{{--        .legend-dot{display:inline-block;width:10px;height:10px;border-radius:50%}--}}
{{--        /* selection (blue) */--}}
{{--        .fc-highlight { background: rgba(59,130,246,.28) !important; }--}}
{{--    </style>--}}
{{--@endpush--}}

{{--@push('script-lib')--}}
{{--    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>--}}
{{--    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/resource-timeline@6.1.11/index.global.min.js"></script>--}}
{{--    <script src="{{ asset('assets/admin/js/moment.min.js') }}"></script>--}}
{{--    <script src="{{ asset('assets/admin/js/daterangepicker.min.js') }}"></script>--}}
{{--@endpush--}}

{{--@push('script')--}}
{{--    <script>--}}
{{--        document.addEventListener('DOMContentLoaded', function(){--}}
{{--            const displayFormat = 'MM/DD/YYYY hh:mm A';--}}
{{--            const backendFormat = 'YYYY-MM-DD hh:mm A';--}}

{{--            // === Date range picker ===--}}
{{--            const start = moment();--}}
{{--            const end   = moment().add(1, 'days');--}}

{{--            function setDateFields(el, s, e){--}}
{{--                $(el).val(s.format(displayFormat) + ' - ' + e.format(displayFormat));--}}
{{--                document.querySelector('input[name="pickup_date"]').value = s.format(backendFormat);--}}
{{--                document.querySelector('input[name="return_date"]').value = e.format(backendFormat);--}}
{{--            }--}}

{{--            $('.bookingDatePicker').daterangepicker({--}}
{{--                minDate: start,--}}
{{--                startDate: start,--}}
{{--                endDate: end,--}}
{{--                timePicker: true,--}}
{{--                timePickerIncrement: 15,--}}
{{--                autoUpdateInput: false,--}}
{{--                locale: { format: displayFormat }--}}
{{--            }, function(s, e){ setDateFields('.bookingDatePicker', s, e); });--}}

{{--            setDateFields('.bookingDatePicker', start, end);--}}

{{--            // === Build initial resources (from PHP) ===--}}
{{--            const initialResources = @json(--}}
{{--                $vehicles->map(function($v){--}}
{{--                    return [--}}
{{--                        'id'    => (string) $v->id,--}}
{{--                        'title' => trim((($v->brand->name ?? '') . ' ' . $v->name . ($v->model ? ' ('.$v->model.')' : ''))),--}}
{{--                        'extendedProps' => [--}}
{{--                            'seats' => optional($v->seater)->number,--}}
{{--                            'trans' => $v->transmission,--}}
{{--                            'fuel'  => $v->fuel_type,--}}
{{--                            'price' => $v->price,--}}
{{--                        ],--}}
{{--                    ];--}}
{{--                })->values()--}}
{{--            );--}}

{{--            // === FullCalendar init ===--}}
{{--            let slotDuration = '00:30:00';--}}
{{--            const calendarEl = document.getElementById('vehicleCalendar');--}}

{{--            const initialResources = @json($initialResources);--}}

{{--            const calendar = new FullCalendar.Calendar(calendarEl, {--}}
{{--                schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',--}}
{{--                timeZone: 'local',--}}
{{--                height: 'auto',--}}
{{--                headerToolbar: { left: 'prev,next today', center: 'title', right: 'resourceTimelineDay,resourceTimelineWeek' },--}}
{{--                initialView: 'resourceTimelineDay',--}}
{{--                resourceAreaHeaderContent: '{{ __("Vehicle") }}',--}}
{{--                resourceAreaWidth: '28%',--}}
{{--                resources: initialResources,--}}
{{--                slotDuration: slotDuration,--}}
{{--                slotMinWidth: 40,--}}
{{--                slotLabelFormat: [{ hour: 'numeric', minute: '2-digit', hour12: true }],--}}

{{--                selectable: true,--}}
{{--                selectMirror: true,--}}
{{--                selectOverlap: false,--}}
{{--                eventOverlap: false,--}}

{{--                // avoid selecting over red background events--}}
{{--                selectAllow: function(sel){--}}
{{--                    const evts = calendar.getEvents().filter(e =>--}}
{{--                        e.getResources().some(r => r.id === sel.resource.id) &&--}}
{{--                        e.display === 'background' &&--}}
{{--                        e.backgroundColor === '#ef4444' &&--}}
{{--                        Math.max(+e.start, +sel.start) < Math.min(+e.end, +sel.end)--}}
{{--                    );--}}
{{--                    return evts.length === 0;--}}
{{--                },--}}

{{--                select: function(info){--}}
{{--                    document.querySelector('input[name="vehicle_id"]').value  = info.resource.id;--}}
{{--                    document.querySelectorAll('input[name="pickup_date"]').forEach(el => el.value = moment(info.start).format(backendFormat));--}}
{{--                    document.querySelectorAll('input[name="return_date"]').forEach(el => el.value = moment(info.end).format(backendFormat));--}}
{{--                    document.querySelector('.confirmBookingBtn')?.removeAttribute('disabled');--}}
{{--                    if (window.notify) notify('success', 'Selected ' + moment(info.start).format(displayFormat) + ' → ' + moment(info.end).format(displayFormat));--}}
{{--                },--}}

{{--                eventSources: [{--}}
{{--                    url: '{{ route('admin.vehicule.agenda.events') }}',--}}
{{--                    method: 'GET',--}}
{{--                    extraParams: function(){--}}
{{--                        // include filters (brand, seater, transmission, fuel)--}}
{{--                        return Object.fromEntries(new FormData(document.querySelector('.formVehicleSearch')));--}}
{{--                    },--}}
{{--                    failure: () => window.notify && notify('error', 'Failed to load availability'),--}}
{{--                }],--}}

{{--                // pretty resource labels--}}
{{--                resourceLabelContent: function(arg){--}}
{{--                    const p = arg.resource.extendedProps || {};--}}
{{--                    const div = document.createElement('div');--}}
{{--                    div.innerHTML = `--}}
{{--                        <div class="fw-semibold">${arg.resource.title}</div>--}}
{{--                        <div class="small text-muted">--}}
{{--                            {{ __("Seats") }}: ${p.seats ?? '-'} • {{ __("Trans") }}: ${p.trans ?? '-'} • {{ __("Fuel") }}: ${p.fuel ?? '-'}--}}
{{--                        </div>`;--}}
{{--                    return { domNodes: [div] };--}}
{{--                },--}}
{{--            });--}}

{{--            calendar.render();--}}

{{--            // === Filters submit: update visibleRange + resources + events--}}
{{--            document.querySelector('.formVehicleSearch').addEventListener('submit', async function(e){--}}
{{--                e.preventDefault();--}}

{{--                // 1) adjust visible range to the picker values--}}
{{--                const pickup = moment(document.querySelector('input[name="pickup_date"]').value, backendFormat);--}}
{{--                const drop   = moment(document.querySelector('input[name="return_date"]').value, backendFormat);--}}

{{--                if (pickup.isValid() && drop.isValid() && drop.isAfter(pickup)) {--}}
{{--                    calendar.setOption('visibleRange', {--}}
{{--                        start: pickup.toISOString(),--}}
{{--                        end: drop.toISOString()--}}
{{--                    });--}}
{{--                    // switch to week if span > 1 day, else day--}}
{{--                    const spanDays = drop.diff(pickup, 'days', true);--}}
{{--                    if (spanDays >= 1) calendar.changeView('resourceTimelineWeek', pickup.toDate());--}}
{{--                    else calendar.changeView('resourceTimelineDay', pickup.toDate());--}}
{{--                }--}}

{{--                // 2) update resources by filters--}}
{{--                const params = new URLSearchParams(new FormData(this)).toString();--}}
{{--                const res = await fetch('{{ route('admin.vehicule.agenda.resources') }}?' + params);--}}
{{--                const resources = await res.json();--}}

{{--                // wipe and load resources--}}
{{--                calendar.getResources().forEach(r => r.remove());--}}
{{--                resources.forEach(r => calendar.addResource(r));--}}

{{--                // 3) refetch events--}}
{{--                calendar.refetchEvents();--}}
{{--            });--}}

{{--            // Zoom buttons--}}
{{--            document.getElementById('zoom15').onclick = () => { slotDuration = '00:15:00'; calendar.setOption('slotDuration', slotDuration); setActive('zoom15'); };--}}
{{--            document.getElementById('zoom30').onclick = () => { slotDuration = '00:30:00'; calendar.setOption('slotDuration', slotDuration); setActive('zoom30'); };--}}
{{--            document.getElementById('zoom60').onclick = () => { slotDuration = '01:00:00'; calendar.setOption('slotDuration', slotDuration); setActive('zoom60'); };--}}
{{--            function setActive(id){ ['zoom15','zoom30','zoom60'].forEach(x => document.getElementById(x).classList.toggle('active', x===id)); }--}}
{{--        });--}}
{{--    </script>--}}
{{--@endpush--}}

@php
    $initialResources = $vehicles->map(function ($v) {
        return [
            'id'    => (string) $v->id,
            'title' => trim(($v->brand->name ?? '') . ' ' . $v->name . ($v->model ? ' (' . $v->model . ')' : '')),
            'extendedProps' => [
                'seats' => optional($v->seater)->number,
                'trans' => $v->transmission,
                'fuel'  => $v->fuel_type,
                'price' => $v->price
            ]
        ];
    })->values()->all();
@endphp

@extends('admin.layouts.app')

@push('style-lib')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/resource-timeline@6.1.11/index.global.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
@endpush

@section('panel')
    {{-- === Filters === --}}
    <div class="card">
        <div class="card-body">
            <form class="formVehicleSearch">
                @csrf
                <div class="d-flex gap-3 flex-wrap">
                    {{-- Brand --}}
                    <select class="form-control select2-basic" name="brand_id">
                        <option value="">@lang('Any Brand')</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                    {{-- Seater --}}
                    <select class="form-control select2-basic" name="seater_id">
                        <option value="">@lang('Any Seater')</option>
                        @foreach($seaters as $s)
                            <option value="{{ $s->id }}">{{ $s->number }}</option>
                        @endforeach
                    </select>
                    {{-- Transmission --}}
                    <select class="form-control select2-basic" name="transmission">
                        <option value="">@lang('Any Transmission')</option>
                        @foreach($transmissions as $t)
                            <option value="{{ $t }}">{{ ucfirst($t) }}</option>
                        @endforeach
                    </select>
                    {{-- Fuel --}}
                    <select class="form-control select2-basic" name="fuel_type">
                        <option value="">@lang('Any Fuel')</option>
                        @foreach($fuelTypes as $f)
                            <option value="{{ $f }}">{{ ucfirst($f) }}</option>
                        @endforeach
                    </select>
                    {{-- Date Picker --}}
                    <input class="bookingDatePicker form-control bg--white" name="date" placeholder="@lang('Select Date')" required>
                    <input type="hidden" name="pickup_date">
                    <input type="hidden" name="return_date">
                    {{-- Search --}}
                    <button class="btn btn--primary search" type="submit">
                        <i class="la la-search"></i> @lang('Search')
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- === Agenda === --}}
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

    {{-- === Booking form === --}}
    @include('admin.booking.partials.booking_form')
@endsection

@push('style')
    <style>
        .legend-dot{display:inline-block;width:10px;height:10px;border-radius:50%}
        .fc-highlight { background: rgba(59,130,246,.28) !important; }
    </style>
@endpush

@push('script-lib')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/resource-timeline@6.1.11/index.global.min.js"></script>
    <script src="{{ asset('assets/admin/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/daterangepicker.min.js') }}"></script>
@endpush

@push('script')
    <script>
        const initialResources = @json($initialResources ?? []);
    </script>
    <script>

        document.addEventListener('DOMContentLoaded', function(){
            const displayFormat = 'MM/DD/YYYY hh:mm A';
            const backendFormat = 'YYYY-MM-DD hh:mm A';
            const start = moment();
            const end   = moment().add(1, 'days');

            function updateDateFields(el, s, e){
                $(el).val(s.format(displayFormat) + ' - ' + e.format(displayFormat));
                document.querySelector('input[name="pickup_date"]').value = s.format(backendFormat);
                document.querySelector('input[name="return_date"]').value = e.format(backendFormat);
            }

            $('.bookingDatePicker').daterangepicker({
                minDate: start,
                startDate: start,
                endDate: end,
                timePicker: true,
                timePickerIncrement: 15,
                autoUpdateInput: false,
                locale: { format: displayFormat }
            }, function(s, e){ updateDateFields('.bookingDatePicker', s, e); });
            updateDateFields('.bookingDatePicker', start, end);

            const initialResources = @json($initialResources);

            let slotDuration = '00:30:00';
            const calendar = new FullCalendar.Calendar(document.getElementById('vehicleCalendar'), {
                schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
                timeZone: 'local',
                height: 'auto',
                headerToolbar: { left: 'prev,next today', center: 'title', right: 'resourceTimelineDay,resourceTimelineWeek' },
                initialView: 'resourceTimelineDay',
                resourceAreaHeaderContent: '{{ __("Vehicle") }}',
                resourceAreaWidth: '28%',
                resources: initialResources,
                slotDuration: slotDuration,
                slotLabelFormat: [{ hour: 'numeric', minute: '2-digit', hour12: true }],
                selectable: true,
                selectOverlap: false,
                eventOverlap: false,

                resourceLabelContent: function(arg){
                    const p = arg.resource.extendedProps || {};
                    const wrap = document.createElement('div');
                    const name = document.createElement('div');
                    name.className = 'fw-semibold';
                    name.textContent = arg.resource.title;
                    const meta = document.createElement('div');
                    meta.className = 'small text-muted';
                    meta.textContent = `{{ __("Seats") }}: ${p.seats ?? '-'} • {{ __("Trans") }}: ${p.trans ?? '-'} • {{ __("Fuel") }}: ${p.fuel ?? '-'}`;
                    wrap.appendChild(name);
                    wrap.appendChild(meta);
                    return { domNodes: [wrap] };
                },

                eventSources: [{
                    url: '{{ route('admin.vehicule.agenda.events') }}',
                    method: 'GET',
                    extraParams: () => Object.fromEntries(new FormData(document.querySelector('.formVehicleSearch')))
                }],

                selectAllow: function(sel){
                    const evts = calendar.getEvents().filter(e =>
                        e.getResources().some(r => r.id === sel.resource.id) &&
                        e.backgroundColor === '#ef4444' &&
                        Math.max(+e.start, +sel.start) < Math.min(+e.end, +sel.end)
                    );
                    return evts.length === 0;
                },

                select: function(info){
                    document.querySelector('input[name="vehicle_id"]').value = info.resource.id;
                    document.querySelectorAll('input[name="pickup_date"]').forEach(el => el.value = moment(info.start).format(backendFormat));
                    document.querySelectorAll('input[name="return_date"]').forEach(el => el.value = moment(info.end).format(backendFormat));
                    document.querySelector('.confirmBookingBtn').removeAttribute('disabled');
                }
            });

            calendar.render();

            // Filters
            document.querySelector('.formVehicleSearch').addEventListener('submit', function(e){
                e.preventDefault();
                calendar.refetchEvents();
            });

            // Zoom buttons
            document.getElementById('zoom15').onclick = () => { slotDuration='00:15:00'; calendar.setOption('slotDuration', slotDuration); };
            document.getElementById('zoom30').onclick = () => { slotDuration='00:30:00'; calendar.setOption('slotDuration', slotDuration); };
            document.getElementById('zoom60').onclick = () => { slotDuration='01:00:00'; calendar.setOption('slotDuration', slotDuration); };
        });
    </script>
@endpush
