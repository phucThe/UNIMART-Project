<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostCat;
use App\Models\PostPostCat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminPostController extends Controller
{

    private $private_status = 0;
    private $public_status = 1;
    private $trash = 2;

    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'post']);

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

        $posts = Post::where('title', 'LIKE', "%{$keyword}%")->paginate(10)->appends('keyword',$keyword);
        $warning = "Bạn có muốn chuyển bài viết này vào thùng rác?";

        if (!is_null($status)) {
            if ($status == $this->trash) {
                $posts = Post::onlyTrashed()->where('title', 'LIKE', "%{$keyword}%")->paginate(10)->appends('keyword',$keyword);
                $warning = "Bạn có muốn xoá vĩnh viễn bài viết này?";
                $list_act = [
                    'forceDelete' => 'Xoá khỏi thùng rác',
                    'restore' => 'Khôi phục'

                ];
            }
        }

        $count_posts_active = Post::get()->count();
        $count_posts_inactive = Post::onlyTrashed()->count();

        $count = [$count_posts_active, $count_posts_inactive];

        return view('admin.post.list', compact('posts', 'count', 'warning', 'status', 'list_act'));
    }

    function add()
    {

        $post_categories = PostCat::orderBy("name")->get();
        return view('admin.post.add', compact('post_categories'));
    }

    function store(Request $request)
    {
        $request->validate(
            [
                'title' => ['required', 'string', 'max:100'],
                'content' => ['required'],
                'post_thumb' => ['required', 'image', 'mimes:png,jpeg,jpg'],
                // 'categories_selector' => ['required'],
            ],
            [
                'required' => ':attribute không được để trống',
                'max' => 'Độ dài tối đa là 100 ký tự',
                'post_thumb.image' => ':attribute phải thuộc định dạng ảnh',
                'post_thumb.mimes' => 'Chỉ chấp nhận định dạng ảnh jpg hoặc png',
                // 'categories_selector.required' => 'Chưa chọn danh mục bài viết',
            ],
            [
                'title' => 'Tiêu đề',
                'content' => 'Nội dung',
                'post_thumb' => 'Hình bìa bài viết',
                // 'categories_selector' => "Danh mục bài viết",
            ]

        );
        $file = $request->file('post_thumb');
        $file_name = date("Y-m-d-His") . "." . $file->getClientOriginalExtension();

        $file_path = $file->storeAs(
            'post_images',
            $file_name,
            'google'
        );
        Storage::disk('google')->setVisibility($file_path, "public");
        $file_metadata = Storage::disk('google')->getAdapter()->getMetadata($file_path);
        $file_id = $file_metadata["extra_metadata"]["id"];
        $file_url = Post::getFileURL($file_id);

        $post = Post::create(
            [
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'thumb_name' => $file_path,
                'thumb_path' => $file_url,
                'user_id' => Auth::user()->id,
                'status' => $request->post_status,
            ]
        );

        $post_cat_list = $request->categories_selector;
        if(!is_null($post_cat_list)){
            $post_cat_list_qty = count($post_cat_list);
            if ($post_cat_list_qty > 0) {
                foreach ($post_cat_list as $post_cat) {
                    PostPostCat::create([
                        'post_id' => $post->id,
                        'post_cat_id' => $post_cat,
                    ]);
                }
            }

        }
        return redirect('admin/post/list')->with('status', 'Đã thêm thành công');
    }
    // End STORE




    function edit($id)
    {
        $post = Post::find($id);
        $post_post_categories = Post::find($id)->post_categories()->orderBy("name")->get();
        $post_categories = PostCat::orderBy("name")->get();
        return view('admin.post.update', compact('post', 'post_post_categories', 'post_categories', 'id'));
    }
    // END EDIT

    function update(Request $request, $id)
    {

        $request->validate(
            [
                'title' => ['required', 'string', 'max:100'],
                'content' => ['required'],
                'post_thumb' => ['image', 'mimes:png,jpeg,jpg'],
                // 'categories_selector' => ['required'],
            ],
            [
                'required' => ':attribute không được để trống',
                'max' => 'Độ dài tối đa là 100 ký tự',
                'post_thumb.image' => ':attribute phải thuộc định dạng ảnh',
                'post_thumb.mimes' => 'Chỉ chấp nhận định dạng ảnh jpg hoặc png',
                // 'categories_selector.required' => 'Chưa chọn danh mục bài viết',
            ],
            [
                'title' => 'Tiêu đề',
                'content' => 'Nội dung',
                'post_thumb' => 'Hình bìa bài viết',
                // 'categories_selector' => "Danh mục bài viết",
            ]

        );
        $post = Post::find($id)->update(
            [
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'status' => $request->post_status,
            ]
        );

        $uploaded_post_categories = $request->categories_selector;
        if (!empty($uploaded_post_categories)) {
            $uploaded_post_categories_qty = count($uploaded_post_categories);

            if ($uploaded_post_categories_qty > 0) {
                $post_categories = Post::find($id)->post_categories()->get();
                $post_categories_qty = Post::find($id)->post_categories()->count();
                if ($uploaded_post_categories_qty < $post_categories_qty) {
                    $post_categories_skip = $uploaded_post_categories_qty;
                    $post_categories_take = $post_categories_qty - $uploaded_post_categories_qty;
                    $db_deleted_post_categories = PostPostCat::where('post_id', $id)->skip($post_categories_skip)->take($post_categories_take)->get();
                    foreach ($db_deleted_post_categories as $del_post_cat) {
                        $del_post_cat->delete();
                    }
                }
                $post_post_cat_list = PostPostCat::where('post_id', $id)->get();
                for ($i = 0; $i < $uploaded_post_categories_qty; $i++) {
                    if (isset($post_categories[$i])) {
                        $post_post_cat_list[$i]->update([
                            'post_cat_id' => $uploaded_post_categories[$i],
                        ]);
                    } else {
                        PostPostCat::create([
                            'post_id' => $id,
                            'post_cat_id' => $uploaded_post_categories[$i],
                        ]);
                    }
                }
            }
        } else {
            $db_deleted_post_categories = PostPostCat::where('post_id', $id)->get();
            foreach ($db_deleted_post_categories as $del_post_cat) {
                $del_post_cat->delete();
            }
        }

        if ($request->hasFile('post_thumb')) {

            Storage::disk('google')->delete(Post::find($id)->thumb_name);

            $file = $request->file('post_thumb');
            $file_name = date("Y-m-d-His") . "." . $file->getClientOriginalExtension();

            $file_path = $file->storeAs(
                'post_images',
                $file_name,
                'google'
            );
            Storage::disk('google')->setVisibility($file_path, "public");
            $file_metadata = Storage::disk('google')->getAdapter()->getMetadata($file_path);
            $file_id = $file_metadata["extra_metadata"]["id"];
            $file_url = Post::getFileURL($file_id);

            Post::find($id)->update([
                'thumb_name' => $file_path,
                'thumb_path' => $file_url,
            ]);
        }
        return redirect('admin/post/list')->with('status', 'Đã cập nhật thành công');
    }

    // END UPDATE

    function delete($id)
    {
        $post = Post::withTrashed()->find($id);
        if ($post->trashed()) {
            Storage::disk('google')->delete($post->thumb_name);
            $post->forceDelete();
            return redirect('admin/post/list')->with('status', 'Bài viết đã bị xoá vĩnh viễn');
        }
        Post::destroy($id);
        return redirect('admin/post/list')->with('status', 'Bài viết đã được chuyển vào thùng rác');
    }
    // END DELETE

    function restore($id)
    {
        Post::onlyTrashed()->find($id)->restore();
        return redirect('admin/post/list')->with('status', 'Bài viết đã được khôi phục');
    }
    // END RESTORE

    function action(Request $request)
    {
        $list_check = $request->input('list_check');
        if ($list_check) {
            if (!empty($list_check)) {
                $act = $request->input('act');
                if ($act == 'forceDelete') {
                    $deleted_posts = Post::withTrashed()->whereIn('id', $list_check)->get();
                    // return $deleted_posts;
                    foreach ($deleted_posts as $post) {
                        Storage::disk('google')->delete($post->thumb_name);
                        $post->forceDelete();
                    }
                    // Post::withTrashed()->whereIn('id',$list_check)->forceDelete();
                    return redirect('admin/post/list')->with('status', 'Bài viết đã bị xoá vĩnh viễn');
                }
                if ($act == 'delete') {
                    Post::destroy($list_check);
                    return redirect('admin/post/list')->with('status', 'Bài viết đã được chuyển vào thùng rác');
                }
                if ($act == 'restore') {
                    Post::withTrashed()->whereIn('id', $list_check)->restore();
                    return redirect('admin/post/list')->with('status', 'Bài viết đã được khôi phục');
                }
                if ($act == "") {
                    return redirect('admin/post/list')->with('status', 'Bạn chưa chọn hành động cần thực hiện');
                }
            }
        } else
            return redirect('admin/post/list')->with('status', 'Không có phẩn tử nào được chọn');
    }
}
