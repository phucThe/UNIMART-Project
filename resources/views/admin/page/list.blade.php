@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <div class="card">
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <h5 class="m-0 ">Danh sách trang</h5>
                <div class="form-search">
                    <form action="" class="form-search d-flex">
                        <input type="" class="form-control form-search mr-1" placeholder="Tìm kiếm" name="keyword"
                            value="{{ request()->input('keyword') }}">
                        <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="analytic">
                    <a href="{{ route('page_list') }}" class="text-primary">Tất cả<span
                            class="text-muted">({{ $count[0] }})</span></a>
                    <a href="{{ route('page_list', 2) }}" class="text-primary">Thùng rác<span
                            class="text-muted">({{ $count[1] }})</span></a>
                </div>
                <form action="{{ url('admin/page/action') }}">
                    <div class="form-action form-inline py-3">
                        <select class="form-control mr-1" id="" name="act">
                            <option value="" hidden>Chọn</option>
                            @foreach ($list_act as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                        <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary">
                    </div>
                    <table class="table table-striped table-checkall">
                        <thead>
                            <tr>
                                <th scope="col">
                                    <input name="checkall" type="checkbox">
                                </th>
                                <th scope="col">#</th>
                                <th scope="col">Tiêu đề</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Ngày tạo</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>

                            @if ($pages->count() > 0)
                                @php
                                    $i = 0;
                                @endphp
                                @foreach ($pages as $page)
                                    @php
                                        $i++;
                                    @endphp
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="list_check[]" value="{{ $page->id }}">
                                        </td>
                                        <td scope="row">{{ $i }}</td>
                                        <td>
                                            <a href="">{{ $page->title }}</a>
                                        </td>
                                        <td>
                                            @if ($page->status == 0)
                                                <span class="badge badge-dark">Riêng tư</span>
                                            @endif
                                            @if ($page->status == 1)
                                                <span class="badge badge-success">Công khai</span>
                                            @endif
                                        </td>
                                        <td>{{ $page->created_at }}</td>
                                        <td>
                                            @if ($status == 2)
                                                <a href="{{ route('page_restore', $page->id) }}"
                                                    class="btn btn-secondary btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Khôi phục">
                                                    <i class="fa-solid fa-clock-rotate-left"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('page_edit', $page->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Chỉnh sửa">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            @endif
                                            <a href="{{ route('page_delete', $page->id) }}"
                                                class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                data-toggle="tooltip" data-placement="top" title="Xoá"
                                                onclick="return confirm('{{ $warning }}')">
                                                <i class="fa fa-trash"></i>
                                            </a>

                                        </td>

                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class=" bg-white">
                                        <p class="text-danger">Không tìm thấy kết quả</p>

                                    </td>
                                </tr>
                            @endif


                        </tbody>
                    </table>

                </form>
                {{ $pages->links() }}
            </div>
        </div>
    </div>
@endsection
