<?php



namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Models\ServiceProduct;
use Validator;
use Image;
use Response;


class SliderController extends Controller
{
    public function index(Request $request)
    {
        $sliders = Slider::get();
        $page_title = 'Manage Slider';
        $deactivateSliders = Slider::where('status', '=', 1)->count();
        /*dd($deactivateSliders);*/
        return view('admin/slider/index', compact('page_title', 'sliders', 'deactivateSliders'));
    }

    public function create(Request $request)
    {
        $page_title = 'Add Slider';
        return view('admin/slider/add', compact('page_title'));
    }

    public function store(Request $request)
    {
        $slider = new Slider;
        $slider->title = $request->title;
        $slider->description = $request->description;
        $slider->status = $request->get('status', 0);
        $slider->save();

        if ($request->hasFile('image')) {
            $main_image = $request->file('image');
            $fileName = time() . "slider." . $main_image->getClientOriginalExtension();
            $main_image->move(public_path('uploads/slider/'), $fileName);

            if ($slider->main_image != 'photo.jpg' && is_file(public_path('uploads/slider/' . $slider->main_image))) {
                unlink(public_path('uploads/slider/' . $slider->main_image));
            }
            $slider->image = $fileName;
            $slider->save();
        }

        return redirect()->route('admin.slider.show')->with('success', 'saved');
    }

    public function show($id)
    {
        $slider = Slider::where('id', '=', $id)->first();
        return view('admin/slider/edit', compact('slider'));
    }

    public function update(Request $request, $id)
    {

        $slider = Slider::where('id', '=', $id)->first();
        $slider->title = $request->title;
        $slider->description = $request->description;
        $slider->status = $request->get('status', 0);
        $slider->save();

        if ($request->hasFile('image')) {
            $main_image = $request->file('image');
            $fileName = time() . "slider." . $main_image->getClientOriginalExtension();
            $main_image->move(public_path('uploads/slider/'), $fileName);

            if ($slider->main_image != 'photo.jpg' && is_file(public_path('uploads/slider/' . $slider->main_image))) {
                unlink(public_path('uploads/slider/' . $slider->main_image));
            }
            $slider->image = $fileName;
            $slider->save();
        }


        return redirect()->route('admin.slider.show')->with('success', 'saved');
    }


    public function destroy($id)
    {
        $slider = Slider::find($id);
        $slider->delete();
        return redirect()->route('admin.slider.show')->with('success', 'saved');
    }



    public function activate(Slider $slider)
    {
        $sliders = Slider::get();
        foreach ($sliders as $key => $slider) {
            $slider->status = 1;
            $slider->save();
        }

        return redirect()->route('admin.slider.show')->with('success', 'saved');
    }

    public function deactivate(Slider $slider)
    {
        $sliders = Slider::get();
        foreach ($sliders as $key => $slider) {
            $slider->status = 0;
            $slider->save();
        }

        return redirect()->route('admin.slider.show')->with('success', 'saved');
    }
    public function api_sliders(Request $request)
    {
        $pass = $request->pass;

        if ($request->pass == 'all_sliders') {
            $sliders = Slider::where('status', 1)->get();
            $i = 0;
            foreach ($sliders as $slider) {
                // $url = 'https://newzoolifeapi.zoolifeshop.com/uploads/slider/';
                $url = url('uploads/slider'). "/";
                $data[$i]['id'] = $slider->id;
                $data[$i]['title'] = $slider->title;
                $data[$i]['description'] = $slider->description;
                if (!empty($slider->image)) {
                    $List = explode(',', $slider->image);
                    $data[$i]['image1'] = $url . $List[0];
                } else {
                    $data[$i]['image1'] = '';

                }

                $i++;
            }

            $dr['error'] = false;
            $dr['status'] = '200';
            $dr['message'] = 'success';
            $dr['data'] = $data;
            return Response::json($dr);
        } else {
            $data['error'] = true;
            $data['status'] = 100;
            $data['Message'] = trans('messages.no_data');
            return Response::json($data);
        }
    }
}
