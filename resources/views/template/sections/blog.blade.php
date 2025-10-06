@php
    $blog = getContent('blog.content', true);
    $blogs = getContent('blog.element', false, 3);
@endphp

<section class="blog-section pt-120 pb-120 position-relative overflow-hidden">
    <div class="shape right-side">{{ __(@$blog->data_values->stylish_text_right) }}</div>
    <div class="shape">{{ __(@$blog->data_values->stylish_text_left) }}</div>
    <div class="container">
        <div class="section__header section__header__center">
            <span class="section__category">{{ __(@$blog->data_values->heading) }}</span>
            <h2 class="section__title">{{ __(@$blog->data_values->subheading) }}</h2>
        </div>
        <div class="row justify-content-center g-4">
            @foreach ($blogs as $blog)
                <div class="col-lg-4 col-md-6 col-sm-10">
                    <div class="post__item">
                        <div class="post__thumb">
                            <a href="{{ route('blog.details', $blog->slug) }}">
                                <img src="{{ frontendImage('blog', 'thumb_' . @$blog->data_values->image, '430x210') }}" alt="">
                            </a>
                        </div>
                        <div class="post__content">
                            <h6 class="post__title">
                                <a href="{{ route('blog.details', $blog->slug) }}">{{ __(@$blog->data_values->title) }}</a>
                            </h6>
                            <div class="meta__date">
                                <div class="meta__item">
                                    <i class="las la-calendar"></i>
                                    {{ showDateTime($blog->created_at, 'd M Y') }}
                                </div>
                                <div class="meta__item">
                                    <i class="las la-eye"></i>
                                    {{ __(@$blog->views) }}
                                </div>
                            </div>
                            <a href="{{ route('blog.details', $blog->slug) }}" class="post__read">@lang('Read More') <i class="las la-long-arrow-alt-right"></i></a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
