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
                <h5 class="m-0 ">Danh sách đơn hàng</h5>
                <div class="form-search form-inline">
                    <form action="" class="form-search d-flex">
                        <input type="" class="form-control form-search mr-1" placeholder="Tìm kiếm" name="keyword">
                        <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="analytic">
                    <a href="{{ route('order_list') }}" class="text-primary">Tất cả<span
                            class="text-muted">({{ $count[0] }})</span></a>
                    <a href="{{ route('order_list', 2) }}" class="text-primary">Đang xử lý<span
                            class="text-muted">({{ $count[2] }})</span></a>
                    <a href="{{ route('order_list', 3) }}" class="text-primary">Đang vận chuyển<span
                            class="text-muted">({{ $count[3] }})</span></a>
                    <a href="{{ route('order_list', 4) }}" class="text-primary">Đã hoàn thành<span
                            class="text-muted">({{ $count[4] }})</span></a>
                    <a href="{{ route('order_list', 0) }}" class="text-primary">Đã huỷ<span
                            class="text-muted">({{ $count[1] }})</span></a>
                    <a href="{{ route('order_list', 5) }}" class="text-primary">Thùng rác<span
                            class="text-muted">({{ $count[5] }})</span></a>
                </div>
                <form action="{{ route('order_action') }}">

                    <div class="form-action form-inline py-3">
                        <select class="form-control mr-1" id="" name="act">
                            <option value="" hidden>Chọn</option>
                            @foreach ($list_act as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                        <input type="submit" name="act_btn" value="Áp dụng" class="btn btn-primary">
                    </div>
                    <table class="table table-striped table-checkall">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" name="checkall">
                                </th>
                                <th scope="col">#</th>
                                <th scope="col">Mã</th>
                                <th scope="col">Khách hàng</th>
                                <th scope="col">Số điện thoại</th>
                                <th scope="col">Tổng tiền</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Thời gian</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>

                            @if ($orders->count() > 0)
                                @php
                                    $i = 0;
                                @endphp
                                @foreach ($orders as $order)
                                    @php
                                        $i++;
                                    @endphp
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="list_check[]" value="{{ $order->id }}">
                                        </td>
                                        <td>{{ $i }}</td>
                                        <td>{{ $order->id }}</td>
                                        <td>
                                            {{ $order->fullname }}
                                        </td>
                                        <td>
                                            {{ $order->phone }}
                                        </td>
                                        <td>{{ number_format($order->total, 0, '') . 'đ' }}</td>
                                        <td>
                                            @if ($order->status == 2)
                                                <span class="badge badge-warning">Đang xử lý</span>
                                            @endif
                                            @if ($order->status == 3)
                                                <span class="badge badge-info">Đang vận chuyển</span>
                                            @endif
                                            @if ($order->status == 4)
                                                <span class="badge badge-success">Đã hoàn thành</span>
                                            @endif
                                            @if ($order->status == 0)
                                                <span class="badge badge-dark">Đã huỷ</span>
                                            @endif

                                        </td>
                                        <td>{{ $order->created_at }}</td>
                                        <td>
                                            @if ($status != 5)
                                                <a href="{{ route('order_detail', $order->id) }}"
                                                    class="btn btn-primary btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Chi tiết">
                                                    <i class="fa-solid fa-ellipsis"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('order_restore', $order->id) }}"
                                                    class="btn btn-secondary btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Khôi phục">
                                                    <i class="fa-solid fa-clock-rotate-left"></i>
                                                </a>
                                            @endif
                                            <a href="{{ route('order_delete', $order->id) }}"
                                                class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                data-toggle="tooltip" data-placement="top"
                                                onclick="return confirm('{{ $warning }}')" title="Xoá">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="9" class=" bg-white">
                                        <p class="text-danger">Không tìm thấy kết quả</p>

                                    </td>
                                </tr>
                            @endif

                        </tbody>
                    </table>
                    {{ $orders->links() }}
                </form>
            </div>
        </div>
    </div>
@endsection
