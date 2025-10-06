<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ gs()->siteName($pageTitle ?? 'Payment Failed') }}</title>
    <link rel="shortcut icon" href="{{ siteFavicon() }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/errors/css/main.css') }}">
</head>
<body>
<div class="error" style="background-image: url({{ asset('assets/errors/images/bg-404.png') }})">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-7 text-center">
                <img src="{{ asset('assets/errors/images/error-404.png') }}" alt="Payment Failed">
                <h2 class="title text-danger mt-4">@lang('Payment Failed')</h2>
                <p class="description text-muted">@lang("We're sorry, your payment could not be completed.")</p>
                <a href="{{ route('user.deposit.history') }}" class="cmn-btn mt-4">
                        <span class="icon">
                            <svg width="20" height="20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M14.707 5.293a1 1 0 010 1.414L10.414 11H18a1 1 0 110 2H10.414l4.293 4.293a1 1 0 01-1.414 1.414l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 0z" fill="#fff"/>
                            </svg>
                        </span>
                    <span class="text">@lang('Back to Payment History')</span>
                </a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
