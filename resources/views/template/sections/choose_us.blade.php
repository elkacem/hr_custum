@php
    $content = getContent('choose_us.content', true);
    $elements = getContent('choose_us.element', false, null, true);
@endphp
<section class="choose-section pt-120 pb-120 position-relative overflow-hidden">
    <div class="shape">{{ __(@$content->data_values->stylish_text) }}</div>
    <div class="container position-relative">
        <div class="row gy-5 justify-content-between flex-wrap-reverse">
            <div class="col-lg-6 align-self-center">
                <div class="choose-thumb rtl">
                    <img src="{{ getImage('assets/images/frontend/choose_us/' . @$content->data_values->image, '909x551') }}" alt="choose">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="section__header">
                    <span class="section__category">{{ __(@$content->data_values->subheading) }}</span>
                    <h2 class="section__title">{{ __(@$content->data_values->heading) }}</h2>
                </div>
                <div class="choose-wrapper">
                    @forelse($elements as $item)
                        <div class="choose__item">
                            <div class="choose__item-icon bg--light-white">
                                @php echo @$item->data_values->icon @endphp
                            </div>
                            <div class="choose__item-content">
                                <h5 class="choose__title">{{ __(@$item->data_values->title) }}</h5>
                                <p>{{ __(@$item->data_values->details) }}</p>
                            </div>
                        </div>
                    @empty
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
