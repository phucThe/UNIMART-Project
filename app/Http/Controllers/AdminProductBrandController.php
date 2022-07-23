<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductBrand;

class AdminProductBrandController extends Controller
{
    //
    function list()
    {
        $product_brand_list=ProductBrand::get();
        return view('admin.product.brand',compact('product_brand_list'));
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
                'name' => 'Tên thương hiệu',
            ]
        );
        ProductBrand::create([
            'name'=>$request->name,
        ]);
        return redirect(route('product-brand-list'))->with('status', 'Thêm thành công');
    }

    function delete($id){
        $product_count = ProductBrand::find($id)->products()->count();
        if($product_count>0){
            $product_list = ProductBrand::find($id)->products()->get();
            foreach($product_list as $product){
                $product->update([
                    'brand_id'=>null,
                ]);
            }
        }
        ProductBrand::find($id)->delete();
        return redirect(route('product-brand-list'))->with('status', 'Xoá thành công');
    }
}
