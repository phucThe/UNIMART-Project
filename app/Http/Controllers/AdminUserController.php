<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Role;
use App\Models\UserRole;


class AdminUserController extends Controller
{

    private $administrator_role = 1;
    private $developer_role = 2;
    private $normal_user_role = 3;

    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'user']);

            return $next($request);
        });
    }

    //
    function list(Request $request)
    {

        $status = $request->input('status');

        $list_act = ['delete' => 'Xoá tạm thời'];

        $keyword = "";
        if ($request->input('keyword')) {
            $keyword = $request->input('keyword');
        }
        if ($status == 'inactive') {

            $list_act = [
                'restore' => 'Khôi phục',
                'forceDelete' => 'Xoá vĩnh viễn',
            ];
            $users = User::onlyTrashed()->where('name', 'LIKE', "%{$keyword}%")->paginate(10)->appends('keyword', $keyword);
        } else {

            $users = User::where('name', 'LIKE', "%{$keyword}%")->paginate(10)->appends('keyword', $keyword);
        }
        // dd($users->total);
        $count_users_active = User::count(); // Tổng số bản ghi ko bao gồm bản ghi ko nằm trong thùng rác
        $count_users_inactive = User::onlyTrashed()->count();

        $count = [$count_users_active, $count_users_inactive];
        return view('admin.user.list', compact('users', 'count', 'list_act', 'status'));
    }

    function add()
    {
        $roles = Role::get();
        return view('admin.user.add')->with('roles', $roles);
    }

    function store(Request $request)
    {
        $request->validate(
            [
                'name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z0-9_\. aAàÀảẢãÃáÁạẠăĂằẰẳẲẵẴắẮặẶâÂầẦẩẨẫẪấẤậẬbBcCdDđĐeEèÈẻẺẽẼéÉẹẸêÊềỀểỂễỄếẾệỆ fFgGhHiIìÌỉỈĩĨíÍịỊjJkKlLmMnNoOòÒỏỎõÕóÓọỌôÔồỒổỔỗỖốỐộỘơƠờỜởỞỡỠớỚợỢpPqQrRsStTu UùÙủỦũŨúÚụỤưƯừỪửỬữỮứỨựỰvVwWxXyYỳỲỷỶỹỸýÝỵỴzZ]{6,32}$/'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'regex:/^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/'],
                'password' => ['required', 'string', 'confirmed', 'regex:/^[A-Za-z0-9_\.!@#$%^&*()]{6,32}$/', 'min:6',],
                'password_confirmation' => ['required'],
                'gender' => ['required'],
                'phone_numbers' => ['required', 'regex:/^[0-9]{8,11}$/'],
                'birth_date' => ['required'],
            ],
            [
                'required' => ':attribute không được để trống',
                'password.regex' => 'Mật khẩu chỉ được phép chứa chữ thường, chữ in hoa, chữ số và các ký tự _\.!@#$%^&*() và có độ dài từ 6 đến 32 ký tự',
                'min' => ':attribute có độ dài ít nhất :min ký tự',
                'max' => ':attribute có độ dài tối đa :max ký tự',
                'confirmed' => 'Xác nhận mật khẩu không thành công',
                'regex' => ":attribute không đúng định dạng",
                'string' => ":attribute phải ở dạng ký tự"
            ],
            [
                'name' => 'Tên người dùng',
                'email' => 'Email',
                'password' => 'Mật khẩu',
                'password_confirmation' => 'Xác nhận mật khẩu',
                'phone_numbers' => 'Số điện thoại',
                'gender' => 'Giới tính',
                'birth_date' => "Ngày sinh",
            ]
        );
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'gender' => $request->input('gender'),
            'phone_numbers' => $request->input('phone_numbers'),
            'birth_date' => date('Y-m-d', strtotime($request->input('birth_date'))),
        ]);

        if ($request->has('roles_selector')) {
            if ($request->roles_selector != 0) {
                UserRole::create([
                    'user_id' => $user->id,
                    'role_id' => $request->roles_selector,
                ]);
            } else {
                UserRole::create([
                    'user_id' => $user->id,
                    'role_id' => $this->normal_user_role,
                ]);
            }
        } else {
            UserRole::create([
                'user_id' => $user->id,
                'role_id' => $this->normal_user_role,
            ]);
        }

        return redirect('admin/user/list')->with('status', 'Đã thêm thành công');

    }


    function delete($id)
    {

        if (Auth::id() != $id) {
            $user = User::withTrashed()->find($id);
            if ($user->trashed()) {
                $user->forceDelete();
                return redirect('admin/user/list')->with('status', 'Đã xoá người dùng ra khỏi hệ thống');
            } else {
                $user->destroy($id);
                return redirect('admin/user/list')->with('status', 'Bạn đã xoá người dùng thành công');
            }
        } else {
            return redirect('admin/user/list')->with('status', 'Bạn không thể thực hiện thao tác này trên tài khoản của bạn');
        }
    }

    function restore($id)
    {
        User::onlyTrashed()->find($id)->restore();
        return redirect('admin/user/list')->with('status', 'Người dùng đã được khôi phục');
    }
    // END RESTORE

    function action(Request $request)
    {
        $list_check = $request->input('list_check');
        if ($list_check) {
            foreach ($list_check as $k => $id) {
                // Loại bỏ phần tử có id người dùng đang đăng nhập
                if (Auth::id() == $id) {
                    unset($list_check[$k]);
                }
            }

            if (!empty($list_check)) {
                $act = $request->input('act');
                if (!empty($act)) {
                    if ($act == 'forceDelete') {
                        User::withTrashed()
                            ->whereIn('id', $list_check)
                            ->forceDelete();
                        return redirect('admin/user/list')->with('status', 'Đã xoá người dùng ra khỏi hệ thống');
                    }
                    if ($act == 'delete') {
                        User::destroy($list_check);
                        return redirect('admin/user/list')->with('status', 'Bạn đã xoá người dùng thành công');
                    }
                    if ($act == 'restore') {
                        User::withTrashed()
                            ->whereIn('id', $list_check)
                            ->restore();
                        return redirect('admin/user/list')->with('status', 'Người dùng đã được khôi phục');
                    }
                } else return redirect('admin/user/list')->with('status', 'Bạn chưa chọn hành động cần thực hiện');
            }
            return redirect('admin/user/list')->with('status', 'Bạn không thể thực hiện thao tác này trên tài khoản của bạn');
        }
        return redirect('admin/user/list')->with('status', 'Không có phần tử nào được chọn');
    }

    function edit($id)
    {
        $user = User::find($id);
        $roles = Role::get();
        $user_role = UserRole::where('user_id', $id)->first();
        return view('admin.user.edit', compact('user', 'id', 'roles', 'user_role'));
    }

    public function update(Request $request, $id)
    {

        $old_password_validation = [];
        $password_validation = [];
        $password_confirm_validation = [];
        if(!is_null($request->old_password)){
            $old_password_validation = ['required', 'string', 'regex:/^[A-Za-z0-9_\.!@#$%^&*()]{6,32}$/', 'min:6',];
            $password_validation = ['required', 'string', 'confirmed', 'regex:/^[A-Za-z0-9_\.!@#$%^&*()]{6,32}$/', 'min:6',];
            $password_confirm_validation = ['required'];
        }

        $request->validate(
            [
                'name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z0-9_\. aAàÀảẢãÃáÁạẠăĂằẰẳẲẵẴắẮặẶâÂầẦẩẨẫẪấẤậẬbBcCdDđĐeEèÈẻẺẽẼéÉẹẸêÊềỀểỂễỄếẾệỆ fFgGhHiIìÌỉỈĩĨíÍịỊjJkKlLmMnNoOòÒỏỎõÕóÓọỌôÔồỒổỔỗỖốỐộỘơƠờỜởỞỡỠớỚợỢpPqQrRsStTu UùÙủỦũŨúÚụỤưƯừỪửỬữỮứỨựỰvVwWxXyYỳỲỷỶỹỸýÝỵỴzZ]{6,32}$/'],
                // 'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'regex:/^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/'],
                'old_password' => $old_password_validation,
                'password' => $password_validation,
                'password_confirmation' => $password_confirm_validation,
                'gender' => ['required'],
                'phone_numbers' => ['required', 'regex:/^[0-9]{8,11}$/'],
                'birth_date' => ['required'],


            ],
            [
                'required' => ':attribute không được để trống',
                'password.regex' => 'Mật khẩu chỉ được phép chứa chữ thường, chữ in hoa, chữ số và các ký tự _\.!@#$%^&*() và có độ dài từ 6 đến 32 ký tự',
                'old_password.regex' => 'Mật khẩu chỉ được phép chứa chữ thường, chữ in hoa, chữ số và các ký tự _\.!@#$%^&*() và có độ dài từ 6 đến 32 ký tự',
                'min' => ':attribute có độ dài ít nhất :min ký tự',
                'max' => ':attribute có độ dài tối đa :max ký tự',
                'confirmed' => 'Xác nhận mật khẩu không thành công',
                'regex' => ":attribute không đúng định dạng",
                'string' => ":attribute phải ở dạng ký tự"
            ],
            [
                'name' => 'Tên người dùng',
                'email' => 'Email',
                'password' => 'Mật khẩu',
                'old_password' => 'Mật khẩu',
                'password_confirmation' => 'Xác nhận mật khẩu',
                'phone_numbers' => 'Số điện thoại',
                'gender' => 'Giới tính',
                'birth_date' => "Ngày sinh",
            ]
        );

        if (!is_null($request->old_password)) {
            if (Auth::user()->id != $id) {
                return redirect()->back()->with('message', 'Bạn không có quyền thay đổi mật khẩu đăng nhập của người dùng này');
            } else {
                $old_password = User::find($id)->password;
                if (Hash::check($request->old_password, $old_password)) {
                    User::where('id', $id)->update([
                        'name' => $request->input('name'),
                        'password' => Hash::make($request->input('password')),
                        'gender' => $request->input('gender'),
                        'phone_numbers' => $request->input('phone_numbers'),
                        'birth_date' => date('Y-m-d', strtotime($request->input('birth_date'))),
                    ]);

                    return redirect('admin/user/list')->with('status', 'Đã cập nhật thành công');
                } else {
                    return redirect()->back()->with('message', 'Mật khẩu cũ không đúng');
                }

            }
        } else {
            User::where('id', $id)->update([
                'name' => $request->input('name'),
                'gender' => $request->input('gender'),
                'phone_numbers' => $request->input('phone_numbers'),
                'birth_date' => date('Y-m-d', strtotime($request->input('birth_date'))),
            ]);

            if ($request->has('roles_selector')) {
                if ($request->roles_selector != 0) {
                    if(UserRole::where('user_id', $id)->count() == 0){
                        UserRole::create([
                            'user_id' => $id,
                            'role_id' => $request->roles_selector,
                        ]);
                    }else
                    UserRole::where('user_id', $id)->first()->update([
                        'role_id' => $request->roles_selector,
                    ]);
                }
            }
            return redirect('admin/user/list')->with('status', 'Đã cập nhật thành công');

        }

    }
}
