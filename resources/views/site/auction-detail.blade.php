@extends('layouts.site.app')

@section('content')
    @php
        $logged_in  = (\Auth::user());
        $currentUrl = \Request::url();
    @endphp
    <!-- post details -->
    <section class="post-details pt-65 pb-90">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-7 mb-30">
                    <div class="post-details-slider owl-carousel mb-30 pb-5">
                        <div class="post-details-single">
                            <div class="post-details-img">
                                @if(!empty($post->imgUrl))
                                <!-- <img src="{{$post->imgUrl}}" alt=""> -->
                                <img src="{{\App\Helpers\CommonHelper::getWebUrl($post->imgUrl, 'ad')}}" alt="">
                                @endif
                            </div>
                        </div>
                        @if(!empty($post->images))
                            @foreach($post->images as $img)
                            <div class="post-details-single">
                                <div class="post-details-img">
                                    <img src="{{$img->file_name}}" alt="">
                                </div>
                            </div>
                            @endforeach
                        @endif
                        @if(!empty($post->videoUrl))
                        <div class="post-details-single">
                            <div class="post-details-img">
                                <video src="{{\App\Helpers\CommonHelper::getWebUrl($post->videoUrl, 'ad_video')}}" alt="" controls style="width: 100%;"></video>
                            </div>
                        </div>
                        @endif
                    </div>
                    <label class="text-danger h4">{{ __('Expire Time') }} : <span class="bid-timer" id="bid-timer" data-time="{{!empty($post->auction_expiry_time) ? date('M d, Y H:i:s', strtotime($post->auction_expiry_time)) : ''}}">{{$post->auction_expiry_time}}</span>
                        <span class=" d-none" id="expire-timer">{{ __('EXPIRED') }}</span></label>
                    <div id="bid-section">
                        <div class="mb-20">
                            <div class="place-bid-form">
                                <input type="text" placeholder="{{ __('Enter Bid') }}" id="bid_amount">
                                <a href="#" id="placeBid" data-id="{{$post->id}}" class="btn theme-btn">{{ __('Place Bid') }}</a>
                            </div>
                            <div class="error text-danger" id="bid_amount_error"></div>
                        </div>
                        <div class="min-bid-price">{{ __('Min Bid:')}} <span>{{$post->min_bid }}</span></div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-5">
                    <div class="post-author-details">
                        <h2 class="title">{{$post->itemTitle ?? ''}}</h2>
                        <div class="post-author-location">
                            <div class="icon-text" style="color: var(--theme-color);">
                                <i class="lar la-calendar"></i>
                                <span>{{\App\Helpers\CommonHelper::getPostTime($post->created_at)}}</span>
                            </div>
                            <div class="icon-text">
                                <i class="lar la-user"></i>
                                <span>{{$post->author ?? ''}}</span>
                            </div>
                            <div class="icon-text">
                                <i class="las la-map-marker"></i>
                                <span>{{$post->city ?? ''}}, {{$post->country ?? ''}}</span>
                            </div>
                            <div class="icon-text">
                                <i class="las la-id-card"></i>
                                <span>{{$post->phone ?? ''}}</span>
                            </div>
                        </div>
                        <div class="post-action-btn">
                            <a href="tel:{{$post->phone ?? ''}}" class="btn theme-btn"><i class="las la-phone fs-25"></i>{{ __('Call') }}</a>
                            <a href="#" class="btn theme-btn-outline" data-bs-toggle="modal" data-bs-target="#popupModal"><i class="lab la-rocketchat fs-25"></i>{{ __('Chat') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- post specification -->
    <section class="post-specification pb-140 d-none">
        <div class="container">
            <div class="section-title mb-30">
                <h2>{{ __('Post Specification') }}</h2>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="post-specification-content">
                        <p>{{$post->itemDesc ?? ''}}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- post info -->
    <section class="post-info pb-140">
        <div class="container">
            <div class="section-title mb-30">
                <h2>{{ __('Details') }}</h2>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="post-info-box">
                        <div class="post-info-list">
                            <div>{{ __('Age') }}</div>
                            <div>{{$post->age ?? ''}}</div>
                        </div>
                        <div class="post-info-list">
                            <div>{{ __('Sex') }}</div>
                            <div>{{$post->sex ?? ''}}</div>
                        </div>
                        <div class="post-info-list">
                            <div>{{ __('Passport') }}</div>
                            <div>{{$post->passport ?? ''}}</div>
                        </div>
                        <div class="post-info-list">
                            <div>{{ __('Vacine Details') }}</div>
                            <div>{{$post->vaccine_detail ?? ''}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- post description -->
    <section class="post-description pb-140">
        <div class="container">
            <div class="section-title mb-30">
                <h2>{{ __('Description') }}</h2>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="post-specification-content mb-30">
                        <p>{{$post->itemDesc ?? ''}}</p>
                    </div>
                    <div class="description-btn mt-5">
                        <a onclick="copyShareLink('{{$currentUrl}}') .then(() => alert('Link copied !'))" class="btn theme-btn w-auto"><i class="las la-share fs-25"></i>{{ __('Share') }}</a>
                        <a target="_blank" href="https://wa.me/{{$post->phone}}?text={{urlencode($currentUrl)}}" class="btn btn-green w-auto"><i class="lab la-whatsapp fs-25"></i>{{ __('Whatsapp') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- warning msg -->
    <section class="warning-message pt-60 pb-150">
        <div class="container">
            <div class="alert alert-warning alert-dismissible fade show position-relative" role="alert">
                <h4 class="alert-heading">{{ __('Warning!') }}</h4>
                <p>{{__('“Zoolife” warns against deadline outside the application and strongly advises to deal through Private messages only, to deal hand in hand, to beware of agents, and to make sure that the bank account belongs to the same person who owns the goods.')}}</p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="las la-times"></i></button>
            </div>
        </div>
    </section>
    <!-- Recent Bidders -->
    <section class="pb-140">
        <div class="container">
            <div class="section-title mb-30">
                <h2>{{ __('Recent Bidders') }}</h2>
            </div>
            <div class="all-bidders">
                <div class="bidder-list">
                    @if($post->biddingObject->count())
                        @foreach($post->biddingObject as $i => $bid)
                        @php $bidder = $bid->user; @endphp
                        @if($i == 0)
                            <div class="winner-section text-center d-none" id="winner-section">
                                <div class="winner-buttone d-inline-block">
                                    <div class="winner-title">Winner Contact</div>
                                    <a href="tel:{{$bidder->phone}}" class="btn theme-btn"><i class="las la-phone fs-25"></i></a>
                                    <a target="_blank" href="https://wa.me/{{$bidder->phone}}?text={{urlencode($currentUrl)}}" class="btn btn-green w-auto"><i class="lab la-whatsapp fs-25"></i></a>
                                    <a href="#" class="btn theme-btn-outline" data-bs-toggle="modal" data-bs-target="#popupModal"><i class="lab la-rocketchat fs-25"></i></a>
                                </div>
                            </div>
                        @endif
                        <div class="single-bid">
                            <div class="bidder-img">
                                <a href="#"><img src="/assets/img/author.png" alt=""></a>
                            </div>
                            <div class="bid-text-box">
                                <div>
                                    <a href="#" class="bidder-name">{{$bidder->username ?? ''}}</a>
                                    <span class="bid-time">{{$bid->created_at->diffForHumans()}}</span>
                                </div>
                                <div class="bid-price"><i class="las la-hand-holding-usd"></i> {{$bid->bid_amount}}</div>
                            </div>
                        </div>
                        @endforeach
                        {{-- <div class="bid-action-btn">
                            <a href="#" class="btn btn-link m-auto">{{ __('View All') }}</a>
                        </div> --}}
                    @else
                    <div class="single-bid no-bids">No Biddings yet!</div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @section('scripts')
    <script type="text/javascript">
        $(document).ready(function(){
            countdown();
        })
    </script>
    @endsection
@endsection
