@extends('layouts.site.app')

@section('content')
<!-- slider -->
    @include('layouts.site.includes.slider')
    @php $loggedIn = (\Auth::user()); @endphp
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

    <!-- feature ads -->
    <section class="posts-area pt-140 pb-65">
        <div class="container">
            <div class="posts feature-post owl-carousel">
                @if(!empty($data['featuredPosts']))
                    @foreach($data['featuredPosts'] as $feature)
                    <div class="post-item">
                        <div class="post-img">
                            @if($feature->post_type == 'auction')
                            <a href="{{route('auction_detail',[$feature->id])}}">
                            @else
                            <a href="{{route('post_detail',[$feature->id])}}">
                            @endif
                            @if(!empty($feature->imgUrl))
                            <!-- <img src="{{asset('/uploads/ad/'.$feature->imgUrl)}}" alt=""> -->
                            <img src="{{\App\Helpers\CommonHelper::getWebUrl($feature->imgUrl, 'ad')}}" alt="">
                            @endif
                            </a>
                            <span class="post-badge">{{ __('Featured') }}</span>
                            <div class="post-fav {{$loggedIn ? 'do-fav' : 'do-fav'}}" data-itemid="{{$feature->id}}" data-fav="{{ !empty($feature->is_favorite) ? '0' : '1'}}" data-type="post">
                                <i class="{{!empty($feature->is_favorite) ? 'las' : 'lar'}} la-heart favrt-icon"></i>
                            </div>
                        </div>
                        <div class="post-body">
                            <h2 class="post-title">
                                @if($feature->post_type == 'auction')
                                <a href="{{route('auction_detail',[$feature->id])}}">{{$feature->itemTitle ?? ''}}</a>
                                @else
                                <a href="{{route('post_detail',[$feature->id])}}">{{$feature->itemTitle ?? ''}}</a>
                                @endif
                            </h2>
                            <p class="post-desc">{{$feature->itemDesc ?? ''}}</p>
                            <div class="post-location">
                                <i class="las la-map-marker-alt"></i>
                                <span>{{$feature->city ?? ''}}</span>
                            </div>
                            <div class="post-author">
                                <div class="author">
                                    <a href="#">
                                        <span class="author-name">{{$feature->author ?? ''}}</span>
                                    </a>
                                </div>
                                @if($feature->post_type == 'auction')
                                    <label class="text-danger h4"><span class="bid-timer" data-time="{{!empty($feature->auction_expiry_time) ? date('M d, Y H:i:s', strtotime($feature->auction_expiry_time)) : ''}}" data-countdown="{{!empty($feature->auction_expiry_time) ? date('Y/m/d H:i:s', strtotime($feature->auction_expiry_time)) : ''}}">{{$feature->auction_expiry_time}}</span>
                                    <span class=" d-none expire-timer">{{ __('EXPIRED') }}</span></label>
                                @else
                                <div class="upload-time">{{\App\Helpers\CommonHelper::getPostTime($feature->created_at)}}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>
    <!-- service category -->
    <section class="service-category-area pt-65 pb-65">
        <div class="container">
            <div class="section-title mb-40 d-flex align-items-center justify-content-between">
                <h2>{{ __('Services Category') }}</h2>
                <a href="#" class="see-all-link mb-10">{{ __('See all') }}<i class="las la-angle-right"></i></a>
            </div>
            <div class="service-category owl-carousel nav-pills">
                @if(!empty($data['categories']))
                    @foreach($data['categories'] as $category)
                        @if(!empty($category->cat_img))
                        <a href="javascript:void(0)" class="category-item" data-target="#category-{{$category->id}}" title="category-{{$category->id}}">
                            <div class="category-icon">
                                <img src="{{asset('uploads/category/'.$category->cat_img)}}" alt="">
                            </div>
                            <h3 class="category-title">{{$category->title ?? '' }} </h3>
                        </a>
                        @endif
                    @endforeach
                @endif
            </div>
            <div class="tab-content category-tab-content" > 
                @if(!empty($data['sub_categories']))
                    @foreach($data['sub_categories'] as $cat=>$subCategory)
                        <div class="tab-pane " id="category-{{$cat}}" role="tabpanel" aria-labelledby="category-{{$cat}}-tab">
                            <div class="category-tag">
                            @foreach($subCategory as $sub)
                            <a  href="{{route('posts')}}?cat={{$sub->id}}" class="tag-item">{{$sub->title ?? '' }}</a>
                            @endforeach
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>
    <!-- posts -->
    <section class="posts-area section--padding">
        <div class="container">
            <div class="section-title mb-40 d-flex align-items-center justify-content-between">
                <h2>{{ __('Posts') }}</h2>
                <a href="{{route('posts')}}" class="see-all-link mb-10">{{ __('See all') }}<i class="las la-angle-right"></i></a>
            </div>
            <div class="posts owl-carousel">
                @if(!empty($data['posts']))
                    @foreach($data['posts'] as $post)
                    <div class="post-item">
                        <div class="post-img">
                            <a href="{{route('post_detail',[$post->id])}}">
                                @if(!empty($post->imgUrl))
                                <!-- <img src="{{asset('/uploads/ad/'.$post->imgUrl)}}" alt=""> -->
                                <img src="{{\App\Helpers\CommonHelper::getWebUrl($post->imgUrl, 'ad')}}" alt="">
                                @else
                                <img src="/assets/img/posts/post-1.png" alt="">
                                @endif
                            </a>
                            <div class="post-fav {{$loggedIn ? 'do-fav' : 'do-fav'}}" data-itemid="{{$post->id}}" data-fav="{{ !empty($post->is_favorite) ? '0' : '1'}}" data-type="post">
                                <i class="{{!empty($post->is_favorite) ? 'las' : 'lar'}} la-heart favrt-icon"></i>
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
                <a href="{{route('posts',['auction'])}}" class="see-all-link mb-10">{{ __('See all') }}<i class="las la-angle-right"></i></a>
            </div>
            <div class="posts owl-carousel">
                @if(!empty($data['auction']))
                    @foreach($data['auction'] as $post)
                    <div class="post-item">
                        <div class="post-img">
                            <a href="{{route('auction_detail',[$post->id])}}">
                                @if(!empty($post->imgUrl))
                                <!-- <img src="{{asset('/uploads/ad/'.$post->imgUrl)}}" alt=""> -->
                                <img src="{{\App\Helpers\CommonHelper::getWebUrl($post->imgUrl, 'ad')}}" alt="">
                                @else
                                <img src="/assets/img/posts/post-1.png" alt="">
                                @endif
                            </a>
                            <div class="post-fav {{$loggedIn ? 'do-fav' : 'do-fav'}}" data-itemid="{{$post->id}}" data-fav="{{ !empty($post->is_favorite) ? '0' : '1'}}" data-type="post">
                                <i class="{{!empty($post->is_favorite) ? 'las' : 'lar'}} la-heart favrt-icon"></i>
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
                                <div class="upload-time"><label class="text-danger h4"><span class="bid-timer" data-time="{{!empty($post->auction_expiry_time) ? date('M d, Y H:i:s', strtotime($post->auction_expiry_time)) : ''}}" data-countdown="{{!empty($post->auction_expiry_time) ? date('Y/m/d H:i:s', strtotime($post->auction_expiry_time)) : ''}}">{{$post->auction_expiry_time}}</span>
                                    <span class=" d-none expire-timer">{{ __('EXPIRED') }}</span></label></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>
    <!-- Explore -->
    <section class="posts-area pt-65 pb-140 explore-list">
        <div class="container">
            <div class="section-title mb-40 d-flex align-items-center justify-content-between">
                <h2>{{ __('Explore') }}</h2>
                <a href="{{route('articles')}}" class="see-all-link mb-10">{{ __('See all') }}<i class="las la-angle-right"></i></a>
            </div>
            <div class="posts owl-carousel">
                @if(!empty($data['articles']))
                    @foreach($data['articles'] as $article)
                    <div class="post-item">
                        <div class="post-img">
                            <a href="{{route('article_detail', [$article->id])}}">
                                @if(!empty($article->image))
                                <img src="{{asset('uploads/article/'.$article->image)}}" alt="">
                                @endif
                            </a>
                            <div class="post-fav d-none">
                                <i class="las la-heart"></i>
                            </div>
                        </div>
                        <div class="post-body">
                            <a href="{{route('article_detail', [$article->id])}}" class="post-category">{{$article->title ?? ''}}</a>
                            <h2 class="post-title mb-15">{{!empty($article->description) ? mb_substr(strip_tags($article->description), 0, 100) : ''}}
                            </h2>
                            <div class="upload-date">{{\App\Helpers\CommonHelper::getPostTime($article->date)}}</div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>
    @section('scripts')
        <script type="text/javascript">
            $("body").on('click', '.category-item', function(){
                let divId = $(this).data('target');
                console.log(divId);
                $('.category-tab-content').find('.tab-pane').removeClass('show active');
                $('.category-tab-content').find(divId).addClass('show active');
            });
        </script>
    @endsection
@endsection
