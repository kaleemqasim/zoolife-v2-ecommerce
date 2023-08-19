<?php

namespace App\Models;

use App\Models\ItemLike;
use App\Models\ItemImage;
use App\Models\ItemComment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Item extends Model
{


    protected $table = 'items';
    private static $attributesQueue = [];
    protected $fillable = [
        'priorty',
        'allowComments',
        'fromUserId',
        'category',
        'subCategory',
        'likesCount',
        'ItemTitle',
        'itemDesc',
        'imgUrl',
        'videoUrl',
        'area',
        'country',
        'city',
        'age',
        'sex',
        'passport',
        'vaccine_detail',
        'showPhoneNumber',
        'showMessage',
        'showComments',
        'showWhatsapp',
        'phoneViewsCount',
        'removeAt',
        'modifyAt'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'auction_expiry_time' => 'datetime',
    ];

    // protected $hidden = ['user'];

    protected $appends = [
        'share_url',
    ];

    public function getShareUrlAttribute()
    {
        if ($this->post_type == 'auction') {
            return !empty($this->id) ? ($this->itemTitle . " " . url('auction/'.$this->id)) : '';
        } else {
            return !empty($this->id) ? ($this->itemTitle . " " . url('post/'.$this->id)) : '';
        }
    }

    public static function boot()
    {
        parent::boot();

        static::deleted(function ($post) {
            $post->itemFavorites()->delete();
            $post->report()->delete();
            $post->itemComments()->delete();
            $post->images()->delete();
            $post->itemLike()->delete();
            $post->notification()->delete();
        });
    }

    protected $dates = ['updated_at', 'created_at'];

    public function images()
    {
        return $this->hasMany(ItemImage::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'fromUserId', 'id');
    }

    public function itemFavorites()
    {
        return $this->hasMany(ItemFavourite::class, 'itemId', 'id');
    }

    public function biddingObject()
    {
        return $this->hasMany(BiddingModel::class, 'item_id', 'id');
    }

    public function biddings()
    {
        return $this->biddingObject()->get();
    }

    /**
     * @Purpose get Highest Bidding.
     * @return mixed
     */
    public function highestBidding()
    {
        return $this->biddingObject()->orderBy('bid_amount', 'desc')->first();
    }

    public function itemLike()
    {
        return $this->hasMany(ItemLike::class, 'itemId', 'id');
    }

    public function report()
    {
        return $this->hasMany(Report::class, 'ads_id', 'r_id');
    }

    public function itemComments()
    {
        return $this->hasMany(ItemComment::class, 'itemId', 'id');
    }

    public function notification()
    {
        return $this->hasMany(Notification::class, 'ads_id', 'id');
    }

    public function getRelatedAddAttribute()
    {
        $relatedads = self::where('category', $this->category)
            ->where('removeAt', '0')
            ->where('items.id',"!=", $this->id)
            ->limit(6)
            ->get(['id', 'imgUrl', 'city', 'itemTitle', 'created_at'])
            ->makeHidden('related_add');

        foreach ($relatedads as $key => &$item) {
            $item->imgUrl   = !empty($item->imgUrl) ? url('/uploads/ad/' . $item->imgUrl) : '';
        }

        return $relatedads;
    }

    public function getUsernameAttribute()
    {
        return $this->user ? $this->user->username : '';
    }

    public function getUseridAttribute()
    {
        return $this->user ? $this->user->id : '';
    }

    public function getDeviceTokenAttribute()
    {
        return $this->user ? $this->user->device_token : '';
    }

    public function getphoneAttribute()
    {
        return $this->user ? $this->user->phone : '';
    }

    public function getEmailAttribute()
    {
        return $this->user ? $this->user->email : '';
    }

    public function favrtitemStatus($user_id)
    {
        $isFavourite = $this->itemFavorites()->where('userId', $user_id)->first();

        // dd( $isFavourite);
        if ($isFavourite) {
            return 1;
        } else {
            return 0;
        }
        // return $this->itemFavorites()->where();
    }

    public function likeitemStatus($user_id)
    {

        $isLiked = DB::table('likes')->where('toUserId', $this->user_id)->where('itemId', $this->id)
            ->where('fromUserId', $user_id)->first();

        // $isLiked =  $this->ItemLike()->where('userId', $user_id)->first();

        if ($isLiked) {
            return 1;
        } else {
            return 0;
        }
    }

    public function reportStatus($user_id)
    {
        // dd($user_id);
        $isReport = DB::table('items_abuse_reports')
            ->where('abuseFromUserId', $user_id)
            ->where('abuseToItemId', $this->id)
            ->first();
        // dd($isReport);

        // $isReport =  $this->report()->where('user_id', $user_id)->first();

        if ($isReport) {
            return 1;
        } else {
            return 0;
        }

    }

    /**
     * @Purpose This function set some parameters in array, if Post of type of Auction.
     * @param $request
     * @return array
     */
    public static function enableAuctionFeature($request): array
    {
        $minBid = (float)$request->min_bid;
        $maxBid = (float)$request->max_bid;


        ## if minimum bid amount was empty or less than 1.
//        if ($minBid < 10 || $minBid > 5000) {
//            $dr['error'] = true;
//            $dr['status'] = 104;
//            $dr['message'] = trans('messages.invalid_min_bid_amount');
//            return $dr;
//        }

        ## if maximum bid amount is greater than 5000.
//        if ($maxBid > 5000 || $maxBid <= $minBid) {
//            $dr['error'] = true;
//            $dr['status'] = 104;
//            $dr['message'] = trans('messages.invalid_max_bid_amount');
//            return $dr;
//        }

        ## If its auction there should be necessary to fill day or hours
        if (empty($request->hours) && empty($request->days)) {
            $dr['error'] = true;
            $dr['status'] = 104;
            $dr['message'] = trans('messages.select_valid_auction_expiry_time');
            return $dr;
        }

        ## if hours are sending then it shouldn't be bigger than 12 and smaller than 1
        if (!empty($request->hours) && ($request->hours > 12 || $request->hours < 1)) {
            $dr['error'] = true;
            $dr['status'] = 104;
            $dr['message'] = trans('messages.invalid_hours');
            return $dr;
        }

        ## if days are sending then it shouldn't be bigger than 7 and smaller than 1
        if (!empty($request->days) && ($request->days > 7 || $request->days < 1)) {
            $dr['error'] = true;
            $dr['status'] = 104;
            $dr['message'] = trans('messages.invalid_days');
            return $dr;
        }

        $formatizeTime = Carbon::now();

        ## add time to time to set expiration date
        if (!empty($request->hours)) {
            $formatizeTime->addHours($request->hours);
        }

        ## add days to time to set expiration date
        if (!empty($request->days)) {
            $formatizeTime->addDays($request->days);
        }

        $blance['min_bid'] = $minBid;
        $blance['max_bid'] = $maxBid;
        $blance['post_type'] = 'auction';
        $blance['expiry_hours'] = $request->hours;
        $blance['expiry_days'] = $request->days;
        $blance['auction_expiry_time'] = $formatizeTime->format('Y-m-d H:i');
        return $blance;
    }

    /**
     * @Purpose Get All Future auctions posts
     * @return mixed
     */
    public static function getAllFutureAuctions()
    {
        $CurrentDateAndTime = Carbon::now()->format('Y-m-d H:i');
        return Item::where('post_type', 'auction')->where('auction_expiry_time', 'LIKE', $CurrentDateAndTime . '%')->get();
    }

    /**
     * @Purpose Declare post winner
     */
    public static function declarePostWinner()
    {
        $Posts = self::getAllFutureAuctions();
        foreach ($Posts as $post) {
            $highestBidding = $post->highestBidding();
            if ($highestBidding instanceof BiddingModel) {
                $highestBidding->is_winner = 1;
                $highestBidding->save();

                Log::info("Declaring Bid winner", [
                    'current_time' => Carbon::now()->format('Y-m-d H:i'),
                    'bidding' => $highestBidding
                ]);
            }
        }
    }

    public function getRelatedPost()
    {
        $relatedPosts = self::select('items.*', 'u.username as author', 'u.phone')
                        ->leftjoin('users as u','u.id','fromUserId')
                        ->where('post_type', $this->post_type)
                        ->where('category', $this->category)
                        ->where('items.id',"!=", $this->id)
                        ->where('removeAt', '0')
                        ->limit(6)
                        ->get();

        return $relatedPosts;
    }
}
