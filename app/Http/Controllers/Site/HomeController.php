<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Category;
use App\Models\Article;
use App\Models\Item;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function changeLocale($locale, Request $request)
    {
        if (! in_array($locale, ['en', 'ar'])) {
            abort(400);
        }

        \App::setLocale($locale);
        session(['locale' => $locale]);
        // echo session('locale');
        
        return redirect()->back();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user   = \Auth::user();
        $userId = !empty($user->id) ? $user->id : 0;
        
        $data = [];
        $data['sliders'] = Slider::where('status', '=', 1)->get();
        $data['categories'] = Category::where('mainCategoryId', 0)->get();

        $catArray = $data['categories']->toArray();
        $catIds = array_column($catArray, 'id');
        $data['sub_categories'] = Category::whereIn('mainCategoryId', $catIds)->get()->groupBy('mainCategoryId');

        $data['articles'] = Article::orderBy('updated_at', 'DESC')->limit(10)->get();
        $data['featuredPosts'] = Item::select('items.*', 'u.name as author', \DB::raw("IF(if.itemId > 0, 1, 0) as is_favorite"))
                                ->leftjoin('users as u','u.id','fromUserId')
                                ->leftJoin("item_favorites as if", function($query) use ($userId) {
                                    $query->on('if.itemId','items.id');
                                    $query->where('if.userId', $userId);
                                    // $query->where('if.type', 'post');
                                })
                                ->where('priority','>',0)
                                ->orderBy('updated_at', 'DESC')
                                ->get();

        $data['posts'] = Item::select('items.*', 'u.name as author', \DB::raw("IF(if.itemId > 0, 1, 0) as is_favorite"))
                        ->leftjoin('users as u','u.id','fromUserId')
                        ->leftJoin("item_favorites as if", function($query) use ($userId) {
                            $query->on('if.itemId','items.id');
                            $query->where('if.userId', $userId);
                            // $query->where('if.type', 'post');
                        })
                        ->where('post_type', 'normal')
                        ->orderBy('updated_at', 'DESC')
                        ->limit(10)->get();

        $data['auction'] = Item::select('items.*', 'u.name as author')
                        ->leftjoin('users as u','u.id','fromUserId')
                        ->where('post_type', 'auction')
                        ->orderBy('updated_at', 'DESC')
                        ->limit(10)->get();
        
        // print_r($data['articles']->toArray());
        // exit;
        return view('site.home', compact('data'));
    }

}
