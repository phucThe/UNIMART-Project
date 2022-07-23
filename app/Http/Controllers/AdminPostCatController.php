<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PostCat;
use Illuminate\Support\Facades\Auth;

class AdminPostCatController extends Controller
{
    //
    function list()
    {
        $post_cat_list = PostCat::get_cat_tree();
        return view('admin.post.cat', compact('post_cat_list'));
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
        PostCat::create(
            [
                'name' => $request->name,
                'slug' => PostCat::slug_convert($request->slug),
                'parent_id' => $request->parent_cat,
                'user_id' => Auth::user()->id,
            ]
        );

        return redirect('admin/post/post-cat/list')->with('status', 'Thêm danh mục cho bài viết thành công');
    }

    function edit($id)
    {
        $post_cat = PostCat::find($id);
        $post_cat_list = PostCat::get_cat_tree();
        return view('admin.post.cat-update', compact('post_cat', 'post_cat_list'));
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
        PostCat::find($id)->update(
            [
                'name' => $request->name,
                'slug' => PostCat::slug_convert($request->slug),
                'parent_id' => $request->parent_cat,
                'user_id' => Auth::user()->id,
            ]
        );
        return redirect('admin/post/post-cat/list')->with('status', 'Cập nhật danh mục cho bài viết thành công');
    }

    function delete($id){
        $child_categories_qty = PostCat::where('parent_id',$id)->count();
        $post_category = PostCat::find($id);
        $post_list = $post_category->posts()->get();
        if($child_categories_qty>0){
            $child_categories = PostCat::where('parent_id',$id)->get();
            foreach($child_categories as $child_cat){
                $child_cat->update([
                    'parent_id'=>$post_category->parent_id,
                ]);
            }
        }
        $post_category->delete();
        return redirect('admin/post/post-cat/list')->with('status', 'Danh mục bài viết được xoá thành công');
    }
}
