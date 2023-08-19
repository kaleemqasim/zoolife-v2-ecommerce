<?php

namespace App\Http\Controllers\Site;

use App\City;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Category;
use App\Models\Article;
use App\Models\BiddingModel;
use App\Models\Item;
use App\Models\ItemComment;
use App\Models\ItemFavourite;
use App\Models\ItemImage;
use App\Models\Like;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public $imagePath;
    public $videoPath;

    public function __construct()
    {
        $this->imagePath = public_path('uploads/ad/');
        $this->videoPath = public_path('uploads/ad_video/');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($type = "normal", Request $request)
    {
        $user   = \Auth::user();
        $userId = !empty($user->id) ? $user->id : 0;

        $data = [];
        $data['sliders'] = Slider::where('status', '=', 1)->get();
        $data['categories'] = Category::get();
        $posts = Item::select('items.*', 'u.name as author', \DB::raw("IF(if.itemId > 0, 1, 0) as is_favorite"))
                        ->leftjoin('users as u','u.id','fromUserId')
                        ->leftJoin("item_favorites as if", function($query) use ($userId) {
                            $query->on('if.itemId','items.id');
                            $query->where('if.userId', $userId);
                            // $query->where('if.type', 'post');
                        })
                        ->where('post_type', $type)
                        ->orderBy('updated_at', 'DESC');
        $searchParam = [];

        if(!empty($request->cat)) {
            $posts->where(function($where) use($request) {
                $where->where('category', $request->cat);
                $where->orWhere('subCategory', $request->cat);
            });
            $searchParam['cat'] = $request->cat;
        }

        if(!empty($request->q)) {
            $posts->where(function($where) use($request) {
                $where->where('itemTitle', 'LIKE', '%' . $request->q . '%');
                $where->orWhere('itemDesc', 'LIKE', '%' . $request->q . '%');
            });
            $searchParam['q'] = $request->q;
        }
        
        $data['posts'] = $posts->paginate(5);
        
        // print_r($data['articles']->toArray());
        // exit;
        return view('site.post-list', compact('data'));
    }

    public function postShow($slug, Request $request)
    {
        $user   = \Auth::user();
        $userId = !empty($user->id) ? $user->id : 0;

        $data = [];
        $post = Item::select('items.*', 'u.username as author', 'u.phone', \DB::raw("IF(if.itemId > 0, 1, 0) as is_favorite"), \DB::raw("IF(l.itemId > 0, 1, 0) as is_liked"))
                        ->with('images')
                        ->with(['itemComments'=> function($query){
                            // $query->limit(10)->latest('co');
                            $query->latest();
                        }])
                        ->leftjoin('users as u','u.id','fromUserId')
                        ->leftJoin("item_favorites as if", function($query) use ($userId) {
                            $query->on('if.itemId','items.id');
                            $query->where('if.userId', $userId);
                            // $query->where('if.type', 'post');
                        })
                        ->leftJoin("likes as l", function($query) use ($userId) {
                            $query->on('l.itemId','items.id');
                            $query->where('l.fromUserId', $userId);
                            // $query->where('l.type', 'post');
                        })
                        ->where('post_type', 'normal')
                        ->where('items.id', $slug)
                        ->first();

        if (!empty($post)) {
            $post->relatedPosts = $post->getRelatedPost();
            return view('site.post-detail', compact('data', 'post'));
        } else {
            abort(404);
        }
    }

    public function auctionShow($slug, Request $request)
    {
        $user   = \Auth::user();
        $userId = !empty($user->id) ? $user->id : 0;

        $data = [];
        $post = Item::select('items.*', 'u.username as author', 'u.phone')
                        ->with('images')
                        ->with(['biddingObject'=> function($query){
                            $query->with('user');
                            $query->latest();
                        }])
                        ->leftjoin('users as u','u.id','fromUserId')
                        ->where('post_type', 'auction')
                        ->where('items.id', $slug)
                        ->first();

        if (!empty($post)) {
            return view('site.auction-detail', compact('data', 'post'));
        } else {
            abort(404);
        }
    }

    public function loadPostOrAuctionModal($type = 0, $id = 0, Request $request)
    {
        $data = $post = [];
        $type = !empty($request->type) && in_array($request->type, ['auction', 'normal']) ? $request->type : 'normal';
        $cities = City::all();
        $categories = Category::where('mainCategoryId',0)->get(['id','title','english_title']);

        if(!empty($id)){
            $post = Item::select('items.*', 'u.username as author', 'u.phone', 'c.title as sub_category', 'c.english_title as sub_category_en')
            ->with('images')
            ->leftjoin('users as u','u.id','fromUserId')
            ->leftjoin('category as c','c.id','subCategory')
            ->where('post_type', $type)
            ->where('items.id', $id)
            ->first();
        }

        $data['type'] = $type;

        return view('site.post-auction-modal', compact('data', 'post', 'cities', 'categories'));
    }

    public function savePost(Request $request)
    {
        $user_id = \Auth::user()->id;
        $id      = $request->id;

        // Put validation
        $validator = Validator::make($request->all(), [
            'imgUrl'   => 'required_without:old_imgUrl|mimes:jpeg,jpg,png,gif,svg,wbmp,webp',
            'sex'      => 'required|in:male,female',
            'passport' => 'required|in:yes,no',
            'category' => 'required',
            'age'      => 'required',
            'itemTitle'=> 'required',
            'post_type'=> 'required',
            // 'videoUrl' => 'required_if:old_videoUrl,""|required_if:post_type,auction|mimes:mp4,3gp,avi,mpeg,flv,mov,qt',
            'videoUrl' => 'mimes:mp4,3gp,avi,mpeg,flv,mov,qt',
            'min_bid'  => 'required_if:post_type,auction',
            'days'  => 'required_if:post_type,auction',
            'hours'  => 'required_if:post_type,auction',
        ], [
            "imgUrl.required_without" => "Please Select Image",
            "min_bid.required_if" => "Please Enter Bid Price",
            "days.required_if" => "Please Select Bid day",
            "hours.required_if" => "Please Select Bid hours",
        ]);

        // $validator->sometimes('videoUrl', 'required', function($input){
        //     return (($input->old_videoUrl == '') && ($input->post_type == 'auction'));
        // });

        if ($validator->fails()) {
            return response()->json([
                'status'  => 402,
                'error'   => true,
                'message' => trans('messages.validation_error'),
                'errors'   => $validator->errors(),
            ], 200);
        }

        $imagePath = public_path('uploads/ad/');
        $videoPath = public_path('uploads/ad_video/');

        // $blance = array(
        //     'fromUserId'      => $user_id,
        //     'priority'        => $request->priority??0,
        //     'category'        => $request->category,
        //     // 'subCategory'     => $request->subCategory,
        //     'itemTitle'       => $request->itemTitle,
        //     'itemDesc'        => $request->itemDesc,
        //     'showComments'    => $request->showComments ? 1 : 0,
        //     'showPhoneNumber' => $request->showPhoneNumber ? 1 : 0,
        //     'showMessage'     => $request->showMessage ? 1 : 0,
        //     'showWhatsapp'    => $request->showWhatsapp ? $request->showWhatsapp : 0,
        //     'city'            => $request->city,
        //     'age'             => !empty($request->age) ? $request->age : "",
        //     'sex'             => !empty($request->sex) ? strtolower($request->sex) : 'male',
        //     'passport'        => !empty($request->passport) ? $request->passport : "no",
        //     'vaccine_detail'  => !empty($request->vaccine_detail) ? strtolower($request->vaccine_detail) : '',
        //     'country'         => !empty($request->country) ? $request->country : "",
        //     'post_type'       => $request->post_type,
        //     'min_bid'         => $request->min_bid,
        //     'expiry_days'     => $request->days,
        //     'expiry_hours'    => $request->hours,
        //     'updated_at'      => Carbon::now()->format('Y-m-d H:i'),
        // );

        if (!empty($id)) {
            $item = Item::find($id);
            // if (!empty($item)) {
            //     // $item->update($blance);
            // }
        } else {
            // $blance['created_at'] = Carbon::now()->format('Y-m-d H:i');
            $item = new Item();
            $item->created_at = Carbon::now()->format('Y-m-d H:i');
        }

        $item->fromUserId      = $user_id;
        $item->priority        = $request->priority??0;
        $item->category        = $request->category;
        $item->subCategory     = !empty($request->subCategory) ? $request->subCategory : 0;
        $item->itemTitle       = $request->itemTitle;
        $item->itemDesc        = $request->itemDesc;
        $item->showComments    = $request->showComments ? 1 : 0;
        $item->showPhoneNumber = $request->showPhoneNumber ? 1 : 0;
        $item->showMessage     = $request->showMessage ? 1 : 0;
        $item->showWhatsapp    = $request->showWhatsapp ? $request->showWhatsapp : 0;
        $item->city            = $request->city;
        $item->age             = !empty($request->age) ? $request->age : "";
        $item->sex             = !empty($request->sex) ? strtolower($request->sex) : "male";
        $item->passport        = !empty($request->passport) ? $request->passport : "no";
        $item->vaccine_detail  = !empty($request->vaccine_detail) ? strtolower($request->vaccine_detail) : "";
        $item->country         = !empty($request->country) ? $request->country : "";
        $item->post_type       = $request->post_type;
        
        $item->updated_at      = Carbon::now()->format("Y-m-d H:i");

        ## if bid is type of auction.
        if ($request->post_type == "auction") {
            $auctionParam = Item::enableAuctionFeature($request);
            if (isset($auctionParam['error']) && $auctionParam['error'] == true) {
                // return $auctionParam;
                $result = [
                    'status'  => 500,
                    'message' => $auctionParam['message'],
                ];
                return response()->json($result);
            } else {
                $item->min_bid      = $auctionParam['min_bid'];
                $item->expiry_days  = $auctionParam['expiry_days'];
                $item->expiry_hours = $auctionParam['expiry_hours'];
                $item->auction_expiry_time = $auctionParam['auction_expiry_time'];
            }
        }

        $item->save();

        // $id = DB::table('items')->insertGetId($blance);
        $id = $item->id;
        if ($id) {

            // Upload image in ad item
            $imageUrl = !empty($request->old_imgUrl) ? $request->old_imgUrl : "";
            if ($request->hasFile('imgUrl')) {
                $image     = $request->file('imgUrl');
                $imageName = time() . '_' . $id . '.' . $image->getClientOriginalExtension();
                $image->move($imagePath, $imageName);
                $imageUrl = $imageName;
            }

            // Upload video in ad item
            $videoUrl = !empty($request->old_videoUrl) ? $request->old_videoUrl : "";
            if ($request->hasFile('videoUrl')) {
                $video     = $request->file('videoUrl');
                $videoName = time() . '_' . $id . '.' . $video->getClientOriginalExtension();
                $video->move($videoPath, $videoName);
                $videoUrl = $videoName;
            }

            // save video and image in ad item
            $item           = Item::where('id', '=', $id)->first();
            $item->imgUrl   = $imageUrl;
            $item->videoUrl = $videoUrl;
            $item->save();

             if ($request->hasFile('images')) {
                $images    = $request->file('images');
                $allImages = [];
                foreach ($images as $k => $image) {
                    $imageName = time() . $k . '_' . $id . '.' . $image->getClientOriginalExtension();
                    $image->move($imagePath, $imageName);
                    $imageUrl = $imageName;

                    $allImages[] = [
                        'item_id'   => $id,
                        'file_name' => $imageUrl,
                    ];
                }

                if (!empty($allImages)) {
                    $image = ItemImage::insert($allImages);
                }
            } 

            $result = [
                'status'  => 200,
                'message' => trans('messages.post_added'),
            ];
        } else {
            $result = [
                'status'  => 500,
                'message' => trans('messages.unable_to_process_request'),
            ];
        }
        return response()->json($result);
    }

    public function deletePost(Request $request)
    {
        $user   = \Auth::user();
        $userId = !empty($user->id) ? $user->id : 0;
        
        $item = Item::where('id', $request->id)->where('fromUserId', $userId)->first();

        if (!empty($item)) {
            $item->delete();
            $result = [
                'status'  => 200,
                'message' => trans('messages.post_deleted'),
            ];
        } else {
            $result = [
                'status'  => 500,
                'message' => "Something went wrong! please try againg after refresh",
            ];
        }
        return response()->json($result);
    }

    public function deletePostImage(Request $request)
    {
        $user   = \Auth::user();
        $userId = !empty($user->id) ? $user->id : 0;
        
        $image = ItemImage::where(['item_id' => $request->item_id, 'id' => $request->id])->first();

        if (!empty($image)) {
            // Remove old Image
            if (!empty($image->file_name)) {
                $parts        = explode('/', $image->file_name);
                $oldImage     = end($parts);
                $oldImagePath = $this->imagePath . $oldImage;
                if (file_exists($oldImagePath)) {
                    @unlink($oldImagePath);
                }
            }

            $image->delete();
            $result = [
                'status'  => 200,
                'message' => "Image removed successfully",
            ];
        } else {
            $result = [
                'status'  => 500,
                'message' => "Something went wrong! please try againg after refresh",
            ];
        }
        return response()->json($result);
    }

    public function getSubcategory(Request $request)
    {
        $data = [];
        $cities = City::all();
        $categories = Category::where('mainCategoryId',">",0)->select('id','title','english_title');

        if (!empty($request->cat_id)) {
            $categories->where('mainCategoryId', $request->cat_id);
        }

        if (!empty($request->search)) {
            $categories->where(function($where) use($request) {
                $where->where('title', 'LIKE', '%' . $request->search . '%');
                $where->orWhere('english_title', 'LIKE', '%' . $request->search . '%');
            });
        }

        $categories = $categories->get();

        $data['results'] = $categories->toArray();
        return response()->json($data);
    }

    public function doFavourite(Request $request)
    {
        $user   = \Auth::user();
        $userId = !empty($user->id) ? $user->id : 0;
        
        $item = ItemFavourite::where('itemId',$request->id)->where('userId', $userId)->first();

        if (!empty($item)) {
            $item->delete();
            $msg = trans('messages.item_removed_fvrt');
        } else{
            $insert = [
                'itemId' => $request->id,
                'userId' => $userId,
                'co'     => date('Y-m-d H:i:s'),
                'uo'     => date('Y-m-d H:i:s'),
            ];
            ItemFavourite::insert($insert);
            $msg = trans('messages.item_added_fvrt');
        }

        return response()->json(['status'=>200, 'message'=>$msg]);
    }

    public function doLike(Request $request)
    {
        $user   = \Auth::user();
        $userId = !empty($user->id) ? $user->id : 0;
        
        $item = Like::where('itemId',$request->id)->where('fromUserId', $userId)->first();

        if (!empty($item)) {
            $item->delete();
            $msg = trans('messages.unliked');
        } else{
            $insert = [
                'itemId' => $request->id,
                'fromUserId' => $userId,
                'createAt'   => time(),
            ];
            Like::insert($insert);
            $msg = trans('messages.liked');
        }

        return response()->json(['status'=>200, 'message'=>$msg]);
    }

    public function doComment(Request $request)
    {
        $user   = \Auth::user();
        $userId = !empty($user->id) ? $user->id : 0;

        // validation
        $validator = Validator::make($request->all(), [
            'comment' => 'required',
        ], [
            // "comment.required" => "Please enter comment before saving."
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 402,
                'error'   => true,
                'message' => trans('messages.validation_error'),
                'errors'   => $validator->errors(),
            ], 200);
        }

        $data = [
            'itemId' => $request->id,
            'userId' => $userId,
            'message'=> $request->comment,
            'co'     => Carbon::now()->format("Y-m-d H:i:s"),
        ];
        $comment = ItemComment::create($data);

        $tr = "<div class='single-comment'>
                    <div class='comment-img'>
                        <a href='#'><img src='/assets/img/author.png' alt=''></a>
                    </div>
                    <div class='comment-text-box'>
                        <div class='d-flex align-items-center justify-content-between'>
                            <a href='#' class='commenter-name'>{$comment->user->username}</a>
                            <span class='comment-time'>{$comment->co->diffForHumans()}</span>
                        </div>
                        <div class='comment-text'>{$comment->message}</div>
                    </div>
                </div>";
        return response()->json(['status'=>200, 'message'=>trans('messages.comment_posted'),'tr'=>$tr]);
    }

    public function placeBid(Request $request)
    {
        $user    = \Auth::user();
        $userId  = !empty($user->id) ? $user->id : 0;
        $auction = Item::where(['post_type' => 'auction', 'id' => $request->item_id])->first();

        if (!empty($auction)) {
            $validator = Validator::make($request->all(), [
                'bid_amount' => 'required|numeric|gt:'.$auction->min_bid,
            ], [
                "bid_amount.required" => "Please enter bid amount before placing bid.",
                "bid_amount.min"      => "Bid amount should be minimum {$auction->min_bid}"
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => 402,
                    'error'   => true,
                    'message' => trans('messages.validation_error'),
                    'errors'   => $validator->errors(),
                ], 200);
            }

            $request->merge(["fromUserId" => $userId]);
            $request->merge(["amount" => $request->bid_amount]);
            $isBidSaved = BiddingModel::addBidding($request);

            if ($isBidSaved) {
                $auction->min_bid = ((float) $request->bid_amount);
                $auction->save();

                $bid = BiddingModel::latest()->first();
                $usrname = $bid->user ? $bid->user->username : '';
                $tr = "<div class='single-bid'>
                            <div class='bidder-img'>
                                <a href='#'><img src='/assets/img/author.png' alt=''></a>
                            </div>
                            <div class='bid-text-box'>
                                <div>
                                    <a href='#' class='bidder-name'>{$usrname}</a>
                                    <span class='bid-time'>{$bid->created_at->diffForHumans()}</span>
                                </div>
                                <div class='bid-price'><i class='las la-hand-holding-usd'></i> {$bid->bid_amount}</div>
                            </div>
                        </div>";
                return response()->json([
                    'status'  => 200,
                    'error'   => false,
                    'message' => trans('messages.bid_save_for_user'),
                    'tr'      => $tr
                ]);
            } else {
                return response()->json([
                    'status'  => 500,
                    'error'   => true,
                    'message' => trans('messages.some_thing_went_wrong'),
                ]);
            }
        } else {
            return response()->json([
                'status'  => 500,
                'error'   => true,
                'message' => "No auction Found",
            ]);
        }
    }

}
