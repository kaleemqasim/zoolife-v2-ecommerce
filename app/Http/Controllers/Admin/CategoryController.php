<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use DB;
use Illuminate\Http\Request;
use Response;

class CategoryController extends Controller
{

    public function index(Request $request)
    {
        $page_title = 'Manage Slider';
        $category = category::orderBy('priority', 'ASC')->get();
        // $category = category::where('mainCategoryId', '=', '0')->orderBy('priority', 'DESC')->get();
        $category = $category->each->setAppends([
            'arabic_name',
        ]);

        // dd($category);
        return view('admin/category/index', compact('category'));
    }

    public function create(Request $request)
    {
        $category = category::get();
        return view('admin/category/add', compact('category'));
    }

    public function store(Request $request)
    {

        //   print_r($request->image);
        //   die();
        $this->validate($request, [
            'title' => 'required',
            //  'cat_img' => 'required|cat_img|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        $cat = new Category;
        $cat->title = $request->title;
        $cat->english_title = $request->english_title;
        $cat->priority = '0';
        $cat->mainCategoryId = $request->sub_id;

        $cat->save();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $fileName = time() . "category." . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/category/'), $fileName);

            if ($cat->cat_img != 'photo.jpg' && is_file(public_path('uploads/category/' . $cat->cat_img))) {
                unlink(public_path('uploads/category/' . $cat->cat_img));
            }
            $cat->cat_img = $fileName;
            $cat->save();
        }

        return redirect()->back();
    }

    public function show($id)
    {
        $cat_show = DB::table('category')->where('id', '=', $id)->get();
        return view('admin/category/edit', compact('cat_show'));
    }

    public function update(Request $request, $id)
    {
        $cat_update = Category::where('id', '=', $id)->first();
        $cat_update->title = $request->title;
        $cat_update->priority = $request->priority;
        $cat_update->english_title = $request->english_title;
        $cat_update->save();
        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $fileName = time() . "category." . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/category/'), $fileName);
            // print_r( $image);
            // die();
            if ($cat_update->cat_img != 'photo.jpg' && is_file(public_path('uploads/category/' . $cat_update->cat_img))) {
                unlink(public_path('uploads/category/' . $cat_update->cat_img));
            }
            $cat_update->cat_img = $fileName;
            $cat_update->save();
        }

        return redirect()->back()->with('success', 'saved');
    }

    public function destroy($id)
    {
        $ads = Item::find($id);
        $ads->delete();
        return redirect()->route('admin.ads.show')->with('success', 'saved');
    }

    public function getSubcategory(Request $request)
    {
        //dd($request->id);
        return Category::where('mainCategoryId', '=', $request->id)->select('id', 'title')->orderBy('priority')->get();
    }
    public function delete_category($id)
    {
        //   print_r($id);
        //   die();
        $cats = DB::table('category')->where('id', '=', $id)->delete();
        return redirect()->back();
    }

    public function api_category(Request $request)
    {
        $page_title = 'Manage Slider';

        // $local = app()->getLocale();
        // if($local == 'en') {
        //     $column = 'english_title';
        // } else {
        //     $column = 'title';
        // }

        $category = category::where('mainCategoryId', '=', '0')->orderBy('priority')->get();
        if ($category) {
            $i = 0;
            foreach ($category as $article) {

                $url = ($article->cat_img) ? url('uploads/category/' . $article->cat_img) : "";

                $data[$i]['id'] = $article->id;
                $data[$i]['mainCategoryId'] = $article->mainCategoryId;

                $data[$i]['title'] = $article->title;
                $data[$i]['description'] = $article->description;
                $data[$i]['img_unSelected'] = $url;
                $data[$i]['img_selected'] = $url;
                $data[$i]['updated_at'] = $article->updated_at;
                $data[$i]['created_at'] = $article->created_at;
                $data[$i]['status'] = 'success';
                $i++;
            }
            $dr['error'] = false;
            $dr['status'] = '200';
            $dr['message'] = 'success';
            $dr['data'] = $data;
            return Response::json($dr);
        } else {
            $data['status'] = true;
            $data['message'] = trans('messages.no_data');
            //   $data['Message'] = 'No Articles';
            return Response::json($data);
        }
    }
    public function get_sub_category(Request $request)
    {
        $dr['status'] = 100;
        $mainCategoryId = $request->category_id;

        $category = category::where('mainCategoryId', $mainCategoryId)
            ->where('removeAt', 0)
            ->orderBy('priority')
            ->get()->makeHidden('english_title');

        // dd($category);
        if (count($category) > 0) {
            $dr['error'] = false;
            $dr['data'] = $category;
            $dr['status'] = 200;
        } else {
            $dr['error'] = false;
            $dr['data'] = [];
            // $dr['message'] = 'No Data Found';
            $dr['message'] = trans('messages.no_data');
            $dr['status'] = 200;
        }
        return Response::json($dr);
    }

    public function get_single_category(Request $request)
    {
        $dr['status'] = 100;
        $id = $request->category_id;
        $category = Category::where('id', $id)->where('removeAt', 0)->first();

        if ($category) {
            // $cat = $category[0];
            if ($category->mainCategoryId == 0) {
                $url = ($category->cat_img) ? url('uploads/category/' . $category->cat_img) : "";

                $data['id'] = $category->id;
                $data['mainCategoryId'] = $category->mainCategoryId;
                $data['title'] = $category->title;
                $data['priority'] = $category->priority;
                $data['cat_img'] = $category->cat_img;
                $data['updated_at'] = $category->updated_at;
                $data['created_at'] = $category->created_at;
                $data['img_unSelected'] = $url;
                $data['img_selected'] = $url;
            }
            $dr['error'] = false;
            $dr['data'] = $data;
            $dr['status'] = 200;
        } else {
            $dr['error'] = false;
            $dr['data'] = [];
            // $dr['message'] = 'No Data Found';
            $dr['message'] = trans('messages.no_data');
            //   $dr['message'] = 'لايوجد اعلانات';
            $dr['status'] = 200;
        }

        return Response::json($dr);
    }
}
