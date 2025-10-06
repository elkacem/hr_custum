@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>@lang('User')</th>
                                <th>@lang('Vehicle')</th>
                                <th>@lang('Pick | Drop Location')</th>
                                <th>@lang('Pick | Drop Time')</th>
                                <th>@lang('Price | TRX')</th>
                                <th>@lang('Status')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($bookingLogs as $log)
                                <tr>
{{--                                    <td>--}}
{{--                                        @php--}}
{{--                                            dd(__($log->user->fullname));--}}
{{--                                        @endphp--}}
{{--                                       {{ __($log->user->fullname) }}--}}
{{--                                        <br>--}}
{{--                                        <small> <a href="{{ route('admin.users.detail', $log->user_id) }}"><span>@</span>{{ $log->user->username }}</a> </small>--}}
{{--                                    </td>--}}

                                    <td>
                                        @if($log->user)
                                            {{ __($log->user->fullname) }}<br>
                                            <small>
                                                <a href="{{ route('admin.users.detail', $log->user_id) }}">
                                                    <span>@</span>{{ $log->user->username }}
                                                </a>
                                            </small>
                                        @else
                                            <span class="text--danger">@lang('User not found')</span>
                                        @endif
                                    </td>


                                    <td>{{ __($log->vehicle->name) }}</td>
                                    <td>{{ __(@$log->pickUpLocation->name) }} <br>{{ __(@$log->dropUpLocation->name) }}</td>
                                    <td>
                                        {{ showDateTime($log->pick_time) }}<br>{{ showDateTime($log->drop_time) }}
                                    </td>
                                    <td>
                                        {{ showAmount($log->price) }}<br> {{ @$log->trx }}
                                    </td>

                                    <td>
                                        @if($log->pick_time > now())
                                            <span class="badge badge--primary">@lang('Upcoming')</span>
                                        @elseif($log->pick_time < now() && $log->drop_time > now())
                                            <span class="badge badge--warning">@lang('Running')</span>
                                        @elseif($log->drop_time < now())
                                            <span class="badge badge--success">@lang('Completed')</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                    @if($bookingLogs->haspages())
                    <div class="card-footer">
                        {{ paginateLinks($bookingLogs) }}
                    </div>
                    @endif
            </div><!-- card end -->
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
<x-search-form placeholder="Vahicle|trx"/>
@endpush
