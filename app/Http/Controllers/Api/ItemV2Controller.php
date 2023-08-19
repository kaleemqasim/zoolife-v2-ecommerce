<?php

namespace App\Http\Controllers\Api;

use App\City;
use App\Http\Controllers\Controller;
use App\Models\BiddingModel;
use App\Models\Category;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;
use DemeterChain\B;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponseTrait;

class ItemV2Controller extends Controller
{
    use ApiResponseTrait;
    
    public function reportApi_ads(Request $request)
    {
        $user_id = $request['user_id'];
        $ads_id  = $request['ads_id'];
        $content = $request['content'];

        if ($user_id && $ads_id && $content) {
            $report = Report::create([
                'user_id' => $user_id,
                'ads_id'  => $ads_id,
                'content' => $content,
                'status'  => 1,
            ]);

            if ($report) {
                return $this->successResponse(trans('messages.report_added'), [], 200);
            } else {
                return $this->errorResponse(trans('messages.can_not_add_data'), [], 400);
            }
        } else {
            return $this->errorResponse('Please fill all the fields', [], 403);
        }
    }

    public function get_by_item_report(Request $request)
    {
        $itemId       = $request->id;
        $data = Report::where('ads_id', '=', $itemId)->get();
        
        return $this->successResponse(trans('messages.request_success'), [], 200);
    }
    
