@extends('layouts.site.app')

@section('content')
    <!-- slider -->
    @include('layouts.site.includes.slider')
    
        <!-- Explore -->
    <section class="posts-area pt-100 pb-120">
        <div class="container">
            <div class="section-title mb-40 d-flex align-items-center justify-content-between">
                <h2>{{ __('Articles') }}</h2>
            </div>
            <div class="posts owl-carousel">
                @if(!empty($data['posts']))
                    @foreach($data['posts'] as $post)
                    <div class="post-item">
                        <div class="post-img">
                            <a href="{{route('article_detail',[$post->id])}}">
                                @if(!empty($post->image))
                                <img src="{{asset('/uploads/article/'.$post->image)}}" alt="">
                                @endif
                            </a>
                            <div class="post-fav">
                                <i class="las la-heart"></i>
                            </div>
                        </div>
                        <div class="post-body">
                            <a href="{{route('article_detail',[$post->id])}}" class="post-category">{{$post->title ?? ''}}</a>
                            <h2 class="post-title mb-15">{{!empty($post->description) ? mb_substr(strip_tags($post->description), 0, 100) : ''}}</h2>
                            <div class="upload-date">{{\App\Helpers\CommonHelper::getPostTime($post->date)}}</div>
                        </div>
                    </div>
                    @endforeach
                    <div class="float-end d-none" >
                        {{ $data['posts']->links() }}
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
