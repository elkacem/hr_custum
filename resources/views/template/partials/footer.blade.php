@php
    $subscribeContent = getContent('subscribe.content', true);
    $contactContent = getContent('contact.content', true);
    $socialIconElements = getContent('social_icon.element', orderById: true);
    $footerContent = getContent('footer.content', true);
    $policyPages = getContent('policy_pages.element', false, null, true);
    $pages = App\Models\Page::where('is_default', Status::NO)->get();

@endphp
<!-- Footer Section -->
<footer class="footer-section">
    <div class="container">
        <div class="newsletter-section">
            <div class="newsletter-wrapper">
                <div class="footer-logo">
                    <a href="{{ route('home') }}">
                        <img src="{{ siteLogo() }}" alt="logo">
                    </a>
                </div>
                <div class="newsletter-title">
                    <div class="section__header border-0">
                        <h4 class="section__title mb-0">@lang('Newsletter') <span class="text--base">@lang('Subscription')</span></h4>
                        <p>{{ __(@$subscribeContent->data_values->content) }}</p>
                    </div>
                </div>
                <div class="newsletter-form">
                    <form action="{{ route('subscribe') }}" id="subscribeForm" method="post">
                        @csrf
                        <div class="input-group">
                            <input name="email" type="email" class="form-control form--control subscribe_email" placeholder="@lang('Enter your email address')">
                            <button type="submit" class="input-group-text cmn--btn">@lang('Subscribe')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="footer__top">
            <div class="footer-wrapper">
                <div class="footer__widget widget__about">
                    <h4 class="widget__title">@lang('About') {{ __(gs('site_name')) }}</h4>
                    <p>{{ __(@$footerContent->data_values->content) }}</p>
                    <ul class="social-icons">
                        @foreach (@$socialIconElements as $icon)
                            <li>
                                <a href="{{ @$icon->data_values->url }}" title="{{ __(@$icon->data_values->title) }}" target="_blank">
                                    @php
                                        echo @$icon->data_values->social_icon;
                                    @endphp
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="footer__widget">
                    <h4 class="widget__title">@lang('Site Links')</h4>
                    <ul class="widget__links">
                        <li><a href="{{ route('home') }}">@lang('Home')</a></li>
                        @foreach (@$pages as $k => $data)
                            <li>
                                <a href="{{ route('pages', [@$data->slug]) }}">{{ __(@$data->name) }}</a>
                            </li>
                        @endforeach
                        <li><a href="{{ route('vehicles.index') }}">@lang('Vehicles')</a></li>
                        <li><a href="{{ route('blogs') }}">@lang('Blog')</a></li>
                    </ul>
                </div>
                <div class="footer__widget">
                    <h4 class="widget__title">@lang('Policy Pages')</h4>
                    <ul class="widget__links">
                        @foreach (@$policyPages as $policy)
                            <li>
                                <a href="{{ route('policy.pages', $policy->slug) }}">
                                    {{ __(@$policy->data_values->title) }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="footer__widget widget__contact">
                    <h4 class="widget__title">@lang('Get In Touch')</h4>
                    <ul class="footer__contact">
                        <li>
                            <div class="icon"><i class="las la-phone-volume"></i></div>
                            <div class="cont">
                                <span class="subtitle">@lang('For Support')</span>
                                <a href="Tel:{{ @$contactContent->data_values->phone }}" class="info">{{ @$contactContent->data_values->phone }}</a>
                            </div>
                        </li>
                        <li>
                            <div class="icon"><i class="las la-headset"></i></div>
                            <div class="cont">
                                <span class="subtitle">@lang('Send Us Email')</span>
                                <a href="mailto:{{ @$contactContent->data_values->email }}" class="info">{{ @$contactContent->data_values->email }}</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="footer__bottom bg--section py-3 text-center"> &copy; @lang('Copyright') {{ now()->year }} <a href="{{ route('home') }}"> <span class="text--base">{{ gs('site_name') }}</span></a> @lang('All rights reserved').</div>
</footer>
<!-- Footer Section -->


@push('script')
    <script>
        (function($) {
            "use strict";
            //Subscribe
            $(document).on("submit", "#subscribeForm", function(e) {
                e.preventDefault();

                var data = $('#subscribeForm').serialize();

                $.ajax({
                    url: '{{ route('subscribe') }}',
                    method: 'post',
                    data: data,
                    success: function(response) {
                        if (response.success) {
                            $('.subscribe_email').val('');
                            notify('success', response.message);
                        } else {
                            $.each(response.error, function(key, value) {
                                notify('error', value);
                            });
                        }
                    },
                    error: function(error) {
                        console.log(error)
                    }
                });
            });

        })(jQuery);
    </script>
@endpush
