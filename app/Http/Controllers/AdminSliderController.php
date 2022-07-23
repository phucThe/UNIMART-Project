<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminSliderController extends Controller
{
    private const STATUS = ['Riêng tư', 'Công khai'];

    private $private_status = 0;
    private $public_status = 1;
    private $trash = 2;

    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'slider']);

            return $next($request);
        });

        // Thiết lập session để add class active vào bên thanh sidebar
    }

    function list(Request $request, $status = null)
    {

        $keyword = '';
        $warning = "";
        $list_act = ['delete' => 'Chuyển vào thùng rác'];

        if ($request->keyword) $keyword = $request->keyword;

        $sliders = Slider::where('name', 'LIKE', "%{$keyword}%")->paginate(10)->appends('keyword', $keyword);
        $warning = "Bạn có muốn chuyển slider này vào thùng rác?";

        if (!is_null($status)) {
            if ($status == $this->trash) {
                $sliders = Slider::onlyTrashed()->where('name', 'LIKE', "%{$keyword}%")->paginate(10)->appends('keyword', $keyword);
                $warning = "Bạn có muốn xoá vĩnh viễn slider này?";
                $list_act = [
                    'forceDelete' => 'Xoá khỏi thùng rác',
                    'restore' => 'Khôi phục'

                ];
            }
        }

        $count_sliders_active = Slider::get()->count();
        $count_sliders_inactive = Slider::onlyTrashed()->count();

        $count = [$count_sliders_active, $count_sliders_inactive];

        return view('admin.slider.list', compact('sliders', 'count', 'warning', 'status', 'list_act'));
    }

    function add()
    {
        return view('admin.slider.add');
    }

    function store(Request $request)
    {

        $request->validate(
            [
                'slider_name' => ['required','string', 'max:100'],
                'slider_image' => ['required', 'image', 'mimes:png,jpeg,jpg']
            ],
            [
                'required' => ':attribute không được để trống',
                'max' => 'Độ dài tối đa là 100 ký tự',
                'slider_image.image' => ':attribute phải thuộc định dạng ảnh',
                'slider_image.mimes' => 'Chỉ chấp nhận định dạng ảnh jpg hoặc png',
            ],
            [
                'slider_name' => 'Tên Slider',
                'slider_desc' => 'Mô tả Slider',
                'slider_image' => 'Hình ảnh Slider',
            ]

        );

        $file = $request->file('slider_image');
        $file_name = date("Y-m-d-His") . "." . $file->getClientOriginalExtension();
        $file_path = $file->storeAs(
            'slider_images',
            $file_name,
            'google'
        );
        Storage::disk('google')->setVisibility($file_path, "public");
        $file_metadata = Storage::disk('google')->getAdapter()->getMetadata($file_path);
        $file_id = $file_metadata["extra_metadata"]["id"];
        $file_url = Slider::getFileURL($file_id);

        Slider::create(
            [
                'name' => $request->input('slider_name'),
                'desc' => $request->input('slider_desc'),
                'img_name' => $file_path,
                'img_path' => $file_url,
                'link' => $request->slider_link,
                'status' => $request->slider_status,
            ]
        );
        // return $file_metadata;
        return redirect('admin/slider/list')->with('status', 'Đã thêm thành công');
    }
    // End STORE




    function edit($id)
    {
        $slider = Slider::find($id);
        return view('admin.slider.update', compact('slider', 'id'));
    }
    // END EDIT

    function update(Request $request, $id)
    {
        $request->validate(
            [
                'slider_name' => ['required', 'string', 'max:100'],
                'slider_image' => ['image', 'mimes:png,jpeg,jpg']
            ],
            [
                'required' => ':attribute không được để trống',
                'max' => 'Độ dài tối đa là 100 ký tự',
                'slider_image.image' => ':attribute phải thuộc định dạng ảnh',
                'slider_image.mimes' => 'Chỉ chấp nhận định dạng ảnh jpg hoặc png',
            ],
            [
                'slider_name' => 'Tên Slider',
                'slider_desc' => 'Mô tả Slider',
                'slider_image' => 'Hình ảnh Slider',
            ]

        );


        $slider = Slider::find($id)->update(
            [
                'name' => $request->input('slider_name'),
                'desc' => $request->input('slider_desc'),
                'link' => $request->slider_link,
                'status' => $request->slider_status,
            ]
        );
        if ($request->hasFile('slider_image')) {

            Storage::disk('google')->delete(Slider::find($id)->img_name);


            $file = $request->file('slider_image');
            $file_name = date("Y-m-d-His") . "." . $file->getClientOriginalExtension();
            $file_path = $file->storeAs(
                'slider_images',
                $file_name,
                'google'
            );
            Storage::disk('google')->setVisibility($file_path, "public");
            $file_metadata = Storage::disk('google')->getAdapter()->getMetadata($file_path);
            $file_id = $file_metadata["extra_metadata"]["id"];
            $file_url = Slider::getFileURL($file_id);


            Slider::find($id)->update([
                'img_name' => $file_path,
                'img_path' => $file_url,
            ]);
        }
        return redirect('admin/slider/list')->with('status', 'Đã cập nhật thành công');
    }

    // END UPDATE

    function delete($id)
    {
        $slider = Slider::withTrashed()->find($id);
        if ($slider->trashed()) {
            Storage::disk('google')->delete($slider->img_name);
            $slider->forceDelete();
            return redirect('admin/slider/list')->with('status', 'Slider đã bị xoá vĩnh viễn');
        }
        Slider::destroy($id);
        return redirect('admin/slider/list')->with('status', 'Slider đã được chuyển vào thùng rác');
    }
    // END DELETE

    function restore($id)
    {
        Slider::onlyTrashed()->find($id)->restore();
        return redirect('admin/slider/list')->with('status', 'Slider đã được khôi phục');
    }
    // END RESTORE

    function action(Request $request)
    {
        $list_check = $request->input('list_check');
        if ($list_check) {
            if (!empty($list_check)) {
                $act = $request->input('act');
                if ($act == 'forceDelete') {
                    $deleted_sliders = Slider::withTrashed()->whereIn('id', $list_check)->get();
                    foreach ($deleted_sliders as $slider) {
                        Storage::disk('google')->delete($slider->img_name);
                        $slider->forceDelete();
                    }
                    return redirect('admin/slider/list')->with('status', 'Slider đã bị xoá vĩnh viễn');
                }
                if ($act == 'delete') {
                    Slider::destroy($list_check);
                    return redirect('admin/slider/list')->with('status', 'Slider đã được chuyển vào thùng rác');
                }
                if ($act == 'restore') {
                    Slider::withTrashed()->whereIn('id', $list_check)->restore();
                    return redirect('admin/slider/list')->with('status', 'Slider đã được khôi phục');
                }
                if ($act == "") {
                    return redirect('admin/slider/list')->with('status', 'Bạn chưa chọn hành động cần thực hiện');
                }
            }
        } else
            return redirect('admin/slider/list')->with('status', 'Không có phẩn tử nào được chọn');
    }
}
