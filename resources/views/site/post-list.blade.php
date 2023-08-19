@extends('layouts.site.app')

@section('content')
<!-- slider -->
    @include('layouts.site.includes.slider')
    @php $logged_in = (\Auth::user()); @endphp
    <!-- Explore -->
    <section class="posts-area pt-100 pb-120">
        <div class="container">
            <div class="section-title mb-40 d-flex align-items-center justify-content-between">
                <h2>{{ __('All Ads') }}</h2>
            </div>
            <div class="post-list-view">
                @if(!empty($data['posts']) && count($data['posts']))
                    @foreach($data['posts'] as $post)
                    <div class="post-list-item mb-3">
                        <div class="card">
                            <div class="row g-0">
                                <div class="col-lg-4">
                                    <div class="post-img">
                                        @if($post->post_type == 'auction')
                                        <a href="{{route('auction_detail',[$post->id])}}">
                                        @else
                                        <a href="{{route('post_detail',[$post->id])}}">
                                        @endif
                                            @if(!empty($post->imgUrl))
                                            <!-- <img src="{{asset('/uploads/ad/'.$post->imgUrl)}}" alt=""> -->
                                            <img src="{{\App\Helpers\CommonHelper::getWebUrl($post->imgUrl, 'ad')}}" alt="">
                                            @endif
                                        </a>
                                        <div class="post-fav {{$logged_in ? 'do-fav' : 'do-fav'}}" data-itemid="{{$post->id}}" data-fav="{{ !empty($post->is_favorite) ? '0' : '1'}}" data-type="post">
                                            <i class="{{!empty($post->is_favorite) ? 'las' : 'lar'}} la-heart favrt-icon"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="card-body">
                                        <h5 class="card-title" hello>
                                            @if($post->post_type == 'auction')
                                            <a href="{{route('auction_detail',[$post->id])}}">{{$post->itemTitle ?? ''}}</a>
                                            @else
                                            <a href="{{route('post_detail',[$post->id])}}">{{$post->itemTitle ?? ''}}</a>
                                            @endif
                                        </h5>
                                        <p class="card-text">{{$post->itemDesc ?? ''}}</p>
                                        <div class="post-location mb-20">
                                            <i class="las la-map-marker-alt"></i>
                                            <span>{{$post->city ?? ''}}</span>
                                        </div>
                                        <div class="post-author">
                                            <div class="author">
                                                <a href="#">
                                                    <span class="author-name">{{$post->author ?? ''}}</span>
                                                </a>
                                            </div>
                                            @if($post->post_type == 'auction')
                                                <label class="text-danger h4"><span class="bid-timer" data-time="{{!empty($post->auction_expiry_time) ? date('M d, Y H:i:s', strtotime($post->auction_expiry_time)) : ''}}" data-countdown="{{!empty($post->auction_expiry_time) ? date('Y/m/d H:i:s', strtotime($post->auction_expiry_time)) : ''}}">{{$post->auction_expiry_time}}</span>
                                                <span class=" d-none expire-timer">{{ __('EXPIRED') }}</span></label>
                                            @else
                                            <div class="upload-time text-muted">{{\App\Helpers\CommonHelper::getPostTime($post->created_at)}}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <div class="float-end" >
                        {{ $data['posts']->links() }}
                    </div>
                @else
                    <h4 class="p-5">No Post found</h4>
                @endif
            </div>
        </div>
    </section>
@endsection
