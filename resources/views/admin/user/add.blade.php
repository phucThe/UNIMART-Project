@extends('layouts.admin')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">

            <div class="card-header font-weight-bold">
                Thêm người dùng
            </div>
            <div class="card-body">

                <form action="{{ url('admin/user/store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Họ và tên</label>
                        <input class="form-control" type="text" name="name" id="name"
                            value="{{ old('name', request()->name) }}">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="birth_date">Ngày sinh</label>
                        <input class="form-control" type="date" name="birth_date" id="birth_date"
                            value="{{ old('birth_date', request()->birth_date) }}">
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
                        <input class="form-control" type="text" name="email" id="email"
                            value="{{ old('email', request()->email) }}">
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="phone_numbers">Số điện thoại</label>
                        <input class="form-control" maxlength="11" type="text" name="phone_numbers" id="phone_numbers"
                            value="{{ old('phone_numbers', request()->phone_numbers) }}">
                        @error('phone_numbers')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password">Mật khẩu</label>
                        <input class="form-control" type="password" name="password" id="password">
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password-confirm">Xác nhận mật khẩu</label>
                        <input class="form-control" type="password" name="password_confirmation" id="password-confirm">
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="roles-selector">Nhóm quyền</label>
                        <select class="form-control" id="roles-selector" name="roles_selector">
                            <option value="0" hidden>Chọn quyền</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" name="btn-add" class="btn btn-primary" value="Thêm mới">Thêm mới</button>
                </form>
            </div>
        </div>
    </div>
@endsection
