@extends('template.layouts.app')

@section('panel')
    <div class="account-section pt-60 pb-60">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-6 col-lg-8">
                    <div class="account__wrapper bg--section">
                        <div class="logo">
                            <a href="{{ route('home') }}" class="d-block text-center"><img src="{{ siteLogo() }}" alt="logo"></a>
                        </div>

                        @include('template.partials.social_login')

                        <form class="account-form verify-gcaptcha" method="POST" action="{{ route('user.login') }}">
                            @csrf
                            <div class="form-group">
                                <label for="username" class="form--label">@lang('Username or Email')</label>
                                <input type="text" name="username" value="{{ old('username') }}"
                                       class="form-control form--control" required>
                            </div>
                            <div class="form-group">
                                <div class="d-flex flex-wrap justify-content-between mb-2">
                                    <label for="password" class="form--label mb-0">@lang('Password')</label>
                                    <a class="forgot-pass" href="{{ route('user.password.request') }}">
                                        @lang('Forgot your password?')
                                    </a>
                                </div>
                                <input id="password" type="password" class="form-control form--control" name="password" required>
                            </div>

                            <x-captcha />


                            <div class="form-group form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                    {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    @lang('Remember Me')
                                </label>
                            </div>

                            <button type="submit" id="recaptcha" class="btn cmn--btn w-100 justify-content-center">@lang('Login')</button>
                        </form>
                        @if (gs('registration'))
                            <p class="mt-3">@lang('Don\'t have any account?') <a class="text--base" href="{{ route('user.register') }}">@lang('Register')</a>
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
