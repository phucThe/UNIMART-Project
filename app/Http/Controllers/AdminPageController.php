<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;

class AdminPageController extends Controller
{
    private $private_status = 0;
    private $public_status = 1;
    private $trash = 2;

    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'page']);

            return $next($request);
        });

        // Thiết lập session để add class active vào bên thanh sidebar
    }

    function list(Request $request, $status = null){

        $keyword = '';
        $warning = "";
        $list_act = ['delete' => 'Chuyển vào thùng rác'];

        if ($request->keyword) $keyword = $request->keyword;

        $pages = Page::where('title', 'LIKE', "%{$keyword}%")->paginate(10)->appends('keyword',$keyword);
        $warning = "Chuyển trang này vào thùng rác?";

        if (!is_null($status)) {
            if ($status == $this->trash) {
                $pages = Page::onlyTrashed()->where('title', 'LIKE', "%{$keyword}%")->paginate(10)->appends('keyword',$keyword);
                $warning = "Xoá vĩnh viễn trang này?";
                $list_act = [
                    'forceDelete' => 'Xoá khỏi thùng rác',
                    'restore' => 'Khôi phục'

                ];
            }
        }

        $count_pages_active = Page::get()->count();
        $count_pages_inactive = Page::onlyTrashed()->count();

        $count = [$count_pages_active, $count_pages_inactive];

        return view('admin.page.list', compact('pages', 'count', 'warning', 'status', 'list_act'));
    }

    function add(){
        return view('admin.page.add');
    }

    function store(Request $request)
    {

        $request->validate(
            [
                'title' => ['required', 'string', 'max:100'],
                'content' => ['required'],

            ],
            [
                'required' => ':attribute không được để trống',
                'max' => 'Độ dài tối đa là 100 ký tự'

            ],
            [
                'title' => 'Tiêu đề',
                'content' => 'Nội dung'
            ]

        );

        $page_slug = Page::slug_convert($request->title);

        $page = Page::create(
            [
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'slug' => $page_slug,
                'status'=> $request->page_status,
            ]
        );

        if(Page::where('slug',$page_slug)->count() > 1){
            $page_slug = $page_slug."-".$page->id;
            $page->update([
                'slug' => $page_slug,
            ]);
        }


        return redirect('admin/page/list')->with('status', 'Đã thêm thành công');
    }
    // End STORE

    function edit($id)
    {
        $page = Page::find($id);
        return view('admin.page.update', compact('page', 'id'));
    }
    // END EDIT

    function update(Request $request, $id)
    {
        $request->validate(
            [
                'title' => ['required', 'string', 'max:100'],
                'content' => ['required'],

            ],
            [
                'required' => ':attribute không được để trống',
                'max' => 'Độ dài tối đa là 100 ký tự'

            ],
            [
                'title' => 'Tiêu đề',
                'content' => 'Nội dung'
            ]

        );

        $page_slug = Page::slug_convert($request->title);

        if(Page::where([['slug', '=', $page_slug], ['id','<>',$id]])->count() > 0){
            $page_slug = $page_slug."-".$id;
        }

        Page::find($id)->update(
            [
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'status'=> $request->page_status,
                'slug' => $page_slug,
            ]
        );
        return redirect('admin/page/list')->with('status', 'Đã cập nhật thành công');
    }

    // END UPDATE

    function delete($id)
    {
        $page = Page::withTrashed()->find($id);
        if ($page->trashed()) {
            $page->forceDelete();
            return redirect('admin/page/list')->with('status', 'Bài viết đã bị xoá vĩnh viễn');
        }
        Page::destroy($id);
        return redirect('admin/page/list')->with('status', 'Bài viết đã được chuyển vào thùng rác');
    }
    // END DELETE

    function restore($id){
        Page::onlyTrashed()->find($id)->restore();
        return redirect('admin/page/list')->with('status', 'Bài viết đã được khôi phục');
    }
    // END RESTORE

    function action(Request $request)
    {
        $list_check = $request->input('list_check');
        if($list_check){
            if(!empty($list_check)){
                $act = $request -> input('act');
                if($act == 'forceDelete'){
                    Page::withTrashed()->whereIn('id',$list_check)->forceDelete();
                    return redirect('admin/page/list')->with('status', 'Bài viết đã bị xoá vĩnh viễn');

                }
                if($act == 'delete' ){
                    Page::destroy($list_check);
                    return redirect('admin/page/list')->with('status', 'Bài viết đã được chuyển vào thùng rác');

                }
                if($act == 'restore'){
                    Page::withTrashed()->whereIn('id',$list_check)->restore();
                    return redirect('admin/page/list')->with('status', 'Bài viết đã được khôi phục');

                }
                if($act == ""){
                    return redirect('admin/page/list')->with('status', 'Bạn chưa chọn hành động cần thực hiện');
                }
            }
        }
        else
            return redirect('admin/page/list')->with('status', 'Không có phẩn tử nào được chọn');
    }

}
