@extends('template.layouts.frontend')

@section('content')
    @php
        $contactContent = getContent('contact.content', true);
    @endphp
    <div class="contact-section pt-120 pb-120 bg--section">
        <div class="container">
            <div class="row">
                <div class="col-xl-6">
                    <div class="account__wrapper mw-100 bg--body">
                        <form class="account-form row g-4 verify-gcaptcha" method="post">
                            @csrf

                            <div class="col-md-6">
                                <label for="name" class="form--label">@lang('Name')</label>
                                <input name="name" type="text" class="form-control form--control" value="{{ old('name', @$user->fullname) }}" @if ($user && $user->profile_complete) readonly @endif required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form--label">@lang('E-Mail')</label>
                                <input name="email" type="email" class="form-control form--control" value="{{ old('email', @$user->email) }}" @if ($user) readonly @endif required>
                            </div>
                            <div class="col-md-12">
                                <label for="subject" class="form--label">@lang('Subject')</label>
                                <input name="subject" type="text" class="form-control form--control"
                                       value="{{ old('subject') }}" required>
                            </div>
                            <div class="col-md-12">
                                <label for="message" class="form--label">@lang('Your Message')</label>
                                <textarea name="message" wrap="off" class="form-control form--control" required>{{ old('message') }}</textarea>
                            </div>

                            <x-captcha />

                            <div class="col-md-12">
                                <button type="submit" class="cmn--btn btn--lg">@lang('Submit')</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="h-100 map-area">
                        <iframe src="https://maps.google.com/maps?q={{ @$contactContent->data_values->map_latitude }},{{ @$contactContent->data_values->map_longitude }}&hl=es;z=14&amp;output=embed"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <section class="brance-section pb-120 bg--section">
        <div class="container">
            <div class="row g-4 justify-content-center">
                <div class="col-xl-4 col-md-6">
                    <div class="contact__item bg--body">
                        <div class="contact__icon">
                            <i class="las la-phone"></i>
                        </div>
                        <div class="contact__body">
                            <h5 class="contact__title">@lang('Phone')</h5>
                            <ul class="contact__info">
                                <li>
                                    <a href="Tel:{{ @$contactContent->data_values->phone }}">{{ @$contactContent->data_values->phone }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="contact__item bg--body">
                        <div class="contact__icon">
                            <i class="las la-envelope"></i>
                        </div>
                        <div class="contact__body">
                            <h5 class="contact__title">@lang('Email')</h5>
                            <ul class="contact__info">
                                <li>
                                    <a href="mailto:{{ @$contactContent->data_values->email }}">{{ @$contactContent->data_values->email }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="contact__item bg--body">
                        <div class="contact__icon">
                            <i class="las la-map-marker"></i>
                        </div>
                        <div class="contact__body">
                            <h5 class="contact__title">@lang('Address')</h5>
                            <ul class="contact__info">
                                <li>{{ __(@$contactContent->data_values->address) }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if (@$sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection
