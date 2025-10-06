@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="container pb-60">
        <div class="d-flex justify-content-center">
            <div class="verification-code-wrapper">
                <div class="verification-area">
                    <form action="{{ route('user.2fa.verify') }}" method="POST" class="submit-form">
                        @csrf

                        @include($activeTemplate . 'partials.verification_code')

                        <div class="form--group mt-3">
                            <button type="submit" class="btn cmn--btn w-100">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('style')
    <style>
        .verification-code span {
            background: transparent;
            border: solid 1px #{{ gs('base_color') }}47;
            color: #{{ gs('base_color') }}47;
        }
    </style>
@endpush
