@extends('template.layouts.app')
@section('panel')
    @php
        $banned = getContent('banned.content', true);
    @endphp
        <section class="container-fluid maintenance-page flex-column justify-content-center">
            <div class="container">
                <div class="row justify-content-center align-items-center">
                    <div class="col-lg-7 text-center">
                        <div class="row justify-content-center">
                            <div class="col-sm-6 col-8 col-lg-12">
                                <img class="mb-4" src="{{ frontendImage('banned', @$banned->data_values->image, '700x400') }}" alt="@lang('image')">
                                <h4 class="text--danger mb-2">{{ __(@$banned->data_values->heading) }}</h4>
                            </div>
                        </div>
                        <p class="mb-4">{{ __($user->ban_reason) }} </p>
                        <a class="btn--base btn btn--sm" href="{{ route('home') }}"> @lang('Go to Home') </a>
                    </div>
                </div>
            </div>
        </section>
    @endsection

    @push('style')
<style>
    body{
        background-color:#22313a;
        display: flex;
        align-items: center;
        height: 100vh;
        justify-content: center;
    }

</style>
@endpush
