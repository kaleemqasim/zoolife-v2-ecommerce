@extends('layouts.site.app')

@section('content')
    <!-- slider -->
    <section class="slider-area">
        <div class="slider owl-carousel">
            <div class="slider-item" style="background-image: url({{asset('/uploads/article/'.$post->image)}});">
                <div class="container">
                    
                </div>
            </div>
        </div>
    </section>
    <!-- explore details -->
    <section class="post-specification pt-65 pb-65">
        <div class="container">
            <div class="section-title mb-30">
                <h2>{{ __('Details') }}</h2>
            </div>
            <div class="row">
                <div class="col-12">
                    <h2 class="">{{$post->itemTitle ?? ''}}</h2>
                    <div class="post-author-location">
                        <div class="icon-text" style="color: var(--theme-color);">
                            <i class="lar la-calendar"></i>
                            <span>{{\App\Helpers\CommonHelper::getPostTime($post->date)}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- explore description -->
    <section class="post-specification pb-160">
        <div class="container">
            <div class="section-title mb-30">
                <h2>{{ __('Description') }}</h2>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="post-specification-content">
                        {!! ($post->description ?? '') !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
