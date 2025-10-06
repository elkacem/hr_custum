@php
    $content = getContent('how_work.content', true);
    $elements = getContent('how_work.element', false, null, true);
@endphp

<!-- How To Section -->
<section class="how-section pt-120 pb-120 position-relative overflow-hidden">
    <div class="shape z-index-1">{{ __(@$content->data_values->stylish_text) }}</div>
    <div class="container">
        <div class="custom-tab">
            <div class="position-relative row g-0 flex-wrap-reverse">
                <div class="col-lg-6 bg--section d-flex flex-wrap align-items-center">
                    <div class="how-area pt-120 pb-120 pt-max-lg-40">
                        <div class="custom-tab-menu">
                            <ul class="tab-menu">
                                @foreach (@$elements as $item)
                                    <li class="{{ $loop->first ? 'active' : '' }}">
                                        <div class="tab-menu-icon">
                                            <span>0{{ $loop->iteration }}</span>
                                        </div>
                                        <div class="tab-menu-content">
                                            <h5 class="title">{{ __(@$item->data_values->title) }}</h5>
                                            <p>{{ __(@$item->data_values->content) }}</p>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="tab-area how--thumb">
                        @foreach ($elements as $item)
                            <div class="tab-item">
                                <img src="{{ frontendImage('how_work', @$item->data_values->image, '907x626') }}" alt="how">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- How To Section -->