    public function items(Request $request)
    {
        $user_id      = $request->user_id;

        $items = Item::where('removeAt', 0)
            ->where('post_type', 'normal')
            ->where('category', '!=', '4000')
            ->orderBy('priority', 'DESC', 'id', 'DESC')
            ->with('images')
            ->orderBy('priority', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->paginate(env('PER_PAGE'));

        $items->each(function ($item, $key) use ($user_id) {
           if(empty($item->price)){
                    $item->price = $item->max_bid;
            }
            $item->imgUrl   = !empty($item->imgUrl) ? url('/uploads/ad/' . $item->imgUrl) : '';
            $item->videoUrl = !empty($item->videoUrl) ? url('/uploads/ad_video/' . $item->videoUrl) : '';
            if (isset($user_id)) {
                $item->favrtitem_status = $item->favrtitemStatus($user_id);
                $item->likeitem_status  = $item->likeitemStatus($user_id);
                $item->report_status    = $item->reportStatus($user_id);
            } else {
                $item->favrtitem_status = 0;
                $item->likeitem_status  = 0;
                $item->report_status    = 0;
            }
            $item->setAppends([
                'share_url', 'username', 'userid', 'device_token', 'phone', 'email', 'related_add',
            ]);
        })->makeHidden('user');
        
        return $this->successResponse(trans('messages.request_success'), $items, 200);

    }

    public function get_all_delivery(Request $request)
    {
        $dr['status'] = 100;
        $dr['data']   = [];
        $user_id      = $request->user_id;

        // $items = DB::table('items')->select('*')->where('removeAt', '=', 0)
        //     ->where('category', '=', '4000')
        //     ->orderBy('priority', 'DESC', 'id', 'DESC')->get();

        $items = Item::where('removeAt', '=', 0)
            ->where('category', '=', '4000')
            ->where('post_type', $request->get('post_type', 'normal'))
            ->orderBy('priority', 'DESC', 'id', 'DESC')
            ->with('images')
            ->orderBy('priority', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->paginate(env('PER_PAGE'));

        $items->each(function ($item, $key) use ($user_id) {
            $item->imgUrl   = !empty($item->imgUrl) ? url('/uploads/ad/' . $item->imgUrl) : '';
            $item->videoUrl = !empty($item->videoUrl) ? url('/uploads/ad_video/' . $item->videoUrl) : '';
            if (isset($user_id)) {
                $item->favrtitem_status = $item->favrtitemStatus($user_id);
                $item->likeitem_status  = $item->likeitemStatus($user_id);
                $item->report_status    = $item->reportStatus($user_id);
            } else {
                $item->favrtitem_status = 0;
                $item->likeitem_status  = 0;
                $item->report_status    = 0;
            }
            $item->setAppends([
                'share_url', 'username', 'userid', 'device_token', 'phone', 'email', 'related_add',
            ]);
        })->makeHidden('user');
        
        
        return $this->successResponse(trans('messages.request_success'), $items, 200);
    }

    public function getAllAuctionPosts(Request $request)
    {
        \Log::info(json_encode($request->all()));
        $dr['status'] = 100;
        $dr['data']   = [];
        $search       = $request->search;
        $items        = Item::where('post_type', '=', 'auction')
            ->where(function ($query) use ($search, $request) {
                if ($request->city) {
                    $query->where('city', '=', "$request->city");
                }
                if ($request->country) {
                    $query->where('category', '=', "$request->category");
                }

            })->orderBy('id', 'DESC')
            ->paginate();

        $items->each(function ($item, $key) {
            $item->imgUrl   = !empty($item->imgUrl) ? url('/uploads/ad/' . $item->imgUrl) : '';
            $item->videoUrl = !empty($item->videoUrl) ? url('/uploads/ad_video/' . $item->videoUrl) : '';
            $latestBid            = $item->biddingObject()->orderBy('id', 'desc')->first();
            $item->latest_bid     = 'SAR ' . number_format($latestBid ? $latestBid->bid_amount : 0, 2);
            $item->remaining_time = !empty($item->auction_expiry_time) ? $item->auction_expiry_time->diffForHumans() : 0;
        });
        
        return $this->successResponse(trans('messages.request_success'), $items, 200);

    }

    public function item_search(Request $request)
    {
        $user_id      = $request->user_id;

        $search = $request->search;

        $items = Item::where('itemTitle', 'like', "%{$search}%")->orWhere('itemDesc', 'like', "%{$search}%")->orWhere('city', 'like', "%{$search}%")->orWhere('country', 'like', "%{$search}%")->where('category', '!=', '4000');
        if ($request->auction == 1) {
            $items->where('post_type', 'auction');
        }
        $items->orderBy('id', 'DESC');
        $items = $items->get();

        $items->each(function ($item, $key) use ($user_id) {
            $item->imgUrl   = !empty($item->imgUrl) ? url('/uploads/ad/' . $item->imgUrl) : '';
            $item->videoUrl = !empty($item->videoUrl) ? url('/uploads/ad_video/' . $item->videoUrl) : '';
            if (isset($user_id)) {
                $item->favrtitem_status = $item->favrtitemStatus($user_id);
                $item->likeitem_status  = $item->likeitemStatus($user_id);
                $item->report_status    = $item->reportStatus($user_id);
            } else {
                $item->favrtitem_status = 0;
                $item->likeitem_status  = 0;
                $item->report_status    = 0;
            }
            $item->setAppends([
                'share_url', 'username', 'userid', 'device_token', 'phone', 'email', 'related_add',
            ]);
        })->makeHidden('user');

        return $this->successResponse(trans('messages.request_success'), $items, 200);
    }

    public function get_all_item_by_category(Request $request)
    {
        $user_id      = $request->category_id;

        if ($request->filled('category_id') && $request->filled('subcategory_id')) {
            $items = Item::where('category', $request->category_id)
                ->where('post_type', $request->get('post_type', 'normal'))
                ->orwhere('subCategory', $request->subcategory_id)
                ->orderBy('priority', 'DESC', 'id', 'DESC')
                ->with('images')
                ->orderBy('priority', 'DESC')
                ->orderBy('created_at', 'DESC')
                ->paginate(env('PER_PAGE'));
        } else if ($request->filled('category_id')) {
            $items = Item::where('category', $request->category_id)
                ->where('post_type', $request->get('post_type', 'normal'))
                ->orderBy('priority', 'DESC', 'id', 'DESC')
                ->with('images')
                ->orderBy('priority', 'DESC')
                ->orderBy('created_at', 'DESC')
                ->paginate(env('PER_PAGE'));
        } else if ($request->filled('subcategory_id')) {
            $items = Item::where('subCategory', $request->subcategory_id)
                ->where('post_type', $request->get('post_type', 'normal'))
                ->orderBy('priority', 'DESC', 'id', 'DESC')
                ->with('images')
                ->orderBy('priority', 'DESC')
                ->orderBy('created_at', 'DESC')
                ->paginate(env('PER_PAGE'));
        } else {
            $dr['error']   = true;
            $dr['message'] = "please enter category_id or subcategory_id";
            return Response::json($dr);
        }

        $items->each(function ($item, $key) use ($user_id) {
            $item->imgUrl   = !empty($item->imgUrl) ? url('/uploads/ad/' . $item->imgUrl) : '';
            $item->videoUrl = !empty($item->videoUrl) ? url('/uploads/ad_video/' . $item->videoUrl) : '';
            if (isset($user_id)) {
                $item->favrtitem_status = $item->favrtitemStatus($user_id);
                $item->likeitem_status  = $item->likeitemStatus($user_id);
                $item->report_status    = $item->reportStatus($user_id);
            } else {
                $item->favrtitem_status = 0;
                $item->likeitem_status  = 0;
                $item->report_status    = 0;
            }
            $item->setAppends([
                'share_url', 'username', 'userid', 'device_token', 'phone', 'email', 'related_add',
            ]);
            
            $share_url_information = [
                    'subject' => __('messages.share_title') . "\n" . $item->share_url,
                    'title' => __('messages.share_title') . "\n" . $item->share_url
                ];
            $item->share_url_details = $share_url_information;
        })->makeHidden('user');

        return $this->successResponse(trans('messages.request_success'), $items, 200);
    }

    public function get_item(Request $request)
    {

        $id      = $request->id;
        $user_id = $request->user_id;

        if ($request->filled('id')) {
            $item = Item::find($request->id);
            if ($item) {
                if(empty($item->price)){
                    $item->price = $item->max_bid;
                }
                $item = $item->load('images');
                $item->imgUrl   = !empty($item->imgUrl) ? url('/uploads/ad/' . $item->imgUrl) : '';
                $item->videoUrl = !empty($item->videoUrl) ? url('/uploads/ad_video/' . $item->videoUrl) : '';
                if (isset($user_id)) {
                    $item->favrtitem_status = $item->favrtitemStatus($user_id);
                    $item->likeitem_status  = $item->likeitemStatus($user_id);
                    $item->report_status    = $item->reportStatus($user_id);
                } else {
                    $item->favrtitem_status = 0;
                    $item->likeitem_status  = 0;
                    $item->report_status    = 0;
                }
                $item->setAppends([
                    'share_url', 'username', 'userid', 'device_token', 'phone', 'email', 'related_add',
                ])->makeHidden('user');
                $share_url_information = [
                    'subject' => __('messages.share_title') . "\n" . $item->share_url,
                    'title' => __('messages.share_title') . "\n" . $item->share_url
                ];
                $item->share_url_details = $share_url_information;

                return $this->successResponse(trans('messages.request_success'), $item, 200);
            } else {
                return $this->successResponse(trans('messages.request_success'), [], 200);
            }
        } else {
            return $this->errorResponse("Please enter post id", [], 400);
        }
    }

    public function get_post_by_user(Request $request)
    {
        $dr['status'] = 100;
        $dr['data']   = [];
        $id           = $request->id;
        $user_id      = $request->user_id;

        if ($user_id) {
            $postType = !empty($request->post_type) ? strtolower($request->post_type) : "";

            $items = Item::with('images')->where('category', '!=', '4000')->where('fromUserId', '=', $user_id);

            if (in_array($postType, ['auction', 'normal'])) {
                $items = $items->where('post_type', $postType);
            }

            $items = $items->get();

            $items->each(function ($item, $key) use ($user_id) {
                $item->imgUrl   = !empty($item->imgUrl) ? url('/uploads/ad/' . $item->imgUrl) : '';
                $item->videoUrl = !empty($item->videoUrl) ? url('/uploads/ad_video/' . $item->videoUrl) : '';
                if (isset($user_id)) {
                    $item->favrtitem_status = $item->favrtitemStatus($user_id);
                    $item->likeitem_status  = $item->likeitemStatus($user_id);
                    $item->report_status    = $item->reportStatus($user_id);
                } else {
                    $item->favrtitem_status = 0;
                    $item->likeitem_status  = 0;
                    $item->report_status    = 0;
                }
                $item->setAppends([
                    'share_url', 'username', 'userid', 'device_token', 'phone', 'email', 'related_add',
                ]);
            });

            return $this->successResponse(trans('messages.request_success'), $items, 200);
        } else {
            return $this->errorResponse("Please enter user id", [], 400);

        }
    }

    public function deactivate_item(Request $request)
    {
        $user_id = $request->user_id;
        $id      = $request->id;
        $blance  = array(
            'removeAt' => 1,
        );
        $item = DB::table('items')->where('id', '=', $id)->update($blance);
        
        return $this->successResponse(trans('messages.post_deactivated'), [], 200);
    }

    public function activate_item(Request $request)
    {
        $user_id = $request->user_id;
        $id      = $request->id;
        $blance  = array(
            'removeAt' => 0,
        );
        $item = DB::table('items')->where('id', '=', $id)->update($blance);
        
        return $this->successResponse(trans('messages.post_activated'), [], 200);
    }

    public function delete_deliver(Request $request)
    {
        // $user_id = $request->user_id;
        $id = $request->id;

        $item = Item::find($id);
        // $item = where('id', '=', $id)->delete();
        if ($item && $item->delete()) {
            return $this->successResponse(trans('messages.post_deleted'), [], 200);
        } else {
            return $this->errorResponse(trans('messages.request_failed'), [], 400);
        }
    }

    public function delete_item(Request $request)
    {
        $user_id = $request->user_id;
        $id      = $request->id;

        $item = Item::find($id);
        if ($item && $item->delete()) {
            return $this->successResponse(trans('messages.post_deleted'), [], 200);
        } else {
            return $this->errorResponse(trans('messages.request_failed'), [], 400);
        }
    }

    public function delete_item_images(Request $request)
    {
        // $user_id = $request->user_id;
        $id   = $request->id;
        $item = DB::table('item_images')->where('item_id', '=', $id)->delete();

        $blance = array(
            'imgUrl' => '',
        );
        $items = DB::table('items')->where('id', '=', $id)->update($blance);

        if ($item or $items) {
            return $this->successResponse(trans('messages.image_deleted'), [], 200);
        } else {
            return $this->successResponse(trans('messages.no_post'), [], 400);
        }
    }

    public function add_post(Request $request)
    {
        $user_id = $request->user_id;
        $id      = $request->id;

        // Put validation
        $validator = Validator::make($request->all(), [
            'imgUrl'   => 'required_without:videoUrl|mimes:jpeg,jpg,png,gif,svg,wbmp,webp',
            'videoUrl' => 'required_without:imgUrl|mimes:mp4,3gp,avi,mpeg,flv,mov,qt',
            'sex'      => 'required|in:male,female',
            'passport' => 'required|in:yes,no',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 100,
                'error'   => true,
                'message' => trans('messages.validation_error'),
                'error'   => $validator->errors(),
            ], 200);
        }

        $imagePath = public_path('uploads/ad/');
        $videoPath = public_path('uploads/ad_video/');

        // $image = $_FILES['imgUrl']['name'];
        // $image_tmp = $_FILES['imgUrl']['tmp_name'];
        $blance = array(
            'fromUserId'      => $request->user_id,
            'priority'        => $request->priority,
            'showComments'    => $request->showComments ? 1 : 0,
            'category'        => $request->category,
            'subCategory'     => $request->subCategory,
            'itemTitle'       => $request->itemTitle,
            'price'           => $request->price,
            'itemDesc'        => $request->itemDesc,
            'showPhoneNumber' => $request->showPhoneNumber ? 1 : 0,
            'showMessage'     => $request->showMessage ? 1 : 0,
            'showWhatsapp'    => $request->showWhatsapp ? $request->showWhatsapp : 0,
            'city'            => $request->city,
            'age'             => !empty($request->age) ? $request->age : "",
            'sex'             => !empty($request->sex) ? strtolower($request->sex) : 'male',
            'passport'        => !empty($request->passport) ? $request->passport : "no",
            'vaccine_detail'  => !empty($request->vaccine_detail) ? strtolower($request->vaccine_detail) : '',
            'country'         => $request->country,
            'created_at'      => Carbon::now()->format('Y-m-d H:i'),
            'updated_at'      => Carbon::now()->format('Y-m-d H:i'),
        );

        ## if bid is type of auction.
        if ($request->auction == 1) {
            $auctionResponse = Item::enableAuctionFeature($request);
            if (isset($auctionResponse['error']) && $auctionResponse['error']) {
                return $auctionResponse;
            } else {
                $blance = array_merge($blance, $auctionResponse);
            }
        }

        // $images = $request->images[];
        $id = DB::table('items')->insertGetId($blance);
        if ($id) {

            // Upload image in ad item
            $imageUrl = "";
            if ($request->hasFile('imgUrl')) {
                $image     = $request->file('imgUrl');
                $imageName = time() . '_' . $id . '.' . $image->getClientOriginalExtension();
                $image->move($imagePath, $imageName);
                // $imageUrl = 'http://newzoolifeapi.zoolifeshop.com/uploads/ad/' . $imageName;
                $imageUrl = $imageName;
            }

            // Upload video in ad item
            $videoUrl = "";
            if ($request->hasFile('videoUrl')) {
                $video     = $request->file('videoUrl');
                $videoName = time() . '_' . $id . '.' . $video->getClientOriginalExtension();
                $video->move($videoPath, $videoName);
                // $videoUrl = 'http://newzoolifeapi.zoolifeshop.com/uploads/ad_video/' . $videoName;
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
                    // $image = $request->file('imgUrl');
                    $imageName = time() . $k . '_' . $id . '.' . $image->getClientOriginalExtension();
                    $image->move($imagePath, $imageName);
                    // $imageUrl = 'http://newzoolifeapi.zoolifeshop.com/uploads/ad/' . $imageName;
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
            return $this->successResponse(trans('messages.post_added'), [], 200);
        } else {
            return $this->errorResponse(trans('messages.request_failed'), [], 400);

        }
        return Response::json($dr);
    }

    public function update_post(Request $request)
    {
        $inputs = $request->all();

        // Put validation
        $validator = Validator::make($inputs, [
            'sex'      => 'required|in:male,female',
            'passport' => 'required|in:yes,no',
            // 'imgUrl'   => 'required_without:videoUrl|mimes:jpeg,jpg,png,gif,svg,wbmp,webp',
            // 'videoUrl' => 'required_without:imgUrl|mimes:mp4,3gp,avi,mpeg,flv,mov,qt',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 100,
                'error'   => true,
                'message' => trans('messages.validation_error'),
                'error'   => $validator->errors(),
            ], 200);
        }

        $imagePath = public_path('uploads/ad/');
        $videoPath = public_path('uploads/ad_video/');
        $item_id   = $request->item_id;

        // $image = $_FILES['imgUrl']['name'];
        // $image_tmp = $_FILES['imgUrl']['tmp_name'];
        $blance = array(
            'fromUserId'      => $request->user_id ?: $request->fromUserId,
            'priority'        => $request->priority,
            'showComments'    => $request->showComments ? 1 : 0,
            'category'        => $request->category,
            'subCategory'     => $request->subCategory,
            'itemTitle'       => $request->itemTitle,
            'itemDesc'        => $request->itemDesc,
            'showPhoneNumber' => $request->showPhoneNumber ? 1 : 0,
            'showMessage'     => $request->showMessage ? 1 : 0,
            'city'            => $request->city,
            'age'             => !empty($request->age) ? $request->age : "",
            'sex'             => !empty($request->sex) ? strtolower($request->sex) : 'male',
            'passport'        => !empty($request->passport) ? $request->passport : "no",
            'vaccine_detail'  => !empty($request->vaccine_detail) ? strtolower($request->vaccine_detail) : '',
            'country'         => $request->country,
            'showWhatsapp'    => $request->showWhatsapp ? 1 : 0,
        );

        ## if bid is type of auction.
        if ($request->auction == 1) {
            $auctionResponse = Item::enableAuctionFeature($request);
            if (isset($auctionResponse['error']) && $auctionResponse['error']) {
                return $auctionResponse;
            } else {
                $blance = array_merge($blance, $auctionResponse);
            }
        } else {
            $blance['post_type']           = 'normal';
            $blance['auction_expiry_time'] = null;
            $blance['min_bid']             = null;
            $blance['max_bid']             = null;
            $blance['expiry_hours']        = null;
            $blance['expiry_days']         = null;
        }

        // $images = $request->images[];
        $id = DB::table('items')->where('id', '=', $item_id)->update($blance);
        if ($item_id) {

            $item = Item::where('id', '=', $item_id)->first();

            // Upload image in ad item
            $imageUrl = "";
            if ($request->hasFile('imgUrl')) {
                $image     = $request->file('imgUrl');
                $imageName = time() . '_' . $item_id . '.' . $image->getClientOriginalExtension();
                $image->move($imagePath, $imageName);
                // $imageUrl = 'http://newzoolifeapi.zoolifeshop.com/uploads/ad/' . $imageName;
                $imageUrl = $imageName;

                // Remove old Image
                if (!empty($item->imgUrl)) {
                    $parts        = explode('/', $item->imgUrl);
                    $oldImage     = end($parts);
                    $oldImagePath = $imagePath . $oldImage;
                    if (file_exists($oldImagePath)) {
                        @unlink($oldImagePath);
                    }
                }

                // Set Image Url
                $item->imgUrl = $imageUrl;
            }

            // Upload video in ad item
            $videoUrl = "";
            if ($request->hasFile('videoUrl')) {
                $video     = $request->file('videoUrl');
                $videoName = time() . '_' . $item_id . '.' . $video->getClientOriginalExtension();

                $video->move($videoPath, $videoName);
                // $videoUrl = 'http://newzoolifeapi.zoolifeshop.com/uploads/ad_video/' . $videoName;
                $videoUrl = $videoName;

                // Remove old Video
                if (!empty($item->videoUrl)) {
                    $parts        = explode('/', $item->videoUrl);
                    $oldVideo     = end($parts);
                    $oldVideoPath = $videoPath . $oldVideo;
                    if (file_exists($oldVideoPath)) {
                        @unlink($oldVideoPath);
                    }
                }

                // Set Video Url
                $item->videoUrl = $videoUrl;
            }

            // save video or image in ad item
            if (!empty($videoUrl) || !empty($imageUrl)) {
                $item->save();
            }

            if ($request->hasFile('images')) {
                $images    = $request->file('images');
                $allImages = [];
                foreach ($images as $k => $image) {
                    // $image = $request->file('imgUrl');
                    $imageName = time() . $k . '_' . $item_id . '.' . $image->getClientOriginalExtension();
                    $image->move($imagePath, $imageName);
                    // $imageUrl = 'http://newzoolifeapi.zoolifeshop.com/uploads/ad/' . $imageName;
                    $imageUrl = $imageName;

                    $allImages[] = [
                        'item_id'   => $item_id,
                        'file_name' => $imageUrl,
                    ];
                }

                if (!empty($allImages)) {
                    $image = ItemImage::insert($allImages);
                }
            }

            if (!empty($inputs['del_images']) && is_array($inputs['del_images'])) {
                $getImages = ItemImage::where('item_id', $request->item_id)->whereIn('id', $inputs['del_images'])->get();
                if (count($getImages)) {
                    foreach ($getImages as $delImg) {
                        // Remove image
                        if (!empty($delImg->file_name)) {
                            $parts        = explode('/', $delImg->file_name);
                            $oldImage     = end($parts);
                            $oldImagePath = $imagePath . $oldImage;
                            if (file_exists($oldImagePath)) {
                                @unlink($oldImagePath);
                            }
                        }
                        $delImg->delete();
                    }
                }
            }
            return $this->successResponse(trans('messages.post_updated'), [], 200);
        } else {
            return $this->errorResponse(trans('messages.request_failed'), [], 400);
        }
        return Response::json($dr);
    }

    public function add_delivery(Request $request)
    {
        $dr['status'] = 100;
        $user_id      = $request->user_id;
        $blance       = array(
            // 'phone' => $request->phone,
            'fromUserId'      => $user_id,
            // 'id' => $request->id,
            'itemTitle'       => $request->itemTitle,
            'itemDesc'        => $request->itemDesc,
            'category'        => 4000,
            'subCategory'     => $request->subCategory,
            'showPhoneNumber' => $request->showPhoneNumber,
            'showComments'    => $request->showComments,
            'showMessage'     => $request->showMessage,
            'city'            => $request->city,
            'country'         => $request->country,
            'imgUrl'          => '',
        );
        $user = User::where('id', $user_id)->where('verify', 1)->first();
        if ($user) {
            $item = Item::create($blance);
            $item->imgUrl   = !empty($item->imgUrl) ? url('/uploads/ad/' . $item->imgUrl) : '';
            $item->videoUrl = !empty($item->videoUrl) ? url('/uploads/ad_video/' . $item->videoUrl) : '';
            if (isset($user_id)) {
                $item->favrtitem_status = $item->favrtitemStatus($user_id);
                $item->likeitem_status  = $item->likeitemStatus($user_id);
                $item->report_status    = $item->reportStatus($user_id);
            } else {
                $item->favrtitem_status = 0;
                $item->likeitem_status  = 0;
                $item->report_status    = 0;
            }
            $item->setAppends([
                'share_url', 'username', 'userid', 'device_token', 'phone', 'email', 'related_add',
            ])->makeHidden('user');

            if ($item) {
                return $this->successResponse(trans('messages.delivery_added'), $item, 200);
            } else {
                return $this->errorResponse(trans('messages.unable_to_create_delivery'), [], 400);
            }
        } else {
            return $this->errorResponse(trans('messages.no_data'), [], 400);
        }
    }

    public function like_item(Request $request)
    {
        $dr['status'] = 100;
        $user_id      = $request->user_id;
        $id           = $request->id;
        $user         = User::where('id', '=', $user_id)->where('verify', '=', 1)->first();

        if ($user) {
            $item = Item::find($request->id);
            if ($item) {
                $blance = array(
                    'toUserId'   => $item->user_id,
                    'itemId'     => $request->id,
                    'fromUserId' => $request->user_id,
                    'createAt'   => time(),
                );
                $alreadyLiked = DB::table('likes')->where('toUserId', $item->user_id)->where('itemId', $request->id)
                    ->where('fromUserId', $request->user_id)->first();

                if ($alreadyLiked) {

                    DB::table('likes')->where('toUserId', $item->user_id)->where('itemId', $request->id)
                        ->where('fromUserId', $request->user_id)->delete();

                    return $this->successResponse(trans('messages.unliked'), $item, 200);

                } else {
                    $likes = DB::table('likes')->insert($blance);

                    $item->likesCount = $item->likesCount + 1;
                    $item->save();
                    return $this->successResponse(trans('messages.liked'), $item, 200);
                }
            } else {
                return $this->errorResponse(trans('messages.no_data'), [], 400);
            }
        } else {
            return $this->errorResponse(trans('messages.no_user'), [], 400);
        }

    }

    public function list_likes(Request $request)
    {

        $dr['status'] = 100;
        $dr['data']   = [];
        $user_id      = $request->user_id;
        $user         = DB::table('users')->select('*')->where('id', '=', $user_id)->where('verify', '=', 1)->get();

        if (count($user) > 0) {
            $favourites      = [];
            $fromUserId      = $user[0]->id;
            $favourites_data = DB::table('likes')->select('*')->where('fromUserId', '=', $fromUserId)->where('removeAt', '=', 0)->get();
            if (count($favourites_data) > 0) {
                foreach ($favourites_data as $favourite) {
                    $itemId  = $favourite->itemId;
                    $product = Item::where('id', '=', $itemId)
                        ->where('post_type', $request->get('post_type', 'normal'))
                        ->where('removeAt', '=', 0)->with('images')->first();

                    $product->imgUrl   = !empty($product->imgUrl) ? url('/uploads/ad/' . $product->imgUrl) : '';
                    $product->videoUrl = !empty($product->videoUrl) ? url('/uploads/ad_video/' . $product->videoUrl) : '';

                    if (isset($user_id)) {
                        $product->favrtitem_status = $product->favrtitemStatus($user_id);
                        $product->likeitem_status  = $product->likeitemStatus($user_id);
                        $product->report_status    = $product->reportStatus($user_id);
                    } else {
                        $product->favrtitem_status = 0;
                        $product->likeitem_status  = 0;
                        $product->report_status    = 0;
                    }
                    $product->setAppends([
                        'username', 'userid', 'device_token', 'phone', 'email', 'related_add',
                    ])->makeHidden('user');

                    $favourite->product = $product;

                    $favourites[] = $favourite;
                }
                
                return $this->successResponse(trans('messages.request_success'), $favourites, 200);

            } else {
                
                return $this->successResponse(trans('messages.no_data'), [], 200);

            }
        } else {
            return $this->errorResponse(trans('messages.no_data'), [], 400);

        }
    }

    public function delete_like_item(Request $request)
    {
        $dr['status'] = 100;
        $user_id      = $request->user_id;
        $id           = $request->id;
        $user         = DB::table('users')->select('*')->where('id', '=', $user_id)->where('verify', '=', 1)->get();
        $blance       = array(
            'removeAt' => time(),
        );
        if (count($user) > 0) {
            $fromUserId = $user[0]->id;
            $deleteLike = DB::table('likes')->where('fromUserId', '=', $fromUserId)->where('itemId', '=', $id)->delete();
            return $this->successResponse(trans('messages.unliked'), [], 200);
        } else {
           return $this->errorResponse(trans('messages.no_data'), [], 400);
        }
    }

    public function abuse_item(Request $request)
    {
        $dr['status'] = 100;
        $user_id      = $request->user_id;
        $user         = User::where('id', '=', $user_id)->where('verify', '=', 1)->first();

        if ($user) {
            $fromUserId = $user->id;

            $alreadyabused = DB::table('items_abuse_reports')
                ->where('abuseFromUserId', $fromUserId)
                ->where('abuseToItemId', $request->id)
                ->first();

            if ($alreadyabused) {
                DB::table('items_abuse_reports')
                    ->where('abuseFromUserId', $fromUserId)
                    ->where('abuseToItemId', $request->id)
                    ->delete();

                return $this->successResponse(trans('messages.abuse_removed'), [], 200);

            } else {

                $blance = array(
                    'abuseFromUserId' => $fromUserId,
                    'abuseToItemId'   => $request->id,
                    'createAt'        => time(),
                );
                $abuse = DB::table('items_abuse_reports')->insert($blance);
                
                return $this->successResponse(trans('messages.abused'), [], 200);
            }

        } else {
            return $this->errorResponse(trans('messages.no_data'), [], 400);
        }
    }

    public function list_abused_items(Request $request)
    {
        $dr['status'] = 100;
        $dr['data']   = [];
        $user_id      = $request->user_id;
        $user         = DB::table('users')->select('*')->where('id', '=', $user_id)->where('verify', '=', 1)->get();
        if (count($user) > 0) {
            $abuses     = [];
            $fromUserId = $user[0]->id;

            $abuses_data = DB::table('items_abuse_reports')->select('*')->where('abuseFromUserId', '=', $fromUserId)->where('removeAt', '=', 0)->get();

            if (count($abuses_data) > 0) {
                foreach ($abuses_data as $abuse) {
                    $itemId = $abuse->abuseToItemId;

                    // $product = DB::table('items')->select('*')->where('id', '=', $itemId)->where('removeAt', '=', 0)->get();
                    $product = Item::where('id', '=', $itemId)
                        ->where('post_type', $request->get('post_type', 'normal'))
                        ->where('removeAt', '=', 0)->with('images')->first();

                    // if (isset($user_id)) {
                    //     $product->favrtitem_status = $product->favrtitemStatus($user_id);
                    //     $product->likeitem_status = $product->likeitemStatus($user_id);
                    //     $product->report_status = $product->reportStatus($user_id);
                    // } else {
                    //     $product->favrtitem_status = 0;
                    //     $product->likeitem_status = 0;
                    //     $product->report_status = 0;
                    // }
                    // $product->setAppends([
                    //         'username', 'userid', 'device_token', 'phone', 'email', 'related_add',
                    //     ])->makeHidden('user');

                    $abuse->product = $product;

                    $abuses[] = $abuse;
                }
                
                return $this->successResponse(trans('messages.request_success'), $abuses, 200);

            } else {
                return $this->successResponse(trans('messages.no_data'), [], 200);

            }
        } else {
            return $this->errorResponse(trans('messages.request_failed'), [], 200);
        }
    }

    public function delete_abused_item(Request $request)
    {
        $dr['status'] = 100;
        $user_id      = $request->get('user_id');
        $id           = $request->id;
        $user         = DB::table('users')->select('*')->where('id', '=', $user_id)->where('verify', '=', 1)->get();
     
        if (count($user) > 0) {
            $fromUserId = $user[0]->id;
            // $blance = array(
            //   'removeAt' => time(),
            // );
            DB::table('items_abuse_reports')->where('abuseFromUserId', '=', $fromUserId)->where('abuseToItemId', '=', $id)->delete();
         
            return $this->successResponse(trans('messages.post_remove_abuse_list'), [], 200);

        } else {
            return $this->errorResponse(trans('messages.no_data'), [], 400);
        }
    }

    public function favoruit_item(Request $request)
    {
        $dr['status'] = 100;
        $user_id      = $request->user_id;
        $itemId       = $request->id;

        $item = Item::where('id', $itemId)->first();

        if ($item) {
            $alreadyFavourite = $item->itemFavorites()->where('userId', $user_id)->first();

            if ($alreadyFavourite) {
                $alreadyFavourite->delete();

                return $this->successResponse(trans('messages.item_removed_fvrt'), [], 200);

            } else {
                $blance = array(
                    'userId' => $user_id,
                    'itemId' => $itemId,
                );
                DB::table('item_favorites')->insert($blance);
                return $this->successResponse(trans('messages.item_added_fvrt'), [], 200);
            }

        } else {
            return $this->errorResponse(trans('messages.no_data'), [], 400);
        }

    }

    public function favoruit_list_item_by_user(Request $request)
    {
        $user_id = $request->user_id;

        if ($user_id) {
            // $items = DB::table('item_favorites')->join('items', 'items.id', '=', 'item_favorites.itemId')->select('item_favorites.*', 'items.*')->where('userId', '=', $user_id)->get();

            $items = Item::whereHas('itemFavorites', function ($query) use ($request) {
                $query->where('userId', $request->user_id)
                    ->where('post_type', $request->get('post_type', 'normal'));
            })->get();

            $items->each(function ($item, $key) use ($user_id) {
                $item->imgUrl   = !empty($item->imgUrl) ? url('/uploads/ad/' . $item->imgUrl) : '';
                $item->videoUrl = !empty($item->videoUrl) ? url('/uploads/ad_video/' . $item->videoUrl) : '';
                if (isset($user_id)) {
                    $item->favrtitem_status = $item->favrtitemStatus($user_id);
                    $item->likeitem_status  = $item->likeitemStatus($user_id);
                    $item->report_status    = $item->reportStatus($user_id);
                } else {
                    $item->favrtitem_status = 0;
                    $item->likeitem_status  = 0;
                    $item->report_status    = 0;
                }
                $item->setAppends([
                    'share_url', 'username', 'userid', 'device_token', 'phone', 'email', 'related_add',
                ]);
            })->makeHidden('user');

            $fav_item = [];
            $i        = 0;
            if (count($items) > 0) {
                return $this->successResponse(trans('messages.request_success'), $items, 200);
            } else {
                return $this->successResponse(trans('messages.request_success'), [], 200);

            }
        } else {
            return $this->errorResponse(trans('messages.no_data'), [], 400);
        }

    }

    public function favoruit_list_by_item(Request $request)
    {
        $user_id = $request->user_id;
        $itemId  = $request->id;

        // $items = Db::table('item_favorites')->join('items', 'items.id', '=', 'item_favorites.itemId')->select('item_favorites.*', 'items.*')->where('userId', '=', $user_id)->where('itemId', '=', $itemId)->get();

        if ($itemId && $user_id) {
            $items = Item::whereHas('itemFavorites', function ($query) use ($request) {
                $query->where('userId', '=', $request->user_id)
                    ->where('post_type', $request->get('post_type', 'normal'))
                    ->where('itemId', '=', $request->id);
            })->get();
            if ($items) {
                $items        = $this->createItemStructure($items, $user_id);
                return $this->successResponse(trans('messages.request_success'), $items, 200);

            } else {
                return $this->successResponse(trans('messages.no_data'), [], 200);
            }
        } else {
            return $this->errorResponse(trans('messages.no_data'), [], 400);
        }
    }

    public function delete_favorites(Request $request)
    {
        $favoriteId = $request->favoriteId;
        DB::table('item_favorites')->where('id', '=', $favoriteId)->delete();
        
        return $this->successResponse(trans('messages.fvrt_deleted'), [], 200);

    }

    public function itemsBySubcategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 100,
                'error'   => true,
                'message' => trans('messages.validation_error'),
                'error'   => $validator->errors(),
            ], 200);
        }

        $items = Item::where('subCategory', $request->category_id)->where('post_type', $request->get('post_type', 'normal'))
            ->with('images')
            ->orderBy('priority', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->paginate(env('PER_PAGE'));

        $items->each(function ($item, $key) {
            $item->imgUrl   = !empty($item->imgUrl) ? url('/uploads/ad/' . $item->imgUrl) : '';
            $item->videoUrl = !empty($item->videoUrl) ? url('/uploads/ad_video/' . $item->videoUrl) : '';
            if (isset($item->fromUserId)) {
                $item->favrtitem_status = $item->favrtitemStatus($item->fromUserId);
                $item->likeitem_status  = $item->likeitemStatus($item->fromUserId);
                $item->report_status    = $item->reportStatus($item->fromUserId);
            } else {
                $item->favrtitem_status = 0;
                $item->likeitem_status  = 0;
                $item->report_status    = 0;
            }
            $item->setAppends([
                'share_url', 'username', 'userid', 'device_token', 'phone', 'email', 'related_add',
            ]);
        })->makeHidden('user');
        
        return $this->successResponse(trans('messages.request_success'), $items, 200);

    }

    public function itemsByCity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'city' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 100,
                'error'   => true,
                'message' => trans('messages.validation_error'),
                'error'   => $validator->errors(),
            ], 200);
        }

        $items = Item::where('city', $request->city)
            ->with('images')
            ->orderBy('priority', 'DESC')
            ->orderBy('created_at', 'DESC');

        if (!empty($request->category_id)) {
            $items->where('category', $request->category_id);
        }

        $items = $items->paginate(env('PER_PAGE'));

        $items->each(function ($item, $key) {
            $item->imgUrl   = !empty($item->imgUrl) ? url('/uploads/ad/' . $item->imgUrl) : '';
            $item->videoUrl = !empty($item->videoUrl) ? url('/uploads/ad_video/' . $item->videoUrl) : '';
            if (isset($item->fromUserId)) {
                $item->favrtitem_status = $item->favrtitemStatus($item->fromUserId);
                $item->likeitem_status  = $item->likeitemStatus($item->fromUserId);
                $item->report_status    = $item->reportStatus($item->fromUserId);
            } else {
                $item->favrtitem_status = 0;
                $item->likeitem_status  = 0;
                $item->report_status    = 0;
            }
            $item->setAppends([
                'share_url', 'username', 'userid', 'device_token', 'phone', 'email', 'related_add',
            ]);
        })->makeHidden('user');

        return $this->successResponse(trans('messages.request_success'), $items, 200);
    }

    public function cities()
    {
        $cities = City::select('id', 'name', 'arabic_name')->get()->makeHidden('arabic_name');
       
        return $this->successResponse(trans('messages.request_success'), $cities, 200);
    }

    /**
     * @Purpose Add Bid to Pos
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addBid(Request $request): \Illuminate\Http\JsonResponse
    {
        ## return back if amount is missing
        if (empty($request->amount)) {
            return $this->errorResponse(__('messages.please_enter_bid_amount'), [], 400);
        }

        ## return back if isn't mentioned how place bid.
        if (empty($request->fromUserId)) {
            return $this->errorResponse(__('messages.invalid_from_user_id'), [], 400);
        }

        ## if item wasn't found and item wasn't auction
        $Post = Item::find($request->item_id);
        if (!$Post instanceof Item || $Post->post_type != 'auction') {
            return $this->errorResponse(__('messages.please_enter_valid_post_id'), [], 400);

        }

        // if ($Post->min_bid > $request->amount) {
        //     return response()->json([
        //         'status' => 100,
        //         'error' => true,
        //         'message' => __('messages.wrong_amount_was_entered'),
        //     ]);
        // }

        // if ($Post->max_bid < $request->amount) {
        //     return response()->json([
        //         'status' => 100,
        //         'error' => true,
        //         'message' => __('messages.wrong_amount_was_entered'),
        //     ]);
        // }

        ## if bid is already placed by the user
        // $IsAlreadyBidByThisUser = BiddingModel::getBidOnBaseOfUserAndPost($request->item_id, $request->fromUserId);
        // if ($IsAlreadyBidByThisUser) {
        //     return response()->json([
        //         'status' => 100,
        //         'error' => true,
        //         'message' => __('messages.already_bid_placed_by_you'),
        //     ]);
        // }

        ## adding bid for user
        $IsBidSaved = BiddingModel::addBidding($request);
        if ($IsBidSaved) {
            $Post->min_bid = ((float) $request->amount);
            $Post->save();
            
            return $this->successResponse(__('messages.bid_save_for_user'), [], 200);

        } else {
            
            return $this->errorResponse(__('messages.request_failed'), [], 400);

        }
    }

    /**
     * @Purpose This function inform, Is this user has already place bid or not.
     * - If success , mean user has placed bid
     * - If error, means use wasn't placed bid
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function alreadyBidByUser(Request $request): \Illuminate\Http\JsonResponse
    {
        if (empty($request->fromUserId)) {
            return $this->errorResponse(__('messages.invalid_from_user_id'), [], 400);
        }

        $Post = Item::find($request->item_id);
        if (!$Post instanceof Item || $Post->post_type != 'auction') {
            return $this->errorResponse(__('messages.please_enter_valid_post_id'), [], 400);
        }

        $IsAlreadyBidByThisUser = BiddingModel::getBidOnBaseOfUserAndPost($request->item_id, $request->fromUserId);
        if ($IsAlreadyBidByThisUser) {
            return $this->errorResponse(__('messages.bid_already_placed_by_user'), [], 400);
        } else {
            return $this->successResponse(__('messages.bid_wasnt_placed_by_user'), [], 200);
        }

    }

    /**
     * @Purpose Get All Bids on base of POST
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllBidsOfPost(Request $request)
    {
        $Post = Item::find($request->item_id);
        if (!$Post instanceof Item || $Post->post_type != 'auction') {
            return $this->errorResponse(__('messages.please_enter_valid_post_id'), [], 400);

        }

        $Bids = BiddingModel::getBidOnBaseOfPost($request->item_id);
        foreach ($Bids as $bid) {
            $bid->readable_time = $bid->created_at->diffForHumans();
        }
      
        return $this->successResponse(__('messages.bid_found'), $Bids, 200);


    }

    public function createItemStructure($items, $user_id)
    {
        return $items->each(function ($item, $key) use ($user_id) {
            $item->imgUrl   = !empty($item->imgUrl) ? url('/uploads/ad/' . $item->imgUrl) : '';
            $item->videoUrl = !empty($item->videoUrl) ? url('/uploads/ad_video/' . $item->videoUrl) : '';
            if (isset($user_id)) {
                $item->favrtitem_status = $item->favrtitemStatus($user_id);
                $item->likeitem_status  = $item->likeitemStatus($user_id);
                $item->report_status    = $item->reportStatus($user_id);
            } else {
                $item->favrtitem_status = 0;
                $item->likeitem_status  = 0;
                $item->report_status    = 0;
            }
            $item->setAppends([
                'share_url', 'username', 'userid', 'device_token', 'phone', 'email', 'related_add',
            ]);
        })->makeHidden('user');
    }
    
    public function getUserPosts(Request $request, $id) {
        $dr['status'] = 100;
        $dr['data']   = [];
        $user_id      = $request->user_id;

        $items = Item::where('removeAt', 0)
            ->where('post_type', 'normal')
            ->where('fromUserId', $id)
            ->where('category', '!=', '4000')
            ->orderBy('priority', 'DESC', 'id', 'DESC')
            ->with('images')
            ->orderBy('priority', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->paginate(env('PER_PAGE'));

        $items->each(function ($item, $key) use ($user_id) {
            $item->imgUrl   = !empty($item->imgUrl) ? url('/uploads/ad/' . $item->imgUrl) : '';
            $item->videoUrl = !empty($item->videoUrl) ? url('/uploads/ad_video/' . $item->videoUrl) : '';
            if (isset($user_id)) {
                $item->favrtitem_status = $item->favrtitemStatus($user_id);
                $item->likeitem_status  = $item->likeitemStatus($user_id);
                $item->report_status    = $item->reportStatus($user_id);
            } else {
                $item->favrtitem_status = 0;
                $item->likeitem_status  = 0;
                $item->report_status    = 0;
            }
            $item->setAppends([
                'share_url', 'username', 'userid', 'device_token', 'phone', 'email', 'related_add',
            ]);
        })->makeHidden('user');

        
        return $this->successResponse(__('messages.request_success'), $items, 200);

    }
}
