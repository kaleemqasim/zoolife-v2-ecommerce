<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Category;
use App\Models\Article;

class ArticleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $data = [];
        $data['sliders'] = Slider::where('status', '=', 1)->get();
        $data['posts'] = Article::orderBy('updated_at', 'DESC')->paginate(5);
        
        // print_r($data['articles']->toArray());
        // exit;
        return view('site.article-list', compact('data'));
    }

    public function show($slug, Request $request)
    {
        $data = [];
        $post = Article::where('id', $slug)->first();

        if (!empty($post)) {
            return view('site.article-detail', compact('data', 'post'));
        } else {
            abort(404);
        }
    }

}
