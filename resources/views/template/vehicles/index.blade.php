@extends( 'template.layouts.frontend')
@section('content')
    <div class="search-section pt-120 pb-120 bg--section position-relative overflow-hidden">
        <div class="shape right-side">@lang('Rent')</div>
        <div class="shape">@lang('Vehicles')</div>
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <aside class="category-sidebar">
                        <div class="widget d-lg-none border--dashed">
                            <div class="d-flex justify-content-between">
                                <h5 class="title border-0 pb-0 mb-0">@lang('Filter Vehicles')</h5>
                                <div class="close-sidebar"><i class="las la-times"></i></div>
                            </div>
                        </div>
                        <div class="widget border--dashed">
                            <h5 class="title">
                                <label for="search">@lang('Search Name')</label>
                            </h5>
                            <div class="widget-body">
                                <form action="{{ route('vehicles.filter') }}" method="get">
                                    <div class="input-group">
                                        <input type="text" name="name" value="{{ @request()->name }}" class="form-control form--control" placeholder="@lang('Vehicle Name')" id="search">
                                        <button class="input-group-text cmn--btn" type="submit"><i class="las la-search"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="widget border--dashed">
                            <div class="d-flex justify-between">
                                <h5 class="title">@lang('Filter by Price')</h5>
                            </div>

                            <div class="widget-body">
                                <form action="{{ route('vehicles.filter') }}" method="get" class="priceForm">
                                    <div class="row justify-content-center">
                                        <div class="col-md-6">
                                            <label for="srt-date" class="form--label">
                                                <i class="las la-dollar-sign"></i> @lang('Min Price')
                                            </label>
                                            <input type="text" value="{{ @request()->min_price }}" class="form-control form--control min_price" name="min_price" placeholder="@lang('Min Price')">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="stat-dae" class="form--label">
                                                <i class="las la-dollar-sign"></i> @lang('Max Price')
                                            </label>
                                            <input type="text" value="{{ @request()->max_price }}" class="form-control form--control max_price" name="max_price" placeholder="@lang('Max Price')">
                                        </div>
                                    </div>
                                    <button class="cmn--btn bg--base w-100 mt-3 justify-content-center" type="submit"><i class="las la-filter"></i> @lang('Filter')</button>
                                </form>
                            </div>
                        </div>
                        <div class="widget border--dashed">
                            <h5 class="title">@lang('Filter by Brand')</h5>
                            <div class="widget-body">
                                <ul class="category-link">
                                    @foreach ($brands ?? [] as $brand)
                                        <li>
                                            <a href="{{ route('vehicles.brand', [$brand->id, slug($brand->name)]) }}"><span>{{ __(@$brand->name) }}</span><span>({{ @$brand->vehicles_count }})</span></a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="widget border--dashed">
                            <h5 class="title">@lang('Filter by Vehicle Seating')</h5>
                            <div class="widget-body">
                                <ul class="category-link">
                                    @foreach (@$seaters ?? [] as $seat)
                                        <li>
                                            <a href="{{ route('vehicles.seater', $seat->id) }}"><span>{{ __(@$seat->number) }} @lang('Seater')</span><span>({{ @$seat->vehicles_count }})</span></a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </aside>
                </div>
                <div class="col-lg-8">
                    <div class="filter-in d-lg-none">
                        <i class="las la-filter"></i>
                    </div>
                    <div class="book__wrapper bg--body border--dashed mb-4">
                        <form class="book--form row gx-3 gy-4 g-md-4" action="{{ route('vehicles.filter') }}" method="get">
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label for="car-type" class="form--label">
                                        <i class="las la-car"></i> @lang('Vehicle Model')
                                    </label>
                                    <select name="brand_id" id="car-type" class="form-control form--control select2">
                                        <option value="" disabled selected>@lang('Select One')</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ __(@$brand->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label for="pick-point" class="form--label">
                                        <i class="las la-couch"></i> @lang('Number Of Seats')
                                    </label>
                                    <select name="seater_id" id="pick-point" class="form-control form--control select2">
                                        <option value="" disabled selected>@lang('Select One')</option>
                                        @foreach ($seaters as $seat)
                                            <option value="{{ $seat->id }}">{{ __(@$seat->number) }} {{ __('Seater') }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label class="form--label d-none d-sm-block">&nbsp;</label>
                                    <button class="cmn--btn form--control bg--base w-100 justify-content-center" type="submit"><i class="las la-filter"></i> @lang('Filter')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="row g-4">
                        @forelse($vehicles as $vehicle)
                            <div class="col-md-6">
                                <div class="rent__item">
                                    <div class="rent__thumb">
                                        <a href="{{ route('vehicles.details', [$vehicle->id, slug($vehicle->name)]) }}">
                                            <img src="{{ getImage(getFilePath('dossier') . '/' . @$vehicle->images[0], getFileSize('dossier')) }}" class="first-look" alt="">
                                            <img src="{{ getImage(getFilePath('dossier') . '/' . @$vehicle->images[1], getFileSize('dossier')) }}" class="hover-look" alt="">
                                        </a>
                                    </div>
                                    <div class="rent__content">
                                        <h6 class="rent__title">
                                            <a href="{{ route('vehicles.details', [$vehicle->id, slug($vehicle->name)]) }}">{{ __(@$vehicle->name) }}</a>
                                        </h6>
                                        <div class="price-area">
                                            <h5 class="item">{{ showAmount($vehicle->price) }} <sub>/@lang('day')</sub></h5>
                                        </div>
                                        <ul class="d-flex car-info">
                                            <li class="pr-3"><i class="las la-car"></i><span class="font-mini">{{ __(@$vehicle->model) }}</span></li>
                                            <li class="pr-3"><i class="las la-tachometer-alt"></i><span class="font-mini">{{ __(@$vehicle->transmission) }}</span></li>
                                            <li class="pr-3"><i class="las la-gas-pump"></i><span class="font-mini">{{ __(@$vehicle->fuel_type) }}</span></li>
                                        </ul>
                                        <div class="rent-btn mt-4 text-center">
                                            <a href="{{ route('vehicles.details', [$vehicle->id, slug($vehicle->name)]) }}" class="btn cmn--btn w-100 justify-content-center">
{{--                                                @if ($dossier->booked())--}}
{{--                                                    @lang('Booked')--}}
{{--                                                @else--}}
                                                    @lang('Book Now')
{{--                                                @endif--}}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            @include( 'template.partials.empty', ['message' => 'Vehicles Not Found!'])
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (@$sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include( 'template.sections.' . $sec)
        @endforeach
    @endif

@endsection


@push('script')
    <script>
        (function($) {
            "use strict";

            $('.min_price').keypress(function(e) {
                if (e.which == 13) {
                    $('.priceForm').submit();
                    return false;
                }
            });

            $('.max_price').keypress(function(e) {
                if (e.which == 13) {
                    $('.priceForm').submit();
                    return false;
                }
            });
        })(jQuery);
    </script>
@endpush
