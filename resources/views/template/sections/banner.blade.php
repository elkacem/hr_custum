@php
    $banners = getContent('banner.element', orderById: true);
@endphp
<div class="banner-slider owl-carousel owl-theme">
    @foreach ($banners as $banner)
        <div class="banner-section">
            <div class="container">
                <div class="banner__wrapper">
                    <div class="banner__content">
                        <span class="banner__category">{{ __(@$banner->data_values->heading) }}</span>
                        <h1 class="banner__title">
                            <span>{{ __(@$banner->data_values->subheading) }}</span>
                        </h1>
                        <p class="banner__txt">{{ __(@$banner->data_values->content) }}</p>
                        <div class="btn__grp">
                            <a href="{{ url(@$banner->data_values->button_1_url) }}" class="cmn--btn active">{{ __(@$banner->data_values->button_1_name) }}</a>
                            <a href="{{ url(@$banner->data_values->button_2_url) }}" class="cmn--btn">{{ __(@$banner->data_values->button_2_name) }}</a>
                        </div>
                    </div>
                    <div class="banner__thumb">
                        <img src="{{ frontendImage('banner', @$banner->data_values->image, '1000x586') }}" alt="image">
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
