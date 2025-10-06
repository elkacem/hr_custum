
{{--@if($vehicles->isEmpty())--}}
{{--    <div class="alert alert-warning mb-0">@lang('No vehicles matched your filters.')</div>--}}
{{--@else--}}
{{--    <div class="table-responsive">--}}
{{--        <table class="table table--light style--two booking-table">--}}
{{--            <thead>--}}
{{--            <tr>--}}
{{--                <th>@lang('Vehicle')</th>--}}
{{--                <th>@lang('Brand')</th>--}}
{{--                <th>@lang('Seats')</th>--}}
{{--                <th class="text-center">@lang('Daily')</th>--}}
{{--                <th>@lang('Occupied in Selected Range')</th>--}}
{{--                <th class="text-right">@lang('Action')</th>--}}
{{--            </tr>--}}
{{--            </thead>--}}
{{--            <tbody>--}}
{{--            @foreach($vehicles as $v)--}}
{{--                @php--}}
{{--                    $busy   = $busyByVehicle[$v->id] ?? collect();--}}
{{--                    $isBusy = $busy->isNotEmpty();--}}
{{--                @endphp--}}
{{--                <tr class="{{ $isBusy ? 'bg--lightdanger' : '' }}">--}}
{{--                    <td>--}}
{{--                        <div class="d-flex flex-column">--}}
{{--                            <span class="fw-bold">{{ $v->name }}</span>--}}
{{--                            <small class="text-muted">--}}
{{--                                {{ $pickup->format('m/d/Y h:i A') }} → {{ $return->format('m/d/Y h:i A') }}--}}
{{--                            </small>--}}
{{--                        </div>--}}
{{--                    </td>--}}
{{--                    <td>{{ optional($v->brand)->name }}</td>--}}
{{--                    <td>{{ optional($v->seater)->number }}</td>--}}
{{--                    <td class="text-center">{{ getAmount($v->price) }} {{ __(gs()->cur_text) }}</td>--}}
{{--                    <td>--}}
{{--                        @if($busy->isEmpty())--}}
{{--                            <span class="badge badge--success">@lang('No conflicts (Available)')</span>--}}
{{--                        @else--}}
{{--                            <div class="d-flex flex-column gap-1">--}}
{{--                                @foreach($busy as $b)--}}
{{--                                    <span class="badge badge--danger">--}}
{{--                                        {{ $b['from']->format('m/d/Y h:i A') }}--}}
{{--                                        &nbsp;→&nbsp;--}}
{{--                                        {{ $b['to']->format('m/d/Y h:i A') }}--}}
{{--                                    </span>--}}
{{--                                @endforeach--}}
{{--                            </div>--}}
{{--                        @endif--}}
{{--                    </td>--}}
{{--                    <td class="text-right">--}}
{{--                        <button--}}
{{--                            class="btn btn--{{ $isBusy ? 'dark' : 'primary' }} btn-sm btn-select-dossier"--}}
{{--                            {{ $isBusy ? 'disabled' : '' }}--}}
{{--                            data-id="{{ $v->id }}"--}}
{{--                            data-name="{{ $v->name }}"--}}
{{--                            data-daily="{{ getAmount($v->price, 2, false) }}"--}}
{{--                            data-days="{{ $days }}"--}}
{{--                            type="button">--}}
{{--                            <i class="la la-check-circle"></i>--}}
{{--                            {{ $isBusy ? __('Booked') : __('Select') }}--}}
{{--                        </button>--}}
{{--                    </td>--}}
{{--                </tr>--}}
{{--            @endforeach--}}
{{--            </tbody>--}}
{{--        </table>--}}
{{--    </div>--}}
{{--@endif--}}

