<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use File;
use Illuminate\Http\Request;
use Image;
use Response;

class ArticleController extends Controller
{

    public function index(Request $request)
    {
        $articles = Article::get();
        $page_title = 'Manage article';
        return view('admin/article/index', compact('page_title', 'articles'));
    }

    public function create(Request $request)
    {

        $page_title = 'Add article';
        return view('admin/article/add', compact('page_title'));
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'date' => 'required',
            //'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $article = new Article;
        $article->title = $request->title;
        $article->description = $request->description;
        $article->date = $request->date;

        $article->save();

        /* if ($request->hasFile('image')) {
        $image = $request->file('image');
        $fileName = time() . "article." . $image->getClientOriginalExtension();
        $image->move(public_path('uploads/article/'), $fileName);

        if ($article->image != 'photo.jpg' && is_file(public_path('uploads/article/' . $article->image))) {
        unlink(public_path('uploads/article/' . $article->image));
        }
        $article->image = $fileName;
        $article->save();
        }*/
        /*Save Multiple Images*/
        if ($request->hasFile('image')) {
            $images = $request->file('image');
            // /dd($images);
            foreach ($images as $k => $image) {
                $fileName = time() . $k . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/article/'), $fileName);
                $data[] = $fileName;

                if ($image != 'photo.jpg' && is_file(public_path('uploads/article/' . $image))) {
                    unlink(public_path('uploads/article/' . $image));
                }
            }
            $article->image = implode(',', $data);

            $article->save();
        }
        return redirect()->route('admin.article.show')->with('success', 'saved');
    }

    public function show($id)
    {
        $article = Article::where('id', '=', $id)->first();

        //dd($article);
        return view('admin/article/edit', compact('article'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'date' => 'required',
            /*'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'*/
        ]);

        $article = Article::where('id', '=', $id)->first();
        $article->title = $request->title;
        $article->description = $request->description;
        $article->date = $request->date;

        $article->save();
        /*if ($request->hasFile('image')) {
        $image = $request->file('image');
        $fileName = time() . "article." . $image->getClientOriginalExtension();
        $image->move(public_path('uploads/article/'), $fileName);

        if ($article->image != 'photo.jpg' && is_file(public_path('uploads/article/' . $article->image))) {
        unlink(public_path('uploads/article/' . $article->image));
        }
        $article->image = $fileName;
        $article->save();
        }*/

        if ($request->hasFile('image')) {
            $images = $request->file('image');
            // /dd($images);
            foreach ($images as $k => $image) {
                $fileName = time() . $k . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/article/'), $fileName);
                $data[] = $fileName;

                if ($image != 'photo.jpg' && is_file(public_path('uploads/article/' . $image))) {
                    unlink(public_path('uploads/article/' . $image));
                }
            }
            $article->image = implode(',', $data);

            $article->save();
        }
        return redirect()->route('admin.article.show')->with('success', 'saved');
    }

    public function destroy($id)
    {
        $p = Article::find($id);
        $p->delete(); //delete the client
        return redirect()->route('admin.article.show')->with('success', 'saved');
    }

    public function deleteImage($id, $filename)
    {
        $data = Article::select('image')->where('id', $id)->first();

        foreach (explode(',', $data->image) as $key => $value) {

            if ($filename == $value) {
                $image_path = public_path() . '/uploads/article/' . $filename;
                unlink($image_path);
                $data->image = $filename;
                $data->delete();
            }
        }
        return redirect()->route('admin.article.edit', $id)->with('success', 'Delete successfully');
    }
    public function api_articles(Request $request)
    {
        $pass = $request['pass'];
        if ($pass == 'all_articles') {
            $articles = Article::all();
            $i = 0;
            foreach ($articles as $article) {
                $url = url('uploads/article'). "/";
                if (!empty($article->image)) {
                    $List = explode(',', $article->image);
                    $data[$i]['image1'] = $url . $List[0];
                    if (!empty($List[1])) {
                        $data[$i]['image2'] = $url . $List[1];
                    } else {
                        $data[$i]['image2'] = '';
                    }
                    if (!empty($List[2])) {
                        $data[$i]['image3'] = $url . $List[2];
                    } else {
                        $data[$i]['image3'] = '';
                    }
                } else {
                    $data[$i]['image1'] = '';
                    $data[$i]['image2'] = '';
                    $data[$i]['image3'] = '';
                }
                $data[$i]['id'] = $article->id;
                $data[$i]['title'] = $article->title;
                $data[$i]['description'] = $article->description;
                $data[$i]['date'] = $article->date;
                $data[$i]['created_at'] = $article->created_at;
                $data[$i]['updated_at'] = $article->updated_at;
                // $data[$i]['status'] = 'success';
                $i++;
            }

            $dr['error'] = false;
            $dr['status'] = '200';
            // $dr['message'] = 'success';
            $dr['data'] = $data;
            return Response::json($dr);
        } else {
            $data['status'] = true;
            $data['Message'] = trans('messages.no_data');
            return Response::json($data);
        }
    }

    public function api_single_article(Request $request)
    {
        $pass = $request['pass'];
        if ($pass == 'article') {
            $id = $request['id'];
            $article = Article::find($id);
            if (!empty($article)) {
                $url = url('uploads/article'). "/";
                if (!empty($article->image)) {
                    $List = explode(',', $article->image);
                    $data['image1'] = $url . $List[0];
                    if (!empty($List[1])) {
                        $data['image2'] = $url . $List[1];
                    } else {
                        $data['image2'] = '';
                    }
                    if (!empty($List[2])) {
                        $data['image3'] = $url . $List[2];
                    } else {
                        $data['image3'] = '';
                    }
                } else {
                    $data['image1'] = '';
                    $data['image2'] = '';
                    $data['image3'] = '';
                }
                $data['id'] = $article->id;
                $data['title'] = $article->title;
                $data['description'] = $article->description;
                $data['date'] = $article->date;
                $data['created_at'] = $article->created_at;
                $data['updated_at'] = $article->updated_at;

                $dr['error'] = false;
                $dr['status'] = '200';
                $data['message'] = 'success';
                $dr['data'] = $data;
                return Response::json($dr);
            } else {
                $dr['error'] = false;
                $dr['status'] = '200';
                $data['message'] = 'No Id exist';
                $dr['data'] = $data;
                return Response::json($dr);
            }
        } else {
            $data['status'] = true;
            $data['message'] = trans('messages.no_data');
            return Response::json($data);
        }
    }
}
