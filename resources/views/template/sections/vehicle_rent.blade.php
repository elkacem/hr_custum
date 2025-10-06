@php
    $content = getContent('vehicle_rent.content', true);
// Get only 3 different models (latest first)
    $vehicles = \App\Models\Vehicle::active()
        ->latest()
        ->with('seater')
        ->get()
        ->unique('model')   // keep first dossier per model
        ->take(3)           // take only 3 unique models
        ->values();         // reset keys for Blade loops
@endphp
<!-- Rental Fleet Section -->
<section class="rental-section pb-120 pt-120 bg--section position-relative overflow-hidden">
    <div class="shape right-side">{{ __(@$content->data_values->stylish_text) }}</div>
    <div class="container position-relative">
        <div class="section__header section__header__center">
            <span class="section__category">{{ __(@$content->data_values->heading) }}</span>
            <h2 class="section__title">{{ __(@$content->data_values->subheading) }}</h2>
        </div>
        <div class="sync1 owl-theme owl-carousel">
            @forelse($vehicles as $vehicle)
                <div class="car__rental">
                    <div class="car__rental-thumb">
                        <img src="{{ getImage(getFilePath('dossier') . '/' . @$vehicle->images[0], getFileSize('dossier')) }}" alt="">
                    </div>
                    <div class="car__rental-content">
                        <div class="car__rental-content-header">
                            <h2 class="price">{{ showAmount($vehicle->price) }} <span class="sub">/ @lang('Day')</span></h2>
                        </div>
                        <div class="car__rental-content-body">
                            <ul class="specification">
                                <li>
                                    <i class="las la-car"></i>@lang('Model'): {{ __(@$vehicle->model) }}
                                </li>
                                <li>
                                    <i class="las la-door-open"></i>@lang('Doors'): {{ __(@$vehicle->doors) }}
                                </li>
                                <li>
                                    <i class="las la-couch"></i>@lang('Seats'): {{ __(@$vehicle->seater->number) }}
                                </li>
                                <li>
                                    <i class="las la-gas-pump"></i>@lang('Transmission'): {{ __(@$vehicle->transmission) }}
                                </li>
                            </ul>
                            <div class="btn__grp">
                                <a href="{{ route('vehicles.details', [$vehicle->id, slug($vehicle->name)]) }}" class="cmn--btn">@lang('Book Now')</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
            @endforelse
        </div>
        <div class="sync2 owl-theme owl-carousel mt-5">
            @foreach ($vehicles as $vehicle)
                <div class="rental__thumbnails">
                    <img src="{{ getImage(getFilePath('dossier') . '/' . $vehicle->images[0], getFileSize('dossier')) }}" alt="">
                </div>
            @endforeach
        </div>
    </div>
</section>
