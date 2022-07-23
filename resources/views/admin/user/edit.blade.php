@extends('layouts.admin')

@section('content')
<div id="content" class="container-fluid">

    @if (session('message'))
    <div class="alert alert-danger">
        {{ session('message') }}
    </div>
    @endif
    <div class="card">
        <div class="card-header font-weight-bold">
            Chỉnh sửa thông tin người dùng
        </div>
        <div class="card-body">

            <form action="{{ url('admin/user/update', $id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Họ và tên</label>
                    <input class="form-control" type="text" name="name" id="name" value="{{ $user->name }}">
                    @error('name')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="birth_date">Ngày sinh</label>
                    <input class="form-control" type="date" name="birth_date" id="birth_date" value="{{ $user->birth_date }}">
                    @error('birth_date')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="">Chọn giới tính</label>
                    <select class="form-control" id="" name="gender">
                        <option value="male" selected>Nam</option>
                        <option value="female">Nữ</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input class="form-control" type="text" name="email" id="email" value="{{ $user->email }}" disabled>
                    @error('email')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="phone_numbers">Số điện thoại</label>
                    <input class="form-control" maxlength="11" type="text" name="phone_numbers" id="phone_numbers" value="{{ $user->phone_numbers }}">
                    @error('phone_numbers')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="old_password">Mật khẩu cũ</label>
                    <input class="form-control" type="password" name="old_password" id="old_password">
                    @error('old_password')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Mật khẩu mới</label>
                    <input class="form-control" type="password" name="password" id="password">
                    @error('password')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password-confirm">Xác nhận mật khẩu</label>
                    <input class="form-control" type="password" name="password_confirmation" id="password-confirm">
                    @error('password_confirmation')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                @if (Auth::user()->id != $id)
                <div class="form-group">
                    <label for="roles-selector">Nhóm quyền</label>
                    <select class="form-control" id="roles-selector" name="roles_selector">
                        <option value="0" hidden>Chọn quyền</option>
                        @foreach ($roles as $role)
                        <option value="{{ $role->id }}" @if (!is_null($user_role)) @if ($user_role->role_id == $role->id) @selected(true) @endif @endif>
                            {{ $role->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @endif


                <button type="submit" name="btn-update" class="btn btn-primary" value="Cập nhật">Cập nhật</button>
            </form>
        </div>
    </div>
</div>
@endsection
