@extends('template.layouts.master')
@section('content')
    <div class="pb-60 pt-60">
        <div class="table-responsive">
            <table class="table cmn--table">
                <thead>
                    <tr>
                        <th>@lang('Vehicle')</th>
                        <th>@lang('Price | TRX')</th>
                        <th>@lang('Pick | Drop Location')</th>
                        <th>@lang('Pick | Drop Time')</th>
                        <th>@lang('Status')</th>
                    </tr>
                </thead>
                <tbody>
                        @forelse ($bookingLogs as $log)
                            <tr>
                                <td>
                                    <div>{{ __($log->vehicle->name) }}</div>
                                </td>
                                <td>
                                    <div>
                                        {{ showAmount($log->price) }}<br> <strong>{{ @$log->trx }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <i class="las la-map-marker-alt"></i>{{ __(@$log->pickUpLocation->name) }} <br>
                                        <i class="las la-map-marker"></i><strong>{{ __(@$log->dropUpLocation->name) }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="text--base">{{ showDateTime($log->pick_time) }}</span><br><strong class="text--warning">{{ showDateTime($log->drop_time) }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        @if ($log->pick_time > now())
                                            <span class="badge badge--primary">@lang('Upcoming')</span>
                                        @elseif($log->pick_time < now() && $log->drop_time > now())
                                            <span class="badge badge--warning">@lang('Running')</span>
                                        @elseif($log->drop_time < now())
                                            <span class="badge badge--success">@lang('Completed')</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center" colspan="100%">
                                    @include('template.partials.empty', ['message' => 'Vehicle Booking Not Found!'])
                                </td>
                            </tr>
                        @endforelse
                </tbody>
            </table>
        </div>

        @if ($bookingLogs->hasPages())
            {{ paginateLinks($bookingLogs) }}
        @endif

    </div>
@endsection
