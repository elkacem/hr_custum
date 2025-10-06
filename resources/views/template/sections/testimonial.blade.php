@php
    $testimonial = getContent('testimonial.content', true);
    $testimonialElement = getContent('testimonial.element', orderById: true);
@endphp
<!-- Clients Say Section -->
<section class="clients-section pt-120 pb-120 bg--section position-relative overflow-hidden">
    <div class="shape right-side">{{ __(@$testimonial->data_values->stylish_text_right) }}</div>
    <div class="shape">{{ __(@$testimonial->data_values->stylish_text_left) }}</div>
    <div class="container">
        <div class="section__header section__header__center">
            <span class="section__category">{{ __(@$testimonial->data_values->subheading) }}</span>
            <h2 class="section__title">{{ __(@$testimonial->data_values->heading) }}</h2>
        </div>
        <div class="client-slider owl-theme owl-carousel">

            @foreach (@$testimonialElement as $item)
                <div class="client__item">
                    <div class="client__header">
                        <div class="thumb">
                            <img src="{{ frontendImage('testimonial', @$item->data_values->image, '100x100') }}" alt="">
                        </div>
                        <div class="name__area">
                            <h6 class="name text--base">{{ __(@$item->data_values->name) }}</h6>
                            <span class="designation">{{ __(@$item->data_values->designation) }}</span>
                        </div>
                    </div>
                    <div class="client__content">
                        <p> {{ __($item->data_values->quote) }}</p>
                        <div class="ratings">
                            @php echo rating($item->data_values->rating) @endphp
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</section>
<!-- Clients Say Section -->
