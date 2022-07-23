@extends('layouts.admin')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <h5 class="m-0 ">Danh sách thành viên</h5>
                <div class="d-flex">
                    <form action="" class="form-search d-flex">
                        <input type="text" class="form-control form-search mr-1" placeholder="Tìm kiếm" name="keyword"
                            value="{{ request()->input('keyword') }}">
                        <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="analytic">
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'active']) }}" class="text-primary">Tất cả<span
                            class="text-muted">({{ $count[0] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'inactive']) }}" class="text-primary">Vô hiệu
                        hoá<span class="text-muted">({{ $count[1] }})</span></a>
                </div>
                <form action="{{ url('admin/user/action') }}" method="">
                    <div class="form-action form-inline py-3">
                        <select class="form-control mr-1" id="" name='act'>
                            <option hidden='true' value="">Chọn</option>
                            @foreach ($list_act as $k => $act)
                                <option value="{{ $k }}">{{ $act }}</option>
                            @endforeach
                            <!-- <option value="restore">Khôi phục</option> -->
                        </select>
                        <input type="submit" name="btn-apply" value="Áp dụng" class="btn btn-primary">
                    </div>
                    <table class="table table-striped table-checkall">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" name="checkall">
                                </th>
                                <th scope="col">#</th>
                                <th scope="col">Họ tên</th>
                                <th scope="col">Email</th>
                                <th scope="col">Quyền</th>
                                <th scope="col">Ngày tạo</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($users->total() > 0)
                                @php
                                    $t = 0;
                                @endphp
                                @foreach ($users as $user)
                                    @php
                                        $t++;
                                    @endphp
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="list_check[]" value="{{ $user->id }}">
                                        </td>
                                        <th scope="row">{{ $t }}</th>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        @if ($user->roles()->count() == 0)
                                            <td>Chưa cấp quyền</td>
                                        @else
                                            <td>{{ $user->roles()->first()->name }}</td>
                                        @endif
                                        <td>{{ $user->created_at }}</td>
                                        <td>
                                            @if ($status == 'inactive')
                                                <a href="{{ route('user_restore', $user->id) }}"
                                                    class="btn btn-secondary btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Khôi phục">
                                                    <i class="fa-solid fa-clock-rotate-left"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('user_edit', $user->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            @endif

                                            @if ($user->id != Auth::user()->id)
                                                <a href="{{ route('delete_user', $user->id) }}"
                                                    onclick="return confirm('Bạn có chắc chắn muốn xoá người dùng này không?')"
                                                    class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class=" bg-white">
                                        <p class="text-danger">Không tìm thấy bản ghi</p>

                                    </td>
                                </tr>
                            @endif


                        </tbody>
                    </table>

                </form>
                {{ $users->links() }}
            </div>
        </div>
    </div>

@endsection
