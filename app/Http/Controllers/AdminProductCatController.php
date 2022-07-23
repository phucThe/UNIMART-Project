<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductCat;
use Illuminate\Support\Facades\Auth;

class AdminProductCatController extends Controller
{
    //


    function list()
    {
        $product_cat_list = ProductCat::get_cat_tree();
        return view('admin.product.cat', compact('product_cat_list'));
    }
    function store(Request $request)
    {
        $request->validate(
            [
                'name' => ['required', 'string', 'max:100'],
                'slug' => ['required', 'string', 'max:100'],
                'parent_cat' => ['required'],
            ],
            [
                'required' => ':attribute không được để trống',
                'max' => 'Độ dài tối đa là 100 ký tự',
            ],
            [
                'name' => 'Tên danh mục',
                'slug' => 'Đường dẫn danh mục',
                'parent_cat' => 'Danh mục',
            ]

        );
        ProductCat::create(
            [
                'name' => $request->name,
                'slug' => ProductCat::slug_convert($request->slug),
                'parent_id' => $request->parent_cat,
                'user_id' => Auth::user()->id,
            ]
        );

        return redirect('admin/product/product-cat/list')->with('status', 'Thêm danh mục cho sản phẩm thành công');
    }

    function edit($id)
    {
        $product_cat = ProductCat::find($id);
        $product_cat_list = ProductCat::get_cat_tree();
        return view('admin.product.cat-update', compact('product_cat', 'product_cat_list'));
    }

    function update(Request $request, $id)
    {
        $request->validate(
            [
                'name' => ['required', 'string', 'max:100'],
                'slug' => ['required', 'string', 'max:100'],
                'parent_cat' => ['required'],
            ],
            [
                'required' => ':attribute không được để trống',
                'max' => 'Độ dài tối đa là 100 ký tự',
            ],
            [
                'name' => 'Tên danh mục',
                'slug' => 'Đường dẫn danh mục',
                'parent_cat' => 'Danh mục',
            ]

        );
        ProductCat::find($id)->update(
            [
                'name' => $request->name,
                'slug' => ProductCat::slug_convert($request->slug),
                'parent_id' => $request->parent_cat,
                'user_id' => Auth::user()->id,
            ]
        );
        return redirect('admin/product/product-cat/list')->with('status', 'Cập nhật danh mục cho sản phẩm thành công');
    }

    function delete($id){
        $product_category = ProductCat::find($id);
        $product_list = $product_category->products()->get();
        foreach($product_list as $product){
            $product->update([
                'product_cat_id'=>null,
            ]);
        }
        $child_categories_qty = ProductCat::where('parent_id',$id)->count();
        if($child_categories_qty>0){
            $child_categories = ProductCat::where('parent_id',$id)->get();
            foreach($child_categories as $child_cat){
                $child_cat->update([
                    'parent_id'=>$product_category->parent_id,
                ]);
            }
        }
        $product_category->delete();
        return redirect('admin/product/product-cat/list')->with('status', 'Danh mục sản phẩm được xoá thành công');
    }
}
