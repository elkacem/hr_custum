@extends('template.layouts.frontend')
@section('content')

    <div class="single-section pt-120 pb-120 bg--section">
        <div class="container">
            <h4 class="mb-4">@lang('You have selected this car')</h4>
            <div class="row gy-5">
                <div class="col-lg-5">
                    <div class="slider-top owl-theme owl-carousel border--dashed">
                        @foreach ($vehicle->images ?? [] as $image)
                            <div class="car__rental-thumb w-100 bg--body p-0">
                                <img src="{{ getImage(getFilePath('dossier') . '/' . @$image, getFileSize('dossier')) }}" alt="">
                            </div>
                        @endforeach
                    </div>
                    <div class="slider-bottom owl-theme owl-carousel mt-4">
                        @foreach ($vehicle->images ?? [] as $image)
                            <div class="rental__thumbnails bg--body">
                                <img src="{{ getImage(getFilePath('dossier') . '/' . @$image, getFileSize('dossier')) }}" alt="">
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="book__wrapper bg--body border--dashed mb-4">
                        <form class="book--form row gx-3 gy-4 g-md-4" method="post" action="{{ route('booking.guest.book.check', $vehicle->id) }}">
                            @csrf

                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="pick-point" class="form--label">
                                        <i class="las la-street-view"></i> @lang('Pick Up Point')
                                    </label>
                                    <select name="pick_location" id="pick-point" class="form-control form--control select2" required>
{{--                                        <option value="" selected disabled>@lang('Pick up point')</option>--}}
                                        @foreach ($locations as $location)
                                            <option value="{{ $location->id }}">{{ @$location->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label class="form--label">
                                        <i class="las la-street-view"></i> @lang('Drop of Point')
                                    </label>
                                    <select name="drop_location" id="drop-point" class="form-control form--control select2" required>
{{--                                        <option value="" selected disabled>@lang('Drop of Point')</option>--}}
                                        @foreach ($locations as $location)
                                            <option value="{{ $location->id }}">{{ @$location->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="start-date" class="form--label">
                                        <i class="las la-calendar-alt"></i> @lang('Pick Up Date & Time')
                                    </label>
                                    <input type="text" name="pick_time" placeholder="@lang('Pick Up Date & Time')" id="dateAndTimePicker" autocomplete="off" data-position="top left" class="form-control form--control pick_time" required>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label class="form--label">
                                        <i class="las la-calendar-alt"></i> @lang('Drop of Date & Time')
                                    </label>
                                    <input type="text" name="drop_time" placeholder="@lang('Drop of Date & Time')" id="dateAndTimePicker2" autocomplete="off" data-position="top left" class="form-control form--control" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <button class="btn cmn--btn form--control bg--base w-100" type="submit">@lang('Book Now')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('style')
    <style>
        /* Booking form container background and font */
        .book__wrapper {
            background-color: #ffffff !important;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #222;
        }

        /* Input and select field styling without changing size */
        .form--control {
            background-color: #ffffff !important;
            color: #333 !important;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-family: inherit;
        }

        .form--control::placeholder {
            color: #999 !important;
            opacity: 1;
        }

        .form--control:focus {
            border-color: #007bff;
            box-shadow: none;
            outline: none;
        }

        /* Select2 custom appearance */
        .select2-container .select2-selection--single {
            background-color: #ffffff !important;
            color: #333 !important;
            border: 1px solid #ccc !important;
            border-radius: 6px;
            font-family: inherit;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #333 !important;
        }

        /* Labels */
        .form--label {
            color: #222;
            font-weight: 600;
            font-size: 14px;
        }

        /* Button appearance only */
        .btn.cmn--btn {
            background-color: #007bff !important;
            color: #fff !important;
            font-weight: 600;
            border-radius: 6px;
            font-family: inherit;
            transition: background-color 0.3s ease;
        }

        .btn.cmn--btn:hover {
            background-color: #0056b3 !important;
        }

        /* Headings */
        .single-section h4 {
            color: #222;
            font-weight: 700;
            font-size: 22px;
        }
    </style>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/daterangepicker.min.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
@endpush
@push('script')
    <script>
        (function($) {
            "use strict"
            $(document).on('click', '.plan_modal', function() {
                var url = $(this).data('url');
                $('.planForm').attr('action', url);
            });

            $('.select2modal').select2({
                dropdownParent: '#planModal'
            });
            $('input[name="pick_time"], input[name="drop_time"]').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                timePicker: true,
                timePicker24Hour: true,
                timePickerIncrement: 15,
                startDate: moment().hour(8).minute(0), // Set initial time to 08:00
                minYear: 2024,
                maxYear: parseInt(moment().format('YYYY')) + 10,
                // locale: {
                //     format: 'Y-MM-DD hh:mm A'
                // }
                locale: {
                    format: 'YYYY-MM-DD hh:mm A',
                    daysOfWeek: ['Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa'],
                    monthNames: [
                        'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
                        'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
                    ],
                    firstDay: 1,
                    applyLabel: "Appliquer",
                    cancelLabel: "Annuler"
                }
            }, function(start, end, label) {
                console.log("Selected start date: ", start.format('Y-MM-DD hh:mm A'));
                $(this.element).val(start.format('Y-MM-DD hh:mm A'));
            });
        })(jQuery)
    </script>

@endpush
