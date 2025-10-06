@extends('template.layouts.frontend')
@section('content')

    @php
        $blogContent = getContent('blog.content', true);
    @endphp

    <section class="faq-section pt-120 pb-120 position-relative bg--section overflow-hidden">
        <div class="shape right-side">{{ __(@$blogContent->data_values->stylish_text_right) }}</div>
        <div class="shape">{{ __(@$blogContent->data_values->stylish_text_left) }}</div>
        <div class="container">
            <div class="row justify-content-center g-4">
                @foreach ($blogs as $blog)
                    <div class="col-lg-4 col-md-6 col-sm-10">
                        <div class="post__item">
                            <div class="post__thumb">
                                <a href="{{ route('blog.details', $blog->slug) }}}">
                                    <img src="{{ frontendImage('blog', 'thumb_' . @$blog->data_values->image, '430x210') }}" alt="blog">
                                </a>
                            </div>
                            <div class="post__content">
                                <h6 class="post__title">
                                    <a href="{{ route('blog.details', $blog->slug) }}">{{ __(@$blog->data_values->title) }}</a>
                                </h6>
                                <div class="meta__date">
                                    <div class="meta__item">
                                        <i class="las la-calendar"></i>
                                        {{ showDateTime($blog->created_at) }}
                                    </div>
                                    <div class="meta__item">
                                        <i class="las la-eye"></i>
                                        {{ __(@$blog->views) }}
                                    </div>
                                </div>
                                <a href="{{ route('blog.details', $blog->slug) }}}" class="post__read">@lang('Read More') <i class="las la-long-arrow-alt-right"></i></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($blogs->hasPages())
                <div class="mt-3">
                    {{ paginateLinks($blogs) }}
                </div>
            @endif
        </div>

    </section>

    @if (@$sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif

@endsection
