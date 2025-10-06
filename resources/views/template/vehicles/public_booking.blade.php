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
                    <div class="book__wrapper custom-white-form border--dashed mb-4">
                        <form class="book--form row gx-3 gy-4 g-md-4" action="{{ route('booking.guest.submit', $vehicle->id) }}" method="post">
                            @csrf
                            <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">
                            <input type="hidden" name="pick_time" value="{{ $pick }}">
                            <input type="hidden" name="drop_time" value="{{ $drop }}">
                            <input type="hidden" name="pick_location" value="{{ $pickup }}">
                            <input type="hidden" name="drop_location" value="{{ $dropoff }}">

                            <div class="col-12">
                                <div class="booking-summary bg--light p-4 rounded border mb-4">
                                    <h5 class="mb-3 text--base">@lang('Your Booking Details')</h5>
                                    <div class="row gy-2">
                                        <div class="col-md-6">
                                            <strong>@lang('Pickup Location'):</strong><br>
                                            {{ optional(\App\Models\Location::find($pickup))->name }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>@lang('Drop-off Location'):</strong><br>
                                            {{ optional(\App\Models\Location::find($dropoff))->name }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>@lang('Pickup Time'):</strong><br>
                                            {{ \Carbon\Carbon::parse($pick)->format('d M Y - h:i A') }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>@lang('Drop-off Time'):</strong><br>
                                            {{ \Carbon\Carbon::parse($drop)->format('d M Y - h:i A') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- User Info --}}
                            <div class="form-section rounded p-3 mb-4">
                                <h5 class="mb-3 text--base"><i class="las la-user-circle me-1"></i> @lang('Your Information')</h5>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <input type="text" name="firstname" class="form-control form--control" placeholder="@lang('First Name')" value="{{ old('firstname') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="lastname" class="form-control form--control" placeholder="@lang('Last Name')" value="{{ old('lastname') }}" required>
                                    </div>

                                    <div class="col-md-6">
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
                                    <div class="col-md-6">
                                        <label class="form--label">@lang('Mobile')</label>
                                        <div class="input-group">
                                            <span class="input-group-text mobile-code"></span>
                                            <input name="mobile_code" type="hidden" value="{{ old('mobile_code') }}">
                                            <input name="country_code" type="hidden" value="{{ old('country_code') }}">
                                            <input class="form-control form--control" name="mobile" type="number" value="{{ old('mobile') }}" required>
                                        </div>
                                        <small class="text--danger mobileExist"></small>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="username" class="form-control form--control" placeholder="@lang('Username')" value="{{ old('username') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="email" name="email" class="form-control form--control checkUser" placeholder="@lang('Email')" value="{{ old('email') }}" required>
                                    </div>
                                    <div class="col-md-12">
{{--                                        <small class="text--danger emailExist d-block"></small>--}}
                                        <div class="login-suggestion text--warning text-sm mt-1" style="display: none;">
                                            @lang("This email is already registered. If it's yours, please")
                                            <a href="{{ route('user.login') }}" class="text--base fw-bold">@lang('log in here')</a>.
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="password" name="password" class="form-control form--control" placeholder="@lang('Password')" required>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="password" name="password_confirmation" class="form-control form--control" placeholder="@lang('Confirm Password')" required>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="address" class="form-control form--control" placeholder="@lang('Address')" value="{{ old('address') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="city" class="form-control form--control" placeholder="@lang('City')" value="{{ old('city') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="state" class="form-control form--control" placeholder="@lang('State')" value="{{ old('state') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="zip" class="form-control form--control" placeholder="@lang('Zip Code')" value="{{ old('zip') }}">
                                    </div>
                                </div>
                            </div>

                            {{-- Flight Info --}}
                            <div class="form-section rounded p-3 mb-4 bg--light">
                                <h5 class="mb-3 text--base"><i class="las la-plane-departure me-1"></i> @lang('Flight Information')</h5>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <input type="text" name="flight_number" class="form-control form--control" placeholder="@lang('N\u00b0 de vol')" value="{{ old('flight_number') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="provenance" class="form-control form--control" placeholder="@lang('Provenance')" value="{{ old('provenance') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="airline_company" class="form-control form--control" placeholder="@lang('Compagnie a\u00e9rienne')" value="{{ old('airline_company') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-3">

                                {{-- Custom Certification Checkboxes --}}
                                <div class="certification-box bg--light border rounded p-3 mb-3">
                                    <h6 class="text--base mb-3">@lang('Je certifie que:')</h6>

                                    <div class="form-check mb-2">
                                        <input type="checkbox" class="form-check-input" id="cert1" name="cert_infos_correctes" required>
                                        <label class="form-check-label" for="cert1">
                                            @lang('Ces informations sont correctes')
                                        </label>
                                    </div>

                                    <div class="form-check mb-2">
                                        <input type="checkbox" class="form-check-input" id="cert2" name="cert_conditions" required>
                                        <label class="form-check-label" for="cert2">
                                            @lang("Avoir consulté les conditions d'utilisation")
                                        </label>
                                    </div>

                                    <div class="form-check mb-2">
                                        <input type="checkbox" class="form-check-input" id="cert3" name="cert_age_min" required>
                                        <label class="form-check-label" for="cert3">
                                            @lang('Avoir 30 ans minimum')
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="cert4" name="cert_permis_5ans" required>
                                        <label class="form-check-label" for="cert4">
                                            @lang('Minimum 5 ans de permis de conduire')
                                        </label>
                                    </div>
                                </div>

                                {{-- Existing Agreement (Terms & Conditions Links) --}}
                                @if (gs('agree'))
                                    @php $policyPages = getContent('policy_pages.element', false, orderById: true); @endphp
                                    <div class="form-check pb-3">
                                        <input type="checkbox" class="form-check-input" id="agree" name="agree" @checked(old('agree')) required>
                                        <label class="form-check-label" for="agree">
                                            @lang('I agree with')
                                            @foreach ($policyPages as $policy)
                                                <a href="{{ route('policy.pages', $policy->slug) }}" target="_blank" class="text--base">
                                                    {{ __($policy->data_values->title) }}
                                                </a>@if (!$loop->last),@endif
                                            @endforeach
                                        </label>
                                    </div>
                                @endif

                                {{-- Submit Button --}}
                                <button class="btn btn-book-now w-100" type="submit">
                                    <i class="las la-car-side me-1"></i> @lang('Book Now')
                                </button>
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
        .form-section {
            background-color: #fdfdfd;
            border: 1px solid #e5e5e5;
        }
        .form-section h5 i {
            color: #007bff;
        }
        .input-group-text {
            min-width: 55px;
            justify-content: center;
        }
        .custom-white-form {
            background-color: #fff;
            padding: 2rem;
            border-radius: 0.75rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .custom-white-form h5 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 1rem;
        }

        .custom-white-form .form--label {
            font-weight: 500;
            color: #555;
            display: block;
            margin-bottom: 0.5rem;
        }

        .custom-white-form .form--control,
        .custom-white-form .select2-container .select2-selection {
            background-color: #fff !important;
            border: 1px solid #ccc !important;
            border-radius: 0.375rem;
            padding: 0.75rem;
            color: #333;
            box-shadow: none !important;
            width: 100%;
            font-size: 0.95rem;
        }

        .custom-white-form .form--control:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 0 0.15rem rgba(0, 123, 255, 0.15);
        }

        .custom-white-form .input-group-text {
            background-color: #f8f9fa;
            border: 1px solid #ccc;
            font-weight: 500;
            color: #333;
        }

        .custom-white-form .booking-summary,
        .custom-white-form .booking-costs {
            background-color: #f9f9f9;
            border: 1px solid #e5e5e5;
            border-radius: 0.5rem;
            padding: 1rem;
        }

        .custom-white-form .booking-costs span {
            font-weight: bold;
            color: #e3342f;
        }

        .custom-white-form .form-group label {
            margin-left: 0.5rem;
        }

        .custom-white-form .form-group input[type="checkbox"] {
            margin-right: 0.5rem;
        }

        .custom-white-form .text--danger {
            color: #e3342f;
            font-size: 0.85rem;
        }

        .custom-white-form .btn {
            font-weight: 600;
            font-size: 1rem;
            padding: 0.75rem;
            border-radius: 0.5rem;
        }

        /* Fix select2 appearance inside white forms */
        .select2-container--default .select2-selection--single {
            background-color: #fff !important;
            border: 1px solid #ccc !important;
            border-radius: 0.375rem !important;
            height: 45px !important;
            padding: 8px 12px;
            display: flex;
            align-items: center;
            font-size: 0.95rem;
            color: #333 !important;
        }

        /* Selected text inside dropdown */
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #333 !important;
            line-height: 1.5;
        }

        /* Dropdown arrow */
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100%;
            right: 10px;
        }

        /* Dropdown list background and hover */
        .select2-container--default .select2-results__option {
            padding: 10px 12px;
            font-size: 0.95rem;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #007bff !important;
            color: #fff !important;
        }
        .btn-book-now {
            background-color: #007bff;
            color: #fff;
            font-weight: 600;
            font-size: 1rem;
            padding: 0.75rem 1.25rem;
            border-radius: 0.5rem;
            border: none;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-book-now:hover {
            background-color: #0056b3;
            transform: translateY(-1px);
            color: #fff;
        }

        .certification-box {
            background-color: #f8f9fa;
            border-left: 4px solid #0d6efd;
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
        "use strict";
        (function($) {
            // On country select change
            $('select[name="country"]').on('change', function () {
                const selected = $(this).find(':selected');
                $('input[name="mobile_code"]').val(selected.data('mobile_code'));
                $('input[name="country_code"]').val(selected.data('code'));
                $('.mobile-code').text('+' + selected.data('mobile_code'));

                checkUser($('input[name="mobile"]').val(), 'mobile');
            });

            // Default on load (first country)
            $('select[name="country"]').trigger('change');

            // User check
            // $('.checkUser').on('focusout', function() {
            $('.checkUser').on('input', function() {
                var value = $(this).val();
                var name = $(this).attr('name');
                checkUser(value, name);
            });

            function checkUser(value) {
                const token = '{{ csrf_token() }}';
                const url = '{{ route('user.checkUser') }}';

                $.post(url, {
                    _token: token,
                    email: value
                }, function(response) {
                    if (response.data !== false) {
                        $('.emailExist').text('{{ __("This email already exists.") }}');
                        $('.login-suggestion').fadeIn();
                    } else {
                        $('.emailExist').text('');
                        $('.login-suggestion').fadeOut();
                    }
                    if (response.type === 'username') {
                        $('.usernameExist').remove(); // Clear previous error
                        if (response.exists) {
                            $('<small class="text--danger usernameExist d-block mt-1">{{ __("This username already exists.") }}</small>')
                                .insertAfter('input[name="username"]');
                        }
                    }
                });
            }

        })(jQuery);
    </script>
@endpush