{{--@if($vehicles->isEmpty())--}}
{{--    <div class="alert alert-warning mb-0">@lang('No vehicles matched your filters.')</div>--}}
{{--@else--}}
{{--    <div class="mb-2 d-flex align-items-center gap-2">--}}
{{--        <span class="badge badge--success">@lang('Free')</span>--}}
{{--        <span class="badge badge--danger">@lang('Occupied')</span>--}}
{{--        <span class="text-muted ms-2">--}}
{{--        {{ $pickup->format('m/d/Y h:i A') }} → {{ $return->format('m/d/Y h:i A') }}--}}
{{--      </span>--}}
{{--    </div>--}}

{{--    <div class="agenda">--}}
{{--        --}}{{-- header row with hour ticks --}}
{{--        <div class="agenda__row agenda__row--header">--}}
{{--            <div class="agenda__label"></div>--}}
{{--            <div class="agenda__track agenda__track--header" data-total-seconds="{{ $totalSeconds }}">--}}
{{--                @foreach($ticks as $i => $t)--}}
{{--                    @php--}}
{{--                        $secFromStart = $t->diffInSeconds($pickup, false);--}}
{{--                        $pct = max(0, min(100, ($secFromStart / $totalSeconds) * 100));--}}
{{--                    @endphp--}}
{{--                    <div class="agenda__tick" style="left: {{ $pct }}%;" title="{{ $t->format('m/d h A') }}">--}}
{{--                        <span>{{ $t->format('h A') }}</span>--}}
{{--                    </div>--}}
{{--                @endforeach--}}
{{--            </div>--}}
{{--            <div class="agenda__action"></div>--}}
{{--        </div>--}}

{{--        --}}{{-- dossier rows --}}
{{--        @foreach($vehicles as $v)--}}
{{--            @php $segments = $segmentsByVehicle[$v->id] ?? collect(); @endphp--}}
{{--            <div class="agenda__row" data-dossier="{{ $v->id }}">--}}
{{--                <div class="agenda__label">--}}
{{--                    <div class="fw-bold">{{ optional($v->brand)->name }} {{ $v->name }}</div>--}}
{{--                    <div class="text-muted small">--}}
{{--                        {{ __('Seats') }}: {{ optional($v->seater)->number }} •--}}
{{--                        {{ __('Trans') }}: {{ ucfirst($v->transmission) }} •--}}
{{--                        {{ __('Fuel') }}: {{ ucfirst($v->fuel_type) }}--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="agenda__track"--}}
{{--                     data-total-seconds="{{ $totalSeconds }}"--}}
{{--                     data-start="{{ $pickup->format('Y-m-d H:i:s') }}"--}}
{{--                     data-end="{{ $return->format('Y-m-d H:i:s') }}">--}}

{{--                    --}}{{-- busy/free segments --}}
{{--                    @foreach($segments as $seg)--}}
{{--                        @php--}}
{{--                            $s = $seg['from']; $e = $seg['to'];--}}
{{--                            $left  = ($s->diffInSeconds($pickup,false) / $totalSeconds) * 100;--}}
{{--                            $width = ($e->diffInSeconds($s) / $totalSeconds) * 100;--}}
{{--                            $cls   = $seg['status'] === 'busy' ? 'seg--busy' : 'seg--free';--}}
{{--                        @endphp--}}
{{--                        <div class="seg {{ $cls }}"--}}
{{--                             data-from="{{ $s->format('Y-m-d H:i:s') }}"--}}
{{--                             data-to="{{ $e->format('Y-m-d H:i:s') }}"--}}
{{--                             style="left: {{ max(0,$left) }}%; width: {{ max(0,$width) }}%;"--}}
{{--                             title="{{ $s->format('m/d h:i A') }} → {{ $e->format('m/d h:i A') }}">--}}
{{--                        </div>--}}
{{--                    @endforeach--}}

{{--                    --}}{{-- live selection overlay (created by JS) --}}
{{--                    <div class="seg seg--selection d-none"></div>--}}
{{--                </div>--}}

