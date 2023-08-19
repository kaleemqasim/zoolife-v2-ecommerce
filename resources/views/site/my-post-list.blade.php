@extends('layouts.site.app')

@section('content')
<!-- slider -->
    @include('layouts.site.includes.slider')
    <section class="slider-area d-none">
        <div class="slider owl-carousel">
            @if(!empty($data['sliders']))
                @foreach($data['sliders'] as $slide)
                <div class="slider-item" style="background-image: url({{ asset('/uploads/slider/' . $slide->image) }});">
                    <div class="container">
                        <div class="slider-content">
                            <h2 class="title">{{$slide->title ?? ''}}</h2>
                            <p class="desc">{{$slide->description ?? ''}}
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </section>

    <!-- posts -->
    <section class="posts-area section--padding">
        <div class="container">
            <div class="section-title mb-40 d-flex align-items-center justify-content-between">
                <h2>{{ __('Posts') }}</h2>
            </div>
            <div class="posts owl-carousel">
                @if(!empty($data['posts']))
                    @foreach($data['posts'] as $post)
                    <div class="post-item">
                        <div class="action-btns">
                            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-type="normal" data-url="{{route('load-post-auction-modal', ['normal',$post->id])}}" data-bs-target="{{ (\Auth::user()) ? '#commonModal' : '#login'}}"><i class="las la-edit"></i></button>
                            <button class="btn btn-danger delete-post-btn" data-type="remove_post" data-id="{{$post->id ?? ''}}"><i class="las la-times"></i></button>
                        </div>
                        <div class="post-img">
                            <a href="{{route('post_detail',[$post->id])}}">
                                @if(!empty($post->imgUrl))
                                <!-- <img src="{{asset('/uploads/ad/'.$post->imgUrl)}}" alt=""> -->
                                <img src="{{\App\Helpers\CommonHelper::getWebUrl($post->imgUrl, 'ad')}}" alt="">
                                @endif
                            </a>
                            <div class="post-fav">
                                <i class="las la-heart"></i>
                            </div>
                        </div>
                        <div class="post-body">
                            <h2 class="post-title"><a href="{{route('post_detail',[$post->id])}}">{{$post->itemTitle ?? ''}}</a></h2>
                            <p class="post-desc">{{$post->itemDesc ?? ''}}</p>
                            <div class="post-location">
                                <i class="las la-map-marker-alt"></i>
                                <span>{{$post->city ?? ''}}</span>
                            </div>
                            <div class="post-author">
                                <div class="author">
                                    <a href="#">
                                        <span class="author-name">{{$post->author ?? ''}}</span>
                                    </a>
                                </div>
                                <div class="upload-time">{{\App\Helpers\CommonHelper::getPostTime($post->created_at)}}</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>
    <!-- auctions -->
    <section class="posts-area section--padding">
        <div class="container">
            <div class="section-title mb-40 d-flex align-items-center justify-content-between">
                <h2>{{ __('Auctions') }}</h2>
            </div>
            <div class="posts owl-carousel">
                @if(!empty($data['auction']))
                    @foreach($data['auction'] as $post)
                    <div class="post-item">
                        <div class="action-btns">
                            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-type="auction" data-url="{{route('load-post-auction-modal', ['auction',$post->id])}}" data-bs-target="{{ (\Auth::user()) ? '#commonModal' : '#login'}}"><i class="las la-edit"></i></button>
                            <button class="btn btn-danger"><i class="las la-times"></i></button>
                        </div>
                        <div class="post-img">
                            <a href="{{route('auction_detail',[$post->id])}}">
                                @if(!empty($post->imgUrl))
                                <!-- <img src="{{asset('/uploads/ad/'.$post->imgUrl)}}" alt=""> -->
                                <img src="{{\App\Helpers\CommonHelper::getWebUrl($post->imgUrl, 'ad')}}" alt="">
                                @else
                                <img src="/assets/img/posts/post-1.png" alt="">
                                @endif
                            </a>
                            <div class="post-fav">
                                <i class="las la-heart"></i>
                            </div>
                        </div>
                        <div class="post-body">
                            <h2 class="post-title"><a href="{{route('auction_detail',[$post->id])}}">{{$post->itemTitle ?? ''}}</a></h2>
                            <p class="post-desc">{{$post->itemDesc ?? ''}}</p>
                            <div class="post-location">
                                <i class="las la-map-marker-alt"></i>
                                <span>{{$post->city ?? ''}}</span>
                            </div>
                            <div class="post-author">
                                <div class="author">
                                    <a href="#">
                                        <span class="author-name">{{$post->author ?? ''}}</span>
                                    </a>
                                </div>
                                <div class="upload-time">{{\App\Helpers\CommonHelper::getPostTime($post->created_at)}}</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>
@endsection
