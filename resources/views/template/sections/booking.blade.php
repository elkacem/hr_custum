{{--@php--}}
{{--    $booking = getContent('booking.content', true);--}}
{{--    $brands = \App\Models\Brand::active()->whereHas('vehicles')->orderBy('name')->get();--}}
{{--    $seaters = \App\Models\Seater::active()->whereHas('vehicles')->orderBy('number', 'asc')->get();--}}
{{--@endphp--}}

{{--<section class="book-section pt-120 pb-120 bg--section">--}}
{{--    <div class="container">--}}
{{--        <div class="book__wrapper  bg--section">--}}
{{--            <h4 class="book__title">{{ __($booking->data_values->heading) }}</h4>--}}
{{--            <form class="book--form row gx-3 gy-4 g-md-4" action="{{ route('vehicles.filter') }}" method="get">--}}
{{--                <div class="col-md-4 col-sm-6">--}}
{{--                    <div class="form-group">--}}
{{--                        <label for="car-type" class="form--label">--}}
{{--                            <i class="las la-car"></i> @lang('Select Brand')--}}
{{--                        </label>--}}
{{--                        <select name="brand_id" id="car-type" class="form-control form--control select2">--}}
{{--                            <option value="" disabled selected>@lang('Select One')</option>--}}
{{--                            @foreach ($brands as $brand)--}}
{{--                                <option value="{{ $brand->id }}">{{ __(@$brand->name) }}</option>--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-md-4 col-sm-6">--}}
{{--                    <div class="form-group">--}}
{{--                        <label for="pick-point" class="form--label">--}}
{{--                            <i class="las la-couch"></i> @lang('Number Of Seats')--}}
{{--                        </label>--}}
{{--                        <select name="seater_id" id="pick-point" class="form-control form--control select2">--}}
{{--                            <option value="" disabled selected>@lang('Select One')</option>--}}
{{--                            @foreach ($seaters as $seat)--}}
{{--                                <option value="{{ $seat->id }}">{{ __(@$seat->number) }} {{ __('Seater') }}</option>--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-md-4 col-sm-6">--}}
{{--                    <div class="form-group">--}}
{{--                        <label class="form--label">--}}
{{--                            <i class="las la-tags"></i> @lang('Vehicle Model')--}}
{{--                        </label>--}}
{{--                        <input type="text" name="model" class="form-control form--control"--}}
{{--                               placeholder="@lang('Sedan, SUV ...')">--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-md-4 col-sm-6">--}}
{{--                    <div class="form-group">--}}
{{--                        <label for="start-datse" class="form--label">--}}
{{--                            <span class="text--base">{{ gs('cur_sym') }}</span> @lang('Min Price')--}}
{{--                        </label>--}}
{{--                        <input type="text" placeholder="@lang('Min Price')" name="min_price" id="start-datse"--}}
{{--                               autocomplete="off" class="form-control form--control">--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-md-4 col-sm-6">--}}
{{--                    <div class="form-group">--}}
{{--                        <label class="form--label">--}}
{{--                            <span class="text--base">{{ gs('cur_sym') }}</span> @lang('Max Price')--}}
{{--                        </label>--}}
{{--                        <input type="text" placeholder="@lang('Max Price')" name="max_price" autocomplete="off"--}}
{{--                               class="form-control form--control">--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-md-4 col-sm-6">--}}
{{--                    <div class="form-group">--}}
{{--                        <label class="form--label d-none d-sm-block">&nbsp;</label>--}}
{{--                        <button class="cmn--btn form--control bg--base w-100 justify-content-center"--}}
{{--                                type="submit"> <i class="las la-filter"></i> @lang('Filter')</button>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </form>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</section>--}}

{{--@php--}}
{{--    $booking = getContent('booking.content', true);--}}
{{--    $brands = \App\Models\Brand::active()->whereHas('vehicles')->orderBy('name')->get();--}}
{{--    $seaters = \App\Models\Seater::active()->whereHas('vehicles')->orderBy('number', 'asc')->get();--}}
{{--    $locations = \App\Models\Location::active()->orderBy('name')->get();--}}
{{--@endphp--}}

{{--<section class="book-section pt-120 pb-120 bg--section">--}}
{{--    <div class="container">--}}
{{--        <div class="book__wrapper  bg--section">--}}
{{--            <h4 class="book__title">{{ __($booking->data_values->heading) }}</h4>--}}
{{--            <form class="book--form row gx-3 gy-4 g-md-4" method="get" action="{{ route('vehicles.vehicles.available') }}" >--}}
{{--            <form class="book--form row gx-3 gy-4 g-md-4" method="post" >--}}
{{--                @csrf--}}

{{--                <div class="col-md-6 col-sm-6">--}}
{{--                    <div class="form-group">--}}
{{--                        <label for="pick-point" class="form--label">--}}
{{--                            <i class="las la-street-view"></i> @lang('Pick Up Point')--}}
{{--                        </label>--}}
{{--                        <select name="pick_location" id="pick-point" class="form-control form--control select2" required>--}}
{{--                            <option value="" selected disabled>@lang('Pick up point')</option>--}}
{{--                            @foreach ($locations as $location)--}}
{{--                                <option value="{{ $location->id }}">{{ @$location->name }}</option>--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="col-md-6 col-sm-6">--}}
{{--                    <div class="form-group">--}}
{{--                        <label for="drop-point" class="form--label">--}}
{{--                            <i class="las la-street-view"></i> @lang('Drop of Point')--}}
{{--                        </label>--}}
{{--                        <select name="drop_location" id="drop-point" class="form-control form--control select2" required>--}}
{{--                            <option value="" selected disabled>@lang('Drop of Point')</option>--}}
{{--                            @foreach ($locations as $location)--}}
{{--                                <option value="{{ $location->id }}">{{ @$location->name }}</option>--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="col-md-6 col-sm-6">--}}
{{--                    <div class="form-group">--}}
{{--                        <label for="dateAndTimePicker" class="form--label">--}}
{{--                            <i class="las la-calendar-alt"></i> @lang('Pick Up Date & Time')--}}
{{--                        </label>--}}
{{--                        <input type="text" name="pick_time" placeholder="@lang('Pick Up Date & Time')" id="dateAndTimePicker" autocomplete="off" class="form-control form--control pick_time" required>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="col-md-6 col-sm-6">--}}
{{--                    <div class="form-group">--}}
{{--                        <label for="dateAndTimePicker2" class="form--label">--}}
{{--                            <i class="las la-calendar-alt"></i> @lang('Drop of Date & Time')--}}
{{--                        </label>--}}
{{--                        <input type="text" name="drop_time" placeholder="@lang('Drop of Date & Time')" id="dateAndTimePicker2" autocomplete="off" class="form-control form--control" required>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="col-12">--}}
{{--                    <div class="form-group">--}}
{{--                        <button class="btn cmn--btn form--control bg--base w-100" type="submit">--}}
{{--                            <i class="las la-search"></i> @lang('Search Available Cars')--}}
{{--                        </button>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </form>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</section>--}}

{{--@push('script-lib')--}}
{{--    <script src="{{ asset('assets/admin/js/moment.min.js') }}"></script>--}}
{{--    <script src="{{ asset('assets/admin/js/daterangepicker.min.js') }}"></script>--}}
{{--@endpush--}}

{{--@push('style-lib')--}}
{{--    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">--}}
{{--@endpush--}}

{{--@push('script')--}}
{{--    <script>--}}
{{--        (function($) {--}}
{{--            "use strict";--}}

{{--            $('input[name="pick_time"], input[name="drop_time"]').daterangepicker({--}}
{{--                singleDatePicker: true,--}}
{{--                showDropdowns: true,--}}
{{--                timePicker: true,--}}
{{--                timePicker24Hour: false,--}}
{{--                startDate: moment().hour(8).minute(0),--}}
{{--                minYear: 2024,--}}
{{--                maxYear: parseInt(moment().format('YYYY')) + 10,--}}
{{--                locale: {--}}
{{--                    format: 'YYYY-MM-DD hh:mm A'--}}
{{--                }--}}
{{--            }, function(start, end, label) {--}}
{{--                $(this.element).val(start.format('Y-MM-DD hh:mm A'));--}}
{{--            });--}}

{{--        })(jQuery);--}}
{{--    </script>--}}
{{--@endpush--}}

{{--@php--}}
{{--    $booking = getContent('booking.content', true);--}}
{{--    $brands = \App\Models\Brand::active()->whereHas('vehicles')->orderBy('name')->get();--}}
{{--    $seaters = \App\Models\Seater::active()->whereHas('vehicles')->orderBy('number', 'asc')->get();--}}
{{--    $locations = \App\Models\Location::active()->orderBy('name')->get();--}}

{{--@endphp--}}

{{--@extends('template.layouts.frontend')--}}

{{--@section('content')--}}

{{--    <div class="single-section pt-120 pb-120 bg--section">--}}
{{--        <div class="container">--}}
{{--            <h4 class="mb-4">@lang('Search for a dossier')</h4>--}}
{{--            <div class="row gy-5 justify-content-center">--}}
{{--                <div class="col-lg-8">--}}
{{--                    <div class="book__wrapper bg--body border--dashed mb-4">--}}
{{--                        --}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--@endsection--}}

{{--@push('script-lib')--}}
{{--    <script src="{{ asset('assets/admin/js/moment.min.js') }}"></script>--}}
{{--    <script src="{{ asset('assets/admin/js/daterangepicker.min.js') }}"></script>--}}
{{--@endpush--}}

{{--@push('style-lib')--}}
{{--    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">--}}
{{--@endpush--}}

{{--@push('script')--}}
{{--    <script>--}}
{{--        (function($) {--}}
{{--            "use strict";--}}

{{--            $('input[name="pick_time"], input[name="drop_time"]').daterangepicker({--}}
{{--                singleDatePicker: true,--}}
{{--                showDropdowns: true,--}}
{{--                timePicker: true,--}}
{{--                timePicker24Hour: false,--}}
{{--                startDate: moment().hour(8).minute(0),--}}
{{--                minYear: parseInt(moment().format('YYYY')),--}}
{{--                maxYear: parseInt(moment().format('YYYY')) + 10,--}}
{{--                locale: {--}}
{{--                    format: 'Y-MM-DD hh:mm A'--}}
{{--                }--}}
{{--            }, function(start, end, label) {--}}
{{--                $(this.element).val(start.format('Y-MM-DD hh:mm A'));--}}
{{--            });--}}

{{--        })(jQuery);--}}
{{--    </script>--}}
{{--@endpush--}}