{{--                <div class="agenda__action">--}}
{{--                    @php $hasBusy = $segments->contains(fn($s) => $s['status'] === 'busy'); @endphp--}}
{{--                    <button--}}
{{--                        class="btn btn--{{ $hasBusy ? 'dark' : 'primary' }} btn-sm btn-select-dossier"--}}
{{--                        {{ $hasBusy ? 'disabled' : '' }}--}}
{{--                        data-id="{{ $v->id }}"--}}
{{--                        data-name="{{ optional($v->brand)->name.' '.$v->name }}"--}}
{{--                        data-daily="{{ getAmount($v->price, 2, false) }}"--}}
{{--                        data-days="{{ $days }}"--}}
{{--                        type="button">--}}
{{--                        <i class="la la-check-circle"></i>--}}
{{--                        {{ $hasBusy ? __('Booked') : __('Select') }}--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        @endforeach--}}
{{--    </div>--}}
{{--@endif--}}

{{--@push('style')--}}
{{--    <style>--}}
{{--        .agenda { display:flex; flex-direction:column; gap:.75rem; }--}}
{{--        .agenda__row { display:grid; grid-template-columns:320px 1fr auto; gap:.75rem; align-items:center; }--}}
{{--        .agenda__row--header { align-items:end; }--}}
{{--        .agenda__label { line-height:1.15; }--}}
{{--        .agenda__action { white-space:nowrap; }--}}

