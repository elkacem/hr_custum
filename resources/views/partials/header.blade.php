@php
    $contact = getContent('contact.content', true);
    $socialIconElements = getContent('social_icon.element', orderById: true);
    $pages = App\Models\Page::where('tempname', $activeTemplate)
        ->where('is_default', Status::NO)
        ->get();
@endphp

<div class="header-top py-2">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between mx--10">
            <div class="header-top-item meta-list">
                <a href="Mailto:{{ @$contact->data_values->email }}"><i
                       class="lar la-envelope"></i>{{ @$contact->data_values->email }}</a>
            </div>
            <div class="d-flex flex-wrap meta-list">
                @auth
                    <div class="header-top-item ml-sm-auto">
                        <a href="{{ route('user.home') }}"><i class="las la-tachometer-alt"></i>@lang('Dashboard')</a>
                    </div>
                    <div class="header-top-item">
                        <a href="{{ route('user.logout') }}"><i class="las la-sign-out-alt"></i>@lang('Logout')</a>
                    </div>
                @else
                    <div class="header-top-item ml-sm-auto">
                        <a href="{{ route('user.login') }}"><i class="las la-user"></i>@lang('Login')</a>
                    </div>
                    <div class="header-top-item">
                        <a href="{{ route('user.register') }}"><i class="las la-user-plus"></i>@lang('Register')</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>
<div class="header-bottom">
    <div class="container">
        <div class="header-wrapper">
            <div class="logo">
                <a href="{{ route('home') }}"><img src="{{ siteLogo() }}" alt="logo"></a>
            </div>
            <ul class="menu">
                @auth
                    @foreach (@$pages as $k => $data)
                        <li class="{{ menuActive('pages', null, @$data->slug) }}">
                            <a href="{{ route('pages', [@$data->slug]) }}" aria-current="page">{{ __(@$data->name) }}</a>
                        </li>
                    @endforeach
                    <li class="{{ menuActive(['vehicles.index', 'user.dossier.booking.log', 'user.dossier.booking']) }}">
                        <a href="#0">@lang('Vehicle')</a>
                        <ul class="submenu">
                            <li class="{{ menuActive('vehicles.index') }}">
                                <a href="{{ route('vehicles.index') }}">@lang('All Vehicles')</a>
                            </li>
                            <li class="{{ menuActive('user.dossier.booking.log') }}">
                                <a href="{{ route('user.dossier.booking.log') }}">@lang('Booking Logs')</a>
                            </li>
                        </ul>
                    </li>
                    <li class="{{ menuActive(['plans', 'user.plan.booking.log']) }}">
                        <a href="#0">@lang('Plan')</a>
                        <ul class="submenu">
                            <li class="{{ menuActive('plans') }}">
                                <a href="{{ route('plans') }}">@lang('All Plans')</a>
                            </li>
                            <li class="{{ menuActive('user.plan.booking.log') }}">
                                <a href="{{ route('user.plan.booking.log') }}">@lang('Booking Logs')</a>
                            </li>
                        </ul>
                    </li>

                    <li class="{{ menuActive(['ticket.open', 'ticket.index']) }}">
                        <a href="#0">@lang('Support Ticket')</a>
                        <ul class="submenu">
                            <li class="{{ menuActive('ticket.open') }}">
                                <a href="{{ route('ticket.open') }}">@lang('Open New Ticket')</a>
                            </li>
                            <li class="{{ menuActive('ticket.index') }}">
                                <a href="{{ route('ticket.index') }}">@lang('My Tickets')</a>
                            </li>
                        </ul>
                    </li>

                    <li
                        class="{{ menuActive(['user.deposit.history', 'user.change.password', 'user.profile.setting', 'user.twofactor']) }}">
                        <a href="#0">@lang('More')</a>
                        <ul class="submenu">
                            <li class="{{ menuActive('user.deposit.history') }}">
                                <a href="{{ route('user.deposit.history') }}">@lang('Payment History')</a>
                            </li>
                            <li class="{{ menuActive('user.profile.setting') }}">
                                <a href="{{ route('user.profile.setting') }}">@lang('Profile Setting')</a>
                            </li>
                            <li class="{{ menuActive('user.change.password') }}">
                                <a href="{{ route('user.change.password') }}">@lang('Change Password')</a>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="{{ menuActive('home') }}">
                        <a href="{{ route('home') }}">@lang('Home')</a>
                    </li>
                    @foreach (@$pages as $k => $data)
                        <li class="{{ menuActive('pages', null, @$data->slug) }}">
                            <a href="{{ route('pages', [@$data->slug]) }}" aria-current="page">{{ __(@$data->name) }}</a>
                        </li>
                    @endforeach
                    <li class="{{ menuActive('vehicles.index') }}"><a
                           href="{{ route('vehicles.index') }}">@lang('Vehicle')</a></li>
                    <li class="{{ menuActive('plans') }}"><a href="{{ route('plans') }}">@lang('Plan')</a></li>
                    <li class="{{ menuActive('blogs') }}"><a href="{{ route('blogs') }}">@lang('Blog')</a></li>
                    <li class="{{ menuActive('contact') }}"><a href="{{ route('contact') }}">@lang('Contact')</a></li>
                @endauth
                <li class="p-3">
                    @include($activeTemplate . 'partials.language')
                </li>
            </ul>
            <div class="header-bar">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
</div>
