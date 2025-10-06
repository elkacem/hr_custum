@php
    $bookingRoute = auth()->check()
        ? route('user.dossier.booking', [$vehicle->id, slug($vehicle->name)])
        : route('booking.guest.book', [$vehicle->id, slug($vehicle->name)]);
@endphp
@extends('template.layouts.frontend')

@section('content')
    <div class="single-section pt-120 pb-120 bg--section position-relative overflow-hidden">
        <div class="shape">@lang('Book Now')</div>
        <div class="container">
            <div class="row gy-5">
                <div class="col-lg-5">
                    <div class="slider-top owl-theme owl-carousel border--dashed">
                        @foreach($vehicle->images ?? [] as $image)
                            <div class="car__rental-thumb w-100 bg--body p-0">
                                <img src="{{ getImage(getFilePath('dossier').'/'.@$image, getFileSize('dossier')) }}"  alt="">
                            </div>
                        @endforeach
                    </div>
                    <div class="slider-bottom owl-theme owl-carousel mt-4">
                        @foreach($vehicle->images ?? [] as $image)
                            <div class="rental__thumbnails bg--body">
                                <img src="{{ getImage(getFilePath('dossier').'/'.@$image, getFileSize('dossier')) }}"  alt="">
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-7 align-self-center">
                    <div class="rent__single">
                        <h3 class="title">{{ __(@$vehicle->name) }}</h3>
{{--                        <div class="ratings mb-4">--}}
{{--                            <span><i class="las la-star"></i></span>--}}
{{--                            <span>({{ @$dossier->ratings_avg_rating + 0 }})</span>--}}
{{--                            <span class="text--body">{{ @$dossier->ratings_count }} @lang('Customer Review')</span>--}}
{{--                        </div>--}}
                        <div class="price-area mb-4">
                            <h5 class="item">{{ showAmount($vehicle->price) }} <sub>/@lang('day')</sub></h5>
                        </div>
                        <div class="content">
                            @php echo @$vehicle->details @endphp
                        </div>
                        <div class="btn__grp">
{{--                            @auth--}}
{{--                                @if($dossier->booked())--}}
{{--                                    <button type="button" class="btn cmn--btn">@lang('Booked')</button>--}}
{{--                                @else--}}
{{--                                    <a href="{{ route('user.dossier.booking', [$dossier->id, slug($dossier->name)]) }}" class="btn cmn--btn">@lang('Book Your Ride')</a>--}}
{{--                                <a href="{{ auth()->check() ? route('user.dossier.booking', [$dossier->id, slug($dossier->name)]) : route('booking.guest.dossier', [$dossier->id, slug($dossier->name)]) }}" class="btn cmn--btn">@lang('Book Your Ride')</a>--}}
                                <a href="{{ $bookingRoute }}" class="btn cmn--btn">@lang('Book Your Ride')</a>

                                {{--                                @endif--}}
{{--                            @else--}}
{{--                                <a href="{{ route('user.login') }}" class="btn cmn--btn">@lang('Book Your Ride')</a>--}}
{{--                            @endauth--}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="single__details mt-5">
                <ul class="nav nav-tabs nav--tabs">
                    <li class="nav-item">
                        <a href="#specifications" data-bs-toggle="tab" class="nav-link active">@lang('All Specifications')</a>
                    </li>
                    <li class="nav-item">
                        <a href="#image-gallery" data-bs-toggle="tab" class="nav-link">@lang('Image Gallery')</a>
                    </li>
                    <li class="nav-item">
                        <a href="#terms" data-bs-toggle="tab" class="nav-link">@lang('Rental Terms')</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="specifications">
                        <h5 class="single__title">@lang('All Specifications')</h5>
                        <div class="single__details-content">

                            @foreach($vehicle->specifications ?? [] as $spec)
                                <div class="specification-item">
                                    @php echo @$spec[0] @endphp
                                    <div class="specification-item-cont">
                                        <h6 class="specification-item-title">{{ @$spec[1] }}: </h6>
                                        <span>{{ @$spec[2] }}</span>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                    <div class="tab-pane" id="image-gallery">
                        <h5 class="single__title">@lang('Our Gallery')</h5>
                        <div class="single__details-content p-4">
                            <div class="row g-4">
                                @foreach($vehicle->images ?? [] as $image)
                                    <div class="col-lg-4 col-md-6">
                                        <div class="gallery__item">
                                            <a href="{{ getImage(getFilePath('dossier').'/'.@$image, getFileSize('dossier')) }}" class="img-pop">
                                                <i class="las la-plus"></i>
                                            </a>
                                            <img src="{{ getImage(getFilePath('dossier').'/'.@$image, getFileSize('dossier')) }}"  alt="">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="terms">
                        <h5 class="single__title">{{ __(@$rental_terms->data_values->title) }}</h5>
                        <div class="single__details-content p-4 py-5">
                            @foreach ($rentalTerms as $rental)
                                @php echo @$rental->data_values->details @endphp
                            @endforeach
                        </div>
                    </div>
                    <div class="tab-pane" id="review">
                        <h5 class="single__title mt-4">@lang('Leave a Review')</h5>
                        <div class="single__details-content p-4 mb-4">
                            @if(auth()->check())
                                <form class="review-form rating row" action="{{ route('user.rating', $vehicle->id) }}" method="post">
                                    @csrf

                                    <div class="review-form-group mb-3 mt-3 col-md-6 d-flex flex-wrap">
                                        <label class="review-label mb-0 mr-3">@lang('Your Ratings') :</label>
                                        <div class="rating-form-group">
                                            <label class="star-label">
                                                <input type="radio" name="rating" value="1"/>
                                                <span class="icon"><i class="las la-star"></i></span>
                                            </label>
                                            <label class="star-label">
                                                <input type="radio" name="rating" value="2"/>
                                                <span class="icon"><i class="las la-star"></i></span>
                                                <span class="icon"><i class="las la-star"></i></span>
                                            </label>
                                            <label class="star-label">
                                                <input type="radio" name="rating" value="3"/>
                                                <span class="icon"><i class="las la-star"></i></span>
                                                <span class="icon"><i class="las la-star"></i></span>
                                                <span class="icon"><i class="las la-star"></i></span>
                                            </label>
                                            <label class="star-label">
                                                <input type="radio" name="rating" value="4"/>
                                                <span class="icon"><i class="las la-star"></i></span>
                                                <span class="icon"><i class="las la-star"></i></span>
                                                <span class="icon"><i class="las la-star"></i></span>
                                                <span class="icon"><i class="las la-star"></i></span>
                                            </label>
                                            <label class="star-label">
                                                <input type="radio" name="rating" value="5"/>
                                                <span class="icon"><i class="las la-star"></i></span>
                                                <span class="icon"><i class="las la-star"></i></span>
                                                <span class="icon"><i class="las la-star"></i></span>
                                                <span class="icon"><i class="las la-star"></i></span>
                                                <span class="icon"><i class="las la-star"></i></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="review-form-group mb-3 col-12 d-flex flex-wrap">
                                        <label class="form--label" for="review-comments">@lang('Say Something')</label>
                                        <textarea name="comment" class="form-control form--control" id="review-comments"></textarea>
                                    </div>
                                     <div class="review-form-group col-12 d-flex flex-wrap">
                                         <button type="submit" class="cmn--btn"  @if(@$isRating) disabled @endif> @if(@$isRating) @lang('Reviewed') @else @lang('Submit Review') @endif</button>
                                      </div>

                                </form>
                            @else
                                <h4>@lang('Please login to add your review!')</h4>
                            @endif
                        </div>

                            <h5 class="single__title">@lang('Review')</h5>
                            <div class="single__details-content px-sm-4">
                                <ul class="content">
                                    @forelse($vehicle->ratings as $rating)
                                        <li class="review-item">
                                            <div class="review-thumb">
                                                <img src="{{ getImage(getFilePath('userProfile').'/'. $rating->user->image, getFileSize('userProfile')) }}" alt="client">
                                            </div>
                                            <div class="review-content">
                                                <div class="entry-meta">
                                                    <div class="posted-on">
                                                        {{ $rating->user->fullname }} &nbsp;
                                                        <p>@lang('Posted on') {{ showDateTime($rating->created_at) }}</p>
                                                    </div>
                                                    <div class="rating">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            @if ($rating->rating >= $i)
                                                                <i class="las la-star"></i>
                                                            @else
                                                                <i class="lar la-star"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                </div>
                                                <div class="entry-content">
                                                    <p>{{ __(@$rating->comment) }}</p>
                                                </div>
                                            </div>
                                        </li>
                                    @empty
                                        @include('template.partials.empty', ['message' =>'Review Not Found!'])
                                    @endforelse
                                </ul>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
