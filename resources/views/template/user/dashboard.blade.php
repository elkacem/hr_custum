@extends('template.layouts.master')
@section('content')
    <div class="notice mb-4"></div>
    <div class="pb-60 pt-60">
        <div class="row justify-content-center g-4">
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="dashboard__item">
                    <div class="dashboard__thumb">
                        <i class="las la-car-side"></i>
                    </div>
                    <div class="dashboard__content">
                        <h4 class="dashboard__title">{{ $data['total_vehicle_booking'] }}</h4>
                        <span class="subtitle">@lang('Total Vehicle Booking')</span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="dashboard__item">
                    <div class="dashboard__thumb">
                        <i class="las la-hourglass-half"></i>
                    </div>
                    <div class="dashboard__content">
                        <h4 class="dashboard__title">{{ $data['upcoming_vehicle_booking'] }}</h4>
                        <span class="subtitle">@lang('Upcoming Vehicle Booking')</span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="dashboard__item">
                    <div class="dashboard__thumb">
                        <i class="las la-spinner"></i>
                    </div>
                    <div class="dashboard__content">
                        <h4 class="dashboard__title">{{ $data['running_vehicle_booking'] }}</h4>
                        <span class="subtitle">@lang('Running Vehicle Booking')</span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="dashboard__item">
                    <div class="dashboard__thumb">
                        <i class="las la-check-circle"></i>
                    </div>
                    <div class="dashboard__content">
                        <h4 class="dashboard__title">{{ $data['completed_vehicle_booking'] }}</h4>
                        <span class="subtitle">@lang('Completed Vehicle Booking')</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Dashboard -->


    <div class="pb-60">
        <div class="table-responsive">
{{--            @include('template.partials.payment_table')--}}
        </div>
    </div>
@endsection
