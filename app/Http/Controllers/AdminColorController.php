<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Color;

class AdminColorController extends Controller
{
    //
    function list()
    {
        $color_list=Color::get();
        return view('admin.colors.list',compact('color_list'));
    }
    function store(Request $request)
    {
        $request->validate(
            [
                'name' => ['required', 'string', 'max:50'],
            ],
            [
                'required' => ':attribute không được để trống',
                'max' => 'Độ dài tối đa là 50 ký tự',
            ],
            [
                'name' => 'Tên màu',
            ]
        );
        Color::create([
            'name'=>$request->name,
        ]);
        return redirect(route('color-list'))->with('status', 'Thêm thành công');
    }

    function delete($id){
        $product_thumb_list = Color::find($id)->product_thumbs()->get();
        foreach($product_thumb_list as $product_thumb){
            $product_thumb->update([
                'color_id'=>null,
            ]);
        }
        Color::find($id)->delete();
        return redirect(route('color-list'))->with('status', 'Xoá thành công');
    }

    function createColorList(){

        $strHTML = "<option value=\"0\" hidden>Chọn màu</option>"."<option value=\"0\">Không có</option>";
        $color_list = Color::get();
        if($color_list->count() > 0){
            foreach($color_list as $color){
                $strHTML.="<option value=\"".$color->id."\">".$color->name."</option>";
            }

        }
        return $strHTML;
    }
}
