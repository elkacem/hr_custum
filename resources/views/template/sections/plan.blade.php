@php
    $planContent = getContent('plan.content', true);
    $plans = \App\Models\Plan::active()->take(3)->get();
    $locations = \App\Models\Location::active()->orderBy('name')->get();
@endphp

<!-- Pricing Section -->
<section class="pricing-section bg--section pt-120 pb-120 position-relative overflow-hidden">
    <div class="shape right-side">{{ __(@$planContent->data_values->stylish_text_right) }}</div>
    <div class="shape">{{ __(@$planContent->data_values->stylish_text_left) }}</div>
    <div class="container">
        <div class="section__header section__header__center">
            <span class="section__category">{{ __(@$planContent->data_values->subheading) }}</span>
            <h2 class="section__title">{{ __(@$planContent->data_values->heading) }}</h2>
        </div>
        <div class="row g-4 justify-content-center">

            @include('template.partials.plan')

        </div>
    </div>
</section>