{{--        .agenda__track {--}}
{{--            position:relative; height:40px; border-radius:10px;--}}
{{--            background:#f6f8fb; overflow:hidden; border:1px solid rgba(0,0,0,.06);--}}
{{--        }--}}
{{--        .agenda__track--header {--}}
{{--            height:28px; background:transparent; border:none; overflow:visible;--}}
{{--        }--}}

{{--        /* hour ticks */--}}
{{--        .agenda__tick {--}}
{{--            position:absolute; top:-22px; transform:translateX(-50%);--}}
{{--            font-size:11px; color:#6b7280; pointer-events:none;--}}
{{--        }--}}
{{--        .agenda__tick::after {--}}
{{--            content:""; position:absolute; left:50%; bottom:-6px; transform:translateX(-50%);--}}
{{--            width:1px; height:12px; background:rgba(0,0,0,.1);--}}
{{--        }--}}

{{--        /* segments */--}}
{{--        .seg { position:absolute; top:0; bottom:0; }--}}
{{--        .seg--free { background:rgba(16,185,129,.22); }--}}
{{--        .seg--busy { background:rgba(239,68,68,.45); }--}}

{{--        /* selection overlay */--}}
{{--        .seg--selection {--}}
{{--            background:rgba(59,130,246,.25);  /* blue */--}}
{{--            outline:2px solid rgba(59,130,246,.55);--}}
{{--            pointer-events:none;--}}
{{--        }--}}

{{--        @media (max-width: 992px) {--}}
{{--            .agenda__row { grid-template-columns:1fr; }--}}
{{--        }--}}
{{--    </style>--}}
{{--@endpush--}}

{{--@push('script')--}}
{{--    <script>--}}
{{--        (function($){--}}
{{--            "use strict";--}}

{{--            // Snap any second count to N-minute increments (default 15)--}}
{{--            function snapSeconds(sec, stepMinutes = 15) {--}}
{{--                const step = stepMinutes * 60;--}}
{{--                return Math.round(sec / step) * step;--}}
{{--            }--}}

{{--            // check overlap with busy blocks--}}
{{--            function overlapsBusy(startSec, endSec, $track) {--}}
{{--                const trackStart = new Date($track.data('start')).getTime() / 1000;--}}
{{--                let has = false;--}}
{{--                $track.find('.seg--busy').each(function(){--}}
{{--                    const bFrom = new Date($(this).data('from')).getTime()/1000;--}}
{{--                    const bTo   = new Date($(this).data('to')).getTime()/1000;--}}
{{--                    const bs = bFrom - trackStart, be = bTo - trackStart;--}}
{{--                    if (Math.max(bs, startSec) < Math.min(be, endSec)) { has = true; return false; }--}}
{{--                });--}}
{{--                return has;--}}
{{--            }--}}

{{--            // format to your backend hidden input (YYYY-MM-DD hh:mm A)--}}
{{--            function toBackend(dt) {--}}
{{--                const pad = n => n.toString().padStart(2,'0');--}}
{{--                let h = dt.getHours(), ampm = 'AM';--}}
{{--                if (h === 0) { h = 12; ampm = 'AM'; }--}}
{{--                else if (h === 12) { ampm = 'PM'; }--}}
{{--                else if (h > 12) { ampm = 'PM'; h -= 12; }--}}
{{--                return dt.getFullYear() + '-' +--}}
{{--                    pad(dt.getMonth()+1) + '-' +--}}
{{--                    pad(dt.getDate()) + ' ' +--}}
{{--                    pad(h) + ':' + pad(dt.getMinutes()) + ' ' + ampm;--}}
{{--            }--}}

{{--            // Drag-to-select per dossier row--}}
{{--            let dragging = null; // { $track, startPx, startSec }--}}

{{--            $(document).on('mousedown', '.agenda__track', function(e){--}}
{{--                // ignore if clicking on a BUSY segment--}}
{{--                if ($(e.target).closest('.seg--busy').length) return;--}}

{{--                const $track = $(this);--}}
{{--                const rect   = $track[0].getBoundingClientRect();--}}
{{--                const x      = e.clientX - rect.left;--}}
{{--                const width  = rect.width;--}}
{{--                const total  = Number($track.data('total-seconds'));--}}

{{--                let startRatio = Math.max(0, Math.min(1, x / width));--}}
{{--                let startSec   = snapSeconds(startRatio * total);--}}

{{--                dragging = { $track, startPx: x, startSec };--}}
{{--                const $sel = $track.find('.seg--selection').removeClass('d-none');--}}
{{--                $sel.css({ left: (startSec/total*100)+'%', width: '0%' });--}}

{{--                e.preventDefault();--}}
{{--            });--}}

{{--            $(document).on('mousemove', function(e){--}}
{{--                if (!dragging) return;--}}
{{--                const {$track, startPx, startSec} = dragging;--}}
{{--                const rect  = $track[0].getBoundingClientRect();--}}
{{--                const curPx = Math.max(0, Math.min(rect.width, e.clientX - rect.left));--}}
{{--                const total = Number($track.data('total-seconds'));--}}

{{--                let a = startPx, b = curPx; if (b < a) [a,b]=[b,a];--}}
{{--                let startRatio = a / rect.width, endRatio = b / rect.width;--}}
{{--                let sSec = snapSeconds(startRatio * total);--}}
{{--                let eSec = snapSeconds(endRatio   * total);--}}
{{--                if (eSec === sSec) eSec = sSec + 15*60; // minimum 15 minutes--}}

{{--                // prevent overlapping busy segments visually (just clamp by not growing across busy)--}}
{{--                if (overlapsBusy(sSec, eSec, $track)) {--}}
{{--                    // shrink end until no overlap--}}
{{--                    while (eSec > sSec && overlapsBusy(sSec, eSec, $track)) eSec -= 15*60;--}}
{{--                    if (eSec <= sSec) { eSec = sSec; }--}}
{{--                }--}}

{{--                const $sel = $track.find('.seg--selection');--}}
{{--                const leftPct  = (sSec / total) * 100;--}}
{{--                const widthPct = Math.max(0, (eSec - sSec) / total * 100);--}}
{{--                $sel.css({ left: leftPct+'%', width: widthPct+'%' });--}}
{{--            });--}}

{{--            $(document).on('mouseup', function(e){--}}
{{--                if (!dragging) return;--}}
{{--                const {$track, startPx} = dragging;--}}
{{--                dragging = null;--}}

{{--                const rect  = $track[0].getBoundingClientRect();--}}
{{--                const curPx = Math.max(0, Math.min(rect.width, e.clientX - rect.left));--}}
{{--                const total = Number($track.data('total-seconds'));--}}
{{--                const trackStart = new Date($track.data('start')).getTime() / 1000;--}}

{{--                let a = startPx, b = curPx; if (b < a) [a,b]=[b,a];--}}
{{--                let sSec = snapSeconds((a/rect.width) * total);--}}
{{--                let eSec = snapSeconds((b/rect.width) * total);--}}
{{--                if (eSec <= sSec) eSec = sSec + 15*60;--}}

{{--                // final overlap check--}}
{{--                if (overlapsBusy(sSec, eSec, $track)) {--}}
{{--                    // cancel selection--}}
{{--                    $track.find('.seg--selection').addClass('d-none').css({width:0});--}}
{{--                    return;--}}
{{--                }--}}

{{--                // Convert seconds offset to absolute datetimes--}}
{{--                const startUnix = (trackStart + sSec) * 1000;--}}
{{--                const endUnix   = (trackStart + eSec) * 1000;--}}
{{--                const startDate = new Date(startUnix);--}}
{{--                const endDate   = new Date(endUnix);--}}

{{--                // fill hidden inputs used by your booking form--}}
{{--                $('[name="pickup_date"]').val(toBackend(startDate));--}}
{{--                $('[name="return_date"]').val(toBackend(endDate));--}}

{{--                // set dossier id from row--}}
{{--                const vehicleId = $track.closest('.agenda__row').data('dossier');--}}
{{--                $('[name="vehicle_id"]').val(vehicleId);--}}

{{--                // show a toast (or your notify)--}}
{{--                if (typeof notify === 'function') {--}}
{{--                    notify('success', 'Selected ' + toBackend(startDate) + ' → ' + toBackend(endDate));--}}
{{--                }--}}

{{--                // optionally enable the "Book Now" button--}}
{{--                $('.confirmBookingBtn').prop('disabled', false);--}}
{{--            });--}}

{{--        })(jQuery);--}}
{{--    </script>--}}
{{--@endpush--}}

@php
    // Build a single time window from requested dates
    $gridStart = (clone $pickup)->startOfHour();
    $gridEnd   = (clone $return)->endOfHour();
    $totalSeconds = max(1, $gridStart->diffInSeconds($gridEnd));

    // Hour ticks
    $ticks = [];
    $it = $gridStart->copy();
    while ($it < $gridEnd) {
        $offset = $gridStart->diffInSeconds($it);
        $ticks[] = [
            'left'  => ($offset / $totalSeconds) * 100,
            'label' => $it->format('h A'),
        ];
        $it->addHour();
    }

    // Day markers
    $daysHeader = [];
    $d = $gridStart->copy()->startOfDay();
    while ($d < $gridEnd) {
        if ($d >= $gridStart && $d <= $gridEnd) {
            $offset = $gridStart->diffInSeconds($d);
            $daysHeader[] = [
                'left'  => ($offset / $totalSeconds) * 100,
                'label' => $d->format('M d'),
            ];
        }
        $d->addDay();
    }
@endphp

<div class="agenda">
    <div class="agenda__bar">
        <div class="agenda__legend">
            <span class="legend-dot legend-red"></span><span>@lang('Reserved')</span>
            <span class="legend-dot legend-green ms-3"></span><span>@lang('Available')</span>
            <span class="legend-dot legend-blue ms-3"></span><span>@lang('Selected')</span>
        </div>

        <div class="agenda__controls">
            <button class="btn btn-sm agenda__nav" data-dir="-1" type="button" title="Previous"><i class="la la-angle-left"></i></button>
            <strong>{{ $pickup->format('M d, Y h:i A') }}</strong>
            <span class="mx-1">→</span>
            <strong>{{ $return->format('M d, Y h:i A') }}</strong>
            <button class="btn btn-sm agenda__nav" data-dir="1" type="button" title="Next"><i class="la la-angle-right"></i></button>

            <div class="btn-group ms-3" role="group" aria-label="Zoom">
                <button class="btn btn-sm btn-outline-secondary agenda__zoom" data-step="15" type="button">15m</button>
                <button class="btn btn-sm btn-outline-secondary agenda__zoom active" data-step="30" type="button">30m</button>
                <button class="btn btn-sm btn-outline-secondary agenda__zoom" data-step="60" type="button">60m</button>
            </div>
        </div>
    </div>

    <div class="agenda__header">
        <div class="agenda__left sticky">@lang('Vehicle')</div>
        <div class="agenda__right">
            <div class="agenda__track agenda__track--header"
                 data-start="{{ $gridStart->toIso8601String() }}"
                 data-total-seconds="{{ $totalSeconds }}">
                @foreach($daysHeader as $dh)
                    <div class="agenda__day" style="left: {{ $dh['left'] }}%">{{ $dh['label'] }}</div>
                @endforeach
                @foreach($ticks as $t)
                    <div class="agenda__tick" style="left: {{ $t['left'] }}%">{{ $t['label'] }}</div>
                @endforeach
                <div class="agenda__now d-none"></div>
            </div>
        </div>
    </div>

    <div class="agenda__body">
        <div class="agenda__left sticky">
            @forelse($vehicles as $v)
                <div class="agenda__cell">
                    <div class="fw-semibold">{{ $v->brand->name ?? '' }} {{ $v->name }} @if($v->model) ({{ $v->model }}) @endif</div>
                    <div class="small text-muted">
                        @lang('Seats'): {{ optional($v->seater)->number }}
                        • @lang('Trans'): {{ ucfirst($v->transmission) }}
                        • @lang('Fuel'): {{ ucfirst($v->fuel_type) }}
                    </div>
                </div>
            @empty
                <div class="agenda__cell">@lang('No vehicles')</div>
            @endforelse
        </div>

        <div class="agenda__right agenda__scroll" id="agendaScroll">
            <div class="agenda__rows">
                @forelse($vehicles as $v)
                    @php $rowBusy = $busyByVehicle[$v->id] ?? collect(); @endphp
                    <div class="agenda__row" data-vehicle="{{ $v->id }}">
                        <div class="agenda__track"
                             data-start="{{ $gridStart->toIso8601String() }}"
                             data-total-seconds="{{ $totalSeconds }}">
                            <div class="seg seg--free" style="left:0; right:0;"></div>

                            @foreach($rowBusy as $b)
                                @php
                                    $bStart = $b['from']->copy();
                                    $bEnd   = $b['to']->copy();
                                    if ($bStart < $gridStart) $bStart = $gridStart->copy();
                                    if ($bEnd   > $gridEnd)   $bEnd   = $gridEnd->copy();
                                    if ($bEnd <= $bStart) continue;

                                    $startOff = $gridStart->diffInSeconds($bStart);
                                    $endOff   = $gridStart->diffInSeconds($bEnd);
                                    $leftPct  = max(0, min(100, ($startOff / $totalSeconds) * 100));
                                    $widthPct = max(0, min(100, (($endOff - $startOff) / $totalSeconds) * 100));
                                @endphp
                                <div class="seg seg--busy"
                                     data-from="{{ $b['from']->toIso8601String() }}"
                                     data-to="{{ $b['to']->toIso8601String() }}"
                                     title="{{ $b['from']->format('m/d/Y h:i A') }} → {{ $b['to']->format('m/d/Y h:i A') }}"
                                     style="left: {{ $leftPct }}%; width: {{ $widthPct }}%;"></div>
                            @endforeach

                            <div class="seg seg--selection d-none"></div>
                        </div>
                    </div>
                @empty
                    <div class="agenda__row"><div class="agenda__track"></div></div>
                @endforelse
            </div>
        </div>
    </div>
</div>
